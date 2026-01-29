@extends('layouts.admin')

@section('page-title', 'Send SMS')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Send SMS</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Upload Excel file to send bulk SMS messages</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                <a href="{{ route('admin.sms.settings') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-[#015425] rounded-md hover:bg-opacity-30 transition font-medium">
                    SMS Settings
                </a>
                <a href="{{ route('admin.sms.templates') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-[#015425] rounded-md hover:bg-opacity-30 transition font-medium">
                    Templates
                </a>
                <a href="{{ route('admin.settings.communication') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to Communication
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto space-y-6">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4">
            <p class="text-sm text-green-800 whitespace-pre-line">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        @if(session('results'))
        @php $results = session('results'); @endphp
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
            <h3 class="font-semibold text-blue-900 mb-2">Sending Results:</h3>
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium">Total:</span> {{ $results['total'] }}
                </div>
                <div class="text-green-700">
                    <span class="font-medium">Success:</span> {{ $results['success'] }}
                </div>
                <div class="text-red-700">
                    <span class="font-medium">Failed:</span> {{ $results['failed'] }}
                </div>
            </div>
            @if(!empty($results['errors']))
            <div class="mt-3 max-h-60 overflow-y-auto">
                <p class="font-medium text-blue-900 mb-1">Errors:</p>
                <ul class="list-disc list-inside text-xs text-blue-800 space-y-1">
                    @foreach(array_slice($results['errors'], 0, 20) as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    @if(count($results['errors']) > 20)
                    <li>... and {{ count($results['errors']) - 20 }} more errors</li>
                    @endif
                </ul>
            </div>
            @endif
        </div>
        @endif

        <!-- Excel Upload Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Upload Excel File</h3>
            
            <form action="{{ route('admin.sms.send.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Excel File Upload -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="excel_file" class="block text-sm font-medium text-gray-700">
                            Excel File <span class="text-red-500">*</span>
                        </label>
                        <a href="{{ route('admin.sms.send.sample') }}" 
                           class="inline-flex items-center px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Sample Excel
                        </a>
                    </div>
                    <input type="file" 
                           name="excel_file" 
                           id="excel_file" 
                           accept=".xlsx,.xls,.csv"
                           required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#027a3a] transition">
                    <p class="mt-1 text-xs text-gray-500">
                        Required columns: <strong>Member ID</strong>, <strong>Full Name</strong>, <strong>Phone Number</strong>, <strong>Saving Behavior</strong>, <strong>Status</strong>
                    </p>
                </div>

                <!-- Template Selection -->
                <div>
                    <label for="template_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Template (Optional)
                    </label>
                    <select name="template_id" 
                            id="template_id" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="">-- Select Template --</option>
                        @foreach($templates as $template)
                        <option value="{{ $template->id }}" 
                                data-behavior="{{ $template->behavior_type }}"
                                data-content="{{ $template->message_content }}">
                            {{ $template->template_name }} 
                            @if($template->behavior_type)
                                ({{ $template->behavior_type }})
                            @endif
                        </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        If a template is selected, it will be used based on the Saving Behavior column in the Excel file.
                    </p>
                </div>

                <!-- Custom Message -->
                <div>
                    <label for="custom_message" class="block text-sm font-medium text-gray-700 mb-2">
                        Custom Message (Optional)
                    </label>
                    <textarea name="custom_message" 
                              id="custom_message" 
                              rows="4"
                              maxlength="500"
                              placeholder="Enter a custom message to send to all recipients..."
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]"></textarea>
                    <p class="mt-1 text-xs text-gray-500">
                        <span id="char_count">0</span>/500 characters. Use this if you don't want to use a template.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium shadow-md">
                        Upload & Send SMS
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Instructions</h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Excel File Format:</h4>
                    <ul class="list-disc list-inside space-y-1 ml-4">
                        <li><strong>Member ID:</strong> Member number or membership code</li>
                        <li><strong>Full Name:</strong> Full name of the member</li>
                        <li><strong>Phone Number:</strong> Phone number (required, format: 255XXXXXXXXX or 0XXXXXXXXX)</li>
                        <li><strong>Saving Behavior:</strong> Inconsistent Saver, Sporadic Saver, Non-Saver, or Regular Saver</li>
                        <li><strong>Status:</strong> Optional status field</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">How it works:</h4>
                    <ul class="list-disc list-inside space-y-1 ml-4">
                        <li>If a template is selected, the system will match it with the Saving Behavior column</li>
                        <li>If no template matches, the custom message will be used (if provided)</li>
                        <li>The system will try to find users by Member ID or Phone Number</li>
                        <li>Template variables like @{{name}}, @{{organization_name}} will be automatically replaced</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customMessage = document.getElementById('custom_message');
    const charCount = document.getElementById('char_count');
    const templateSelect = document.getElementById('template_id');

    // Character counter
    if (customMessage && charCount) {
        customMessage.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }

    // Template preview
    if (templateSelect) {
        templateSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value && selectedOption.dataset.content) {
                customMessage.value = selectedOption.dataset.content;
                charCount.textContent = customMessage.value.length;
            }
        });
    }
});
</script>
@endsection

