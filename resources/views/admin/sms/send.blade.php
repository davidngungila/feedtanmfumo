@extends('layouts.admin')

@section('page-title', 'Send SMS')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Send Bulk SMS</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Advanced Excel upload with custom column mapping</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3 flex-wrap">
                <a href="{{ route('admin.sms.logs') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-white rounded-md hover:bg-opacity-30 transition font-medium">
                    SMS Logs
                </a>
                <a href="{{ route('admin.sms.settings') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-white rounded-md hover:bg-opacity-30 transition font-medium">
                    SMS Settings
                </a>
                <a href="{{ route('admin.sms.templates') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-white rounded-md hover:bg-opacity-30 transition font-medium">
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

        <!-- Step 1: Upload Excel File -->
        <div id="step-1" class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Step 1: Upload Excel File</h3>
            
            <div class="space-y-4">
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
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#015425] file:text-white hover:file:bg-[#027a3a] transition">
                <p class="text-xs text-gray-500">
                    Supports multiple sheets. The sample file includes: Members, Loans, Savings, Investments, and Welfare sheets.
                </p>
                <button type="button" 
                        id="preview-btn"
                        class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium">
                    Preview Excel File
                </button>
            </div>
        </div>

        <!-- Step 2: Select Sheet & Map Columns (Hidden initially) -->
        <div id="step-2" class="bg-white rounded-lg shadow-md p-6 hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Step 2: Select Sheet & Map Columns</h3>
            
            <!-- Sheet Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select Sheet <span class="text-red-500">*</span>
                </label>
                <select id="sheet-select" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    <option value="">-- Select a sheet --</option>
                </select>
            </div>

            <!-- Column Mapping -->
            <div id="column-mapping-section" class="hidden">
                <h4 class="font-semibold text-gray-800 mb-3">Map Your Columns</h4>
                <p class="text-xs text-gray-500 mb-4">
                    Select which Excel column corresponds to each field. You can use any column from your Excel file.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <select name="column_mapping[phone]" 
                                id="map-phone"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                            <option value="">-- Select column --</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Name / Member Name
                        </label>
                        <select name="column_mapping[name]" 
                                id="map-name"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                            <option value="">-- Select column (optional) --</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Member ID
                        </label>
                        <select name="column_mapping[member_id]" 
                                id="map-member-id"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                            <option value="">-- Select column (optional) --</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Saving Behavior
                        </label>
                        <select name="column_mapping[saving_behavior]" 
                                id="map-saving-behavior"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                            <option value="">-- Select column (optional) --</option>
                        </select>
                    </div>
                </div>

                <!-- Custom Column Mapping -->
                <div class="border-t pt-4 mt-4">
                    <h5 class="font-semibold text-gray-700 mb-2">Additional Custom Columns</h5>
                    <p class="text-xs text-gray-500 mb-3">
                        Map additional columns that you want to use in your message template. Use @{{column_name}} in your message to insert values.
                    </p>
                    <div id="custom-columns" class="space-y-2">
                        <!-- Custom columns will be added here -->
                    </div>
                    <button type="button" 
                            id="add-custom-column"
                            class="mt-2 px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                        + Add Custom Column
                    </button>
                </div>

                <!-- Preview Data -->
                <div id="data-preview" class="mt-6 hidden">
                    <h5 class="font-semibold text-gray-700 mb-2">Data Preview</h5>
                    <div class="overflow-x-auto border rounded-md">
                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                            <thead id="preview-headers" class="bg-gray-50">
                                <!-- Headers will be inserted here -->
                            </thead>
                            <tbody id="preview-body" class="bg-white divide-y divide-gray-200">
                                <!-- Sample data will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Configure Message (Hidden initially) -->
        <div id="step-3" class="bg-white rounded-lg shadow-md p-6 hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Step 3: Configure Message</h3>
            
            <form action="{{ route('admin.sms.send.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="send-form">
                @csrf
                <input type="file" name="excel_file" id="excel_file_form" accept=".xlsx,.xls,.csv" style="display: none;">
                <input type="hidden" name="sheet_index" id="sheet_index">
                <input type="hidden" name="column_mapping" id="column_mapping_json">

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
                                data-content="{{ $template->message_content }}">
                            {{ $template->template_name }} 
                            @if($template->behavior_type)
                                ({{ $template->behavior_type }})
                            @endif
                        </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        Templates support variables like {{name}}, {{member_id}}, {{amount}}, etc. You can also use any column name from your Excel.
                    </p>
                </div>

                <!-- Custom Message -->
                <div>
                    <label for="custom_message" class="block text-sm font-medium text-gray-700 mb-2">
                        Custom Message (Optional)
                    </label>
                    <textarea name="custom_message" 
                              id="custom_message" 
                              rows="6"
                              maxlength="1000"
                              placeholder="Enter a custom message. Use @{{column_name}} to insert values from Excel columns..."
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]"></textarea>
                    <p class="mt-1 text-xs text-gray-500">
                        <span id="char_count">0</span>/1000 characters. Use @{{column_name}} syntax to insert Excel column values.
                    </p>
                    <div class="mt-2 text-xs text-gray-500">
                        <p class="font-semibold">Available Variables:</p>
                        <ul class="list-disc list-inside ml-2 mt-1" id="available-variables">
                            <li>@{{name}} - Member name</li>
                            <li>@{{member_id}} - Member ID</li>
                            <li>@{{phone}} - Phone number</li>
                        </ul>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium shadow-md">
                        Send SMS
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">How It Works</h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">1. Upload Excel File</h4>
                    <p>Upload an Excel file with one or more sheets. Each sheet can have different data structures (Loans, Savings, Investments, Welfare, etc.)</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">2. Select Sheet & Map Columns</h4>
                    <p>Choose which sheet to use and map your Excel columns to the required fields. You can map any column from your Excel file. Use @{{column_name}} in your message to insert values.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">3. Use Variables in Messages</h4>
                    <p>In your message template, use @{{column_name}} to insert values from any Excel column. For example:</p>
                    <ul class="list-disc list-inside ml-4 mt-1 space-y-1">
                        <li>@{{name}} - Member name</li>
                        <li>@{{loan_amount}} - Loan amount from Excel</li>
                        <li>@{{balance}} - Account balance</li>
                        <li>@{{due_date}} - Due date</li>
                        <li>Any column name from your Excel file</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">4. Flexible Data Support</h4>
                    <p>The system supports different data types:</p>
                    <ul class="list-disc list-inside ml-4 mt-1 space-y-1">
                        <li><strong>Loans:</strong> Loan ID, Amount, Outstanding Balance, Due Date, etc.</li>
                        <li><strong>Savings:</strong> Account Number, Balance, Last Deposit, etc.</li>
                        <li><strong>Investments:</strong> Investment ID, Principal, Expected Return, Maturity Date, etc.</li>
                        <li><strong>Welfare:</strong> Welfare ID, Type, Amount, Status, etc.</li>
                        <li><strong>Any custom data structure</strong> - Map any columns you need</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const excelFileInput = document.getElementById('excel_file');
    const previewBtn = document.getElementById('preview-btn');
    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');
    const sheetSelect = document.getElementById('sheet-select');
    const columnMappingSection = document.getElementById('column-mapping-section');
    const customMessage = document.getElementById('custom_message');
    const charCount = document.getElementById('char_count');
    const templateSelect = document.getElementById('template_id');
    const addCustomColumnBtn = document.getElementById('add-custom-column');
    const customColumnsDiv = document.getElementById('custom-columns');
    const availableVariablesList = document.getElementById('available-variables');
    
    let excelSheets = [];
    let currentSheetIndex = 0;
    let currentHeaders = [];
    let columnMapping = {};

    // Character counter
    if (customMessage && charCount) {
        customMessage.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }

    // Preview Excel file
    previewBtn.addEventListener('click', function() {
        if (!excelFileInput.files.length) {
            alert('Please select an Excel file first');
            return;
        }

        const formData = new FormData();
        formData.append('excel_file', excelFileInput.files[0]);

        previewBtn.disabled = true;
        previewBtn.textContent = 'Loading...';

        fetch('{{ route("admin.sms.send.preview") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            previewBtn.disabled = false;
            previewBtn.textContent = 'Preview Excel File';

            if (data.success) {
                excelSheets = data.sheets;
                populateSheetSelect();
                step2.classList.remove('hidden');
                step2.scrollIntoView({ behavior: 'smooth' });
            } else {
                alert('Error: ' + (data.error || 'Failed to preview Excel file'));
            }
        })
        .catch(error => {
            previewBtn.disabled = false;
            previewBtn.textContent = 'Preview Excel File';
            alert('Error: ' + error.message);
        });
    });

    // Populate sheet select
    function populateSheetSelect() {
        sheetSelect.innerHTML = '<option value="">-- Select a sheet --</option>';
        excelSheets.forEach((sheet, index) => {
            const option = document.createElement('option');
            option.value = index;
            option.textContent = `${sheet.name} (${sheet.row_count} rows)`;
            sheetSelect.appendChild(option);
        });
    }

    // Handle sheet selection
    sheetSelect.addEventListener('change', function() {
        const sheetIndex = parseInt(this.value);
        if (isNaN(sheetIndex)) {
            columnMappingSection.classList.add('hidden');
            return;
        }

        currentSheetIndex = sheetIndex;
        const sheet = excelSheets[sheetIndex];
        currentHeaders = sheet.headers;
        
        // Store file data for later submission
        const fileInput = document.getElementById('excel_file');
        const formData = new FormData();
        formData.append('excel_file', fileInput.files[0]);
        
        // Convert to base64 for storage (simplified - in production, handle file upload differently)
        document.getElementById('excel_file_data').value = 'uploaded';
        document.getElementById('sheet_index').value = sheetIndex;

        populateColumnMappings(sheet.headers);
        showDataPreview(sheet.sample_data);
        columnMappingSection.classList.remove('hidden');
        step3.classList.remove('hidden');
        step3.scrollIntoView({ behavior: 'smooth' });
    });

    // Populate column mapping selects
    function populateColumnMappings(headers) {
        const mappingSelects = ['map-phone', 'map-name', 'map-member-id', 'map-saving-behavior'];
        
        mappingSelects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select) {
                select.innerHTML = selectId === 'map-phone' 
                    ? '<option value="">-- Select column (required) --</option>'
                    : '<option value="">-- Select column (optional) --</option>';
                
                headers.forEach(header => {
                    const option = document.createElement('option');
                    option.value = header;
                    option.textContent = header;
                    select.appendChild(option);
                });
            }
        });

        // Update available variables
        updateAvailableVariables(headers);
    }

    // Update available variables list
    function updateAvailableVariables(headers) {
        availableVariablesList.innerHTML = '';
        const commonVars = [
            { key: 'name', label: '@{{name}} - Member name' },
            { key: 'member_id', label: '@{{member_id}} - Member ID' },
            { key: 'phone', label: '@{{phone}} - Phone number' },
        ];
        
        commonVars.forEach(v => {
            const li = document.createElement('li');
            li.textContent = v.label;
            availableVariablesList.appendChild(li);
        });

        headers.forEach(header => {
            const key = header.toLowerCase().replace(/\s+/g, '_');
            const li = document.createElement('li');
            li.textContent = '@{{' + key + '}} - ' + header;
            availableVariablesList.appendChild(li);
        });
    }

    // Show data preview
    function showDataPreview(sampleData) {
        const previewSection = document.getElementById('data-preview');
        const previewHeaders = document.getElementById('preview-headers');
        const previewBody = document.getElementById('preview-body');

        if (!sampleData || sampleData.length === 0) {
            previewSection.classList.add('hidden');
            return;
        }

        previewSection.classList.remove('hidden');

        // Headers
        previewHeaders.innerHTML = '<tr>';
        currentHeaders.forEach(header => {
            previewHeaders.innerHTML += `<th class="px-3 py-2 text-left font-medium text-gray-700">${header}</th>`;
        });
        previewHeaders.innerHTML += '</tr>';

        // Sample data
        previewBody.innerHTML = '';
        sampleData.slice(0, 3).forEach(row => {
            previewBody.innerHTML += '<tr>';
            row.forEach(cell => {
                previewBody.innerHTML += `<td class="px-3 py-2 text-gray-600">${cell || ''}</td>`;
            });
            previewBody.innerHTML += '</tr>';
        });
    }

    // Add custom column mapping
    let customColumnCount = 0;
    addCustomColumnBtn.addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'flex gap-2 items-end';
        div.innerHTML = `
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-700 mb-1">Column Name</label>
                <select class="custom-column-select block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-[#015425] focus:ring-[#015425]">
                    <option value="">-- Select column --</option>
                    ${currentHeaders.map(h => `<option value="${h}">${h}</option>`).join('')}
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-700 mb-1">Variable Name</label>
                <input type="text" class="custom-variable-name block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-[#015425] focus:ring-[#015425]" placeholder="e.g., loan_amount">
            </div>
            <button type="button" class="remove-custom-column px-3 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-sm">Remove</button>
        `;
        
        div.querySelector('.remove-custom-column').addEventListener('click', function() {
            div.remove();
        });
        
        customColumnsDiv.appendChild(div);
        customColumnCount++;
    });

    // Template selection
    if (templateSelect) {
        templateSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value && selectedOption.dataset.content) {
                customMessage.value = selectedOption.dataset.content;
                charCount.textContent = customMessage.value.length;
            }
        });
    }

    // Form submission - build column mapping JSON
    document.getElementById('send-form').addEventListener('submit', function(e) {
        // Build column mapping object
        columnMapping = {
            phone: document.getElementById('map-phone').value,
            name: document.getElementById('map-name').value || null,
            member_id: document.getElementById('map-member-id').value || null,
            saving_behavior: document.getElementById('map-saving-behavior').value || null,
        };

        // Add custom columns
        document.querySelectorAll('.custom-column-select').forEach(select => {
            const columnName = select.value;
            const variableName = select.closest('.flex').querySelector('.custom-variable-name').value;
            if (columnName && variableName) {
                columnMapping[variableName] = columnName;
            }
        });

        // Validate required fields
        if (!columnMapping.phone) {
            e.preventDefault();
            alert('Please map the Phone Number column (required)');
            return false;
        }

        // Store mapping as JSON
        document.getElementById('column_mapping_json').value = JSON.stringify(columnMapping);

        // Copy file to form input
        const fileInput = document.getElementById('excel_file');
        const formFileInput = document.getElementById('excel_file_form');
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please upload an Excel file');
            return false;
        }
        
        // Copy file to form input using DataTransfer
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(fileInput.files[0]);
        formFileInput.files = dataTransfer.files;
    });
});
</script>
@endsection
