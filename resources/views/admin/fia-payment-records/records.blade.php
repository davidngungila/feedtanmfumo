@extends('layouts.admin')

@section('page-title', 'All Payment Records')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">All Payment Records</h1>
                <p class="text-gray-600 mt-1">View and manage all FIA payment records</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="exportRecords()" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Export to Excel
                </button>
                <a href="{{ route('admin.fia-payment-records.upload') }}" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Upload New Sheet
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" id="search" placeholder="Member ID or Name..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#015425]">
            </div>
            <div class="flex items-end">
                <button onclick="applyFilters()" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition mr-3">
                    Search
                </button>
                <button onclick="resetFilters()" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Records Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S/N</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gawio la FIA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FIA IlivyoKoma</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumla</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Malipo ya Vipande</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LOAN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kiasi Baki</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($records as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $records->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $record->member_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $record->member_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($record->gawio_la_fia, 0) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($record->fia_iliyokomaa, 0) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($record->jumla, 0) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($record->malipo_ya_vipande_yaliyokuwa_yamepelea, 0) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($record->loan, 0) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($record->kiasi_baki, 0) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <button onclick="deleteRecord({{ $record->id }})" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-500">No records found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
            <div class="text-sm text-gray-700">
                Showing {{ $records->firstItem() }} to {{ $records->lastItem() }} of {{ $records->total() }} results
            </div>
            <div class="flex space-x-2">
                {{ $records->links() }}
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;

document.addEventListener('DOMContentLoaded', function() {
    loadRecords();
});

function loadRecords(page = 1) {
    currentPage = page;
    
    const params = new URLSearchParams({
        page: page,
        search: document.getElementById('search').value
    });

    fetch('{{ route("admin.fia-payment-records.records-data") }}?' + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderRecordsTable(data.data);
            updatePagination(data.data);
        }
    })
    .catch(error => {
        console.error('Error loading records:', error);
    });
}

function renderRecordsTable(records) {
    const tbody = document.getElementById('records-table-body');
    
    if (records.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="10" class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500">No records found</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = records.data.map((record, index) => {
        const serialNumber = (records.from || 1) + index;
        
        return `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">${serialNumber}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${record.member_id}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${record.member_name || 'N/A'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${number_format(record.gawio_la_fia, 0)}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${number_format(record.fia_iliyokomaa, 0)}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">${number_format(record.jumla, 0)}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${number_format(record.malipo_ya_vipande_yaliyokuwa_yamepelea, 0)}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${number_format(record.loan, 0)}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${number_format(record.kiasi_baki, 0)}</td>
                <td class="px-6 py-4 text-sm">
                    <button onclick="deleteRecord(${record.id})" 
                            class="text-red-600 hover:text-red-900">
                        Delete
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

function updatePagination(records) {
    document.getElementById('showing-from').textContent = records.from || 0;
    document.getElementById('showing-to').textContent = records.to || 0;
    document.getElementById('total-results').textContent = records.total || 0;
    
    document.getElementById('prev-page').disabled = !records.prev_page_url;
    document.getElementById('next-page').disabled = !records.next_page_url;
}

function changePage(direction) {
    if (direction === 'prev' && currentPage > 1) {
        loadRecords(currentPage - 1);
    } else if (direction === 'next') {
        loadRecords(currentPage + 1);
    }
}

function applyFilters() {
    loadRecords(1);
}

function resetFilters() {
    document.getElementById('search').value = '';
    loadRecords(1);
}

function deleteRecord(id) {
    if (confirm('Are you sure you want to delete this record?')) {
        fetch(`/admin/fia-payment-records/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting record');
        });
    }
}

function exportRecords() {
    window.location.href = '{{ route("admin.fia-payment-records.export") }}';
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
