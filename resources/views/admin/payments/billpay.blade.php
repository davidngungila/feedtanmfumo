@extends('layouts.app')

@section('title', 'BillPay - Payments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">BillPay</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                        <li class="breadcrumb-item active">BillPay</li>
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
                                Total Control Numbers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['total_control_numbers'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
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
                                Active Bills</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['active_bills'] ?? 0) }}</div>
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Paid Bills</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['paid_bills'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-check-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Create Control Number Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create Control Number</h6>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="controlNumberTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="order-tab" data-toggle="tab" href="#order" role="tab" aria-controls="order" aria-selected="true">Order Control Number</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="customer-tab" data-toggle="tab" href="#customer" role="tab" aria-controls="customer" aria-selected="false">Customer Control Number</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="bulk-tab" data-toggle="tab" href="#bulk" role="tab" aria-controls="bulk" aria-selected="false">Bulk Create</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="controlNumberTabsContent">
                        <!-- Order Control Number Tab -->
                        <div class="tab-pane fade show active" id="order" role="tabpanel" aria-labelledby="order-tab">
                            <form id="orderControlForm" class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="billDescription" class="form-label">Bill Description *</label>
                                            <textarea class="form-control" id="billDescription" name="billDescription" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="billAmount" class="form-label">Bill Amount (TZS) *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">TZS</span>
                                                </div>
                                                <input type="number" class="form-control" id="billAmount" name="billAmount" min="0.01" step="0.01" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="billPaymentMode" class="form-label">Payment Mode *</label>
                                            <select class="form-control" id="billPaymentMode" name="billPaymentMode" required>
                                                <option value="ALLOW_PARTIAL_AND_OVER_PAYMENT">Allow Partial & Over Payment</option>
                                                <option value="EXACT">Exact Amount Only</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="billReference" class="form-label">Custom Reference (Optional)</label>
                                            <input type="text" class="form-control" id="billReference" name="billReference" maxlength="50">
                                            <small class="form-text text-muted">Leave empty for auto-generated reference</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Order Control Number
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Customer Control Number Tab -->
                        <div class="tab-pane fade" id="customer" role="tabpanel" aria-labelledby="customer-tab">
                            <form id="customerControlForm" class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customerName" class="form-label">Customer Name *</label>
                                            <input type="text" class="form-control" id="customerName" name="customerName" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customerEmail" class="form-label">Customer Email</label>
                                            <input type="email" class="form-control" id="customerEmail" name="customerEmail">
                                            <small class="form-text text-muted">Required if phone not provided</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customerPhone" class="form-label">Customer Phone</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">+255</span>
                                                </div>
                                                <input type="text" class="form-control" id="customerPhone" name="customerPhone" pattern="[0-9]{9}" maxlength="9">
                                            </div>
                                            <small class="form-text text-muted">Required if email not provided</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customerBillAmount" class="form-label">Bill Amount (TZS) *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">TZS</span>
                                                </div>
                                                <input type="number" class="form-control" id="customerBillAmount" name="customerBillAmount" min="0.01" step="0.01" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customerBillDescription" class="form-label">Bill Description *</label>
                                            <textarea class="form-control" id="customerBillDescription" name="customerBillDescription" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customerBillPaymentMode" class="form-label">Payment Mode *</label>
                                            <select class="form-control" id="customerBillPaymentMode" name="customerBillPaymentMode" required>
                                                <option value="ALLOW_PARTIAL_AND_OVER_PAYMENT">Allow Partial & Over Payment</option>
                                                <option value="EXACT">Exact Amount Only</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-user-plus"></i> Create Customer Control Number
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Bulk Create Tab -->
                        <div class="tab-pane fade" id="bulk" role="tabpanel" aria-labelledby="bulk-tab">
                            <div class="mt-3">
                                <h6>Bulk Create Control Numbers</h6>
                                <p class="text-muted">Create multiple control numbers at once (max 50 per request)</p>
                                
                                <div class="form-group">
                                    <label for="bulkType" class="form-label">Bulk Type</label>
                                    <select class="form-control" id="bulkType" name="bulkType">
                                        <option value="order">Order Control Numbers</option>
                                        <option value="customer">Customer Control Numbers</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="bulkData" class="form-label">Bulk Data (JSON)</label>
                                    <textarea class="form-control" id="bulkData" name="bulkData" rows="10" placeholder='[{"billAmount": 25000, "billDescription": "Order 1"}]'></textarea>
                                    <small class="form-text text-muted">Enter JSON array of control number objects</small>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" onclick="createBulkControlNumbers()">
                                        <i class="fas fa-upload"></i> Bulk Create
                                    </button>
                                    <button type="button" class="btn btn-secondary ml-2" onclick="showBulkExample()">
                                        <i class="fas fa-info-circle"></i> Show Example
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Card -->
            <div class="card shadow mb-4" id="resultsCard" style="display: none;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Control Number Created</h6>
                </div>
                <div class="card-body" id="resultsContent">
                    <!-- Results will be displayed here -->
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <!-- API Documentation -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">BillPay API Reference</h6>
                </div>
                <div class="card-body">
                    <h6>Control Number Types</h6>
                    <ul class="small">
                        <li><strong>Order Control Number:</strong> For specific bills/invoices</li>
                        <li><strong>Customer Control Number:</strong> For customer-specific billing</li>
                        <li><strong>Bulk Create:</strong> Up to 50 control numbers at once</li>
                    </ul>

                    <h6 class="mt-3">Payment Modes</h6>
                    <ul class="small">
                        <li><strong>ALLOW_PARTIAL_AND_OVER_PAYMENT:</strong> Accept partial and over payments</li>
                        <li><strong>EXACT:</strong> Require exact amount only</li>
                    </ul>

                    <h6 class="mt-3">Response Format</h6>
                    <pre class="bg-light p-2 rounded small">
{
  "billPayNumber": "55042914871931",
  "billDescription": "Water Bill",
  "billAmount": 90900,
  "billPaymentMode": "EXACT"
}
                    </pre>
                </div>
            </div>

            <!-- Recent Control Numbers -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Control Numbers</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="recentControlNumbers">
                                <!-- Recent control numbers will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Control Numbers Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">All Control Numbers</h6>
            <div>
                <button class="btn btn-sm btn-info" onclick="refreshControlNumbers()">
                    <i class="fas fa-refresh"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Control Number</th>
                            <th>Description</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment Mode</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="allControlNumbers">
                        <!-- Control numbers will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Control Number Details Modal -->
<div class="modal fade" id="controlNumberModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Control Number Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="controlNumberDetails">
                <!-- Control number details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('orderControlForm').addEventListener('submit', function(e) {
    e.preventDefault();
    createOrderControlNumber();
});

document.getElementById('customerControlForm').addEventListener('submit', function(e) {
    e.preventDefault();
    createCustomerControlNumber();
});

function createOrderControlNumber() {
    const form = document.getElementById('orderControlForm');
    const formData = new FormData(form);
    
    const payload = {
        billDescription: formData.get('billDescription'),
        billAmount: parseFloat(formData.get('billAmount')),
        billPaymentMode: formData.get('billPaymentMode'),
    };

    if (formData.get('billReference')) {
        payload.billReference = formData.get('billReference');
    }

    fetch('/admin/payments/create-order-control-number', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            displayResults(data.data, 'Order Control Number');
            form.reset();
            refreshControlNumbers();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating order control number');
    });
}

