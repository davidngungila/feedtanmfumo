@extends('layouts.admin')

@section('page-title', 'Import Data')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Import Data</h1>
        <p class="text-white text-opacity-90">Import data from CSV or Excel files</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Type</label>
                    <select name="data_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="users">Users</option>
                        <option value="loans">Loans</option>
                        <option value="savings">Savings Accounts</option>
                        <option value="investments">Investments</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">File</label>
                    <input type="file" name="file" accept=".csv,.xlsx,.xls" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    <p class="mt-1 text-sm text-gray-500">Upload CSV or Excel file (max 10MB)</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">Import Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

