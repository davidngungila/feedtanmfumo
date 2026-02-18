@php
    $currentStep = 7;
    $beneficiaries = $beneficiaries ?? [];
    if (empty($beneficiaries)) {
        $beneficiaries = [['name' => '', 'relationship' => '', 'allocation' => '', 'contact' => '']];
    }
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header-banner">
    <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Succession Mandate</h2>
    <p class="text-gray-500 font-medium max-w-2xl leading-relaxed">Designate your legacy beneficiaries. Defining clear allocation percentages ensures strategic asset continuity and family financial security.</p>
</div>

<div class="step-body">
    <form method="POST" action="{{ route('member.membership.store-step7') }}" id="step7Form" class="ajax-form">
        @csrf
        
        <div id="beneficiaries-container" class="space-y-12 mb-12">
            @foreach($beneficiaries as $index => $beneficiary)
            <div class="beneficiary-item relative bg-gray-50/50 rounded-[3rem] p-10 border border-gray-100 group transition-all duration-500 hover:bg-white hover:shadow-2xl hover:shadow-gray-900/5">
                <div class="flex justify-between items-center mb-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#015425] shadow-sm font-black text-lg">
                            {{ $index + 1 }}
                        </div>
                        <h3 class="text-xl font-black text-gray-900">Beneficiary Identity Node</h3>
                    </div>
                    @if($index > 0)
                    <button type="button" onclick="removeBeneficiary(this)" class="px-5 py-2 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                        Terminate Entry
                    </button>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <label class="premium-label">Full Identity Name</label>
                        <input type="text" name="beneficiaries[{{ $index }}][name]" value="{{ $beneficiary['name'] ?? '' }}" 
                               class="premium-input bg-white/70" placeholder="Beneficiary Legal Name">
                    </div>
                    <div class="space-y-4">
                        <label class="premium-label">Social Relationship Vector</label>
                        <input type="text" name="beneficiaries[{{ $index }}][relationship]" value="{{ $beneficiary['relationship'] ?? '' }}" 
                               class="premium-input bg-white/70" placeholder="e.g., Blood Relation, Spouse, Ward">
                    </div>
                    <div class="space-y-4">
                        <label class="premium-label">Asset Allocation (%)</label>
                        <div class="relative">
                            <input type="number" name="beneficiaries[{{ $index }}][allocation]" value="{{ $beneficiary['allocation'] ?? '' }}" 
                                   min="0" max="100" step="0.01" class="premium-input bg-white/70 pr-12" placeholder="0.00">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-black text-gray-300">%</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="premium-label">Contact Link (Mobile / Encrypted)</label>
                        <input type="text" name="beneficiaries[{{ $index }}][contact]" value="{{ $beneficiary['contact'] ?? '' }}" 
                               class="premium-input bg-white/70" placeholder="Phone or digital identifier">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="flex flex-col md:flex-row gap-6 mb-12">
            <button type="button" onclick="addBeneficiary()" class="flex-1 py-5 bg-white border-2 border-dashed border-gray-200 text-gray-500 rounded-[2rem] font-black text-xs uppercase tracking-widest hover:border-[#015425] hover:text-[#015425] transition-all flex items-center justify-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                Deploy Additional Beneficiary Node
            </button>
            <div class="p-6 bg-orange-50/50 rounded-[2rem] border border-orange-100 flex items-center gap-4 flex-1">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-orange-600 shadow-sm shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.268 17c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-[10px] text-orange-900 font-bold leading-relaxed px-2">Total legacy allocation must equate to exactly 100.00% for successful certification.</p>
            </div>
        </div>
        
        <div class="flex justify-between items-center pt-10 border-t border-gray-50">
            <a href="{{ route('member.membership.step6') }}" class="px-8 py-4 bg-gray-50 hover:bg-gray-100 text-gray-400 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                PHASE 06
            </a>
            <button type="submit" class="luxury-button flex items-center gap-4">
                Commit Succession
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let beneficiaryCount = {{ count($beneficiaries) }};

function addBeneficiary() {
    const container = document.getElementById('beneficiaries-container');
    const newItem = document.createElement('div');
    newItem.className = 'beneficiary-item relative bg-gray-50/50 rounded-[3rem] p-10 border border-gray-100 group transition-all duration-500 hover:bg-white hover:shadow-2xl hover:shadow-gray-900/5 animate-in slide-in-from-bottom duration-500';
    newItem.innerHTML = `
        <div class="flex justify-between items-center mb-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#015425] shadow-sm font-black text-lg">
                    ${beneficiaryCount + 1}
                </div>
                <h3 class="text-xl font-black text-gray-900">Beneficiary Identity Node</h3>
            </div>
            <button type="button" onclick="removeBeneficiary(this)" class="px-5 py-2 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                Terminate Entry
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="space-y-4">
                <label class="premium-label">Full Identity Name</label>
                <input type="text" name="beneficiaries[${beneficiaryCount}][name]" class="premium-input bg-white/70" placeholder="Beneficiary Legal Name">
            </div>
            <div class="space-y-4">
                <label class="premium-label">Social Relationship Vector</label>
                <input type="text" name="beneficiaries[${beneficiaryCount}][relationship]" class="premium-input bg-white/70" placeholder="e.g., Blood Relation, Spouse, Ward">
            </div>
            <div class="space-y-4">
                <label class="premium-label">Asset Allocation (%)</label>
                <div class="relative">
                    <input type="number" name="beneficiaries[${beneficiaryCount}][allocation]" min="0" max="100" step="0.01" class="premium-input bg-white/70 pr-12" placeholder="0.00">
                    <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-black text-gray-300">%</span>
                </div>
            </div>
            <div class="space-y-4">
                <label class="premium-label">Contact Link (Mobile / Encrypted)</label>
                <input type="text" name="beneficiaries[${beneficiaryCount}][contact]" class="premium-input bg-white/70" placeholder="Phone or digital identifier">
            </div>
        </div>
    `;
    container.appendChild(newItem);
    beneficiaryCount++;
}

function removeBeneficiary(button) {
    const item = button.closest('.beneficiary-item');
    item.classList.add('animate-out', 'fade-out', 'zoom-out', 'duration-300');
    setTimeout(() => item.remove(), 300);
}
</script>
@endpush
@endsection
