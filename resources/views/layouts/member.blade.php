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
                            <button type="button" {{ !$isApproved ? 'disabled' : '' }} class="nav-dropdown-toggle flex items-center px-3 py-2 rounded-md transition {{ request()->routeIs('member.savings.*') || request()->routeIs('member.monthly-deposits.*') ? 'bg-white font-semibold text-[#015425]' : 'bg-[#015425] text-white hover:bg-[#027a3a]' }}" {{ !$isApproved ? 'title="Membership must be approved to access Savings"' : '' }}>
                                Savings
                                <svg class="w-4 h-4 ml-1 nav-dropdown-arrow transition-transform {{ request()->routeIs('member.savings.*') || request()->routeIs('member.monthly-deposits.*') ? 'text-[#015425]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            @if($isApproved)
                            <div class="nav-dropdown-menu hidden absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <a href="{{ route('member.savings.index') }}" class="block px-4 py-2 text-sm text-[#015425] hover:bg-green-50 hover:text-[#015425] transition">All Accounts</a>
                                <a href="{{ route('member.savings.create') }}" class="block px-4 py-2 text-sm text-[#015425] hover:bg-green-50 hover:text-[#015425] transition">Open Account</a>
                                <a href="{{ route('member.monthly-deposits.index') }}" class="block px-4 py-2 text-sm font-bold text-orange-600 hover:bg-orange-50 transition border-t border-gray-100 mt-1 pt-2">Deposit Statements</a>
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

                        <a href="{{ $isApproved ? route('member.payment-confirmations.index') : '#' }}" class="px-3 py-2 rounded-md transition {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }} {{ request()->routeIs('member.payment-confirmations.*') ? 'bg-white font-semibold text-[#015425]' : 'bg-[#015425] text-white hover:bg-[#027a3a]' }}" {{ !$isApproved ? 'title="Membership must be approved to access Payment Verification"' : '' }}>
                            Payment Verification
                        </a>
                    </nav>
                    
                    <div class="flex items-center space-x-3 sm:space-x-4 flex-shrink-0 ml-auto">
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
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#015425] transition group">
                                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-[#015425] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Account Settings
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition group">
                                            <svg class="w-4 h-4 mr-3 text-red-400 group-hover:text-red-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Navigation Menu - Enhanced with Slide Animation -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-white border-opacity-10 py-3 bg-[#013019] shadow-inner">
                <div class="px-4 space-y-1">
                    <a href="{{ route('member.dashboard') }}" class="block px-3 py-2 rounded-md transition {{ request()->routeIs('member.dashboard') ? 'bg-white text-[#015425] font-bold shadow-md' : 'text-white hover:bg-white hover:bg-opacity-10' }}">
                        Dashboard
                    </a>
                    
                    @if($isApproved)
                    <div class="border-t border-white border-opacity-5 my-2"></div>
                    
                    <!-- Mobile Loans -->
                    <div class="space-y-1">
                        <p class="px-3 py-1 text-[10px] uppercase tracking-widest text-white text-opacity-50 font-bold">Loans</p>
                        <a href="{{ route('member.loans.index') }}" class="block px-3 py-2 rounded-md transition {{ request()->routeIs('member.loans.index') ? 'bg-white text-[#015425] font-bold' : 'text-white hover:bg-white hover:bg-opacity-10' }}">All Loans</a>
                        <a href="{{ route('member.loans.create') }}" class="block px-3 py-2 rounded-md transition {{ request()->routeIs('member.loans.create') ? 'bg-white text-[#015425] font-bold' : 'text-white hover:bg-white hover:bg-opacity-10' }}">Apply for Loan</a>
                    </div>
                    
                    <div class="border-t border-white border-opacity-5 my-2"></div>
                    
                    <!-- Mobile Savings -->
                    <div class="space-y-1">
                        <p class="px-3 py-1 text-[10px] uppercase tracking-widest text-white text-opacity-50 font-bold">Savings</p>
                        <a href="{{ route('member.savings.index') }}" class="block px-3 py-2 rounded-md transition {{ request()->routeIs('member.savings.index') ? 'bg-white text-[#015425] font-bold' : 'text-white hover:bg-white hover:bg-opacity-10' }}">All Accounts</a>
                        <a href="{{ route('member.savings.create') }}" class="block px-3 py-2 rounded-md transition {{ request()->routeIs('member.savings.create') ? 'bg-white text-[#015425] font-bold' : 'text-white hover:bg-white hover:bg-opacity-10' }}">Open Account</a>
                    </div>
                    
                    <div class="border-t border-white border-opacity-5 my-2"></div>
                    
                    <!-- Mobile Investments -->
                    <div class="space-y-1">
                        <p class="px-3 py-1 text-[10px] uppercase tracking-widest text-white text-opacity-50 font-bold">Investments</p>
                        <a href="{{ route('member.investments.index') }}" class="block px-3 py-2 rounded-md transition {{ request()->routeIs('member.investments.index') ? 'bg-white text-[#015425] font-bold' : 'text-white hover:bg-white hover:bg-opacity-10' }}">All Investments</a>
                        <a href="{{ route('member.investments.create') }}" class="block px-3 py-2 rounded-md transition {{ request()->routeIs('member.investments.create') ? 'bg-white text-[#015425] font-bold' : 'text-white hover:bg-white hover:bg-opacity-10' }}">Start Investment</a>
                    </div>

                    <div class="border-t border-white border-opacity-5 my-2"></div>

                    <!-- Mobile Payment Verification -->
                    <a href="{{ route('member.payment-confirmations.index') }}" class="block px-3 py-2 rounded-md transition {{ request()->routeIs('member.payment-confirmations.*') ? 'bg-white text-[#015425] font-bold' : 'text-white hover:bg-white hover:bg-opacity-10' }}">
                        Payment Verification
                    </a>
                    
                    <div class="border-t border-white border-opacity-5 my-2"></div>
                    
                    <a href="{{ route('member.welfare.index') }}" class="block px-3 py-2 rounded-md transition {{ request()->routeIs('member.welfare.*') ? 'bg-white text-[#015425] font-bold' : 'text-white hover:bg-white hover:bg-opacity-10' }}">
                        Welfare Fund
                    </a>
                    
                    <div class="border-t border-white border-opacity-5 my-2"></div>
                    
                    <div class="space-y-1">
                        <p class="px-3 py-1 text-[10px] uppercase tracking-widest text-white text-opacity-50 font-bold">Help & Support</p>
                        <a href="{{ route('member.issues.index') }}" class="block px-3 py-2 rounded-md transition {{ request()->routeIs('member.issues.index') ? 'bg-white text-[#015425] font-bold' : 'text-white hover:bg-white hover:bg-opacity-10' }}">View Issues</a>
                        <a href="{{ route('member.issues.create') }}" class="block px-3 py-2 rounded-md transition {{ request()->routeIs('member.issues.create') ? 'bg-white text-[#015425] font-bold' : 'text-white hover:bg-white hover:bg-opacity-10' }}">Report Issue</a>
                    </div>
                    @else
                    <div class="p-3 bg-white bg-opacity-10 rounded-md text-white text-xs text-center italic">
                        Access restricted until membership is approved
                    </div>
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-grow pt-16">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <!-- Page Breadcrumbs/Header -->
                @if(!request()->routeIs('member.dashboard'))
                <nav class="flex mb-5" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li>
                            <div class="flex items-center">
                                <a href="{{ route('member.dashboard') }}" class="text-xs sm:text-sm font-medium text-gray-400 hover:text-[#015425] transition">Dashboard</a>
                            </div>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <svg class="flex-shrink-0 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                            </svg>
                            <span class="ml-2 text-xs sm:text-sm font-bold text-[#015425]">@yield('page-title')</span>
                        </li>
                    </ol>
                </nav>
                @endif
                
                @include('components.alerts')
                @yield('content')
            </div>
        </main>

        <!-- Dynamic and Modern Footer -->
        <footer class="bg-white border-t border-gray-100 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start">
                             <div class="w-8 h-8 bg-[#015425] rounded-md flex items-center justify-center mr-2">
                                <span class="text-white font-bold text-xs">FD</span>
                            </div>
                            <span class="text-lg font-bold text-[#015425]">FeedTan Digital</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Empowering Growth through Digital Finance.</p>
                    </div>
                    
                    <div class="flex justify-center space-x-6">
                        <a href="#" class="text-gray-400 hover:text-[#015425] transition">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-[#015425] transition">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-[#015425] transition">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0H5C2.24 0 0 2.24 0 5v14c0 2.76 2.24 5 5 5h14c2.76 0 5-2.24 5-5V5c0-2.76-2.24-5-5-5zM8 19H5V10h3v9zM6.5 8.25c-.96 0-1.75-.79-1.75-1.75S5.54 4.75 6.5 4.75s1.75.79 1.75 1.75-.79 1.75-1.75 1.75zM19 19h-3v-4.74c0-1.42-.6-2.39-1.93-2.39-1.07 0-1.61.72-1.89 1.42-.1.24-.13.57-.13.9v4.81h-3V10h3v1.38c.4-.62 1.12-1.5 2.72-1.5 1.99 0 3.48 1.3 3.48 4.09V19z"/></svg>
                        </a>
                    </div>
                    
                    <div class="text-center md:text-right">
                        <p class="text-xs text-gray-400">&copy; {{ date('Y') }} FEEDTAN DIGITAL. All Rights Reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Enhanced Layout Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuIcon = document.getElementById('mobile-menu-icon');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                    // Change icon based on state
                    if (mobileMenu.classList.contains('hidden')) {
                        mobileMenuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
                    } else {
                        mobileMenuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
                    }
                });
            }

            // Dropdown Menu Handling (Hover Simulation for better UX)
            const dropdownContainers = document.querySelectorAll('.nav-dropdown, .notification-container, .user-profile-container');
            
            dropdownContainers.forEach(container => {
                const trigger = container.querySelector('button');
                const menu = container.querySelector('div[id$="-dropdown"], .nav-dropdown-menu');
                const arrow = container.querySelector('.nav-dropdown-arrow, .user-menu-arrow');
                
                if (trigger && menu) {
                    let timeout;

                    const showMenu = () => {
                        clearTimeout(timeout);
                        menu.classList.remove('hidden');
                        if (arrow) arrow.style.transform = 'rotate(180deg)';
                    };

                    const hideMenu = () => {
                        timeout = setTimeout(() => {
                            menu.classList.add('hidden');
                            if (arrow) arrow.style.transform = 'rotate(0deg)';
                        }, 200);
                    };

                    container.addEventListener('mouseenter', showMenu);
                    container.addEventListener('mouseleave', hideMenu);
                    
                    // Also support click for touch devices
                    trigger.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const isHidden = menu.classList.contains('hidden');
                        
                        // Close all other menus first
                        dropdownContainers.forEach(c => {
                             const m = c.querySelector('div[id$="-dropdown"], .nav-dropdown-menu');
                             const a = c.querySelector('.nav-dropdown-arrow, .user-menu-arrow');
                             if (m) m.classList.add('hidden');
                             if (a) a.style.transform = 'rotate(0deg)';
                        });
                        
                        if (isHidden) {
                            menu.classList.remove('hidden');
                            if (arrow) arrow.style.transform = 'rotate(180deg)';
                        }
                    });
                }
            });

            // Close menus on outside click
            document.addEventListener('click', () => {
                dropdownContainers.forEach(container => {
                    const menu = container.querySelector('div[id$="-dropdown"], .nav-dropdown-menu');
                    const arrow = container.querySelector('.nav-dropdown-arrow, .user-menu-arrow');
                    if (menu) menu.classList.add('hidden');
                    if (arrow) arrow.style.transform = 'rotate(0deg)';
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
