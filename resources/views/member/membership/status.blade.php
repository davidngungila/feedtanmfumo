@extends('layouts.member')

@section('page-title', 'Membership Status')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Membership Status</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">View your membership application status</p>
            </div>
        </div>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-6">
            @if($user->membership_status === 'pending')
            <div class="inline-block p-4 bg-yellow-100 rounded-full mb-4">
                <svg class="w-16 h-16 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-yellow-600 mb-2">Pending Approval</h2>
            <p class="text-gray-600">Your membership application is being reviewed by administrators.</p>
            @elseif($user->membership_status === 'approved')
            <div class="inline-block p-4 bg-green-100 rounded-full mb-4">
                <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-green-600 mb-2">Membership Approved!</h2>
            <p class="text-gray-600">Congratulations! Your membership has been approved.</p>
            @elseif($user->membership_status === 'rejected')
            <div class="inline-block p-4 bg-red-100 rounded-full mb-4">
                <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-red-600 mb-2">Application Rejected</h2>
            <p class="text-gray-600">Your membership application has been rejected.</p>
            @if($user->status_reason)
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                <p class="text-sm font-medium text-red-800 mb-1">Reason:</p>
                <p class="text-sm text-red-700">{{ $user->status_reason }}</p>
            </div>
            @endif
            @elseif($user->membership_status === 'suspended')
            <div class="inline-block p-4 bg-orange-100 rounded-full mb-4">
                <svg class="w-16 h-16 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-orange-600 mb-2">Membership Suspended</h2>
            <p class="text-gray-600">Your membership has been temporarily suspended.</p>
            @if($user->status_reason)
            <div class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded-md">
                <p class="text-sm font-medium text-orange-800 mb-1">Reason:</p>
                <p class="text-sm text-orange-700">{{ $user->status_reason }}</p>
            </div>
            @endif
            @endif
        </div>

        <!-- Membership Details -->
        @if($user->membershipType)
        <div class="border-t border-gray-200 pt-6 mt-6">
            <h3 class="text-lg font-semibold text-[#015425] mb-4">Membership Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Membership Type</label>
                    <p class="text-gray-900 font-semibold">{{ $user->membershipType->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Membership Code</label>
                    <p class="text-gray-900">{{ $user->membership_code ?? 'N/A' }}</p>
                </div>
                @if($user->membership_approved_at)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Approved Date</label>
                    <p class="text-gray-900">{{ $user->membership_approved_at->format('M d, Y') }}</p>
                </div>
                @endif
                @if($user->membershipApprovedBy)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                    <p class="text-gray-900">{{ $user->membershipApprovedBy->name }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="border-t border-gray-200 pt-6 mt-6">
            <div class="flex flex-wrap gap-3">
                @if($user->membership_status === 'pending')
                    @php
                        $nextStep = $user->membership_application_current_step ?? 1;
                        if ($nextStep == 0) $nextStep = 1;
                    @endphp
                    <a href="{{ route('member.membership.step' . $nextStep) }}" class="inline-block px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                        Continue Application
                    </a>
                @elseif($user->membership_status === 'approved')
                    <a href="{{ route('member.dashboard') }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        Go to Dashboard
                    </a>
                @elseif($user->membership_status === 'rejected')
                    @php
                        $nextStep = $user->membership_application_current_step ?? 1;
                        if ($nextStep == 0) $nextStep = 1;
                    @endphp
                    <a href="{{ route('member.membership.step' . $nextStep) }}" class="inline-block px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                        Reapply
                    </a>
                @endif
                
                @if($user->membership_type_id)
                <a href="{{ route('member.membership.preview') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview Application
                </a>
                <a href="{{ route('member.membership.download-pdf') }}" class="inline-block px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

