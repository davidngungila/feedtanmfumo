@extends('layouts.admin')

@section('page-title', 'Share Capital Growth Analysis')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Capital Growth Analysis</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Analyze share capital growth trends</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">YTD Growth</p>
            <p class="text-2xl font-bold text-green-600">0%</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Monthly Growth</p>
            <p class="text-2xl font-bold text-blue-600">0%</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">New Shareholders</p>
            <p class="text-2xl font-bold text-purple-600">0</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600 mb-1">Average Shares/Member</p>
            <p class="text-2xl font-bold text-orange-600">0</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Growth Trends</h2>
        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <p class="text-gray-500">Growth chart will be displayed here</p>
        </div>
    </div>
</div>
@endsection

