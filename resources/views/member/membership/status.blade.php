@extends('layouts.member')

@section('page-title', 'Application Status')

@section('content')
<div class="max-w-4xl mx-auto space-y-10 pb-20">
    <!-- Premium Header -->
    <div class="bg-gradient-to-br from-[#015425] via-[#027a3a] to-[#013019] rounded-[2.5rem] shadow-2xl p-10 sm:p-14 text-white relative overflow-hidden text-center md:text-left">
        <div class="absolute -right-24 -top-24 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <h1 class="text-4xl sm:text-6xl font-black mb-4 tracking-tight">Onboarding Orbit</h1>
            <p class="text-green-50 text-lg opacity-80 max-w-xl font-medium mx-auto md:mx-0">Tracking your digital integration within the FEEDTAN REJESHO ecosystem. Certification status updated in real-time.</p>
        </div>
    </div>

    <!-- Status Intelligence Card -->
    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10 sm:p-20">
            <div class="text-center space-y-8">
                @if($user->membership_status === 'pending')
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-yellow-400 opacity-20 blur-3xl rounded-full animate-pulse"></div>
                        <div class="relative w-24 h-24 bg-yellow-50 rounded-[2rem] flex items-center justify-center text-yellow-600 shadow-inner">
                            <svg class="w-12 h-12 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 mb-2">Protocol: Pending Review</h2>
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Onboarding Stage: Verification Authority</p>
                    </div>
                    <p class="text-sm text-gray-500 max-w-md mx-auto leading-relaxed">Your digital application is currently undergoing multi-factor authentication and governance review by our administration team.</p>
                
                @elseif($user->membership_status === 'approved')
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-green-400 opacity-20 blur-3xl rounded-full"></div>
                        <div class="relative w-24 h-24 bg-green-50 rounded-[2rem] flex items-center justify-center text-green-600 shadow-inner">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-green-600 mb-2">Protocol: Certified</h2>
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Onboarding Stage: Fully Integrated</p>
                    </div>
                    <p class="text-sm text-gray-500 max-w-md mx-auto leading-relaxed">Welcome to the inner circle. Your membership credentials have been verified and your access to premium capital is now live.</p>

                @elseif($user->membership_status === 'rejected')
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-red-400 opacity-20 blur-3xl rounded-full"></div>
                        <div class="relative w-24 h-24 bg-red-50 rounded-[2rem] flex items-center justify-center text-red-600 shadow-inner">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-red-600 mb-2">Protocol: Denied</h2>
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Onboarding Stage: Compliance Failure</p>
                    </div>
                    @if($user->status_reason)
                    <div class="mt-8 p-6 bg-red-50/50 rounded-3xl border border-red-100 text-left">
                        <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-2">Rejection Log</p>
                        <p class="text-sm font-bold text-red-800">{{ $user->status_reason }}</p>
                    </div>
                    @endif
                
                @elseif($user->membership_status === 'suspended')
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-orange-400 opacity-20 blur-3xl rounded-full"></div>
                        <div class="relative w-24 h-24 bg-orange-50 rounded-[2rem] flex items-center justify-center text-orange-600 shadow-inner">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-orange-600 mb-2">Protocol: Suspended</h2>
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Onboarding Stage: Inactive Mandate</p>
                    </div>
                    @if($user->status_reason)
                    <div class="mt-8 p-6 bg-orange-50/50 rounded-3xl border border-orange-100 text-left">
                        <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest mb-2">Suspension Log</p>
                        <p class="text-sm font-bold text-orange-800">{{ $user->status_reason }}</p>
                    </div>
                    @endif
                @endif
            </div>

            <!-- Detailed Parameters -->
            @if($user->membershipType)
            <div class="mt-20 pt-20 border-t border-gray-50 grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tier Level</p>
                    <p class="text-xl font-black text-gray-900 leading-tight">{{ $user->membershipType->name }}</p>
                    <p class="text-[10px] text-[#015425] font-bold uppercase mt-1">Authorized Profile Class</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Member Signature</p>
                    <p class="text-xl font-black text-gray-900 leading-tight font-mono">{{ $user->membership_code ?? 'PENDING_SIG' }}</p>
                    <p class="text-[10px] text-[#015425] font-bold uppercase mt-1">Unique Identifier Key</p>
                </div>
                @if($user->membership_approved_at)
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Certification Date</p>
                    <p class="text-xl font-black text-gray-900 leading-tight">{{ $user->membership_approved_at->format('M d, Y') }}</p>
                </div>
                @endif
                @if($user->membershipApprovedBy)
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Issuing Authority</p>
                    <p class="text-xl font-black text-gray-900 leading-tight">{{ $user->membershipApprovedBy->name }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Operation Console -->
            <div class="mt-20 pt-10 border-t border-gray-50 flex flex-wrap justify-center md:justify-start gap-4">
                @if($user->membership_status === 'pending')
                    @php $nextStep = max(1, $user->membership_application_current_step ?? 1); @endphp
                    <a href="{{ route('member.membership.step' . $nextStep) }}" class="px-10 py-5 bg-[#015425] text-white rounded-2xl font-black text-xs shadow-2xl hover:bg-[#013019] transition-all flex items-center gap-3">
                        CONTINUE ONBOARDING
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                @elseif($user->membership_status === 'approved')
                    <a href="{{ route('member.dashboard') }}" class="px-10 py-5 bg-[#015425] text-white rounded-2xl font-black text-xs shadow-2xl hover:bg-[#013019] transition-all">TERMINAL ACCESS</a>
                @elseif($user->membership_status === 'rejected')
                    @php $nextStep = max(1, $user->membership_application_current_step ?? 1); @endphp
                    <a href="{{ route('member.membership.step' . $nextStep) }}" class="px-10 py-5 bg-[#015425] text-white rounded-2xl font-black text-xs shadow-2xl hover:bg-[#013019] transition-all">RE-INITIALIZE APPLICATION</a>
                @endif
                
                @if($user->membership_type_id)
                <a href="{{ route('member.membership.preview') }}" class="px-10 py-5 bg-gray-900 text-white rounded-2xl font-black text-xs shadow-xl hover:-translate-y-1 transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    PREVIEW DATA
                </a>
                <a href="{{ route('member.membership.download-pdf') }}" class="px-10 py-5 bg-red-600 text-white rounded-2xl font-black text-xs shadow-xl hover:bg-red-700 transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    EXPORT PDF
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.animate-spin-slow {
    animation: spin-slow 8s linear infinite;
}
</style>
@endsection
