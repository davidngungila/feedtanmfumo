@extends('layouts.admin')

@section('page-title', 'Register New Member')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Register New Member</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Complete member registration form with comprehensive information and advanced features</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
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

    <!-- Registration Form -->
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" id="memberRegistrationForm" class="bg-white rounded-lg shadow-md p-4 sm:p-6 lg:p-8">
        @csrf

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Registration Progress</span>
                <span class="text-sm font-medium text-[#015425]" id="progressText">Step 1 of 7</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-[#015425] h-2 rounded-full transition-all duration-300" id="progressBar" style="width: 14.28%"></div>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="mb-8 form-section" data-section="1">
            <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Personal Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter full name">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                    @error('date_of_birth')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Must be 18 years or older</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">National ID Number <span class="text-red-500">*</span></label>
                    <input type="text" name="national_id" value="{{ old('national_id') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter NIDA number">
                    @error('national_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status</label>
                    <select name="marital_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Status</option>
                        <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                        <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                    </select>
                    @error('marital_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Member Number</label>
                    <div class="flex">
                        <input type="text" name="member_number" id="member_number" value="{{ old('member_number') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-[#015425] focus:border-[#015425]"
                               placeholder="Auto-generated if left empty">
                        <button type="button" onclick="generateMemberNumber()" class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-200 transition" title="Generate Member Number">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                    @error('member_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Leave empty for auto-generation</p>
                </div>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div class="mb-8 form-section" data-section="2">
            <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Contact Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="email@example.com">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="+255 123 456 789">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alternate Phone</label>
                    <input type="tel" name="alternate_phone" value="{{ old('alternate_phone') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="+255 987 654 321">
                    @error('alternate_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Address Information Section -->
        <div class="mb-8 form-section" data-section="3">
            <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Address Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Street Address <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="2" required 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                              placeholder="Enter complete street address">{{ old('address') }}</textarea>
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter city">
                    @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Region/State <span class="text-red-500">*</span></label>
                    <input type="text" name="region" value="{{ old('region') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter region">
                    @error('region')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter postal code">
                    @error('postal_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Employment Information Section -->
        <div class="mb-8 form-section" data-section="4">
            <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Employment Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter occupation">
                    @error('occupation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Title</label>
                    <input type="text" name="job_title" value="{{ old('job_title') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter job title">
                    @error('job_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employer Name</label>
                    <input type="text" name="employer" value="{{ old('employer') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter employer name">
                    @error('employer')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Income (TZS)</label>
                    <input type="number" name="monthly_income" value="{{ old('monthly_income') }}" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="0.00">
                    @error('monthly_income')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employment Status</label>
                    <select name="employment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Status</option>
                        <option value="employed" {{ old('employment_status') == 'employed' ? 'selected' : '' }}>Employed</option>
                        <option value="self_employed" {{ old('employment_status') == 'self_employed' ? 'selected' : '' }}>Self-Employed</option>
                        <option value="unemployed" {{ old('employment_status') == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                        <option value="student" {{ old('employment_status') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="retired" {{ old('employment_status') == 'retired' ? 'selected' : '' }}>Retired</option>
                    </select>
                    @error('employment_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Membership Information Section -->
        <div class="mb-8 form-section" data-section="5">
            <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Membership Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Membership Type</label>
                    <select name="membership_type_id" id="membership_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Membership Type</option>
                        @foreach($membershipTypes as $type)
                            <option value="{{ $type->id }}" 
                                    data-entrance-fee="{{ $type->entrance_fee }}"
                                    data-capital="{{ $type->capital_contribution }}"
                                    data-min-shares="{{ $type->minimum_shares }}"
                                    {{ old('membership_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} - TZS {{ number_format($type->entrance_fee, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('membership_type_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Membership Status</label>
                    <select name="membership_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="pending" {{ old('membership_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('membership_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('membership_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('membership_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Shares</label>
                    <input type="number" name="number_of_shares" id="number_of_shares" value="{{ old('number_of_shares', 0) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('number_of_shares')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Entrance Fee (TZS)</label>
                    <input type="number" name="entrance_fee" id="entrance_fee" value="{{ old('entrance_fee', 0) }}" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('entrance_fee')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capital Contribution (TZS)</label>
                    <input type="number" name="capital_contribution" id="capital_contribution" value="{{ old('capital_contribution', 0) }}" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('capital_contribution')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Introduced By</label>
                    <input type="text" name="introduced_by" value="{{ old('introduced_by') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Member name or code">
                    @error('introduced_by')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Membership Type Info Card -->
            <div id="membershipInfoCard" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-blue-900" id="membershipTypeName"></p>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-2 text-xs text-blue-700">
                            <div>Entrance Fee: <span id="infoEntranceFee" class="font-semibold">TZS 0.00</span></div>
                            <div>Min Capital: <span id="infoCapital" class="font-semibold">TZS 0.00</span></div>
                            <div>Min Shares: <span id="infoMinShares" class="font-semibold">0</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Information Section -->
        <div class="mb-8 form-section" data-section="6">
            <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Bank Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter bank name">
                    @error('bank_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Branch</label>
                    <input type="text" name="bank_branch" value="{{ old('bank_branch') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter branch name">
                    @error('bank_branch')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter account number">
                    @error('bank_account_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reference Number</label>
                    <input type="text" name="payment_reference_number" value="{{ old('payment_reference_number') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter payment reference">
                    @error('payment_reference_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Group Information Section -->
        <div class="mb-8 form-section" data-section="7">
            <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Group Information (Optional)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Group Name</label>
                    <input type="text" name="group_name" value="{{ old('group_name') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter group name">
                    @error('group_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center mt-6">
                    <input type="checkbox" name="group_registered" id="group_registered" value="1" {{ old('group_registered') ? 'checked' : '' }}
                           class="h-4 w-4 text-[#015425] focus:ring-[#015425] border-gray-300 rounded">
                    <label for="group_registered" class="ml-2 block text-sm text-gray-900">Is group registered?</label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Group Leaders</label>
                    <input type="text" name="group_leaders" value="{{ old('group_leaders') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter group leaders">
                    @error('group_leaders')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Group Bank Account</label>
                    <input type="text" name="group_bank_account" value="{{ old('group_bank_account') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="Enter group bank account">
                    @error('group_bank_account')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- KYC Documents Section -->
        <div class="mb-8 form-section" data-section="8">
            <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                KYC Documents (Optional)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label for="passport_picture" class="block text-sm font-medium text-gray-700 mb-2">Passport Size Picture</label>
                    <input type="file" name="passport_picture" id="passport_picture" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#013019]">
                    @error('passport_picture')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Max size: 2MB, Formats: JPG, PNG</p>
                </div>

                <div>
                    <label for="nida_picture" class="block text-sm font-medium text-gray-700 mb-2">NIDA ID Picture</label>
                    <input type="file" name="nida_picture" id="nida_picture" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#013019]">
                    @error('nida_picture')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Max size: 2MB, Formats: JPG, PNG</p>
                </div>

                <div>
                    <label for="application_letter" class="block text-sm font-medium text-gray-700 mb-2">Application Letter</label>
                    <input type="file" name="application_letter" id="application_letter" accept=".pdf,.doc,.docx"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#013019]">
                    @error('application_letter')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Max size: 2MB, Formats: PDF, DOC, DOCX</p>
                </div>

                <div>
                    <label for="payment_slips" class="block text-sm font-medium text-gray-700 mb-2">Payment Slips (Multiple)</label>
                    <input type="file" name="payment_slips[]" id="payment_slips" multiple accept=".pdf,.jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#013019]">
                    @error('payment_slips')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Max size: 2MB per file</p>
                </div>
            </div>
        </div>

        <!-- Account Setup Section -->
        <div class="mb-8 form-section" data-section="9">
            <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                Account Setup & Security
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
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
                    <div class="mt-2 space-y-1">
                        <div class="flex items-center text-xs" id="password-length">
                            <span class="w-2 h-2 rounded-full bg-gray-300 mr-2"></span>
                            <span class="text-gray-600">At least 8 characters</span>
                        </div>
                        <div class="flex items-center text-xs" id="password-number">
                            <span class="w-2 h-2 rounded-full bg-gray-300 mr-2"></span>
                            <span class="text-gray-600">Contains a number</span>
                        </div>
                    </div>
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
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">KYC Status</label>
                    <select name="kyc_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="pending" {{ old('kyc_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ old('kyc_status') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="rejected" {{ old('kyc_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('kyc_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Roles Assignment Section -->
        <div class="mb-8 form-section" data-section="10">
            <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Roles & Permissions (Optional)
            </h2>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-900">Note</p>
                        <p class="text-sm text-yellow-700 mt-1">Assigning roles will grant additional permissions. Only assign roles if this user needs staff/admin access.</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($roles as $role)
                    @if($role->slug !== 'admin' || auth()->user()->isAdmin())
                        <label class="flex items-center space-x-2 p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }} 
                                   class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                            <span class="text-sm">{{ $role->name }}</span>
                        </label>
                    @endif
                @endforeach
            </div>
            @error('roles')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Additional Notes Section -->
        <div class="mb-8 form-section" data-section="11">
            <h2 class="text-xl font-semibold text-[#015425] mb-4 pb-2 border-b border-gray-200 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Additional Notes
            </h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes/Remarks</label>
                <textarea name="notes" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                          placeholder="Enter any additional notes or remarks about this member...">{{ old('notes') }}</textarea>
                @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
            <div class="text-sm text-gray-600">
                <span class="font-medium">Required fields are marked with <span class="text-red-500">*</span></span>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.users.directory') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Register Member
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Progress tracking
function updateProgress() {
    const sections = document.querySelectorAll('.form-section');
    const filledSections = Array.from(sections).filter(section => {
        const inputs = section.querySelectorAll('input[required], select[required]');
        return Array.from(inputs).some(input => input.value.trim() !== '');
    }).length;
    
    const progress = (filledSections / sections.length) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
    document.getElementById('progressText').textContent = `Step ${filledSections} of ${sections.length}`;
}

// Member number generation
function generateMemberNumber() {
    const prefix = 'MEM';
    const random = Math.random().toString(36).substring(2, 10).toUpperCase();
    document.getElementById('member_number').value = `${prefix}-${random}`;
}

// Membership type info
document.getElementById('membership_type_id')?.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const card = document.getElementById('membershipInfoCard');
    
    if (selected.value) {
        const entranceFee = selected.dataset.entranceFee || 0;
        const capital = selected.dataset.capital || 0;
        const minShares = selected.dataset.minShares || 0;
        
        document.getElementById('membershipTypeName').textContent = selected.text;
        document.getElementById('infoEntranceFee').textContent = `TZS ${parseFloat(entranceFee).toLocaleString()}`;
        document.getElementById('infoCapital').textContent = `TZS ${parseFloat(capital).toLocaleString()}`;
        document.getElementById('infoMinShares').textContent = minShares;
        
        // Auto-fill entrance fee and capital
        document.getElementById('entrance_fee').value = entranceFee;
        document.getElementById('capital_contribution').value = capital;
        document.getElementById('number_of_shares').value = minShares;
        
        card.classList.remove('hidden');
    } else {
        card.classList.add('hidden');
    }
});

// Password validation
document.getElementById('password')?.addEventListener('input', function() {
    const password = this.value;
    const lengthCheck = document.getElementById('password-length');
    const numberCheck = document.getElementById('password-number');
    
    // Length check
    if (password.length >= 8) {
        lengthCheck.querySelector('span').classList.remove('bg-gray-300');
        lengthCheck.querySelector('span').classList.add('bg-green-500');
        lengthCheck.querySelector('span + span').classList.remove('text-gray-600');
        lengthCheck.querySelector('span + span').classList.add('text-green-600');
    } else {
        lengthCheck.querySelector('span').classList.remove('bg-green-500');
        lengthCheck.querySelector('span').classList.add('bg-gray-300');
        lengthCheck.querySelector('span + span').classList.remove('text-green-600');
        lengthCheck.querySelector('span + span').classList.add('text-gray-600');
    }
    
    // Number check
    if (/\d/.test(password)) {
        numberCheck.querySelector('span').classList.remove('bg-gray-300');
        numberCheck.querySelector('span').classList.add('bg-green-500');
        numberCheck.querySelector('span + span').classList.remove('text-gray-600');
        numberCheck.querySelector('span + span').classList.add('text-green-600');
    } else {
        numberCheck.querySelector('span').classList.remove('bg-green-500');
        numberCheck.querySelector('span').classList.add('bg-gray-300');
        numberCheck.querySelector('span + span').classList.remove('text-green-600');
        numberCheck.querySelector('span + span').classList.add('text-gray-600');
    }
});

// Password toggle
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    if (field.type === 'password') {
        field.type = 'text';
        eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
    } else {
        field.type = 'password';
        eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
}

// Track form changes for progress
document.querySelectorAll('input, select, textarea').forEach(input => {
    input.addEventListener('change', updateProgress);
    input.addEventListener('input', updateProgress);
});

// Initial progress update
updateProgress();
</script>
@endsection
