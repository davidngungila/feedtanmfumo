@extends('layouts.admin')

@section('page-title', 'Share Cancellation')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Cancellation</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Cancel shares from the register</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Cancel Shares</h2>
        <form method="POST" action="#" class="space-y-6">
            @csrf
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800"><strong>Warning:</strong> Share cancellation is irreversible. Please ensure all approvals are in place.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Member *</label>
                    <select name="member_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                        <option value="">-- Select Member --</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Shares to Cancel *</label>
                    <input type="number" name="share_count" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cancellation Date *</label>
                    <input type="date" name="cancellation_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Approval Reference</label>
                    <input type="text" name="approval_ref" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Cancellation *</label>
                <textarea name="reason" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required></textarea>
            </div>
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.shares.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">Cancel Shares</button>
            </div>
        </form>
    </div>
</div>
@endsection

