@extends('layouts.admin')

@section('page-title', 'Assign Staff from Members')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Assign Staff from Members</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Select an approved member to assign staff role</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                Back to Users
            </a>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('admin.users.officials.store') }}" class="space-y-6">
            @csrf

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> Only approved members can be assigned staff roles. Staff members are selected from the existing member list.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Member *</label>
                <select name="member_id" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">-- Select Approved Member --</option>
                    @foreach($approvedMembers as $member)
                    <option value="{{ $member->id }}">
                        {{ $member->name }} ({{ $member->email }}) - {{ $member->membershipType->name ?? 'N/A' }} - {{ $member->membership_code ?? 'N/A' }}
                    </option>
                    @endforeach
                </select>
                @if($approvedMembers->isEmpty())
                <p class="mt-2 text-sm text-red-600">No approved members available. Please approve members first.</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Staff Role *</label>
                <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option value="">-- Select Role --</option>
                    <option value="loan_officer">Loan Officer</option>
                    <option value="deposit_officer">Deposit Officer</option>
                    <option value="investment_officer">Investment Officer</option>
                    <option value="chairperson">Chairperson</option>
                    <option value="secretary">Secretary</option>
                    <option value="accountant">Accountant</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Roles (Optional)</label>
                <div class="space-y-2">
                    @foreach($roles as $role)
                    <label class="flex items-center">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                               class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        <span class="ml-2 text-sm text-gray-700">{{ $role->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Position/Title</label>
                    <input type="text" name="position" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <input type="text" name="department" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Assign Staff Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
