{{-- Standard Header Partial for System Settings Pages --}}
@props(['title', 'description', 'backRoute' => 'admin.settings.index', 'actions' => []])

<div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
    <div class="flex flex-col md:flex-row md:items-center">
        <div class="flex-1">
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">{{ $title }}</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">{{ $description }}</p>
        </div>
        <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
            @foreach($actions as $action)
                <a href="{{ $action['route'] }}" class="inline-flex items-center px-6 py-3 {{ $action['class'] ?? 'bg-white text-[#015425] hover:bg-gray-100' }} rounded-md transition font-medium shadow-md">
                    @if(isset($action['icon']))
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $action['icon'] !!}
                        </svg>
                    @endif
                    {{ $action['label'] }}
                </a>
            @endforeach
            <a href="{{ route($backRoute) }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-[#015425] rounded-md transition font-medium">
                Back to Settings
            </a>
        </div>
    </div>
</div>

