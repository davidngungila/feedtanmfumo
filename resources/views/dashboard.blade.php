@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gradient-emaoni">FEEDTAN DIGITAL</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-[#015425] hover:bg-green-50 rounded-md transition">
                            Admin Panel
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-md transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-3xl font-bold text-[#015425] mb-6">Dashboard</h2>
            
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg">
                    <div class="text-4xl mb-4">📝</div>
                    <h3 class="text-xl font-semibold text-[#015425] mb-2">Submit Feedback</h3>
                    <p class="text-gray-600">Share your complaints, suggestions, or inquiries</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg">
                    <div class="text-4xl mb-4">💰</div>
                    <h3 class="text-xl font-semibold text-[#015425] mb-2">Loan Management</h3>
                    <p class="text-gray-600">Apply for loans and track your payments</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold text-[#015425] mb-2">Community Insights</h3>
                    <p class="text-gray-600">View announcements and community updates</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Account Information</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Name:</span> {{ Auth::user()->name }}</p>
                    <p><span class="font-medium">Email:</span> {{ Auth::user()->email }}</p>
                    <p><span class="font-medium">Role:</span> <span class="px-2 py-1 bg-[#015425] text-white rounded text-sm">{{ ucfirst(Auth::user()->role) }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

