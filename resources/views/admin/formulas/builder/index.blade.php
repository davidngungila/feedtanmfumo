@extends('layouts.admin')

@section('page-title', 'Formula Builder')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Formula Builder</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Visual formula editor and builder</p>
            </div>
            <a href="{{ route('admin.formulas.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">Back to Formulas</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formula Editor -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Formula Editor</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Formula Name *</label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]" placeholder="Enter formula name">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Formula Category *</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    <option>Loans - Interest Calculation</option>
                    <option>Loans - Fees</option>
                    <option>Savings - Interest</option>
                    <option>Investments</option>
                    <option>Shares</option>
                    <option>Welfare</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Formula Code *</label>
                <textarea id="formula-editor" rows="12" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm focus:ring-[#015425] focus:border-[#015425]" placeholder="Enter formula expression...">(principal_amount * interest_rate / 100) / 12</textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <button class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">Test Formula</button>
                <button class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save Formula</button>
            </div>
        </div>

        <!-- Toolbox -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-6">Formula Toolbox</h2>
            
            <!-- Mathematical Operators -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-3">Operators</h3>
                <div class="grid grid-cols-3 gap-2">
                    <button onclick="insertOperator('+')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-center font-mono">+</button>
                    <button onclick="insertOperator('-')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-center font-mono">-</button>
                    <button onclick="insertOperator('*')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-center font-mono">×</button>
                    <button onclick="insertOperator('/')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-center font-mono">/</button>
                    <button onclick="insertOperator('(')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-center font-mono">(</button>
                    <button onclick="insertOperator(')')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-center font-mono">)</button>
                    <button onclick="insertOperator('**')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-center font-mono">^</button>
                    <button onclick="insertOperator('%')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-center font-mono">%</button>
                    <button onclick="insertOperator('==')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-center font-mono">==</button>
                </div>
            </div>

            <!-- Variables -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-3">Variables</h3>
                <div class="space-y-2">
                    <button onclick="insertVariable('principal_amount')" class="w-full text-left px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded text-sm">{principal_amount}</button>
                    <button onclick="insertVariable('interest_rate')" class="w-full text-left px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded text-sm">{interest_rate}</button>
                    <button onclick="insertVariable('term_months')" class="w-full text-left px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded text-sm">{term_months}</button>
                    <button onclick="insertVariable('account_balance')" class="w-full text-left px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded text-sm">{account_balance}</button>
                    <button onclick="insertVariable('days_elapsed')" class="w-full text-left px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded text-sm">{days_elapsed}</button>
                </div>
            </div>

            <!-- Functions -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-3">Functions</h3>
                <div class="space-y-2">
                    <button onclick="insertFunction('Math.pow(base, exponent)')" class="w-full text-left px-3 py-2 bg-green-50 hover:bg-green-100 rounded text-sm">Math.pow()</button>
                    <button onclick="insertFunction('Math.round()')" class="w-full text-left px-3 py-2 bg-green-50 hover:bg-green-100 rounded text-sm">Math.round()</button>
                    <button onclick="insertFunction('Math.max()')" class="w-full text-left px-3 py-2 bg-green-50 hover:bg-green-100 rounded text-sm">Math.max()</button>
                    <button onclick="insertFunction('Math.min()')" class="w-full text-left px-3 py-2 bg-green-50 hover:bg-green-100 rounded text-sm">Math.min()</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Formula Preview -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Formula Preview & Testing</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Test Value 1</label>
                <input type="number" id="test1" value="1000000" class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Test Value 2</label>
                <input type="number" id="test2" value="12" class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Result</label>
                <input type="text" id="formula-result" readonly class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md font-semibold">
            </div>
        </div>
        <button onclick="testFormula()" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Test Formula</button>
    </div>
</div>

<script>
function insertOperator(op) {
    const editor = document.getElementById('formula-editor');
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    editor.value = editor.value.substring(0, start) + op + editor.value.substring(end);
    editor.focus();
    editor.setSelectionRange(start + op.length, start + op.length);
}

function insertVariable(varName) {
    const editor = document.getElementById('formula-editor');
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    editor.value = editor.value.substring(0, start) + '{' + varName + '}' + editor.value.substring(end);
    editor.focus();
    editor.setSelectionRange(start + varName.length + 2, start + varName.length + 2);
}

function insertFunction(func) {
    const editor = document.getElementById('formula-editor');
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    editor.value = editor.value.substring(0, start) + func + editor.value.substring(end);
    editor.focus();
    editor.setSelectionRange(start + func.length, start + func.length);
}

function testFormula() {
    const formula = document.getElementById('formula-editor').value;
    const test1 = parseFloat(document.getElementById('test1').value) || 0;
    const test2 = parseFloat(document.getElementById('test2').value) || 0;
    
    try {
        const result = eval(formula.replace('{principal_amount}', test1).replace('{interest_rate}', test2));
        document.getElementById('formula-result').value = result.toLocaleString() + ' TZS';
    } catch (e) {
        document.getElementById('formula-result').value = 'Error: ' + e.message;
    }
}
</script>
@endsection

