@extends('layouts.admin')

@section('page-title', 'Share Buyback')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Buyback</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Repurchase shares from members</p>
            </div>
            <a href="{{ route('admin.shares.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Buybacks This Month</p>
            <p class="text-2xl font-bold text-blue-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Total Buyback Amount</p>
            <p class="text-2xl font-bold text-green-600">0 TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Pending Buybacks</p>
            <p class="text-2xl font-bold text-orange-600">0</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Buyback Shares</h2>
        <form method="POST" action="#" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Member *</label>
                    <select name="member_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                        <option value="">-- Select Member --</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Shares to Buyback *</label>
                    <input type="number" name="share_count" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buyback Price per Share (TZS) *</label>
                    <input type="number" name="buyback_price" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buyback Date *</label>
                    <input type="date" name="buyback_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium">Total Buyback Amount:</span>
                    <span class="text-2xl font-bold text-[#015425]" id="total-buyback">0 TZS</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Buyback</label>
                <textarea name="reason" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"></textarea>
            </div>
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.shares.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Process Buyback</button>
            </div>
        </form>
    </div>
</div>
@endsection

