@extends('layouts.admin')

@section('page-title', 'Share Price Setting')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Price Setting</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Configure share pricing parameters</p>
            </div>
            <a href="{{ route('admin.shares.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">Back to Shares</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Current Share Price</p>
            <p class="text-3xl font-bold text-[#015425]">0 TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Par Value</p>
            <p class="text-3xl font-bold text-blue-600">0 TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Market Value</p>
            <p class="text-3xl font-bold text-green-600">0 TZS</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Share Price Configuration</h2>
        <form method="POST" action="#" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Par Value per Share (TZS) *</label>
                    <input type="number" name="par_value" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                    <p class="text-xs text-gray-500 mt-1">Nominal value of each share</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Market Price (TZS) *</label>
                    <input type="number" name="market_price" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                    <p class="text-xs text-gray-500 mt-1">Current trading price</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Share Price (TZS)</label>
                    <input type="number" name="min_price" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Share Price (TZS)</label>
                    <input type="number" name="max_price" min="0" step="0.01" class="w-full px-2 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Effective Date *</label>
                <input type="date" name="effective_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
            </div>
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="auto_update" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                    <span class="ml-2 text-sm text-gray-700">Auto-update based on NAV calculation</span>
                </label>
            </div>
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.shares.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save Settings</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Price History</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Par Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Market Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Change</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated By</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No price history found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

