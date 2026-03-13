@extends('layouts.app')

@section('title', 'Payments - FeedTan CMG')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Payments</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payments</li>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['total_transactions'] ?? 0) }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">TZS {{ number_format($statistics['total_amount'] ?? 0, 2) }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['successful_transactions'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['pending_transactions'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Services Grid -->
    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-exchange-alt fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">All Transactions</h5>
                    <p class="card-text">View and manage all payment transactions across the platform.</p>
                    <a href="{{ route('admin.payments.transactions') }}" class="btn btn-primary">View Transactions</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-file-invoice-dollar fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">BillPay</h5>
                    <p class="card-text">Process bill payments for utilities and service providers.</p>
                    <a href="{{ route('admin.payments.billpay') }}" class="btn btn-success">BillPay</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shopping-cart fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title">Checkout Payments</h5>
                    <p class="card-text">Manage checkout sessions and payment processing.</p>
                    <a href="{{ route('admin.payments.checkout') }}" class="btn btn-info">Checkout</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title">USSD Push</h5>
                    <p class="card-text">Initiate USSD push payments to mobile money users.</p>
                    <a href="{{ route('admin.payments.ussd-push') }}" class="btn btn-warning">USSD Push</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-credit-card fa-3x text-secondary"></i>
                    </div>
                    <h5 class="card-title">Card Payments</h5>
                    <p class="card-text">Process credit and debit card transactions.</p>
                    <a href="{{ route('admin.payments.card-payments') }}" class="btn btn-secondary">Card Payments</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-hand-holding-usd fa-3x text-danger"></i>
                    </div>
                    <h5 class="card-title">Payouts</h5>
                    <p class="card-text">Manage payouts and disbursements to recipients.</p>
                    <a href="{{ route('admin.payments.payouts') }}" class="btn btn-danger">Payouts</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-balance-scale fa-3x text-dark"></i>
                    </div>
                    <h5 class="card-title">Reconciliation</h5>
                    <p class="card-text">Reconcile transactions and resolve discrepancies.</p>
                    <a href="{{ route('admin.payments.reconciliation') }}" class="btn btn-dark">Reconciliation</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
                    <a href="{{ route('admin.payments.transactions') }}" class="btn btn-sm btn-primary">View All</a>
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
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($recentTransactions) && $recentTransactions->count() > 0)
                                    @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_reference }}</td>
                                        <td>{{ $transaction->user_name }}</td>
                                        <td>TZS {{ number_format($transaction->amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $transaction->payment_method === 'ussd_push' ? 'primary' : 'secondary' }}">
                                                {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'failed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="viewTransaction({{ $transaction->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">No recent transactions found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
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
                                <tr><td>Transaction ID:</td><td>${transaction.transaction_reference}</td></tr>
                                <tr><td>Order Reference:</td><td>${transaction.order_reference}</td></tr>
                                <tr><td>Amount:</td><td>TZS ${parseFloat(transaction.amount).toLocaleString()}</td></tr>
                                <tr><td>Currency:</td><td>${transaction.currency}</td></tr>
                                <tr><td>Payment Method:</td><td>${transaction.payment_method}</td></tr>
                                <tr><td>Status:</td><td><span class="badge badge-${transaction.status === 'completed' ? 'success' : (transaction.status === 'failed' ? 'danger' : 'warning')}">${transaction.status}</span></td></tr>
                                <tr><td>Created:</td><td>${new Date(transaction.created_at).toLocaleString()}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>User Information</h6>
                            <table class="table table-sm">
                                <tr><td>Name:</td><td>${transaction.user_name}</td></tr>
                                <tr><td>Member Number:</td><td>${transaction.member_number}</td></tr>
                                <tr><td>Email:</td><td>${transaction.email}</td></tr>
                                <tr><td>Phone:</td><td>${transaction.phone}</td></tr>
                                ${transaction.loan_number ? `<tr><td>Loan Number:</td><td>${transaction.loan_number}</td></tr>` : ''}
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
