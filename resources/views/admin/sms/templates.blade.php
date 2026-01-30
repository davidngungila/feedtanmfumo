@extends('layouts.admin')

@section('page-title', 'SMS Message Templates')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">SMS Message Templates</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage SMS message templates for different saving behaviors and scenarios</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3 flex-wrap">
                <a href="{{ route('admin.sms.send') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-[#015425] rounded-md hover:bg-opacity-30 transition font-medium">
                    Send SMS
                </a>
                <a href="{{ route('admin.sms.settings') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-[#015425] rounded-md hover:bg-opacity-30 transition font-medium">
                    Settings
                </a>
                <a href="{{ route('admin.sms.logs') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-[#015425] rounded-md hover:bg-opacity-30 transition font-medium">
                    SMS Logs
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

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-[#015425]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Templates</p>
                        <p class="text-2xl font-bold text-[#015425]">{{ $templates->count() }}</p>
                    </div>
                    <div class="text-3xl text-[#015425] opacity-20">📝</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Swahili Templates</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $templates->where('language', 'sw')->count() }}</p>
                    </div>
                    <div class="text-3xl text-blue-500 opacity-20">🇹🇿</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">English Templates</p>
                        <p class="text-2xl font-bold text-green-600">{{ $templates->where('language', 'en')->count() }}</p>
                    </div>
                    <div class="text-3xl text-green-500 opacity-20">🇬🇧</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Behavior Types</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $templates->pluck('behavior_type')->filter()->unique()->count() }}</p>
                    </div>
                    <div class="text-3xl text-purple-500 opacity-20">📊</div>
                </div>
            </div>
        </div>

        <!-- Actions Bar -->
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex gap-3">
                    <button onclick="openModal()" 
                            class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium shadow-md">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Template
                    </button>
                    <button onclick="exportTemplates()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export
                    </button>
                </div>
                <div class="flex gap-3">
                    <input type="text" 
                           id="search-input"
                           placeholder="Search templates..." 
                           class="px-4 py-2 border border-gray-300 rounded-md focus:border-[#015425] focus:ring-[#015425] text-sm">
                    <select id="filter-language" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:border-[#015425] focus:ring-[#015425] text-sm">
                        <option value="">All Languages</option>
                        <option value="sw">Swahili</option>
                        <option value="en">English</option>
                    </select>
                    <select id="filter-behavior" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:border-[#015425] focus:ring-[#015425] text-sm">
                        <option value="">All Behaviors</option>
                        <option value="Inconsistent Saver">Inconsistent Saver</option>
                        <option value="Sporadic Saver">Sporadic Saver</option>
                        <option value="Non-Saver">Non-Saver</option>
                        <option value="Regular Saver">Regular Saver</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Templates Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="templates-table">
                    <thead class="bg-[#015425]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Template Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Behavior Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Message Preview</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Language</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Variables</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Last Modified</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($templates as $template)
                        <tr class="template-row hover:bg-gray-50 transition" 
                            data-name="{{ strtolower($template->template_name) }}"
                            data-language="{{ $template->language }}"
                            data-behavior="{{ strtolower($template->behavior_type ?? '') }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $template->template_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($template->behavior_type)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $template->behavior_type }}
                                </span>
                                @else
                                <span class="text-sm text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <div class="text-sm text-gray-600 truncate" title="{{ $template->message_content }}">
                                        {{ Str::limit($template->message_content, 60) }}
                                    </div>
                                    <button onclick="viewFullMessage({{ $template->id }})" 
                                            class="text-xs text-[#015425] hover:underline mt-1">
                                        View Full Message
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $template->language === 'sw' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ strtoupper($template->language) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $template->priority }}</span>
                                    @if($template->priority <= 2)
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-800">High</span>
                                    @elseif($template->priority <= 5)
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800">Medium</span>
                                    @else
                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-800">Low</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($template->variables && count($template->variables) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($template->variables, 0, 3) as $var)
                                    <span class="px-2 py-0.5 text-xs rounded bg-gray-100 text-gray-700">@{{{{ $var }}}}</span>
                                    @endforeach
                                    @if(count($template->variables) > 3)
                                    <span class="px-2 py-0.5 text-xs rounded bg-gray-100 text-gray-700">+{{ count($template->variables) - 3 }}</span>
                                    @endif
                                </div>
                                @else
                                <span class="text-sm text-gray-400">No variables</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $template->last_modified ? $template->last_modified->format('M d, Y') : 'N/A' }}
                                @if($template->last_modified)
                                <div class="text-xs text-gray-400">{{ $template->last_modified->diffForHumans() }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <button onclick="viewTemplate({{ $template->id }})" 
                                            class="text-[#015425] hover:text-[#027a3a] transition" 
                                            title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="editTemplate({{ $template->id }})" 
                                            class="text-[#015425] hover:text-[#027a3a] transition" 
                                            title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="duplicateTemplate({{ $template->id }})" 
                                            class="text-blue-600 hover:text-blue-800 transition" 
                                            title="Duplicate">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="testTemplate({{ $template->id }})" 
                                            class="text-green-600 hover:text-green-800 transition" 
                                            title="Test Send">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </button>
                                    <button onclick="deleteTemplate({{ $template->id }})" 
                                            class="text-red-600 hover:text-red-800 transition" 
                                            title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center">
                                <div class="text-gray-400">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-sm font-medium">No templates found</p>
                                    <p class="text-xs mt-1">Click "Add New Template" to create your first template</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Template Modal -->
<div id="viewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Template Details</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="viewModalContent" class="space-y-4">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Test Send Modal -->
<div id="testModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Test Send SMS</h3>
                <button onclick="closeTestModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="testForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="test_phone" 
                           name="phone" 
                           placeholder="255712345678"
                           required
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Preview Message
                    </label>
                    <div id="test_message_preview" class="p-3 bg-gray-50 rounded-md text-sm text-gray-700 border border-gray-200">
                        <!-- Message preview will appear here -->
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" 
                            onclick="closeTestModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium">
                        Send Test SMS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add/Edit Template Modal -->
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
                        <option value="">-- Select Behavior Type (Optional) --</option>
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
                              rows="6"
                              required
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]"></textarea>
                    <div class="mt-2 flex items-start justify-between">
                        <p class="text-xs text-gray-500">
                            Available variables: @{{name}}, @{{full_name}}, @{{organization_name}}, @{{amount}}, @{{member_id}}
                        </p>
                        <span id="message_char_count" class="text-xs text-gray-500">0 / 160 characters</span>
                    </div>
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
                        <p class="mt-1 text-xs text-gray-500">1 = Highest priority, 10 = Lowest</p>
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

