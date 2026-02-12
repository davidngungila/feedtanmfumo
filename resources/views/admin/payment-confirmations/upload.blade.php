@extends('layouts.admin')

@section('page-title', 'Upload Payment Confirmation Sheet')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Upload Payment Confirmation Sheet</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Upload Excel sheet with member payment information</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3 flex-wrap">
                <a href="{{ route('admin.payment-confirmations.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    Back to List
                </a>
            </div>
        </div>
    </div>

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
            <h3 class="font-semibold text-blue-900 mb-2">Import Results:</h3>
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
                <a href="{{ route('admin.payment-confirmations.download-sample') }}" 
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
                Expected columns: S/N, Members Name, Member type, ID, Amount to be paid. 2026
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
                Select which Excel column corresponds to each field.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Member ID <span class="text-red-500">*</span>
                    </label>
                    <select name="column_mapping[member_id]" 
                            id="map-member-id"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="">-- Select column --</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Amount to be Paid <span class="text-red-500">*</span>
                    </label>
                    <select name="column_mapping[amount]" 
                            id="map-amount"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="">-- Select column --</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Member Name (Optional)
                    </label>
                    <select name="column_mapping[member_name]" 
                            id="map-member-name"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="">-- Select column (optional) --</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Member Type (Optional)
                    </label>
                    <select name="column_mapping[member_type]" 
                            id="map-member-type"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="">-- Select column (optional) --</option>
                    </select>
                </div>
            </div>

            <!-- Preview Table -->
            <div id="preview-table-section" class="hidden">
                <h4 class="font-semibold text-gray-800 mb-3">Preview (First 5 rows)</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                        <thead id="preview-headers" class="bg-gray-50">
                        </thead>
                        <tbody id="preview-body" class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 3: Process Upload (Hidden initially) -->
    <div id="step-3" class="bg-white rounded-lg shadow-md p-6 hidden">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Step 3: Process Upload</h3>
        
        <form id="upload-form" method="POST" action="{{ route('admin.payment-confirmations.process-upload') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="excel_file" id="form-excel-file" style="display: none;" accept=".xlsx,.xls,.csv">
            <input type="hidden" id="form-sheet-index" name="sheet_index">
            <input type="hidden" id="form-column-mapping" name="column_mapping">
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                <p class="text-sm text-yellow-800">
                    <strong>Note:</strong> This will create payment confirmation records for members. Members will need to complete the distribution form on the public page.
                </p>
            </div>
            
            <button type="submit" 
                    class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium">
                Process Upload
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const excelFileInput = document.getElementById('excel_file');
    const previewBtn = document.getElementById('preview-btn');
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');
    const sheetSelect = document.getElementById('sheet-select');
    const columnMappingSection = document.getElementById('column-mapping-section');
    const mapMemberId = document.getElementById('map-member-id');
    const mapAmount = document.getElementById('map-amount');
    const previewTableSection = document.getElementById('preview-table-section');
    const previewHeaders = document.getElementById('preview-headers');
    const previewBody = document.getElementById('preview-body');
    const uploadForm = document.getElementById('upload-form');
    
    let currentSheets = [];
    let currentSheetData = null;
    let currentSheetIndex = 0;

    previewBtn.addEventListener('click', async function() {
        const file = excelFileInput.files[0];
        if (!file) {
            alert('Please select an Excel file first.');
            return;
        }

        previewBtn.disabled = true;
        previewBtn.textContent = 'Loading...';

        const formData = new FormData();
        formData.append('excel_file', file);

        try {
            const response = await fetch('{{ route("admin.payment-confirmations.preview-excel") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                currentSheets = data.sheets;
                
                // Populate sheet select
                sheetSelect.innerHTML = '<option value="">-- Select a sheet --</option>';
                data.sheets.forEach((sheet, index) => {
                    const option = document.createElement('option');
                    option.value = index;
                    option.textContent = `${sheet.name} (${sheet.row_count} rows)`;
                    sheetSelect.appendChild(option);
                });

                step2.classList.remove('hidden');
                step2.scrollIntoView({ behavior: 'smooth' });
            } else {
                alert('Error: ' + (data.error || 'Failed to preview Excel file'));
            }
        } catch (error) {
            alert('Error: ' + error.message);
        } finally {
            previewBtn.disabled = false;
            previewBtn.textContent = 'Preview Excel File';
        }
    });

    sheetSelect.addEventListener('change', function() {
        const sheetIndex = parseInt(this.value);
        if (isNaN(sheetIndex)) {
            columnMappingSection.classList.add('hidden');
            return;
        }

        currentSheetIndex = sheetIndex;
        currentSheetData = currentSheets[sheetIndex];

        // Populate column selects
        const headers = currentSheetData.headers;
        const mapMemberName = document.getElementById('map-member-name');
        const mapMemberType = document.getElementById('map-member-type');
        
        [mapMemberId, mapAmount, mapMemberName, mapMemberType].forEach(select => {
            if (select) {
                const isOptional = select === mapMemberName || select === mapMemberType;
                select.innerHTML = isOptional ? '<option value="">-- Select column (optional) --</option>' : '<option value="">-- Select column --</option>';
                headers.forEach((header, index) => {
                    const option = document.createElement('option');
                    option.value = header;
                    option.textContent = header;
                    select.appendChild(option);
                });
            }
        });

        // Show preview
        showPreview(currentSheetData);

        columnMappingSection.classList.remove('hidden');
        step3.classList.remove('hidden');
    });

    function showPreview(sheetData) {
        const headers = sheetData.headers;
        const sampleData = sheetData.sample_data;

        // Build header row
        previewHeaders.innerHTML = '<tr>';
        headers.forEach(header => {
            previewHeaders.innerHTML += `<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">${header}</th>`;
        });
        previewHeaders.innerHTML += '</tr>';

        // Build body rows
        previewBody.innerHTML = '';
        sampleData.forEach(row => {
            previewBody.innerHTML += '<tr>';
            headers.forEach((header, index) => {
                previewBody.innerHTML += `<td class="px-4 py-2 text-sm text-gray-900 border-b">${row[index] || ''}</td>`;
            });
            previewBody.innerHTML += '</tr>';
        });

        previewTableSection.classList.remove('hidden');
    }

    uploadForm.addEventListener('submit', function(e) {
        const file = excelFileInput.files[0];
        if (!file) {
            e.preventDefault();
            alert('Please select an Excel file.');
            return;
        }

        const sheetIndex = sheetSelect.value;
        if (!sheetIndex) {
            e.preventDefault();
            alert('Please select a sheet.');
            return;
        }

        const memberIdColumn = mapMemberId.value;
        const amountColumn = mapAmount.value;
        const memberNameColumn = mapMemberName?.value || '';
        const memberTypeColumn = mapMemberType?.value || '';

        if (!memberIdColumn || !amountColumn) {
            e.preventDefault();
            alert('Please map all required columns (Member ID and Amount).');
            return;
        }

        // Set hidden form values
        const formExcelFile = document.getElementById('form-excel-file');
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        formExcelFile.files = dataTransfer.files;
        
        const columnMapping = {
            member_id: memberIdColumn,
            amount: amountColumn,
            member_name: memberNameColumn,
            member_type: memberTypeColumn
        };
        
        document.getElementById('form-sheet-index').value = sheetIndex;
        document.getElementById('form-column-mapping').value = JSON.stringify(columnMapping);
        
        // Debug logging
        console.log('Form submission data:', {
            file: file.name,
            sheetIndex: sheetIndex,
            columnMapping: columnMapping
        });

        // Show loading overlay
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing... Please wait...';
        
        // Disable form inputs to prevent double submission
        this.querySelectorAll('input, select, button').forEach(el => {
            if (el !== submitBtn) {
                el.disabled = true;
            }
        });

        // Allow form to submit normally - it will redirect after processing
    });
});
</script>
@endpush
@endsection

