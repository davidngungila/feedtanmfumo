@extends('layouts.admin')

@section('page-title', 'Share Balance per Member')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Balance per Member</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">View share holdings for each member</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-bold text-[#015425]">Member Share Balances</h2>
            <input type="text" placeholder="Search member..." class="mt-4 md:mt-0 px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Shares</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Share Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ownership %</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No share balances found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

