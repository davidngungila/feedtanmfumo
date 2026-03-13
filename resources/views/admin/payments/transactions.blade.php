@extends('layouts.app')

@section('title', 'All Transactions - Payments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">All Transactions</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                        <li class="breadcrumb-item active">All Transactions</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Transactions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['total_transactions']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
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
                                Total Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">TZS {{ number_format($statistics['total_amount'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Successful</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['successful_transactions']) }}</div>
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
                                Failed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['failed_transactions']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payments.transactions') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Transaction ID, Name, Member Number">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="method" class="form-label">Payment Method</label>
                        <select class="form-control" id="method" name="method">
                            <option value="">All Methods</option>
                            <option value="ussd_push" {{ request('method') == 'ussd_push' ? 'selected' : '' }}>USSD Push</option>
                            <option value="card_payment" {{ request('method') == 'card_payment' ? 'selected' : '' }}>Card Payment</option>
                            <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="mobile_money" {{ request('method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <a href="{{ route('admin.payments.transactions') }}" class="btn btn-secondary btn-block">Clear</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Transactions ({{ $transactions->total() }})</h6>
            <div>
                <button class="btn btn-sm btn-success" onclick="exportTransactions()">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Order Reference</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                <code>{{ $transaction->transaction_reference }}</code>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $transaction->user_name }}</strong><br>
                                    <small class="text-muted">{{ $transaction->member_number }}</small>
                                </div>
                            </td>
                            <td>
                                <strong>TZS {{ number_format($transaction->amount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge badge-{{ getPaymentMethodBadgeColor($transaction->payment_method) }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ getTransactionStatusBadgeColor($transaction->status) }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>
                                <code>{{ $transaction->order_reference }}</code>
                            </td>
                            <td>
                                {{ $transaction->created_at->format('d M Y, H:i') }}<br>
                                <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="viewTransaction({{ $transaction->id }})" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($transaction->status === 'pending')
                                    <button class="btn btn-warning" onclick="reconcileTransaction({{ $transaction->id }})" title="Reconcile">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                    @endif
                                    <button class="btn btn-secondary" onclick="downloadReceipt({{ $transaction->id }})" title="Download Receipt">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-search fa-3x text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No transactions found matching your criteria.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} entries
                </div>
                <div>
                    {{ $transactions->links() }}
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
                                <tr><td>Currency:</td><td>${transaction.currency}</td></tr>
                                <tr><td>Payment Method:</td><td>${transaction.payment_method}</td></tr>
                                <tr><td>Status:</td><td><span class="badge badge-${transaction.status === 'completed' ? 'success' : (transaction.status === 'failed' ? 'danger' : 'warning')}">${transaction.status}</span></td></tr>
                                <tr><td>Phone Number:</td><td>${transaction.phone_number || 'N/A'}</td></tr>
                                <tr><td>Created:</td><td>${new Date(transaction.created_at).toLocaleString()}</td></tr>
                                <tr><td>Updated:</td><td>${new Date(transaction.updated_at).toLocaleString()}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>User Information</h6>
                            <table class="table table-sm">
                                <tr><td>Name:</td><td><strong>${transaction.user_name}</strong></td></tr>
                                <tr><td>Member Number:</td><td><code>${transaction.member_number}</code></td></tr>
                                <tr><td>Email:</td><td>${transaction.email}</td></tr>
                                <tr><td>Phone:</td><td>${transaction.phone}</td></tr>
                                ${transaction.loan_number ? `<tr><td>Loan Number:</td><td><code>${transaction.loan_number}</code></td></tr>` : ''}
                            </table>
                        </div>
                    </div>
                    ${transaction.response_data ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>API Response Data</h6>
                            <pre class="bg-light p-3 rounded"><code>${JSON.stringify(JSON.parse(transaction.response_data), null, 2)}</code></pre>
                        </div>
                    </div>
                    ` : ''}
                `;
                document.getElementById('transactionDetails').innerHTML = detailsHtml;
                $('#transactionModal').modal('show');
            } else {
                alert('Error loading transaction details: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading transaction details');
        });
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
                alert('Error reconciling transaction: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error reconciling transaction');
        });
    }
}

function downloadReceipt(transactionId) {
    window.open(`/admin/payments/transactions/${transactionId}/receipt`, '_blank');
}

function exportTransactions() {
    const url = new URL(window.location.href);
    url.searchParams.set('export', '1');
    window.open(url.toString(), '_blank');
}
</script>

@php
function getPaymentMethodBadgeColor($method) {
    $colors = [
        'ussd_push' => 'primary',
        'card_payment' => 'info',
        'bank_transfer' => 'success',
        'mobile_money' => 'warning',
    ];
    return $colors[$method] ?? 'secondary';
}

function getTransactionStatusBadgeColor($status) {
    $colors = [
        'pending' => 'warning',
        'completed' => 'success',
        'failed' => 'danger',
        'cancelled' => 'secondary',
    ];
    return $colors[$status] ?? 'secondary';
}
@endphp
@endpush
