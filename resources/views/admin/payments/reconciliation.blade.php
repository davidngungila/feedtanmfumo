@extends('layouts.app')

@section('title', 'Reconciliation - Payments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Reconciliation</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                        <li class="breadcrumb-item active">Reconciliation</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Reconciliation Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Reconciliation</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingReconciliation->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Reconciled Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reconciledToday }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Failed Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $failedReconciliation }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Success Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($reconciledToday + $failedReconciliation > 0)
                                    {{ round(($reconciledToday / ($reconciledToday + $failedReconciliation)) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Reconciliation -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-warning">Pending Reconciliation ({{ $pendingReconciliation->count() }})</h6>
            <div>
                <button class="btn btn-success btn-sm" onclick="reconcileAll()">
                    <i class="fas fa-sync"></i> Reconcile All
                </button>
                <button class="btn btn-warning btn-sm ml-2" onclick="refreshPending()">
                    <i class="fas fa-refresh"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>Transaction ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Age</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingReconciliation as $transaction)
                        <tr>
                            <td>
                                <input type="checkbox" class="transaction-checkbox" value="{{ $transaction->id }}">
                            </td>
                            <td><code>{{ $transaction->transaction_reference }}</code></td>
                            <td>{{ $transaction->user_name ?? 'N/A' }}</td>
                            <td><strong>TZS {{ number_format($transaction->amount, 2) }}</strong></td>
                            <td>
                                <span class="badge badge-secondary">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-warning">
                                    {{ \Carbon\Carbon::parse($transaction->created_at)->diffForHumans() }}
                                </span>
                            </td>
                            <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="viewTransaction({{ $transaction->id }})" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-success" onclick="reconcileTransaction({{ $transaction->id }})" title="Reconcile">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                    <button class="btn btn-danger" onclick="markAsFailed({{ $transaction->id }})" title="Mark as Failed">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-success">No pending transactions for reconciliation!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Reconciliation Actions -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Reconciliation Actions</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center">
                        <h5>Auto Reconcile</h5>
                        <p class="text-muted">Automatically reconcile pending transactions with ClickPesa API</p>
                        <button class="btn btn-primary" onclick="autoReconcile()">
                            <i class="fas fa-robot"></i> Auto Reconcile
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h5>Manual Reconcile</h5>
                        <p class="text-muted">Manually reconcile selected transactions</p>
                        <button class="btn btn-success" onclick="reconcileSelected()">
                            <i class="fas fa-hand-paper"></i> Reconcile Selected
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h5>Export Report</h5>
                        <p class="text-muted">Export reconciliation report for analysis</p>
                        <button class="btn btn-info" onclick="exportReconciliationReport()">
                            <i class="fas fa-download"></i> Export Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="transactionDetails">
                <!-- Transaction details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.transaction-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function reconcileAll() {
    if (confirm('Are you sure you want to reconcile all pending transactions?')) {
        const transactionIds = Array.from(document.querySelectorAll('.transaction-checkbox')).map(cb => cb.value);
        
        fetch('/admin/payments/reconcile-transactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                transaction_ids: transactionIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('All transactions reconciled successfully');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error reconciling transactions');
        });
    }
}

function reconcileSelected() {
    const selectedCheckboxes = document.querySelectorAll('.transaction-checkbox:checked');
    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one transaction to reconcile');
        return;
    }

    if (confirm(`Are you sure you want to reconcile ${selectedCheckboxes.length} selected transaction(s)?`)) {
        const transactionIds = Array.from(selectedCheckboxes).map(cb => cb.value);
        
        fetch('/admin/payments/reconcile-transactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                transaction_ids: transactionIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Selected transactions reconciled successfully');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error reconciling transactions');
        });
    }
}

function reconcileTransaction(transactionId) {
    if (confirm('Are you sure you want to reconcile this transaction?')) {
        fetch('/admin/payments/reconcile-transactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                transaction_ids: [transactionId]
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Transaction reconciled successfully');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error reconciling transaction');
        });
    }
}

function markAsFailed(transactionId) {
    if (confirm('Are you sure you want to mark this transaction as failed?')) {
        // Implementation to mark transaction as failed
        alert('Mark as failed functionality to be implemented');
    }
}

function autoReconcile() {
    if (confirm('Start automatic reconciliation process? This may take a few minutes.')) {
        alert('Auto reconciliation functionality to be implemented');
    }
}

function refreshPending() {
    location.reload();
}

function exportReconciliationReport() {
    window.open('/admin/payments/export-reconciliation-report', '_blank');
}

function viewTransaction(transactionId) {
    fetch(`/admin/payments/transaction-details/${transactionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const transaction = data.data;
                let detailsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Transaction Information</h6>
                            <table class="table table-sm">
                                <tr><td>Transaction ID:</td><td><code>${transaction.transaction_reference}</code></td></tr>
                                <tr><td>Order Reference:</td><td><code>${transaction.order_reference}</code></td></tr>
                                <tr><td>Amount:</td><td><strong>TZS ${parseFloat(transaction.amount).toLocaleString()}</strong></td></tr>
                                <tr><td>Payment Method:</td><td>${transaction.payment_method}</td></tr>
                                <tr><td>Status:</td><td><span class="badge badge-warning">${transaction.status}</span></td></tr>
                                <tr><td>Created:</td><td>${new Date(transaction.created_at).toLocaleString()}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Reconciliation Info</h6>
                            <table class="table table-sm">
                                <tr><td>Age:</td><td>${\Carbon\Carbon::parse(transaction.created_at).diffForHumans()}</td></tr>
                                <tr><td>Last Updated:</td><td>${new Date(transaction.updated_at).toLocaleString()}</td></tr>
                                <tr><td>Reconciled:</td><td>${transaction.reconciled_at ? new Date(transaction.reconciled_at).toLocaleString() : 'Not reconciled'}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                document.getElementById('transactionDetails').innerHTML = detailsHtml;
                $('#transactionModal').modal('show');
            } else {
                alert('Error loading transaction details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading transaction details');
        });
}
</script>
@endpush
