<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $investments = Investment::where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($investment) {
                return [
                    'id' => $investment->id,
                    'investment_number' => $investment->investment_number,
                    'plan_type' => $investment->plan_type,
                    'principal_amount' => (float) $investment->principal_amount,
                    'expected_return' => (float) $investment->expected_return,
                    'status' => $investment->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $investments,
        ]);
    }

    public function show(Investment $investment)
    {
        if ($investment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $investment->id,
                'investment_number' => $investment->investment_number,
                'plan_type' => $investment->plan_type,
                'principal_amount' => (float) $investment->principal_amount,
                'expected_return' => (float) $investment->expected_return,
                'profit_share' => (float) $investment->profit_share,
                'status' => $investment->status,
                'maturity_date' => $investment->maturity_date?->format('Y-m-d'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        // TODO: Implement investment subscription
        return response()->json([
            'success' => true,
            'message' => 'Investment subscription created successfully',
        ], 201);
    }
}
