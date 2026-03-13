@extends('layouts.app')

@section('title', 'Card Payments - Payments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Card Payments</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                        <li class="breadcrumb-item active">Card Payments</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Card Payment Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Process Card Payment</h6>
                </div>
                <div class="card-body">
                    <form id="cardPaymentForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount" class="form-label">Amount (TZS) *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">TZS</span>
                                        </div>
                                        <input type="number" class="form-control" id="amount" name="amount" min="100" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="orderReference" class="form-label">Order Reference *</label>
                                    <input type="text" class="form-control" id="orderReference" name="orderReference" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h6>Card Information</h6>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cardNumber" class="form-label">Card Number *</label>
                                    <input type="text" class="form-control" id="cardNumber" name="cardNumber" maxlength="16" pattern="[0-9]{16}" required>
                                    <small class="form-text text-muted">16-digit card number</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expiryMonth" class="form-label">Expiry Month *</label>
                                    <select class="form-control" id="expiryMonth" name="expiryMonth" required>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expiryYear" class="form-label">Expiry Year *</label>
                                    <select class="form-control" id="expiryYear" name="expiryYear" required>
                                        @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                            <option value="{{ substr($i, -2) }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cvv" class="form-label">CVV *</label>
                                    <input type="text" class="form-control" id="cvv" name="cvv" maxlength="4" pattern="[0-9]{3,4}" required>
                                    <small class="form-text text-muted">3 or 4 digit security code</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cardholderName" class="form-label">Cardholder Name *</label>
                                    <input type="text" class="form-control" id="cardholderName" name="cardholderName" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-credit-card"></i> Process Payment
                            </button>
                            <button type="reset" class="btn btn-secondary ml-2">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Card Payment Security</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-shield-alt"></i> Secure Processing</h6>
                        <p class="small mb-0">All card payments are processed securely through ClickPesa's PCI-DSS compliant payment gateway.</p>
                    </div>
                    
                    <h6>Accepted Cards</h6>
                    <div class="d-flex justify-content-around mb-3">
                        <i class="fab fa-cc-visa fa-2x text-primary"></i>
                        <i class="fab fa-cc-mastercard fa-2x text-danger"></i>
                        <i class="fab fa-cc-amex fa-2x text-info"></i>
                        <i class="fab fa-cc-discover fa-2x text-warning"></i>
                    </div>

                    <h6>Processing Fees</h6>
                    <ul class="small">
                        <li>Visa/Mastercard: 2.5% + TZS 500</li>
                        <li>Amex: 3.0% + TZS 800</li>
                        <li>International Cards: 3.5% + TZS 1000</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Card Transactions</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Card</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cardTransactions->take(5) as $transaction)
                                <tr>
                                    <td>****-****-****-{{ substr($transaction->last_four_digits ?? '0000', -4) }}</td>
                                    <td>TZS {{ number_format($transaction->amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td><small>{{ $transaction->created_at->format('H:i') }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent transactions</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Transactions Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Card Transactions History</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>User</th>
                            <th>Cardholder</th>
                            <th>Card Number</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cardTransactions as $transaction)
                        <tr>
                            <td><code>{{ $transaction->transaction_reference }}</code></td>
                            <td>{{ $transaction->user_name }}</td>
                            <td>{{ $transaction->cardholder_name }}</td>
                            <td>****-****-****-{{ substr($transaction->last_four_digits ?? '0000', -4) }}</td>
                            <td><strong>TZS {{ number_format($transaction->amount, 2) }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'failed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="viewCardTransaction({{ $transaction->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-credit-card fa-3x text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No card transactions found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $cardTransactions->firstItem() }} to {{ $cardTransactions->lastItem() }} of {{ $cardTransactions->total() }} entries
                </div>
                <div>
                    {{ $cardTransactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Card Transaction Details Modal -->
<div class="modal fade" id="cardTransactionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Card Transaction Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="cardTransactionDetails">
                <!-- Transaction details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('cardPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    processCardPayment();
});

function processCardPayment() {
    const form = document.getElementById('cardPaymentForm');
    const formData = new FormData(form);
    
    const payload = {
        amount: formData.get('amount'),
        currency: 'TZS',
        orderReference: formData.get('orderReference'),
        cardNumber: formData.get('cardNumber'),
        expiryMonth: formData.get('expiryMonth'),
        expiryYear: formData.get('expiryYear'),
        cvv: formData.get('cvv'),
        cardholderName: formData.get('cardholderName')
    };

    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    fetch('/admin/payments/process-card-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-credit-card"></i> Process Payment';

        if (data.status === 'success') {
            alert('Card payment processed successfully!');
            form.reset();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-credit-card"></i> Process Payment';
        console.error('Error:', error);
        alert('Error processing card payment');
    });
}

function viewCardTransaction(transactionId) {
    // Implementation to view card transaction details
    alert('Card transaction details functionality to be implemented');
}

// Auto-generate order reference
document.getElementById('amount').addEventListener('input', function() {
    const orderRef = document.getElementById('orderReference');
    if (!orderRef.value) {
        orderRef.value = 'CARD-' + Date.now();
    }
});

// Format card number input
document.getElementById('cardNumber').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});
</script>
@endpush
