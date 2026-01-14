@extends('layouts.admin')

@section('page-title', 'Welfare Benefit Calculation')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Welfare Benefit Calculation</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure benefit calculation formulas</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Medical Claims</h3>
            <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">Math.min(actual_expense * reimbursement_rate / 100, maximum_coverage)</textarea>
            <button class="mt-4 px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Funeral Assistance</h3>
            <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">base_benefit_amount * relationship_multiplier</textarea>
            <button class="mt-4 px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>
    </div>
</div>
@endsection

