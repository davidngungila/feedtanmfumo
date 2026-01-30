@extends('layouts.admin')

@section('page-title', 'Add New SMS Template')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Add New SMS Template</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Create a new SMS message template for your communications</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.sms.templates') }}" 
                   class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Templates
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
            <ul class="list-disc list-inside text-sm text-red-800">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Template Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.sms.templates.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="template_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Template Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="template_name" 
                           name="template_name" 
                           value="{{ old('template_name') }}"
                           required
                           placeholder="e.g., Welcome Message, Payment Reminder"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    <p class="mt-1 text-xs text-gray-500">A descriptive name for this template</p>
                </div>

                <div>
                    <label for="behavior_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Behavior Type
                    </label>
                    <select id="behavior_type" 
                            name="behavior_type"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="">-- Select Behavior Type (Optional) --</option>
                        <option value="Inconsistent Saver" {{ old('behavior_type') === 'Inconsistent Saver' ? 'selected' : '' }}>Inconsistent Saver</option>
                        <option value="Sporadic Saver" {{ old('behavior_type') === 'Sporadic Saver' ? 'selected' : '' }}>Sporadic Saver</option>
                        <option value="Non-Saver" {{ old('behavior_type') === 'Non-Saver' ? 'selected' : '' }}>Non-Saver</option>
                        <option value="Regular Saver" {{ old('behavior_type') === 'Regular Saver' ? 'selected' : '' }}>Regular Saver</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Target saving behavior for this template</p>
                </div>

                <div>
                    <label for="message_content" class="block text-sm font-medium text-gray-700 mb-2">
                        Message Content <span class="text-red-500">*</span>
                    </label>
                    <textarea id="message_content" 
                              name="message_content" 
                              rows="8"
                              required
                              placeholder="Enter your SMS message here. Use variables like {{name}}, {{amount}}, etc."
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">{{ old('message_content') }}</textarea>
                    <div class="mt-2 flex items-start justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">
                                Available variables: <code class="bg-gray-100 px-1 py-0.5 rounded">@{{name}}</code>, 
                                <code class="bg-gray-100 px-1 py-0.5 rounded">@{{full_name}}</code>, 
                                <code class="bg-gray-100 px-1 py-0.5 rounded">@{{organization_name}}</code>, 
                                <code class="bg-gray-100 px-1 py-0.5 rounded">@{{amount}}</code>, 
                                <code class="bg-gray-100 px-1 py-0.5 rounded">@{{member_id}}</code>
                            </p>
                            <p class="text-xs text-gray-500">
                                These will be replaced with actual values when sending SMS
                            </p>
                        </div>
                        <span id="message_char_count" class="text-xs text-gray-500 font-medium">0 / 160 characters</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-2">
                            Language <span class="text-red-500">*</span>
                        </label>
                        <select id="language" 
                                name="language" 
                                required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                            <option value="sw" {{ old('language', 'sw') === 'sw' ? 'selected' : '' }}>Swahili (sw)</option>
                            <option value="en" {{ old('language') === 'en' ? 'selected' : '' }}>English (en)</option>
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="priority" 
                               name="priority" 
                               min="1" 
                               max="10" 
                               value="{{ old('priority', 1) }}"
                               required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <p class="mt-1 text-xs text-gray-500">1 = Highest priority, 10 = Lowest priority</p>
                    </div>
                </div>

                <div>
                    <label for="variables" class="block text-sm font-medium text-gray-700 mb-2">
                        Variables (comma-separated)
                    </label>
                    <input type="text" 
                           id="variables" 
                           name="variables" 
                           value="{{ old('variables') }}"
                           placeholder="name, amount, organization_name"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    <p class="mt-1 text-xs text-gray-500">
                        List the variables used in the message (e.g., name, amount, organization_name). Separate multiple variables with commas.
                    </p>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('admin.sms.templates') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium shadow-md">
                        Create Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Character counter for message
document.getElementById('message_content').addEventListener('input', function() {
    const count = this.value.length;
    const counter = document.getElementById('message_char_count');
    counter.textContent = count + ' / 160 characters';
    
    if (count > 160) {
        counter.classList.remove('text-gray-500');
        counter.classList.add('text-red-600', 'font-bold');
    } else if (count > 140) {
        counter.classList.remove('text-gray-500', 'text-red-600', 'font-bold');
        counter.classList.add('text-yellow-600');
    } else {
        counter.classList.remove('text-red-600', 'text-yellow-600', 'font-bold');
        counter.classList.add('text-gray-500');
    }
});

// Initialize character count on page load
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('message_content');
    if (textarea.value) {
        textarea.dispatchEvent(new Event('input'));
    }
});
</script>
@endsection

