<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\LoanStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanStatementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all statements for this member, ordered by latest
        $statements = LoanStatement::where('user_id', $user->id)
            ->orWhere('member_id', $user->membership_code)
            ->orWhere('member_id', $user->member_number)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('member.loan-statements.index', compact('statements'));
    }

    public function show(LoanStatement $loanStatement)
    {
        // Ensure the member can only see their own statements
        if ($loanStatement->user_id !== Auth::id() && 
            $loanStatement->member_id !== Auth::user()->membership_code &&
            $loanStatement->member_id !== Auth::user()->member_number) {
            abort(403);
        }

        return view('member.loan-statements.show', compact('loanStatement'));
    }
}
