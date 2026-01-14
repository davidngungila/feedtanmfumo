@extends('layouts.member')

@section('page-title', 'My Profile')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 sm:p-8 text-white">
        <div class="flex flex-col md:flex-row items-center md:items-start justify-between">
            <div class="flex items-center space-x-4 sm:space-x-6 mb-4 md:mb-0">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-white bg-opacity-20 backdrop-blur-sm flex items-center justify-center text-3xl sm:text-4xl font-bold border-4 border-white border-opacity-30">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">{{ $user->name }}</h1>
                    <p class="text-base sm:text-lg text-white text-opacity-90 mb-1">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="text-xs sm:text-sm text-white text-opacity-75">{{ $user->phone }}</p>
                    @endif
                </div>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                <a href="{{ route('member.profile.edit') }}" class="px-4 sm:px-6 py-2 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium text-center text-sm sm:text-base">
                    Edit Profile
                </a>
                <a href="{{ route('member.profile.settings') }}" class="px-4 sm:px-6 py-2 bg-white bg-opacity-20 backdrop-blur-sm text-white rounded-md hover:bg-opacity-30 transition font-medium text-center text-sm sm:text-base">
                    Settings
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Loans</p>
                            <p class="text-xl sm:text-2xl font-bold text-[#015425]">{{ $stats['loans'] }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Savings</p>
                            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ $stats['savings'] }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Investments</p>
                            <p class="text-xl sm:text-2xl font-bold text-purple-600">{{ $stats['investments'] }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Issues</p>
                            <p class="text-xl sm:text-2xl font-bold text-orange-600">{{ $stats['issues'] }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-[#015425] mb-4">Personal Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Full Name</p>
                        <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Email Address</p>
                        <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                    </div>
                    @if($user->phone)
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Phone Number</p>
                        <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $user->phone }}</p>
                    </div>
                    @endif
                    @if($user->address)
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Address</p>
                        <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $user->address }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Member Since</p>
                        <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Last Updated</p>
                        <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $user->updated_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-[#015425] mb-4">Roles & Permissions</h2>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->roles as $role)
                        <span class="px-3 sm:px-4 py-1 sm:py-2 bg-[#015425] text-white rounded-full text-xs sm:text-sm font-medium">
                            {{ $role->name }}
                        </span>
                    @empty
                        <p class="text-gray-500 text-sm">No roles assigned</p>
                    @endforelse
                </div>
            </div>

            @if($user->bio)
            <!-- Bio -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-[#015425] mb-4">About</h2>
                <p class="text-sm sm:text-base text-gray-700 whitespace-pre-wrap">{{ $user->bio }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-[#015425] mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('member.profile.edit') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 rounded-md hover:bg-gray-50 transition text-gray-700 text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profile
                    </a>
                    <a href="{{ route('member.profile.settings') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 rounded-md hover:bg-gray-50 transition text-gray-700 text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </a>
                    <a href="{{ route('member.dashboard') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 rounded-md hover:bg-gray-50 transition text-gray-700 text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>

            <!-- Account Security -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-[#015425] mb-4">Account Security</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm text-gray-600">Email Verified</span>
                        @if($user->email_verified_at)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Verified</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Not Verified</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm text-gray-600">Two-Factor Auth</span>
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Disabled</span>
                    </div>
                    <a href="{{ route('member.profile.edit') }}#password" class="block w-full mt-4 px-4 py-2 text-center bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-xs sm:text-sm font-medium">
                        Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

