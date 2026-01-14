@extends('layouts.admin')

@section('page-title', 'Maximum Shares per Member')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Maximum Shares per Member</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure maximum share ownership limits</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Maximum Share Configuration</h2>
        <form method="POST" action="#" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Shares Allowed *</label>
                <input type="number" name="max_shares" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                <p class="text-xs text-gray-500 mt-1">Maximum number of shares a single member can hold</p>
            </div>
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="enforce_maximum" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                    <span class="ml-2 text-sm text-gray-700">Enforce maximum shares limit</span>
                </label>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Exception Policy</label>
                <textarea name="exception_policy" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"></textarea>
                <p class="text-xs text-gray-500 mt-1">Document exceptions and approval process</p>
            </div>
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.shares.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection

