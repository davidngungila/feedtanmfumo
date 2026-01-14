<!-- Success Notification Toast -->
<div id="success-notification" class="fixed top-4 right-4 z-50 hidden transform transition-all duration-300 ease-in-out">
    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg shadow-lg p-4 min-w-[300px] max-w-md">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-green-800" id="success-message">
                    {{ session('success') ?? 'Operation completed successfully!' }}
                </p>
            </div>
            <button onclick="closeNotification('success-notification')" class="ml-4 text-green-500 hover:text-green-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Error Notification Toast -->
<div id="error-notification" class="fixed top-4 right-4 z-50 hidden transform transition-all duration-300 ease-in-out">
    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg shadow-lg p-4 min-w-[300px] max-w-md">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-red-800" id="error-message">
                    {{ session('error') ?? 'An error occurred!' }}
                </p>
            </div>
            <button onclick="closeNotification('error-notification')" class="ml-4 text-red-500 hover:text-red-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
function showNotification(type, message) {
    const notificationId = type === 'success' ? 'success-notification' : 'error-notification';
    const notification = document.getElementById(notificationId);
    const messageElement = document.getElementById(type === 'success' ? 'success-message' : 'error-message');
    
    if (notification && messageElement) {
        messageElement.textContent = message;
        notification.classList.remove('hidden');
        notification.classList.add('translate-x-0', 'opacity-100');
        
        // Auto close after 5 seconds
        setTimeout(() => {
            closeNotification(notificationId);
        }, 5000);
    }
}

function closeNotification(notificationId) {
    const notification = document.getElementById(notificationId);
    if (notification) {
        notification.classList.add('hidden');
        notification.classList.remove('translate-x-0', 'opacity-100');
    }
}

// Show notification if there's a success/error message in session
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('success', '{{ session('success') }}');
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('error', '{{ session('error') }}');
    });
@endif
</script>

