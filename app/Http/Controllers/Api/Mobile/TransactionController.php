<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->paginate(20)
            ->through(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'type' => $transaction->transaction_type,
                    'amount' => (float) $transaction->amount,
                    'status' => $transaction->status,
                    'date' => $transaction->transaction_date->format('Y-m-d'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }
}
