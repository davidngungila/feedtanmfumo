<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function index()
    {
        return view('admin.shares.index');
    }

    public function issue()
    {
        return view('admin.shares.issue');
    }

    public function purchase()
    {
        return view('admin.shares.purchase');
    }

    public function transfer()
    {
        return view('admin.shares.transfer');
    }

    public function buyback()
    {
        return view('admin.shares.buyback');
    }

    public function cancellation()
    {
        return view('admin.shares.cancellation');
    }

    public function priceSetting()
    {
        return view('admin.shares.price-setting');
    }

    public function minimumShares()
    {
        return view('admin.shares.minimum-shares');
    }

    public function maximumShares()
    {
        return view('admin.shares.maximum-shares');
    }

    public function certificateTemplate()
    {
        return view('admin.shares.certificate-template');
    }

    public function dividendPolicy()
    {
        return view('admin.shares.dividend-policy');
    }

    public function ownershipRegister()
    {
        return view('admin.shares.ownership-register');
    }

    public function balancePerMember()
    {
        return view('admin.shares.balance-per-member');
    }

    public function transactionHistory()
    {
        return view('admin.shares.transaction-history');
    }

    public function certificatesIssued()
    {
        return view('admin.shares.certificates-issued');
    }

    public function dividendDistribution()
    {
        return view('admin.shares.dividend-distribution');
    }

    public function capitalReport()
    {
        return view('admin.shares.capital-report');
    }

    public function shareholderRegister()
    {
        return view('admin.shares.shareholder-register');
    }

    public function transactionLedger()
    {
        return view('admin.shares.transaction-ledger');
    }

    public function dividendReport()
    {
        return view('admin.shares.dividend-report');
    }

    public function growthAnalysis()
    {
        return view('admin.shares.growth-analysis');
    }
}

