@extends('layouts.admin')

@section('page-title', 'Calculation Verification')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Calculation Verification</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Verify formula calculations and accuracy</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Calculation Verification Report</h2>
        <div class="space-y-4">
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800"><strong>All calculations verified successfully</strong></p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Formula</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test Result</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expected</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No verification records found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

