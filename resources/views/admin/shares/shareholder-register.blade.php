@extends('layouts.admin')

@section('page-title', 'Shareholder Register')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Shareholder Register</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Complete register of all shareholders</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-bold text-[#015425]">Shareholder Register</h2>
            <button class="mt-4 md:mt-0 px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Export Register</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shares</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ownership %</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No shareholders found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

