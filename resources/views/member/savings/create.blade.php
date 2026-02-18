@extends('layouts.member')

@section('page-title', 'Establish Savings Vault')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Premium Header -->
    <div class="bg-gradient-to-br from-[#015425] via-[#027a3a] to-[#013019] rounded-[2.5rem] shadow-2xl p-10 sm:p-14 text-white relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-center gap-10">
            <div class="text-center lg:text-left">
                <h1 class="text-4xl sm:text-6xl font-black mb-6 tracking-tight">Financial Fortress</h1>
                <p class="text-green-50 text-lg sm:text-xl opacity-80 max-w-2xl leading-relaxed font-medium">Engineer your financial future. Deploy a specialized savings vehicle tailored to your liquidity requirements and growth objectives.</p>
            </div>
            <div class="mt-4 md:mt-0 lg:ml-auto">
                 <a href="{{ route('member.savings.index') }}" class="px-10 py-4 bg-white text-[#015425] rounded-2xl font-black text-xs shadow-xl hover:-translate-y-1 transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Account Ledger
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 sm:p-12 border-b border-gray-50 bg-gray-50/30 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-gray-900">Vault Configuration</h2>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Define your liquidity structure</p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                 <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                 <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Awaiting Parameter Entry</span>
            </div>
        </div>
        
        <form action="{{ route('member.savings.store') }}" method="POST" class="p-8 sm:p-12 space-y-12">
            @csrf
            
            <!-- Section: Protocol Selection -->
            <div class="space-y-8">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block ml-1">Asset Class Allocation</label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Emergency -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="account_type" value="emergency" class="peer sr-only" required>
                        <div class="h-full p-6 bg-gray-50 rounded-[2rem] border-2 border-transparent peer-checked:border-red-500 peer-checked:bg-red-50/30 transition-all group-hover:bg-gray-100">
                            <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-red-600 mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.268 17c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h3 class="text-sm font-black text-gray-900 mb-2">Emergency Hub</h3>
                            <p class="text-[10px] text-gray-500 font-medium leading-relaxed">Immediate deployment for critical contingencies.</p>
                        </div>
                    </label>

                    <!-- Flex -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="account_type" value="flex" class="peer sr-only">
                        <div class="h-full p-6 bg-gray-50 rounded-[2rem] border-2 border-transparent peer-checked:border-blue-500 peer-checked:bg-blue-50/30 transition-all group-hover:bg-gray-100">
                            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <h3 class="text-sm font-black text-gray-900 mb-2">Liquid Flex</h3>
                            <p class="text-[10px] text-gray-500 font-medium leading-relaxed">High-velocity capital with standard yields.</p>
                        </div>
                    </label>

                    <!-- RDA -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="account_type" value="rda" class="peer sr-only">
                        <div class="h-full p-6 bg-gray-50 rounded-[2rem] border-2 border-transparent peer-checked:border-purple-600 peer-checked:bg-purple-50/30 transition-all group-hover:bg-gray-100">
                            <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-sm font-black text-gray-900 mb-2">RDA Protocol</h3>
                            <p class="text-[10px] text-gray-500 font-medium leading-relaxed">Structured maturity for maximized growth.</p>
                        </div>
                    </label>

                    <!-- Business -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="account_type" value="business" class="peer sr-only">
                        <div class="h-full p-6 bg-gray-50 rounded-[2rem] border-2 border-transparent peer-checked:border-green-600 peer-checked:bg-green-50/30 transition-all group-hover:bg-gray-100">
                            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <h3 class="text-sm font-black text-gray-900 mb-2">Capital Reserve</h3>
                            <p class="text-[10px] text-gray-500 font-medium leading-relaxed">Segregated funding for professional expansion.</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Section: Temporal Parameters -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block ml-1">Effective Activation Date</label>
                    <input type="date" name="opening_date" id="opening_date" value="{{ date('Y-m-d') }}" required
                        class="w-full px-8 py-5 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-[#015425] transition-all">
                    @error('opening_date')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
                </div>

                <div id="maturity_date_container" class="space-y-4 hidden opacity-0 translate-y-4 transition-all duration-500">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block ml-1">Projected Maturity Date (RDA)</label>
                    <input type="date" name="maturity_date" id="maturity_date" value="{{ date('Y-m-d', strtotime('+1 year')) }}"
                        class="w-full px-8 py-5 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-purple-600 transition-all shadow-inner">
                    <p class="text-[9px] text-gray-400 font-bold italic ml-2">* Minimum 12-month lock-in period recommended.</p>
                    @error('maturity_date')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="pt-10 border-t border-gray-50 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="max-w-md text-center md:text-left">
                     <p class="text-sm font-bold text-gray-900 mb-1">Authorization Protocol</p>
                     <p class="text-xs text-gray-400 font-medium leading-relaxed">By initializing this vault, you bind your digital identity to the community savings governance framework.</p>
                </div>
                
                <button type="submit" class="w-full md:w-auto px-16 py-6 bg-[#015425] text-white rounded-[1.5rem] font-black text-sm shadow-2xl hover:bg-[#013019] hover:-translate-y-1 transition-all">
                    INITIALIZE VAULT
                </button>
            </div>
        </form>
    </div>

    <!-- Tier 3: Support & Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-gray-900 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
             <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full group-hover:scale-125 transition-transform duration-1000"></div>
             <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-8 border-b border-white/10 pb-4">Liquidity Intelligence</h3>
             <ul class="space-y-4">
                 <li class="flex items-start gap-4 text-xs">
                     <span class="w-1.5 h-1.5 rounded-full bg-green-500 mt-1.5 shrink-0"></span>
                     <p class="text-gray-300 font-medium">Standard accounts provide instant withdrawal capabilities within community operating hours.</p>
                 </li>
                 <li class="flex items-start gap-4 text-xs">
                     <span class="w-1.5 h-1.5 rounded-full bg-purple-500 mt-1.5 shrink-0"></span>
                     <p class="text-gray-300 font-medium">RDA protocols offer enhanced interest rates in exchange for temporal lock-in mandates.</p>
                 </li>
             </ul>
        </div>

        <div class="bg-indigo-600 rounded-[2.5rem] p-10 text-white shadow-xl group cursor-pointer hover:bg-indigo-700 transition-colors">
            <h3 class="text-2xl font-black mb-4 tracking-tight">Need Strategy Assistance?</h3>
            <p class="text-sm text-indigo-100 leading-relaxed mb-8 opacity-80 font-medium">Our treasury officers can help you optimize your savings structure for maximum yield and security.</p>
            <a href="{{ route('member.issues.create') }}" class="inline-flex items-center gap-3 text-[10px] font-black uppercase tracking-widest bg-white/10 px-6 py-3 rounded-xl hover:bg-white/20 transition-all">
                CONSULT TREASURY PORTAL
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeRadios = document.querySelectorAll('input[name="account_type"]');
        const maturityContainer = document.getElementById('maturity_date_container');
        const maturityInput = document.getElementById('maturity_date');

        typeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'rda') {
                    maturityContainer.classList.remove('hidden');
                    setTimeout(() => {
                        maturityContainer.classList.add('opacity-100', 'translate-y-0');
                        maturityContainer.classList.remove('translate-y-4');
                    }, 10);
                    maturityInput.required = true;
                } else {
                    maturityContainer.classList.add('opacity-0', 'translate-y-4');
                    maturityContainer.classList.remove('opacity-100', 'translate-y-0');
                    setTimeout(() => maturityContainer.classList.add('hidden'), 500);
                    maturityInput.required = false;
                }
            });
        });
        
        // Initial state check
        const selected = document.querySelector('input[name="account_type"]:checked');
        if (selected && selected.value === 'rda') {
            maturityContainer.classList.remove('hidden');
            maturityContainer.classList.add('opacity-100', 'translate-y-0');
        }
    });
</script>
@endpush
@endsection
