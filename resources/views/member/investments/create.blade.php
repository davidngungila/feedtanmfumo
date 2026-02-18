@extends('layouts.member')

@section('page-title', 'Initiate Growth Plan')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Premium Header -->
    <div class="bg-gradient-to-br from-[#015425] via-[#027a3a] to-[#013019] rounded-[2.5rem] shadow-2xl p-10 sm:p-14 text-white relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-center gap-10">
            <div class="text-center lg:text-left">
                <h1 class="text-4xl sm:text-6xl font-black mb-6 tracking-tight">Growth Catalyst</h1>
                <p class="text-green-50 text-lg sm:text-xl opacity-80 max-w-2xl leading-relaxed font-medium">Deploy your capital into high-yield community assets. Start a structured investment plan designed for long-term prosperity.</p>
            </div>
            <div class="flex flex-col gap-4">
                 <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 text-center">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-green-200 mb-1">Standard ROI</p>
                    <p class="text-4xl font-black">12.0% <span class="text-xs font-normal opacity-60">p.a</span></p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('member.investments.store') }}" method="POST" id="investment-form">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content (8 columns) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Section: Plan Selection -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-12">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Capital Mandate</h2>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Select your maturity horizon</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="plan_type" value="4_year" class="peer sr-only" required checked>
                            <div class="p-8 bg-gray-50 rounded-[2rem] border-2 border-transparent peer-checked:border-[#015425] peer-checked:bg-green-50/50 transition-all group-hover:bg-gray-100">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-black text-gray-900 group-hover:text-[#015425] transition-colors">4 Year Horizon</h3>
                                    <div class="w-2 h-2 rounded-full bg-gray-300 peer-checked:bg-[#015425]"></div>
                                </div>
                                <p class="text-xs text-gray-500 font-medium leading-relaxed mb-6">Optimized for medium-term capital deployment with consistent annual yields.</p>
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200/50">
                                    <span class="text-[10px] font-black uppercase text-gray-400">Target ROI</span>
                                    <span class="text-sm font-black text-[#015425]">12% Year</span>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer group">
                            <input type="radio" name="plan_type" value="6_year" class="peer sr-only">
                            <div class="p-8 bg-gray-50 rounded-[2rem] border-2 border-transparent peer-checked:border-purple-600 peer-checked:bg-purple-50/50 transition-all group-hover:bg-gray-100">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-black text-gray-900 group-hover:text-purple-600 transition-colors">6 Year Horizon</h3>
                                    <div class="w-2 h-2 rounded-full bg-gray-300 peer-checked:bg-purple-600"></div>
                                </div>
                                <p class="text-xs text-gray-500 font-medium leading-relaxed mb-6">Maximum return strategy for generational wealth building and long-term security.</p>
                                <div class="flex items-center justify-between pt-4 border-t border-purple-200/50">
                                    <span class="text-[10px] font-black uppercase text-gray-400">Target ROI</span>
                                    <span class="text-sm font-black text-purple-600">12% Year</span>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('plan_type')<p class="text-red-500 text-[10px] font-bold mt-4 ml-2">{{ $message }}</p>@enderror
                </div>

                <!-- Section: Financial Inputs -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-12">
                     <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-[#015425]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Deployment Logistics</h2>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Define your initial contribution</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Principal Amount (TZS)</label>
                            <div class="relative">
                                <input type="number" name="principal_amount" id="principal_amount" step="0.01" min="1000" required 
                                    class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl text-xl font-black text-gray-900 focus:ring-2 focus:ring-[#015425] transition-all"
                                    placeholder="0.00">
                                <span class="absolute right-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-300">CURRENCY</span>
                            </div>
                            @error('principal_amount')<p class="text-red-500 text-[10px] font-bold mt-2 ml-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Effective Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-d') }}" required 
                                class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-[#015425] transition-all">
                            @error('start_date')<p class="text-red-500 text-[10px] font-bold mt-2 ml-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mt-10 p-6 bg-blue-50/50 rounded-3xl border border-blue-100 flex items-start gap-4">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-xs text-blue-800 leading-relaxed font-medium">
                            <strong>Yield Protocol:</strong> Interest is calculated annually on the principal amount and compounded upon maturity. Early liquidation may be subject to community adjustment fees.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Strategy (4 columns) -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- Performance Simulation -->
                <div class="bg-gray-900 rounded-[2.5rem] p-10 text-white shadow-2xl sticky top-8 border border-white/10">
                    <h3 class="text-sm font-black uppercase tracking-widest mb-10 text-gray-400">Yield Simulation</h3>
                    
                    <div class="space-y-8">
                        <div>
                             <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Selected Horizon</p>
                             <p class="text-xl font-black text-white" id="summary-plan">4 Year Horizon</p>
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Net Principal</p>
                            <p class="text-2xl font-black text-white" id="summary-principal">0.00 TZS</p>
                        </div>
                        
                        <div class="pt-8 border-t border-white/10">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Projected Interest</p>
                            <p class="text-2xl font-black text-green-400" id="summary-interest">0.00 TZS</p>
                        </div>

                        <div class="pt-8 border-t border-white/10">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Maturity Valuation</p>
                            <p class="text-4xl font-black text-white" id="summary-total">0.00 TZS</p>
                        </div>
                        
                         <div class="bg-white/5 rounded-3xl p-6 border border-white/5">
                            <p class="text-xs text-gray-400 leading-relaxed italic">"Simulated values based on fixed 12.0% p.a interest. Official certification will be provided upon deployment."</p>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-5 bg-[#015425] hover:bg-[#027a3a] text-white rounded-2xl font-black text-sm shadow-xl transition-all duration-300 hover:-translate-y-1">
                                COMMIT CAPITAL
                            </button>
                            <a href="{{ route('member.investments.index') }}" class="block w-full text-center py-4 text-xs font-bold text-gray-500 hover:text-white mt-2 transition-colors">
                                Discard Strategy
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Guidance -->
                 <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl group">
                     <h3 class="text-lg font-black mb-4">Capital Security</h3>
                     <p class="text-xs text-indigo-100 leading-relaxed mb-6">Investments are backed by community assets and diverse project portfolios. Your growth is protected by conservative deployment strategies.</p>
                     <div class="flex items-center gap-2 text-[10px] font-black text-white/80">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                         SECURED ASSET CLASS
                     </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const planRadios = document.querySelectorAll('input[name="plan_type"]');
        const amountInput = document.getElementById('principal_amount');
        const summaryPlan = document.getElementById('summary-plan');
        const summaryPrincipal = document.getElementById('summary-principal');
        const summaryInterest = document.getElementById('summary-interest');
        const summaryTotal = document.getElementById('summary-total');

        function updateSummary() {
            const selectedPlan = document.querySelector('input[name="plan_type"]:checked');
            const amount = parseFloat(amountInput.value) || 0;
            
            if (selectedPlan) {
                const planValue = selectedPlan.value;
                const years = planValue === '4_year' ? 4 : 6;
                const planName = planValue === '4_year' ? '4 Year Horizon' : '6 Year Horizon';
                
                const interestRate = 0.12;
                const interest = amount * interestRate * years;
                const total = amount + interest;

                summaryPlan.textContent = planName;
                summaryPrincipal.textContent = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(amount) + ' TZS';
                summaryInterest.textContent = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(interest) + ' TZS';
                summaryTotal.textContent = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(total) + ' TZS';
            }
        }

        planRadios.forEach(radio => radio.addEventListener('change', updateSummary));
        amountInput.addEventListener('input', updateSummary);
        
        // Initial simulation
        updateSummary();
    });
</script>
@endpush
@endsection
