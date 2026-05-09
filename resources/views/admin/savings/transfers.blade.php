@extends('layouts.admin')

@section('page-title', 'Transfer Between Accounts')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Transfer Between Accounts</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Transfer funds between savings accounts</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.savings.deposits') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Deposits
                </a>
                <a href="{{ route('admin.savings.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    All Savings
                </a>
            </div>
        </div>
    </div>

    <!-- Simple Message -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 3 2 3m0 8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0V1m-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-600 mb-2">Transfer Functionality</h2>
            <p class="text-gray-500 mb-4">The transfer functionality has been temporarily disabled.</p>
            <div class="space-y-2 text-sm text-gray-600">
                <p>• Transfer between savings accounts is currently not available</p>
                <p>• Please contact administrator for assistance</p>
                <p>• You can still access deposits and withdrawals from the menu above</p>
            </div>
        </div>
    </div>
</div>
@endsection
