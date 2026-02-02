@php
    $currentLocale = App::getLocale();
    $availableLocales = [
        'en' => ['name' => 'English', 'flag' => '🇬🇧'],
        'sw' => ['name' => 'Kiswahili', 'flag' => '🇹🇿'],
    ];
@endphp

<div class="language-switcher relative" id="languageSwitcher">
    <button 
        type="button"
        class="language-switcher-btn flex items-center space-x-2 px-3 py-2 rounded-md transition focus:outline-none focus:ring-2 focus:ring-[#015425] focus:ring-offset-2"
        id="languageSwitcherButton"
        aria-label="Switch Language">
        <span class="text-lg">{{ $availableLocales[$currentLocale]['flag'] ?? '🌐' }}</span>
        <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ $availableLocales[$currentLocale]['name'] ?? strtoupper($currentLocale) }}</span>
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div 
        id="languageDropdown" 
        class="language-dropdown hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 overflow-hidden">
        @foreach($availableLocales as $locale => $info)
        <a 
            href="{{ route('language.switch', $locale) }}" 
            class="language-option flex items-center space-x-3 px-4 py-3 hover:bg-green-50 transition {{ $currentLocale === $locale ? 'bg-green-50 text-[#015425] font-semibold' : 'text-gray-700' }}">
            <span class="text-xl">{{ $info['flag'] }}</span>
            <span class="flex-1">{{ $info['name'] }}</span>
            @if($currentLocale === $locale)
            <svg class="w-5 h-5 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            @endif
        </a>
        @endforeach
    </div>
</div>

<style>
    .language-switcher-btn {
        @apply bg-white border border-gray-300 hover:bg-gray-50;
    }
    
    .language-switcher-btn:hover {
        @apply border-[#015425];
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const switcher = document.getElementById('languageSwitcher');
        const button = document.getElementById('languageSwitcherButton');
        const dropdown = document.getElementById('languageDropdown');

        if (switcher && button && dropdown) {
            // Toggle dropdown
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!switcher.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            // Close dropdown on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    dropdown.classList.add('hidden');
                }
            });
        }
    });
</script>
