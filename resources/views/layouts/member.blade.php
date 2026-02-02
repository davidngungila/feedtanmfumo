<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Member Dashboard') - FEEDTAN DIGITAL</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <style>
        @font-face {
            font-family: 'Quicksand';
            font-style: normal;
            font-weight: 500 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/quicksand/v37/6xKtdSZaM9iE8KbpRA_hJFQNcOM.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
        }
        @font-face {
            font-family: 'Quicksand';
            font-style: normal;
            font-weight: 500 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/quicksand/v37/6xKtdSZaM9iE8KbpRA_hJVQNcOM.woff2) format('woff2');
            unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        @font-face {
            font-family: 'Quicksand';
            font-style: normal;
            font-weight: 500 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/quicksand/v37/6xKtdSZaM9iE8KbpRA_hK1QN.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        
        [x-cloak] { display: none !important; }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Loading Screen -->
    @include('components.loading-screen')
    <div class="min-h-screen flex flex-col">
        <!-- Header with Green Background -->
        <header class="bg-gradient-to-r from-[#015425] to-[#027a3a] shadow-lg fixed top-0 left-0 right-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center h-16 gap-4">
                    <div class="flex items-center flex-1 justify-between">
                        <div class="flex items-center flex-shrink-0">
                        <!-- Mobile Menu Button -->
                        <button type="button" id="mobile-menu-button" class="md:hidden mr-3 p-2 text-white hover:bg-white hover:bg-opacity-20 hover:text-[#015425] rounded-md transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#015425]">
                            <svg id="mobile-menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <a href="{{ route('member.dashboard') }}" class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg flex items-center justify-center mr-2 sm:mr-3 border border-white border-opacity-30">
                                <span class="text-[#015425] font-bold text-sm sm:text-lg">FD</span>
                            </div>
                            <span class="text-lg sm:text-xl font-bold text-white">FeedTan Digital</span>
                        </a>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex items-center space-x-2 lg:space-x-3 xl:space-x-4 flex-1 justify-center">
                        <a href="{{ route('member.dashboard') }}" class="px-3 py-2 rounded-md transition {{ request()->routeIs('member.dashboard') ? 'bg-white font-semibold text-[#015425]' : 'bg-[#015425] text-white hover:bg-[#027a3a]' }}">
                            Dashboard
                        </a>
                        
                        @php
                            $isApproved = Auth::user()->membership_status === 'approved';
                        @endphp
                        
                        <!-- Loans Dropdown -->
                        <div class="relative nav-dropdown {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <button type="button" {{ !$isApproved ? 'disabled' : '' }} class="nav-dropdown-toggle flex items-center px-3 py-2 rounded-md transition {{ request()->routeIs('member.loans.*') ? 'bg-white font-semibold text-[#015425]' : 'bg-[#015425] text-white hover:bg-[#027a3a]' }}" {{ !$isApproved ? 'title="Membership must be approved to access Loans"' : '' }}>
                                Loans
                                <svg class="w-4 h-4 ml-1 nav-dropdown-arrow transition-transform {{ request()->routeIs('member.loans.*') ? 'text-[#015425]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            @if($isApproved)
                            <div class="nav-dropdown-menu hidden absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <a href="{{ route('member.loans.index') }}" class="block px-4 py-2 text-sm text-[#015425] hover:bg-green-50 hover:text-[#015425] transition">All Loans</a>
                                <a href="{{ route('member.loans.create') }}" class="block px-4 py-2 text-sm text-[#015425] hover:bg-green-50 hover:text-[#015425] transition">Apply for Loan</a>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Savings Dropdown -->
                        <div class="relative nav-dropdown {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <button type="button" {{ !$isApproved ? 'disabled' : '' }} class="nav-dropdown-toggle flex items-center px-3 py-2 rounded-md transition {{ request()->routeIs('member.savings.*') ? 'bg-white font-semibold text-[#015425]' : 'bg-[#015425] text-white hover:bg-[#027a3a]' }}" {{ !$isApproved ? 'title="Membership must be approved to access Savings"' : '' }}>
                                Savings
                                <svg class="w-4 h-4 ml-1 nav-dropdown-arrow transition-transform {{ request()->routeIs('member.savings.*') ? 'text-[#015425]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            @if($isApproved)
                            <div class="nav-dropdown-menu hidden absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <a href="{{ route('member.savings.index') }}" class="block px-4 py-2 text-sm text-[#015425] hover:bg-green-50 hover:text-[#015425] transition">All Accounts</a>
                                <a href="{{ route('member.savings.create') }}" class="block px-4 py-2 text-sm text-[#015425] hover:bg-green-50 hover:text-[#015425] transition">Open Account</a>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Investments Dropdown -->
                        <div class="relative nav-dropdown {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <button type="button" {{ !$isApproved ? 'disabled' : '' }} class="nav-dropdown-toggle flex items-center px-3 py-2 rounded-md transition {{ request()->routeIs('member.investments.*') ? 'bg-white font-semibold text-[#015425]' : 'bg-[#015425] text-white hover:bg-[#027a3a]' }}" {{ !$isApproved ? 'title="Membership must be approved to access Investments"' : '' }}>
                                Investments
                                <svg class="w-4 h-4 ml-1 nav-dropdown-arrow transition-transform {{ request()->routeIs('member.investments.*') ? 'text-[#015425]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            @if($isApproved)
                            <div class="nav-dropdown-menu hidden absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <a href="{{ route('member.investments.index') }}" class="block px-4 py-2 text-sm text-[#015425] hover:bg-green-50 hover:text-[#015425] transition">All Investments</a>
                                <a href="{{ route('member.investments.create') }}" class="block px-4 py-2 text-sm text-[#015425] hover:bg-green-50 hover:text-[#015425] transition">Start Investment</a>
                            </div>
                            @endif
                        </div>
                        
                        <a href="{{ $isApproved ? route('member.welfare.index') : '#' }}" class="px-3 py-2 rounded-md transition {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }} {{ request()->routeIs('member.welfare.*') ? 'bg-white font-semibold text-[#015425]' : 'bg-[#015425] text-white hover:bg-[#027a3a]' }}" {{ !$isApproved ? 'title="Membership must be approved to access Welfare"' : '' }}>
                            Welfare
                        </a>
                        
                        <!-- Issues Dropdown -->
                        <div class="relative nav-dropdown {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <button type="button" {{ !$isApproved ? 'disabled' : '' }} class="nav-dropdown-toggle flex items-center px-3 py-2 rounded-md transition {{ request()->routeIs('member.issues.*') ? 'bg-white font-semibold text-[#015425]' : 'bg-[#015425] text-white hover:bg-[#027a3a]' }}" {{ !$isApproved ? 'title="Membership must be approved to access Issues"' : '' }}>
                                Issues
                                <svg class="w-4 h-4 ml-1 nav-dropdown-arrow transition-transform {{ request()->routeIs('member.issues.*') ? 'text-[#015425]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            @if($isApproved)
                            <div class="nav-dropdown-menu hidden absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <a href="{{ route('member.issues.index') }}" class="block px-4 py-2 text-sm text-[#015425] hover:bg-green-50 hover:text-[#015425] transition">All Issues</a>
                                <a href="{{ route('member.issues.create') }}" class="block px-4 py-2 text-sm text-[#015425] hover:bg-green-50 hover:text-[#015425] transition">Report Issue</a>
                            </div>
                            @endif
                        </div>
                    </nav>
                    
                    <div class="flex items-center space-x-3 sm:space-x-4 flex-shrink-0 ml-auto">
                        <!-- Language Switcher -->
                        <div class="hidden md:block">
                            @include('components.language-switcher')
                        </div>
                        
                        <!-- Notifications with Hover -->
                        <div class="relative notification-container">
                            <button id="notification-button" class="relative p-2 text-white hover:bg-white hover:bg-opacity-20 hover:text-[#015425] rounded-full transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#015425]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <!-- Notification Dropdown - Hidden by default, shows on hover -->
                            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50 max-h-96 overflow-y-auto">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-[#015425] to-[#027a3a]">
                                    <h3 class="text-sm font-semibold text-white">Notifications</h3>
                                </div>
                                <div class="py-2">
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                        No new notifications
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Profile Dropdown with Hover -->
                        <div class="relative user-profile-container">
                            <button id="user-menu-button" class="flex items-center space-x-2 sm:space-x-3 p-1 rounded-full hover:bg-white hover:bg-opacity-20 transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#015425] group">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 flex items-center justify-center text-[#015425] font-semibold text-xs sm:text-sm group-hover:bg-white group-hover:border-[#015425] transition">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-white group-hover:text-[#015425] transition">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-white text-opacity-80 group-hover:text-[#015425] group-hover:text-opacity-80 transition">{{ Auth::user()->email }}</p>
                                </div>
                                <svg class="w-4 h-4 text-white hidden md:block user-menu-arrow transition-transform group-hover:text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu - Hidden by default, shows on hover -->
                            <div id="user-menu-dropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-[#015425] to-[#027a3a]">
                                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-white text-opacity-90 truncate">{{ Auth::user()->email }}</p>
                                    @if(Auth::user()->membership_code)
                                    <p class="text-xs text-white text-opacity-75 mt-1">Code: {{ Auth::user()->membership_code }}</p>
                                    @endif
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('member.profile.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#015425] transition group">
                                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-[#015425] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        My Profile
                                    </a>
                                    <a href="{{ route('member.profile.settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#015425] transition group">
                                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-[#015425] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Settings
                                    </a>
                                </div>
                                <div class="border-t border-gray-200 py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Mobile Sidebar Menu -->
        <div id="mobile-sidebar" class="hidden lg:hidden fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] px-4 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg flex items-center justify-center mr-3 border border-white border-opacity-30">
                            <span class="text-[#015425] font-bold">FD</span>
                        </div>
                        <span class="text-lg font-bold text-white">FeedTan</span>
                    </div>
                    <button id="close-sidebar" class="text-white hover:bg-white hover:bg-opacity-20 hover:text-[#015425] p-2 rounded-md transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Sidebar Navigation -->
                <nav class="flex-1 px-4 py-4 overflow-y-auto">
                    @php
                        $isApproved = Auth::user()->membership_status === 'approved';
                    @endphp
                    
                    <a href="{{ route('member.dashboard') }}" class="flex items-center px-3 py-2 mb-2 rounded-md {{ request()->routeIs('member.dashboard') ? 'bg-white text-[#015425] font-semibold' : 'text-gray-700 hover:bg-green-50 hover:text-[#015425]' }} transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <!-- Loans Mobile -->
                    <div class="mobile-dropdown {{ !$isApproved ? 'opacity-50' : '' }} mb-2">
                        <button type="button" {{ !$isApproved ? 'disabled' : '' }} class="mobile-dropdown-toggle w-full flex items-center justify-between px-3 py-2 rounded-md {{ !$isApproved ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-green-50 hover:text-[#015425]' }} {{ request()->routeIs('member.loans.*') ? 'bg-white text-[#015425] font-semibold' : '' }} transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Loans</span>
                            </div>
                            @if($isApproved)
                            <svg class="w-4 h-4 mobile-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            @endif
                        </button>
                        @if($isApproved)
                        <div class="mobile-dropdown-menu hidden pl-4 mt-1 space-y-1">
                            <a href="{{ route('member.loans.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-green-50 hover:text-[#015425] transition">All Loans</a>
                            <a href="{{ route('member.loans.create') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-green-50 hover:text-[#015425] transition">Apply for Loan</a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Savings Mobile -->
                    <div class="mobile-dropdown {{ !$isApproved ? 'opacity-50' : '' }} mb-2">
                        <button type="button" {{ !$isApproved ? 'disabled' : '' }} class="mobile-dropdown-toggle w-full flex items-center justify-between px-3 py-2 rounded-md {{ !$isApproved ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-green-50 hover:text-[#015425]' }} {{ request()->routeIs('member.savings.*') ? 'bg-white text-[#015425] font-semibold' : '' }} transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Savings</span>
                            </div>
                            @if($isApproved)
                            <svg class="w-4 h-4 mobile-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            @endif
                        </button>
                        @if($isApproved)
                        <div class="mobile-dropdown-menu hidden pl-4 mt-1 space-y-1">
                            <a href="{{ route('member.savings.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-green-50 hover:text-[#015425] transition">All Accounts</a>
                            <a href="{{ route('member.savings.create') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-green-50 hover:text-[#015425] transition">Open Account</a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Investments Mobile -->
                    <div class="mobile-dropdown {{ !$isApproved ? 'opacity-50' : '' }} mb-2">
                        <button type="button" {{ !$isApproved ? 'disabled' : '' }} class="mobile-dropdown-toggle w-full flex items-center justify-between px-3 py-2 rounded-md {{ !$isApproved ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-green-50 hover:text-[#015425]' }} {{ request()->routeIs('member.investments.*') ? 'bg-white text-[#015425] font-semibold' : '' }} transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span>Investments</span>
                            </div>
                            @if($isApproved)
                            <svg class="w-4 h-4 mobile-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            @endif
                        </button>
                        @if($isApproved)
                        <div class="mobile-dropdown-menu hidden pl-4 mt-1 space-y-1">
                            <a href="{{ route('member.investments.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-green-50 hover:text-[#015425] transition">All Investments</a>
                            <a href="{{ route('member.investments.create') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-green-50 hover:text-[#015425] transition">Start Investment</a>
                        </div>
                        @endif
                    </div>
                    
                    <a href="{{ $isApproved ? route('member.welfare.index') : '#' }}" class="flex items-center px-3 py-2 mb-2 rounded-md {{ !$isApproved ? 'text-gray-400 cursor-not-allowed opacity-50' : 'text-gray-700 hover:bg-green-50 hover:text-[#015425]' }} {{ request()->routeIs('member.welfare.*') ? 'bg-white text-[#015425] font-semibold' : '' }} transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Welfare
                    </a>
                    
                    <!-- Issues Mobile -->
                    <div class="mobile-dropdown {{ !$isApproved ? 'opacity-50' : '' }} mb-2">
                        <button type="button" {{ !$isApproved ? 'disabled' : '' }} class="mobile-dropdown-toggle w-full flex items-center justify-between px-3 py-2 rounded-md {{ !$isApproved ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-green-50 hover:text-[#015425]' }} {{ request()->routeIs('member.issues.*') ? 'bg-white text-[#015425] font-semibold' : '' }} transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span>Issues</span>
                            </div>
                            @if($isApproved)
                            <svg class="w-4 h-4 mobile-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            @endif
                        </button>
                        @if($isApproved)
                        <div class="mobile-dropdown-menu hidden pl-4 mt-1 space-y-1">
                            <a href="{{ route('member.issues.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-green-50 hover:text-[#015425] transition">All Issues</a>
                            <a href="{{ route('member.issues.create') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-green-50 hover:text-[#015425] transition">Report Issue</a>
                        </div>
                        @endif
                    </div>
                </nav>
                
                <!-- Sidebar Footer -->
                <div class="px-4 py-4 border-t border-gray-200">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 flex items-center justify-center text-[#015425] font-semibold text-sm mr-3">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="hidden lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
        
        <!-- Main Content -->
        <main class="flex-1 flex flex-col pt-16">
            <div class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 w-full">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-auto w-full">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-4 text-xs sm:text-sm text-gray-600">
                        <span>&copy; {{ date('Y') }} FeedTan CMG. All rights reserved.</span>
                        <div class="flex items-center gap-2 sm:gap-4">
                            <span class="text-gray-400">Version 1.0.0</span>
                            <span class="hidden sm:inline text-gray-300">|</span>
                            <span class="hidden sm:inline">Powered by FeedTan Team</span>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>
    
    <script>
        (function() {
            'use strict';
            
            // Mobile Sidebar Toggle
            function initMobileSidebar() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileSidebar = document.getElementById('mobile-sidebar');
                const sidebarOverlay = document.getElementById('sidebar-overlay');
                const closeSidebar = document.getElementById('close-sidebar');
                
                function openSidebar() {
                    mobileSidebar.classList.remove('hidden');
                    mobileSidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
                
                function closeSidebarFunc() {
                    mobileSidebar.classList.add('-translate-x-full');
                    setTimeout(() => {
                        mobileSidebar.classList.add('hidden');
                    }, 300);
                    sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }
                
                if (mobileMenuButton) {
                    mobileMenuButton.addEventListener('click', openSidebar);
                }
                
                if (closeSidebar) {
                    closeSidebar.addEventListener('click', closeSidebarFunc);
                }
                
                if (sidebarOverlay) {
                    sidebarOverlay.addEventListener('click', closeSidebarFunc);
                }
            }
            
            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initMobileSidebar);
            } else {
                initMobileSidebar();
            }
        })();
        
        document.addEventListener('DOMContentLoaded', function() {
            // Notification dropdown - Hover on desktop, click on mobile
            const notificationContainer = document.querySelector('.notification-container');
            const notificationButton = document.getElementById('notification-button');
            const notificationDropdown = document.getElementById('notification-dropdown');
            let notificationClicked = false;
            
            if (notificationContainer && notificationDropdown) {
                // Desktop: hover to show, click to toggle
                if (window.innerWidth >= 1024) {
                    notificationContainer.addEventListener('mouseenter', function() {
                        if (!notificationClicked) {
                            notificationDropdown.classList.remove('hidden');
                        }
                    });
                    
                    notificationContainer.addEventListener('mouseleave', function() {
                        if (!notificationClicked) {
                            notificationDropdown.classList.add('hidden');
                        }
                    });
                    
                    // Click to toggle (stays open after click)
                    if (notificationButton) {
                        notificationButton.addEventListener('click', function(e) {
                            e.stopPropagation();
                            notificationClicked = !notificationClicked;
                            if (notificationClicked) {
                                notificationDropdown.classList.remove('hidden');
                            } else {
                                notificationDropdown.classList.add('hidden');
                            }
                        });
                    }
                } else {
                    // Mobile: click to toggle
                    if (notificationButton) {
                        notificationButton.addEventListener('click', function(e) {
                            e.stopPropagation();
                            notificationDropdown.classList.toggle('hidden');
                        });
                    }
                }
            }
            
            // User profile dropdown - Hover only on desktop
            const userProfileContainer = document.querySelector('.user-profile-container');
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            const userMenuArrow = document.querySelector('.user-menu-arrow');
            
            if (userProfileContainer && userMenuDropdown) {
                // Desktop: hover
                if (window.innerWidth >= 1024) {
                    userProfileContainer.addEventListener('mouseenter', function() {
                        userMenuDropdown.classList.remove('hidden');
                        if (userMenuArrow) userMenuArrow.classList.add('rotate-180');
                    });
                    
                    userProfileContainer.addEventListener('mouseleave', function() {
                        userMenuDropdown.classList.add('hidden');
                        if (userMenuArrow) userMenuArrow.classList.remove('rotate-180');
                    });
                }
                
                // Mobile: click
                if (userMenuButton) {
                    userMenuButton.addEventListener('click', function(e) {
                        if (window.innerWidth < 1024) {
                            e.stopPropagation();
                            userMenuDropdown.classList.toggle('hidden');
                            if (userMenuArrow) userMenuArrow.classList.toggle('rotate-180');
                        }
                    });
                }
            }
            
            // Desktop Navigation dropdowns - Hover on desktop (PC size), click on mobile
            const navDropdowns = document.querySelectorAll('.nav-dropdown');
            navDropdowns.forEach(function(dropdown) {
                const toggle = dropdown.querySelector('.nav-dropdown-toggle');
                const menu = dropdown.querySelector('.nav-dropdown-menu');
                const arrow = dropdown.querySelector('.nav-dropdown-arrow');
                let hoverTimeout;
                
                if (toggle && menu) {
                    // Function to close all dropdowns except the specified one
                    function closeOtherDropdowns(exceptDropdown) {
                        navDropdowns.forEach(function(otherDropdown) {
                            if (otherDropdown !== exceptDropdown) {
                                const otherMenu = otherDropdown.querySelector('.nav-dropdown-menu');
                                const otherArrow = otherDropdown.querySelector('.nav-dropdown-arrow');
                                if (otherMenu) otherMenu.classList.add('hidden');
                                if (otherArrow) otherArrow.classList.remove('rotate-180');
                            }
                        });
                    }
                    
                    // Desktop (lg and above - 1024px+): Hover behavior
                    function setupDesktopHover() {
                        dropdown.addEventListener('mouseenter', function() {
                            if (window.innerWidth >= 1024) {
                                if (hoverTimeout) {
                                    clearTimeout(hoverTimeout);
                                }
                                closeOtherDropdowns(dropdown);
                                menu.classList.remove('hidden');
                                if (arrow) arrow.classList.add('rotate-180');
                            }
                        });
                        
                        dropdown.addEventListener('mouseleave', function() {
                            if (window.innerWidth >= 1024) {
                                hoverTimeout = setTimeout(function() {
                                    menu.classList.add('hidden');
                                    if (arrow) arrow.classList.remove('rotate-180');
                                }, 150);
                            }
                        });
                    }
                    
                    // Mobile: Click behavior
                    function setupMobileClick() {
                        toggle.addEventListener('click', function(e) {
                            if (window.innerWidth < 1024) {
                                e.preventDefault();
                                e.stopPropagation();
                                
                                const isHidden = menu.classList.contains('hidden');
                                closeOtherDropdowns(dropdown);
                                
                                if (isHidden) {
                                    menu.classList.remove('hidden');
                                    if (arrow) arrow.classList.add('rotate-180');
                                } else {
                                    menu.classList.add('hidden');
                                    if (arrow) arrow.classList.remove('rotate-180');
                                }
                            }
                        });
                    }
                    
                    // Setup both behaviors
                    setupDesktopHover();
                    setupMobileClick();
                }
            });
            
            // Mobile dropdowns
            const mobileDropdowns = document.querySelectorAll('.mobile-dropdown');
            mobileDropdowns.forEach(function(dropdown) {
                const toggle = dropdown.querySelector('.mobile-dropdown-toggle');
                const menu = dropdown.querySelector('.mobile-dropdown-menu');
                const arrow = dropdown.querySelector('.mobile-dropdown-arrow');
                
                if (toggle && menu) {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Close all other mobile dropdowns
                        mobileDropdowns.forEach(function(otherDropdown) {
                            if (otherDropdown !== dropdown) {
                                const otherMenu = otherDropdown.querySelector('.mobile-dropdown-menu');
                                const otherArrow = otherDropdown.querySelector('.mobile-dropdown-arrow');
                                if (otherMenu) otherMenu.classList.add('hidden');
                                if (otherArrow) otherArrow.classList.remove('rotate-180');
                            }
                        });
                        
                        // Toggle current dropdown
                        menu.classList.toggle('hidden');
                        if (arrow) arrow.classList.toggle('rotate-180');
                    });
                }
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                // Close notification dropdown
                if (notificationContainer && notificationDropdown) {
                    if (window.innerWidth >= 1024) {
                        // Desktop: close if clicked outside and was clicked before
                        if (notificationClicked && !notificationContainer.contains(event.target)) {
                            notificationDropdown.classList.add('hidden');
                            notificationClicked = false;
                        }
                    } else {
                        // Mobile: close if clicked outside
                        if (notificationButton && !notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
                            notificationDropdown.classList.add('hidden');
                        }
                    }
                }
                
                // Close user dropdown (mobile only)
                if (window.innerWidth < 1024) {
                    if (userMenuDropdown && userMenuButton && !userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                        userMenuDropdown.classList.add('hidden');
                        if (userMenuArrow) {
                            userMenuArrow.classList.remove('rotate-180');
                        }
                    }
                }
            });
        });
    </script>
    
    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }
        
        .mobile-dropdown-arrow {
            transition: transform 0.2s ease-in-out;
        }
        
        .mobile-dropdown-arrow.rotate-180 {
            transform: rotate(180deg);
        }
        
        .mobile-dropdown-menu {
            animation: slideDown 0.2s ease-out;
        }
        
        .user-menu-arrow {
            transition: transform 0.2s ease-in-out;
        }
        
        .user-menu-arrow.rotate-180 {
            transform: rotate(180deg);
        }
        
        #user-menu-dropdown, #notification-dropdown {
            animation: slideDown 0.2s ease-out;
        }
        
        #mobile-sidebar {
            transform: translateX(-100%);
        }
        
        #mobile-sidebar:not(.hidden) {
            transform: translateX(0);
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    @stack('scripts')
    
    <!-- Quick Contact Widget -->
    @include('components.quick-contact-widget')
</body>
</html>
