<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavingsAccount;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SavingsAccountController extends Controller
{
    public function index()
    {
        $accounts = SavingsAccount::with('user')->latest()->paginate(20);
        $stats = [
            'total' => SavingsAccount::count(),
            'total_balance' => SavingsAccount::sum('balance'),
            'emergency' => SavingsAccount::where('account_type', 'emergency')->sum('balance'),
            'business' => SavingsAccount::where('account_type', 'business')->sum('balance'),
        ];
        return view('admin.savings.index', compact('accounts', 'stats'));
    }

    public function create(Request $request)
    {
        $users = User::where('role', 'user')->get();
        $selectedUser = null;
        
        if ($request->has('user_id')) {
            $selectedUser = User::where('role', 'user')->find($request->user_id);
        }
        
        return view('admin.savings.create', compact('users', 'selectedUser'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'account_type' => 'required|in:emergency,rda,flex,business',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'minimum_balance' => 'nullable|numeric|min:0',
            'opening_date' => 'required|date',
            'maturity_date' => 'nullable|date|required_if:account_type,rda',
            'notes' => 'nullable|string',
        ]);

        SavingsAccount::create([
            'user_id' => $validated['user_id'],
            'account_number' => 'SAV-' . strtoupper(Str::random(8)),
            'account_type' => $validated['account_type'],
            'balance' => 0,
            'interest_rate' => $validated['interest_rate'] ?? 0,
            'minimum_balance' => $validated['minimum_balance'] ?? 0,
            'opening_date' => $validated['opening_date'],
            'maturity_date' => $validated['maturity_date'] ?? null,
            'status' => 'active',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.savings.index')->with('success', 'Savings account created successfully.');
    }

    public function show(SavingsAccount $saving)
    {
        $saving->load(['user', 'transactions']);
        return view('admin.savings.show', compact('saving'));
    }

    public function edit(SavingsAccount $saving)
    {
        $users = User::where('role', 'user')->get();
        return view('admin.savings.edit', compact('saving', 'users'));
    }

    public function update(Request $request, SavingsAccount $saving)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,closed,frozen',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $saving->update($validated);
        return redirect()->route('admin.savings.index')->with('success', 'Savings account updated successfully.');
    }

    public function destroy(SavingsAccount $saving)
    {
        if ($saving->balance > 0) {
            return redirect()->back()->with('error', 'Cannot delete account with balance.');
        }
        $saving->delete();
        return redirect()->route('admin.savings.index')->with('success', 'Savings account deleted successfully.');
    }

    public function deposits(Request $request)
    {
        $query = SavingsAccount::with('user')->where('status', 'active');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $accounts = $query->latest()->paginate(20);
        
        $stats = [
            'total_accounts' => SavingsAccount::where('status', 'active')->count(),
            'total_balance' => SavingsAccount::where('status', 'active')->sum('balance'),
            'total_deposits_today' => Transaction::where('transaction_type', 'savings_deposit')
                ->whereDate('created_at', today())
                ->where('related_type', 'savings_account')
                ->sum('amount') ?? 0,
            'total_deposits_month' => Transaction::where('transaction_type', 'savings_deposit')
                ->whereMonth('created_at', now()->month)
                ->where('related_type', 'savings_account')
                ->sum('amount') ?? 0,
        ];
        
        return view('admin.savings.deposits', compact('accounts', 'stats'));
    }

    public function withdrawals(Request $request)
    {
        $query = SavingsAccount::with('user')->where('status', 'active');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $accounts = $query->latest()->paginate(20);
        
        $withdrawals = Transaction::where('transaction_type', 'savings_withdrawal')
            ->where('related_type', 'savings_account')
            ->with('user')
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total_withdrawals_today' => Transaction::where('transaction_type', 'savings_withdrawal')
                ->whereDate('created_at', today())
                ->where('related_type', 'savings_account')
                ->sum('amount') ?? 0,
            'total_withdrawals_month' => Transaction::where('transaction_type', 'savings_withdrawal')
                ->whereMonth('created_at', now()->month)
                ->where('related_type', 'savings_account')
                ->sum('amount') ?? 0,
            'pending_requests' => 0, // Placeholder for withdrawal requests
        ];
        
        return view('admin.savings.withdrawals', compact('accounts', 'withdrawals', 'stats'));
    }

    public function transfers(Request $request)
    {
        $accounts = SavingsAccount::with('user')->where('status', 'active')->get();
        
        // Get transfer transactions (withdrawals with bank_transfer payment method)
        $transfers = Transaction::with(['user', 'related'])
            ->where('payment_method', 'bank_transfer')
            ->where('transaction_type', 'savings_withdrawal')
            ->where('related_type', 'App\Models\SavingsAccount')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Calculate stats
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        
        $transfersToday = Transaction::where('payment_method', 'bank_transfer')
            ->where('transaction_type', 'savings_withdrawal')
            ->where('related_type', 'App\Models\SavingsAccount')
            ->where('created_at', '>=', $today)
            ->count();
            
        $transfersMonth = Transaction::where('payment_method', 'bank_transfer')
            ->where('transaction_type', 'savings_withdrawal')
            ->where('related_type', 'App\Models\SavingsAccount')
            ->where('created_at', '>=', $thisMonth)
            ->count();
            
        $totalAmountTransferred = Transaction::where('payment_method', 'bank_transfer')
            ->where('transaction_type', 'savings_withdrawal')
            ->where('related_type', 'App\Models\SavingsAccount')
            ->where('created_at', '>=', $thisMonth)
            ->sum('amount');
        
        $stats = [
            'total_transfers_today' => $transfersToday,
            'total_transfers_month' => $transfersMonth,
            'total_amount_transferred' => $totalAmountTransferred,
        ];
        
        return view('admin.savings.transfers', compact('accounts', 'transfers', 'stats'));
    }

    public function processTransfer(Request $request)
    {
        $request->validate([
            'from_account_id' => 'required|exists:savings_accounts,id',
            'to_account_id' => 'required|exists:savings_accounts,id|different:from_account_id',
            'amount' => 'required|numeric|min:100',
            'transfer_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $fromAccount = SavingsAccount::findOrFail($request->from_account_id);
            $toAccount = SavingsAccount::findOrFail($request->to_account_id);
            $amount = $request->amount;

            // Check sufficient balance
            if ($fromAccount->balance < $amount) {
                return redirect()->back()
                    ->with('error', 'Insufficient balance in source account.')
                    ->withInput();
            }

            // Generate transaction numbers
            $withdrawalNumber = 'TRN-W-' . date('YmdHis') . '-' . rand(1000, 9999);
            $depositNumber = 'TRN-D-' . date('YmdHis') . '-' . rand(1000, 9999);

            // Create withdrawal transaction
            $withdrawal = Transaction::create([
                'user_id' => $fromAccount->user_id,
                'transaction_number' => $withdrawalNumber,
                'transaction_type' => 'savings_withdrawal',
                'related_type' => 'App\Models\SavingsAccount',
                'related_id' => $fromAccount->id,
                'amount' => $amount,
                'payment_method' => 'bank_transfer',
                'reference_number' => $depositNumber,
                'transaction_date' => $request->transfer_date,
                'description' => 'Transfer to account ' . $toAccount->account_number . ($request->description ? ' - ' . $request->description : ''),
                'status' => 'completed',
                'processed_by' => auth()->id(),
            ]);

            // Create deposit transaction
            $deposit = Transaction::create([
                'user_id' => $toAccount->user_id,
                'transaction_number' => $depositNumber,
                'transaction_type' => 'savings_deposit',
                'related_type' => 'App\Models\SavingsAccount',
                'related_id' => $toAccount->id,
                'amount' => $amount,
                'payment_method' => 'bank_transfer',
                'reference_number' => $withdrawalNumber,
                'transaction_date' => $request->transfer_date,
                'description' => 'Transfer from account ' . $fromAccount->account_number . ($request->description ? ' - ' . $request->description : ''),
                'status' => 'completed',
                'processed_by' => auth()->id(),
            ]);

            // Update account balances
            $fromAccount->decrement('balance', $amount);
            $toAccount->increment('balance', $amount);

            DB::commit();

            return redirect()->route('admin.savings.transfers')
                ->with('success', 'Transfer of ' . number_format($amount, 0) . ' TZS processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Transfer failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function interestPosting(Request $request)
    {
        $accounts = SavingsAccount::with('user')
            ->where('status', 'active')
            ->where('interest_rate', '>', 0)
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total_accounts' => SavingsAccount::where('status', 'active')
                ->where('interest_rate', '>', 0)
                ->count(),
            'total_interest_payable' => SavingsAccount::where('status', 'active')
                ->where('interest_rate', '>', 0)
                ->get()
                ->sum(function($account) {
                    return ($account->balance * $account->interest_rate) / 100 / 12; // Monthly interest
                }),
            // Note: Interest posting type doesn't exist in enum - you may need to add it or track differently
            'last_posting_date' => Transaction::where('transaction_type', 'savings_deposit')
                ->where('related_type', 'savings_account')
                ->where('description', 'like', '%interest%')
                ->latest()
                ->first()?->created_at,
        ];
        
        return view('admin.savings.interest-posting', compact('accounts', 'stats'));
    }

    public function statements(Request $request)
    {
        $accounts = SavingsAccount::with('user')->where('status', 'active')->get();
        $selectedAccount = null;
        $transactions = collect();
        
        if ($request->has('account_id') && $request->account_id) {
            $selectedAccount = SavingsAccount::with('user')->find($request->account_id);
            if ($selectedAccount) {
                $transactions = Transaction::where('related_id', $selectedAccount->id)
                    ->where('related_type', 'savings_account')
                    ->latest()
                    ->paginate(50);
            }
        }
        
        return view('admin.savings.statements', compact('accounts', 'selectedAccount', 'transactions'));
    }

    public function closeAccount(Request $request)
    {
        $query = SavingsAccount::with('user')->where('status', 'active');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $accounts = $query->latest()->paginate(20);
        
        $stats = [
            'total_active' => SavingsAccount::where('status', 'active')->count(),
            'total_closed' => SavingsAccount::where('status', 'closed')->count(),
            'pending_closures' => 0,
        ];
        
        return view('admin.savings.close-account', compact('accounts', 'stats'));
    }

    public function freezeUnfreeze(Request $request)
    {
        $query = SavingsAccount::with('user');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $accounts = $query->latest()->paginate(20);
        
        $stats = [
            'active' => SavingsAccount::where('status', 'active')->count(),
            'frozen' => SavingsAccount::where('status', 'frozen')->count(),
            'closed' => SavingsAccount::where('status', 'closed')->count(),
        ];
        
        return view('admin.savings.freeze-unfreeze', compact('accounts', 'stats'));
    }

    public function upgrades(Request $request)
    {
        $accounts = SavingsAccount::with('user')
            ->where('status', 'active')
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total_accounts' => SavingsAccount::where('status', 'active')->count(),
            'eligible_for_upgrade' => SavingsAccount::where('status', 'active')
                ->where('balance', '>=', 1000000)
                ->count(),
        ];
        
        return view('admin.savings.upgrades', compact('accounts', 'stats'));
    }

    public function minimumBalance(Request $request)
    {
        $query = SavingsAccount::with('user')
            ->where('status', 'active')
            ->whereColumn('balance', '<', 'minimum_balance');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $accounts = $query->latest()->paginate(20);
        
        $stats = [
            'below_minimum' => SavingsAccount::where('status', 'active')
                ->whereColumn('balance', '<', 'minimum_balance')
                ->count(),
            'total_deficit' => SavingsAccount::where('status', 'active')
                ->whereColumn('balance', '<', 'minimum_balance')
                ->get()
                ->sum(function($account) {
                    return max(0, $account->minimum_balance - $account->balance);
                }),
        ];
        
        return view('admin.savings.minimum-balance', compact('accounts', 'stats'));
    }

    public function totalBalance(Request $request)
    {
        $accounts = SavingsAccount::with('user')->latest()->paginate(20);
        
        $stats = [
            'total_balance' => SavingsAccount::sum('balance'),
            'emergency' => SavingsAccount::where('account_type', 'emergency')->sum('balance'),
            'rda' => SavingsAccount::where('account_type', 'rda')->sum('balance'),
            'flex' => SavingsAccount::where('account_type', 'flex')->sum('balance'),
            'business' => SavingsAccount::where('account_type', 'business')->sum('balance'),
            'active_accounts' => SavingsAccount::where('status', 'active')->count(),
            'total_accounts' => SavingsAccount::count(),
        ];
        
        // Monthly growth data
        $monthlyGrowth = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyGrowth[] = [
                'month' => $date->format('M Y'),
                'balance' => SavingsAccount::whereYear('opening_date', $date->year)
                    ->whereMonth('opening_date', '<=', $date->month)
                    ->sum('balance') ?? 0,
            ];
        }
        
        return view('admin.savings.total-balance', compact('accounts', 'stats', 'monthlyGrowth'));
    }
}
