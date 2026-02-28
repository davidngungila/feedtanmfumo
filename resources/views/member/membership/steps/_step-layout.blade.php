@php
    $currentStep = $currentStep ?? 1;
    $completedSteps = $user->membership_application_completed_steps ?? [];
    $stepTitles = [
        1 => 'Selection',
        2 => 'Identity',
        3 => 'Location',
        4 => 'Occupation',
        5 => 'Equity',
        6 => 'Parameters',
        7 => 'Beneficiaries',
        8 => 'Social',
        9 => 'Verification',
        10 => 'Finalize',
    ];
@endphp

@extends('layouts.member')

@section('page-title', 'Onboarding Journey - Stage ' . $currentStep)

@push('styles')
<style>
    /* Premium Progress Architecture */
    .step-progress-container {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border-radius: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 2.5rem;
        margin-bottom: 3rem;
        box-shadow: 0 10px 40px rgba(1, 84, 37, 0.05);
    }
    
    .step-indicator {
        display: flex;
        justify-content: space-between;
        position: relative;
        padding-bottom: 1.5rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    
    .step-indicator::-webkit-scrollbar { display: none; }
    
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        right: 0;
        height: 2px;
        background: #f1f5f9;
        z-index: 0;
    }
    
    .step-progress-line {
        position: absolute;
        top: 24px;
        left: 0;
        height: 2px;
        background: linear-gradient(to right, #10b981, #015425);
        z-index: 1;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .step-item {
        position: relative;
        z-index: 2;
        flex: 1;
        text-align: center;
        min-width: 80px;
    }
    
    .step-number {
        width: 48px;
        height: 48px;
        border-radius: 1.25rem;
        background: white;
        border: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-weight: 900;
        font-size: 0.9rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        color: #94a3b8;
    }
    
    .step-item.completed .step-number {
        background: #015425;
        border-color: #015425;
        color: white;
        box-shadow: 0 10px 15px -3px rgba(1, 84, 37, 0.2);
    }
    
    .step-item.active .step-number {
        background: white;
        border-color: #015425;
        color: #015425;
        transform: scale(1.15);
        box-shadow: 0 20px 25px -5px rgba(1, 84, 37, 0.1);
    }
    
    .step-title {
        font-size: 0.65rem;
        color: #94a3b8;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.3s;
    }
    
    .step-item.active .step-title {
        color: #015425;
        transform: translateY(2px);
    }
    
    /* Content Sculpting */
    .step-content-card {
        background: white;
        border-radius: 3rem;
        box-shadow: 0 25px 50px -12px rgba(1, 84, 37, 0.08);
        border: 1px solid #f8fafc;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .step-header-banner {
        padding: 4rem 3rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e2e8f0;
    }
    
    .step-body {
        padding: 4rem 3rem;
    }
    
    .premium-label {
        display: block;
        font-weight: 900;
        font-size: 10px;
        color: #015425;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        margin-bottom: 0.75rem;
        margin-left: 0.25rem;
    }
    
    .premium-input {
        width: 100%;
        padding: 1.25rem 1.5rem;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 1.25rem;
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        box-sizing: border-box;
        line-height: 1.25;
        transition: all 0.3s;
    }
    
    .premium-input:focus {
        background: #ffffff;
        border-color: #015425;
        outline: none;
        box-shadow: 0 10px 15px -3px rgba(1, 84, 37, 0.1);
    }

    .premium-input::placeholder {
        color: #94a3b8;
        font-weight: 700;
    }

    @media (max-width: 640px) {
        .step-header-banner { padding: 2.5rem 1.5rem; }
        .step-body { padding: 2.5rem 1.5rem; }
    }
    
    .luxury-button {
        background: #015425;
        color: white;
        padding: 1.25rem 3rem;
        border-radius: 1.5rem;
        font-weight: 900;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        transition: all 0.3s;
        box-shadow: 0 20px 25px -5px rgba(1, 84, 37, 0.3);
        border: none;
        cursor: pointer;
    }
    
    .luxury-button:hover {
        transform: translateY(-3px);
        background: #027a3a;
        box-shadow: 0 25px 30px -5px rgba(2, 122, 58, 0.4);
    }
    
    .luxury-button:disabled {
        background: #94a3b8;
        box-shadow: none;
        cursor: not-allowed;
        transform: none;
    }

    /* Modal Styling */
    .save-popup-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s;
    }
    .save-popup-overlay.show { opacity: 1; visibility: visible; }
    
    .save-popup-content {
        background: white;
        border-radius: 2.5rem;
        padding: 3rem;
        max-width: 400px;
        width: 90%;
        text-align: center;
        transform: scale(0.9);
        transition: all 0.4s;
    }
    .save-popup-overlay.show .save-popup-content { transform: scale(1); }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto py-8 lg:py-12">
    <!-- Sophisticated Progress Navigation -->
    <div class="step-progress-container">
        <div class="step-indicator" id="stepIndicator">
            <div class="step-progress-line" style="width: {{ (($currentStep - 1) / 9) * 100 }}%;"></div>
            @for($i = 1; $i <= 10; $i++)
                @php
                    $isCompleted = in_array($i, $completedSteps);
                    $isActive = $i == $currentStep;
                    $isLocked = !$isCompleted && !$isActive && $i > 1 && !in_array($i - 1, $completedSteps);
                @endphp
                <div class="step-item {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }} {{ $isLocked ? 'locked' : '' }}">
                    <div class="step-number">
                        @if($isCompleted)
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        @else
                            {{ $i }}
                        @endif
                    </div>
                    <div class="step-title">{{ $stepTitles[$i] }}</div>
                </div>
            @endfor
        </div>
        
        <div class="flex items-center justify-between mt-10 px-4">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Stage {{ $currentStep }} of 10</span>
            </div>
            <div class="text-[10px] font-black uppercase text-[#015425] tracking-widest bg-green-50 px-4 py-1.5 rounded-full">
                {{ floor((count($completedSteps) / 10) * 100) }}% Complete
            </div>
        </div>
    </div>

    <!-- Main Content Vessel -->
    <div class="step-content-card">
        @yield('step-content')
    </div>
</div>

<!-- Global Notifications -->
<div id="toastContainer" class="fixed top-8 right-8 z-[100] space-y-4"></div>

<div id="savePopupOverlay" class="save-popup-overlay">
    <div class="save-popup-content">
        <div id="popupIcon" class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6"></div>
        <h3 id="popupTitle" class="text-2xl font-black text-gray-900 mb-2"></h3>
        <p id="popupMessage" class="text-sm text-gray-500 font-medium mb-8"></p>
        <button onclick="closeSavePopup()" class="luxury-button w-full">Acknowledged</button>
    </div>
</div>

<script>
    // System Intelligence
    function showToast(type, title, message) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        const color = type === 'success' ? '#015425' : '#ef4444';
        
        toast.className = "p-6 bg-white rounded-3xl shadow-2xl border border-gray-100 flex items-center gap-4 animate-in slide-in-from-right duration-500";
        toast.innerHTML = `
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white" style="background: ${color}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="text-sm font-black text-gray-900">${title}</p>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">${message}</p>
            </div>
        `;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }

    function showSavePopup(type, title, message) {
        const overlay = document.getElementById('savePopupOverlay');
        const icon = document.getElementById('popupIcon');
        const titleEl = document.getElementById('popupTitle');
        const msgEl = document.getElementById('popupMessage');
        
        icon.className = `w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 ${type === 'success' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600'}`;
        icon.innerHTML = `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>`;
        titleEl.textContent = title;
        msgEl.textContent = message;
        
        overlay.classList.add('show');
        setTimeout(closeSavePopup, 4000);
    }

    function closeSavePopup() {
        document.getElementById('savePopupOverlay').classList.remove('show');
    }

    @if(session('success')) showToast('success', 'Certification Success', '{{ session('success') }}'); @endif
    @if(session('error')) showToast('error', 'Protocol Alert', '{{ session('error') }}'); @endif

    // Ajax Orchestration
    function setupAjaxForm(formId) {
        const form = document.getElementById(formId);
        if(!form) return;
        
        form.addEventListener('submit', async function(e) {
            if (!form.checkValidity()) return;
            e.preventDefault();
            
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="animate-pulse">PROCESSING DATA...</span>';
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();
                
                if (data.success) {
                    showSavePopup('success', 'Stage Certified', data.message);
                    if (data.redirect) setTimeout(() => window.location.href = data.redirect, 1500);
                } else {
                    showSavePopup('error', 'Verification Failed', data.message);
                }
            } catch (err) {
                showSavePopup('error', 'Network Interruption', 'Establish connection and retry.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[id^="step"]').forEach(f => setupAjaxForm(f.id));
        
        // Mobile Navigation Optimization
        const stepContainer = document.getElementById('stepIndicator');
        const activeItem = stepContainer.querySelector('.step-item.active');
        if (activeItem && window.innerWidth < 768) {
            stepContainer.scrollTo({ left: activeItem.offsetLeft - 100, behavior: 'smooth' });
        }
    });
</script>
@endsection
