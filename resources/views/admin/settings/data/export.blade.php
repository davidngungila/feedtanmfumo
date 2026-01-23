@extends('layouts.admin')

@section('page-title', 'Export Data')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Export Data</h1>
        <p class="text-white text-opacity-90">Export system data to CSV or Excel</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="#" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Type</label>
                    <select name="data_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="users">Users</option>
                        <option value="loans">Loans</option>
                        <option value="savings">Savings Accounts</option>
                        <option value="investments">Investments</option>
                        <option value="transactions">Transactions</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                    <select name="format" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="csv">CSV</option>
                        <option value="xlsx">Excel (XLSX)</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">Export Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

