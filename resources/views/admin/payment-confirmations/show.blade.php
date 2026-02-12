@extends('layouts.admin')

@section('page-title', 'Payment Confirmation Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Payment Confirmation Details</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">View payment confirmation information</p>
            </div>
            <a href="{{ route('admin.payment-confirmations.index') }}" class="px-4 py-2 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Member Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Member Information</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Member ID</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $paymentConfirmation->member_id }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Member Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $paymentConfirmation->member_name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Member Type</label>
                    <p class="text-lg text-gray-900">{{ $paymentConfirmation->member_type ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-lg text-gray-900">{{ $paymentConfirmation->member_email ?: 'Not provided' }}</p>
                </div>
                @if($paymentConfirmation->user_id)
                <div>
                    <label class="text-sm font-medium text-gray-500">Registered User</label>
                    <p class="text-lg text-gray-900">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Yes</span>
                        @if($paymentConfirmation->user)
                            <a href="{{ route('admin.users.show', $paymentConfirmation->user) }}" class="text-[#015425] hover:underline ml-2">View User Profile</a>
                        @endif
                    </p>
                </div>
                @else
                <div>
                    <label class="text-sm font-medium text-gray-500">Registered User</label>
                    <p class="text-lg text-gray-900">
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">Not Registered</span>
                        <span class="text-sm text-gray-500 ml-2">Member not yet registered in system</span>
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-[#015425] mb-4">Payment Information</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Amount to be Paid</label>
                    <p class="text-2xl font-bold text-[#015425]">TZS {{ number_format($paymentConfirmation->amount_to_pay, 2) }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Deposit Balance</label>
                    <p class="text-lg font-semibold text-gray-900">TZS {{ number_format($paymentConfirmation->deposit_balance, 2) }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Status</label>
                    <div class="mt-1">
                        @if($paymentConfirmation->total_distribution > 0)
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending Distribution
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Submitted At</label>
                    <p class="text-lg text-gray-900">{{ $paymentConfirmation->created_at->format('F d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Distribution -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Payment Distribution</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($paymentConfirmation->swf_contribution > 0)
            <div class="bg-gray-50 p-4 rounded-lg">
                <label class="text-sm font-medium text-gray-500">SWF Contribution</label>
                <p class="text-lg font-semibold text-gray-900">TZS {{ number_format($paymentConfirmation->swf_contribution, 2) }}</p>
            </div>
            @endif

            @if($paymentConfirmation->re_deposit > 0)
            <div class="bg-gray-50 p-4 rounded-lg">
                <label class="text-sm font-medium text-gray-500">Re-deposit</label>
                <p class="text-lg font-semibold text-gray-900">TZS {{ number_format($paymentConfirmation->re_deposit, 2) }}</p>
            </div>
            @endif

            @if($paymentConfirmation->fia_investment > 0)
            <div class="bg-gray-50 p-4 rounded-lg">
                <label class="text-sm font-medium text-gray-500">FIA Investment ({{ $paymentConfirmation->fia_type === '4_year' ? '4 Year' : '6 Year' }})</label>
                <p class="text-lg font-semibold text-gray-900">TZS {{ number_format($paymentConfirmation->fia_investment, 2) }}</p>
            </div>
            @endif

            @if($paymentConfirmation->capital_contribution > 0)
            <div class="bg-gray-50 p-4 rounded-lg">
                <label class="text-sm font-medium text-gray-500">Capital Contribution (Share)</label>
                <p class="text-lg font-semibold text-gray-900">TZS {{ number_format($paymentConfirmation->capital_contribution, 2) }}</p>
            </div>
            @endif

            @if($paymentConfirmation->loan_repayment > 0)
            <div class="bg-gray-50 p-4 rounded-lg">
                <label class="text-sm font-medium text-gray-500">Loan Repayment</label>
                <p class="text-lg font-semibold text-gray-900">TZS {{ number_format($paymentConfirmation->loan_repayment, 2) }}</p>
            </div>
            @endif
        </div>

        @if($paymentConfirmation->total_distribution == 0)
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">No distribution has been set yet. Member needs to complete the payment confirmation form.</p>
            </div>
        @else
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-green-800">Total Distribution:</span>
                    <span class="text-lg font-bold text-green-900">TZS {{ number_format($paymentConfirmation->total_distribution, 2) }}</span>
                </div>
            </div>
        @endif
    </div>

    @if($paymentConfirmation->notes)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-4">Notes</h2>
        <p class="text-gray-700">{{ $paymentConfirmation->notes }}</p>
    </div>
    @endif
</div>
@endsection

