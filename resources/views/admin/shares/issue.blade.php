@extends('layouts.admin')

@section('page-title', 'Issue New Shares')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Issue New Shares</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Create and issue new shares to members</p>
            </div>
            <a href="{{ route('admin.shares.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                Back to Shares
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Total Shares Issued</p>
            <p class="text-2xl font-bold text-blue-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Available for Issue</p>
            <p class="text-2xl font-bold text-green-600">Unlimited</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Current Share Price</p>
            <p class="text-2xl font-bold text-purple-600">0 TZS</p>
        </div>
    </div>

    <!-- Issue Shares Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Issue Shares to Member</h2>
        
        <form method="POST" action="#" class="space-y-6">
            @csrf
            
            <!-- Member Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Member *</label>
                <select name="member_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                    <option value="">-- Select Member --</option>
                    <!-- Members will be populated here -->
                </select>
            </div>

            <!-- Share Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Shares *</label>
                    <input type="number" name="share_count" min="1" step="1" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Enter number of shares" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Share Price (TZS) *</label>
                    <input type="number" name="share_price" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Enter share price" required>
                </div>
            </div>

            <!-- Total Amount Calculation -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium">Total Amount:</span>
                    <span class="text-2xl font-bold text-[#015425]" id="total-amount">0 TZS</span>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                        <option value="">-- Select Payment Method --</option>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="savings_deduction">Savings Deduction</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Reference</label>
                    <input type="text" name="transaction_ref" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Enter transaction reference">
                </div>
            </div>

            <!-- Issue Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Issue Date *</label>
                <input type="date" name="issue_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Additional notes (optional)"></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.shares.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Issue Shares
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Share Issues -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-[#015425]">Recent Share Issues</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shares</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price per Share</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No share issues found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Calculate total amount
    document.querySelector('input[name="share_count"]').addEventListener('input', calculateTotal);
    document.querySelector('input[name="share_price"]').addEventListener('input', calculateTotal);
    
    function calculateTotal() {
        const shareCount = parseFloat(document.querySelector('input[name="share_count"]').value) || 0;
        const sharePrice = parseFloat(document.querySelector('input[name="share_price"]').value) || 0;
        const total = shareCount * sharePrice;
        document.getElementById('total-amount').textContent = total.toLocaleString() + ' TZS';
    }
</script>
@endsection

