@extends('layouts.admin')

@section('page-title', 'Formula Variables')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Formula Variables</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Available variables for formula building</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">System Variables</h3>
            <div class="space-y-2">
                <div class="p-3 bg-gray-50 rounded"><code class="text-sm">{current_date}</code> - Today's date</div>
                <div class="p-3 bg-gray-50 rounded"><code class="text-sm">{account_balance}</code> - Current balance</div>
                <div class="p-3 bg-gray-50 rounded"><code class="text-sm">{days_in_month}</code> - Days in month</div>
                <div class="p-3 bg-gray-50 rounded"><code class="text-sm">{interest_rate}</code> - Interest rate</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Member Variables</h3>
            <div class="space-y-2">
                <div class="p-3 bg-gray-50 rounded"><code class="text-sm">{member_age}</code> - Member's age</div>
                <div class="p-3 bg-gray-50 rounded"><code class="text-sm">{months_member}</code> - Membership duration</div>
                <div class="p-3 bg-gray-50 rounded"><code class="text-sm">{total_savings}</code> - All savings balance</div>
                <div class="p-3 bg-gray-50 rounded"><code class="text-sm">{active_loans}</code> - Active loans count</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Transaction Variables</h3>
            <div class="space-y-2">
                <div class="p-3 bg-gray-50 rounded"><code class="text-sm">{transaction_amount}</code> - Transaction amount</div>
                <div class="p-3 bg-gray-50 rounded"><code class="text-sm">{days_overdue}</code> - Days overdue</div>
                <div class="p-3 bg-gray-50 rounded"><code class="text-sm">{original_amount}</code> - Original amount</div>
            </div>
        </div>
    </div>
</div>
@endsection