// Search and Filter
document.getElementById('search-input')?.addEventListener('input', filterTemplates);
document.getElementById('filter-language')?.addEventListener('change', filterTemplates);
document.getElementById('filter-behavior')?.addEventListener('change', filterTemplates);

function filterTemplates() {
    const search = document.getElementById('search-input').value.toLowerCase();
    const language = document.getElementById('filter-language').value;
    const behavior = document.getElementById('filter-behavior').value.toLowerCase();
    const rows = document.querySelectorAll('.template-row');

    rows.forEach(row => {
        const name = row.dataset.name || '';
        const rowLanguage = row.dataset.language || '';
        const rowBehavior = row.dataset.behavior || '';

        const matchesSearch = name.includes(search);
        const matchesLanguage = !language || rowLanguage === language;
        const matchesBehavior = !behavior || rowBehavior.includes(behavior);

        if (matchesSearch && matchesLanguage && matchesBehavior) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Character counter for message
document.getElementById('message_content')?.addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('message_char_count').textContent = count + ' / 160 characters';
    if (count > 160) {
        document.getElementById('message_char_count').classList.add('text-red-600');
    } else {
        document.getElementById('message_char_count').classList.remove('text-red-600');
    }
});

function openModal(templateId = null) {
    const modal = document.getElementById('templateModal');
    const form = document.getElementById('templateForm');
    const title = document.getElementById('modalTitle');
    
    // Remove any existing method input
    const existingMethod = form.querySelector('input[name="_method"]');
    if (existingMethod) {
        existingMethod.remove();
    }
    
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
            
            // Update character count
            const charCount = template.message_content.length;
            document.getElementById('message_char_count').textContent = charCount + ' / 160 characters';
        }
    } else {
        title.textContent = 'Add New Template';
        form.reset();
        document.getElementById('template_id').value = '';
        form.action = '{{ route("admin.sms.templates.store") }}';
        form.method = 'POST';
        document.getElementById('message_char_count').textContent = '0 / 160 characters';
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('templateModal').classList.add('hidden');
    document.getElementById('templateForm').reset();
}

