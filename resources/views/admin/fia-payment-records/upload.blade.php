@extends('layouts.admin')

@section('page-title', 'Upload Payment Records')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Upload Payment Records</h1>
                <p class="text-gray-600 mt-1">Upload FIA payment records from Excel file</p>
            </div>
            <a href="{{ route('admin.fia-payment-records.records') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                View All Records
            </a>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form id="upload-form" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Excel File</label>
                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">Excel files (XLSX, XLS, CSV) - MAX. 10MB</p>
                            <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded text-xs text-green-800">
                                <strong>✓ Flexible column mapping!</strong> System automatically maps your columns.
                            </div>
                            <div class="mt-1 p-2 bg-blue-50 border border-blue-200 rounded text-xs text-blue-800">
                                <strong>Accepted column names:</strong><br>
                                • Member ID: ID, Member ID, MemberID, Member-Number<br>
                                • Name: Name, Member Name, Full Name, FullName<br>
                                • Gawio la FIA: Gawio la FIA, Gawio, FIA Gawio<br>
                                • FIA iliyokomaa: FIA iliyokomaa, FIA Komaa<br>
                                • Jumla: Jumla, Total, Sum, Amount<br>
                                • Malipo ya vipande: Malipo ya vipande, Installments Paid<br>
                                • Loan: Loan, Loan Amount, Kopo<br>
                                • Kiasi baki: Kiasi baki, Balance, Remaining
                            </div>
                        </div>
                        <input id="excel_file" name="excel_file" type="file" class="hidden" accept=".xlsx,.xls,.csv" required />
                    </label>
                </div>
                <div id="file-info" class="mt-4 hidden">
                    <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span id="file-name" class="text-sm text-blue-800"></span>
                        <button type="button" onclick="clearFile()" class="ml-auto text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="resetForm()" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Clear
                </button>
                <button type="submit" id="upload-btn" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition disabled:opacity-50" disabled>
                    Upload File
                </button>
            </div>
        </form>
    </div>

    <!-- Upload Progress -->
    <div id="upload-progress" class="bg-white rounded-lg shadow-sm p-6 mb-6 hidden">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Progress</h3>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div id="progress-bar" class="bg-[#015425] h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <p id="progress-text" class="text-sm text-gray-600 mt-2">Uploading...</p>
    </div>

    <!-- Upload Result -->
    <div id="upload-result" class="bg-white rounded-lg shadow-sm p-6 hidden">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Result</h3>
        <div id="result-content"></div>
    </div>

    <!-- Template Section -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Excel Template Guide</h3>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-700 mb-4">Your Excel file should contain the following columns in the exact order:</p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase">Column</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase">Header Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 uppercase">Description</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-2 text-sm font-medium text-gray-900">A</td>
                            <td class="px-4 py-2 text-sm text-gray-900">S/N</td>
                            <td class="px-4 py-2 text-sm text-gray-600">Serial Number</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm font-medium text-gray-900">B</td>
                            <td class="px-4 py-2 text-sm text-gray-900">ID</td>
                            <td class="px-4 py-2 text-sm text-gray-600">Member ID</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm font-medium text-gray-900">C</td>
                            <td class="px-4 py-2 text-sm text-gray-900">NAME</td>
                            <td class="px-4 py-2 text-sm text-gray-600">Member Full Name</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm font-medium text-gray-900">D</td>
                            <td class="px-4 py-2 text-sm text-gray-900">Gawio la FIA</td>
                            <td class="px-4 py-2 text-sm text-gray-600">FIA Share Amount</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm font-medium text-gray-900">E</td>
                            <td class="px-4 py-2 text-sm text-gray-900">FIA iliyokomaa</td>
                            <td class="px-4 py-2 text-sm text-gray-600">Completed FIA Amount</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm font-medium text-gray-900">F</td>
                            <td class="px-4 py-2 text-sm text-gray-900">Jumla</td>
                            <td class="px-4 py-2 text-sm text-gray-600">Total Amount</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm font-medium text-gray-900">G</td>
                            <td class="px-4 py-2 text-sm text-gray-900">Malipo ya vipande yailiyakuwa Yamepelea</td>
                            <td class="px-4 py-2 text-sm text-gray-600">Previous Share Payments</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm font-medium text-gray-900">H</td>
                            <td class="px-4 py-2 text-sm text-gray-900">LOAN</td>
                            <td class="px-4 py-2 text-sm text-gray-600">Loan Amount</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm font-medium text-gray-900">I</td>
                            <td class="px-4 py-2 text-sm text-gray-900">Kiasi baki</td>
                            <td class="px-4 py-2 text-sm text-gray-600">Remaining Balance</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>Important:</strong> Make sure your Excel file has headers in the first row with the exact names shown above. The system will automatically map the columns based on the header names.
                </p>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="#" onclick="downloadTemplate()" class="inline-flex items-center px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Sample Template
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
});