function createCustomerControlNumber() {
    const form = document.getElementById('customerControlForm');
    const formData = new FormData(form);
    
    const payload = {
        customerName: formData.get('customerName'),
        billDescription: formData.get('customerBillDescription'),
        billAmount: parseFloat(formData.get('customerBillAmount')),
        billPaymentMode: formData.get('customerBillPaymentMode'),
    };

    if (formData.get('customerEmail')) {
        payload.customerEmail = formData.get('customerEmail');
    }
    if (formData.get('customerPhone')) {
        payload.customerPhone = '255' + formData.get('customerPhone');
    }

    fetch('/admin/payments/create-customer-control-number', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            displayResults(data.data, 'Customer Control Number');
            form.reset();
            refreshControlNumbers();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating customer control number');
    });
}

function createBulkControlNumbers() {
    const bulkType = document.getElementById('bulkType').value;
    const bulkData = document.getElementById('bulkData').value;
    
    if (!bulkData.trim()) {
        alert('Please enter bulk data');
        return;
    }

    try {
        const data = JSON.parse(bulkData);
        const payload = {
            controlNumbers: data
        };

        const endpoint = bulkType === 'order' 
            ? '/admin/payments/bulk-create-order-control-numbers'
            : '/admin/payments/bulk-create-customer-control-numbers';

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayBulkResults(data.data);
                refreshControlNumbers();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating bulk control numbers');
        });

    } catch (error) {
        alert('Invalid JSON format');
    }
}

