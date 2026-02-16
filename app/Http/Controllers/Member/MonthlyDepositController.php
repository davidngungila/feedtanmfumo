<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MonthlyDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyDepositController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Find deposits by user_id or member_id (for those not yet fully linked)
        $deposits = MonthlyDeposit::where('user_id', $user->id)
            ->orWhere('member_id', $user->member_number)
            ->orWhere('member_id', $user->membership_code)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('member.monthly-deposits.index', compact('deposits'));
    }

    public function show(MonthlyDeposit $monthlyDeposit)
    {
        // Security check
        $user = Auth::user();
        if ($monthlyDeposit->user_id !== $user->id && 
            $monthlyDeposit->member_id !== $user->member_number && 
            $monthlyDeposit->member_id !== $user->membership_code) {
            abort(403);
        }

        return view('member.monthly-deposits.show', compact('monthlyDeposit'));
    }
}
