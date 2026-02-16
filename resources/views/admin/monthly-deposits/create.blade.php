@extends('layouts.admin')

@section('page-title', 'Upload Monthly Deposits')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Import Deposit Data</h3>
                <p class="text-sm text-gray-500">Upload an Excel file containing member contributions</p>
            </div>
            <a href="{{ route('admin.monthly-deposits.index') }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>

        <form action="{{ route('admin.monthly-deposits.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Year Selection -->
                <div>
                    <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">Select Year</label>
                    <select name="year" id="year" class="w-full rounded-lg border-gray-300 focus:border-[#015425] focus:ring-[#015425] transition-all" required>
                        @php $currentYear = date('Y'); @endphp
                        @for($y = $currentYear; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ old('year', $currentYear) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Month Selection -->
                <div>
                    <label for="month" class="block text-sm font-semibold text-gray-700 mb-2">Select Month</label>
                    <select name="month" id="month" class="w-full rounded-lg border-gray-300 focus:border-[#015425] focus:ring-[#015425] transition-all" required>
                        @php $currentMonth = date('n'); @endphp
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ old('month', $currentMonth) == $m ? 'selected' : '' }}>
                                {{ date("F", mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- File Upload -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-4">Excel/CSV File</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-[#015425] transition-all bg-gray-50 group cursor-pointer" onclick="document.getElementById('excel_file').click()">
                    <div class="space-y-2 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-[#015425] transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <span class="relative cursor-pointer bg-white rounded-md font-medium text-[#015425] hover:text-[#027a3a] focus-within:outline-none">
                                Upload a file
                            </span>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">XLSX, XLS, CSV up to 10MB</p>
                        <p id="file-name" class="text-sm font-bold text-[#015425] mt-2"></p>
                    </div>
                </div>
                <input id="excel_file" name="excel_file" type="file" class="hidden" accept=".xlsx,.xls,.csv" required onchange="displayFileName(this)">
            </div>

            <!-- Guidelines -->
            <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                <h4 class="text-sm font-bold text-blue-800 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Mapping Instructions
                </h4>
                <p class="text-xs text-blue-700 leading-relaxed">
                    System will automatically detect columns based on names. Recommended column headers are:
                    <span class="font-bold">Member ID, Name, Savings, Shares, Welfare, Loan Principal, Loan Interest, Total, Notes</span>.
                </p>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-3 bg-[#015425] text-white rounded-lg hover:bg-[#027a3a] shadow-lg hover:shadow-xl transition-all font-bold text-lg">
                    Start Processing
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function displayFileName(input) {
    const fileName = input.files[0] ? input.files[0].name : '';
    document.getElementById('file-name').textContent = fileName ? 'Selected: ' + fileName : '';
}
</script>
@endsection
