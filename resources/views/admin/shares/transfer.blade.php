@extends('layouts.admin')

@section('page-title', 'Share Transfer')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Transfer</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Transfer shares between members</p>
            </div>
            <a href="{{ route('admin.shares.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">Back to Shares</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Transfers This Month</p>
            <p class="text-2xl font-bold text-blue-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Pending Transfers</p>
            <p class="text-2xl font-bold text-orange-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Total Transferred</p>
            <p class="text-2xl font-bold text-green-600">0</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Transfer Shares</h2>
        <form method="POST" action="#" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Member (Seller) *</label>
                    <select name="from_member_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                        <option value="">-- Select Seller --</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Member (Buyer) *</label>
                    <select name="to_member_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                        <option value="">-- Select Buyer --</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Shares *</label>
                    <input type="number" name="share_count" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transfer Price per Share (TZS)</label>
                    <input type="number" name="transfer_price" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transfer Date *</label>
                    <input type="date" name="transfer_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transfer Fee</label>
                    <input type="number" name="transfer_fee" min="0" step="0.01" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Transfer</label>
                <textarea name="reason" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"></textarea>
            </div>
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.shares.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Process Transfer</button>
            </div>
        </form>
    </div>
</div>
@endsection

