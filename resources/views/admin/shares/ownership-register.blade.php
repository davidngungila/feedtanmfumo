@extends('layouts.admin')

@section('page-title', 'Share Ownership Register')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Ownership Register</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Complete record of share ownership</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <button class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">Export</button>
                <a href="{{ route('admin.shares.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">Back</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Total Shareholders</p>
            <p class="text-2xl font-bold text-blue-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Total Shares Issued</p>
            <p class="text-2xl font-bold text-green-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Total Share Capital</p>
            <p class="text-2xl font-bold text-purple-600">0 TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Average Shares/Member</p>
            <p class="text-2xl font-bold text-orange-600">0</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-bold text-[#015425]">Share Ownership Register</h2>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <input type="text" placeholder="Search member..." class="px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                <button class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Search</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Shares</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Share Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ownership %</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No share ownership records found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

