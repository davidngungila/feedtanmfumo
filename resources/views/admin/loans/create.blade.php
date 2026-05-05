@extends('layouts.admin')

@section('page-title', 'Create New Loan Application')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">New Loan Application</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Multi-step loan application with automatic saving</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    All Loans
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

    <!-- Multi-Step Loan Form -->
    <form id="multiStepLoanForm" class="bg-white rounded-lg shadow-md p-4 sm:p-6 lg:p-8">
        @csrf
        
        <!-- Step Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    @for($i = 1; $i <= 4; $i++)
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
                <span class="text-sm font-medium text-gray-700" id="stepTitle">Step 1: Basic Loan Information</span>
            </div>
            <div class="flex space-x-4 text-xs text-gray-600">
                <span>Basic Info</span>
                <span>Applicant</span>
                <span>Collateral</span>
                <span>Documents</span>
            </div>
        </div>

        <!-- Step 1: Basic Loan Information -->
        <div id="step1" class="step-content">
            <h2 class="text-xl font-semibold text-[#015425] mb-6">Basic Loan Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Type <span class="text-red-500">*</span></label>
                    <select name="loan_type" id="loan_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
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
                    @error('loan_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Principal Amount (TZS) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">TZS</span>
                        <input type="number" name="principal_amount" id="principal_amount" step="0.01" min="0" required 
                               class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                               placeholder="0.00">
                    </div>
                    @error('principal_amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interest Rate (%) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="interest_rate" id="interest_rate" step="0.01" min="0" max="100" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                               placeholder="0.00">
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                    </div>
                    @error('interest_rate')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Term (Months) <span class="text-red-500">*</span></label>
                    <input type="number" name="term_months" id="term_months" min="1" max="120" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                           placeholder="12">
                    @error('term_months')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Frequency <span class="text-red-500">*</span></label>
                    <select name="payment_frequency" id="payment_frequency" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">-- Select frequency --</option>
                        <option value="weekly">Weekly</option>
                        <option value="bi-weekly">Bi-weekly</option>
                        <option value="monthly" selected>Monthly</option>
                    </select>
                    @error('payment_frequency')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Application Date <span class="text-red-500">*</span></label>
                    <input type="date" name="application_date" id="application_date" value="{{ date('Y-m-d') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @error('application_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Purpose Category <span class="text-red-500">*</span></label>
                    <select name="loan_purpose_category" id="loan_purpose_category" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425] mb-3">
                        <option value="">-- Select purpose category --</option>
                        <option value="Business Expansion">Business Expansion</option>
                        <option value="Agricultural Investment">Agricultural Investment</option>
                        <option value="Education">Education</option>
                        <option value="Emergency">Emergency</option>
                        <option value="Asset Financing">Asset Financing</option>
                        <option value="Home Improvement">Home Improvement</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('loan_purpose_category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Purpose Description <span class="text-red-500">*</span></label>
                    <textarea name="purpose" id="purpose" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                              placeholder="Provide detailed description of the loan purpose and how it will be used..."></textarea>
                    @error('purpose')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Step 2: Applicant Information -->
        <div id="step2" class="step-content hidden">
            <h2 class="text-xl font-semibold text-[#015425] mb-6">Applicant Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Member <span class="text-red-500">*</span></label>
                    <select name="user_id" id="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">-- Select a member --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" data-email="{{ $user->email }}" data-phone="{{ $user->phone ?? 'N/A' }}">
                                {{ $user->name }} - {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div id="member-details" class="hidden bg-gray-50 rounded-lg p-4 border border-gray-200 md:col-span-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Email</p>
                            <p class="text-sm font-medium text-gray-900" id="member-email">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Phone</p>
                            <p class="text-sm font-medium text-gray-900" id="member-phone">-</p>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Plan / Project Description</label>
                    <textarea name="business_plan" id="business_plan" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                              placeholder="Describe the business plan or project that the loan will finance..."></textarea>
                    @error('business_plan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Repayment Source</label>
                    <textarea name="repayment_source" id="repayment_source" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                              placeholder="Explain how the loan will be repaid (e.g., business income, salary, agricultural sales, etc.)"></textarea>
                    @error('repayment_source')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Step 3: Collateral & Security -->
        <div id="step3" class="step-content hidden">
            <h2 class="text-xl font-semibold text-[#015425] mb-6">Collateral & Security</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Collateral Description</label>
                    <textarea name="collateral_description" id="collateral_description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                              placeholder="Describe the collateral being offered (e.g., land title, vehicle, property, etc.)"></textarea>
                    @error('collateral_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Collateral Value (TZS)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">TZS</span>
                        <input type="number" name="collateral_value" id="collateral_value" step="0.01" min="0"
                               class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                               placeholder="0.00">
                    </div>
                    @error('collateral_value')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Guarantor Member (Optional)</label>
                    <select name="guarantor_id" id="guarantor_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
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
                    @error('guarantor_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div id="guarantor-details" class="hidden bg-gray-50 rounded-lg p-4 border border-gray-200 md:col-span-2">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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

        <!-- Step 4: Documents & Terms -->
        <div id="step4" class="step-content hidden">
            <h2 class="text-xl font-semibold text-[#015425] mb-6">Documents & Terms</h2>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Main Application Document <span class="text-gray-500">(PDF, DOC, DOCX, JPG, PNG - Max 10MB)</span></label>
                    <input type="file" name="application_document" id="application_document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#027a3a]">
                    @error('application_document')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ID Document <span class="text-gray-500">(PDF, JPG, PNG - Max 10MB)</span></label>
                    <input type="file" name="id_document" id="id_document" accept=".pdf,.jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#027a3a]">
                    @error('id_document')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proof of Income <span class="text-gray-500">(PDF, DOC, DOCX, JPG, PNG - Max 10MB)</span></label>
                    <input type="file" name="proof_of_income" id="proof_of_income" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#027a3a]">
                    @error('proof_of_income')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supporting Documents <span class="text-gray-500">(Multiple files - PDF, DOC, DOCX, JPG, PNG - Max 10MB each)</span></label>
                    <input type="file" name="supporting_documents[]" id="supporting_documents" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#027a3a]">
                    @error('supporting_documents.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-500 mt-1">You can select multiple files (e.g., bank statements, references, etc.)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea name="additional_notes" id="additional_notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"
                              placeholder="Any additional information or notes about this loan application..."></textarea>
                    @error('additional_notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">Important Notes:</h4>
                    <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                        <li>Loan application will be created with "Pending" status</li>
                        <li>Requires approval from authorized personnel before disbursement</li>
                        <li>All uploaded documents will be securely stored</li>
                        <li>Interest will be calculated based on the provided rate and term</li>
                        <li>Loan number will be automatically generated upon creation</li>
                        <li>Member will be notified once the loan is approved</li>
                    </ul>
                </div>

                <div class="flex items-start">
                    <input type="checkbox" id="terms_accepted" name="terms_accepted" required 
                           class="mt-1 mr-3 h-4 w-4 text-[#015425] focus:ring-[#015425] border-gray-300 rounded">
                    <label for="terms_accepted" class="text-sm text-gray-700">
                        I confirm that all information provided is accurate, all documents are authentic, and the member has been properly verified.
                    </label>
                </div>
                @error('terms_accepted')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
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
                Create Loan Application
            </button>
        </div>
    </form>
</div>

<script>
let currentStep = 1;
const totalSteps = 4;
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
        'Step 1: Basic Loan Information',
        'Step 2: Applicant Information', 
        'Step 3: Collateral & Security',
        'Step 4: Documents & Terms'
    ];
    document.getElementById('stepTitle').textContent = stepTitles[currentStep - 1];
    
    // Update buttons
    document.getElementById('prevBtn').disabled = currentStep === 1;
    
    if (currentStep === totalSteps) {
        document.getElementById('nextBtn').classList.add('hidden');
        document.getElementById('submitBtn').classList.remove('hidden');
    } else {
        document.getElementById('nextBtn').classList.remove('hidden');
        document.getElementById('submitBtn').classList.add('hidden');
    }
}

function setupEventListeners() {
    // Member selection
    const userSelect = document.getElementById('user_id');
    if (userSelect) {
        userSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const email = selectedOption.dataset.email;
            const phone = selectedOption.dataset.phone;
            
            const detailsDiv = document.getElementById('member-details');
            if (email && phone) {
                document.getElementById('member-email').textContent = email;
                document.getElementById('member-phone').textContent = phone;
                detailsDiv.classList.remove('hidden');
            } else {
                detailsDiv.classList.add('hidden');
            }
        });
    }

    // Guarantor selection
    const guarantorSelect = document.getElementById('guarantor_id');
    if (guarantorSelect) {
        guarantorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const code = selectedOption.dataset.code;
            const email = selectedOption.dataset.email;
            const phone = selectedOption.dataset.phone;
            
            const detailsDiv = document.getElementById('guarantor-details');
            if (code && email && phone) {
                document.getElementById('guarantor-code').textContent = code;
                document.getElementById('guarantor-email').textContent = email;
                document.getElementById('guarantor-phone').textContent = phone;
                detailsDiv.classList.remove('hidden');
            } else {
                detailsDiv.classList.add('hidden');
            }
        });
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

async function saveStepData(step) {
    const stepElement = document.getElementById('step' + step);
    const formData = new FormData(stepElement);
    const stepData = {};
    
    for (let [key, value] of formData.entries()) {
        stepData[key] = value;
    }
    
    try {
        const response = await fetch('/admin/loans/save-step', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                step: step,
                data: stepData,
                token: registrationToken
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            registrationToken = result.token;
            showSuccessMessage('Step ' + step + ' saved successfully');
        } else {
            console.error('Failed to save step:', result.message);
        }
    } catch (error) {
        console.error('Error saving step:', error);
    }
}

async function loadRegistrationData() {
    try {
        const response = await fetch('/admin/loans/load-registration?token=' + registrationToken);
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
                        } else if (field.type === 'file') {
                            // File inputs cannot be repopulated for security reasons
                        } else {
                            field.value = stepData[key];
                        }
                    }
                });
            }
            
            currentStep = result.current_step || 1;
            updateStepDisplay();
            setupEventListeners(); // Re-setup event listeners after data is loaded
        }
    } catch (error) {
        console.error('Error loading registration data:', error);
    }
}

function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-4';
    successDiv.textContent = message;
    
    const form = document.getElementById('multiStepLoanForm');
    form.parentNode.insertBefore(successDiv, form);
    
    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

// Form submission
document.getElementById('multiStepLoanForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const isValid = await validateCurrentStep();
    if (!isValid) return;
    
    // Save final step data
    await saveStepData(currentStep);
    
    // Add registration token to form if exists
    if (registrationToken) {
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = 'registration_token';
        tokenInput.value = registrationToken;
        this.appendChild(tokenInput);
    }
    
    // Submit the complete form
    this.submit();
});
</script>
@endsection
