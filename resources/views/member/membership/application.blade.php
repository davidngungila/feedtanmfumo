@extends('layouts.member')

@section('page-title', 'Membership Application')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Membership Application</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Complete your membership application form</p>
            </div>
            @if($user->membership_status === 'pending')
            <span class="mt-4 md:mt-0 px-4 py-2 bg-yellow-500 bg-opacity-20 rounded-md text-sm">Status: Pending Approval</span>
            @elseif($user->membership_status === 'approved')
            <span class="mt-4 md:mt-0 px-4 py-2 bg-green-500 bg-opacity-20 rounded-md text-sm">Status: Approved</span>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <p class="text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    @if($user->membership_status === 'approved')
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-bold text-blue-900 mb-2">Membership Approved!</h3>
        <p class="text-blue-800">Your membership has been approved. You can now access all available services based on your membership type.</p>
        <a href="{{ route('member.dashboard') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Go to Dashboard</a>
    </div>
    @else
    <!-- Application Form -->
    <form method="POST" action="{{ route('member.membership.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Step 1: Membership Type Selection -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Step 1: Select Membership Type</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($membershipTypes as $type)
                <label class="relative">
                    <input type="radio" name="membership_type_id" value="{{ $type->id }}" 
                           class="peer hidden" 
                           {{ old('membership_type_id', $user->membership_type_id) == $type->id ? 'checked' : '' }}
                           required>
                    <div class="border-2 border-gray-300 rounded-lg p-4 cursor-pointer hover:border-[#015425] peer-checked:border-[#015425] peer-checked:bg-green-50 transition">
                        <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $type->name }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $type->description }}</p>
                        <div class="text-xs text-gray-500 space-y-1">
                            @if($type->entrance_fee > 0)
                            <p>Entrance Fee: {{ number_format($type->entrance_fee) }} TZS</p>
                            @endif
                            @if($type->capital_contribution > 0)
                            <p>Capital Contribution: {{ number_format($type->capital_contribution) }} TZS</p>
                            @endif
                            @if($type->minimum_shares > 0)
                            <p>Minimum Shares: {{ $type->minimum_shares }}</p>
                            @endif
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
            @error('membership_type_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Step 2: Personal Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Step 2: Personal Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alternate Phone</label>
                    <input type="text" name="alternate_phone" value="{{ old('alternate_phone', $user->alternate_phone) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                    <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                        <option value="">-- Select Gender --</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">National ID (NIDA) *</label>
                    <input type="text" name="national_id" value="{{ old('national_id', $user->national_id) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status *</label>
                    <select name="marital_status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                        <option value="">-- Select Status --</option>
                        <option value="single" {{ old('marital_status', $user->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ old('marital_status', $user->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                        <option value="divorced" {{ old('marital_status', $user->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="widowed" {{ old('marital_status', $user->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Step 3: Address Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Step 3: Address Information</h2>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Address *</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>{{ old('address', $user->address) }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Region *</label>
                        <input type="text" name="region" value="{{ old('region', $user->region) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Employment Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Step 4: Employment Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $user->occupation) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employer / Self Employment</label>
                    <input type="text" name="employer" value="{{ old('employer', $user->employer) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Income (TZS)</label>
                    <input type="number" name="monthly_income" value="{{ old('monthly_income', $user->monthly_income) }}" 
                           min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
            </div>
        </div>

        <!-- Step 5: Bank Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Step 5: Bank Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $user->bank_name) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Branch</label>
                    <input type="text" name="bank_branch" value="{{ old('bank_branch', $user->bank_branch) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Account Number</label>
                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $user->bank_account_number) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reference Number</label>
                    <input type="text" name="payment_reference_number" value="{{ old('payment_reference_number', $user->payment_reference_number) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
            </div>
        </div>

        <!-- Step 6: Additional Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Step 6: Additional Information</h2>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Short Biography (Optional - To be published in Groups' official documents)</label>
                    <textarea name="short_bibliography" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('short_bibliography', $user->short_bibliography) }}</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Introduced By / Guarantor Name</label>
                    <input type="text" name="introduced_by" value="{{ old('introduced_by', $user->introduced_by) }}" 
                           placeholder="Name of person who introduced FeedTan to you or who guarantees you" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statement Preference *</label>
                    <select name="statement_preference" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" required>
                        <option value="email" {{ old('statement_preference', $user->statement_preference) == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ old('statement_preference', $user->statement_preference) == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="postal" {{ old('statement_preference', $user->statement_preference) == 'postal' ? 'selected' : '' }}>Postal Mail</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Step 7: Beneficiaries -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Step 7: Beneficiaries Information</h2>
            <p class="text-sm text-gray-600 mb-4">Provide beneficiary details (Names, Relationship, % Allocation, Bank details, Contact) in case of unfortunate event</p>
            
            <div id="beneficiaries-container">
                <div class="beneficiary-item border border-gray-300 rounded-lg p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="beneficiaries[0][name]" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Relationship</label>
                            <input type="text" name="beneficiaries[0][relationship]" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Allocation (%)</label>
                            <input type="number" name="beneficiaries[0][allocation]" min="0" max="100" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact (Mobile/Email)</label>
                            <input type="text" name="beneficiaries[0][contact]" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" onclick="addBeneficiary()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">+ Add Beneficiary</button>
        </div>

        <!-- Step 8: Group Information (if applicable) -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Step 8: Group Information (If Applicable)</h2>
            
            <div class="space-y-6">
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_group_registered" value="1" 
                               {{ old('is_group_registered', $user->is_group_registered) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        <span class="ml-2 text-sm text-gray-700">Is your group registered with Government Authorities?</span>
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Group Name</label>
                    <input type="text" name="group_name" value="{{ old('group_name', $user->group_name) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Group Leaders</label>
                    <input type="text" name="group_leaders" value="{{ old('group_leaders', $user->group_leaders) }}" 
                           placeholder="Names of leaders" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Group Bank Account</label>
                    <input type="text" name="group_bank_account" value="{{ old('group_bank_account', $user->group_bank_account) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Group Contacts (Email, Mobile, Address)</label>
                    <textarea name="group_contacts" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('group_contacts', $user->group_contacts) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Step 9: Documents Upload -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Step 9: Documents Upload</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Passport Size Picture *</label>
                    <input type="file" name="passport_picture" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @if($user->passport_picture_path)
                    <p class="mt-1 text-xs text-gray-500">Current: {{ basename($user->passport_picture_path) }}</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIDA ID Picture *</label>
                    <input type="file" name="nida_picture" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    @if($user->nida_picture_path)
                    <p class="mt-1 text-xs text-gray-500">Current: {{ basename($user->nida_picture_path) }}</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Membership Application Letter</label>
                    <input type="file" name="application_letter" accept=".pdf,.doc,.docx" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Slip/Evidence</label>
                    <input type="file" name="payment_slip" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Standing Order (if applicable)</label>
                    <input type="file" name="standing_order" accept=".pdf,.doc,.docx,image/*" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
            </div>
        </div>

        <!-- Step 10: Additional Options -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Step 10: Additional Options</h2>
            
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="wants_ordinary_membership" value="1" 
                           {{ old('wants_ordinary_membership', $user->wants_ordinary_membership) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                    <span class="ml-2 text-sm text-gray-700">Would you like to be considered for ordinary membership?</span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center">
                <a href="{{ route('member.dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Save as Draft
                </a>
                <button type="submit" class="px-8 py-3 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                    Submit for Approval
                </button>
            </div>
        </div>
    </form>
    @endif
</div>

<script>
let beneficiaryCount = 1;

function addBeneficiary() {
    const container = document.getElementById('beneficiaries-container');
    const newItem = document.createElement('div');
    newItem.className = 'beneficiary-item border border-gray-300 rounded-lg p-4 mb-4';
    newItem.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <h4 class="font-medium text-gray-700">Beneficiary ${beneficiaryCount + 1}</h4>
            <button type="button" onclick="this.closest('.beneficiary-item').remove()" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="beneficiaries[${beneficiaryCount}][name]" class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Relationship</label>
                <input type="text" name="beneficiaries[${beneficiaryCount}][relationship]" class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Allocation (%)</label>
                <input type="number" name="beneficiaries[${beneficiaryCount}][allocation]" min="0" max="100" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact (Mobile/Email)</label>
                <input type="text" name="beneficiaries[${beneficiaryCount}][contact]" class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>
        </div>
    `;
    container.appendChild(newItem);
    beneficiaryCount++;
}
</script>
@endsection

