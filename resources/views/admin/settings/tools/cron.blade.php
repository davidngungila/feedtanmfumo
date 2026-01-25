@extends('layouts.admin')

@section('page-title', 'Cron Jobs')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Cron Jobs</h1>
        <p class="text-white text-opacity-90">Manage scheduled tasks</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">Daily Interest Calculation</h3>
                <p class="text-sm text-gray-600">Schedule: Daily at 12:00 AM</p>
                <p class="text-xs text-gray-500 mt-1">Calculates interest for savings and investments</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">Payment Reminders</h3>
                <p class="text-sm text-gray-600">Schedule: Daily at 9:00 AM</p>
                <p class="text-xs text-gray-500 mt-1">Sends payment reminders to members</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">Report Generation</h3>
                <p class="text-sm text-gray-600">Schedule: Weekly on Monday at 8:00 AM</p>
                <p class="text-xs text-gray-500 mt-1">Generates weekly financial reports</p>
            </div>
        </div>
    </div>
</div>
@endsection


