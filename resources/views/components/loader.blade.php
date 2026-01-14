<div class="grid place-content-center h-screen">
    <div class="flex items-center justify-center gap-5">
        <div class="emaoni-loader"></div>
        <div class="flex flex-col items-center gap-1">
            <div class="text-xl font-bold text-gradient-emaoni">
                @if(isset($title))
                    {{ $title }}
                @else
                    Welcome to FEEDTAN DIGITAL!
                @endif
            </div>
            <div class="text-xs">
                @if(isset($message))
                    {{ $message }}
                @else
                    The system is starting, please wait...
                @endif
            </div>
        </div>
    </div>
</div>

