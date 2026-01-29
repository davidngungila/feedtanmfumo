@extends('layouts.admin')

@section('page-title', 'SMS Message Templates')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">SMS Message Templates</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage SMS message templates for different saving behaviors</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                <a href="{{ route('admin.sms.send') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-[#015425] rounded-md hover:bg-opacity-30 transition font-medium">
                    Send SMS
                </a>
                <a href="{{ route('admin.sms.settings') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-[#015425] rounded-md hover:bg-opacity-30 transition font-medium">
                    Settings
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
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Add New Template Button -->
        <div class="flex justify-end">
            <button onclick="openModal()" 
                    class="px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium shadow-md">
                Add New Template
            </button>
        </div>

        <!-- Templates List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Template Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Behavior Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message Content</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Language</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variables</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Modified</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($templates as $template)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $template->template_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $template->behavior_type ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="max-w-xs truncate" title="{{ $template->message_content }}">
                                {{ Str::limit($template->message_content, 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 py-1 text-xs rounded-full {{ $template->language === 'sw' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ strtoupper($template->language) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $template->priority }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($template->variables)
                                {{ implode(', ', $template->variables) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $template->last_modified ? $template->last_modified->format('Y-m-d') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editTemplate({{ $template->id }})" 
                                    class="text-[#015425] hover:text-[#027a3a] mr-3">
                                Edit
                            </button>
                            <button onclick="deleteTemplate({{ $template->id }})" 
                                    class="text-red-600 hover:text-red-800">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            No templates found. Click "Add New Template" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Template -->
<div id="templateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Add New Template</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="templateForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="template_id" name="template_id">

                <div>
                    <label for="template_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Template Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="template_name" 
                           name="template_name" 
                           required
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                </div>

                <div>
                    <label for="behavior_type" class="block text-sm font-medium text-gray-700 mb-1">
                        Behavior Type
                    </label>
                    <select id="behavior_type" 
                            name="behavior_type"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="">-- Select Behavior Type --</option>
                        <option value="Inconsistent Saver">Inconsistent Saver</option>
                        <option value="Sporadic Saver">Sporadic Saver</option>
                        <option value="Non-Saver">Non-Saver</option>
                        <option value="Regular Saver">Regular Saver</option>
                    </select>
                </div>

                <div>
                    <label for="message_content" class="block text-sm font-medium text-gray-700 mb-1">
                        Message Content <span class="text-red-500">*</span>
                    </label>
                    <textarea id="message_content" 
                              name="message_content" 
                              rows="5"
                              required
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]"></textarea>
                    <p class="mt-1 text-xs text-gray-500">
                        Available variables: @{{name}}, @{{full_name}}, @{{organization_name}}, @{{amount}}, @{{member_id}}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-1">
                            Language <span class="text-red-500">*</span>
                        </label>
                        <select id="language" 
                                name="language" 
                                required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                            <option value="sw">Swahili (sw)</option>
                            <option value="en">English (en)</option>
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="priority" 
                               name="priority" 
                               min="1" 
                               max="10" 
                               value="1"
                               required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <p class="mt-1 text-xs text-gray-500">1 = Highest priority</p>
                    </div>
                </div>

                <div>
                    <label for="variables" class="block text-sm font-medium text-gray-700 mb-1">
                        Variables (comma-separated)
                    </label>
                    <input type="text" 
                           id="variables" 
                           name="variables" 
                           placeholder="name, amount, organization_name"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    <p class="mt-1 text-xs text-gray-500">
                        List the variables used in the message (e.g., name, amount, organization_name)
                    </p>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" 
                            onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium">
                        Save Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
const templates = @json($templates);

function openModal(templateId = null) {
    const modal = document.getElementById('templateModal');
    const form = document.getElementById('templateForm');
    const title = document.getElementById('modalTitle');
    
    if (templateId) {
        const template = templates.find(t => t.id === templateId);
        if (template) {
            title.textContent = 'Edit Template';
            document.getElementById('template_id').value = template.id;
            document.getElementById('template_name').value = template.template_name;
            document.getElementById('behavior_type').value = template.behavior_type || '';
            document.getElementById('message_content').value = template.message_content;
            document.getElementById('language').value = template.language;
            document.getElementById('priority').value = template.priority;
            document.getElementById('variables').value = template.variables ? template.variables.join(', ') : '';
            form.action = '{{ route("admin.sms.templates.update", ":id") }}'.replace(':id', template.id);
            form.method = 'POST';
            // Add method spoofing for PUT
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);
        }
    } else {
        title.textContent = 'Add New Template';
        form.reset();
        document.getElementById('template_id').value = '';
        form.action = '{{ route("admin.sms.templates.store") }}';
        form.method = 'POST';
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('templateModal').classList.add('hidden');
    document.getElementById('templateForm').reset();
}

function editTemplate(id) {
    openModal(id);
}

function deleteTemplate(id) {
    if (confirm('Are you sure you want to delete this template?')) {
        const form = document.getElementById('deleteForm');
        form.action = '{{ route("admin.sms.templates.destroy", ":id") }}'.replace(':id', id);
        form.submit();
    }
}
</script>
@endsection

