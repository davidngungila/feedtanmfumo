@extends('layouts.member')

@section('page-title', 'Initiate Growth Plan')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 sm:p-8 text-white">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Start Investment</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Configure your plan, simulate projected returns, and submit your investment request.</p>
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Standard ROI: 12.0% p.a</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ now()->format('l, F d, Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="{{ route('member.investments.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white/10 text-white rounded-md border border-white/20 hover:bg-white/20 transition font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Investments
                </a>
                <button type="button" onclick="document.getElementById('investment-form')?.scrollIntoView({behavior: 'smooth', block: 'start'});" class="inline-flex items-center justify-center px-4 py-2 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium">
                    Review & Submit
                </button>
            </div>
        </div>
    </div>

    <form action="{{ route('member.investments.store') }}" method="POST" id="investment-form">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content (8 columns) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Section: Plan Selection -->
                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 sm:p-8">
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
                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 sm:p-8">
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

                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Additional Details</h2>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Optional profile information</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Risk Profile</label>
                            <select name="risk_profile" class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-indigo-600 transition-all">
                                <option value="">Select risk preference</option>
                                <option value="conservative">Conservative</option>
                                <option value="balanced">Balanced</option>
                                <option value="growth">Growth</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Contribution Frequency</label>
                            <select name="contribution_frequency" class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-indigo-600 transition-all">
                                <option value="">One-time (default)</option>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Investment Goal</label>
                            <input type="text" name="investment_goal" class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-indigo-600 transition-all" placeholder="e.g. Education fund, Business expansion, Retirement">
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Beneficiary Name</label>
                            <input type="text" name="beneficiary_name" class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-indigo-600 transition-all" placeholder="Optional">
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Beneficiary Relationship</label>
                            <input type="text" name="beneficiary_relationship" class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-indigo-600 transition-all" placeholder="e.g. Spouse, Child, Parent">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Notes</label>
                            <textarea name="notes" rows="4" class="w-full px-6 py-5 bg-gray-50 border-none rounded-3xl text-sm font-medium text-gray-700 focus:ring-2 focus:ring-indigo-600 transition-all" placeholder="Optional comments for the investment committee..."></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="inline-flex items-center gap-3">
                                <input type="checkbox" name="auto_renew" value="1" class="h-5 w-5 bg-gray-50 border-gray-200 rounded text-[#015425] focus:ring-[#015425]">
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-widest">Auto-renew at maturity (if eligible)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Strategy (4 columns) -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- Performance Simulation -->
                <div class="bg-gray-900 rounded-lg p-6 sm:p-8 text-white shadow-2xl sticky top-8 border border-white/10">
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
                 <div class="bg-indigo-600 rounded-lg p-6 sm:p-8 text-white shadow-xl group">
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