function displayResults(data, type) {
    const html = `
        <div class="alert alert-success">
            <h6><i class="fas fa-check-circle"></i> ${type} Created Successfully!</h6>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h6>Control Number Information</h6>
                <table class="table table-sm">
                    <tr><td>Control Number:</td><td><code>${data.billPayNumber}</code></td></tr>
                    <tr><td>Description:</td><td>${data.billDescription}</td></tr>
                    <tr><td>Amount:</td><td><strong>TZS ${parseFloat(data.billAmount).toLocaleString()}</strong></td></tr>
                    <tr><td>Payment Mode:</td><td>${data.billPaymentMode}</td></tr>
                    ${data.billCustomerName ? `<tr><td>Customer:</td><td>${data.billCustomerName}</td></tr>` : ''}
                </table>
            </div>
            <div class="col-md-6">
                <h6>Next Steps</h6>
                <ul>
                    <li>Share the control number with customer</li>
                    <li>Customer can pay via mobile money, bank, or other methods</li>
                    <li>Monitor payment status in the system</li>
                    <li>Control number will remain active until payment is made</li>
                </ul>
                <div class="mt-3">
                    <button class="btn btn-info btn-sm" onclick="copyControlNumber('${data.billPayNumber}')">
                        <i class="fas fa-copy"></i> Copy Number
                    </button>
                </div>
            </div>
        </div>
    `;

    document.getElementById('resultsContent').innerHTML = html;
    document.getElementById('resultsCard').style.display = 'block';
}

function displayBulkResults(data) {
    let html = `
        <div class="alert alert-success">
            <h6><i class="fas fa-check-circle"></i> Bulk Creation Complete!</h6>
            <p>Created: ${data.created} | Failed: ${data.failed}</p>
        </div>
    `;

    if (data.created > 0) {
        html += `
            <h6>Successfully Created Control Numbers:</h6>
            <div class="list-group mb-3">
        `;
        
        data.billPayNumbers.forEach(number => {
            html += `<div class="list-group-item"><code>${number}</code></div>`;
        });
        
        html += '</div>';
    }

    if (data.failed > 0 && data.errors) {
        html += `
            <h6>Errors:</h6>
            <div class="list-group">
        `;
        
        data.errors.forEach(error => {
            html += `<div class="list-group-item list-group-item-danger">Item ${error.index + 1}: ${error.reason}</div>`;
        });
        
        html += '</div>';
    }

    document.getElementById('resultsContent').innerHTML = html;
    document.getElementById('resultsCard').style.display = 'block';
}

function copyControlNumber(number) {
    navigator.clipboard.writeText(number).then(() => {
        alert('Control number copied to clipboard!');
    });
}

