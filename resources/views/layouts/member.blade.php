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
<body class="h-full bg-[#fafafa] text-[#41546b] overflow-hidden">
    @php
        $isApproved = Auth::user()->membership_status === 'approved';
        $isDashboard = request()->routeIs('member.dashboard');
        $isActiveLoans = request()->routeIs('member.loans.*');
        $isActiveSavings = request()->routeIs('member.savings.*') || request()->routeIs('member.monthly-deposits.*');
        $isActiveInvestments = request()->routeIs('member.investments.*');
        $isActiveIssues = request()->routeIs('member.issues.*');
        $isActiveWelfare = request()->routeIs('member.welfare.*');
        $isActivePaymentConfirmations = request()->routeIs('member.payment-confirmations.*');
    @endphp
    <div class="h-full flex flex-col lg:flex-row">
        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#015425] text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col h-full">
            <div class="p-6 border-b border-[#013019]">
                <h1 class="text-2xl font-bold text-white">FeedTan Digital</h1>
                <button id="close-sidebar" class="lg:hidden absolute top-4 right-4 text-white hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-4 space-y-1">
                <a href="{{ route('member.dashboard') }}" class="flex items-center w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isDashboard ? 'bg-[#013019]' : '' }}">
                    <span class="text-lg mr-3">📊</span>
                    <span>Dashboard</span>
                </a>

                <div class="dropdown-container" data-menu="loans">
                    <button {{ !$isApproved ? 'disabled' : '' }} class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveLoans ? 'bg-[#013019]' : '' }} {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isApproved ? 'title="Membership must be approved to access Loans"' : '' }}>
                        <div class="flex items-center">
                            <span class="text-lg mr-3">💳</span>
                            <span>Loans</span>
                        </div>
                        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveLoans ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    @if($isApproved)
                    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveLoans ? '' : 'hidden' }}">
                        <a href="{{ route('member.loans.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Loans</a>
                        <a href="{{ route('member.loans.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Apply for Loan</a>
                    </div>
                    @endif
                </div>

                <div class="dropdown-container" data-menu="savings">
                    <button {{ !$isApproved ? 'disabled' : '' }} class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveSavings ? 'bg-[#013019]' : '' }} {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isApproved ? 'title="Membership must be approved to access Savings"' : '' }}>
                        <div class="flex items-center">
                            <span class="text-lg mr-3">💰</span>
                            <span>Savings</span>
                        </div>
                        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveSavings ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    @if($isApproved)
                    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveSavings ? '' : 'hidden' }}">
                        <a href="{{ route('member.savings.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Accounts</a>
                        <a href="{{ route('member.savings.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Open Account</a>
                        <a href="{{ route('member.monthly-deposits.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Deposit Statements</a>
                    </div>
                    @endif
                </div>

                <div class="dropdown-container" data-menu="investments">
                    <button {{ !$isApproved ? 'disabled' : '' }} class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveInvestments ? 'bg-[#013019]' : '' }} {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isApproved ? 'title="Membership must be approved to access Investments"' : '' }}>
                        <div class="flex items-center">
                            <span class="text-lg mr-3">📈</span>
                            <span>Investments</span>
                        </div>
                        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveInvestments ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    @if($isApproved)
                    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveInvestments ? '' : 'hidden' }}">
                        <a href="{{ route('member.investments.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Investments</a>
                        <a href="{{ route('member.investments.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Start Investment</a>
                    </div>
                    @endif
                </div>

                <a href="{{ $isApproved ? route('member.welfare.index') : '#' }}" class="flex items-center w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveWelfare ? 'bg-[#013019]' : '' }} {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isApproved ? 'title="Membership must be approved to access Welfare"' : '' }}>
                    <span class="text-lg mr-3">🫶</span>
                    <span>Welfare</span>
                </a>

                <div class="dropdown-container" data-menu="issues">
                    <button {{ !$isApproved ? 'disabled' : '' }} class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActiveIssues ? 'bg-[#013019]' : '' }} {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isApproved ? 'title="Membership must be approved to access Issues"' : '' }}>
                        <div class="flex items-center">
                            <span class="text-lg mr-3">⚙️</span>
                            <span>Issues</span>
                        </div>
                        <svg class="w-4 h-4 dropdown-arrow transition-transform {{ $isActiveIssues ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    @if($isApproved)
                    <div class="dropdown-menu pl-4 mt-1 space-y-1 {{ $isActiveIssues ? '' : 'hidden' }}">
                        <a href="{{ route('member.issues.index') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">All Issues</a>
                        <a href="{{ route('member.issues.create') }}" class="block px-4 py-2 rounded-md hover:bg-[#013019] transition text-sm">Report Issue</a>
                    </div>
                    @endif
                </div>

                <a href="{{ $isApproved ? route('member.payment-confirmations.index') : '#' }}" class="flex items-center w-full px-4 py-3 rounded-md hover:bg-[#013019] transition {{ $isActivePaymentConfirmations ? 'bg-[#013019]' : '' }} {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isApproved ? 'title="Membership must be approved to access Payment Verification"' : '' }}>
                    <span class="text-lg mr-3">✅</span>
                    <span>Payment Verification</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
            <header class="fixed top-0 left-0 right-0 bg-white shadow-sm z-30 lg:left-64">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button id="menu-toggle" class="lg:hidden mr-4 text-[#015425] hover:text-[#013019]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h2 class="text-xl lg:text-2xl font-bold text-[#015425]">@yield('page-title', 'Member Dashboard')</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div id="notification-container" class="relative">
                            <button id="notification-button" class="relative p-2 text-gray-600 hover:text-[#015425] hover:bg-gray-100 rounded-full transition focus:outline-none focus:ring-2 focus:ring-[#015425] focus:ring-offset-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span id="notification-badge" class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 max-h-96 overflow-y-auto">
                                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                </div>
                                <div class="py-2">
                                    <div class="px-4 py-3 text-center text-gray-500 text-sm">
                                        No new notifications
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="user-menu-container" class="relative user-profile-dropdown">
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
            </header>

            <main class="flex-1 overflow-y-auto pt-16 flex flex-col">
                <div class="flex-1 p-4 sm:p-6 lg:p-8">
                    @include('components.alerts')
                    @yield('content')
                </div>
                <footer class="bg-white border-t border-gray-200 mt-auto">
                    <div class="px-4 sm:px-6 lg:px-8 py-3">
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
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');

            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                });
            }

            if (closeSidebar) {
                closeSidebar.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }

            const dropdownContainers = document.querySelectorAll('.dropdown-container');
            dropdownContainers.forEach(container => {
                const toggleButton = container.querySelector('.dropdown-toggle');
                const menu = container.querySelector('.dropdown-menu');
                const arrow = container.querySelector('.dropdown-arrow');

                if (toggleButton) {
                    toggleButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (!menu || !arrow) return;
                        const isHidden = menu.classList.contains('hidden');

                        dropdownContainers.forEach(otherContainer => {
                            if (otherContainer !== container) {
                                const otherMenu = otherContainer.querySelector('.dropdown-menu');
                                const otherArrow = otherContainer.querySelector('.dropdown-arrow');
                                if (otherMenu && otherArrow) {
                                    otherMenu.classList.add('hidden');
                                    otherArrow.classList.remove('rotate-180');
                                }
                            }
                        });

                        if (isHidden) {
                            menu.classList.remove('hidden');
                            arrow.classList.add('rotate-180');
                        } else {
                            menu.classList.add('hidden');
                            arrow.classList.remove('rotate-180');
                        }
                    });
                }
            });

            const notificationButton = document.getElementById('notification-button');
            const notificationDropdown = document.getElementById('notification-dropdown');
            const notificationContainer = document.getElementById('notification-container');

            if (notificationButton && notificationDropdown && notificationContainer) {
                let hoverTimeout;
                function showDropdown() {
                    if (hoverTimeout) clearTimeout(hoverTimeout);
                    document.getElementById('user-menu-dropdown')?.classList.add('hidden');
                    notificationDropdown.classList.remove('hidden');
                }
                function hideDropdown() {
                    hoverTimeout = setTimeout(function() {
                        notificationDropdown.classList.add('hidden');
                    }, 150);
                }

                notificationContainer.addEventListener('mouseenter', showDropdown);
                notificationContainer.addEventListener('mouseleave', hideDropdown);
            }

            const userMenuContainer = document.getElementById('user-menu-container');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            const userMenuArrow = document.querySelector('.user-menu-arrow');

            if (userMenuContainer && userMenuDropdown) {
                let userHoverTimeout;
                function showUserDropdown() {
                    if (userHoverTimeout) clearTimeout(userHoverTimeout);
                    notificationDropdown?.classList.add('hidden');
                    userMenuDropdown.classList.remove('hidden');
                    userMenuArrow?.classList.add('rotate-180');
                }
                function hideUserDropdown() {
                    userHoverTimeout = setTimeout(function() {
                        userMenuDropdown.classList.add('hidden');
                        userMenuArrow?.classList.remove('rotate-180');
                    }, 150);
                }

                userMenuContainer.addEventListener('mouseenter', showUserDropdown);
                userMenuContainer.addEventListener('mouseleave', hideUserDropdown);
            }
        });
    </script>
</body>
</html>
