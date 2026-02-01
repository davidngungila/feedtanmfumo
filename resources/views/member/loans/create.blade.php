@extends('layouts.member')

@section('page-title', 'Apply for Loan')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Application</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Submit a comprehensive loan application with detailed information and supporting documents</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('member.loans.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    My Loans
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('member.loans.store') }}" method="POST" id="loan-form" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Member Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#015425]">Your Information</h2>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Full Name</p>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Email</p>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->email }}</p>
                            </div>
                            @if(Auth::user()->phone)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Phone</p>
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->phone }}</p>
                            </div>
                            @endif
                            @if(Auth::user()->membership_code)
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Membership Code</p>
                                <p class="text-sm font-semibold text-[#015425]">{{ Auth::user()->membership_code }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Loan Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#015425]">Loan Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Loan Type
                            </label>
                            <select name="loan_type" id="loan_type" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition">
                                <option value="">-- Select loan type --</option>
                                <option value="Personal">Personal Loan</option>
                                <option value="Business">Business Loan</option>
                                <option value="Agricultural">Agricultural Loan</option>
                                <option value="Education">Education Loan</option>
                                <option value="Emergency">Emergency Loan</option>
                                <option value="Asset Financing">Asset Financing</option>
                                <option value="Home Improvement">Home Improvement</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('loan_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Principal Amount (TZS) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">TZS</span>
                                <input type="number" name="principal_amount" id="principal_amount" step="0.01" min="1000" required 
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition"
                                    placeholder="0.00">
                            </div>
                            @error('principal_amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Minimum amount: 1,000 TZS</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Interest Rate (%) <span class="text-gray-500">(Optional - Default: 10%)</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="interest_rate" id="interest_rate" step="0.01" min="0" max="100" value="10"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition"
                                    placeholder="10.00">
                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                            </div>
                            @error('interest_rate')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Annual interest rate percentage</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Loan Term (Months) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="term_months" id="term_months" min="1" max="120" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition"
                                placeholder="12">
                            @error('term_months')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Repayment period in months (1-120 months)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Frequency <span class="text-red-500">*</span>
                            </label>
                            <select name="payment_frequency" id="payment_frequency" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition">
                                <option value="">-- Select frequency --</option>
                                <option value="weekly">Weekly</option>
                                <option value="bi-weekly">Bi-weekly</option>
                                <option value="monthly" selected>Monthly</option>
                            </select>
                            @error('payment_frequency')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">How often payments will be made</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Application Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="application_date" id="application_date" value="{{ date('Y-m-d') }}" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition">
                            @error('application_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Loan Purpose <span class="text-red-500">*</span>
                        </label>
                        <select name="loan_purpose_category" id="loan_purpose_category" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition mb-3">
                            <option value="">-- Select purpose category --</option>
                            <option value="Business Expansion">Business Expansion</option>
                            <option value="Agricultural Investment">Agricultural Investment</option>
                            <option value="Education">Education</option>
                            <option value="Emergency">Emergency</option>
                            <option value="Asset Financing">Asset Financing</option>
                            <option value="Home Improvement">Home Improvement</option>
                            <option value="Other">Other</option>
                        </select>
                        <textarea name="purpose" id="purpose" rows="4" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition"
                            placeholder="Provide detailed description of the loan purpose and how it will be used..."></textarea>
                        @error('purpose')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Detailed explanation of how the loan will be used (minimum 10 characters)</p>
                    </div>
                </div>

                <!-- Collateral Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#015425]">Collateral Information</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Collateral Description
                            </label>
                            <textarea name="collateral_description" id="collateral_description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition"
                                placeholder="Describe the collateral being offered (e.g., land title, vehicle, property, etc.)"></textarea>
                            @error('collateral_description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Collateral Value (TZS)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">TZS</span>
                                <input type="number" name="collateral_value" id="collateral_value" step="0.01" min="0"
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition"
                                    placeholder="0.00">
                            </div>
                            @error('collateral_value')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Guarantor Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#015425]">Guarantor Information</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Guarantor Member <span class="text-gray-500">(Optional)</span>
                            </label>
                            <select name="guarantor_id" id="guarantor_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition">
                                <option value="">-- Select a member by member code --</option>
                                @foreach($members ?? [] as $member)
                                    <option value="{{ $member->id }}" 
                                        data-code="{{ $member->membership_code }}"
                                        data-email="{{ $member->email }}"
                                        data-phone="{{ $member->phone ?? 'N/A' }}">
                                        {{ $member->membership_code }} - {{ $member->name }} ({{ $member->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('guarantor_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Select a member from the list to act as guarantor. Only members with membership codes are available.</p>
                        </div>

                        <div id="guarantor-details" class="hidden bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Member Code</p>
                                    <p class="text-sm font-medium text-gray-900" id="guarantor-code">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Email</p>
                                    <p class="text-sm font-medium text-gray-900" id="guarantor-email">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Phone</p>
                                    <p class="text-sm font-medium text-gray-900" id="guarantor-phone">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Plan & Repayment -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#015425]">Business Plan & Repayment Source</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Business Plan / Project Description
                            </label>
                            <textarea name="business_plan" id="business_plan" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition"
                                placeholder="Describe the business plan or project that the loan will finance..."></textarea>
                            @error('business_plan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Repayment Source
                            </label>
                            <textarea name="repayment_source" id="repayment_source" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition"
                                placeholder="Explain how the loan will be repaid (e.g., business income, salary, agricultural sales, etc.)"></textarea>
                            @error('repayment_source')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes
                            </label>
                            <textarea name="additional_notes" id="additional_notes" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition"
                                placeholder="Any additional information or notes about this loan application..."></textarea>
                            @error('additional_notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Document Uploads -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#015425]">Loan Application Documents</h2>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Main Application Document <span class="text-gray-500">(PDF, DOC, DOCX, JPG, PNG - Max 10MB)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-[#015425] transition">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="application_document" class="relative cursor-pointer bg-white rounded-md font-medium text-[#015425] hover:text-[#027a3a] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#015425]">
                                            <span>Upload a file</span>
                                            <input id="application_document" name="application_document" type="file" class="sr-only" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG up to 10MB</p>
                                </div>
                            </div>
                            @error('application_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div id="application_document_preview" class="mt-2 hidden">
                                <p class="text-sm text-green-600 font-medium">✓ File selected: <span id="application_document_name"></span></p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                ID Document <span class="text-gray-500">(PDF, JPG, PNG - Max 10MB)</span>
                            </label>
                            <input type="file" name="id_document" id="id_document" accept=".pdf,.jpg,.jpeg,.png"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#027a3a] file:cursor-pointer">
                            @error('id_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Proof of Income <span class="text-gray-500">(PDF, DOC, DOCX, JPG, PNG - Max 10MB)</span>
                            </label>
                            <input type="file" name="proof_of_income" id="proof_of_income" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#027a3a] file:cursor-pointer">
                            @error('proof_of_income')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Collateral Document <span class="text-gray-500">(PDF, DOC, DOCX, JPG, PNG - Max 10MB)</span>
                            </label>
                            <input type="file" name="collateral_document" id="collateral_document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#027a3a] file:cursor-pointer">
                            @error('collateral_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Guarantor Document <span class="text-gray-500">(PDF, DOC, DOCX, JPG, PNG - Max 10MB)</span>
                            </label>
                            <input type="file" name="guarantor_document" id="guarantor_document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#027a3a] file:cursor-pointer">
                            @error('guarantor_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Supporting Documents <span class="text-gray-500">(Multiple files - PDF, DOC, DOCX, JPG, PNG - Max 10MB each)</span>
                            </label>
                            <input type="file" name="supporting_documents[]" id="supporting_documents" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#027a3a] file:cursor-pointer">
                            @error('supporting_documents.*')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">You can select multiple files (e.g., bank statements, references, etc.)</p>
                            <div id="supporting_documents_preview" class="mt-2 hidden">
                                <p class="text-sm text-green-600 font-medium">✓ <span id="supporting_documents_count"></span> file(s) selected</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#015425]">Terms & Conditions</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-2">Important Notes:</h4>
                            <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                                <li>Loan application will be submitted with "Pending" status</li>
                                <li>Requires approval from authorized personnel before disbursement</li>
                                <li>All uploaded documents will be securely stored</li>
                                <li>Interest will be calculated based on the provided rate and term</li>
                                <li>Loan number will be automatically generated upon submission</li>
                                <li>You will be notified via email and in-app notification once the loan is reviewed</li>
                            </ul>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" id="terms_accepted" name="terms_accepted" required 
                                class="mt-1 mr-3 h-4 w-4 text-[#015425] focus:ring-[#015425] border-gray-300 rounded">
                            <label for="terms_accepted" class="text-sm text-gray-700">
                                I confirm that all information provided is accurate, all documents are authentic, and I understand the terms and conditions of the loan application.
                            </label>
                        </div>
                        @error('terms_accepted')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar - Loan Calculator & Summary -->
            <div class="space-y-6">
                <!-- Loan Calculator -->
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#015425]">Loan Calculator</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-600 mb-1">Principal Amount</p>
                            <p class="text-lg font-bold text-gray-900" id="calc-principal">TZS 0.00</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-600 mb-1">Interest Rate</p>
                            <p class="text-lg font-bold text-gray-900" id="calc-interest-rate">0.00%</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-600 mb-1">Loan Term</p>
                            <p class="text-lg font-bold text-gray-900" id="calc-term">0 months</p>
                        </div>

                        <div class="border-t pt-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Interest</span>
                                <span class="text-sm font-semibold text-orange-600" id="calc-total-interest">TZS 0.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Amount</span>
                                <span class="text-lg font-bold text-[#015425]" id="calc-total-amount">TZS 0.00</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="text-sm font-medium text-gray-700">Monthly Payment</span>
                                <span class="text-lg font-bold text-green-600" id="calc-monthly-payment">TZS 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-[#015425] mb-4">Quick Information</h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-600">Loan will be submitted with status: <strong>Pending</strong></span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-600">Requires approval before disbursement</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-600">Documents will be securely stored</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-600">Loan number will be auto-generated</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium shadow-md">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Submit Application
                            </div>
                        </button>
                        <a href="{{ route('member.loans.index') }}" class="block w-full px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center font-medium">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const principalInput = document.getElementById('principal_amount');
    const interestInput = document.getElementById('interest_rate');
    const termInput = document.getElementById('term_months');
    const frequencySelect = document.getElementById('payment_frequency');

    // Guarantor selection handler
    const guarantorSelect = document.getElementById('guarantor_id');
    const guarantorDetails = document.getElementById('guarantor-details');
    const guarantorCode = document.getElementById('guarantor-code');
    const guarantorEmail = document.getElementById('guarantor-email');
    const guarantorPhone = document.getElementById('guarantor-phone');

    if (guarantorSelect) {
        guarantorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                guarantorCode.textContent = selectedOption.dataset.code || '-';
                guarantorEmail.textContent = selectedOption.dataset.email || '-';
                guarantorPhone.textContent = selectedOption.dataset.phone || '-';
                guarantorDetails.classList.remove('hidden');
            } else {
                guarantorDetails.classList.add('hidden');
            }
        });
    }

    // Loan calculator
    function calculateLoan() {
        const principal = parseFloat(principalInput.value) || 0;
        const interestRate = parseFloat(interestInput.value) || 0;
        const term = parseInt(termInput.value) || 0;
        const frequency = frequencySelect.value;

        // Update display values
        document.getElementById('calc-principal').textContent = formatCurrency(principal);
        document.getElementById('calc-interest-rate').textContent = interestRate.toFixed(2) + '%';
        document.getElementById('calc-term').textContent = term + ' months';

        if (principal > 0 && interestRate > 0 && term > 0) {
            // Calculate total interest (simple interest)
            const totalInterest = (principal * interestRate / 100) * (term / 12);
            const totalAmount = principal + totalInterest;

            // Calculate monthly payment
            let monthlyPayment = 0;
            if (term > 0) {
                monthlyPayment = totalAmount / term;
            }

            // Update display
            document.getElementById('calc-total-interest').textContent = formatCurrency(totalInterest);
            document.getElementById('calc-total-amount').textContent = formatCurrency(totalAmount);
            document.getElementById('calc-monthly-payment').textContent = formatCurrency(monthlyPayment);
        } else {
            document.getElementById('calc-total-interest').textContent = 'TZS 0.00';
            document.getElementById('calc-total-amount').textContent = 'TZS 0.00';
            document.getElementById('calc-monthly-payment').textContent = 'TZS 0.00';
        }
    }

    function formatCurrency(amount) {
        return 'TZS ' + amount.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Add event listeners for calculator
    principalInput.addEventListener('input', calculateLoan);
    interestInput.addEventListener('input', calculateLoan);
    termInput.addEventListener('input', calculateLoan);
    frequencySelect.addEventListener('change', calculateLoan);

    // Initial calculation
    calculateLoan();

    // File upload preview handlers
    const applicationDocument = document.getElementById('application_document');
    if (applicationDocument) {
        applicationDocument.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                document.getElementById('application_document_name').textContent = e.target.files[0].name;
                document.getElementById('application_document_preview').classList.remove('hidden');
            } else {
                document.getElementById('application_document_preview').classList.add('hidden');
            }
        });
    }

    const supportingDocuments = document.getElementById('supporting_documents');
    if (supportingDocuments) {
        supportingDocuments.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                document.getElementById('supporting_documents_count').textContent = e.target.files.length;
                document.getElementById('supporting_documents_preview').classList.remove('hidden');
            } else {
                document.getElementById('supporting_documents_preview').classList.add('hidden');
            }
        });
    }
});
</script>
@endpush
@endsection
