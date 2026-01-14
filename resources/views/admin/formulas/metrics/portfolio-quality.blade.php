@extends('layouts.admin')

@section('page-title', 'Portfolio Quality Formulas')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Portfolio Quality Formulas</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Configure portfolio quality metrics</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Portfolio at Risk (PAR)</h3>
            <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">(loans_at_risk / total_portfolio) * 100</textarea>
            <button class="mt-4 px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Default Rate</h3>
            <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">(defaulted_loans / total_loans_disbursed) * 100</textarea>
            <button class="mt-4 px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-[#015425] mb-4">Recovery Rate</h3>
            <textarea rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md font-mono text-sm">(amount_recovered / amount_due) * 100</textarea>
            <button class="mt-4 px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save</button>
        </div>
    </div>
</div>
@endsection

