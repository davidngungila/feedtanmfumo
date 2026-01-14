<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Member Dashboard') - FEEDTAN DIGITAL</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <button type="button" id="mobile-menu-button" class="md:hidden mr-3 p-2 text-gray-600 hover:text-[#015425] hover:bg-gray-100 rounded-md transition focus:outline-none focus:ring-2 focus:ring-[#015425] focus:ring-offset-2">
                            <svg id="mobile-menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <a href="{{ route('member.dashboard') }}" class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-[#015425] to-[#027a3a] rounded-lg flex items-center justify-center mr-2 sm:mr-3">
                                <span class="text-white font-bold text-sm sm:text-lg">FD</span>
                            </div>
                            <span class="text-lg sm:text-xl font-bold text-[#015425]">FeedTan Digital</span>
                        </a>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex items-center space-x-4 lg:space-x-6">
                        <a href="{{ route('member.dashboard') }}" class="text-gray-700 hover:text-[#015425] transition {{ request()->routeIs('member.dashboard') ? 'text-[#015425] font-semibold' : '' }}">
                            Dashboard
                        </a>
                        
                        <!-- Loans Dropdown -->
                        <div class="relative nav-dropdown">
                            <button type="button" class="nav-dropdown-toggle flex items-center text-gray-700 hover:text-[#015425] transition {{ request()->routeIs('member.loans.*') ? 'text-[#015425] font-semibold' : '' }}">
                                Loans
                                <svg class="w-4 h-4 ml-1 nav-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-dropdown-menu hidden absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                <a href="{{ route('member.loans.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">All Loans</a>
                                <a href="{{ route('member.loans.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Apply for Loan</a>
                            </div>
                        </div>
                        
                        <!-- Savings Dropdown -->
                        <div class="relative nav-dropdown">
                            <button type="button" class="nav-dropdown-toggle flex items-center text-gray-700 hover:text-[#015425] transition {{ request()->routeIs('member.savings.*') ? 'text-[#015425] font-semibold' : '' }}">
                                Savings
                                <svg class="w-4 h-4 ml-1 nav-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-dropdown-menu hidden absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                <a href="{{ route('member.savings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">All Accounts</a>
                                <a href="{{ route('member.savings.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Open Account</a>
                            </div>
                        </div>
                        
                        <!-- Investments Dropdown -->
                        <div class="relative nav-dropdown">
                            <button type="button" class="nav-dropdown-toggle flex items-center text-gray-700 hover:text-[#015425] transition {{ request()->routeIs('member.investments.*') ? 'text-[#015425] font-semibold' : '' }}">
                                Investments
                                <svg class="w-4 h-4 ml-1 nav-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-dropdown-menu hidden absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                <a href="{{ route('member.investments.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">All Investments</a>
                                <a href="{{ route('member.investments.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Start Investment</a>
                            </div>
                        </div>
                        
                        <a href="{{ route('member.welfare.index') }}" class="text-gray-700 hover:text-[#015425] transition {{ request()->routeIs('member.welfare.*') ? 'text-[#015425] font-semibold' : '' }}">
                            Welfare
                        </a>
                        
                        <!-- Issues Dropdown -->
                        <div class="relative nav-dropdown">
                            <button type="button" class="nav-dropdown-toggle flex items-center text-gray-700 hover:text-[#015425] transition {{ request()->routeIs('member.issues.*') ? 'text-[#015425] font-semibold' : '' }}">
                                Issues
                                <svg class="w-4 h-4 ml-1 nav-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="nav-dropdown-menu hidden absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                <a href="{{ route('member.issues.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">All Issues</a>
                                <a href="{{ route('member.issues.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Report Issue</a>
                            </div>
                        </div>
                    </nav>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button id="notification-button" class="relative p-2 text-gray-600 hover:text-[#015425] hover:bg-gray-100 rounded-full transition focus:outline-none focus:ring-2 focus:ring-[#015425] focus:ring-offset-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <!-- Notification Dropdown -->
                            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 max-h-96 overflow-y-auto">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                </div>
                                <div class="py-2">
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                        No new notifications
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Profile Dropdown -->
                        <div class="relative user-profile-dropdown">
                            <button id="user-menu-button" class="flex items-center space-x-3 p-1 rounded-full hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-[#015425] focus:ring-offset-2">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#015425] to-[#027a3a] flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 hidden md:block user-menu-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="user-menu-dropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('member.profile.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        My Profile
                                    </a>
                                    <a href="{{ route('member.profile.settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white">
                <nav class="px-4 py-3 space-y-1">
                    <a href="{{ route('member.dashboard') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('member.dashboard') ? 'bg-gray-100 text-[#015425] font-semibold' : '' }}">
                        Dashboard
                    </a>
                    
                    <!-- Loans Mobile -->
                    <div class="mobile-dropdown">
                        <button type="button" class="mobile-dropdown-toggle w-full flex items-center justify-between px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('member.loans.*') ? 'bg-gray-100 text-[#015425] font-semibold' : '' }}">
                            <span>Loans</span>
                            <svg class="w-4 h-4 mobile-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="mobile-dropdown-menu hidden pl-4 mt-1 space-y-1">
                            <a href="{{ route('member.loans.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">All Loans</a>
                            <a href="{{ route('member.loans.create') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Apply for Loan</a>
                        </div>
                    </div>
                    
                    <!-- Savings Mobile -->
                    <div class="mobile-dropdown">
                        <button type="button" class="mobile-dropdown-toggle w-full flex items-center justify-between px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('member.savings.*') ? 'bg-gray-100 text-[#015425] font-semibold' : '' }}">
                            <span>Savings</span>
                            <svg class="w-4 h-4 mobile-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="mobile-dropdown-menu hidden pl-4 mt-1 space-y-1">
                            <a href="{{ route('member.savings.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">All Accounts</a>
                            <a href="{{ route('member.savings.create') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Open Account</a>
                        </div>
                    </div>
                    
                    <!-- Investments Mobile -->
                    <div class="mobile-dropdown">
                        <button type="button" class="mobile-dropdown-toggle w-full flex items-center justify-between px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('member.investments.*') ? 'bg-gray-100 text-[#015425] font-semibold' : '' }}">
                            <span>Investments</span>
                            <svg class="w-4 h-4 mobile-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="mobile-dropdown-menu hidden pl-4 mt-1 space-y-1">
                            <a href="{{ route('member.investments.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">All Investments</a>
                            <a href="{{ route('member.investments.create') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Start Investment</a>
                        </div>
                    </div>
                    
                    <a href="{{ route('member.welfare.index') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('member.welfare.*') ? 'bg-gray-100 text-[#015425] font-semibold' : '' }}">
                        Welfare
                    </a>
                    
                    <!-- Issues Mobile -->
                    <div class="mobile-dropdown">
                        <button type="button" class="mobile-dropdown-toggle w-full flex items-center justify-between px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('member.issues.*') ? 'bg-gray-100 text-[#015425] font-semibold' : '' }}">
                            <span>Issues</span>
                            <svg class="w-4 h-4 mobile-dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="mobile-dropdown-menu hidden pl-4 mt-1 space-y-1">
                            <a href="{{ route('member.issues.index') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">All Issues</a>
                            <a href="{{ route('member.issues.create') }}" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Report Issue</a>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        
        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
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
            
            function initMobileMenu() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');
                
                if (!mobileMenuButton || !mobileMenu) {
                    console.error('Mobile menu elements not found');
                    return;
                }
                
                // Remove any existing listeners
                const newButton = mobileMenuButton.cloneNode(true);
                mobileMenuButton.parentNode.replaceChild(newButton, mobileMenuButton);
                
                // Add click event
                newButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Mobile menu button clicked');
                    
                    if (mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.remove('hidden');
                        console.log('Menu shown');
                    } else {
                        mobileMenu.classList.add('hidden');
                        console.log('Menu hidden');
                    }
                });
            }
            
            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initMobileMenu);
            } else {
                initMobileMenu();
            }
        })();
        
        document.addEventListener('DOMContentLoaded', function() {
            
            // Notification dropdown toggle
            const notificationButton = document.getElementById('notification-button');
            const notificationDropdown = document.getElementById('notification-dropdown');
            
            if (notificationButton && notificationDropdown) {
                notificationButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    notificationDropdown.classList.toggle('hidden');
                });
            }
            
            // User menu toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            const userMenuArrow = document.querySelector('.user-menu-arrow');
            
            if (userMenuButton && userMenuDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = userMenuDropdown.classList.contains('hidden');
                    
                    // Close notification dropdown
                    if (notificationDropdown) {
                        notificationDropdown.classList.add('hidden');
                    }
                    
                    // Toggle user menu
                    userMenuDropdown.classList.toggle('hidden');
                    if (userMenuArrow) {
                        userMenuArrow.classList.toggle('rotate-180');
                    }
                });
            }
            
            // Desktop Navigation dropdowns - Hover and click support
            const navDropdowns = document.querySelectorAll('.nav-dropdown');
            navDropdowns.forEach(function(dropdown) {
                const toggle = dropdown.querySelector('.nav-dropdown-toggle');
                const menu = dropdown.querySelector('.nav-dropdown-menu');
                const arrow = dropdown.querySelector('.nav-dropdown-arrow');
                let isOpen = false;
                
                if (toggle && menu) {
                    // Click handler
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        isOpen = !isOpen;
                        
                        // Close all other dropdowns
                        navDropdowns.forEach(function(otherDropdown) {
                            if (otherDropdown !== dropdown) {
                                const otherMenu = otherDropdown.querySelector('.nav-dropdown-menu');
                                const otherArrow = otherDropdown.querySelector('.nav-dropdown-arrow');
                                if (otherMenu) {
                                    otherMenu.classList.add('hidden');
                                    otherMenu.dataset.forceOpen = 'false';
                                }
                                if (otherArrow) otherArrow.classList.remove('rotate-180');
                            }
                        });
                        
                        // Toggle current dropdown
                        if (isOpen) {
                            menu.classList.remove('hidden');
                            menu.dataset.forceOpen = 'true';
                            if (arrow) arrow.classList.add('rotate-180');
                        } else {
                            menu.classList.add('hidden');
                            menu.dataset.forceOpen = 'false';
                            if (arrow) arrow.classList.remove('rotate-180');
                        }
                    });
                    
                    // Hover handlers for desktop only
                    if (window.innerWidth >= 768) {
                        dropdown.addEventListener('mouseenter', function() {
                            menu.classList.remove('hidden');
                            if (arrow) arrow.classList.add('rotate-180');
                        });
                        
                        dropdown.addEventListener('mouseleave', function() {
                            // Only hide if not force opened by click
                            if (menu.dataset.forceOpen !== 'true') {
                                menu.classList.add('hidden');
                                if (arrow) arrow.classList.remove('rotate-180');
                            }
                        });
                    }
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
                // Close mobile menu
                if (mobileMenu && mobileMenuButton && !mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                }
                
                // Close notification dropdown
                if (notificationDropdown && notificationButton && !notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
                    notificationDropdown.classList.add('hidden');
                }
                
                // Close user dropdown
                if (userMenuDropdown && userMenuButton && !userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                    userMenuDropdown.classList.add('hidden');
                    if (userMenuArrow) {
                        userMenuArrow.classList.remove('rotate-180');
                    }
                }
                
                // Close desktop navigation dropdowns if clicked outside
                navDropdowns.forEach(function(dropdown) {
                    if (!dropdown.contains(event.target)) {
                        const menu = dropdown.querySelector('.nav-dropdown-menu');
                        const arrow = dropdown.querySelector('.nav-dropdown-arrow');
                        if (menu && menu.dataset.forceOpen === 'true') {
                            menu.classList.add('hidden');
                            menu.dataset.forceOpen = 'false';
                            if (arrow) arrow.classList.remove('rotate-180');
                        }
                    }
                });
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
        
        #user-menu-dropdown {
            animation: slideDown 0.2s ease-out;
        }
        
        #notification-dropdown {
            animation: slideDown 0.2s ease-out;
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
</body>
</html>

