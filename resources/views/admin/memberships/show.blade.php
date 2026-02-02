@extends('layouts.admin')

@section('page-title', 'Membership Application Details')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Membership Application Details</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">{{ $user->name }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.memberships.pdf', $user) }}" target="_blank" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-medium shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.memberships.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Applications
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <p class="text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Status Badge -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-[#015425] mb-2">Application Status</h2>
                @if($user->membership_status === 'pending')
                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-medium">Pending Approval</span>
                @elseif($user->membership_status === 'approved')
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium">Approved</span>
                @elseif($user->membership_status === 'rejected')
                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full font-medium">Rejected</span>
                @elseif($user->membership_status === 'suspended')
                <span class="px-4 py-2 bg-orange-100 text-orange-800 rounded-full font-medium">Suspended</span>
                @endif
            </div>
            @if($user->membership_approved_at)
            <div class="text-sm text-gray-600">
                <p>Approved: {{ $user->membership_approved_at->format('M d, Y') }}</p>
                @if($user->membershipApprovedBy)
                <p>By: {{ $user->membershipApprovedBy->name }}</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Personal Information -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Personal Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <p class="text-gray-900">{{ $user->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <p class="text-gray-900">{{ $user->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <p class="text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                <p class="text-gray-900">{{ ucfirst($user->gender ?? 'N/A') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                <p class="text-gray-900">{{ $user->date_of_birth?->format('M d, Y') ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">National ID (NIDA)</label>
                <p class="text-gray-900">{{ $user->national_id ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                <p class="text-gray-900">{{ ucfirst($user->marital_status ?? 'N/A') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <p class="text-gray-900">{{ $user->address ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Membership Information -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Membership Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Membership Type</label>
                <p class="text-gray-900 font-semibold">{{ $user->membershipType->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Membership Code</label>
                <p class="text-gray-900">{{ $user->membership_code ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Number of Shares</label>
                <p class="text-gray-900">{{ number_format($user->number_of_shares ?? 0) }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Entrance Fee</label>
                <p class="text-gray-900">{{ number_format($user->entrance_fee ?? 0) }} TZS</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capital Contribution</label>
                <p class="text-gray-900">{{ number_format($user->capital_contribution ?? 0) }} TZS</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Account</label>
                <p class="text-gray-900">{{ $user->bank_account_number ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Documents -->
    @if($user->passport_picture_path || $user->nida_picture_path || $user->application_letter_path || $user->payment_slip_path)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Uploaded Documents</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($user->passport_picture_path)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Passport Picture</label>
                <a href="{{ Storage::url($user->passport_picture_path) }}" target="_blank" class="text-[#015425] hover:underline">View Document</a>
            </div>
            @endif
            @if($user->nida_picture_path)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NIDA Picture</label>
                <a href="{{ Storage::url($user->nida_picture_path) }}" target="_blank" class="text-[#015425] hover:underline">View Document</a>
            </div>
            @endif
            @if($user->application_letter_path)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Application Letter</label>
                <a href="{{ Storage::url($user->application_letter_path) }}" target="_blank" class="text-[#015425] hover:underline">View Document</a>
            </div>
            @endif
            @if($user->payment_slip_path)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Slip</label>
                <a href="{{ Storage::url($user->payment_slip_path) }}" target="_blank" class="text-[#015425] hover:underline">View Document</a>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    @if($user->membership_status === 'pending')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Actions</h2>
        
        <!-- Approve Form -->
        <form method="POST" action="{{ route('admin.memberships.approve', $user) }}" class="mb-6 border-b border-gray-200 pb-6">
            @csrf
            <h3 class="text-lg font-semibold text-green-700 mb-4">Approve Membership</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Membership Code</label>
                    <input type="text" name="membership_code" value="{{ $user->membership_code }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Shares</label>
                    <input type="number" name="number_of_shares" value="{{ $user->number_of_shares ?? 0 }}" min="0" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Entrance Fee (TZS)</label>
                    <input type="number" name="entrance_fee" value="{{ $user->entrance_fee ?? 0 }}" min="0" step="0.01" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capital Contribution (TZS)</label>
                    <input type="number" name="capital_contribution" value="{{ $user->capital_contribution ?? 0 }}" min="0" step="0.01" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ $user->notes }}</textarea>
            </div>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                Approve Membership
            </button>
        </form>

        <!-- Request Edits Form -->
        <div class="mb-6 pb-6 border-b border-gray-200">
            @if($user->editing_requested)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h4 class="text-sm font-semibold text-yellow-800 mb-2">Edit Request Active</h4>
                        <p class="text-sm text-yellow-700 mb-2">Applicant has been requested to make changes. Current comments:</p>
                        <div class="bg-white rounded p-3 mb-3">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $user->reviewer_comments }}</p>
                        </div>
                        @if($user->editing_requested_at)
                        <p class="text-xs text-yellow-600">Requested on: {{ $user->editing_requested_at->format('F d, Y \a\t g:i A') }}</p>
                        @endif
                        <form method="POST" action="{{ route('admin.memberships.clear-edit-request', $user) }}" class="mt-3">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition text-sm">
                                Clear Edit Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            
            <form method="POST" action="{{ route('admin.memberships.request-edits', $user) }}">
                @csrf
                <h3 class="text-lg font-semibold text-yellow-700 mb-4">Request Edits from Applicant</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comments / Issues to Address *</label>
                    <textarea name="reviewer_comments" rows="5" required 
                              placeholder="Enter specific comments, issues, or details that need to be corrected or updated by the applicant..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('reviewer_comments', $user->reviewer_comments) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">The applicant will be able to see these comments and edit their application accordingly.</p>
                </div>
                <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                    Request Edits
                </button>
            </form>
        </div>

        <!-- Reject Form -->
        <form method="POST" action="{{ route('admin.memberships.reject', $user) }}">
            @csrf
            <h3 class="text-lg font-semibold text-red-700 mb-4">Reject Membership</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                <textarea name="rejection_reason" rows="3" required 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"></textarea>
            </div>
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                Reject Application
            </button>
        </form>
    </div>
    @elseif($user->membership_status === 'approved')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Actions</h2>
        <form method="POST" action="{{ route('admin.memberships.suspend', $user) }}" class="mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Suspension Reason *</label>
                <textarea name="suspension_reason" rows="3" required 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]"></textarea>
            </div>
            <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition">
                Suspend Membership
            </button>
        </form>
    </div>
    @elseif($user->membership_status === 'suspended')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Actions</h2>
        <form method="POST" action="{{ route('admin.memberships.reactivate', $user) }}">
            @csrf
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                Reactivate Membership
            </button>
        </form>
    </div>
    @endif
</div>
@endsection

