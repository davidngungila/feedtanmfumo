@extends('layouts.admin')

@section('page-title', 'All Payment Confirmations')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">All Payment Confirmations</h1>
                <p class="text-gray-600 mt-1">View and manage all FIA payment confirmations</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="exportPayments()" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" id="search" placeholder="Membership code, name, or reference..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#015425]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#015425]">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="verified">Verified</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" id="date_from" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#015425]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" id="date_to" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#015425]">
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <button onclick="applyFilters()" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                Apply Filters
            </button>
            <button onclick="resetFilters()" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                Reset Filters
            </button>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6" id="bulk-actions" style="display: none;">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-sm text-gray-700">
                    <span id="selected-count">0</span> items selected
                </span>
            </div>
            <div class="flex space-x-3">
                <button onclick="bulkVerify('verified')" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    Mark as Verified
                </button>
                <button onclick="bulkVerify('rejected')" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    Mark as Rejected
                </button>
                <button onclick="clearSelection()" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Clear Selection
                </button>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membership Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="payments-table-body" class="bg-white divide-y divide-gray-200">
                    <!-- Will be loaded via AJAX -->
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="animate-spin inline-block w-6 h-6 border-2 border-[#015425] border-t-transparent rounded-full"></div>
                            <p class="text-gray-500 mt-2">Loading payments...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
            <div class="text-sm text-gray-700">
                Showing <span id="showing-from">0</span> to <span id="showing-to">0</span> of <span id="total-results">0</span> results
            </div>
            <div class="flex space-x-2">
                <button onclick="changePage('prev')" id="prev-page" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 disabled:opacity-50" disabled>
                    Previous
                </button>
                <button onclick="changePage('next')" id="next-page" class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 disabled:opacity-50" disabled>
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Verification Modal -->
<div id="verification-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Verify Payment</h3>
            <form id="verification-form">
                <input type="hidden" id="payment-id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="verification-status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#015425]" required>
                        <option value="verified">Verified</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="verification-notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#015425]"
                              placeholder="Add verification notes..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeVerificationModal()" 
                            class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019]">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let selectedPayments = new Set();

document.addEventListener('DOMContentLoaded', function() {
    loadPayments();
    setupEventListeners();
});

function setupEventListeners() {
    // Select all checkbox
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="payment_checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            const paymentId = parseInt(checkbox.value);
            if (this.checked) {
                selectedPayments.add(paymentId);
            } else {
                selectedPayments.delete(paymentId);
            }
        });
        updateBulkActions();
    });

    // Verification form
    document.getElementById('verification-form').addEventListener('submit', function(e) {
        e.preventDefault();
        submitVerification();
    });

    // Filter inputs
    ['search', 'status', 'date_from', 'date_to'].forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            if (id === 'status') {
                applyFilters();
            }
        });
    });
}

function loadPayments(page = 1) {
    currentPage = page;
    
    const params = new URLSearchParams({
        page: page,
        search: document.getElementById('search').value,
        status: document.getElementById('status').value,
        date_from: document.getElementById('date_from').value,
        date_to: document.getElementById('date_to').value
    });

    fetch('{{ route("admin.fia-payments.confirmations-data") }}?' + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderPaymentsTable(data.data);
            updatePagination(data.data);
        }
    })
    .catch(error => {
        console.error('Error loading payments:', error);
    });
}

function renderPaymentsTable(payments) {
    const tbody = document.getElementById('payments-table-body');
    
    if (payments.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500">No payments found</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = payments.data.map(payment => {
        const statusColor = payment.status === 'verified' ? 'green' : 
                          payment.status === 'rejected' ? 'red' : 'yellow';
        const statusBg = payment.status === 'verified' ? 'bg-green-100 text-green-800' : 
                       payment.status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800';
        
        return `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <input type="checkbox" name="payment_checkbox" value="${payment.id}" 
                           class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]"
                           ${selectedPayments.has(payment.id) ? 'checked' : ''}>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">${payment.id}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${payment.membership_code}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${payment.member_name || 'N/A'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${payment.reference_number}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${number_format(payment.amount, 0)}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${payment.payment_date}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full ${statusBg}">
                        ${payment.status}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm">
                    <button onclick="openVerificationModal(${payment.id})" 
                            class="text-[#015425] hover:text-[#013019] mr-3">
                        Verify
                    </button>
                    <button onclick="deletePayment(${payment.id})" 
                            class="text-red-600 hover:text-red-900">
                        Delete
                    </button>
                </td>
            </tr>
        `;
    }).join('');

    // Reattach checkbox listeners
    document.querySelectorAll('input[name="payment_checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const paymentId = parseInt(this.value);
            if (this.checked) {
                selectedPayments.add(paymentId);
            } else {
                selectedPayments.delete(paymentId);
            }
            updateBulkActions();
        });
    });
}

