@extends('layouts.member')

@section('page-title', 'Application Dossier - Certified Preview')

@push('styles')
<style>
    /* Certified Dossier Architecture */
    .dossier-container {
        max-width: 1000px;
        margin: 0 auto;
        padding-bottom: 5rem;
    }
    
    .dossier-section {
        background: white;
        border-radius: 3rem;
        padding: 4rem;
        margin-bottom: 3rem;
        box-shadow: 0 25px 50px -12px rgba(1, 84, 37, 0.05);
        border: 1px solid #f1f5f9;
        position: relative;
        overflow: hidden;
    }
    
    .dossier-section::after {
        content: 'FEEDTAN CERTIFIED';
        position: absolute;
        top: 2rem;
        right: -3rem;
        background: #f8fafc;
        color: #e2e8f0;
        font-weight: 900;
        font-size: 0.6rem;
        padding: 0.5rem 4rem;
        transform: rotate(45deg);
        letter-spacing: 0.2em;
    }
    
    .dossier-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 3rem;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 2rem;
    }
    
    .dossier-title {
        font-size: 1.5rem;
        font-weight: 900;
        color: #015425;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .dossier-title i {
        width: 12px;
        height: 12px;
        background: #015425;
        border-radius: 4px;
    }
    
    .dossier-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2.5rem;
    }
    
    .data-node {
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 1.5rem;
        transition: all 0.3s;
    }
    
    .data-node:hover {
        background: white;
        box-shadow: 0 10px 15px -3px rgba(1, 84, 37, 0.05);
        transform: translateY(-2px);
    }
    
    .data-label {
        font-size: 0.65rem;
        font-weight: 900;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.5rem;
    }
    
    .data-value {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        word-break: break-word;
    }
    
    .certified-badge {
        padding: 0.5rem 1.25rem;
        border-radius: 1rem;
        font-size: 0.7rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    
    .badge-success { background: #ecfdf5; color: #059669; }
    .badge-pending { background: #fffbeb; color: #d97706; }
    .badge-alert { background: #fef2f2; color: #dc2626; }

    .premium-action-bar {
        position: sticky;
        bottom: 2rem;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        padding: 1.5rem 3rem;
        border-radius: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 4rem;
        z-index: 50;
    }

    @media print {
        .no-print { display: none !important; }
        .dossier-section { box-shadow: none; border: 1px solid #eee; margin-bottom: 2rem; border-radius: 1rem; padding: 2rem; }
        .premium-action-bar { display: none; }
    }
</style>
@endpush

@section('content')
<div class="dossier-container">
    <!-- Header Protocol -->
    <div class="mb-12 no-print">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <p class="text-[10px] font-black text-[#015425] uppercase tracking-[0.3em] mb-2">Member Onboarding Sequence</p>
                <h1 class="text-5xl font-black text-gray-900 tracking-tighter">Application Dossier</h1>
            </div>
            <div class="flex items-center gap-4 bg-gray-50 px-6 py-3 rounded-2xl border border-gray-100">
                <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Status: {{ strtoupper($user->membership_status ?? 'Pending Verification') }}</span>
            </div>
        </div>
    </div>

    <!-- Reviewer Requisition Block -->
    @if($user->editing_requested && $user->reviewer_comments)
    <div class="bg-orange-50 rounded-[2.5rem] p-10 mb-12 border border-orange-100 no-print">
        <div class="flex items-start gap-6">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-orange-500 shadow-sm shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h3 class="text-xl font-black text-orange-900 mb-2 uppercase tracking-tight">Reviewer Modification Protocol</h3>
                <p class="text-sm text-orange-800/70 font-bold mb-6">The institutional review board has requested modifications to your dossier. Please update the relevant sections immediately.</p>
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-orange-200">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Directives:</p>
                    <p class="text-sm text-gray-700 font-medium whitespace-pre-wrap">{{ $user->reviewer_comments }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Section 1: Institutional Tier -->
    <div class="dossier-section">
        <div class="dossier-header">
            <h3 class="dossier-title"><i></i> Tier Parameter Identification</h3>
            @if($user->editing_requested)
            <a href="{{ route('member.membership.step1') }}" class="px-6 py-2 bg-[#015425] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:-translate-y-1 transition-all no-print">Modify Stage</a>
            @endif
        </div>
        <div class="dossier-grid">
            <div class="data-node">
                <p class="data-label">Membership Tier</p>
                <p class="data-value text-[#015425]">{{ $user->membershipType->name ?? 'NOT_SELECTED' }}</p>
            </div>
            <div class="data-node">
                <p class="data-label">System Index Code</p>
                <p class="data-value font-mono">{{ $user->membership_code ?? 'INIT_PENDING' }}</p>
            </div>
            <div class="data-node">
                <p class="data-label">Entrance Threshold</p>
                <p class="data-value">{{ number_format($user->membershipType->entrance_fee ?? 0) }} TZS</p>
            </div>
            <div class="data-node">
                <p class="data-label">Capital Commitment</p>
                <p class="data-value">{{ number_format($user->membershipType->capital_contribution ?? 0) }} TZS</p>
            </div>
        </div>
    </div>

    <!-- Section 2: Core Identity -->
    <div class="dossier-section">
        <div class="dossier-header">
            <h3 class="dossier-title"><i></i> Core Identity Matrix</h3>
            <a href="{{ route('member.membership.step2') }}" class="px-6 py-2 bg-[#015425] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:-translate-y-1 transition-all no-print">Modify Stage</a>
        </div>
        <div class="dossier-grid">
            <div class="data-node"><p class="data-label">Legal Name</p><p class="data-value">{{ $user->name }}</p></div>
            <div class="data-node"><p class="data-label">Comm Node (Email)</p><p class="data-value">{{ $user->email }}</p></div>
            <div class="data-node"><p class="data-label">Primary Gateway</p><p class="data-value font-mono">{{ $user->phone ?? 'NULL' }}</p></div>
            <div class="data-node"><p class="data-label">Chronological Index</p><p class="data-value">{{ $user->date_of_birth ? $user->date_of_birth->format('d F Y') : 'NULL' }}</p></div>
            <div class="data-node"><p class="data-label">NIDA Credential</p><p class="data-value font-mono">{{ $user->national_id ?? 'NULL' }}</p></div>
            <div class="data-node"><p class="data-label">Social Status</p><p class="data-value">{{ ucfirst($user->marital_status ?? 'NULL') }}</p></div>
        </div>
    </div>

    <!-- Section 3: Localization -->
    <div class="dossier-section">
        <div class="dossier-header">
            <h3 class="dossier-title"><i></i> Geospatial Location</h3>
            <a href="{{ route('member.membership.step3') }}" class="px-6 py-2 bg-[#015425] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:-translate-y-1 transition-all no-print">Modify Stage</a>
        </div>
        <div class="dossier-grid">
            <div class="data-node md:col-span-2"><p class="data-label">Residential coordinates</p><p class="data-value">{{ $user->address ?? 'NULL' }}</p></div>
            <div class="data-node"><p class="data-label">Municipal Hub</p><p class="data-value">{{ $user->city ?? 'NULL' }}</p></div>
            <div class="data-node"><p class="data-label">Region</p><p class="data-value">{{ $user->region ?? 'NULL' }}</p></div>
        </div>
    </div>

    <!-- Section 4: Economic Sector -->
    <div class="dossier-section">
        <div class="dossier-header">
            <h3 class="dossier-title"><i></i> Economic Yield Data</h3>
            <a href="{{ route('member.membership.step4') }}" class="px-6 py-2 bg-[#015425] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:-translate-y-1 transition-all no-print">Modify Stage</a>
        </div>
        <div class="dossier-grid">
            <div class="data-node"><p class="data-label">Professional Role</p><p class="data-value">{{ $user->occupation ?? 'NULL' }}</p></div>
            <div class="data-node"><p class="data-label">Institutional Affiliation</p><p class="data-value">{{ $user->employer ?? 'NULL' }}</p></div>
            <div class="data-node"><p class="data-label">Monthly Fiscal Yield</p><p class="data-value text-green-600 font-black">{{ $user->monthly_income ? number_format($user->monthly_income) . ' TZS' : 'NULL' }}</p></div>
        </div>
    </div>

    <!-- Section 5: Financial Interface -->
    <div class="dossier-section">
        <div class="dossier-header">
            <h3 class="dossier-title"><i></i> Settlement Interface</h3>
            <a href="{{ route('member.membership.step5') }}" class="px-6 py-2 bg-[#015425] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:-translate-y-1 transition-all no-print">Modify Stage</a>
        </div>
        <div class="dossier-grid">
            <div class="data-node"><p class="data-label">Banking Institution</p><p class="data-value">{{ $user->bank_name ?? 'NULL' }}</p></div>
            <div class="data-node"><p class="data-label">Account Index</p><p class="data-value font-mono">{{ $user->bank_account_number ?? 'NULL' }}</p></div>
            <div class="data-node"><p class="data-label">Payment Ref</p><p class="data-value">{{ $user->payment_reference_number ?? 'NULL' }}</p></div>
        </div>
    </div>

    <!-- Section 9: Archive Vault -->
    <div class="dossier-section">
        <div class="dossier-header">
            <h3 class="dossier-title"><i></i> Archive Vault Status</h3>
            <a href="{{ route('member.membership.step9') }}" class="px-6 py-2 bg-[#015425] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:-translate-y-1 transition-all no-print">Modify Stage</a>
        </div>
        <div class="dossier-grid">
            @php
                $docs = [
                    'Passport Biometric' => $user->passport_picture_path,
                    'ID Credential' => $user->nida_picture_path,
                    'Formal Decree' => $user->application_letter_path,
                    'Settlement Slip' => $user->payment_slip_path,
                    'Standing Order' => $user->standing_order_path
                ];
            @endphp
            @foreach($docs as $label => $path)
            <div class="data-node">
                <p class="data-label">{{ $label }}</p>
                <div class="flex items-center gap-2">
                    <span class="certified-badge {{ $path ? 'badge-success' : 'badge-alert' }}">
                        {{ $path ? 'ARCHIVED' : 'MISSING' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Dossier Authorization Bar -->
    <div class="premium-action-bar no-print">
        <div class="flex items-center gap-6">
            <div class="w-12 h-12 bg-gray-900 rounded-2xl flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Dossier Serialization</p>
                <p class="text-xs font-bold text-gray-900">Download for physical archival</p>
            </div>
        </div>
        
        <div class="flex gap-4">
            <button onclick="window.print()" class="px-8 py-4 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest transition-all border border-gray-200">
                Print Ledger
            </button>
            <a href="{{ route('member.membership.download-pdf') }}" class="px-10 py-4 bg-[#015425] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-green-900/20 hover:-translate-y-1 transition-all">
                Download PDF Dossier
            </a>
            <a href="{{ route('member.dashboard') }}" class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all">
                Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
