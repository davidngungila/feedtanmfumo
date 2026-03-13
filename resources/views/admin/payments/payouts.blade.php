@extends('layouts.app')

@section('title', 'Payouts - Payments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Payouts</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                        <li class="breadcrumb-item active">Payouts</li>
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
                                Total Payouts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['total_payouts'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['successful_payouts'] ?? 0) }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['pending_payouts'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Create Payout Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create Payout</h6>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="payoutTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="mobile-money-tab" data-toggle="tab" href="#mobile-money" role="tab" aria-controls="mobile-money" aria-selected="true">Mobile Money Payout</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="bank-transfer-tab" data-toggle="tab" href="#bank-transfer" role="tab" aria-controls="bank-transfer" aria-selected="false">Bank Transfer</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="payout-link-tab" data-toggle="tab" href="#payout-link" role="tab" aria-controls="payout-link" aria-selected="false">Generate Payout Link</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="payoutTabsContent">
                        <!-- Mobile Money Payout Tab -->
                        <div class="tab-pane fade show active" id="mobile-money" role="tabpanel" aria-labelledby="mobile-money-tab">
                            <form id="mobileMoneyPayoutForm" class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mmAmount" class="form-label">Amount (TZS) *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">TZS</span>
                                                </div>
                                                <input type="number" class="form-control" id="mmAmount" name="amount" min="100" step="0.01" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mmCurrency" class="form-label">Currency *</label>
                                            <select class="form-control" id="mmCurrency" name="currency" required>
                                                <option value="TZS">Tanzanian Shilling (TZS)</option>
                                                <option value="USD">US Dollar (USD)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mmPhoneNumber" class="form-label">Phone Number *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">+255</span>
                                                </div>
                                                <input type="text" class="form-control" id="mmPhoneNumber" name="phoneNumber" pattern="[0-9]{9}" maxlength="9" required>
                                            </div>
                                            <small class="form-text text-muted">Format: 712345678</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mmOrderReference" class="form-label">Order Reference *</label>
                                            <input type="text" class="form-control" id="mmOrderReference" name="orderReference" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-info" onclick="previewMobileMoneyPayout()">
                                        <i class="fas fa-eye"></i> Preview Payout
                                    </button>
                                    <button type="submit" class="btn btn-primary ml-2" disabled id="createMMPayoutBtn">
                                        <i class="fas fa-paper-plane"></i> Create Payout
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Bank Transfer Tab -->
                        <div class="tab-pane fade" id="bank-transfer" role="tabpanel" aria-labelledby="bank-transfer-tab">
                            <form id="bankTransferForm" class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="btAmount" class="form-label">Amount (TZS) *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">TZS</span>
                                                </div>
                                                <input type="number" class="form-control" id="btAmount" name="amount" min="100" step="0.01" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="btCurrency" class="form-label">Currency *</label>
                                            <select class="form-control" id="btCurrency" name="currency" required>
                                                <option value="TZS">Tanzanian Shilling (TZS)</option>
                                                <option value="USD">US Dollar (USD)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="btAccountNumber" class="form-label">Account Number *</label>
                                            <input type="text" class="form-control" id="btAccountNumber" name="accountNumber" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="btAccountName" class="form-label">Account Name *</label>
                                            <input type="text" class="form-control" id="btAccountName" name="accountName" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="btBank" class="form-label">Bank *</label>
                                            <select class="form-control" id="btBank" name="bic" required>
                                                <option value="">Select Bank</option>
                                                <!-- Banks will be loaded dynamically -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="btTransferType" class="form-label">Transfer Type *</label>
                                            <select class="form-control" id="btTransferType" name="transferType" required>
                                                <option value="ACH">ACH</option>
                                                <option value="RTGS">RTGS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="btOrderReference" class="form-label">Order Reference *</label>
                                            <input type="text" class="form-control" id="btOrderReference" name="orderReference" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-info" onclick="previewBankPayout()">
                                        <i class="fas fa-eye"></i> Preview Payout
                                    </button>
                                    <button type="submit" class="btn btn-primary ml-2" disabled id="createBTPayoutBtn">
                                        <i class="fas fa-paper-plane"></i> Create Payout
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Payout Link Tab -->
                        <div class="tab-pane fade" id="payout-link" role="tabpanel" aria-labelledby="payout-link-tab">
                            <form id="payoutLinkForm" class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="plAmount" class="form-label">Amount (TZS) *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">TZS</span>
                                                </div>
                                                <input type="text" class="form-control" id="plAmount" name="amount" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="plOrderReference" class="form-label">Order Reference *</label>
                                            <input type="text" class="form-control" id="plOrderReference" name="orderReference" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-link"></i> Generate Payout Link
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Results -->
            <div class="card shadow mb-4" id="previewCard" style="display: none;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Payout Preview</h6>
                </div>
                <div class="card-body" id="previewResults">
                    <!-- Preview results will be displayed here -->
                </div>
            </div>

            <!-- Payout Results -->
            <div class="card shadow mb-4" id="payoutResultsCard" style="display: none;">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Payout Created</h6>
                </div>
                <div class="card-body" id="payoutResults">
                    <!-- Payout results will be displayed here -->
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <!-- Account Balance -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Balance</h6>
                </div>
                <div class="card-body" id="accountBalance">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
                        <p class="text-muted">Loading balance...</p>
                    </div>
                </div>
            </div>

            <!-- Exchange Rates -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Exchange Rates</h6>
                </div>
                <div class="card-body" id="exchangeRates">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
                        <p class="text-muted">Loading rates...</p>
                    </div>
                </div>
            </div>

            <!-- Recent Payouts -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Payouts</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Recipient</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="recentPayouts">
                                <!-- Recent payouts will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payouts Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">All Payouts</h6>
            <div>
                <button class="btn btn-sm btn-info" onclick="refreshPayouts()">
                    <i class="fas fa-refresh"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Payout ID</th>
                            <th>Recipient</th>
                            <th>Amount</th>
                            <th>Channel</th>
                            <th>Provider</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="allPayouts">
                        <!-- Payouts will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Payout Details Modal -->
<div class="modal fade" id="payoutModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payout Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="payoutDetails">
                <!-- Payout details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let payoutPreviewData = null;

document.getElementById('mobileMoneyPayoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    createMobileMoneyPayout();
});

