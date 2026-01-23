@extends('layouts.admin')

@section('page-title', 'Social Welfare Details')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Welfare Record Details</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">{{ $welfare->welfare_number }}</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.welfare.edit', $welfare) }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Edit Record
                </a>
                <a href="{{ route('admin.welfare.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-[#015425] rounded-md transition font-medium">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Record Information Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Record Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Welfare Number</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $welfare->welfare_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Type</p>
                        <span class="px-4 py-2 text-sm font-semibold rounded-full inline-block {{ 
                            $welfare->type === 'contribution' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'
                        }}">
                            {{ ucfirst($welfare->type) }}
                        </span>
                    </div>
                    @if($welfare->benefit_type)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Benefit Type</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $welfare->benefit_type_name }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Amount</p>
                        <p class="text-2xl font-bold {{ $welfare->type === 'contribution' ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($welfare->amount, 2) }} TZS
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Transaction Date</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $welfare->transaction_date->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $welfare->transaction_date->diffForHumans() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <span class="px-4 py-2 text-sm font-semibold rounded-full inline-block {{ 
                            $welfare->status === 'approved' || $welfare->status === 'completed' ? 'bg-green-100 text-green-800' : 
                            ($welfare->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            ($welfare->status === 'disbursed' ? 'bg-blue-100 text-blue-800' :
                            ($welfare->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))
                        }}">
                            {{ ucfirst($welfare->status) }}
                        </span>
                    </div>
                    @if($welfare->approval_date)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Approval Date</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $welfare->approval_date->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $welfare->approval_date->diffForHumans() }}</p>
                    </div>
                    @endif
                    @if($welfare->disbursement_date)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Disbursement Date</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $welfare->disbursement_date->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $welfare->disbursement_date->diffForHumans() }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">1</div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Record Created</p>
                            <p class="text-sm text-gray-600">{{ $welfare->created_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($welfare->approval_date)
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">2</div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Approved</p>
                            <p class="text-sm text-gray-600">{{ $welfare->approval_date->format('F d, Y') }}</p>
                            @if($welfare->approver)
                            <p class="text-xs text-gray-500">By: {{ $welfare->approver->name }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    @if($welfare->disbursement_date)
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold">3</div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Disbursed</p>
                            <p class="text-sm text-gray-600">{{ $welfare->disbursement_date->format('F d, Y') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($welfare->status === 'completed')
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Completed</p>
                            <p class="text-sm text-gray-600">This record has been completed</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($welfare->status === 'rejected')
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-red-900">Rejected</p>
                            <p class="text-sm text-gray-600">{{ $welfare->updated_at->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Description Card -->
            @if($welfare->description)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Description</h3>
                <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $welfare->description }}</p>
            </div>
            @endif

            <!-- Eligibility Notes Card -->
            @if($welfare->eligibility_notes)
            <div class="bg-blue-50 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <h3 class="text-xl font-bold text-blue-800 mb-4">Eligibility Notes</h3>
                <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $welfare->eligibility_notes }}</p>
            </div>
            @endif

            <!-- Rejection Reason Card -->
            @if($welfare->rejection_reason)
            <div class="bg-red-50 rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <h3 class="text-xl font-bold text-red-800 mb-4">Rejection Reason</h3>
                <p class="text-gray-700 leading-relaxed">{{ $welfare->rejection_reason }}</p>
            </div>
            @endif

            <!-- Transaction History -->
            @if($welfare->transactions->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Related Transactions</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction #</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($welfare->transactions as $transaction)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium">{{ $transaction->transaction_number }}</td>
                                <td class="px-4 py-3 text-sm">{{ ucfirst($transaction->transaction_type) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold">{{ number_format($transaction->amount, 2) }} TZS</td>
                                <td class="px-4 py-3 text-sm">{{ $transaction->transaction_date->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full {{ 
                                        $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                    }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Member Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Member Information</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Name</p>
                        <p class="font-semibold text-gray-900">{{ $welfare->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <p class="font-semibold text-gray-900">{{ $welfare->user->email }}</p>
                    </div>
                    @if($welfare->user->phone)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Phone</p>
                        <p class="font-semibold text-gray-900">{{ $welfare->user->phone }}</p>
                    </div>
                    @endif
                    <div>
                        <a href="{{ route('admin.users.show', $welfare->user) }}" class="text-[#015425] hover:text-[#013019] font-medium text-sm">View Full Profile →</a>
                    </div>
                </div>
            </div>

            <!-- Approval Info Card -->
            @if($welfare->approver)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Approval Information</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Approved By</p>
                        <p class="font-semibold text-gray-900">{{ $welfare->approver->name }}</p>
                    </div>
                    @if($welfare->approver->email)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <p class="font-semibold text-gray-900">{{ $welfare->approver->email }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Stats Card -->
            <div class="bg-gradient-to-br from-[#015425] to-[#027a3a] rounded-lg shadow-md p-6 text-white">
                <h3 class="text-xl font-bold mb-4">Quick Stats</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-white text-opacity-80">Amount</span>
                        <span class="font-bold">{{ number_format($welfare->amount, 0) }} TZS</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white text-opacity-80">Days Since Created</span>
                        <span class="font-bold">{{ $welfare->created_at->diffInDays(now()) }} days</span>
                    </div>
                    @if($welfare->approval_date)
                    <div class="flex justify-between">
                        <span class="text-white text-opacity-80">Processing Time</span>
                        <span class="font-bold">{{ $welfare->created_at->diffInDays($welfare->approval_date) }} days</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
