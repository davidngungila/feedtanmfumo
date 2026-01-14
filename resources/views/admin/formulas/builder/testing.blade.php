@extends('layouts.admin')

@section('page-title', 'Formula Testing')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Formula Testing</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Test formulas with sample data</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Test Formula</h2>
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Formula to Test</label>
                <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">(principal_amount * interest_rate / 100) / 12</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Variable 1: principal_amount</label>
                    <input type="number" id="var1" value="1000000" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Variable 2: interest_rate</label>
                    <input type="number" id="var2" value="12" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Result</label>
                    <input type="text" id="test-result" readonly class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md font-semibold">
                </div>
            </div>
            <button onclick="testFormula()" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Test Formula</button>
        </div>
    </div>
</div>

<script>
function testFormula() {
    const formula = document.querySelector('textarea').value;
    const var1 = parseFloat(document.getElementById('var1').value) || 0;
    const var2 = parseFloat(document.getElementById('var2').value) || 0;
    
    try {
        const result = eval(formula.replace('principal_amount', var1).replace('interest_rate', var2));
        document.getElementById('test-result').value = result.toLocaleString();
    } catch (e) {
        document.getElementById('test-result').value = 'Error: ' + e.message;
    }
}
</script>
@endsection