document.getElementById('bankTransferForm').addEventListener('submit', function(e) {
    e.preventDefault();
    createBankPayout();
});

document.getElementById('payoutLinkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    generatePayoutLink();
});

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    loadAccountBalance();
    loadExchangeRates();
    loadBanks();
    refreshPayouts();
});

function loadAccountBalance() {
    fetch('/admin/payments/retrieve-account-balance')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const balance = data.data[0];
                document.getElementById('accountBalance').innerHTML = `
                    <div class="text-center">
                        <h4 class="text-primary">TZS ${parseFloat(balance.balance).toLocaleString()}</h4>
                        <p class="text-muted">Available Balance</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading balance:', error);
            document.getElementById('accountBalance').innerHTML = '<p class="text-danger">Error loading balance</p>';
        });
}

function loadExchangeRates() {
    fetch('/admin/payments/retrieve-exchange-rates')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                let html = '';
                data.data.forEach(rate => {
                    html += `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>${rate.source} → ${rate.target}</span>
                            <strong>${rate.rate}</strong>
                        </div>
                    `;
                });
                document.getElementById('exchangeRates').innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error loading rates:', error);
            document.getElementById('exchangeRates').innerHTML = '<p class="text-danger">Error loading rates</p>';
        });
}

function loadBanks() {
    fetch('/admin/payments/retrieve-banks-list')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const bankSelect = document.getElementById('btBank');
                data.data.forEach(bank => {
                    const option = document.createElement('option');
                    option.value = bank.bic;
                    option.textContent = bank.name;
                    bankSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading banks:', error);
        });
}

function previewMobileMoneyPayout() {
    const form = document.getElementById('mobileMoneyPayoutForm');
    const formData = new FormData(form);
    
    const payload = {
        amount: parseFloat(formData.get('amount')),
        phoneNumber: '255' + formData.get('phoneNumber'),
        currency: formData.get('currency'),
        orderReference: formData.get('orderReference'),
    };

    fetch('/admin/payments/preview-mobile-money-payout', {
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
            payoutPreviewData = data.data;
            displayPayoutPreview(data.data, 'Mobile Money');
            document.getElementById('createMMPayoutBtn').disabled = false;
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error previewing mobile money payout');
    });
}

function previewBankPayout() {
    const form = document.getElementById('bankTransferForm');
    const formData = new FormData(form);
    
    const payload = {
        amount: parseFloat(formData.get('amount')),
        accountNumber: formData.get('accountNumber'),
        currency: formData.get('currency'),
        orderReference: formData.get('orderReference'),
        bic: formData.get('bic'),
        transferType: formData.get('transferType'),
        accountCurrency: 'TZS',
    };

    fetch('/admin/payments/preview-bank-payout', {
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
            payoutPreviewData = data.data;
            displayPayoutPreview(data.data, 'Bank Transfer');
            document.getElementById('createBTPayoutBtn').disabled = false;
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error previewing bank payout');
    });
}

function displayPayoutPreview(data, type) {
    const html = `
        <div class="alert alert-info">
            <h6><i class="fas fa-eye"></i> ${type} Payout Preview</h6>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h6>Payout Details</h6>
                <table class="table table-sm">
                    <tr><td>Total Amount:</td><td><strong>TZS ${parseFloat(data.amount).toLocaleString()}</strong></td></tr>
                    <tr><td>Payout Amount:</td><td>TZS ${parseFloat(data.order.amount).toLocaleString()}</td></tr>
                    <tr><td>Fee:</td><td>TZS ${parseFloat(data.fee).toLocaleString()}</td></tr>
                    <tr><td>Channel Provider:</td><td>${data.channelProvider}</td></tr>
                    ${data.transferType ? `<tr><td>Transfer Type:</td><td>${data.transferType}</td></tr>` : ''}
                    <tr><td>Fee Bearer:</td><td>${data.payoutFeeBearer}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Recipient Information</h6>
                <table class="table table-sm">
                    <tr><td>Name:</td><td><strong>${data.receiver.accountName || 'N/A'}</strong></td></tr>
                    <tr><td>Account:</td><td><code>${data.receiver.accountNumber}</code></td></tr>
                    <tr><td>Amount to Receive:</td><td><strong>TZS ${parseFloat(data.receiver.amount).toLocaleString()}</strong></td></tr>
                </table>
            </div>
        </div>
        ${data.exchanged ? `
        <div class="row mt-3">
            <div class="col-12">
                <h6>Exchange Information</h6>
                <div class="alert alert-warning">
                    <strong>Currency Exchange Applied:</strong> ${data.exchange.sourceAmount} ${data.exchange.sourceCurrency} at ${data.exchange.rate} = ${data.exchange.sourceAmount * data.exchange.rate} ${data.exchange.targetCurrency}
                </div>
            </div>
        </div>
        ` : ''}
    `;

    document.getElementById('previewResults').innerHTML = html;
    document.getElementById('previewCard').style.display = 'block';
}

function createMobileMoneyPayout() {
    if (!payoutPreviewData) {
        alert('Please preview the payout first');
        return;
    }

    const form = document.getElementById('mobileMoneyPayoutForm');
    const formData = new FormData(form);
    
    const payload = {
        amount: parseFloat(formData.get('amount')),
        phoneNumber: '255' + formData.get('phoneNumber'),
        currency: formData.get('currency'),
        orderReference: formData.get('orderReference'),
    };

    fetch('/admin/payments/create-mobile-money-payout', {
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
            displayPayoutResults(data.data, 'Mobile Money');
            form.reset();
            document.getElementById('createMMPayoutBtn').disabled = true;
            document.getElementById('previewCard').style.display = 'none';
            refreshPayouts();
            loadAccountBalance();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating mobile money payout');
    });
}

function createBankPayout() {
    if (!payoutPreviewData) {
        alert('Please preview the payout first');
        return;
    }

    const form = document.getElementById('bankTransferForm');
    const formData = new FormData(form);
    
    const payload = {
        amount: parseFloat(formData.get('amount')),
        accountNumber: formData.get('accountNumber'),
        accountName: formData.get('accountName'),
        currency: formData.get('currency'),
        orderReference: formData.get('orderReference'),
        bic: formData.get('bic'),
        transferType: formData.get('transferType'),
        accountCurrency: 'TZS',
    };

    fetch('/admin/payments/create-bank-payout', {
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
            displayPayoutResults(data.data, 'Bank Transfer');
            form.reset();
            document.getElementById('createBTPayoutBtn').disabled = true;
            document.getElementById('previewCard').style.display = 'none';
            refreshPayouts();
            loadAccountBalance();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating bank payout');
    });
}

function generatePayoutLink() {
    const form = document.getElementById('payoutLinkForm');
    const formData = new FormData(form);
    
    const payload = {
        amount: formData.get('amount'),
        orderReference: formData.get('orderReference'),
    };

    fetch('/admin/payments/generate-payout-link', {
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
            displayPayoutLinkResults(data.data);
            form.reset();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error generating payout link');
    });
}

function displayPayoutResults(data, type) {
    const html = `
        <div class="alert alert-success">
            <h6><i class="fas fa-check-circle"></i> ${type} Payout Created Successfully!</h6>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h6>Payout Information</h6>
                <table class="table table-sm">
                    <tr><td>Payout ID:</td><td><code>${data.id}</code></td></tr>
                    <tr><td>Order Reference:</td><td><code>${data.orderReference}</code></td></tr>
                    <tr><td>Amount:</td><td><strong>${data.currency} ${parseFloat(data.amount).toLocaleString()}</strong></td></tr>
                    <tr><td>Fee:</td><td>${data.currency} ${parseFloat(data.fee).toLocaleString()}</td></tr>
                    <tr><td>Status:</td><td><span class="badge badge-warning">${data.status}</span></td></tr>
                    <tr><td>Channel:</td><td>${data.channel}</td></tr>
                    <tr><td>Provider:</td><td>${data.channelProvider}</td></tr>
                    <tr><td>Created:</td><td>${new Date(data.createdAt).toLocaleString()}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Beneficiary Information</h6>
                <table class="table table-sm">
                    <tr><td>Name:</td><td><strong>${data.beneficiary.accountName}</strong></td></tr>
                    <tr><td>Account:</td><td><code>${data.beneficiary.accountNumber}</code></td></tr>
                    <tr><td>Amount to Receive:</td><td><strong>${data.currency} ${parseFloat(data.beneficiary.amount).toLocaleString()}</strong></td></tr>
                </table>
                <div class="mt-3">
                    <button class="btn btn-info btn-sm" onclick="viewPayout('${data.id}')">
                        <i class="fas fa-eye"></i> View Details
                    </button>
                </div>
            </div>
        </div>
    `;

    document.getElementById('payoutResults').innerHTML = html;
    document.getElementById('payoutResultsCard').style.display = 'block';
}

function displayPayoutLinkResults(data) {
    const html = `
        <div class="alert alert-success">
            <h6><i class="fas fa-check-circle"></i> Payout Link Generated Successfully!</h6>
        </div>
        <div class="row">
            <div class="col-md-8">
                <h6>Payout Link</h6>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="payoutLinkInput" value="${data.payoutLink}" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="copyPayoutLink()">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                        <button class="btn btn-outline-primary" type="button" onclick="openPayoutLink()">
                            <i class="fas fa-external-link-alt"></i> Open
                        </button>
                    </div>
                </div>
                <small class="form-text text-muted">Share this link with recipients to collect payout details</small>
            </div>
            <div class="col-md-4">
                <h6>Client ID</h6>
                <p><code>${data.clientId}</code></p>
            </div>
        </div>
    `;

    document.getElementById('payoutResults').innerHTML = html;
    document.getElementById('payoutResultsCard').style.display = 'block';
}

function copyPayoutLink() {
    const input = document.getElementById('payoutLinkInput');
    input.select();
    document.execCommand('copy');
    alert('Payout link copied to clipboard!');
}

function openPayoutLink() {
    const link = document.getElementById('payoutLinkInput').value;
    window.open(link, '_blank');
}

function refreshPayouts() {
    // Load recent payouts
    fetch('/admin/payments/recent-payouts')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('recentPayouts');
                tbody.innerHTML = '';
                
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No recent payouts</td></tr>';
                    return;
                }

                data.data.forEach(payout => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${payout.beneficiary?.accountName || 'N/A'}</td>
                            <td>${payout.currency} ${parseFloat(payout.amount).toLocaleString()}</td>
                            <td><span class="badge badge-${payout.status === 'SUCCESS' ? 'success' : 'warning'}">${payout.status}</span></td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error loading recent payouts:', error);
        });

    // Load all payouts
    fetch('/admin/payments/all-payouts')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('allPayouts');
                tbody.innerHTML = '';
                
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><i class="fas fa-hand-holding-usd fa-3x text-gray-300 mb-3"></i><p class="text-gray-500">No payouts found.</p></td></tr>';
                    return;
                }

                data.data.forEach(payout => {
                    tbody.innerHTML += `
                        <tr>
                            <td><code>${payout.id}</code></td>
                            <td>${payout.beneficiary?.accountName || 'N/A'}</td>
                            <td><strong>${payout.currency} ${parseFloat(payout.amount).toLocaleString()}</strong></td>
                            <td><span class="badge badge-info">${payout.channel}</span></td>
                            <td>${payout.channelProvider}</td>
                            <td><span class="badge badge-${payout.status === 'SUCCESS' ? 'success' : (payout.status === 'FAILED' ? 'danger' : 'warning')}">${payout.status}</span></td>
                            <td>${new Date(payout.createdAt).toLocaleString()}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info" onclick="viewPayout('${payout.id}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error loading all payouts:', error);
        });
}