function showBulkExample() {
    const bulkType = document.getElementById('bulkType').value;
    const example = bulkType === 'order' 
        ? `[
  {
    "billAmount": 25000,
    "billDescription": "Water Bill - July 2024",
    "billPaymentMode": "EXACT"
  },
  {
    "billAmount": 50000,
    "billDescription": "Electricity Bill",
    "billReference": "ELEC002",
    "billPaymentMode": "ALLOW_PARTIAL_AND_OVER_PAYMENT"
  }
]`
        : `[
  {
    "customerName": "John Doe",
    "customerEmail": "john@example.com",
    "billAmount": 25000,
    "billDescription": "Water Bill - July 2024",
    "billPaymentMode": "EXACT"
  },
  {
    "customerName": "Jane Smith",
    "customerPhone": "255712345678",
    "billAmount": 50000,
    "billDescription": "Electricity Bill",
    "billPaymentMode": "ALLOW_PARTIAL_AND_OVER_PAYMENT"
  }
]`;

    document.getElementById('bulkData').value = example;
}

function refreshControlNumbers() {
    // Load recent control numbers
    fetch('/admin/payments/recent-control-numbers')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('recentControlNumbers');
                tbody.innerHTML = '';
                
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No recent control numbers</td></tr>';
                    return;
                }

                data.data.forEach(cn => {
                    tbody.innerHTML += `
                        <tr>
                            <td><code>${cn.billPayNumber}</code></td>
                            <td>TZS ${parseFloat(cn.billAmount).toLocaleString()}</td>
                            <td><span class="badge badge-success">Active</span></td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error loading recent control numbers:', error);
        });

    // Load all control numbers
    fetch('/admin/payments/all-control-numbers')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('allControlNumbers');
                tbody.innerHTML = '';
                
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><i class="fas fa-receipt fa-3x text-gray-300 mb-3"></i><p class="text-gray-500">No control numbers found.</p></td></tr>';
                    return;
                }

                data.data.forEach(cn => {
                    tbody.innerHTML += `
                        <tr>
                            <td><code>${cn.billPayNumber}</code></td>
                            <td>${cn.billDescription}</td>
                            <td>${cn.billCustomerName || 'N/A'}</td>
                            <td><strong>TZS ${parseFloat(cn.billAmount).toLocaleString()}</strong></td>
                            <td><span class="badge badge-info">${cn.billPaymentMode}</span></td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>${new Date(cn.createdAt).toLocaleString()}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="viewControlNumber('${cn.billPayNumber}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning" onclick="updateControlNumber('${cn.billPayNumber}')" title="Update">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error loading all control numbers:', error);
        });
}

function viewControlNumber(billPayNumber) {
    fetch(`/admin/payments/query-billpay-details/${billPayNumber}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const cn = data.data;
                let detailsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Control Number Information</h6>
                            <table class="table table-sm">
                                <tr><td>Control Number:</td><td><code>${cn.billPayNumber}</code></td></tr>
                                <tr><td>Description:</td><td>${cn.billDescription}</td></tr>
                                <tr><td>Amount:</td><td><strong>TZS ${parseFloat(cn.billAmount).toLocaleString()}</strong></td></tr>
                                <tr><td>Payment Mode:</td><td>${cn.billPaymentMode}</td></tr>
                                <tr><td>Status:</td><td><span class="badge badge-success">Active</span></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Customer Information</h6>
                            <table class="table table-sm">
                                ${cn.billCustomerName ? `<tr><td>Name:</td><td><strong>${cn.billCustomerName}</strong></td></tr>` : ''}
                                <tr><td>Created:</td><td>${new Date(cn.createdAt).toLocaleString()}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                document.getElementById('controlNumberDetails').innerHTML = detailsHtml;
                $('#controlNumberModal').modal('show');
            } else {
                alert('Error loading control number details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading control number details');
        });
}

function updateControlNumber(billPayNumber) {
    // Implementation to update control number
    alert('Update control number functionality to be implemented');
}

// Load control numbers on page load
document.addEventListener('DOMContentLoaded', function() {
    refreshControlNumbers();
});
</script>
@endpush
