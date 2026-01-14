<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormulaController extends Controller
{
    public function index()
    {
        return view('admin.formulas.index');
    }

    // Loans Formulas
    public function loansInterest()
    {
        return view('admin.formulas.loans.interest');
    }

    public function loansFees()
    {
        return view('admin.formulas.loans.fees');
    }

    public function loansLimits()
    {
        return view('admin.formulas.loans.limits');
    }

    public function loansRepayment()
    {
        return view('admin.formulas.loans.repayment');
    }

    // Savings Formulas
    public function savingsInterest()
    {
        return view('admin.formulas.savings.interest');
    }

    public function savingsAccountSpecific()
    {
        return view('admin.formulas.savings.account-specific');
    }

    // Investments Formulas
    public function investments4Year()
    {
        return view('admin.formulas.investments.4-year');
    }

    public function investments6Year()
    {
        return view('admin.formulas.investments.6-year');
    }

    public function investmentsPerformance()
    {
        return view('admin.formulas.investments.performance');
    }

    // Shares Formulas
    public function sharesValue()
    {
        return view('admin.formulas.shares.value');
    }

    public function sharesDividend()
    {
        return view('admin.formulas.shares.dividend');
    }

    public function sharesPricing()
    {
        return view('admin.formulas.shares.pricing');
    }

    // Welfare Formulas
    public function welfareContribution()
    {
        return view('admin.formulas.welfare.contribution');
    }

    public function welfareBenefit()
    {
        return view('admin.formulas.welfare.benefit');
    }

    public function welfareEligibility()
    {
        return view('admin.formulas.welfare.eligibility');
    }

    // Fees & Charges
    public function feesMembership()
    {
        return view('admin.formulas.fees.membership');
    }

    public function feesTransaction()
    {
        return view('admin.formulas.fees.transaction');
    }

    public function feesService()
    {
        return view('admin.formulas.fees.service');
    }

    // Tax & Compliance
    public function taxCalculations()
    {
        return view('admin.formulas.tax.calculations');
    }

    public function taxReserves()
    {
        return view('admin.formulas.tax.reserves');
    }

    // Performance Metrics
    public function metricsFinancialRatios()
    {
        return view('admin.formulas.metrics.financial-ratios');
    }

    public function metricsPortfolioQuality()
    {
        return view('admin.formulas.metrics.portfolio-quality');
    }

    // Commission & Incentives
    public function commissionStaff()
    {
        return view('admin.formulas.commission.staff');
    }

    public function commissionMember()
    {
        return view('admin.formulas.commission.member');
    }

    // Formula Builder
    public function builder()
    {
        return view('admin.formulas.builder.index');
    }

    public function builderVariables()
    {
        return view('admin.formulas.builder.variables');
    }

    public function builderTesting()
    {
        return view('admin.formulas.builder.testing');
    }

    public function builderHistory()
    {
        return view('admin.formulas.builder.history');
    }

    // Formula Reports
    public function reportsAudit()
    {
        return view('admin.formulas.reports.audit');
    }

    public function reportsCalculation()
    {
        return view('admin.formulas.reports.calculation');
    }
}