function viewPayout(payoutId) {
    fetch(`/admin/payments/query-payout-status/${payoutId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const payout = data.data[0];
                let detailsHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Payout Information</h6>
                            <table class="table table-sm">
                                <tr><td>Payout ID:</td><td><code>${payout.id}</code></td></tr>
                                <tr><td>Order Reference:</td><td><code>${payout.orderReference}</code></td></tr>
                                <tr><td>Amount:</td><td><strong>${payout.currency} ${parseFloat(payout.amount).toLocaleString()}</strong></td></tr>
                                <tr><td>Fee:</td><td>${payout.currency} ${parseFloat(payout.fee).toLocaleString()}</td></tr>
                                <tr><td>Status:</td><td><span class="badge badge-${payout.status === 'SUCCESS' ? 'success' : (payout.status === 'FAILED' ? 'danger' : 'warning')}">${payout.status}</span></td></tr>
                                <tr><td>Channel:</td><td>${payout.channel}</td></tr>
                                <tr><td>Provider:</td><td>${payout.channelProvider}</td></tr>
                                <tr><td>Created:</td><td>${new Date(payout.createdAt).toLocaleString()}</td></tr>
                                <tr><td>Updated:</td><td>${new Date(payout.updatedAt).toLocaleString()}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Beneficiary Information</h6>
                            <table class="table table-sm">
                                <tr><td>Name:</td><td><strong>${payout.beneficiary.accountName}</strong></td></tr>
                                <tr><td>Account Number:</td><td><code>${payout.beneficiary.accountNumber}</code></td></tr>
                                <tr><td>Amount:</td><td><strong>${payout.currency} ${parseFloat(payout.beneficiary.amount).toLocaleString()}</strong></td></tr>
                                <tr><td>Currency:</td><td>${payout.beneficiary.accountCurrency}</td></tr>
                                ${payout.transferType ? `<tr><td>Transfer Type:</td><td>${payout.transferType}</td></tr>` : ''}
                            </table>
                        </div>
                    </div>
                `;
                document.getElementById('payoutDetails').innerHTML = detailsHtml;
                $('#payoutModal').modal('show');
            } else {
                alert('Error loading payout details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading payout details');
        });
}

// Auto-generate order references
document.getElementById('mmAmount').addEventListener('input', function() {
    const orderRef = document.getElementById('mmOrderReference');
    if (!orderRef.value) {
        orderRef.value = 'MM-' + Date.now();
    }
});

document.getElementById('btAmount').addEventListener('input', function() {
    const orderRef = document.getElementById('btOrderReference');
    if (!orderRef.value) {
        orderRef.value = 'BT-' + Date.now();
    }
});

document.getElementById('plAmount').addEventListener('input', function() {
    const orderRef = document.getElementById('plOrderReference');
    if (!orderRef.value) {
        orderRef.value = 'PL-' + Date.now();
    }
});
</script>
@endpush
