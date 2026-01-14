@extends('layouts.member')

@section('page-title', 'Social Welfare')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#015425]">Social Welfare</h1>
        <a href="{{ route('member.welfare.create') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-center text-sm sm:text-base">
            New Record
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Contributions</p>
            <p class="text-lg sm:text-2xl font-bold text-green-600">{{ number_format($stats['total_contributions'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Benefits</p>
            <p class="text-lg sm:text-2xl font-bold text-blue-600">{{ number_format($stats['total_benefits'], 0) }} TZS</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Pending</p>
            <p class="text-xl sm:text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600 mb-1">Approved</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
        </div>
    </div>

    <!-- Welfare Records -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Welfare #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($welfares as $welfare)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $welfare->welfare_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucfirst($welfare->type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">{{ number_format($welfare->amount, 0) }} TZS</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $welfare->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                    ($welfare->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')
                                }}">
                                    {{ ucfirst($welfare->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $welfare->transaction_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('member.welfare.show', $welfare) }}" class="text-[#015425] hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No welfare records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($welfares->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $welfares->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

