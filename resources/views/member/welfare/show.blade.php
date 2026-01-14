@extends('layouts.member')

@section('page-title', 'Welfare Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-[#015425]">Welfare Details</h1>
        <a href="{{ route('member.welfare.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
            Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Record Information</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Welfare Number</p>
                <p class="text-lg font-semibold">{{ $welfare->welfare_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Type</p>
                <span class="px-3 py-1 text-sm rounded-full {{ 
                    $welfare->type === 'contribution' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'
                }}">
                    {{ ucfirst($welfare->type) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Amount</p>
                <p class="text-2xl font-bold {{ $welfare->type === 'contribution' ? 'text-green-600' : 'text-blue-600' }}">
                    {{ number_format($welfare->amount, 0) }} TZS
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <span class="px-3 py-1 text-sm rounded-full {{ 
                    $welfare->status === 'approved' ? 'bg-green-100 text-green-800' : 
                    ($welfare->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')
                }}">
                    {{ ucfirst($welfare->status) }}
                </span>
            </div>
            @if($welfare->description)
            <div class="col-span-2">
                <p class="text-sm text-gray-600 mb-1">Description</p>
                <p class="text-gray-900">{{ $welfare->description }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

