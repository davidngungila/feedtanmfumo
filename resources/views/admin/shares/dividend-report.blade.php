@extends('layouts.admin')

@section('page-title', 'Dividend Distribution Report')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Dividend Distribution Report</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive dividend distribution analysis</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Dividend Distribution Report</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Dividend</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dividend per Share</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shareholders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No dividend distributions found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

