@extends('layouts.member')

@section('page-title', 'Report Social Transaction')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Premium Header -->
    <div class="bg-gradient-to-br from-[#015425] via-[#027a3a] to-[#013019] rounded-[2.5rem] shadow-2xl p-10 sm:p-14 text-white relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <h1 class="text-4xl sm:text-5xl font-black mb-6 tracking-tight">Social Entry</h1>
            <p class="text-green-50 text-lg sm:text-xl opacity-80 max-w-xl leading-relaxed font-medium">Record your community contributions or report benefits received to keep your social ledger accurate.</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-12 overflow-hidden">
        <form action="{{ route('member.welfare.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Left: Category & Identity -->
                <div class="space-y-8">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Transaction Flow</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="contribution" checked id="type_contribution" class="peer sr-only">
                                <div class="p-6 bg-gray-50 rounded-3xl border-2 border-transparent peer-checked:border-[#015425] peer-checked:bg-green-50/50 transition-all text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 peer-checked:text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-600 peer-checked:text-[#015425]">Contribution</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="benefit" id="type_benefit" class="peer sr-only">
                                <div class="p-6 bg-gray-50 rounded-3xl border-2 border-transparent peer-checked:border-blue-600 peer-checked:bg-blue-50/50 transition-all text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-600 peer-checked:text-blue-600">Benefit</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="benefit_type_field" class="hidden animate-in slide-in-from-top duration-500">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Benefit Classification</label>
                        <select name="benefit_type" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-blue-600 transition-all">
                            <option value="medical">Medical Support</option>
                            <option value="funeral">Funeral Support</option>
                            <option value="educational">Educational Support</option>
                            <option value="other">Other Support</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Asset Volume (TZS)</label>
                        <div class="relative">
                            <input type="number" name="amount" step="0.01" min="0" required 
                                class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl text-xl font-black text-gray-900 focus:ring-2 focus:ring-[#015425] transition-all"
                                placeholder="0.00">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-300">CURRENCY</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Chronology & Documentation -->
                <div class="space-y-8">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Effective Date</label>
                        <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required 
                            class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl text-sm font-black text-gray-900 focus:ring-2 focus:ring-[#015425] transition-all">
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Narrative Description</label>
                        <textarea name="description" rows="5" 
                            class="w-full px-6 py-5 bg-gray-50 border-none rounded-3xl text-sm font-medium text-gray-700 focus:ring-2 focus:ring-[#015425] transition-all"
                            placeholder="Provide context for this transaction (e.g., medical bill receipt #, bereavement notice date, etc.)..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Security & Submission -->
            <div class="mt-12 pt-10 border-t border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-4 text-xs text-gray-400 font-bold max-w-sm">
                    <svg class="w-6 h-6 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    By submitting this record, you affirm it corresponds to a valid community transaction that may be audited.
                </div>
                <div class="flex gap-4 w-full sm:w-auto">
                    <a href="{{ route('member.welfare.index') }}" class="flex-1 sm:flex-none px-10 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-xs hover:bg-gray-200 transition-all text-center">
                        Discard
                    </a>
                    <button type="submit" class="flex-1 sm:flex-none px-12 py-4 bg-[#015425] text-white rounded-2xl font-black text-xs shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all">
                        Commit Record
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const benefitField = document.getElementById('benefit_type_field');
            if (this.value === 'benefit') {
                benefitField.classList.remove('hidden');
                benefitField.querySelector('select').required = true;
            } else {
                benefitField.classList.add('hidden');
                benefitField.querySelector('select').required = false;
            }
        });
    });
</script>
@endpush
@endsection