function updatePagination(payments) {
    document.getElementById('showing-from').textContent = payments.from || 0;
    document.getElementById('showing-to').textContent = payments.to || 0;
    document.getElementById('total-results').textContent = payments.total || 0;
    
    document.getElementById('prev-page').disabled = !payments.prev_page_url;
    document.getElementById('next-page').disabled = !payments.next_page_url;
}

function changePage(direction) {
    if (direction === 'prev' && currentPage > 1) {
        loadPayments(currentPage - 1);
    } else if (direction === 'next') {
        loadPayments(currentPage + 1);
    }
}

function applyFilters() {
    loadPayments(1);
}

function resetFilters() {
    document.getElementById('search').value = '';
    document.getElementById('status').value = '';
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    loadPayments(1);
}

function updateBulkActions() {
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    if (selectedPayments.size > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = selectedPayments.size;
    } else {
        bulkActions.style.display = 'none';
    }
}

function clearSelection() {
    selectedPayments.clear();
    document.querySelectorAll('input[name="payment_checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('select-all').checked = false;
    updateBulkActions();
}

function openVerificationModal(paymentId) {
    document.getElementById('payment-id').value = paymentId;
    document.getElementById('verification-modal').classList.remove('hidden');
}

function closeVerificationModal() {
    document.getElementById('verification-modal').classList.add('hidden');
    document.getElementById('verification-form').reset();
}

function submitVerification() {
    const paymentId = document.getElementById('payment-id').value;
    const status = document.getElementById('verification-status').value;
    const notes = document.getElementById('verification-notes').value;

    fetch('{{ route("admin.fia-payments.verify-confirmation") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            payment_id: paymentId,
            status: status,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeVerificationModal();
            loadPayments(currentPage);
            // Show success message
            showNotification('Payment verified successfully', 'success');
        }
    })
    .catch(error => {
        console.error('Error verifying payment:', error);
        showNotification('Error verifying payment', 'error');
    });
}

function bulkVerify(status) {
    if (selectedPayments.size === 0) return;

    if (!confirm(`Are you sure you want to mark ${selectedPayments.size} payments as ${status}?`)) {
        return;
    }

    fetch('{{ route("admin.fia-payments.bulk-verify") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            payment_ids: Array.from(selectedPayments),
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            clearSelection();
            loadPayments(currentPage);
            showNotification(data.message, 'success');
        }
    })
    .catch(error => {
        console.error('Error bulk verifying payments:', error);
        showNotification('Error updating payments', 'error');
    });
}

function deletePayment(paymentId) {
    if (!confirm('Are you sure you want to delete this payment?')) {
        return;
    }

    fetch(`{{ route('admin.fia-payments.destroy', ':id') }}`.replace(':id', paymentId), {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadPayments(currentPage);
            showNotification('Payment deleted successfully', 'success');
        }
    })
    .catch(error => {
        console.error('Error deleting payment:', error);
        showNotification('Error deleting payment', 'error');
    });
}

function exportPayments() {
    const params = new URLSearchParams({
        search: document.getElementById('search').value,
        status: document.getElementById('status').value,
        date_from: document.getElementById('date_from').value,
        date_to: document.getElementById('date_to').value
    });

    window.open('{{ route("admin.fia-payments.export") }}?' + params.toString(), '_blank');
}

function showNotification(message, type) {
    // Simple notification implementation
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
