@extends('layouts.admin')

@section('page-title', 'Upload Members Bulk')

@push('styles')
<style>
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e5e7eb;
        z-index: 0;
    }
    
    .step-item {
        flex: 1;
        position: relative;
        z-index: 1;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #9ca3af;
        margin: 0 auto 0.5rem;
        transition: all 0.3s;
    }
    
    .step-item.active .step-circle {
        background: #015425;
        border-color: #015425;
        color: white;
    }
    
    .step-item.completed .step-circle {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }
    
    .step-label {
        text-align: center;
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .step-item.active .step-label {
        color: #015425;
        font-weight: 600;
    }
    
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 0.75rem;
        padding: 3rem 2rem;
        text-align: center;
        background: #f9fafb;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .upload-area:hover {
        border-color: #015425;
        background: #f0fdf4;
    }
    
    .upload-area.dragover {
        border-color: #015425;
        background: #f0fdf4;
        border-style: solid;
    }
    
    .file-info {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background: #e0f2fe;
        border-radius: 0.5rem;
        border-left: 4px solid #015425;
    }
    
    .file-info.show {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Bulk Member Upload</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Upload multiple members at once using an Excel or CSV file</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3 flex-wrap">
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Form
                </a>
            </div>
        </div>
    </div>

    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="step-item active" id="step-indicator-1">
            <div class="step-circle">1</div>
            <div class="step-label">Upload File</div>
        </div>
        <div class="step-item" id="step-indicator-2">
            <div class="step-circle">2</div>
            <div class="step-label">Map Columns</div>
        </div>
        <div class="step-item" id="step-indicator-3">
            <div class="step-circle">3</div>
            <div class="step-label">Process</div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="alert-container"></div>

    <!-- Step 1: Upload Excel File -->
    <div id="step-1" class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Step 1: Upload Excel File</h3>
            <a href="{{ route('admin.users.download-sample') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Sample Excel
            </a>
        </div>
        
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="upload-area" id="upload-area">
                <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" class="hidden">
                <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-700 mb-2">Click to upload or drag and drop</p>
                    <p class="text-sm text-gray-500 mb-4">Excel files only (.xlsx, .xls, .csv)</p>
                    <button type="button" onclick="document.getElementById('excel_file').click()" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium">
                        Select File
                    </button>
                </div>
            </div>
            
            <div class="file-info" id="file-info">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-[#015425] mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900" id="file-name"></p>
                            <p class="text-xs text-gray-500" id="file-size"></p>
                        </div>
                    </div>
                    <button type="button" id="remove-file" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="mt-6">
                <button type="submit" id="preview-btn" class="w-full px-6 py-3 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] transition font-medium disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <span id="preview-btn-text" class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview and Map Columns
                    </span>
                    <span id="preview-btn-loader" class="hidden">
                        <svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- Step 2: Map Columns -->
    <div id="step-2" class="bg-white rounded-lg shadow-md p-6 hidden">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Step 2: Map Columns</h3>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Sheet</label>
            <select id="sheet-select" class="w-full border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" id="mapping-container">
            <!-- Mapping fields will be injected here -->
        </div>

        <div class="border-t pt-6 flex justify-between">
            <button type="button" onclick="goToStep(1)" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Back</button>
            <button type="button" id="process-btn" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a] font-medium">Start Import</button>
        </div>
    </div>

    <!-- Step 3: Progress & Results -->
    <div id="step-3" class="bg-white rounded-lg shadow-md p-6 hidden">
        <h3 class="text-lg font-semibold text-gray-800 mb-4" id="processing-title">Processing Upload</h3>
        
        <div id="progress-container" class="mb-6">
            <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                <div id="progress-bar" class="bg-[#015425] h-4 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p id="progress-text" class="text-sm text-center text-gray-600">Preparing data...</p>
        </div>

        <div id="results-container" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <p class="text-sm text-gray-500">Total Rows</p>
                    <p class="text-2xl font-bold text-gray-800" id="res-total">0</p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg text-center">
                    <p class="text-sm text-green-600">Successful Imports</p>
                    <p class="text-2xl font-bold text-green-700" id="res-success">0</p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg text-center">
                    <p class="text-sm text-red-600">Failed Rows</p>
                    <p class="text-2xl font-bold text-red-700" id="res-failed">0</p>
                </div>
            </div>

            <div id="error-list-container" class="hidden">
                <h4 class="font-bold text-red-700 mb-2">Error Details</h4>
                <div class="max-h-60 overflow-y-auto bg-red-50 p-4 rounded-lg border border-red-100 italic text-sm text-red-600 space-y-1" id="error-list">
                </div>
            </div>

            <div class="mt-6 flex justify-center">
                <a href="{{ route('admin.users.directory') }}" class="px-8 py-3 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">Go to User Directory</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const fileInput = document.getElementById('excel_file');
    const uploadArea = document.getElementById('upload-area');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFile = document.getElementById('remove-file');
    const previewBtn = document.getElementById('preview-btn');
    const uploadForm = document.getElementById('uploadForm');
    const sheetSelect = document.getElementById('sheet-select');
    
    let allSheetData = null;

    const fieldsToMap = [
        { id: 'name', label: 'Full Name', required: true, icons: 'user' },
        { id: 'email', label: 'Email Address', required: true, icons: 'mail' },
        { id: 'phone', label: 'Phone Number', required: false, icons: 'phone' },
        { id: 'gender', label: 'Gender', required: false, icons: 'users' },
        { id: 'membership_type', label: 'Membership Type', required: false, icons: 'tag' },
        { id: 'membership_code', label: 'Membership Code', required: false, icons: 'code' },
        { id: 'date_of_birth', label: 'Date of Birth', required: false, icons: 'calendar' },
        { id: 'national_id', label: 'NIDA Number', required: false, icons: 'id-card' },
        { id: 'marital_status', label: 'Marital Status', required: false, icons: 'heart' },
        { id: 'address', label: 'Address', required: false, icons: 'map-pin' },
        { id: 'status', label: 'Status (Active/Pending)', required: false, icons: 'check-circle' },
        { id: 'number_of_shares', label: 'Number of Shares', required: false, icons: 'percent' },
        { id: 'entrance_fee', label: 'Entrance Fee', required: false, icons: 'dollar-sign' },
        { id: 'capital_contribution', label: 'Capital Contribution', required: false, icons: 'briefcase' },
        { id: 'capital_outstanding', label: 'Capital Outstanding', required: false, icons: 'clock' },
        { id: 'interest_percentage', label: 'Interest %', required: false, icons: 'activity' },
        { id: 'bank_info', label: 'Bank Name', required: false, icons: 'landmark' },
        { id: 'bank_account', label: 'Bank Account', required: false, icons: 'credit-card' }
    ];

    // File selection handling
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
            fileInfo.classList.add('show');
            uploadArea.classList.add('hidden');
            previewBtn.disabled = false;
        }
    });

    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        fileInfo.classList.remove('show');
        uploadArea.classList.remove('hidden');
        previewBtn.disabled = true;
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('dragover'); });
    uploadArea.addEventListener('dragleave', () => { uploadArea.classList.remove('dragover'); });
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    // Step 1 Submission (Preview)
    uploadForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        setLoading(true);

        try {
            const response = await fetch('{{ route("admin.users.preview-excel") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            const data = await response.json();
            if (data.success) {
                allSheetData = data.sheets;
                populateSheets(data.sheets);
                goToStep(2);
            } else {
                showAlert(data.message, 'error');
            }
        } catch (err) {
            showAlert('An error occurred during file preview.', 'error');
        } finally {
            setLoading(false);
        }
    });

    function populateSheets(sheets) {
        sheetSelect.innerHTML = '';
        sheets.forEach(sheet => {
            const opt = document.createElement('option');
            opt.value = sheet.index;
            opt.textContent = `${sheet.name} (${sheet.headers.length} columns)`;
            sheetSelect.appendChild(opt);
        });

        updateMapping(sheets[0].headers);
        
        sheetSelect.onchange = () => {
            const sheet = sheets.find(s => s.index == sheetSelect.value);
            updateMapping(sheet.headers);
        };
    }

    function updateMapping(headers) {
        const container = document.getElementById('mapping-container');
        container.innerHTML = '';

        fieldsToMap.forEach(field => {
            const div = document.createElement('div');
            div.className = 'space-y-1';
            
            const label = document.createElement('label');
            label.className = 'block text-sm font-medium text-gray-700';
            label.innerHTML = `${field.label} ${field.required ? '<span class="text-red-500">*</span>' : ''}`;
            
            const select = document.createElement('select');
            select.className = 'map-select w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-[#015425]';
            select.dataset.field = field.id;
            select.required = field.required;
            
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = '-- Select column --';
            select.appendChild(defaultOpt);

            headers.forEach((header, idx) => {
                const opt = document.createElement('option');
                opt.value = Object.keys(allSheetData[sheetSelect.value].preview[0])[idx];
                opt.textContent = header;
                
                // Auto-map based on common names
                if (header.toLowerCase().includes(field.id.toLowerCase().replace('_', ' ')) || 
                    (field.id === 'name' && (header.toLowerCase().includes('name') || header.toLowerCase().includes('mwanachama'))) ||
                    (field.id === 'gender' && header.toLowerCase().includes('jinsia')) ||
                    (field.id === 'national_id' && header.toLowerCase().includes('nida'))
                ) {
                    opt.selected = true;
                }

                select.appendChild(opt);
            });

            div.appendChild(label);
            div.appendChild(select);
            container.appendChild(div);
        });
    }

    // Process Import
    document.getElementById('process-btn').onclick = async function() {
        const mapping = {};
        let missingRequired = false;
        
        document.querySelectorAll('.map-select').forEach(sel => {
            if (sel.required && !sel.value) missingRequired = true;
            mapping[sel.dataset.field] = sel.value;
        });

        if (missingRequired) {
            alert('Please map all required fields (Name and Email).');
            return;
        }

        goToStep(3);
        const formData = new FormData(uploadForm);
        formData.append('sheet_index', sheetSelect.value);
        formData.append('column_mapping', JSON.stringify(mapping));

        try {
            const response = await fetch('{{ route("admin.users.process-upload") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            const data = await response.json();
            showResults(data);
        } catch (err) {
            showAlert('An error occurred during import.', 'error');
            document.getElementById('progress-text').textContent = 'Error occurred.';
        }
    };

    function showResults(data) {
        document.getElementById('progress-container').classList.add('hidden');
        document.getElementById('processing-title').textContent = 'Import Complete';
        
        const results = data.results;
        document.getElementById('res-total').textContent = results.total;
        document.getElementById('res-success').textContent = results.success;
        document.getElementById('res-failed').textContent = results.failed;
        
        if (results.failed > 0) {
            document.getElementById('error-list-container').classList.remove('hidden');
            const errList = document.getElementById('error-list');
            errList.innerHTML = '';
            results.errors.forEach(err => {
                const p = document.createElement('p');
                p.textContent = err;
                errList.appendChild(p);
            });
        }

        document.getElementById('results-container').classList.remove('hidden');
        document.getElementById('progress-bar').style.width = '100%';
    }

    function goToStep(step) {
        document.querySelectorAll('[id^="step-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="step-indicator-"]').forEach(el => el.classList.remove('active', 'completed'));
        
        document.getElementById(`step-${step}`).classList.remove('hidden');
        
        for (let i = 1; i < step; i++) {
            document.getElementById(`step-indicator-${i}`).classList.add('completed');
        }
        document.getElementById(`step-indicator-${step}`).classList.add('active');
    }

    function setLoading(isLoading) {
        if (isLoading) {
            previewBtn.disabled = true;
            document.getElementById('preview-btn-text').classList.add('hidden');
            document.getElementById('preview-btn-loader').classList.remove('hidden');
        } else {
            previewBtn.disabled = false;
            document.getElementById('preview-btn-text').classList.remove('hidden');
            document.getElementById('preview-btn-loader').classList.add('hidden');
        }
    }

    function showAlert(message, type) {
        const container = document.getElementById('alert-container');
        const colorClass = type === 'success' ? 'bg-green-50 border-green-400 text-green-800' : 'bg-red-50 border-red-400 text-red-800';
        container.innerHTML = `
            <div class="${colorClass} border-l-4 p-4 mb-6 rounded shadow-sm role="alert">
                <p>${message}</p>
            </div>
        `;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endpush
@endsection
