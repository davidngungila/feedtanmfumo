@php
    $currentStep = 10;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header-banner">
    <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Final Authorization</h2>
    <p class="text-gray-500 font-medium max-w-2xl leading-relaxed">Execute final protocols and authorize your membership application. Once committed, your data will undergo institutional verification for full account activation.</p>
</div>

<div class="step-body">
    <form method="POST" action="{{ route('member.membership.store-step10') }}" id="step10Form">
        @csrf
        
        <div class="space-y-12">
            <!-- Ordinary Membership Option -->
            <div class="bg-blue-50/50 p-10 rounded-[3rem] border border-blue-100 group transition-all hover:bg-white hover:shadow-2xl hover:shadow-blue-900/5">
                <label class="flex items-start cursor-pointer">
                    <div class="relative mt-1">
                        <input type="checkbox" name="wants_ordinary_membership" value="1" 
                               {{ old('wants_ordinary_membership', $user->wants_ordinary_membership) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-10 h-10 bg-white border-2 border-blue-200 rounded-xl flex items-center justify-center peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all">
                             <svg class="w-6 h-6 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <span class="ml-6 block">
                        <span class="text-lg font-black text-gray-900 uppercase tracking-tight block mb-2">Institutional Voting Status (Ordinary Membership)</span>
                        <p class="text-sm text-blue-900/60 font-bold leading-relaxed">Declare intent for Ordinary Membership status. This grants sovereign voting rights and advanced dividend participation within the FEEDTAN hierarchy.</p>
                    </span>
                </label>
            </div>
            
            <!-- Application Summary Ledger -->
            <div class="bg-gray-900 rounded-[3rem] p-12 text-white relative overflow-hidden group shadow-2xl">
                <div class="absolute top-0 right-0 w-64 h-64 bg-green-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-green-400 backdrop-blur-md">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight uppercase">Application Summary Ledger</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 border-t border-white/10 pt-10">
                        <div class="space-y-6">
                            <div>
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Assigned Tier</p>
                                <p class="text-xl font-bold text-green-400">{{ $user->membershipType->name ?? 'Dormant' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Verification Code</p>
                                <p class="text-xl font-mono text-white opacity-90">{{ $user->membership_code ?? 'PENDING_INIT' }}</p>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Protocol Integrity</p>
                                <p class="text-xl font-bold text-white">{{ count($user->membership_application_completed_steps ?? []) }} / 10 Stages Ready</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">System Readiness</p>
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                                    <p class="text-xl font-bold text-white uppercase tracking-tight">Prime for Execution</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pt-10 border-t border-gray-50">
                <a href="{{ route('member.membership.step9') }}" class="px-10 py-5 bg-gray-50 hover:bg-gray-100 text-gray-400 rounded-2xl font-black text-xs uppercase tracking-widest transition-all text-center border border-gray-100">
                    STAGE 09
                </a>
                <a href="{{ route('member.membership.preview') }}" target="_blank" class="px-10 py-5 bg-white border-2 border-[#015425] text-[#015425] rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#015425] hover:text-white transition-all text-center flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    PREVIEW LEDGE
                </a>
                <button type="submit" class="luxury-button flex items-center justify-center gap-4 bg-gradient-to-r from-[#015425] to-[#027a3a] shadow-green-900/40">
                    EXECUTE SUBMISSION
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                </button>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    /* Premium Modal & Progress Overlays */
    .premium-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 5, 2, 0.8);
        backdrop-filter: blur(20px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    }
    .premium-modal-overlay.show { opacity: 1; visibility: visible; }
    
    .premium-modal-content {
        background: white;
        border-radius: 4rem;
        padding: 4rem;
        max-width: 550px;
        width: 90%;
        text-align: center;
        transform: translateY(40px) scale(0.95);
        transition: all 0.6s cubic-bezier(0.19, 1, 0.22, 1);
        box-shadow: 0 40px 100px rgba(0, 0, 0, 0.4);
    }
    .premium-modal-overlay.show .premium-modal-content { transform: translateY(0) scale(1); }

    .progress-hub {
        position: fixed;
        inset: 0;
        background: #011a0c;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 20000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.8s;
        color: white;
    }
    .progress-hub.show { opacity: 1; visibility: visible; }

    .pulse-orbit {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 2px solid rgba(16, 185, 129, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin-bottom: 3rem;
    }
    .pulse-orbit::before {
        content: '';
        position: absolute;
        inset: -20px;
        border-radius: 50%;
        border: 1px solid rgba(16, 185, 129, 0.05);
        animation: orbit-spin 4s linear infinite;
    }
    @keyframes orbit-spin { to { transform: rotate(360deg); } }

    .hub-loader {
        width: 100%;
        height: 4px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 2px;
        overflow: hidden;
        margin-top: 2rem;
        max-width: 300px;
    }
    .hub-loader-bar {
        height: 100%;
        background: #10b981;
        width: 0%;
        transition: width 0.4s ease;
        box-shadow: 0 0 20px #10b981;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('step10Form');
    if (!form) return;

    // Premium Confirmation Modal
    const modalHTML = `
        <div id="confirmModal" class="premium-modal-overlay">
            <div class="premium-modal-content">
                <div class="w-24 h-24 bg-orange-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 text-orange-500">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-4 uppercase tracking-tight">Final Authorization</h3>
                <p class="text-gray-500 font-bold leading-relaxed mb-10">You are about to execute the final submission protocol. Once authorized, application data becomes immutable and enters the verification queue.</p>
                <div class="flex gap-4">
                    <button type="button" class="flex-1 py-5 bg-gray-100 text-gray-500 rounded-[2rem] font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all" id="cancelBtn">Abbreviate</button>
                    <button type="button" class="flex-1 py-5 bg-[#015425] text-white rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-xl shadow-green-900/20 hover:-translate-y-1 transition-all" id="confirmBtn">Confirm & Execute</button>
                </div>
            </div>
        </div>
    `;

    // High-Tech Progress Hub
    const progressHTML = `
        <div id="progressHub" class="progress-hub">
            <div class="pulse-orbit">
                <div class="w-32 h-32 bg-[#10b981] rounded-full flex items-center justify-center text-white shadow-[0_0_60px_rgba(16,185,129,0.4)]">
                    <svg class="w-16 h-16 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h2 class="text-4xl font-black mb-2 uppercase tracking-tighter" id="hubTitle">Initializing Protocol</h2>
            <p class="text-green-500 font-black text-[10px] uppercase tracking-[0.4em] mb-10" id="hubPercentage">0% SYNCHRONIZED</p>
            <p class="text-gray-500 font-bold max-w-sm text-center leading-relaxed" id="hunMessage">Deploying encryption vectors and validating institutional datasets...</p>
            <div class="hub-loader">
                <div class="hub-loader-bar" id="hubBar"></div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    document.body.insertAdjacentHTML('beforeend', progressHTML);

    const modal = document.getElementById('confirmModal');
    const hub = document.getElementById('progressHub');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmBtn = document.getElementById('confirmBtn');
    const hubBar = document.getElementById('hubBar');
    const hubPerc = document.getElementById('hubPercentage');
    const hubTitle = document.getElementById('hubTitle');
    const hubMsg = document.getElementById('hunMessage');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        modal.classList.add('show');
    });

    cancelBtn.addEventListener('click', () => modal.classList.remove('show'));

    confirmBtn.addEventListener('click', function() {
        modal.classList.remove('show');
        hub.classList.add('show');
        
        let progress = 0;
        const statusMap = [
            { p: 15, t: 'Validating Keys', m: 'Ensuring integrity of identity vectors...' },
            { p: 35, t: 'Encrypting Data', m: 'Applying AES-256 protocols to sensitive archives...' },
            { p: 55, t: 'Routing Deposits', m: 'Interfacing with institutional banking nodes...' },
            { p: 75, t: 'Finalizing Ledger', m: 'Committing transaction hash to collective history...' },
            { p: 100, t: 'Authorized', m: 'Protocol successfully executed. Redirecting...' }
        ];

        const timer = setInterval(() => {
            progress += 1;
            if (progress > 100) {
                clearInterval(timer);
                return;
            }

            hubBar.style.width = progress + '%';
            hubPerc.textContent = progress + '% SYNCHRONIZED';

            const status = statusMap.find(s => progress <= s.p) || statusMap[statusMap.length - 1];
            hubTitle.textContent = status.t;
            hubMsg.textContent = status.m;

            if (progress === 50) {
                const formData = new FormData(form);
                formData.append('_ajax', '1');
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    setTimeout(() => {
                        if (data.success && data.redirect) window.location.href = data.redirect;
                        else window.location.reload();
                    }, 1000);
                })
                .catch(() => {
                    clearInterval(timer);
                    hubTitle.textContent = 'PROTOCOL FAILURE';
                    hubMsg.textContent = 'Terminal interruption detected. Re-initializing connection...';
                    setTimeout(() => window.location.reload(), 3000);
                });
            }
        }, 60);
    });
});
</script>
@endpush
@endsection
