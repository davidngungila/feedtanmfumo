@extends('layouts.admin')

@section('page-title', 'Edit Member')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex items-center space-x-4 flex-1">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-1">Edit Member</h1>
                    <p class="text-white text-opacity-90 text-sm sm:text-base">{{ $user->name }}</p>
                    @if($user->member_number)
                    <p class="text-white text-opacity-80 text-xs mt-1">Member #: {{ $user->member_number }}</p>
                    @endif
                </div>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Details
                </a>
                <a href="{{ route('admin.users.directory') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-[#015425] rounded-md transition font-medium">
                    Back to Directory
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-800 px-4 py-3 rounded-md shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 text-red-800 px-4 py-3 rounded-md shadow-sm">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="list-disc list-inside ml-7">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tab Navigation -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px" id="form-tabs">
                        <button type="button" class="tab-button active px-6 py-3 text-sm font-medium text-[#015425] border-b-2 border-[#015425]" data-tab="personal">
                            Personal Info
                        </button>
                        <button type="button" class="tab-button px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent" data-tab="contact">
                            Contact & Address
                        </button>
                        <button type="button" class="tab-button px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent" data-tab="employment">
                            Employment
                        </button>
                        <button type="button" class="tab-button px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent" data-tab="account">
                            Account & Status
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Edit Form -->
            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" id="edit-user-form">
                @csrf
                @method('PUT')

                <!-- Personal Information Tab -->
                <div id="tab-personal" class="tab-content">
                    <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Personal Information</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Member Number</label>
                                <input type="text" name="member_number" value="{{ old('member_number', $user->member_number) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                <p class="mt-1 text-xs text-gray-500">Unique identifier for this member</p>
                                @error('member_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('date_of_birth')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                                <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">National ID Number</label>
                                <input type="text" name="national_id" value="{{ old('national_id', $user->national_id) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                <p class="mt-1 text-xs text-gray-500">Government-issued identification number</p>
                                @error('national_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status</label>
                                <select name="marital_status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                    <option value="">Select Status</option>
                                    <option value="single" {{ old('marital_status', $user->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('marital_status', $user->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('marital_status', $user->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('marital_status', $user->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                                @error('marital_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        @if($user->membershipType)
                        <div class="pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Membership Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Membership Type</label>
                                    <input type="text" value="{{ $user->membershipType->name }}" disabled
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Membership Code</label>
                                    <input type="text" name="membership_code" value="{{ old('membership_code', $user->membership_code) }}" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Shares</label>
                                    <input type="number" name="number_of_shares" value="{{ old('number_of_shares', $user->number_of_shares ?? 0) }}" min="0" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Contact & Address Tab -->
                <div id="tab-contact" class="tab-content hidden">
                    <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Contact & Address Information</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                <p class="mt-1 text-xs text-gray-500">Used for login and notifications</p>
                                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alternate Phone</label>
                                <input type="tel" name="alternate_phone" value="{{ old('alternate_phone', $user->alternate_phone) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('alternate_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                                <input type="text" name="address" value="{{ old('address', $user->address) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input type="text" name="city" value="{{ old('city', $user->city) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Region/State</label>
                                <input type="text" name="region" value="{{ old('region', $user->region) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('region')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('postal_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Tab -->
                <div id="tab-employment" class="tab-content hidden">
                    <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Employment & Financial Information</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                                <input type="text" name="occupation" value="{{ old('occupation', $user->occupation) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('occupation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Employer Name</label>
                                <input type="text" name="employer" value="{{ old('employer', $user->employer) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('employer')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Income (TZS)</label>
                                <input type="number" name="monthly_income" value="{{ old('monthly_income', $user->monthly_income) }}" step="0.01" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                <p class="mt-1 text-xs text-gray-500">Approximate monthly earnings</p>
                                @error('monthly_income')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $user->bank_name) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('bank_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bank Branch</label>
                                <input type="text" name="bank_branch" value="{{ old('bank_branch', $user->bank_branch) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('bank_branch')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bank Account Number</label>
                                <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $user->bank_account_number) }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('bank_account_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account & Status Tab -->
                <div id="tab-account" class="tab-content hidden">
                    <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Account Status & Security</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Member Status <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                    <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">KYC Status</label>
                                <select name="kyc_status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                    <option value="pending" {{ old('kyc_status', $user->kyc_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="verified" {{ old('kyc_status', $user->kyc_status) == 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="rejected" {{ old('kyc_status', $user->kyc_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="expired" {{ old('kyc_status', $user->kyc_status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                                @error('kyc_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">KYC Expiry Date</label>
                                <input type="date" name="kyc_expiry_date" value="{{ old('kyc_expiry_date', $user->kyc_expiry_date ? $user->kyc_expiry_date->format('Y-m-d') : '') }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                @error('kyc_expiry_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Reason</label>
                                <textarea name="status_reason" rows="3" 
                                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">{{ old('status_reason', $user->status_reason) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Reason for current status (required if inactive or suspended)</p>
                                @error('status_reason')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                                <p class="text-sm text-yellow-800">
                                    <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    Leave password fields blank to keep the current password unchanged.
                                </p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <input type="password" name="password" id="password"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                </div>
                            </div>
                        </div>

                        <!-- Roles Section -->
                        @if(isset($roles) && $roles->count() > 0 && $user->role !== 'admin' && !$user->isAdmin())
                        <div class="pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Roles & Permissions</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($roles as $role)
                                    <label class="flex items-center space-x-2 p-3 border-2 rounded-md hover:bg-gray-50 cursor-pointer transition {{ $user->roles->contains($role->id) ? 'border-[#015425] bg-green-50' : 'border-gray-200' }}">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'checked' : '' }} 
                                               class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                                        <span class="text-sm font-medium">{{ $role->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('roles')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        @endif

                        <!-- Additional Notes -->
                        <div class="pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Notes</h3>
                            <textarea name="notes" rows="4" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">{{ old('notes', $user->notes) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Internal notes and remarks about this member</p>
                            @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('admin.users.directory') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium shadow-md">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Member
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Member Quick Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Information</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Member Status</p>
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ 
                            $user->status === 'active' ? 'bg-green-100 text-green-800' : 
                            ($user->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 
                            ($user->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'))
                        }}">
                            {{ ucfirst($user->status ?? 'N/A') }}
                        </span>
                    </div>
                    @if($user->member_number)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Member Number</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->member_number }}</p>
                    </div>
                    @endif
                    @if($user->email)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="text-sm font-semibold text-gray-900 break-all">{{ $user->email }}</p>
                    </div>
                    @endif
                    @if($user->phone)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Phone</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->phone }}</p>
                    </div>
                    @endif
                    @if($user->kyc_status)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">KYC Status</p>
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ 
                            $user->kyc_status === 'verified' ? 'bg-green-100 text-green-800' : 
                            ($user->kyc_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            ($user->kyc_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))
                        }}">
                            {{ ucfirst($user->kyc_status) }}
                        </span>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Member Since</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            @php
                $loansCount = $user->loans()->count();
                $savingsCount = $user->savingsAccounts()->count();
                $investmentsCount = $user->investments()->count();
            @endphp
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Member Statistics</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-md">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Active Loans</span>
                        </div>
                        <span class="text-lg font-bold text-blue-600">{{ $loansCount }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-md">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Savings Accounts</span>
                        </div>
                        <span class="text-lg font-bold text-green-600">{{ $savingsCount }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-md">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Investments</span>
                        </div>
                        <span class="text-lg font-bold text-purple-600">{{ $investmentsCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="block w-full px-4 py-2 text-center bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium">
                        View Full Profile
                    </a>
                    <a href="{{ route('admin.loans.create', ['user_id' => $user->id]) }}" class="block w-full px-4 py-2 text-center bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition font-medium">
                        Create Loan
                    </a>
                    <a href="{{ route('admin.savings.create', ['user_id' => $user->id]) }}" class="block w-full px-4 py-2 text-center bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition font-medium">
                        Open Savings Account
                    </a>
                    <a href="{{ route('admin.users.directory') }}" class="block w-full px-4 py-2 text-center bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium">
                        Back to Directory
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'text-[#015425]', 'border-[#015425]');
                btn.classList.add('text-gray-500', 'border-transparent');
            });
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Add active class to clicked button and corresponding content
            this.classList.add('active', 'text-[#015425]', 'border-[#015425]');
            this.classList.remove('text-gray-500', 'border-transparent');
            document.getElementById('tab-' + targetTab).classList.remove('hidden');
        });
    });
});
</script>
@endsection