function setupEventListeners() {
    // File input change
    document.getElementById('excel_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            displayFileInfo(file);
            document.getElementById('upload-btn').disabled = false;
        }
    });

    // Form submission
    document.getElementById('upload-form').addEventListener('submit', function(e) {
        e.preventDefault();
        uploadFile();
    });

    // Drag and drop
    const dropZone = document.querySelector('label[for="excel_file"]');
    
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || 
                file.type === 'application/vnd.ms-excel' || 
                file.type === 'text/csv' || 
                file.name.toLowerCase().endsWith('.xlsx') || 
                file.name.toLowerCase().endsWith('.xls') || 
                file.name.toLowerCase().endsWith('.csv')) {
                document.getElementById('excel_file').files = files;
                displayFileInfo(file);
                document.getElementById('upload-btn').disabled = false;
            } else {
                showNotification('Please upload a valid Excel file (XLSX, XLS, or CSV)', 'error');
            }
        }
    });
}

function displayFileInfo(file) {
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    
    fileInfo.classList.remove('hidden');
    fileName.textContent = `${file.name} (${formatFileSize(file.size)})`;
}

function clearFile() {
    document.getElementById('excel_file').value = '';
    document.getElementById('file-info').classList.add('hidden');
    document.getElementById('upload-btn').disabled = true;
}

function resetForm() {
    clearFile();
    document.getElementById('upload-progress').classList.add('hidden');
    document.getElementById('upload-result').classList.add('hidden');
    document.getElementById('progress-bar').style.width = '0%';
}

function uploadFile() {
    const formData = new FormData();
    formData.append('excel_file', document.getElementById('excel_file').files[0]);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // Show progress
    document.getElementById('upload-progress').classList.remove('hidden');
    document.getElementById('upload-result').classList.add('hidden');
    document.getElementById('upload-btn').disabled = true;

    // Simulate progress
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        updateProgress(progress, 'Uploading...');
    }, 200);

    fetch('{{ route("admin.fia-payment-records.process-upload") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(progressInterval);
        updateProgress(100, 'Upload complete!');
        
        setTimeout(() => {
            document.getElementById('upload-progress').classList.add('hidden');
            showUploadResult(data);
        }, 500);
    })
    .catch(error => {
        clearInterval(progressInterval);
        console.error('Upload error:', error);
        updateProgress(0, 'Upload failed');
        showNotification('Upload failed. Please try again.', 'error');
        document.getElementById('upload-btn').disabled = false;
    });
}

function updateProgress(percent, text) {
    document.getElementById('progress-bar').style.width = percent + '%';
    document.getElementById('progress-text').textContent = text;
}

function showUploadResult(data) {
    const resultDiv = document.getElementById('upload-result');
    const resultContent = document.getElementById('result-content');
    
    resultDiv.classList.remove('hidden');
    
    if (data.requires_mapping) {
        // Show column mapping interface
        resultContent.innerHTML = `
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    <div>
                        <h4 class="text-yellow-800 font-medium">Column Mapping Required</h4>
                        <p class="text-yellow-700 text-sm mt-1">${data.message}</p>
                        <p class="text-yellow-600 text-xs mt-2">Found ${data.total_rows} rows to process</p>
                    </div>
                </div>
                <div class="mt-4" id="column-mapping-container">
                    <!-- Column mapping will be inserted here -->
                </div>
            </div>
        `;
        
        // Generate column mapping interface
        generateColumnMapping(data);
    } else if (data.success) {
        let skipReasonsHtml = '';
        if (data.skip_reasons && data.skip_reasons.length > 0) {
            skipReasonsHtml = `
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h6 class="font-medium text-yellow-800 mb-2">Why records were skipped:</h6>
                    <div class="text-sm text-yellow-700">
                        ${data.skip_reasons.slice(0, 10).map(reason => `<div class="py-1">• ${reason}</div>`).join('')}
                        ${data.skip_reasons.length > 10 ? `<div class="py-1 text-yellow-600">... and ${data.skip_reasons.length - 10} more</div>` : ''}
                    </div>
                </div>
            `;
        }
        
        resultContent.innerHTML = `
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-green-800 font-medium">Upload Complete!</h4>
                        <p class="text-green-700 text-sm mt-1">${data.message}</p>
                        <p class="text-green-600 text-xs mt-2">File saved as: ${data.filename}</p>
                    </div>
                </div>
                ${skipReasonsHtml}
                <div class="mt-4 flex space-x-3">
                    <a href="{{ route('admin.fia-payment-records.records') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        View Records
                    </a>
                    <button onclick="resetForm()" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                        Upload Another File
                    </button>
                </div>
            </div>
        `;
    } else {
        resultContent.innerHTML = `
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-red-800 font-medium">Upload Failed</h4>
                        <p class="text-red-700 text-sm mt-1">${data.message}</p>
                        ${data.errors ? `<div class="mt-2 text-xs text-red-600">${JSON.stringify(data.errors, null, 2)}</div>` : ''}
                    </div>
                </div>
                <div class="mt-4">
                    <button onclick="resetForm()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Try Again
                    </button>
                </div>
            </div>
        `;
    }
}

