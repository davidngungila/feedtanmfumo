@extends('layouts.member')

@section('page-title', 'Uwekezaji Mpya')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] px-6 py-8 sm:px-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">Anza Uwekezaji Mpya</h2>
            <p class="text-green-50 text-sm sm:text-base">Chagua mpango wa uwekezaji unaokufaa na uanze kukuza akiba yako leo.</p>
        </div>
        
        <form action="{{ route('member.investments.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Investment selection -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Chagua Mpango wa Uwekezaji *</label>
                        <div class="grid grid-cols-1 gap-4">
                            <label class="relative flex p-4 cursor-pointer rounded-xl border-2 transition focus:outline-none hover:bg-green-50 border-gray-200" id="label-4year">
                                <input type="radio" name="plan_type" value="4_year" class="sr-only" required checked onclick="updateInfo('4_year')">
                                <div class="flex flex-col">
                                    <span class="block text-sm font-bold text-[#015425] uppercase tracking-wider">FIA - Miaka 4</span>
                                    <span class="block mt-1 text-xs text-gray-500">8.6% Annual Return</span>
                                    <span class="block mt-2 text-xs font-medium text-gray-600">Bei: TZS 110/100</span>
                                </div>
                                <div class="absolute top-2 right-2 hidden check-icon text-[#015425]">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                </div>
                            </label>

                            <label class="relative flex p-4 cursor-pointer rounded-xl border-2 transition focus:outline-none hover:bg-green-50 border-gray-200" id="label-6year">
                                <input type="radio" name="plan_type" value="6_year" class="sr-only" onclick="updateInfo('6_year')">
                                <div class="flex flex-col">
                                    <span class="block text-sm font-bold text-[#015425] uppercase tracking-wider">FIA - Miaka 6</span>
                                    <span class="block mt-1 text-xs text-gray-500">10.0% Annual Return</span>
                                    <span class="block mt-2 text-xs font-medium text-gray-600">Bei: TZS 120/100</span>
                                </div>
                                <div class="absolute top-2 right-2 hidden check-icon text-[#015425]">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                </div>
                            </label>
                        </div>
                        @error('plan_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kiasi cha Uwekezaji (TZS) *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-medium">TZS</span>
                            </div>
                            <input type="number" name="principal_amount" id="amount" step="1000" min="1000" required 
                                class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-[#015425] focus:border-[#015425] shadow-sm transition" 
                                placeholder="Mfano: 500,000"
                                oninput="calculatePreview()">
                        </div>
                        <p class="mt-2 text-xs text-gray-500 italic">Kiasi cha chini ni TZS 1,000</p>
                        @error('principal_amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Preview and Receipt -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex flex-col h-full">
                    <h3 class="text-lg font-bold text-[#015425] mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Muhtasari wa Makadirio
                    </h3>
                    
                    <div class="space-y-4 flex-grow">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Idadi ya Units:</span>
                            <span class="text-sm font-bold text-gray-800" id="preview-units">0.00</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Riba (Annual):</span>
                            <span class="text-sm font-bold text-gray-800" id="preview-rate">8.6%</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Kipindi:</span>
                            <span class="text-sm font-bold text-gray-800" id="preview-years">Miaka 4</span>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-inner mt-4">
                            <div class="flex justify-between items-center text-green-700">
                                <span class="text-xs font-bold uppercase">Marejesho Yanayotarajiwa:</span>
                                <span class="text-lg font-black" id="preview-total">TZS 0.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ambatisha Risiti ya Malipo *</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-[#015425] transition relative">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="payment_receipt" class="relative cursor-pointer bg-white rounded-md font-medium text-[#015425] hover:text-[#013019] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#015425]">
                                        <span>Pakia faili</span>
                                        <input id="payment_receipt" name="payment_receipt" type="file" required class="sr-only" accept=".pdf,.jpg,.jpeg,.png" onchange="updateFileName(this)">
                                    </label>
                                    <p class="pl-1">au buruta hapa</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF, PNG, JPG (Max 5MB)</p>
                                <p id="file-name" class="text-xs font-bold text-[#015425] mt-2"></p>
                            </div>
                        </div>
                        @error('payment_receipt')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-between border-t border-gray-100 pt-8 gap-4">
                <p class="text-sm text-gray-500 italic flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    Uwekezaji utaanza rasmi mara baada ya malipo kuhakikiwa na Timu ya FedTan Digital.
                </p>
                <div class="flex space-x-4 w-full sm:w-auto">
                    <a href="{{ route('member.investments.index') }}" class="flex-1 sm:flex-none px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition text-center">
                        Ghairi
                    </a>
                    <button type="submit" class="flex-1 sm:flex-none px-8 py-3 bg-[#015425] text-white font-bold rounded-xl hover:bg-[#013019] shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                        Wasilisha Uwekezaji
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    input[type="radio"]:checked + div + .check-icon {
        display: block;
    }
    input[type="radio"]:checked + div {
        color: #015425;
    }
    input[type="radio"]:checked ~ label,
    label:has(input[type="radio"]:checked) {
        border-color: #015425;
        background-color: #f0fdf4;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    let currentPlan = '4_year';
    
    function updateInfo(plan) {
        currentPlan = plan;
        document.getElementById('preview-rate').innerText = plan === '4_year' ? '8.6%' : '10.0%';
        document.getElementById('preview-years').innerText = plan === '4_year' ? 'Miaka 4' : 'Miaka 6';
        
        // Update selection UI
        document.getElementById('label-4year').classList.toggle('border-[#015425]', plan === '4_year');
        document.getElementById('label-4year').classList.toggle('bg-green-50', plan === '4_year');
        document.getElementById('label-6year').classList.toggle('border-[#015425]', plan === '6_year');
        document.getElementById('label-6year').classList.toggle('bg-green-50', plan === '6_year');
        
        calculatePreview();
    }

    function calculatePreview() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const is4Year = currentPlan === '4_year';
        
        const years = is4Year ? 4 : 6;
        const rate = is4Year ? 0.086 : 0.10;
        const price = is4Year ? 110 : 120;
        
        const unitPriceFactor = price / 100;
        const units = amount * unitPriceFactor;
        const totalInterest = units * rate * years;
        const expectedReturn = units + totalInterest;
        
        document.getElementById('preview-units').innerText = units.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('preview-total').innerText = 'TZS ' + expectedReturn.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function updateFileName(input) {
        const fileName = input.files[0] ? input.files[0].name : '';
        document.getElementById('file-name').innerText = 'Faili lililoandaliwa: ' + fileName;
    }

    // Initialize UI
    window.onload = function() {
        updateInfo('4_year');
    };
</script>
@endsection
