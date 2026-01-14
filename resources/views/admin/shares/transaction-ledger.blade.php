@extends('layouts.admin')

@section('page-title', 'Share Transaction Ledger')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Transaction Ledger</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Detailed transaction ledger for shares</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-bold text-[#015425]">Transaction Ledger</h2>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <input type="date" class="px-4 py-2 border border-gray-300 rounded-md">
                <input type="date" class="px-4 py-2 border border-gray-300 rounded-md">
                <button class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Filter</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ref #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No ledger entries found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