function generateColumnMapping(data) {
    const container = document.getElementById('column-mapping-container');
    const headers = data.headers;
    const sampleData = data.sample_data;
    const autoMapping = data.auto_mapping;
    
    const requiredFields = {
        'member_id': 'Member ID *',
        'member_name': 'Member Name *',
        'gawio_la_fia': 'Gawio la FIA',
        'fia_iliyokomaa': 'FIA iliyokomaa',
        'jumla': 'Jumla',
        'malipo_ya_vipande_yaliyokuwa_yamepelea': 'Malipo ya vipande',
        'loan': 'Loan',
        'kiasi_baki': 'Kiasi baki'
    };
    
    let mappingHtml = `
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-4">
                <h5 class="font-medium text-gray-900 mb-4">Map your Excel columns to database fields:</h5>
                <div class="space-y-3">
    `;
    
    // Create mapping rows
    Object.entries(requiredFields).forEach(([field, label]) => {
        mappingHtml += `
            <div class="flex items-center space-x-4">
                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700">${label}</label>
                </div>
                <div class="flex-1">
                    <select class="column-mapping-select w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#015425]" data-field="${field}">
                        <option value="">-- Select Column --</option>
        `;
        
        Object.entries(headers).forEach(([index, header]) => {
            const selected = autoMapping[field] == index ? 'selected' : '';
            mappingHtml += `<option value="${index}" ${selected}>${header}</option>`;
        });
        
        mappingHtml += `
                    </select>
                </div>
                <div class="w-32">
                    <div class="text-xs text-gray-500">
                        ${sampleData[0] && autoMapping[field] !== null && sampleData[0][autoMapping[field]] ? 'Sample: ' + sampleData[0][autoMapping[field]] : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    mappingHtml += `
                </div>
                
                <!-- Sample Data Preview -->
                <div class="mt-6">
                    <h6 class="font-medium text-gray-900 mb-3">Sample Data Preview:</h6>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-2 py-1 text-left font-medium text-gray-700">Row</th>
        `;
    
    Object.values(headers).forEach(header => {
        mappingHtml += `<th class="px-2 py-1 text-left font-medium text-gray-700">${header}</th>`;
    });
    
    mappingHtml += `
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
        `;
    
    sampleData.forEach((row, index) => {
        mappingHtml += `<tr><td class="px-2 py-1 font-medium">${index + 1}</td>`;
        Object.values(headers).forEach((header, colIndex) => {
            mappingHtml += `<td class="px-2 py-1">${row[colIndex] || ''}</td>`;
        });
        mappingHtml += `</tr>`;
    });
    
    mappingHtml += `
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button onclick="resetForm()" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button onclick="processWithMapping()" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                        Process Upload
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.innerHTML = mappingHtml;
    
    // Store data for processing
    window.uploadData = data;
}

function processWithMapping() {
    const mapping = {};
    const selects = document.querySelectorAll('.column-mapping-select');
    
    selects.forEach(select => {
        const field = select.dataset.field;
        const value = select.value;
        mapping[field] = value === '' ? null : value;
    });
    
    // Validate required fields
    if (!mapping.member_id || !mapping.member_name) {
        showNotification('Please map both Member ID and Member Name fields', 'error');
        return;
    }
    
    // Show progress
    document.getElementById('upload-progress').classList.remove('hidden');
    updateProgress(50, 'Processing with column mapping...');
    
    const formData = new FormData();
    formData.append('column_mapping', JSON.stringify(mapping));
    formData.append('filename', window.uploadData.filename);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    fetch('{{ route("admin.fia-payment-records.process-upload") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        updateProgress(100, 'Processing complete!');
        
        setTimeout(() => {
            document.getElementById('upload-progress').classList.add('hidden');
            showUploadResult(data);
        }, 500);
    })
    .catch(error => {
        console.error('Processing error:', error);
        updateProgress(0, 'Processing failed');
        showNotification('Processing failed. Please try again.', 'error');
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function downloadTemplate() {
    // Create a sample CSV template with the exact structure
    const csvContent = `S/N,ID,NAME,Gawio la FIA,FIA iliyokomaa,Jumla,Malipo ya vipande yailiyakuwa Yamepelea,LOAN,Kiasi baki
1,MEM001,John Doe,50000,75000,125000,10000,20000,95000
2,MEM002,Jane Smith,60000,80000,140000,15000,15000,110000
3,MEM003,Bob Johnson,45000,65000,110000,8000,25000,77000`;

    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'fia_payment_records_template.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-md text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
