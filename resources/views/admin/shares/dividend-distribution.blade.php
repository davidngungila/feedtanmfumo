@extends('layouts.admin')

@section('page-title', 'Dividend Distribution')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Dividend Distribution</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Manage dividend distributions to shareholders</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Total Dividend Pool</p>
            <p class="text-2xl font-bold text-blue-600">0 TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Dividend per Share</p>
            <p class="text-2xl font-bold text-green-600">0 TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Eligible Shareholders</p>
            <p class="text-2xl font-bold text-purple-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Distributions This Year</p>
            <p class="text-2xl font-bold text-orange-600">0</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">New Dividend Distribution</h2>
        <form method="POST" action="#" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Distribution Period *</label>
                    <select name="period" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                        <option value="">-- Select Period --</option>
                        <option value="2024">2024</option>
                        <option value="2024-Q1">2024 Q1</option>
                        <option value="2024-Q2">2024 Q2</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Dividend Amount (TZS) *</label>
                    <input type="number" name="total_amount" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dividend per Share (TZS)</label>
                    <input type="number" name="dividend_per_share" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                    <input type="date" name="payment_date" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
            </div>
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.shares.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Process Distribution</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Distribution List</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shares</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dividend per Share</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Dividend</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No distribution records found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

