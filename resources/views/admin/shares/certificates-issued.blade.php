@extends('layouts.admin')

@section('page-title', 'Share Certificates Issued')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Certificates Issued</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Manage share certificates</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Total Certificates</p>
            <p class="text-2xl font-bold text-blue-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Pending Issuance</p>
            <p class="text-2xl font-bold text-orange-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">This Month</p>
            <p class="text-2xl font-bold text-green-600">0</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-bold text-[#015425]">Issued Certificates</h2>
            <button class="mt-4 md:mt-0 px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Issue New Certificate</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Certificate #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shares</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No certificates issued</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

