@php
    $isMemberLayout = request()->is('member/*');
@endphp
<div class="language-switcher relative" id="language-switcher">
    <button 
        id="language-switcher-button"
        class="flex items-center gap-2 px-3 py-2 rounded-lg {{ $isMemberLayout ? 'bg-white bg-opacity-20 backdrop-blur-sm border border-white border-opacity-30 hover:bg-white hover:bg-opacity-30 text-white' : 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300' }} transition-colors shadow-sm"
        title="{{ __('common.switch_language') }}"
    >
        <svg class="w-5 h-5 {{ $isMemberLayout ? 'text-white' : 'text-[#015425]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
        </svg>
        <span class="text-sm font-medium {{ $isMemberLayout ? 'text-white' : 'text-gray-700 dark:text-gray-300' }}">
            {{ app()->getLocale() === 'sw' ? 'Kiswahili' : 'English' }}
        </span>
        <svg class="w-4 h-4 {{ $isMemberLayout ? 'text-white' : 'text-gray-500' }} transition-transform" id="language-switcher-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div 
        id="language-switcher-dropdown"
        class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 overflow-hidden"
    >
        <a 
            href="{{ route('language.switch', 'en') }}"
            class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ app()->getLocale() === 'en' ? 'bg-green-50 dark:bg-green-900/20 text-[#015425] dark:text-green-400 font-medium' : 'text-gray-700 dark:text-gray-300' }}"
        >
            <span class="text-lg">🇬🇧</span>
            <span>English</span>
            @if(app()->getLocale() === 'en')
                <svg class="w-4 h-4 ml-auto text-[#015425]" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            @endif
        </a>
        <a 
            href="{{ route('language.switch', 'sw') }}"
            class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ app()->getLocale() === 'sw' ? 'bg-green-50 dark:bg-green-900/20 text-[#015425] dark:text-green-400 font-medium' : 'text-gray-700 dark:text-gray-300' }}"
        >
            <span class="text-lg">🇹🇿</span>
            <span>Kiswahili</span>
            @if(app()->getLocale() === 'sw')
                <svg class="w-4 h-4 ml-auto text-[#015425]" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            @endif
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const switcher = document.getElementById('language-switcher');
        const button = document.getElementById('language-switcher-button');
        const dropdown = document.getElementById('language-switcher-dropdown');
        const arrow = document.getElementById('language-switcher-arrow');
        
        if (!switcher || !button || !dropdown) {
            return;
        }
        
        let isOpen = false;
        let timeout;
        
        function toggleDropdown() {
            isOpen = !isOpen;
            if (isOpen) {
                dropdown.classList.remove('hidden');
                if (arrow) {
                    arrow.classList.add('rotate-180');
                }
            } else {
                dropdown.classList.add('hidden');
                if (arrow) {
                    arrow.classList.remove('rotate-180');
                }
            }
        }
        
        function closeDropdown() {
            if (isOpen) {
                isOpen = false;
                dropdown.classList.add('hidden');
                if (arrow) {
                    arrow.classList.remove('rotate-180');
                }
            }
        }
        
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleDropdown();
        });
        
        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!switcher.contains(e.target)) {
                closeDropdown();
            }
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDropdown();
            }
        });
    });
</script>
