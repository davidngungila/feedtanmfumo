@extends('layouts.admin')

@section('page-title', 'Register New Member')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Register New Member</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Multi-step registration with automatic saving</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.users.upload') }}" class="inline-flex items-center px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Bulk Upload
                </a>
                <a href="{{ route('admin.users.directory') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Directory
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md">
            <p class="font-semibold mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Multi-Step Registration Form -->
    <form id="multiStepForm" class="bg-white rounded-lg shadow-md p-4 sm:p-6 lg:p-8" onsubmit="return false;">
        @csrf
        
        <!-- Progress Modal -->
        <div id="progressModal" class="fixed inset-0 backdrop-blur-sm bg-white/30 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg p-8 flex flex-col items-center shadow-xl max-w-sm w-full mx-4">
                <div class="mb-6">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#015425]"></div>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Processing...</h3>
                <p id="progressMessage" class="text-gray-600 text-sm mb-4">Saving step data...</p>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                    <div id="progressBar" class="bg-[#015425] h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                
                <!-- Progress Percentage -->
                <div class="text-2xl font-bold text-[#015425]">
                    <span id="progressPercentage">0</span>%
                </div>
            </div>
        </div>
        
        <!-- Step Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="flex items-center">
                            <div id="stepIndicator{{ $i }}" class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium transition-colors duration-300
                                {{ $i == 1 ? 'bg-[#015425] text-white' : 'bg-gray-200 text-gray-500' }}">
                                {{ $i }}
                            </div>
                            @if($i < 4)
                                <div class="w-16 h-1 bg-gray-200 mx-2" id="progressLine{{ $i }}"></div>
                            @endif
                        </div>
                    @endfor
                </div>
                <span class="text-sm font-medium text-gray-700" id="stepTitle">Step 1: Basic Information</span>
            </div>
            <div class="flex space-x-4 text-xs text-gray-600">
                <span>Basic Info</span>
                <span>Personal Details</span>
                <span>Membership</span>
                <span>Additional</span>
            </div>
        </div>

        <!-- Step 1: Basic Information -->
        <div id="step1" class="step-content">
            <h2 class="text-xl font-semibold text-[#015425] mb-6">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter full name">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="email@example.com">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" id="phone" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="+255 123 456 789">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alternate Phone</label>
                    <input type="tel" name="alternate_phone" id="alternate_phone" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="+255 987 654 321">
                    @error('alternate_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                    <input type="date" name="date_of_birth" id="date_of_birth" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                    @error('date_of_birth')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Must be 18 years or older</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" id="gender" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    @error('gender')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">National ID Number <span class="text-red-500">*</span></label>
                    <input type="text" name="national_id" id="national_id" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter NIDA number">
                    @error('national_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status</label>
                    <select name="marital_status" id="marital_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Status</option>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                        <option value="divorced">Divorced</option>
                        <option value="widowed">Widowed</option>
                    </select>
                    @error('marital_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Step 2: Personal Details -->
        <div id="step2" class="step-content hidden">
            <h2 class="text-xl font-semibold text-[#015425] mb-6">Personal Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Street Address <span class="text-red-500">*</span></label>
                    <textarea name="address" id="address" rows="2" required 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                              placeholder="Enter complete street address"></textarea>
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" id="city" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter city">
                    @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Region/State <span class="text-red-500">*</span></label>
                    <input type="text" name="region" id="region" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter region">
                    @error('region')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                    <input type="text" name="postal_code" id="postal_code" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter postal code">
                    @error('postal_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                    <input type="text" name="occupation" id="occupation" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter occupation">
                    @error('occupation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employer Name</label>
                    <input type="text" name="employer" id="employer" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter employer name">
                    @error('employer')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Income (TZS)</label>
                    <input type="number" name="monthly_income" id="monthly_income" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="0.00">
                    @error('monthly_income')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employment Status</label>
                    <select name="employment_status" id="employment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Status</option>
                        <option value="employed">Employed</option>
                        <option value="self_employed">Self-Employed</option>
                        <option value="unemployed">Unemployed</option>
                        <option value="student">Student</option>
                        <option value="retired">Retired</option>
                    </select>
                    @error('employment_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Step 3: Membership Information -->
        <div id="step3" class="step-content hidden">
            <h2 class="text-xl font-semibold text-[#015425] mb-6">Membership Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Member Number</label>
                    <div class="flex">
                        <input type="text" name="member_number" id="member_number" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-[#015425] focus:border-[#015425]"
                               placeholder="Auto-generated if left empty">
                        <button type="button" onclick="generateMemberNumber()" class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-200 transition">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                    @error('member_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Leave empty for auto-generation</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Membership Type</label>
                    <select name="membership_type_id" id="membership_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Membership Type</option>
                        @foreach($membershipTypes as $type)
                            <option value="{{ $type->id }}" 
                                    data-entrance-fee="{{ $type->entrance_fee }}"
                                    data-capital="{{ $type->capital_contribution }}"
                                    data-min-shares="{{ $type->minimum_shares }}">
                                {{ $type->name }} - TZS {{ number_format($type->entrance_fee, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('membership_type_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Membership Status</label>
                    <select name="membership_status" id="membership_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="pending" selected>Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    @error('membership_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Shares</label>
                    <input type="number" name="number_of_shares" id="number_of_shares" value="0" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('number_of_shares')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Entrance Fee (TZS)</label>
                    <input type="number" name="entrance_fee" id="entrance_fee" value="0" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('entrance_fee')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capital Contribution (TZS)</label>
                    <input type="number" name="capital_contribution" id="capital_contribution" value="0" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('capital_contribution')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Introduced By</label>
                    <input type="text" name="introduced_by" id="introduced_by" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Member name or code">
                    @error('introduced_by')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Step 4: Additional Information -->
        <div id="step4" class="step-content hidden">
            <h2 class="text-xl font-semibold text-[#015425] mb-6">Additional Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                    <input type="text" name="bank_name" id="bank_name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter bank name">
                    @error('bank_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Branch</label>
                    <input type="text" name="bank_branch" id="bank_branch" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter branch name">
                    @error('bank_branch')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                    <input type="text" name="bank_account_number" id="bank_account_number" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter account number">
                    @error('bank_account_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reference Number</label>
                    <input type="text" name="payment_reference_number" id="payment_reference_number" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter payment reference">
                    @error('payment_reference_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425] pr-10">
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="password-eye" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Minimum 8 characters with letters and numbers</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425] pr-10">
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg id="password_confirmation-eye" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Member Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="pending" selected>Pending</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">KYC Status</label>
                    <select name="kyc_status" id="kyc_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="pending" selected>Pending</option>
                        <option value="verified">Verified</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    @error('kyc_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes/Remarks</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                              placeholder="Enter any additional notes or remarks about this member..."></textarea>
                    @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Step 5: Upload Documents & KYC -->
        <div id="step5" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Passport Picture</label>
                    <input type="file" name="passport_picture" id="passport_picture" accept="image/*" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <p class="mt-1 text-xs text-gray-500">Upload a clear passport photo (JPG, PNG format)</p>
                    @error('passport_picture')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIDA Picture</label>
                    <input type="file" name="nida_picture" id="nida_picture" accept="image/*" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <p class="mt-1 text-xs text-gray-500">Upload NIDA identification card photo</p>
                    @error('nida_picture')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Application Letter</label>
                    <input type="file" name="application_letter" id="application_letter" accept=".pdf,.doc,.docx" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <p class="mt-1 text-xs text-gray-500">Upload application letter (PDF, DOC, DOCX format)</p>
                    @error('application_letter')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Slip</label>
                    <input type="file" name="payment_slip" id="payment_slip" accept="image/*,.pdf" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <p class="mt-1 text-xs text-gray-500">Upload payment slip (JPG, PNG, PDF format)</p>
                    @error('payment_slip')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Standing Order</label>
                    <input type="file" name="standing_order" id="standing_order" accept=".pdf,.doc,.docx" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <p class="mt-1 text-xs text-gray-500">Upload bank standing order letter (PDF, DOC, DOCX format)</p>
                    @error('standing_order')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
            <button type="button" id="prevBtn" onclick="changeStep(-1)" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Previous
            </button>
            
            <div class="text-sm text-gray-600">
                <span class="font-medium">Required fields are marked with <span class="text-red-500">*</span></span>
            </div>
            
            <button type="button" id="nextBtn" onclick="changeStep(1)" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                Next
                <svg class="w-5 h-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <button type="submit" id="submitBtn" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-medium hidden">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Register Member
            </button>
        </div>
    </form>
</div>

<script>
let currentStep = 1;
const totalSteps = 5;
let registrationToken = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Check for existing registration
    const urlParams = new URLSearchParams(window.location.search);
    registrationToken = urlParams.get('token');
    
    if (registrationToken) {
        loadRegistrationData();
    }
    
    updateStepDisplay();
    setupEventListeners();
});

function updateStepDisplay() {
    // Hide all steps
    for (let i = 1; i <= totalSteps; i++) {
        document.getElementById('step' + i).classList.add('hidden');
    }
    
    // Show current step
    document.getElementById('step' + currentStep).classList.remove('hidden');
    
    // Update progress indicators
    for (let i = 1; i <= totalSteps; i++) {
        const indicator = document.getElementById('stepIndicator' + i);
        const line = document.getElementById('progressLine' + i);
        
        if (i < currentStep) {
            indicator.className = 'w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium bg-green-500 text-white';
            if (line) line.className = 'w-16 h-1 bg-green-500 mx-2';
        } else if (i === currentStep) {
            indicator.className = 'w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium bg-[#015425] text-white';
            if (line) line.className = 'w-16 h-1 bg-gray-200 mx-2';
        } else {
            indicator.className = 'w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium bg-gray-200 text-gray-500';
            if (line) line.className = 'w-16 h-1 bg-gray-200 mx-2';
        }
    }
    
    // Update step title
    const stepTitles = [
        'Step 1: Basic Information',
        'Step 2: Personal Details', 
        'Step 3: Membership Information',
        'Step 4: Additional Information',
        'Step 5: Upload Documents & KYC'
    ];
    document.getElementById('stepTitle').textContent = stepTitles[currentStep - 1];
    
    // Update buttons
    document.getElementById('prevBtn').disabled = currentStep === 1;
    
    // Update navigation buttons
    if (currentStep === totalSteps) {
        document.getElementById('nextBtn').classList.add('hidden');
        document.getElementById('submitBtn').classList.remove('hidden');
        document.getElementById('submitBtn').textContent = 'Register Member';
    } else {
        document.getElementById('nextBtn').classList.remove('hidden');
        document.getElementById('submitBtn').classList.add('hidden');
    }
}

async function changeStep(direction) {
    // Save current step data
    if (direction > 0) {
        const isValid = await validateCurrentStep();
        if (!isValid) return;
        
        await saveStepData(currentStep);
    }
    
    const newStep = currentStep + direction;
    if (newStep >= 1 && newStep <= totalSteps) {
        currentStep = newStep;
        updateStepDisplay();
        
        // Update URL with token if exists
        if (registrationToken) {
            const url = new URL(window.location);
            url.searchParams.set('token', registrationToken);
            window.history.replaceState({}, '', url);
        }
    }
}

async function validateCurrentStep() {
    const currentStepElement = document.getElementById('step' + currentStep);
    
    if (!currentStepElement) {
        return false;
    }
    
    const requiredFields = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
    
    for (let field of requiredFields) {
        if (!field.value.trim()) {
            field.focus();
            field.classList.add('border-red-500');
            
            // Show error message
            const errorMsg = document.createElement('p');
            errorMsg.className = 'mt-1 text-sm text-red-600';
            errorMsg.textContent = 'This field is required';
            
            // Remove existing error if any
            const existingError = field.parentNode.querySelector('.text-red-600');
            if (existingError) {
                existingError.remove();
            }
            
            field.parentNode.appendChild(errorMsg);
            
            setTimeout(() => {
                field.classList.remove('border-red-500');
                errorMsg.remove();
            }, 3000);
            
            return false;
        }
    }
    
    return true;
}

// Progress animation function
function animateProgress(start, end, duration, message) {
    const progressBar = document.getElementById('progressBar');
    const progressPercentage = document.getElementById('progressPercentage');
    const progressMessage = document.getElementById('progressMessage');
    
    if (message) {
        progressMessage.textContent = message;
    }
    
    const startTime = performance.now();
    
    function updateProgress(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min((elapsed / duration) * 100, 100);
        const currentProgress = start + (progress * (end - start) / 100);
        
        progressBar.style.width = currentProgress + '%';
        progressPercentage.textContent = Math.round(currentProgress);
        
        if (progress < 100) {
            requestAnimationFrame(updateProgress);
        }
    }
    
    requestAnimationFrame(updateProgress);
}

async function saveStepData(step) {
    const stepElement = document.getElementById('step' + step);
    
    if (!stepElement) {
        return;
    }
    
    // Manually collect form data from step elements
    const stepData = {};
    const inputs = stepElement.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        if (input.type === 'checkbox') {
            stepData[input.name] = input.checked;
        } else if (input.type === 'radio') {
            if (input.checked) {
                stepData[input.name] = input.value;
            }
        } else if (input.type === 'file') {
            // Handle file uploads - store file info
            if (input.files.length > 0) {
                stepData[input.name] = {
                    name: input.files[0].name,
                    size: input.files[0].size,
                    type: input.files[0].type
                };
            }
        } else {
            stepData[input.name] = input.value;
        }
    });
    
    try {
        // Show progress modal
        document.getElementById('progressModal').classList.remove('hidden');
        
        // Start progress animation
        animateProgress(0, 30, 500, 'Validating data...');
        
        // Simulate validation delay
        await new Promise(resolve => setTimeout(resolve, 500));
        
        animateProgress(30, 70, 1000, 'Saving to database...');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF Token:', csrfToken);
        console.log('Step data being sent:', { step, data: stepData, token: registrationToken });
        
        const response = await fetch('/admin/users/save-step', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                step: step,
                data: stepData,
                token: registrationToken
            })
        });
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const errorText = await response.text();
            console.error('Non-JSON response:', errorText);
            throw new Error('Server returned HTML instead of JSON. Status: ' + response.status + ' ' + response.statusText);
        }
        
        const result = await response.json();
        
        // Complete progress
        animateProgress(70, 100, 500, 'Finalizing...');
        
        // Simulate finalization delay
        await new Promise(resolve => setTimeout(resolve, 500));
        
        // Hide progress modal
        document.getElementById('progressModal').classList.add('hidden');
        
        if (result.success) {
            registrationToken = result.token;
            showSuccessMessage('Step ' + step + ' saved successfully');
            
            // Update URL with token for persistence
            const url = new URL(window.location);
            url.searchParams.set('token', registrationToken);
            window.history.replaceState({}, '', url);
            
        } else {
            alert('Error saving step: ' + result.message);
        }
    } catch (error) {
        document.getElementById('progressModal').classList.add('hidden');
        console.error('Save step error:', error);
        alert('Error saving step data: ' + error.message);
    }
}

async function loadRegistrationData() {
    try {
        const response = await fetch('/admin/users/load-registration?token=' + registrationToken);
        const result = await response.json();
        
        if (result.success) {
            // Populate form with saved data
            const allData = result.data;
            
            for (let step = 1; step <= totalSteps; step++) {
                const stepData = allData['step_' + step] || {};
                
                Object.keys(stepData).forEach(key => {
                    const field = document.getElementById(key);
                    if (field) {
                        if (field.type === 'checkbox') {
                            field.checked = stepData[key];
                        } else {
                            field.value = stepData[key];
                        }
                    }
                });
            }
            
            currentStep = result.current_step || 1;
            updateStepDisplay();
        }
    } catch (error) {
        console.error('Error loading registration data:', error);
    }
}

function generateMemberNumber() {
    const prefix = 'MEM';
    const random = Math.random().toString(36).substring(2, 10).toUpperCase();
    document.getElementById('member_number').value = `${prefix}-${random}`;
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.025m3.856 1.735a9.97 9.97 0 013.856-1.735m0 0a10.05 10.05 0 01-3.856 1.735"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>';
    } else {
        field.type = 'password';
        eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
}

function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-4';
    successDiv.textContent = message;
    
    const form = document.getElementById('multiStepForm');
    form.parentNode.insertBefore(successDiv, form);
    
    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

// Final form submission
document.getElementById('submitBtn').addEventListener('click', async function(e) {
    e.preventDefault();
    
    const isValid = await validateCurrentStep();
    if (!isValid) return;
    
    // Save final step data first
    await saveStepData(currentStep);
    
    // Submit the complete form using AJAX
    try {
        // Show progress modal
        document.getElementById('progressModal').classList.remove('hidden');
        
        // Start progress animation
        animateProgress(0, 20, 500, 'Preparing final submission...');
        
        // Simulate preparation delay
        await new Promise(resolve => setTimeout(resolve, 500));
        
        animateProgress(20, 60, 1000, 'Creating user account...');
        
        const formData = new FormData();
        formData.append('registration_token', registrationToken);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('Final submission CSRF Token:', csrfToken);
        console.log('Registration token for final submission:', registrationToken);
        
        const response = await fetch('/admin/users/store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const errorText = await response.text();
            console.error('Non-JSON response from store:', errorText);
            throw new Error('Server returned HTML instead of JSON. Status: ' + response.status + ' ' + response.statusText);
        }
        
        const result = await response.json();
        
        // Complete progress
        animateProgress(60, 100, 800, 'Finalizing registration...');
        
        // Simulate finalization delay
        await new Promise(resolve => setTimeout(resolve, 800));
        
        // Hide progress modal
        document.getElementById('progressModal').classList.add('hidden');
        
        if (result.success) {
            // Redirect to users list
            window.location.href = '/admin/users';
        } else {
            if (result.errors) {
                // Show validation errors
                let errorMessage = 'Please fix the following errors:\n';
                for (const [field, errors] of Object.entries(result.errors)) {
                    errorMessage += `\n${field}: ${errors.join(', ')}`;
                }
                alert(errorMessage);
            } else {
                alert('Error creating user: ' + result.message);
            }
        }
    } catch (error) {
        document.getElementById('progressModal').classList.add('hidden');
        console.error('Submit error:', error);
        alert('Error creating user: ' + error.message);
    }
});
</script>
@endsection
