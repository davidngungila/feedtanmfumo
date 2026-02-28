<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MonthlyDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyDepositController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = MonthlyDeposit::query()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('member_id', $user->member_number)
                    ->orWhere('member_id', $user->membership_code);
            });

        $from = $request->query('from');
        $to = $request->query('to');

        $fromKey = null;
        if (is_string($from) && preg_match('/^\d{4}-\d{2}$/', $from)) {
            [$fy, $fm] = array_map('intval', explode('-', $from));
            if ($fy > 0 && $fm >= 1 && $fm <= 12) {
                $fromKey = ($fy * 100) + $fm;
            }
        }

        $toKey = null;
        if (is_string($to) && preg_match('/^\d{4}-\d{2}$/', $to)) {
            [$ty, $tm] = array_map('intval', explode('-', $to));
            if ($ty > 0 && $tm >= 1 && $tm <= 12) {
                $toKey = ($ty * 100) + $tm;
            }
        }

        if ($fromKey !== null) {
            $query->whereRaw('(year * 100 + month) >= ?', [$fromKey]);
        }

        if ($toKey !== null) {
            $query->whereRaw('(year * 100 + month) <= ?', [$toKey]);
        }

        $query->orderBy('year', 'desc')->orderBy('month', 'desc');

        if ($request->query('export') === 'csv') {
            $rows = $query->get();
            $filename = 'statements_' . now()->format('Ymd_His') . '.csv';

            return response()->streamDownload(function () use ($rows) {
                $out = fopen('php://output', 'w');
                fputcsv($out, [
                    'Year',
                    'Month',
                    'Member ID',
                    'Name',
                    'Savings',
                    'Shares',
                    'Welfare',
                    'Loan Principal',
                    'Loan Interest',
                    'Fine/Penalty',
                    'Total',
                    'PDF',
                ]);

                foreach ($rows as $deposit) {
                    fputcsv($out, [
                        $deposit->year,
                        $deposit->month_name,
                        $deposit->member_id,
                        $deposit->name,
                        $deposit->savings,
                        $deposit->shares,
                        $deposit->welfare,
                        $deposit->loan_principal,
                        $deposit->loan_interest,
                        $deposit->fine_penalty,
                        $deposit->total,
                        $deposit->statement_pdf,
                    ]);
                }

                fclose($out);
            }, $filename, [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        $deposits = $query->get();

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
