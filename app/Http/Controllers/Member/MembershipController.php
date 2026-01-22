<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MembershipType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class MembershipController extends Controller
{
    /**
     * Get the next step user should be on
     */
    private function getNextStep(User $user): int
    {
        $completedSteps = $user->membership_application_completed_steps ?? [];
        $currentStep = $user->membership_application_current_step ?? 0;
        
        // If no steps completed, start at step 1
        if (empty($completedSteps) && $currentStep == 0) {
            return 1;
        }
        
        // Find the first incomplete step
        for ($i = 1; $i <= 10; $i++) {
            if (!in_array($i, $completedSteps)) {
                return $i;
            }
        }
        
        // All steps completed - return 0 to indicate completion
        return 0;
    }
    
    /**
     * Check if all steps are completed
     */
    private function allStepsCompleted(User $user): bool
    {
        $completedSteps = $user->membership_application_completed_steps ?? [];
        return count($completedSteps) >= 10;
    }
    
    /**
     * Check if user can access a specific step
     */
    private function canAccessStep(User $user, int $step): bool
    {
        // Can always access step 1
        if ($step == 1) {
            return true;
        }
        
        $completedSteps = $user->membership_application_completed_steps ?? [];
        
        // Can access if previous step is completed
        return in_array($step - 1, $completedSteps);
    }
    
    /**
     * Mark step as completed
     */
    private function markStepCompleted(User $user, int $step): void
    {
        $completedSteps = $user->membership_application_completed_steps ?? [];
        
        if (!in_array($step, $completedSteps)) {
            $completedSteps[] = $step;
            $user->update([
                'membership_application_completed_steps' => $completedSteps,
                'membership_application_current_step' => $step,
            ]);
        }
    }
    
    /**
     * Show Step 1: Membership Type
     */
    public function showStep1()
    {
        $user = Auth::user();
        
        if ($user->membership_status === 'approved') {
            return redirect()->route('member.dashboard')->with('info', 'You already have an approved membership.');
        }
        
        // If all steps completed, redirect to preview
        if ($this->allStepsCompleted($user)) {
            return redirect()->route('member.membership.preview');
        }
        
        $membershipTypes = MembershipType::where('is_active', true)->orderBy('sort_order')->get();
        
        return view('member.membership.steps.step1', compact('membershipTypes', 'user'));
    }
    
    /**
     * Store Step 1: Membership Type
     */
    public function storeStep1(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'membership_type_id' => 'required|exists:membership_types,id',
        ]);
        
        // Generate membership code if not exists
        $membershipCode = $user->membership_code;
        if (!$membershipCode) {
            $membershipType = MembershipType::find($request->membership_type_id);
            if ($membershipType) {
                $membershipCode = strtoupper(substr($membershipType->slug ?? 'MEM', 0, 3)) . '-' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
            }
        }
        
        $user->update([
            'membership_type_id' => $request->membership_type_id,
            'membership_code' => $membershipCode,
        ]);
        
        $this->markStepCompleted($user, 1);
        
        if ($request->ajax() || $request->wantsJson() || $request->has('_ajax')) {
            return response()->json([
                'success' => true,
                'message' => 'Step 1 saved successfully!',
                'next_step' => 2,
                'redirect' => route('member.membership.step2')
            ]);
        }
        
        return redirect()->route('member.membership.step2')->with('success', 'Step 1 completed successfully!');
    }
    
    /**
     * Show Step 2: Personal Information
     */
    public function showStep2()
    {
        $user = Auth::user();
        
        // If all steps completed, redirect to preview
        if ($this->allStepsCompleted($user)) {
            return redirect()->route('member.membership.preview');
        }
        
        if (!$this->canAccessStep($user, 2)) {
            return redirect()->route('member.membership.step1')->with('error', 'Please complete Step 1 first.');
        }
        
        return view('member.membership.steps.step2', compact('user'));
    }
    
    /**
     * Store Step 2: Personal Information
     */
    public function storeStep2(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canAccessStep($user, 2)) {
            return redirect()->route('member.membership.step1')->with('error', 'Please complete Step 1 first.');
        }
        
        $request->validate([
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'national_id' => 'required|string|max:50',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'alternate_phone' => 'nullable|string|max:20',
        ]);
        
        $user->update($request->only([
            'phone', 'gender', 'date_of_birth', 'national_id', 
            'marital_status', 'alternate_phone'
        ]));
        
        $this->markStepCompleted($user, 2);
        
        if ($request->ajax() || $request->wantsJson() || $request->has('_ajax')) {
            return response()->json([
                'success' => true,
                'message' => 'Step 2 saved successfully!',
                'next_step' => 3,
                'redirect' => route('member.membership.step3')
            ]);
        }
        
        return redirect()->route('member.membership.step3')->with('success', 'Step 2 completed successfully!');
    }
    
    /**
     * Show Step 3: Address Information
     */
    public function showStep3()
    {
        $user = Auth::user();
        
        // If all steps completed, redirect to preview
        if ($this->allStepsCompleted($user)) {
            return redirect()->route('member.membership.preview');
        }
        
        if (!$this->canAccessStep($user, 3)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        return view('member.membership.steps.step3', compact('user'));
    }
    
    /**
     * Store Step 3: Address Information
     */
    public function storeStep3(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canAccessStep($user, 3)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        $request->validate([
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);
        
        $user->update($request->only(['address', 'city', 'region', 'postal_code']));
        
        $this->markStepCompleted($user, 3);
        
        if ($request->ajax() || $request->wantsJson() || $request->has('_ajax')) {
            return response()->json([
                'success' => true,
                'message' => 'Step 3 saved successfully!',
                'next_step' => 4,
                'redirect' => route('member.membership.step4')
            ]);
        }
        
        return redirect()->route('member.membership.step4')->with('success', 'Step 3 completed successfully!');
    }
    
    /**
     * Show Step 4: Employment Information
     */
    public function showStep4()
    {
        $user = Auth::user();
        
        // If all steps completed, redirect to preview
        if ($this->allStepsCompleted($user)) {
            return redirect()->route('member.membership.preview');
        }
        
        if (!$this->canAccessStep($user, 4)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        return view('member.membership.steps.step4', compact('user'));
    }
    
    /**
     * Store Step 4: Employment Information
     */
    public function storeStep4(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canAccessStep($user, 4)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        $request->validate([
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
        ]);
        
        $user->update($request->only(['occupation', 'employer', 'monthly_income']));
        
        $this->markStepCompleted($user, 4);
        
        if ($request->ajax() || $request->wantsJson() || $request->has('_ajax')) {
            return response()->json([
                'success' => true,
                'message' => 'Step 4 saved successfully!',
                'next_step' => 5,
                'redirect' => route('member.membership.step5')
            ]);
        }
        
        return redirect()->route('member.membership.step5')->with('success', 'Step 4 completed successfully!');
    }
    
    /**
     * Show Step 5: Bank Information
     */
    public function showStep5()
    {
        $user = Auth::user();
        
        // If all steps completed, redirect to preview
        if ($this->allStepsCompleted($user)) {
            return redirect()->route('member.membership.preview');
        }
        
        if (!$this->canAccessStep($user, 5)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        return view('member.membership.steps.step5', compact('user'));
    }
    
    /**
     * Store Step 5: Bank Information
     */
    public function storeStep5(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canAccessStep($user, 5)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        $request->validate([
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'payment_reference_number' => 'nullable|string|max:100',
        ]);
        
        $user->update($request->only([
            'bank_name', 'bank_branch', 'bank_account_number', 'payment_reference_number'
        ]));
        
        $this->markStepCompleted($user, 5);
        
        if ($request->ajax() || $request->wantsJson() || $request->has('_ajax')) {
            return response()->json([
                'success' => true,
                'message' => 'Step 5 saved successfully!',
                'next_step' => 6,
                'redirect' => route('member.membership.step6')
            ]);
        }
        
        return redirect()->route('member.membership.step6')->with('success', 'Step 5 completed successfully!');
    }
    
    /**
     * Show Step 6: Additional Information
     */
    public function showStep6()
    {
        $user = Auth::user();
        
        // If all steps completed, redirect to preview
        if ($this->allStepsCompleted($user)) {
            return redirect()->route('member.membership.preview');
        }
        
        if (!$this->canAccessStep($user, 6)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        return view('member.membership.steps.step6', compact('user'));
    }
    
    /**
     * Store Step 6: Additional Information
     */
    public function storeStep6(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canAccessStep($user, 6)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        $request->validate([
            'statement_preference' => 'required|in:email,sms,postal',
            'short_bibliography' => 'nullable|string|max:1000',
            'introduced_by' => 'nullable|string|max:255',
        ]);
        
        $user->update($request->only(['short_bibliography', 'introduced_by', 'statement_preference']));
        
        $this->markStepCompleted($user, 6);
        
        if ($request->ajax() || $request->wantsJson() || $request->has('_ajax')) {
            return response()->json([
                'success' => true,
                'message' => 'Step 6 saved successfully!',
                'next_step' => 7,
                'redirect' => route('member.membership.step7')
            ]);
        }
        
        return redirect()->route('member.membership.step7')->with('success', 'Step 6 completed successfully!');
    }
    
    /**
     * Show Step 7: Beneficiaries
     */
    public function showStep7()
    {
        $user = Auth::user();
        
        // If all steps completed, redirect to preview
        if ($this->allStepsCompleted($user)) {
            return redirect()->route('member.membership.preview');
        }
        
        if (!$this->canAccessStep($user, 7)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        $beneficiaries = $user->beneficiaries_info ?? [];
        
        return view('member.membership.steps.step7', compact('user', 'beneficiaries'));
    }
    
    /**
     * Store Step 7: Beneficiaries
     */
    public function storeStep7(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canAccessStep($user, 7)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        $beneficiaries = [];
        
        if ($request->has('beneficiaries')) {
            foreach ($request->beneficiaries as $beneficiary) {
                if (!empty($beneficiary['name'])) {
                    $beneficiaries[] = [
                        'name' => $beneficiary['name'] ?? '',
                        'relationship' => $beneficiary['relationship'] ?? '',
                        'allocation' => $beneficiary['allocation'] ?? 0,
                        'contact' => $beneficiary['contact'] ?? '',
                    ];
                }
            }
        }
        
        $user->update(['beneficiaries_info' => $beneficiaries]);
        
        $this->markStepCompleted($user, 7);
        
        if ($request->ajax() || $request->wantsJson() || $request->has('_ajax')) {
            return response()->json([
                'success' => true,
                'message' => 'Step 7 saved successfully!',
                'next_step' => 8,
                'redirect' => route('member.membership.step8')
            ]);
        }
        
        return redirect()->route('member.membership.step8')->with('success', 'Step 7 completed successfully!');
    }
    
    /**
     * Show Step 8: Group Information
     */
    public function showStep8()
    {
        $user = Auth::user();
        
        // If all steps completed, redirect to preview
        if ($this->allStepsCompleted($user)) {
            return redirect()->route('member.membership.preview');
        }
        
        if (!$this->canAccessStep($user, 8)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        return view('member.membership.steps.step8', compact('user'));
    }
    
    /**
     * Store Step 8: Group Information
     */
    public function storeStep8(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canAccessStep($user, 8)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        $request->validate([
            'is_group_registered' => 'nullable|boolean',
            'group_name' => 'nullable|string|max:255',
            'group_leaders' => 'nullable|string|max:500',
            'group_bank_account' => 'nullable|string|max:100',
            'group_contacts' => 'nullable|string|max:500',
        ]);
        
        $data = $request->only([
            'group_name', 'group_leaders', 'group_bank_account', 'group_contacts'
        ]);
        $data['is_group_registered'] = $request->has('is_group_registered') ? (bool) $request->input('is_group_registered') : false;
        
        $user->update($data);
        
        $this->markStepCompleted($user, 8);
        
        if ($request->ajax() || $request->wantsJson() || $request->has('_ajax')) {
            return response()->json([
                'success' => true,
                'message' => 'Step 8 saved successfully!',
                'next_step' => 9,
                'redirect' => route('member.membership.step9')
            ]);
        }
        
        return redirect()->route('member.membership.step9')->with('success', 'Step 8 completed successfully!');
    }
    
    /**
     * Show Step 9: Documents Upload
     */
    public function showStep9()
    {
        $user = Auth::user();
        
        // If all steps completed, redirect to preview
        if ($this->allStepsCompleted($user)) {
            return redirect()->route('member.membership.preview');
        }
        
        if (!$this->canAccessStep($user, 9)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        return view('member.membership.steps.step9', compact('user'));
    }
    
    /**
     * Store Step 9: Documents Upload
     */
    public function storeStep9(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canAccessStep($user, 9)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        $request->validate([
            'passport_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nida_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'application_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'payment_slip' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'standing_order' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);
        
        $data = [];
        
        if ($request->hasFile('passport_picture')) {
            $data['passport_picture_path'] = $request->file('passport_picture')->store('membership/passport', 'public');
        }
        
        if ($request->hasFile('nida_picture')) {
            $data['nida_picture_path'] = $request->file('nida_picture')->store('membership/nida', 'public');
        }
        
        if ($request->hasFile('application_letter')) {
            $data['application_letter_path'] = $request->file('application_letter')->store('membership/letters', 'public');
        }
        
        if ($request->hasFile('payment_slip')) {
            $data['payment_slip_path'] = $request->file('payment_slip')->store('membership/payments', 'public');
        }
        
        if ($request->hasFile('standing_order')) {
            $data['standing_order_path'] = $request->file('standing_order')->store('membership/standing-orders', 'public');
        }
        
        if (!empty($data)) {
            $user->update($data);
        }
        
        $this->markStepCompleted($user, 9);
        
        if ($request->ajax() || $request->wantsJson() || $request->has('_ajax')) {
            return response()->json([
                'success' => true,
                'message' => 'Step 9 saved successfully!',
                'next_step' => 10,
                'redirect' => route('member.membership.step10')
            ]);
        }
        
        return redirect()->route('member.membership.step10')->with('success', 'Step 9 completed successfully!');
    }
    
    /**
     * Show Step 10: Additional Options
     */
    public function showStep10()
    {
        $user = Auth::user();
        
        // If all steps completed, redirect to preview
        if ($this->allStepsCompleted($user)) {
            return redirect()->route('member.membership.preview');
        }
        
        if (!$this->canAccessStep($user, 10)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        return view('member.membership.steps.step10', compact('user'));
    }
    
    /**
     * Store Step 10: Additional Options & Final Submission
     */
    public function storeStep10(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->canAccessStep($user, 10)) {
            return redirect()->route('member.membership.step' . ($this->getNextStep($user)))->with('error', 'Please complete previous steps first.');
        }
        
        $request->validate([
            'wants_ordinary_membership' => 'nullable|boolean',
        ]);
        
        $user->update([
            'wants_ordinary_membership' => $request->has('wants_ordinary_membership') ? (bool) $request->input('wants_ordinary_membership') : false,
            'membership_status' => 'pending',
        ]);
        
        $this->markStepCompleted($user, 10);
        
        if ($request->ajax() || $request->wantsJson() || $request->has('_ajax')) {
            return response()->json([
                'success' => true,
                'message' => 'Congratulations! Your membership application has been submitted successfully. It will be reviewed by administrators.',
                'redirect' => route('member.membership.preview')
            ]);
        }
        
        return redirect()->route('member.membership.preview')->with('success', 'Congratulations! Your membership application has been submitted successfully. It will be reviewed by administrators.');
    }
    
    /**
     * Show application status
     */
    public function status()
    {
        $user = Auth::user();
        
        return view('member.membership.status', compact('user'));
    }
    
    /**
     * Preview application
     */
    public function preview()
    {
        $user = Auth::user();
        $user->load('membershipType');
        
        // If user tries to access steps after submission, redirect to preview
        if ($user->membership_status === 'pending' && count($user->membership_application_completed_steps ?? []) >= 10) {
            // All steps completed, show preview only
        } elseif ($user->membership_status === 'approved') {
            // Already approved, can view preview
        } else {
            // Not all steps completed, redirect to appropriate step
            $nextStep = $this->getNextStep($user);
            if ($nextStep > 0 && $nextStep <= 10) {
                return redirect()->route('member.membership.step' . $nextStep);
            }
        }
        
        return view('member.membership.preview', compact('user'));
    }
    
    /**
     * Download application as PDF
     */
    public function downloadPdf()
    {
        $user = Auth::user();
        $user->load('membershipType');
        
        $pdf = PDF::loadView('member.membership.pdf', compact('user'));
        
        $filename = 'Membership_Application_' . ($user->membership_code ?? $user->id) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
