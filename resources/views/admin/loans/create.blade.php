@extends('layouts.admin')

@section('page-title', 'Create New Loan Application')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">New Loan Application</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Create a new loan application for a member</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    All Loans
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.loans.store') }}" method="POST" id="loan-form">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Member Selection -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#015425]">Member Information</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Member <span class="text-red-500">*</span>
                            </label>
                            <select name="user_id" id="user_id" required class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition">
                                <option value="">-- Select a member --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-email="{{ $user->email }}" data-phone="{{ $user->phone ?? 'N/A' }}">
                                        {{ $user->name }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Search and select the member applying for the loan</p>
                        </div>

                        <div id="member-details" class="hidden bg-gray-50 rounded-lg p-4 border border-gray-200">
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
                                Principal Amount (TZS) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">TZS</span>
                                <input type="number" name="principal_amount" id="principal_amount" step="0.01" min="0" required 
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition"
                                    placeholder="0.00">
                            </div>
                            @error('principal_amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Enter the loan amount requested by the member</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Interest Rate (%) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="interest_rate" id="interest_rate" step="0.01" min="0" max="100" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425] transition"
                                    placeholder="0.00">
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
                        <p class="text-xs text-gray-500 mt-1">Detailed explanation of how the loan will be used, including any collateral or security information</p>
                    </div>
                </div>

                <!-- Additional Information -->
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
                                <li>Loan application will be created with "Pending" status</li>
                                <li>Requires approval from authorized personnel before disbursement</li>
                                <li>Interest will be calculated based on the provided rate and term</li>
                                <li>Loan number will be automatically generated upon creation</li>
                                <li>Member will be notified once the loan is approved</li>
                            </ul>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" id="terms_accepted" name="terms_accepted" required 
                                class="mt-1 mr-3 h-4 w-4 text-[#015425] focus:ring-[#015425] border-gray-300 rounded">
                            <label for="terms_accepted" class="text-sm text-gray-700">
                                I confirm that all information provided is accurate and the member has been properly verified.
                            </label>
                        </div>
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
                            <span class="text-gray-600">Loan will be created with status: <strong>Pending</strong></span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-600">Requires approval before disbursement</span>
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
                                Create Loan Application
                            </div>
                        </button>
                        <a href="{{ route('admin.loans.index') }}" class="block w-full px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center font-medium">
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
    const userIdSelect = document.getElementById('user_id');
    const memberDetails = document.getElementById('member-details');
    const memberEmail = document.getElementById('member-email');
    const memberPhone = document.getElementById('member-phone');

    // Member selection handler
    userIdSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            memberEmail.textContent = selectedOption.dataset.email || '-';
            memberPhone.textContent = selectedOption.dataset.phone || '-';
            memberDetails.classList.remove('hidden');
        } else {
            memberDetails.classList.add('hidden');
        }
    });

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
});
</script>
@endpush
@endsection
