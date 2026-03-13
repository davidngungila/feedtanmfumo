@extends('layouts.app')

@section('title', 'USSD Push - Payments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">USSD Push</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                        <li class="breadcrumb-item active">USSD Push</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- USSD Push Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Initiate USSD Push Payment</h6>
                </div>
                <div class="card-body">
                    <form id="ussdPushForm">
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
                                    <small class="form-text text-muted">Minimum amount: TZS 100</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="orderReference" class="form-label">Order Reference *</label>
                                    <input type="text" class="form-control" id="orderReference" name="orderReference" required>
                                    <small class="form-text text-muted">Unique reference for this transaction</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phoneNumber" class="form-label">Phone Number *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+255</span>
                                        </div>
                                        <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" pattern="[0-9]{9}" maxlength="9" required>
                                    </div>
                                    <small class="form-text text-muted">Format: 712345678 (without country code)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fetchSenderDetails" class="form-label">Fetch Sender Details</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="fetchSenderDetails" name="fetchSenderDetails">
                                        <label class="form-check-label" for="fetchSenderDetails">
                                            Get sender account information
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Will fetch account name and provider</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-info" onclick="previewUssdPush()">
                                <i class="fas fa-eye"></i> Preview Request
                            </button>
                            <button type="submit" class="btn btn-primary ml-2" disabled id="initiateBtn">
                                <i class="fas fa-paper-plane"></i> Initiate USSD Push
                            </button>
                            <button type="reset" class="btn btn-secondary ml-2">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Results -->
            <div class="card shadow mb-4" id="previewCard" style="display: none;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Preview Results</h6>
                </div>
                <div class="card-body" id="previewResults">
                    <!-- Preview results will be displayed here -->
                </div>
            </div>

            <!-- Initiation Results -->
            <div class="card shadow mb-4" id="initiationCard" style="display: none;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">USSD Push Initiated</h6>
                </div>
                <div class="card-body" id="initiationResults">
                    <!-- Initiation results will be displayed here -->
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <!-- API Documentation -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">ClickPesa API Reference</h6>
                </div>
                <div class="card-body">
                    <h6>Preview USSD Push</h6>
                    <p class="text-muted small">Validates push details and verifies payment channels availability</p>
                    
                    <h6 class="mt-3">Request Parameters</h6>
                    <ul class="small">
                        <li><strong>amount</strong>: Payment amount (TZS)</li>
                        <li><strong>currency</strong>: Currency code (TZS)</li>
                        <li><strong>orderReference</strong>: Your unique order reference</li>
                        <li><strong>phoneNumber</strong>: Phone number (255712345678)</li>
                        <li><strong>fetchSenderDetails</strong>: Get sender info (true/false)</li>
                    </ul>

                    <h6 class="mt-3">Response Codes</h6>
                    <ul class="small">
                        <li><strong>200</strong>: Success</li>
                        <li><strong>400</strong>: Bad Request</li>
                        <li><strong>401</strong>: Unauthorized</li>
                        <li><strong>404</strong>: Not Found</li>
                        <li><strong>409</strong>: Conflict</li>
                    </ul>
                </div>
            </div>

            <!-- Recent USSD Pushes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent USSD Pushes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Phone</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody id="recentUssdPushes">
                                <!-- Recent USSD pushes will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let activeMethods = [];
let senderDetails = null;

document.getElementById('ussdPushForm').addEventListener('submit', function(e) {
    e.preventDefault();
    initiateUssdPush();
});

function previewUssdPush() {
    const form = document.getElementById('ussdPushForm');
    const formData = new FormData(form);
    
    const payload = {
        amount: formData.get('amount'),
        currency: 'TZS',
        orderReference: formData.get('orderReference'),
        phoneNumber: '255' + formData.get('phoneNumber'),
        fetchSenderDetails: document.getElementById('fetchSenderDetails').checked
    };

    // Show loading
    const previewBtn = event.target;
    previewBtn.disabled = true;
    previewBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

    fetch('/admin/payments/preview-ussd-push', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        previewBtn.disabled = false;
        previewBtn.innerHTML = '<i class="fas fa-eye"></i> Preview Request';

        if (data.status === 'success') {
            displayPreviewResults(data.data);
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        previewBtn.disabled = false;
        previewBtn.innerHTML = '<i class="fas fa-eye"></i> Preview Request';
        console.error('Error:', error);
        alert('Error previewing USSD push');
    });
}

function displayPreviewResults(data) {
    activeMethods = data.activeMethods || [];
    senderDetails = data.sender;

    let html = '<div class="row">';
    
    if (senderDetails) {
        html += `
            <div class="col-md-6">
                <h6>Sender Details</h6>
                <table class="table table-sm">
                    <tr><td>Account Name:</td><td><strong>${senderDetails.accountName}</strong></td></tr>
                    <tr><td>Account Number:</td><td><code>${senderDetails.accountNumber}</code></td></tr>
                    <tr><td>Provider:</td><td><span class="badge badge-primary">${senderDetails.accountProvider}</span></td></tr>
                </table>
            </div>
        `;
    }

    if (activeMethods.length > 0) {
        html += `
            <div class="col-md-6">
                <h6>Available Payment Methods</h6>
                <div class="list-group">
        `;
        
        activeMethods.forEach((method, index) => {
            const statusColor = method.status === 'AVAILABLE' ? 'success' : 'warning';
            html += `
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${method.name}</strong>
                            <br>
                            <small class="text-muted">Fee: TZS ${method.fee || 0}</small>
                        </div>
                        <span class="badge badge-${statusColor}">${method.status}</span>
                    </div>
                </div>
            `;
        });
        
        html += '</div></div>';
    }

    html += '</div>';

    // Show method selection if methods are available
    const availableMethods = activeMethods.filter(m => m.status === 'AVAILABLE');
    if (availableMethods.length > 0) {
        html += `
            <div class="mt-3">
                <h6>Select Payment Method</h6>
                <select class="form-control" id="selectedMethod">
                    <option value="">Select a method...</option>
        `;
        
        availableMethods.forEach(method => {
            html += `<option value="${method.name}">${method.name} (Fee: TZS ${method.fee || 0})</option>`;
        });
        
        html += `
                </select>
            </div>
        `;
        
        // Enable initiate button
        document.getElementById('initiateBtn').disabled = false;
    } else {
        html += '<div class="alert alert-warning">No available payment methods for this phone number.</div>';
        document.getElementById('initiateBtn').disabled = true;
    }

    document.getElementById('previewResults').innerHTML = html;
    document.getElementById('previewCard').style.display = 'block';
}

function initiateUssdPush() {
    const form = document.getElementById('ussdPushForm');
    const formData = new FormData(form);
    
    const selectedMethod = document.getElementById('selectedMethod');
    if (!selectedMethod.value) {
        alert('Please select a payment method from the preview results.');
        return;
    }

    const payload = {
        amount: formData.get('amount'),
        currency: 'TZS',
        orderReference: formData.get('orderReference'),
        phoneNumber: '255' + formData.get('phoneNumber'),
        selectedMethod: selectedMethod.value
    };

    // Show loading
    const initiateBtn = document.getElementById('initiateBtn');
    initiateBtn.disabled = true;
    initiateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Initiating...';

    fetch('/admin/payments/initiate-ussd-push', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        initiateBtn.disabled = false;
        initiateBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Initiate USSD Push';

        if (data.status === 'success') {
            displayInitiationResults(data);
            loadRecentUssdPushes(); // Refresh recent pushes
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        initiateBtn.disabled = false;
        initiateBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Initiate USSD Push';
        console.error('Error:', error);
        alert('Error initiating USSD push');
    });
}

function displayInitiationResults(data) {
    const html = `
        <div class="alert alert-success">
            <h6><i class="fas fa-check-circle"></i> USSD Push Initiated Successfully!</h6>
            <p>The customer will receive a USSD prompt on their phone to complete the payment.</p>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h6>Transaction Details</h6>
                <table class="table table-sm">
                    <tr><td>Transaction ID:</td><td><code>${data.transaction_id || 'N/A'}</code></td></tr>
                    <tr><td>Amount:</td><td><strong>TZS ${parseFloat(data.data?.amount || 0).toLocaleString()}</strong></td></tr>
                    <tr><td>Order Reference:</td><td><code>${data.data?.orderReference || 'N/A'}</code></td></tr>
                    <tr><td>Phone Number:</td><td><code>${data.data?.phoneNumber || 'N/A'}</code></td></tr>
                    <tr><td>Payment Method:</td><td>${data.data?.paymentMethod || 'N/A'}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Next Steps</h6>
                <ul>
                    <li>Customer will receive USSD prompt</li>
                    <li>Customer enters PIN to confirm</li>
                    <li>Transaction will be processed automatically</li>
                    <li>Check transaction status in All Transactions</li>
                </ul>
                <div class="mt-3">
                    <button class="btn btn-info btn-sm" onclick="checkTransactionStatus('${data.transaction_id}')">
                        <i class="fas fa-sync"></i> Check Status
                    </button>
                </div>
            </div>
        </div>
    `;

    document.getElementById('initiationResults').innerHTML = html;
    document.getElementById('initiationCard').style.display = 'block';
    
    // Reset form
    document.getElementById('ussdPushForm').reset();
    document.getElementById('previewCard').style.display = 'none';
    document.getElementById('initiateBtn').disabled = true;
}

function checkTransactionStatus(transactionId) {
    // Implementation to check transaction status
    alert('Transaction status check functionality to be implemented');
}

function loadRecentUssdPushes() {
    // Load recent USSD pushes
    fetch('/admin/payments/recent-ussd-pushes')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('recentUssdPushes');
                tbody.innerHTML = '';
                
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No recent USSD pushes</td></tr>';
                    return;
                }

                data.data.forEach(push => {
                    const statusColor = push.status === 'completed' ? 'success' : (push.status === 'failed' ? 'danger' : 'warning');
                    tbody.innerHTML += `
                        <tr>
                            <td><code>${push.phone_number}</code></td>
                            <td>TZS ${parseFloat(push.amount).toLocaleString()}</td>
                            <td><span class="badge badge-${statusColor}">${push.status}</span></td>
                            <td><small>${new Date(push.created_at).toLocaleTimeString()}</small></td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error loading recent USSD pushes:', error);
        });
}

// Load recent USSD pushes on page load
document.addEventListener('DOMContentLoaded', function() {
    loadRecentUssdPushes();
});

// Auto-generate order reference
document.getElementById('amount').addEventListener('input', function() {
    const orderRef = document.getElementById('orderReference');
    if (!orderRef.value) {
        orderRef.value = 'USSD-' + Date.now();
    }
});
</script>
@endpush
