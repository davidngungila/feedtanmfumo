@extends('layouts.app')

@section('title', 'Checkout Payments - Payments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Checkout Payments</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                        <li class="breadcrumb-item active">Checkout Payments</li>
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
                                Active Checkouts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['active_checkouts'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
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
                                Completed Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['completed_today'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">TZS {{ number_format($statistics['total_revenue'] ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Conversion Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($statistics['total_checkouts'] ?? 0 > 0)
                                    {{ round(($statistics['completed_today'] / $statistics['total_checkouts']) * 100, 1) }}%
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

    <div class="row">
        <!-- Generate Checkout Link Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Generate Checkout Link</h6>
                </div>
                <div class="card-body">
                    <form id="checkoutLinkForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="totalPrice" class="form-label">Total Price *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">TZS</span>
                                        </div>
                                        <input type="text" class="form-control" id="totalPrice" name="totalPrice" required>
                                    </div>
                                    <small class="form-text text-muted">Enter amount without commas (e.g., 10000.50)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="orderCurrency" class="form-label">Currency *</label>
                                    <select class="form-control" id="orderCurrency" name="orderCurrency" required>
                                        <option value="TZS">Tanzanian Shilling (TZS)</option>
                                        <option value="USD">US Dollar (USD)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="orderReference" class="form-label">Order Reference *</label>
                                    <input type="text" class="form-control" id="orderReference" name="orderReference" required>
                                    <small class="form-text text-muted">Unique identifier for this order</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customerName" class="form-label">Customer Name</label>
                                    <input type="text" class="form-control" id="customerName" name="customerName">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customerEmail" class="form-label">Customer Email</label>
                                    <input type="email" class="form-control" id="customerEmail" name="customerEmail">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customerPhone" class="form-label">Customer Phone</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+255</span>
                                        </div>
                                        <input type="text" class="form-control" id="customerPhone" name="customerPhone" pattern="[0-9]{9}" maxlength="9">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">Order Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-link"></i> Generate Checkout Link
                            </button>
                            <button type="reset" class="btn btn-secondary ml-2">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Checkout Link Results -->
            <div class="card shadow mb-4" id="checkoutResultsCard" style="display: none;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Checkout Link Generated</h6>
                </div>
                <div class="card-body" id="checkoutResults">
                    <!-- Checkout results will be displayed here -->
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <!-- API Documentation -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Checkout API Reference</h6>
                </div>
                <div class="card-body">
                    <h6>Supported Currencies</h6>
                    <ul class="small">
                        <li><strong>TZS:</strong> Tanzanian Shilling</li>
                        <li><strong>USD:</strong> US Dollar</li>
                    </ul>

                    <h6 class="mt-3">Required Fields</h6>
                    <ul class="small">
                        <li><strong>totalPrice:</strong> Order amount</li>
                        <li><strong>orderReference:</strong> Unique order ID</li>
                        <li><strong>orderCurrency:</strong> Currency code</li>
                    </ul>

                    <h6 class="mt-3">Optional Fields</h6>
                    <ul class="small">
                        <li><strong>customerName:</strong> Customer full name</li>
                        <li><strong>customerEmail:</strong> Customer email</li>
                        <li><strong>customerPhone:</strong> Customer phone</li>
                        <li><strong>description:</strong> Order description</li>
                    </ul>

                    <h6 class="mt-3">Response Format</h6>
                    <pre class="bg-light p-2 rounded small">
{
  "checkoutLink": "https://checkout...",
  "clientId": "your-client-id"
}
                    </pre>
                </div>
            </div>

            <!-- Recent Checkout Links -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Checkout Links</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody id="recentCheckouts">
                                <!-- Recent checkouts will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Sessions Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Checkout Sessions</h6>
            <div>
                <button class="btn btn-sm btn-info" onclick="refreshCheckoutSessions()">
                    <i class="fas fa-refresh"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Session ID</th>
                            <th>Order Reference</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Completed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="checkoutSessions">
                        <!-- Checkout sessions will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Details Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Checkout Session Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="checkoutDetails">
                <!-- Checkout details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('checkoutLinkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    generateCheckoutLink();
});

function generateCheckoutLink() {
    const form = document.getElementById('checkoutLinkForm');
    const formData = new FormData(form);
    
    const payload = {
        totalPrice: formData.get('totalPrice'),
        orderReference: formData.get('orderReference'),
        orderCurrency: formData.get('orderCurrency'),
    };

    if (formData.get('customerName')) payload.customerName = formData.get('customerName');
    if (formData.get('customerEmail')) payload.customerEmail = formData.get('customerEmail');
    if (formData.get('customerPhone')) payload.customerPhone = '255' + formData.get('customerPhone');
    if (formData.get('description')) payload.description = formData.get('description');

    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

    fetch('/admin/payments/generate-checkout-link', {
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
        submitBtn.innerHTML = '<i class="fas fa-link"></i> Generate Checkout Link';

        if (data.status === 'success') {
            displayCheckoutResults(data.data);
            refreshCheckoutSessions();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-link"></i> Generate Checkout Link';
        console.error('Error:', error);
        alert('Error generating checkout link');
    });
}

function displayCheckoutResults(data) {
    const html = `
        <div class="alert alert-success">
            <h6><i class="fas fa-check-circle"></i> Checkout Link Generated Successfully!</h6>
        </div>
        <div class="row">
            <div class="col-md-8">
                <h6>Checkout Link</h6>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="checkoutLinkInput" value="${data.checkoutLink}" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="copyCheckoutLink()">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                        <button class="btn btn-outline-primary" type="button" onclick="openCheckoutLink()">
                            <i class="fas fa-external-link-alt"></i> Open
                        </button>
                    </div>
                </div>
                <small class="form-text text-muted">Share this link with customers to complete payment</small>
            </div>
            <div class="col-md-4">
                <h6>Client ID</h6>
                <p><code>${data.clientId}</code></p>
                <div class="mt-3">
                    <button class="btn btn-sm btn-info" onclick="shareCheckoutLink()">
                        <i class="fas fa-share"></i> Share
                    </button>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6>Next Steps</h6>
                <ul>
                    <li>Share the checkout link with your customer</li>
                    <li>Customer will be redirected to ClickPesa secure payment page</li>
                    <li>Customer can pay via mobile money, card, or bank transfer</li>
                    <li>Monitor payment status in the checkout sessions table</li>
                    <li>You'll receive notifications when payment is completed</li>
                </ul>
            </div>
        </div>
    `;

    document.getElementById('checkoutResults').innerHTML = html;
    document.getElementById('checkoutResultsCard').style.display = 'block';
}

function copyCheckoutLink() {
    const input = document.getElementById('checkoutLinkInput');
    input.select();
    document.execCommand('copy');
    alert('Checkout link copied to clipboard!');
}

function openCheckoutLink() {
    const link = document.getElementById('checkoutLinkInput').value;
    window.open(link, '_blank');
}

function shareCheckoutLink() {
    const link = document.getElementById('checkoutLinkInput').value;
    if (navigator.share) {
        navigator.share({
            title: 'Payment Checkout Link',
            text: 'Complete your payment using this secure checkout link',
            url: link
        });
    } else {
        copyCheckoutLink();
    }
}

function refreshCheckoutSessions() {
    // Load recent checkout links
    fetch('/admin/payments/recent-checkout-sessions')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('recentCheckouts');
                tbody.innerHTML = '';
                
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No recent checkout sessions</td></tr>';
                    return;
                }

                data.data.forEach(session => {
                    tbody.innerHTML += `
                        <tr>
                            <td><code>${session.orderReference}</code></td>
                            <td>${session.orderCurrency} ${parseFloat(session.totalPrice).toLocaleString()}</td>
                            <td><span class="badge badge-${session.status === 'completed' ? 'success' : 'warning'}">${session.status}</span></td>
                            <td><small>${new Date(session.created_at).toLocaleTimeString()}</small></td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error loading recent checkout sessions:', error);
        });

    // Load all checkout sessions
    fetch('/admin/payments/all-checkout-sessions')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('checkoutSessions');
                tbody.innerHTML = '';
                
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4"><i class="fas fa-shopping-cart fa-3x text-gray-300 mb-3"></i><p class="text-gray-500">No checkout sessions found.</p></td></tr>';
                    return;
                }

                data.data.forEach(session => {
                    tbody.innerHTML += `
                        <tr>
                            <td><code>${session.session_id}</code></td>
                            <td><code>${session.orderReference}</code></td>
                            <td>${session.customer_name || 'N/A'}</td>
                            <td><strong>${session.orderCurrency} ${parseFloat(session.totalPrice).toLocaleString()}</strong></td>
                            <td><span class="badge badge-info">${session.orderCurrency}</span></td>
                            <td><span class="badge badge-${session.status === 'completed' ? 'success' : (session.status === 'failed' ? 'danger' : 'warning')}">${session.status}</span></td>
                            <td>${new Date(session.created_at).toLocaleString()}</td>
                            <td>${session.completed_at ? new Date(session.completed_at).toLocaleString() : 'N/A'}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="viewCheckoutSession('${session.session_id}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($session->status === 'completed')
                                    <button class="btn btn-success" onclick="downloadReceipt('${session.session_id}')" title="Download Receipt">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error loading all checkout sessions:', error);
        });
}

function viewCheckoutSession(sessionId) {
    fetch(`/admin/payments/checkout-session-details/${sessionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const session = data.data;
                let detailsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Session Information</h6>
                            <table class="table table-sm">
                                <tr><td>Session ID:</td><td><code>${session.session_id}</code></td></tr>
                                <tr><td>Order Reference:</td><td><code>${session.orderReference}</code></td></tr>
                                <tr><td>Amount:</td><td><strong>${session.orderCurrency} ${parseFloat(session.totalPrice).toLocaleString()}</strong></td></tr>
                                <tr><td>Currency:</td><td>${session.orderCurrency}</td></tr>
                                <tr><td>Status:</td><td><span class="badge badge-${session.status === 'completed' ? 'success' : (session.status === 'failed' ? 'danger' : 'warning')}">${session.status}</span></td></tr>
                                <tr><td>Created:</td><td>${new Date(session.created_at).toLocaleString()}</td></tr>
                                <tr><td>Completed:</td><td>${session.completed_at ? new Date(session.completed_at).toLocaleString() : 'Not completed'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Customer Information</h6>
                            <table class="table table-sm">
                                <tr><td>Name:</td><td>${session.customer_name || 'N/A'}</td></tr>
                                <tr><td>Email:</td><td>${session.customer_email || 'N/A'}</td></tr>
                                <tr><td>Phone:</td><td>${session.customer_phone || 'N/A'}</td></tr>
                                <tr><td>Description:</td><td>${session.description || 'N/A'}</td></tr>
                            </table>
                        </div>
                    </div>
                    ${session.checkout_link ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Checkout Link</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" value="${session.checkout_link}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" onclick="copyCheckoutLink()">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="openCheckoutLink()">
                                        <i class="fas fa-external-link-alt"></i> Open
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                `;
                document.getElementById('checkoutDetails').innerHTML = detailsHtml;
                $('#checkoutModal').modal('show');
            } else {
                alert('Error loading checkout session details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading checkout session details');
        });
}

function downloadReceipt(sessionId) {
    window.open(`/admin/payments/checkout-receipt/${sessionId}`, '_blank');
}

// Auto-generate order reference
document.getElementById('totalPrice').addEventListener('input', function() {
    const orderRef = document.getElementById('orderReference');
    if (!orderRef.value) {
        orderRef.value = 'CHK-' + Date.now();
    }
});

// Load checkout sessions on page load
document.addEventListener('DOMContentLoaded', function() {
    refreshCheckoutSessions();
});
</script>
@endpush
