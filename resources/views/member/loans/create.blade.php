@extends('layouts.member')

@section('page-title', 'Apply for Community Capital')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 sm:p-8 text-white">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Apply for Community Capital</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Submit your loan application, add endorsements, and upload supporting documents.</p>
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Standard interest: 10.0% p.a</span>
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
                <a href="{{ route('member.loans.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white/10 text-white rounded-md border border-white/20 hover:bg-white/20 transition font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Loans
                </a>
                <button type="button" onclick="document.getElementById('terms_accepted')?.scrollIntoView({behavior: 'smooth', block: 'center'});" class="inline-flex items-center justify-center px-4 py-2 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium">
                    Review & Submit
                </button>
            </div>
        </div>
    </div>

    <form action="{{ route('member.loans.store') }}" method="POST" id="loan-form" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Application Section (8 columns) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Section: Financial Requirements -->
                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-[#015425]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Capital Requirements</h2>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Define your funding goals</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Loan Classification</label>
                            <select name="loan_type" id="loan_type" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-[#015425] transition-all">
                                <option value="">Select Category</option>
                                <option value="Personal">Personal Loan</option>
                                <option value="Business">Business Loan</option>
                                <option value="Agricultural">Agricultural Loan</option>
                                <option value="Education">Education Loan</option>
                                <option value="Emergency">Emergency Loan</option>
                                <option value="Asset Financing">Asset Financing</option>
                                <option value="Home Improvement">Home Improvement</option>
                            </select>
                             @error('loan_type')<p class="text-red-500 text-[10px] font-bold mt-2 ml-1">{{ $message }}@enderror</p>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Requested Principal (TZS)</label>
                            <div class="relative">
                                <input type="number" name="principal_amount" id="principal_amount" step="0.01" min="1000" required 
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-[#015425] transition-all"
                                    placeholder="e.g. 5,000,000">
                                <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-300">CURRENCY</span>
                            </div>
                             @error('principal_amount')<p class="text-red-500 text-[10px] font-bold mt-2 ml-1">{{ $message }}@enderror</p>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Interest Rate (%)</label>
                            <input type="number" name="interest_rate" id="interest_rate" step="0.01" min="0" max="100" value="10"
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-[#015425] transition-all">
                             @error('interest_rate')<p class="text-red-500 text-[10px] font-bold mt-2 ml-1">{{ $message }}@enderror</p>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Repayment Term (Months)</label>
                            <input type="number" name="term_months" id="term_months" min="1" max="120" required 
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-[#015425] transition-all"
                                placeholder="e.g. 12">
                             @error('term_months')<p class="text-red-500 text-[10px] font-bold mt-2 ml-1">{{ $message }}@enderror</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Purpose & Analysis -->
                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 sm:p-8">
                     <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Purpose & Analysis</h2>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Help us understand the impact</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Strategic Use of Funds</label>
                            <select name="loan_purpose_category" id="loan_purpose_category" 
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-blue-600 transition-all mb-4">
                                <option value="">Select Primary Intent</option>
                                <option value="Business Expansion">Business Expansion</option>
                                <option value="Agricultural Investment">Agricultural Investment</option>
                                <option value="Education">Education</option>
                                <option value="Emergency">Emergency Relief</option>
                                <option value="Asset Financing">Asset Acquisition</option>
                                <option value="Home Improvement">Real Estate Improvement</option>
                            </select>
                            <textarea name="purpose" id="purpose" rows="4" required
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-3xl text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-600 transition-all"
                                placeholder="Provide a detailed operational breakdown of how this capital will be deployed..."></textarea>
                             @error('purpose')<p class="text-red-500 text-[10px] font-bold mt-2 ml-1">{{ $message }}@enderror</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                             <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Repayment Methodology</label>
                                <textarea name="repayment_source" id="repayment_source" rows="3"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-3xl text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-600 transition-all"
                                    placeholder="Define the income streams for repayment..."></textarea>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Exposure / Collateral</label>
                                <textarea name="collateral_description" id="collateral_description" rows="3"
                                    class="w-full px-5 py-4 bg-gray-50 border-none rounded-3xl text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-600 transition-all"
                                    placeholder="Describe physical or financial assets offered..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Endorsements (Guarantor) -->
                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 sm:p-8">
                     <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Community Endorsement</h2>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Trust-based verification</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Select Sponsoring Member</label>
                        <select name="guarantor_id" id="guarantor_id" 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-purple-600 transition-all">
                            <option value="">Search Community Members...</option>
                            @foreach($members ?? [] as $member)
                                <option value="{{ $member->id }}" 
                                    data-code="{{ $member->membership_code }}"
                                    data-email="{{ $member->email }}"
                                    data-phone="{{ $member->phone ?? 'N/A' }}">
                                    {{ $member->membership_code }} — {{ $member->name }}
                                </option>
                            @endforeach
                        </select>

                        <div id="guarantor-details" class="hidden bg-purple-50/50 rounded-3xl p-6 border border-purple-100">
                             <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div>
                                    <p class="text-[9px] font-bold text-purple-400 uppercase tracking-widest mb-1">Code</p>
                                    <p class="text-sm font-black text-purple-900" id="guarantor-code">-</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-purple-400 uppercase tracking-widest mb-1">Contact</p>
                                    <p class="text-sm font-black text-purple-900" id="guarantor-email">-</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-purple-400 uppercase tracking-widest mb-1">Verification</p>
                                    <p class="text-sm font-black text-green-600">Qualified</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Evidence Uploads -->
                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 sm:p-8">
                     <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Documentary Evidence</h2>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Verification & Compliance</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-6 rounded-3xl border-2 border-dashed border-gray-200 hover:border-red-400 transition-all group relative">
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-4 tracking-widest">Master Application</p>
                                <input id="application_document" name="application_document" type="file" class="absolute inset-0 opacity-0 cursor-pointer z-10" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="text-center group-hover:-translate-y-1 transition-transform">
                                    <p class="text-xs font-bold text-gray-600" id="application_document_name">Click or drag to upload</p>
                                    <p class="text-[9px] text-gray-400 mt-1">PDF, DOC, JPG (MAX 10MB)</p>
                                </div>
                            </div>

                             <div class="space-y-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Identity Verification</label>
                                <input type="file" name="id_document" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200">
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Proof of Solvency</label>
                                <input type="file" name="proof_of_income" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200">
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Exposure Appraisal</label>
                                <input type="file" name="collateral_document" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200">
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Supporting Artifacts</label>
                                <input type="file" name="supporting_documents[]" multiple class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Strategy (4 columns) -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- Financial Simulation -->
                <div class="bg-gray-900 rounded-lg p-6 sm:p-8 text-white shadow-2xl sticky top-8 border border-white/10">
                    <h3 class="text-sm font-black uppercase tracking-widest mb-10 text-gray-400">Yield Simulation</h3>
                    
                    <div class="space-y-8">
                        <div>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Total Exposure</p>
                            <p class="text-4xl font-black text-white" id="calc-total-amount">0.00</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-6 pt-8 border-t border-white/10">
                            <div>
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Net Interest</p>
                                <p class="text-base font-black text-red-500" id="calc-total-interest">0.00</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Instalment</p>
                                <p class="text-base font-black text-green-500" id="calc-monthly-payment">0.00</p>
                            </div>
                        </div>
                        
                         <div class="bg-white/5 rounded-3xl p-6 border border-white/5">
                            <p class="text-xs text-gray-400 leading-relaxed italic">"Simple interest calculation applied. Terms subject to final community credit committee approval."</p>
                        </div>

                        <div class="pt-4">
                            <div class="flex items-center gap-3 mb-6">
                                <input type="checkbox" id="terms_accepted" name="terms_accepted" required 
                                    class="h-5 w-5 bg-white/10 border-none rounded text-green-600 focus:ring-0">
                                <label for="terms_accepted" class="text-[10px] font-bold text-gray-400 uppercase leading-normal">
                                    I attest to the veracity of all provided metrics & evidence.
                                </label>
                            </div>

                            <button type="submit" class="w-full py-5 bg-[#015425] hover:bg-[#027a3a] text-white rounded-2xl font-black text-sm shadow-xl transition-all duration-300 hover:-translate-y-1">
                                SUBMIT APPLICATION
                            </button>
                            <a href="{{ route('member.loans.index') }}" class="block w-full text-center py-4 text-xs font-bold text-gray-500 hover:text-white mt-2 transition-colors">
                                Discard & Exit
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Guidance Card -->
                <div class="bg-indigo-600 rounded-lg p-6 sm:p-8 text-white shadow-xl relative overflow-hidden group">
                     <div class="absolute -right-10 -top-10 w-32 h-32 bg-white opacity-10 rounded-full group-hover:scale-110 transition-transform"></div>
                     <h3 class="text-lg font-black mb-4">Capital Speed</h3>
                     <p class="text-xs text-indigo-100 leading-relaxed mb-6">Complete applications with high-quality evidence are prioritized. Ensure your guarantor is notified to expedite the trust-check.</p>
                     <div class="flex items-center gap-2 text-[10px] font-black text-white/80">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                         AVG PROCESSING: 48-72 HRS
                     </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const principalInput = document.getElementById('principal_amount');
    const interestInput = document.getElementById('interest_rate');
    const termInput = document.getElementById('term_months');
    const loanForm = document.getElementById('loan-form');

    // Guarantor selection handler
    const guarantorSelect = document.getElementById('guarantor_id');
    const guarantorDetails = document.getElementById('guarantor-details');
    const guarantorCode = document.getElementById('guarantor-code');
    const guarantorEmail = document.getElementById('guarantor-email');

    if (guarantorSelect) {
        guarantorSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt.value) {
                guarantorCode.textContent = opt.dataset.code || '-';
                guarantorEmail.textContent = opt.dataset.email || '-';
                guarantorDetails.classList.remove('hidden');
            } else {
                guarantorDetails.classList.add('hidden');
            }
        });
    }

    // Advanced Simulator
    function simulate() {
        const principal = parseFloat(principalInput.value) || 0;
        const rate = parseFloat(interestInput.value) || 0;
        const term = parseInt(termInput.value) || 0;

        if (principal > 0 && rate > 0 && term > 0) {
            const interest = (principal * rate / 100) * (term / 12);
            const total = principal + interest;
            const monthly = total / term;

            animateValue('calc-total-amount', total);
            animateValue('calc-total-interest', interest);
            animateValue('calc-monthly-payment', monthly);
        } else {
            document.getElementById('calc-total-amount').textContent = '0.00';
            document.getElementById('calc-total-interest').textContent = '0.00';
            document.getElementById('calc-monthly-payment').textContent = '0.00';
        }
    }

    function animateValue(id, value) {
        const el = document.getElementById(id);
        el.textContent = new Intl.NumberFormat('en-US', { minimumFractionDigits: 0 }).format(value);
    }

    principalInput.addEventListener('input', simulate);
    interestInput.addEventListener('input', simulate);
    termInput.addEventListener('input', simulate);

    // Initial sync
    simulate();

    // File name sync
    document.getElementById('application_document').addEventListener('change', function(e) {
        if(e.target.files.length) document.getElementById('application_document_name').textContent = e.target.files[0].name;
    });
});
</script>
@endpush
@endsection
