@extends('layouts.member')

@section('page-title', 'Fungua Akaunti ya Akiba')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-[#1a4a35] to-[#015425] px-6 py-8 sm:px-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">Fungua Akaunti Mpya</h2>
            <p class="text-green-50 text-sm sm:text-base">Chagua aina ya akaunti ya akiba kulingana na malengo yako ya kifedha.</p>
        </div>
        
        <form action="{{ route('member.savings.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Account types -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Chagua Aina ya Akaunti *</label>
                        <div class="grid grid-cols-1 gap-3">
                            <label class="relative flex p-4 cursor-pointer rounded-xl border-2 transition focus:outline-none hover:bg-green-50 border-gray-200" id="label-business">
                                <input type="radio" name="account_type" value="business" class="sr-only" required checked onclick="updateSavingsInfo('business')">
                                <div class="flex flex-col">
                                    <span class="block text-sm font-bold text-[#015425] uppercase">Business Saving</span>
                                    <span class="block mt-1 text-xs text-gray-500">Interest: 2.96% Annual</span>
                                </div>
                            </label>

                            <label class="relative flex p-4 cursor-pointer rounded-xl border-2 transition focus:outline-none hover:bg-green-50 border-gray-200" id="label-flex">
                                <input type="radio" name="account_type" value="flex" class="sr-only" onclick="updateSavingsInfo('flex')">
                                <div class="flex flex-col">
                                    <span class="block text-sm font-bold text-[#015425] uppercase">FeedTan Flexi</span>
                                    <span class="block mt-1 text-xs text-gray-500">Interest: 5.37% Annual</span>
                                </div>
                            </label>

                            <label class="relative flex p-4 cursor-pointer rounded-xl border-2 transition focus:outline-none hover:bg-green-50 border-gray-200" id="label-emergency">
                                <input type="radio" name="account_type" value="emergency" class="sr-only" onclick="updateSavingsInfo('emergency')">
                                <div class="flex flex-col">
                                    <span class="block text-sm font-bold text-[#015425] uppercase">Emergence Fund</span>
                                    <span class="block mt-1 text-xs text-gray-500">Interest: 0% (Liquid)</span>
                                </div>
                            </label>

                            <label class="relative flex p-4 cursor-pointer rounded-xl border-2 transition focus:outline-none hover:bg-green-50 border-gray-200" id="label-rda">
                                <input type="radio" name="account_type" value="rda" class="sr-only" onclick="updateSavingsInfo('rda')">
                                <div class="flex flex-col">
                                    <span class="block text-sm font-bold text-[#015425] uppercase">Recurrent Deposit Account (RDA)</span>
                                    <span class="block mt-1 text-xs text-gray-500">Interest: Hadi 6.78% (Inategemea Kiasi)</span>
                                </div>
                            </label>
                        </div>
                        @error('account_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div id="rda-details" class="hidden animate-fade-in">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <h4 class="text-xs font-bold text-blue-800 uppercase mb-2">Viwango vya RDA:</h4>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li>• > 299,000 TZS: <strong>6.78%</strong></li>
                                <li>• 200,000 - 299,000 TZS: <strong>6.30%</strong></li>
                                <li>• 100,000 - 199,000 TZS: <strong>5.84%</strong></li>
                                <li>• < 100,000 TZS: <strong>0%</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Input fields -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Amana ya Kwanza (TZS) *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-medium">TZS</span>
                            </div>
                            <input type="number" name="initial_deposit" id="initial_deposit" step="1000" min="0" required 
                                class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-[#015425] focus:border-[#015425] shadow-sm transition" 
                                placeholder="Mfano: 100,000" oninput="calculateSavingsRate()">
                        </div>
                        @error('initial_deposit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tarehe ya Kufungua *</label>
                            <input type="date" name="opening_date" value="{{ date('Y-m-d') }}" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-[#015425] focus:border-[#015425] shadow-sm text-sm">
                        </div>
                        <div id="maturity_field" class="hidden">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tarehe ya Kukoma *</label>
                            <input type="date" name="maturity_date" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-[#015425] focus:border-[#015425] shadow-sm text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Riba Inayotumika:</label>
                        <div class="bg-gray-100 rounded-xl p-4 flex justify-between items-center">
                            <span class="text-sm text-gray-600" id="rate-label">Standard Rate</span>
                            <span class="text-lg font-bold text-[#015425]" id="current-rate">2.96%</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ambatisha Risiti ya Amana *</label>
                        <div class="mt-1 flex justify-center px-6 pt-4 pb-4 border-2 border-gray-300 border-dashed rounded-xl hover:border-[#015425] transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-xs text-gray-600">
                                    <label for="payment_receipt" class="relative cursor-pointer font-medium text-[#015425] hover:text-[#013019]">
                                        <span>Pakia Risiti</span>
                                        <input id="payment_receipt" name="payment_receipt" type="file" required class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                    </label>
                                </div>
                                <p class="text-[10px] text-gray-500">PDF, JPG up to 5MB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex space-x-4 border-t border-gray-100 pt-8">
                <a href="{{ route('member.savings.index') }}" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition text-center">
                    Ghairi
                </a>
                <button type="submit" class="flex-1 px-6 py-3 bg-[#015425] text-white font-bold rounded-xl hover:bg-[#013019] shadow-lg transition">
                    Fungua Akaunti
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let activeType = 'business';

    function updateSavingsInfo(type) {
        activeType = type;
        
        // Toggle RDA details
        document.getElementById('rda-details').classList.toggle('hidden', type !== 'rda');
        document.getElementById('maturity_field').classList.toggle('hidden', type !== 'rda');
        
        // Update labels
        document.querySelectorAll('[id^="label-"]').forEach(el => {
            el.classList.remove('border-[#015425]', 'bg-green-50');
        });
        document.getElementById('label-' + type).classList.add('border-[#015425]', 'bg-green-50');
        
        calculateSavingsRate();
    }

    function calculateSavingsRate() {
        const amount = parseFloat(document.getElementById('initial_deposit').value) || 0;
        let rate = "0%";
        let label = "Interest Rate";

        switch(activeType) {
            case 'business': rate = "2.96%"; label = "Business Saving Rate"; break;
            case 'flex': rate = "5.37%"; label = "FeedTan Flexi Rate"; break;
            case 'emergency': rate = "0%"; label = "Emergence Fund (No Interest)"; break;
            case 'rda':
                label = "RDA Dynamic Rate";
                if (amount > 299000) rate = "6.78%";
                else if (amount >= 200000) rate = "6.30%";
                else if (amount >= 100000) rate = "5.84%";
                else rate = "0%";
                break;
        }

        document.getElementById('current-rate').innerText = rate;
        document.getElementById('rate-label').innerText = label;
    }

    window.onload = function() {
        updateSavingsInfo('business');
    };
</script>
@endsection
