@extends('layouts.admin')

@section('page-title', 'Minimum Shares per Member')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Minimum Shares per Member</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure minimum share requirements</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Minimum Share Configuration</h2>
        <form method="POST" action="#" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Shares Required *</label>
                <input type="number" name="min_shares" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                <p class="text-xs text-gray-500 mt-1">Minimum number of shares each member must hold</p>
            </div>
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="enforce_minimum" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                    <span class="ml-2 text-sm text-gray-700">Enforce minimum shares requirement</span>
                </label>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Grace Period (Days)</label>
                <input type="number" name="grace_period" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                <p class="text-xs text-gray-500 mt-1">Number of days before enforcement begins</p>
            </div>
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.shares.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save Settings</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Members Below Minimum</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Shares</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Required</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shortfall</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No members below minimum</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