function viewTemplate(id) {
    const template = templates.find(t => t.id === id);
    if (!template) return;

    const modal = document.getElementById('viewModal');
    const content = document.getElementById('viewModalContent');
    
    content.innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Template Name</label>
                    <p class="mt-1 text-sm text-gray-900 font-semibold">${template.template_name}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Behavior Type</label>
                    <p class="mt-1 text-sm text-gray-900">${template.behavior_type || 'N/A'}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Language</label>
                    <p class="mt-1">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${template.language === 'sw' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'}">
                            ${template.language.toUpperCase()}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Priority</label>
                    <p class="mt-1 text-sm text-gray-900">${template.priority}</p>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Message Content</label>
                <div class="mt-1 p-3 bg-gray-50 rounded-md text-sm text-gray-900 border border-gray-200 whitespace-pre-wrap">${template.message_content}</div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Variables</label>
                <div class="mt-1 flex flex-wrap gap-2">
                    ${template.variables && template.variables.length > 0 
                        ? template.variables.map(v => `<span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">@{{${v}}}</span>`).join('')
                        : '<span class="text-sm text-gray-400">No variables</span>'}
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Last Modified</label>
                <p class="mt-1 text-sm text-gray-900">${template.last_modified ? new Date(template.last_modified).toLocaleString() : 'N/A'}</p>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button onclick="closeViewModal(); editTemplate(${template.id})" 
                        class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium">
                    Edit Template
                </button>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

function viewFullMessage(id) {
    viewTemplate(id);
}

function duplicateTemplate(id) {
    const template = templates.find(t => t.id === id);
    if (!template) return;

    if (confirm(`Duplicate template "${template.template_name}"?`)) {
        // Create a new template with duplicated data
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.sms.templates.store") }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        const nameInput = document.createElement('input');
        nameInput.type = 'hidden';
        nameInput.name = 'template_name';
        nameInput.value = template.template_name + ' (Copy)';
        form.appendChild(nameInput);
        
        const behaviorInput = document.createElement('input');
        behaviorInput.type = 'hidden';
        behaviorInput.name = 'behavior_type';
        behaviorInput.value = template.behavior_type || '';
        form.appendChild(behaviorInput);
        
        const contentInput = document.createElement('input');
        contentInput.type = 'hidden';
        contentInput.name = 'message_content';
        contentInput.value = template.message_content;
        form.appendChild(contentInput);
        
        const languageInput = document.createElement('input');
        languageInput.type = 'hidden';
        languageInput.name = 'language';
        languageInput.value = template.language;
        form.appendChild(languageInput);
        
        const priorityInput = document.createElement('input');
        priorityInput.type = 'hidden';
        priorityInput.name = 'priority';
        priorityInput.value = template.priority;
        form.appendChild(priorityInput);
        
        if (template.variables && template.variables.length > 0) {
            template.variables.forEach((v, i) => {
                const varInput = document.createElement('input');
                varInput.type = 'hidden';
                varInput.name = `variables[${i}]`;
                varInput.value = v;
                form.appendChild(varInput);
            });
        }
        
        document.body.appendChild(form);
        form.submit();
    }
}

function testTemplate(id) {
    const template = templates.find(t => t.id === id);
    if (!template) return;

    const modal = document.getElementById('testModal');
    const preview = document.getElementById('test_message_preview');
    
    // Show preview with sample data
    let previewMessage = template.message_content;
    previewMessage = previewMessage.replace(/@\{\{name\}\}/g, 'John');
    previewMessage = previewMessage.replace(/@\{\{full_name\}\}/g, 'John Doe');
    previewMessage = previewMessage.replace(/@\{\{organization_name\}\}/g, 'FeedTan CMG');
    previewMessage = previewMessage.replace(/@\{\{amount\}\}/g, '50,000');
    previewMessage = previewMessage.replace(/@\{\{member_id\}\}/g, 'M001');
    
    preview.textContent = previewMessage;
    
    const form = document.getElementById('testForm');
    form.onsubmit = function(e) {
        e.preventDefault();
        const phone = document.getElementById('test_phone').value;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';
        
        // Send test SMS via AJAX
        fetch('{{ route("admin.sms.send.upload") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                test_template_id: id,
                test_phone: phone,
                test_message: previewMessage,
            }),
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            
            if (data.success) {
                alert('Test SMS sent successfully!');
                closeTestModal();
            } else {
                alert('Error: ' + (data.error || 'Failed to send test SMS'));
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            alert('Error: ' + error.message);
        });
    };
    
    modal.classList.remove('hidden');
}

function closeTestModal() {
    document.getElementById('testModal').classList.add('hidden');
    document.getElementById('testForm').reset();
}

function editTemplate(id) {
    openModal(id);
}

function deleteTemplate(id) {
    const template = templates.find(t => t.id === id);
    if (confirm(`Are you sure you want to delete template "${template?.template_name}"?`)) {
        const form = document.getElementById('deleteForm');
        form.action = '{{ route("admin.sms.templates.destroy", ":id") }}'.replace(':id', id);
        form.submit();
    }
}

function exportTemplates() {
    // Export templates as JSON
    const dataStr = JSON.stringify(templates, null, 2);
    const dataBlob = new Blob([dataStr], { type: 'application/json' });
    const url = URL.createObjectURL(dataBlob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'sms-templates-' + new Date().toISOString().split('T')[0] + '.json';
    link.click();
}
</script>
@endsection
