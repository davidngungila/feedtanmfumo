@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center space-y-8 bg-white p-10 rounded-3xl shadow-2xl border border-green-100">
        <div>
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Assessment Submitted!</h2>
            <p class="text-gray-600 mb-8">Thank you for completing the guarantor assessment. Your commitment is a vital part of the FeedTan community.</p>
        </div>

        <div class="bg-gray-50 rounded-2xl p-6 text-left border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">What's Next?</h3>
            <ul class="space-y-4">
                <li class="flex items-start">
                    <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mt-0.5">1</div>
                    <p class="ml-3 text-sm text-gray-700 font-medium">An automated email containing the <strong>Guarantee Agreement</strong> has been sent to you and the borrower.</p>
                </li>
                <li class="flex items-start">
                    <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mt-0.5">2</div>
                    <p class="ml-3 text-sm text-gray-700">A Loan Officer will review your assessment responses.</p>
                </li>
                <li class="flex items-start">
                    <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mt-0.5">3</div>
                    <p class="ml-3 text-sm text-gray-700">If any "Clarification Needed" answers were provided, you may be contacted for a brief counseling session.</p>
                </li>
            </ul>
        </div>

        <div class="pt-4">
            <p class="text-xs text-gray-500 italic mb-6">Agreement ID: {{ $assessment->ulid }}</p>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-md transition duration-150">
                Return to Home
            </a>
        </div>
    </div>
</div>
@endsection
