@extends('layouts.member')

@section('page-title', 'Membership Application Preview')

@push('styles')
<style>
    .preview-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 2.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .preview-section:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .preview-section h3 {
        color: #015425;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #015425;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    
    .preview-section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }
    
    .edit-step-btn {
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
        color: white;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(1, 84, 37, 0.2);
    }
    
    .edit-step-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(1, 84, 37, 0.3);
    }
    
    .edit-step-btn svg {
        width: 16px;
        height: 16px;
    }
    
    .preview-section h3::before {
        content: '';
        width: 8px;
        height: 8px;
        background: #015425;
        border-radius: 50%;
        display: inline-block;
    }
    
    .preview-row {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 1.5rem;
        padding: 1rem 0;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s ease;
    }
    
    .preview-row:hover {
        background: #f9fafb;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        border-radius: 8px;
    }
    
    .preview-row:last-child {
        border-bottom: none;
    }
    
    .preview-label {
        font-weight: 600;
        color: #4b5563;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .preview-value {
        color: #111827;
        font-size: 1rem;
        font-weight: 500;
        word-break: break-word;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .document-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .document-uploaded {
        background: #d1fae5;
        color: #065f46;
    }
    
    .document-missing {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .info-card {
        background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(1, 84, 37, 0.2);
    }
    
    .beneficiary-card {
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .beneficiary-card:last-child {
        margin-bottom: 0;
    }
    
    .beneficiary-header {
        font-weight: 700;
        color: #015425;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }
    
    @media (max-width: 768px) {
        .preview-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .preview-label {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        
        .preview-section {
            padding: 1.5rem;
        }
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        .preview-section {
            page-break-inside: avoid;
            box-shadow: none;
            border: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }
        
        .preview-row:hover {
            background: transparent;
            padding-left: 0;
            padding-right: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Reviewer Comments / Edit Request -->
    @if($user->editing_requested && $user->reviewer_comments)
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">Reviewer Requested Changes</h3>
                <p class="text-sm text-yellow-700 mb-3">A reviewer has requested that you make changes to your application. Please review the comments below and update your application accordingly.</p>
                <div class="bg-white rounded-lg p-4 border border-yellow-200">
                    <p class="text-sm font-medium text-gray-700 mb-2">Reviewer Comments:</p>
                    <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $user->reviewer_comments }}</p>
                    @if($user->editing_requested_at)
                    <p class="text-xs text-gray-500 mt-3">Requested on: {{ $user->editing_requested_at->format('F d, Y \a\t g:i A') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Application Status Card -->
    <div class="info-card no-print">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold mb-2">Membership Application Submitted</h1>
                <p class="text-white text-opacity-90 text-lg mb-3">Your application has been successfully submitted and is now under review.</p>
                <div class="flex items-center gap-3">
                    <span class="status-badge status-{{ $user->membership_status ?? 'pending' }}">
                        {{ ucfirst($user->membership_status ?? 'Pending') }}
                    </span>
                    @if($user->membership_code)
                    <span class="text-white text-opacity-80 font-semibold">Code: {{ $user->membership_code }}</span>
                    @endif
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('member.membership.download-pdf') }}" class="px-6 py-3 bg-white text-[#015425] rounded-lg hover:bg-gray-100 transition font-semibold text-center shadow-lg">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
                <button onclick="window.print()" class="px-6 py-3 bg-white bg-opacity-20 text-white rounded-lg hover:bg-opacity-30 transition font-semibold text-center border-2 border-white">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
            </div>
        </div>
    </div>

    <!-- Step 1: Membership Type -->
    <div class="preview-section">
        <h3>
            <span class="preview-section-header">
                <span>1. Membership Type Selection</span>
            </span>
            @if($user->editing_requested)
            <a href="{{ route('member.membership.step1') }}" class="edit-step-btn no-print">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            @endif
        </h3>
        <div class="preview-row">
            <div class="preview-label">Membership Type:</div>
            <div class="preview-value font-bold text-[#015425]">{{ $user->membershipType->name ?? 'Not selected' }}</div>
        </div>
        @if($user->membershipType)
        <div class="preview-row">
            <div class="preview-label">Description:</div>
            <div class="preview-value">{{ $user->membershipType->description ?? 'N/A' }}</div>
        </div>
        @endif
        <div class="preview-row">
            <div class="preview-label">Membership Code:</div>
            <div class="preview-value font-mono font-semibold">{{ $user->membership_code ?? 'Pending Assignment' }}</div>
        </div>
        @if($user->membershipType)
        <div class="preview-row">
            <div class="preview-label">Entrance Fee:</div>
            <div class="preview-value font-semibold text-green-600">{{ number_format($user->membershipType->entrance_fee ?? 0) }} TZS</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Capital Contribution:</div>
            <div class="preview-value font-semibold text-green-600">{{ number_format($user->membershipType->capital_contribution ?? 0) }} TZS</div>
        </div>
        @if($user->membershipType->minimum_shares > 0)
        <div class="preview-row">
            <div class="preview-label">Minimum Shares Required:</div>
            <div class="preview-value font-semibold">{{ $user->membershipType->minimum_shares }} shares</div>
        </div>
        @endif
        @endif
    </div>

    <!-- Step 2: Personal Information -->
    <div class="preview-section">
        <h3>
            <span class="preview-section-header">
                <span>2. Personal Information</span>
            </span>
            <a href="{{ route('member.membership.step2') }}" class="edit-step-btn no-print">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </h3>
        <div class="preview-row">
            <div class="preview-label">Full Name:</div>
            <div class="preview-value font-semibold">{{ $user->name }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Email Address:</div>
            <div class="preview-value">{{ $user->email }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Primary Phone:</div>
            <div class="preview-value font-mono">{{ $user->phone ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Alternate Phone:</div>
            <div class="preview-value font-mono">{{ $user->alternate_phone ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Gender:</div>
            <div class="preview-value">{{ ucfirst($user->gender ?? 'Not provided') }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Date of Birth:</div>
            <div class="preview-value">{{ $user->date_of_birth ? $user->date_of_birth->format('d F Y') . ' (' . $user->date_of_birth->age . ' years old)' : 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">National ID (NIDA):</div>
            <div class="preview-value font-mono font-semibold">{{ $user->national_id ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Marital Status:</div>
            <div class="preview-value">{{ ucfirst(str_replace('_', ' ', $user->marital_status ?? 'Not provided')) }}</div>
        </div>
    </div>

    <!-- Step 3: Address -->
    <div class="preview-section">
        <h3>
            <span class="preview-section-header">
                <span>3. Address Information</span>
            </span>
            <a href="{{ route('member.membership.step3') }}" class="edit-step-btn no-print">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </h3>
        <div class="preview-row">
            <div class="preview-label">Street Address:</div>
            <div class="preview-value">{{ $user->address ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">City/Town:</div>
            <div class="preview-value font-semibold">{{ $user->city ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Region:</div>
            <div class="preview-value font-semibold">{{ $user->region ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Postal Code:</div>
            <div class="preview-value font-mono">{{ $user->postal_code ?? 'Not provided' }}</div>
        </div>
    </div>

    <!-- Step 4: Employment -->
    <div class="preview-section">
        <h3>
            <span class="preview-section-header">
                <span>4. Employment Information</span>
            </span>
            <a href="{{ route('member.membership.step4') }}" class="edit-step-btn no-print">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </h3>
        <div class="preview-row">
            <div class="preview-label">Occupation/Profession:</div>
            <div class="preview-value">{{ $user->occupation ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Employer/Organization:</div>
            <div class="preview-value">{{ $user->employer ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Monthly Income:</div>
            <div class="preview-value font-semibold text-green-600">{{ $user->monthly_income ? number_format($user->monthly_income) . ' TZS' : 'Not provided' }}</div>
        </div>
    </div>

    <!-- Step 5: Bank Information -->
    <div class="preview-section">
        <h3>
            <span class="preview-section-header">
                <span>5. Bank Account Information</span>
            </span>
            <a href="{{ route('member.membership.step5') }}" class="edit-step-btn no-print">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </h3>
        <div class="preview-row">
            <div class="preview-label">Bank Name:</div>
            <div class="preview-value font-semibold">{{ $user->bank_name ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Bank Branch:</div>
            <div class="preview-value">{{ $user->bank_branch ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Account Number:</div>
            <div class="preview-value font-mono font-semibold">{{ $user->bank_account_number ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Payment Reference Number:</div>
            <div class="preview-value font-mono">{{ $user->payment_reference_number ?? 'Not provided' }}</div>
        </div>
    </div>

    <!-- Step 6: Additional Information -->
    <div class="preview-section">
        <h3>
            <span class="preview-section-header">
                <span>6. Additional Information & Preferences</span>
            </span>
            <a href="{{ route('member.membership.step6') }}" class="edit-step-btn no-print">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </h3>
        <div class="preview-row">
            <div class="preview-label">Statement Preference:</div>
            <div class="preview-value">
                @if($user->statement_preference)
                    <span class="inline-flex items-center gap-2">
                        @if($user->statement_preference === 'email')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        @elseif($user->statement_preference === 'sms')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        @endif
                        {{ ucfirst($user->statement_preference) }}
                    </span>
                @else
                    Not provided
                @endif
            </div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Short Biography:</div>
            <div class="preview-value">{{ $user->short_bibliography ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Introduced By:</div>
            <div class="preview-value">{{ $user->introduced_by ?? 'Not provided' }}</div>
        </div>
    </div>

    <!-- Step 7: Beneficiaries -->
    @if($user->beneficiaries_info && count($user->beneficiaries_info) > 0)
    <div class="preview-section">
        <h3>
            <span class="preview-section-header">
                <span>7. Beneficiaries Information</span>
            </span>
            <a href="{{ route('member.membership.step7') }}" class="edit-step-btn no-print">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </h3>
        @foreach($user->beneficiaries_info as $index => $beneficiary)
        <div class="beneficiary-card">
            <div class="beneficiary-header">Beneficiary {{ $index + 1 }}</div>
            <div class="preview-row">
                <div class="preview-label">Full Name:</div>
                <div class="preview-value font-semibold">{{ $beneficiary['name'] ?? 'Not provided' }}</div>
            </div>
            <div class="preview-row">
                <div class="preview-label">Relationship:</div>
                <div class="preview-value">{{ ucfirst($beneficiary['relationship'] ?? 'Not provided') }}</div>
            </div>
            <div class="preview-row">
                <div class="preview-label">Allocation Percentage:</div>
                <div class="preview-value font-semibold text-[#015425]">{{ $beneficiary['allocation'] ?? 0 }}%</div>
            </div>
            <div class="preview-row">
                <div class="preview-label">Contact Information:</div>
                <div class="preview-value font-mono">{{ $beneficiary['contact'] ?? 'Not provided' }}</div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="preview-section">
        <h3>
            <span class="preview-section-header">
                <span>7. Beneficiaries Information</span>
            </span>
            <a href="{{ route('member.membership.step7') }}" class="edit-step-btn no-print">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </h3>
        <div class="preview-row">
            <div class="preview-label">Beneficiaries:</div>
            <div class="preview-value text-gray-500 italic">No beneficiaries added</div>
        </div>
    </div>
    @endif

    <!-- Step 8: Group Information -->
    <div class="preview-section">
        <h3>
            <span class="preview-section-header">
                <span>8. Group Registration Information</span>
            </span>
            <a href="{{ route('member.membership.step8') }}" class="edit-step-btn no-print">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </h3>
        <div class="preview-row">
            <div class="preview-label">Group Registered:</div>
            <div class="preview-value">
                <span class="status-badge {{ $user->is_group_registered ? 'status-approved' : 'status-pending' }}">
                    {{ $user->is_group_registered ? 'Yes' : 'No' }}
                </span>
            </div>
        </div>
        @if($user->is_group_registered)
        <div class="preview-row">
            <div class="preview-label">Group Name:</div>
            <div class="preview-value font-semibold">{{ $user->group_name ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Group Leaders:</div>
            <div class="preview-value">{{ $user->group_leaders ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Group Bank Account:</div>
            <div class="preview-value font-mono">{{ $user->group_bank_account ?? 'Not provided' }}</div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Group Contact Information:</div>
            <div class="preview-value">{{ $user->group_contacts ?? 'Not provided' }}</div>
        </div>
        @endif
    </div>

    <!-- Step 9: Documents -->
    <div class="preview-section">
        <h3>
            <span class="preview-section-header">
                <span>9. Document Uploads</span>
            </span>
            <a href="{{ route('member.membership.step9') }}" class="edit-step-btn no-print">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </h3>
        <div class="preview-row">
            <div class="preview-label">Passport Picture:</div>
            <div class="preview-value">
                <span class="document-status {{ $user->passport_picture_path ? 'document-uploaded' : 'document-missing' }}">
                    @if($user->passport_picture_path)
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Uploaded
                    @else
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        Not Uploaded
                    @endif
                </span>
            </div>
        </div>
        <div class="preview-row">
            <div class="preview-label">NIDA Picture:</div>
            <div class="preview-value">
                <span class="document-status {{ $user->nida_picture_path ? 'document-uploaded' : 'document-missing' }}">
                    @if($user->nida_picture_path)
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Uploaded
                    @else
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        Not Uploaded
                    @endif
                </span>
            </div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Application Letter:</div>
            <div class="preview-value">
                <span class="document-status {{ $user->application_letter_path ? 'document-uploaded' : 'document-missing' }}">
                    @if($user->application_letter_path)
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Uploaded
                    @else
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        Not Uploaded
                    @endif
                </span>
            </div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Payment Slip:</div>
            <div class="preview-value">
                <span class="document-status {{ $user->payment_slip_path ? 'document-uploaded' : 'document-missing' }}">
                    @if($user->payment_slip_path)
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Uploaded
                    @else
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        Not Uploaded
                    @endif
                </span>
            </div>
        </div>
        <div class="preview-row">
            <div class="preview-label">Standing Order:</div>
            <div class="preview-value">
                <span class="document-status {{ $user->standing_order_path ? 'document-uploaded' : 'document-missing' }}">
                    @if($user->standing_order_path)
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Uploaded
                    @else
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        Not Uploaded
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Step 10: Options -->
    <div class="preview-section">
        <h3>
            <span class="preview-section-header">
                <span>10. Additional Options</span>
            </span>
            <a href="{{ route('member.membership.step10') }}" class="edit-step-btn no-print">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </h3>
        <div class="preview-row">
            <div class="preview-label">Ordinary Membership Request:</div>
            <div class="preview-value">
                <span class="status-badge {{ $user->wants_ordinary_membership ? 'status-approved' : 'status-pending' }}">
                    {{ $user->wants_ordinary_membership ? 'Yes' : 'No' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-lg shadow-md p-6 no-print border border-gray-200">
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('member.membership.download-pdf') }}" class="px-6 py-3 bg-[#015425] text-white rounded-lg hover:bg-[#013019] transition font-semibold text-center shadow-lg">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </a>
            <a href="{{ route('member.dashboard') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-semibold text-center shadow-lg">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
