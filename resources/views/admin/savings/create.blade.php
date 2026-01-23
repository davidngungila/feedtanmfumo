@extends('layouts.admin')

@section('page-title', 'Create Savings Account')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Create New Savings Account</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Open a new savings account for a member</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                @if($selectedUser)
                <a href="{{ route('admin.users.show', $selectedUser) }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition font-medium">
                    View Member Profile
                </a>
                @endif
                <a href="{{ route('admin.savings.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition font-medium">
                    Back to List
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
            <!-- Selected Member Info Card -->
            @if($selectedUser)
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-2xl font-bold text-white">{{ strtoupper(substr($selectedUser->name, 0, 2)) }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $selectedUser->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $selectedUser->email }}</p>
                            @if($selectedUser->member_number)
                            <p class="text-xs text-gray-500 mt-1">Member #: {{ $selectedUser->member_number }}</p>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('admin.users.show', $selectedUser) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium">
                        View Profile
                    </a>
                </div>
                @php
                    $existingAccounts = $selectedUser->savingsAccounts()->count();
                @endphp
                @if($existingAccounts > 0)
                <div class="mt-4 pt-4 border-t border-blue-200">
                    <p class="text-sm text-blue-800">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        This member already has <strong>{{ $existingAccounts }}</strong> savings account(s).
                    </p>
                </div>
                @endif
            </div>
            @endif

            <!-- Account Creation Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Account Details</h2>
                </div>

                <form action="{{ route('admin.savings.store') }}" method="POST" id="savings-account-form">
            @csrf
            
                    <div class="space-y-6">
                        <!-- Member Selection -->
                <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Member <span class="text-red-500">*</span></label>
                            <select name="user_id" id="user_id" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                        <option value="">Select Member</option>
                        @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ ($selectedUser && $selectedUser->id == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                        @if($user->member_number) - #{{ $user->member_number }}@endif
                                    </option>
                        @endforeach
                    </select>
                            <p class="mt-1 text-xs text-gray-500">Select the member who will own this savings account</p>
                    @error('user_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                        <!-- Account Type -->
                <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Account Type <span class="text-red-500">*</span></label>
                            <select name="account_type" id="account_type" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                        <option value="emergency">Emergency Savings</option>
                        <option value="rda">Recurrent Deposit Account (RDA)</option>
                        <option value="flex">Flex Account</option>
                        <option value="business">Business Savings</option>
                    </select>
                            <p class="mt-1 text-xs text-gray-500">Choose the type of savings account to open</p>
                    @error('account_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                        <!-- Account Type Information -->
                        <div id="account_type_info" class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div id="emergency_info" class="account-info">
                                <h4 class="font-semibold text-blue-900 mb-2">Emergency Savings Account</h4>
                                <p class="text-sm text-blue-800">A flexible savings account for emergency funds with easy access and competitive interest rates.</p>
                            </div>
                            <div id="rda_info" class="account-info hidden">
                                <h4 class="font-semibold text-blue-900 mb-2">Recurrent Deposit Account (RDA)</h4>
                                <p class="text-sm text-blue-800">Fixed-term deposit account with recurring contributions and maturity date. Higher interest rates than regular savings.</p>
                            </div>
                            <div id="flex_info" class="account-info hidden">
                                <h4 class="font-semibold text-blue-900 mb-2">Flex Account</h4>
                                <p class="text-sm text-blue-800">A flexible savings account that combines savings and transaction features with minimal restrictions.</p>
                            </div>
                            <div id="business_info" class="account-info hidden">
                                <h4 class="font-semibold text-blue-900 mb-2">Business Savings Account</h4>
                                <p class="text-sm text-blue-800">Designed for business members with higher transaction limits and business-focused features.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Interest Rate -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interest Rate (%)</label>
                                <input type="number" name="interest_rate" step="0.01" min="0" max="100" value="0" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                <p class="mt-1 text-xs text-gray-500">Annual interest rate percentage (0-100%)</p>
                    @error('interest_rate')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                            <!-- Minimum Balance -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Balance (TZS)</label>
                                <input type="number" name="minimum_balance" step="0.01" min="0" value="0" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                <p class="mt-1 text-xs text-gray-500">Minimum required balance to maintain the account</p>
                    @error('minimum_balance')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                            <!-- Opening Date -->
                <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Opening Date <span class="text-red-500">*</span></label>
                                <input type="date" name="opening_date" value="{{ date('Y-m-d') }}" required 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                    @error('opening_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                            <!-- Maturity Date (for RDA) -->
                <div id="maturity_date_field" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Maturity Date <span class="text-red-500">*</span></label>
                                <input type="date" name="maturity_date" id="maturity_date" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]">
                                <p class="mt-1 text-xs text-gray-500">Required for Recurrent Deposit Accounts (RDA)</p>
                    @error('maturity_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Initial Deposit (Optional) -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">Initial Deposit</p>
                                    <p class="text-xs text-yellow-700 mt-1">The account will be created with a zero balance. You can record the initial deposit after account creation through the deposits section.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="4" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#015425] focus:border-[#015425]"
                                      placeholder="Any additional notes or remarks about this account..."></textarea>
                            <p class="mt-1 text-xs text-gray-500">Optional notes for internal reference</p>
                            @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('admin.savings.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium shadow-md">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create Savings Account
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Account Type Guide -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Types Guide</h3>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h4 class="font-semibold text-gray-900">Emergency Savings</h4>
                        <p class="text-sm text-gray-600 mt-1">Flexible access, standard interest rates. Perfect for emergency funds.</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-4">
                        <h4 class="font-semibold text-gray-900">Recurrent Deposit (RDA)</h4>
                        <p class="text-sm text-gray-600 mt-1">Fixed-term with maturity date. Higher interest rates. Requires maturity date.</p>
                    </div>
                    <div class="border-l-4 border-purple-500 pl-4">
                        <h4 class="font-semibold text-gray-900">Flex Account</h4>
                        <p class="text-sm text-gray-600 mt-1">Combines savings and transaction features. Flexible terms.</p>
                    </div>
                    <div class="border-l-4 border-orange-500 pl-4">
                        <h4 class="font-semibold text-gray-900">Business Savings</h4>
                        <p class="text-sm text-gray-600 mt-1">For business members. Higher limits and business features.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Quick Tips</h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>All accounts start with zero balance. Record initial deposit separately.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>RDA accounts require a maturity date for proper tracking.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Interest rates can be adjusted later if needed.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Minimum balance helps track accounts below threshold.</span>
                    </li>
                </ul>
            </div>

            <!-- Member Info (if selected) -->
            @if($selectedUser)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Member Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Full Name</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $selectedUser->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $selectedUser->email }}</p>
                    </div>
                    @if($selectedUser->phone)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Phone</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $selectedUser->phone }}</p>
                    </div>
                    @endif
                    @if($selectedUser->member_number)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Member Number</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $selectedUser->member_number }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Status</p>
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ 
                            $selectedUser->status === 'active' ? 'bg-green-100 text-green-800' : 
                            ($selectedUser->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')
                        }}">
                            {{ ucfirst($selectedUser->status ?? 'N/A') }}
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const accountTypeSelect = document.getElementById('account_type');
    const maturityDateField = document.getElementById('maturity_date_field');
    const maturityDateInput = document.getElementById('maturity_date');
    const accountInfoDivs = document.querySelectorAll('.account-info');

    function updateAccountTypeInfo() {
        const selectedType = accountTypeSelect.value;
        
        // Hide all info divs
        accountInfoDivs.forEach(div => div.classList.add('hidden'));
        
        // Show selected info div
        const selectedInfo = document.getElementById(selectedType + '_info');
        if (selectedInfo) {
            selectedInfo.classList.remove('hidden');
        }

        // Show/hide maturity date field for RDA
        if (selectedType === 'rda') {
            maturityDateField.style.display = 'block';
            maturityDateInput.required = true;
            // Set default maturity date to 12 months from opening date
            const openingDateInput = document.querySelector('input[name="opening_date"]');
            if (openingDateInput && openingDateInput.value) {
                const openingDate = new Date(openingDateInput.value);
                const maturityDate = new Date(openingDate);
                maturityDate.setMonth(maturityDate.getMonth() + 12);
                maturityDateInput.value = maturityDate.toISOString().split('T')[0];
            }
        } else {
            maturityDateField.style.display = 'none';
            maturityDateInput.required = false;
            maturityDateInput.value = '';
        }
    }

    accountTypeSelect.addEventListener('change', updateAccountTypeInfo);
    
    // Initialize on page load
    updateAccountTypeInfo();

    // Update maturity date when opening date changes (for RDA)
    const openingDateInput = document.querySelector('input[name="opening_date"]');
    if (openingDateInput) {
        openingDateInput.addEventListener('change', function() {
            if (accountTypeSelect.value === 'rda' && this.value) {
                const openingDate = new Date(this.value);
                const maturityDate = new Date(openingDate);
                maturityDate.setMonth(maturityDate.getMonth() + 12);
                maturityDateInput.value = maturityDate.toISOString().split('T')[0];
            }
        });
        }
    });
</script>
@endsection
