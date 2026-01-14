@extends('layouts.admin')

@section('page-title', 'Share Capital Report')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Capital Report</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive share capital analysis</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <button class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">Export PDF</button>
                <button class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">Export Excel</button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Total Share Capital</p>
            <p class="text-2xl font-bold text-blue-600">0 TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Issued Shares</p>
            <p class="text-2xl font-bold text-green-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Outstanding Shares</p>
            <p class="text-2xl font-bold text-purple-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Shareholders</p>
            <p class="text-2xl font-bold text-orange-600">0</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Share Capital Summary</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shares</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value per Share</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percentage</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No data available</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

