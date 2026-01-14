<!-- Modal Component -->
<div id="{{ $id ?? 'modal' }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4" onclick="event.target === this && closeModal('{{ $id ?? 'modal' }}')">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-xl sm:text-2xl font-bold text-[#015425]">{{ $title ?? 'Details' }}</h3>
            <button type="button" onclick="closeModal('{{ $id ?? 'modal' }}')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6">
            {{ $slot }}
        </div>
        
        <!-- Modal Footer -->
        @if(isset($footer))
            <div class="p-4 sm:p-6 border-t border-gray-200">
                {{ $footer }}
            </div>
        @else
            <div class="p-4 sm:p-6 border-t border-gray-200 flex justify-end">
                <button type="button" onclick="closeModal('{{ $id ?? 'modal' }}')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Close
                </button>
            </div>
        @endif
    </div>
</div>

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[id$="modal"]');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                closeModal(modal.id);
            }
        });
    }
});
</script>

