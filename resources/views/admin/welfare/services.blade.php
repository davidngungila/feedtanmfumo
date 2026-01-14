@extends('layouts.admin')

@section('page-title', 'Welfare Services')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Welfare Services</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Available welfare services, eligibility criteria, and service information</p>
            </div>
            <a href="{{ route('admin.welfare.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Welfare
            </a>
        </div>
    </div>

    <!-- Services Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($services as $key => $service)
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ 
                $key === 'medical' ? 'border-blue-500' : 
                ($key === 'funeral' ? 'border-purple-500' : 
                ($key === 'educational' ? 'border-green-500' : 'border-gray-500'))
            }}">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $service['name'] }}</h3>
                    <svg class="w-8 h-8 {{ 
                        $key === 'medical' ? 'text-blue-500' : 
                        ($key === 'funeral' ? 'text-purple-500' : 
                        ($key === 'educational' ? 'text-green-500' : 'text-gray-500'))
                    }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <p class="text-sm text-gray-600 mb-4">{{ $service['description'] }}</p>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Benefits:</span>
                        <span class="font-semibold text-gray-900">TZS {{ number_format($service['total_benefits'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Claims:</span>
                        <span class="font-semibold text-gray-900">{{ $service['total_claims'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Pending:</span>
                        <span class="font-semibold text-yellow-600">{{ $service['pending_claims'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Detailed Service Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($services as $key => $service)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-[#015425]">{{ $service['name'] }}</h3>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                        {{ $service['total_claims'] }} Claims
                    </span>
                </div>
                
                <p class="text-gray-700 mb-4">{{ $service['description'] }}</p>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Eligibility Criteria</h4>
                    <p class="text-sm text-gray-700">{{ $service['eligibility_criteria'] }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-green-50 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">Total Disbursed</p>
                        <p class="text-lg font-bold text-green-600">TZS {{ number_format($service['total_benefits'], 2) }}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">Pending Claims</p>
                        <p class="text-lg font-bold text-yellow-600">{{ $service['pending_claims'] }}</p>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.welfare.claims-processing', ['benefit_type' => $key]) }}" class="text-sm text-[#015425] hover:text-[#013019] font-medium">
                        View {{ $service['name'] }} Claims →
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recent Service Requests -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-[#015425]">Recent Service Requests</h2>
            <a href="{{ route('admin.welfare.claims-processing') }}" class="text-sm text-[#015425] hover:text-[#013019] font-medium">View All Claims →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Welfare #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentRequests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $request->welfare_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $request->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $request->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $request->benefit_type_name ?? 'Other' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                                TZS {{ number_format($request->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $request->transaction_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ 
                                    $request->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                    ($request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($request->status === 'disbursed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'))
                                }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.welfare.show', $request) }}" class="text-[#015425] hover:text-[#013019]">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">No service requests found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Service Guidelines -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-[#015425] mb-4">Service Guidelines</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border-l-4 border-blue-500 pl-4">
                <h3 class="font-semibold text-gray-900 mb-2">Application Process</h3>
                <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                    <li>Submit application with required documentation</li>
                    <li>Eligibility verification by welfare committee</li>
                    <li>Approval decision within 7-14 business days</li>
                    <li>Disbursement within 3-5 business days after approval</li>
                </ul>
            </div>
            <div class="border-l-4 border-green-500 pl-4">
                <h3 class="font-semibold text-gray-900 mb-2">Required Documents</h3>
                <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                    <li>Completed application form</li>
                    <li>Supporting documents (medical reports, invoices, etc.)</li>
                    <li>Member identification</li>
                    <li>Proof of membership status</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

