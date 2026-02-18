@extends('layouts.admin')

@section('page-title', 'Management of Loan Statements')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Loan Statements History</h3>
            <p class="text-sm text-gray-500">View and manage uploaded loan statements by month.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.loan-statements.create') }}" class="inline-flex items-center px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition shadow-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Import New Statements
            </a>
        </div>
    </div>

    @if($months->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No Statements Yet</h3>
            <p class="text-gray-500 mt-2 max-w-sm mx-auto">Upload your first loan statement Excel sheet to get started.</p>
            <div class="mt-6">
                <a href="{{ route('admin.loan-statements.create') }}" class="text-[#015425] font-semibold hover:underline">Import Statements &rarr;</a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($months as $item)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition group">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center text-[#015425] group-hover:bg-[#015425] group-hover:text-white transition-colors">
                                <span class="text-xl font-bold">{{ substr($item->month_name, 0, 3) }}</span>
                            </div>
                            <span class="text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-600 rounded-full">{{ $item->year }}</span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900">{{ $item->month_name }} {{ $item->year }}</h4>
                        <p class="text-sm text-gray-500 mt-1">Loan performance snapshot for this period.</p>
                        
                        <div class="mt-6 flex flex-col gap-2">
                            <a href="{{ route('admin.loan-statements.show', ['year' => $item->year, 'month' => $item->month]) }}" class="w-full text-center px-4 py-2 bg-gray-50 text-gray-700 rounded-md hover:bg-[#015425] hover:text-white transition text-sm font-semibold">
                                View Details
                            </a>
                            <form action="{{ route('admin.loan-statements.destroy', ['year' => $item->year, 'month' => $item->month]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all records for this month?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-center px-4 py-2 border border-transparent text-xs text-red-600 hover:bg-red-50 rounded-md transition font-medium">
                                    Delete This Month
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
