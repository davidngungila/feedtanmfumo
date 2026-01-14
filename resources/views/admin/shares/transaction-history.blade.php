@extends('layouts.admin')

@section('page-title', 'Share Transaction History')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Transaction History</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Complete history of all share transactions</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-bold text-[#015425]">Transaction History</h2>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <select class="px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option>All Types</option>
                    <option>Issue</option>
                    <option>Purchase</option>
                    <option>Transfer</option>
                    <option>Buyback</option>
                    <option>Cancellation</option>
                </select>
                <input type="date" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                <button class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Filter</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shares</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No transactions found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

