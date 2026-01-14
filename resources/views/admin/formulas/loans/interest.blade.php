@extends('layouts.admin')

@section('page-title', 'Loan Interest Calculation Formulas')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Loan Interest Calculation Formulas</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Configure interest calculation methods for loans</p>
            </div>
            <a href="{{ route('admin.formulas.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                Back to Formulas
            </a>
        </div>
    </div>

    <!-- Formula Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Flat Rate Interest Formula -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[#015425]">Flat Rate Interest Formula</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Active</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Simple interest calculation based on the original principal amount</p>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <code class="text-sm">
                    Monthly Interest = (Principal × Annual Rate) ÷ 12<br>
                    Total Interest = Principal × Rate × (Term / 12)
                </code>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                    <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">(principal_amount * interest_rate / 100) / 12</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Interest Rate (%)</label>
                    <input type="number" step="0.01" value="12.00" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
            </div>

            <div class="mt-6 pt-4 border-t flex justify-end">
                <button class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Save Formula
                </button>
            </div>
        </div>

        <!-- Reducing Balance Interest Formula -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[#015425]">Reducing Balance Interest Formula</h3>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Active</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Interest calculated on the outstanding principal balance</p>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <code class="text-sm">
                    Monthly Interest = (Outstanding Principal × Monthly Rate)<br>
                    Monthly Rate = Annual Rate ÷ 12
                </code>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                    <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">(outstanding_principal * (interest_rate / 100)) / 12</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Interest Rate (%)</label>
                    <input type="number" step="0.01" value="15.00" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
            </div>

            <div class="mt-6 pt-4 border-t flex justify-end">
                <button class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Save Formula
                </button>
            </div>
        </div>

        <!-- Daily Interest Calculation -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[#015425]">Daily Interest Calculation</h3>
                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">Inactive</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Calculate interest on a daily basis</p>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <code class="text-sm">
                    Daily Interest = (Principal × Annual Rate) ÷ 365<br>
                    Total Interest = Daily Interest × Number of Days
                </code>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                    <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">(principal_amount * interest_rate / 100) / 365 * days_elapsed</textarea>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t flex justify-end">
                <button class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Save Formula
                </button>
            </div>
        </div>

        <!-- EMI Calculator -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[#015425]">EMI (Equated Monthly Installment)</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Active</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Calculate equal monthly installments</p>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <code class="text-sm">
                    EMI = [P × r × (1+r)^n] ÷ [(1+r)^n - 1]<br>
                    Where: P = Principal, r = Monthly Rate, n = Number of Months
                </code>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Formula</label>
                    <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">(principal_amount * (monthly_rate / 100) * Math.pow(1 + (monthly_rate / 100), term_months)) / (Math.pow(1 + (monthly_rate / 100), term_months) - 1)</textarea>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t flex justify-end">
                <button class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Save Formula
                </button>
            </div>
        </div>
    </div>

    <!-- Formula Testing Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Formula Testing</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Principal Amount</label>
                <input type="number" id="test-principal" value="1000000" class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Interest Rate (%)</label>
                <input type="number" id="test-rate" value="15" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Term (Months)</label>
                <input type="number" id="test-term" value="12" class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>
        </div>
        
        <div class="mt-6">
            <button onclick="testFormula()" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                Test Formula
            </button>
        </div>
        
        <div id="test-result" class="mt-6 bg-gray-50 rounded-lg p-4 hidden">
            <h3 class="font-semibold mb-2">Test Result:</h3>
            <p class="text-lg" id="result-text"></p>
        </div>
    </div>
</div>

<script>
function testFormula() {
    const principal = parseFloat(document.getElementById('test-principal').value);
    const rate = parseFloat(document.getElementById('test-rate').value);
    const term = parseFloat(document.getElementById('test-term').value);
    
    // Flat rate calculation
    const monthlyInterest = (principal * rate / 100) / 12;
    const totalInterest = principal * rate / 100 * (term / 12);
    
    document.getElementById('result-text').textContent = 
        `Monthly Interest: ${monthlyInterest.toLocaleString()} TZS | Total Interest: ${totalInterest.toLocaleString()} TZS`;
    document.getElementById('test-result').classList.remove('hidden');
}
</script>
@endsection

