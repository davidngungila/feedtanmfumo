@extends('layouts.admin')

@section('page-title', 'Feature Toggles')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Feature Toggles</h1>
        <p class="text-white text-opacity-90">Enable or disable system features</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.system-settings.feature-toggles.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h3 class="font-medium text-gray-900">Loan Management</h3>
                        <p class="text-sm text-gray-500">Enable loan application and management features</p>
                    </div>
                    <input type="checkbox" name="feature_loans" value="1" 
                           {{ ($settings['feature_loans']->value ?? '1') === '1' ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h3 class="font-medium text-gray-900">Savings Accounts</h3>
                        <p class="text-sm text-gray-500">Enable savings account features</p>
                    </div>
                    <input type="checkbox" name="feature_savings" value="1" 
                           {{ ($settings['feature_savings']->value ?? '1') === '1' ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h3 class="font-medium text-gray-900">Investments</h3>
                        <p class="text-sm text-gray-500">Enable investment management features</p>
                    </div>
                    <input type="checkbox" name="feature_investments" value="1" 
                           {{ ($settings['feature_investments']->value ?? '1') === '1' ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h3 class="font-medium text-gray-900">Social Welfare</h3>
                        <p class="text-sm text-gray-500">Enable social welfare features</p>
                    </div>
                    <input type="checkbox" name="feature_welfare" value="1" 
                           {{ ($settings['feature_welfare']->value ?? '1') === '1' ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

