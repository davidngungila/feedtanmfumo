<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvestmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Investment::with('user');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('investment_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('membership_code', 'like', "%{$search}%");
                  });
            });
        }
        
        $investments = $query->latest()->paginate(20);
        $stats = [
            'total' => Investment::count(),
            'active' => Investment::where('status', 'active')->count(),
            'matured' => Investment::where('status', 'matured')->count(),
            'total_principal' => Investment::sum('principal_amount') ?? 0,
            'total_profit' => Investment::sum('profit_share') ?? 0,
        ];
        return view('admin.investments.index', compact('investments', 'stats'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.investments.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_type' => 'required|in:4_year,6_year',
            'principal_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $principal = $validated['principal_amount'];
        $years = $validated['plan_type'] === '4_year' ? 4 : 6;
        $interest = ($principal * $validated['interest_rate'] / 100) * $years;
        $expectedReturn = $principal + $interest;

        Investment::create([
            'user_id' => $validated['user_id'],
            'investment_number' => 'INV-' . strtoupper(Str::random(8)),
            'plan_type' => $validated['plan_type'],
            'principal_amount' => $principal,
            'interest_rate' => $validated['interest_rate'],
            'expected_return' => $expectedReturn,
            'profit_share' => 0,
            'start_date' => $validated['start_date'],
            'maturity_date' => now()->parse($validated['start_date'])->addYears($years),
            'status' => 'active',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.investments.index')->with('success', 'Investment created successfully.');
    }

    public function show(Investment $investment)
    {
        $investment->load(['user', 'transactions']);
        return view('admin.investments.show', compact('investment'));
    }

    public function edit(Investment $investment)
    {
        $users = User::where('role', 'user')->get();
        return view('admin.investments.edit', compact('investment', 'users'));
    }

    public function update(Request $request, Investment $investment)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,matured,disbursed,cancelled',
            'profit_share' => 'nullable|numeric|min:0',
            'disbursement_date' => 'nullable|date|required_if:status,disbursed',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'matured' && $investment->status === 'active') {
            $validated['profit_share'] = $investment->expected_return - $investment->principal_amount;
        }

        $investment->update($validated);
        return redirect()->route('admin.investments.index')->with('success', 'Investment updated successfully.');
    }

    public function destroy(Investment $investment)
    {
        if ($investment->status === 'active') {
            return redirect()->back()->with('error', 'Cannot delete an active investment.');
        }
        $investment->delete();
        return redirect()->route('admin.investments.index')->with('success', 'Investment deleted successfully.');
    }
}
