@extends('layouts.admin')

@section('page-title', 'Upload Excel Sheet')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Upload Excel Sheet</h1>
                <p class="text-gray-600 mt-1">Upload FIA payment confirmations from Excel file</p>
            </div>
            <a href="{{ route('admin.fia-payments.confirmations') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                View All Confirmations
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
                            <p class="text-xs text-gray-500">XLSX, XLS, CSV (MAX. 10MB)</p>
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
            <p class="text-sm text-gray-700 mb-4">Your Excel file should contain the following columns:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Required Columns:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <strong>membership_code</strong> - Member membership code</li>
                        <li>• <strong>member_name</strong> - Full name of the member</li>
                        <li>• <strong>reference_number</strong> - Payment reference number</li>
                        <li>• <strong>amount</strong> - Payment amount</li>
                        <li>• <strong>payment_date</strong> - Date of payment (YYYY-MM-DD)</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Optional Columns:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <strong>notes</strong> - Additional payment notes</li>
                        <li>• <strong>payment_method</strong> - Method used for payment</li>
                        <li>• <strong>bank_name</strong> - Bank name (if applicable)</li>
                        <li>• <strong>account_number</strong> - Account number (if applicable)</li>
                    </ul>
                </div>
            </div>
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>Note:</strong> Make sure your Excel file has headers in the first row. The system will automatically map the columns based on the header names.
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
                file.type === 'text/csv') {
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

    fetch('{{ route("admin.fia-payments.process-upload") }}', {
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
    
    if (data.success) {
        resultContent.innerHTML = `
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-green-800 font-medium">Upload Successful!</h4>
                        <p class="text-green-700 text-sm mt-1">${data.message}</p>
                        <p class="text-green-600 text-xs mt-2">File saved as: ${data.filename}</p>
                    </div>
                </div>
                <div class="mt-4 flex space-x-3">
                    <a href="{{ route('admin.fia-payments.confirmations') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        View Confirmations
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

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function downloadTemplate() {
    // Create a sample CSV template
    const csvContent = `membership_code,member_name,reference_number,amount,payment_date,notes,payment_method,bank_name,account_number
MEM001,John Doe,REF001,50000,2024-01-15,Monthly payment,Bank Transfer,CNBC,1234567890
MEM002,Jane Smith,REF002,75000,2024-01-15,Monthly payment,Mobile Money,M-Pesa,255712345678
MEM003,Bob Johnson,REF003,45000,2024-01-16,Monthly payment,Bank Transfer,CRDB,9876543210`;

    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'fia_payment_template.csv';
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
