@extends('layouts.admin')

@section('page-title', 'Product Configuration')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Product Configuration</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Configure loan products, savings accounts, and investment plans</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Settings
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.product-configuration.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Loan Products Configuration -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Loan Products Configuration
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Loan Amount (TZS)</label>
                    <input type="number" name="loan_min_amount" value="{{ isset($settings['loan_min_amount']) ? $settings['loan_min_amount']->value : '100000' }}" min="0" step="1000" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Minimum amount that can be borrowed</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Loan Amount (TZS)</label>
                    <input type="number" name="loan_max_amount" value="{{ isset($settings['loan_max_amount']) ? $settings['loan_max_amount']->value : '10000000' }}" min="0" step="10000" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Maximum amount that can be borrowed</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Interest Rate (%)</label>
                    <input type="number" name="loan_default_interest_rate" value="{{ isset($settings['loan_default_interest_rate']) ? $settings['loan_default_interest_rate']->value : '12' }}" min="0" max="100" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Default annual interest rate for new loans</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Term (Months)</label>
                    <input type="number" name="loan_default_term_months" value="{{ isset($settings['loan_default_term_months']) ? $settings['loan_default_term_months']->value : '12' }}" min="1" max="120" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Default loan term in months</p>
                </div>
            </div>
        </div>

        <!-- Savings Products Configuration -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Savings Products Configuration
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Balance (TZS)</label>
                    <input type="number" name="savings_minimum_balance" value="{{ isset($settings['savings_minimum_balance']) ? $settings['savings_minimum_balance']->value : '10000' }}" min="0" step="1000" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Minimum balance required for savings accounts</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Interest Rate (%)</label>
                    <input type="number" name="savings_default_interest_rate" value="{{ isset($settings['savings_default_interest_rate']) ? $settings['savings_default_interest_rate']->value : '5' }}" min="0" max="100" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Default annual interest rate for savings accounts</p>
                </div>
            </div>
        </div>

        <!-- Investment Products Configuration -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Investment Products Configuration
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Investment Amount (TZS)</label>
                    <input type="number" name="investment_min_amount" value="{{ isset($settings['investment_min_amount']) ? $settings['investment_min_amount']->value : '500000' }}" min="0" step="10000" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Minimum amount required to start an investment</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Term (Months)</label>
                    <input type="number" name="investment_default_term_months" value="{{ isset($settings['investment_default_term_months']) ? $settings['investment_default_term_months']->value : '12' }}" min="1" max="120" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    <p class="text-xs text-gray-500 mt-1">Default investment term in months</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Save Configuration
                </button>
                <a href="{{ route('admin.settings.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

