<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanSmsReminder;
use App\Models\User;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoanSmsReminderController extends Controller
{
    protected $smsService;

    public function __construct(SmsNotificationService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display a listing of loan SMS reminders
     */
    public function index(Request $request)
    {
        $query = LoanSmsReminder::with(['loan', 'user', 'sentBy'])
            ->orderBy('due_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by send status
        if ($request->filled('status')) {
            $query->where('send_status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('due_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('due_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('loan_number', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $reminders = $query->paginate(50);

        // Statistics
        $stats = [
            'total' => LoanSmsReminder::count(),
            'pending' => LoanSmsReminder::where('send_status', 'pending')->count(),
            'sent' => LoanSmsReminder::where('send_status', 'sent')->count(),
            'failed' => LoanSmsReminder::where('send_status', 'failed')->count(),
            'overdue' => LoanSmsReminder::where('days_overdue', '>', 0)->count(),
        ];

        return view('admin.loans.sms-reminders.index', compact('reminders', 'stats'));
    }

    /**
     * Generate reminders for loans with due payments
     */
    public function generate(Request $request)
    {
        try {
            $daysBeforeDue = $request->input('days_before', 3); // Default 3 days before due date
            $includeOverdue = $request->has('include_overdue') ? true : false;
            
            $today = Carbon::today();
            $targetDate = $today->copy()->addDays($daysBeforeDue);

            // Get active loans
            $loans = Loan::with('user')
                ->where('status', 'active')
                ->whereNotNull('disbursement_date')
                ->where('term_months', '>', 0)
                ->get();

            $generated = 0;
            $skipped = 0;

            foreach ($loans as $loan) {
                if (!$loan->user || !$loan->user->phone) {
                    $skipped++;
                    continue;
                }

                // Calculate monthly payment
                $monthlyPayment = $loan->total_amount / $loan->term_months;
                
                // Calculate next due date
                $disbursementDate = Carbon::parse($loan->disbursement_date);
                $monthsSinceDisbursement = $today->diffInMonths($disbursementDate);
                
                // Calculate next installment due date
                $nextDueDate = $disbursementDate->copy()->addMonths($monthsSinceDisbursement + 1);
                
                // Skip if not within target date range
                if (!$includeOverdue && $nextDueDate->isPast()) {
                    $skipped++;
                    continue;
                }

                // Check if reminder already exists for this due date
                $existingReminder = LoanSmsReminder::where('loan_id', $loan->id)
                    ->where('due_date', $nextDueDate->format('Y-m-d'))
                    ->where('send_status', '!=', 'cancelled')
                    ->first();

                if ($existingReminder) {
                    $skipped++;
                    continue;
                }

                // Calculate days overdue
                $daysOverdue = $nextDueDate->isPast() ? $today->diffInDays($nextDueDate) : 0;

                // Determine repayment status
                $repaymentStatus = 'pending';
                if ($daysOverdue > 0) {
                    $repaymentStatus = 'overdue';
                } elseif ($loan->paid_amount > 0) {
                    $expectedPaid = $monthlyPayment * $monthsSinceDisbursement;
                    if ($loan->paid_amount >= $expectedPaid) {
                        $repaymentStatus = 'paid';
                    } elseif ($loan->paid_amount > 0) {
                        $repaymentStatus = 'partial';
                    }
                }

                // Generate SMS message
                $smsMessage = $this->generateSmsMessage($loan, $monthlyPayment, $nextDueDate, $daysOverdue, $repaymentStatus);

                // Create reminder
                LoanSmsReminder::create([
                    'loan_id' => $loan->id,
                    'user_id' => $loan->user_id,
                    'member_id' => $loan->user->member_number ?? null,
                    'loan_number' => $loan->loan_number,
                    'customer_name' => $loan->user->name,
                    'phone' => $loan->user->phone,
                    'outstanding_amount' => $loan->remaining_amount,
                    'monthly_repayment' => $monthlyPayment,
                    'repayment_status' => $repaymentStatus,
                    'due_date' => $nextDueDate,
                    'days_overdue' => $daysOverdue,
                    'sms_template' => $daysOverdue > 0 ? 'overdue' : 'reminder',
                    'sms_message' => $smsMessage,
                    'send_status' => 'pending',
                ]);

                $generated++;
            }

            return redirect()->route('admin.loans.sms-reminders.index')
                ->with('success', "Generated {$generated} reminders. {$skipped} skipped.");
        } catch (\Exception $e) {
            Log::error('Failed to generate loan SMS reminders: ' . $e->getMessage());
            return redirect()->route('admin.loans.sms-reminders.index')
                ->with('error', 'Failed to generate reminders: ' . $e->getMessage());
        }
    }

    /**
     * Send a single reminder
     */
    public function send(LoanSmsReminder $reminder)
    {
        try {
            if ($reminder->send_status === 'sent') {
                return redirect()->route('admin.loans.sms-reminders.index')
                    ->with('warning', 'This reminder has already been sent.');
            }

            // Send SMS
            $result = $this->smsService->sendSms(
                $reminder->phone,
                $reminder->sms_message
            );

            if (isset($result['success']) && $result['success']) {
                $reminder->update([
                    'send_status' => 'sent',
                    'send_date' => now(),
                    'sent_by' => auth()->id(),
                ]);

                return redirect()->route('admin.loans.sms-reminders.index')
                    ->with('success', 'SMS reminder sent successfully.');
            } else {
                $reminder->update([
                    'send_status' => 'failed',
                    'error_message' => 'Failed to send SMS. Please check SMS configuration.',
                ]);

                return redirect()->route('admin.loans.sms-reminders.index')
                    ->with('error', 'Failed to send SMS reminder.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to send loan SMS reminder: ' . $e->getMessage());
            
            $reminder->update([
                'send_status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('admin.loans.sms-reminders.index')
                ->with('error', 'Failed to send reminder: ' . $e->getMessage());
        }
    }

    /**
     * Send multiple reminders
     */
    public function sendBulk(Request $request)
    {
        try {
            $reminderIds = $request->input('reminder_ids', []);
            
            if (empty($reminderIds)) {
                return redirect()->route('admin.loans.sms-reminders.index')
                    ->with('error', 'Please select at least one reminder to send.');
            }

            $reminders = LoanSmsReminder::whereIn('id', $reminderIds)
                ->where('send_status', 'pending')
                ->get();

            $sent = 0;
            $failed = 0;

            foreach ($reminders as $reminder) {
                try {
                    $result = $this->smsService->sendSms(
                        $reminder->phone,
                        $reminder->sms_message
                    );

                    if (isset($result['success']) && $result['success']) {
                        $reminder->update([
                            'send_status' => 'sent',
                            'send_date' => now(),
                            'sent_by' => auth()->id(),
                        ]);
                        $sent++;
                    } else {
                        $reminder->update([
                            'send_status' => 'failed',
                            'error_message' => 'Failed to send SMS.',
                        ]);
                        $failed++;
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to send reminder ID {$reminder->id}: " . $e->getMessage());
                    $reminder->update([
                        'send_status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                    $failed++;
                }
            }

            $message = "Sent {$sent} reminders successfully.";
            if ($failed > 0) {
                $message .= " {$failed} failed.";
            }

            return redirect()->route('admin.loans.sms-reminders.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Failed to send bulk reminders: ' . $e->getMessage());
            return redirect()->route('admin.loans.sms-reminders.index')
                ->with('error', 'Failed to send reminders: ' . $e->getMessage());
        }
    }

    /**
     * Delete a reminder
     */
    public function destroy(LoanSmsReminder $reminder)
    {
        try {
            if ($reminder->send_status === 'sent') {
                return redirect()->route('admin.loans.sms-reminders.index')
                    ->with('error', 'Cannot delete a reminder that has already been sent.');
            }

            $reminder->delete();

            return redirect()->route('admin.loans.sms-reminders.index')
                ->with('success', 'Reminder deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete reminder: ' . $e->getMessage());
            return redirect()->route('admin.loans.sms-reminders.index')
                ->with('error', 'Failed to delete reminder.');
        }
    }

    /**
     * Generate SMS message based on loan details
     */
    protected function generateSmsMessage($loan, $monthlyPayment, $dueDate, $daysOverdue, $repaymentStatus): string
    {
        $orgInfo = $this->smsService->getOrganizationInfo();
        $orgName = $orgInfo['name'] ?? 'FeedTan CMG';
        
        $dueDateFormatted = Carbon::parse($dueDate)->format('d/m/Y');
        $amountFormatted = number_format($monthlyPayment, 0);
        $outstandingFormatted = number_format($loan->remaining_amount, 0);

        if ($daysOverdue > 0) {
            // Overdue message
            return "Habari {$loan->user->name}, Deni lako la mkopo namba {$loan->loan_number} linafikia TSh {$amountFormatted} na limeshindwa kwa siku {$daysOverdue}. Tafadhali fanya malipo mapema. Salio: TSh {$outstandingFormatted}. {$orgName}";
        } else {
            // Reminder message
            $daysUntilDue = Carbon::today()->diffInDays($dueDate);
            if ($daysUntilDue === 0) {
                return "Habari {$loan->user->name}, Deni lako la mkopo namba {$loan->loan_number} linapaswa kulipwa leo. Kiasi: TSh {$amountFormatted}. Salio: TSh {$outstandingFormatted}. {$orgName}";
            } else {
                return "Habari {$loan->user->name}, Deni lako la mkopo namba {$loan->loan_number} linapaswa kulipwa tarehe {$dueDateFormatted} (siku {$daysUntilDue} zimebaki). Kiasi: TSh {$amountFormatted}. Salio: TSh {$outstandingFormatted}. {$orgName}";
            }
        }
    }
}
