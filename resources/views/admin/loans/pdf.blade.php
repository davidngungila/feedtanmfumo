<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loan Application Document - {{ $loan->loan_number }}</title>
    <style>
        @page {
            margin: 10mm 12mm;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            border-bottom: 3px solid #015425;
            padding-bottom: 15px;
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
        }
        .header-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 15px auto;
        }
        .title {
            font-size: 18pt;
            font-weight: bold;
            color: #015425;
            margin: 15px 0 10px 0;
            text-align: center;
        }
        .serial-number {
            text-align: center;
            font-size: 8pt;
            color: #666;
            margin-bottom: 15px;
            font-family: 'Courier New', monospace;
        }
        .stats {
            display: table;
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            font-size: 8pt;
        }
        .stats-label {
            font-weight: bold;
            color: #015425;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 8pt;
        }
        th {
            background: #015425;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #015425;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .section {
            margin: 15px 0;
            page-break-inside: avoid;
        }
        .section-header {
            background: #015425;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 8px;
        }
        .section-content {
            padding: 8px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
        .info-box {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 8px;
            margin: 5px 0;
            border-radius: 3px;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            display: inline-block;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-completed {
            background: #cce5ff;
            color: #004085;
        }
        .status-overdue {
            background: #f8d7da;
            color: #721c24;
        }
        .status-rejected {
            background: #e2e3e5;
            color: #383d41;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 15px;">
            @if(isset($headerBase64) && $headerBase64)
            <img src="{{ $headerBase64 }}" alt="FeedTan Header" class="header-image">
            @else
            <div style="background: #015425; color: white; padding: 8px 12px; font-weight: bold; font-size: 14pt; margin: 0 auto 10px auto; display: inline-block;">FD</div>
            @endif
        </div>
        <div class="title">{{ $documentTitle ?? 'Loan Application Document' }}</div>
        <div class="serial-number">Serial No: FCMGLAD-{{ date('Ymd') }}-{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}</div>
        <div style="font-size: 10pt; color: #666; margin-top: 8px;">
            Generated: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <!-- Loan Summary Stats -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Loan Number:</div>
            <div class="stats-cell"><strong>{{ $loan->loan_number }}</strong></div>
            <div class="stats-cell stats-label">Status:</div>
            <div class="stats-cell">
                <span class="status-badge status-{{ $loan->status }}">
                    {{ strtoupper($loan->status) }}
                </span>
            </div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Principal Amount:</div>
            <div class="stats-cell"><strong>{{ number_format($loan->principal_amount, 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Total Amount:</div>
            <div class="stats-cell"><strong>{{ number_format($loan->total_amount, 2) }} TZS</strong></div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Paid Amount:</div>
            <div class="stats-cell"><strong>{{ number_format($loan->paid_amount, 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Remaining Amount:</div>
            <div class="stats-cell"><strong>{{ number_format($loan->remaining_amount, 2) }} TZS</strong></div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Interest Rate:</div>
            <div class="stats-cell"><strong>{{ $loan->interest_rate }}% per annum</strong></div>
            <div class="stats-cell stats-label">Loan Term:</div>
            <div class="stats-cell"><strong>{{ $loan->term_months }} Months</strong></div>
        </div>
    </div>

    <!-- Member Details -->
    <div class="section">
        <div class="section-header">Member Details</div>
        <div class="section-content">
            <table>
                <tr>
                    <th style="width: 30%;">Member Name</th>
                    <td><strong>{{ $loan->user->name }}</strong></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $loan->user->email }}</td>
                </tr>
                @if($loan->user->phone)
                <tr>
                    <th>Phone</th>
                    <td>{{ $loan->user->phone }}</td>
                </tr>
                @endif
                @if($loan->user->membership_code)
                <tr>
                    <th>Membership Code</th>
                    <td>{{ $loan->user->membership_code }}</td>
                </tr>
                @endif
                <tr>
                    <th>Application Date</th>
                    <td>{{ $loan->application_date->format('F d, Y') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Loan Details -->
    <div class="section">
        <div class="section-header">Loan Details</div>
        <div class="section-content">
            <table>
                @if($loan->loan_type)
                <tr>
                    <th style="width: 30%;">Loan Type</th>
                    <td>{{ $loan->loan_type }}</td>
                </tr>
                @endif
                <tr>
                    <th>Payment Frequency</th>
                    <td>{{ ucfirst(str_replace('-', ' ', $loan->payment_frequency)) }}</td>
                </tr>
                @if($loan->approval_date)
                <tr>
                    <th>Approval Date</th>
                    <td>{{ $loan->approval_date->format('F d, Y') }}</td>
                </tr>
                @endif
                @if($loan->approver)
                <tr>
                    <th>Approved By</th>
                    <td>{{ $loan->approver->name }}</td>
                </tr>
                @endif
                @if($loan->disbursement_date)
                <tr>
                    <th>Disbursement Date</th>
                    <td>{{ $loan->disbursement_date->format('F d, Y') }}</td>
                </tr>
                @endif
                @if($loan->maturity_date)
                <tr>
                    <th>Maturity Date</th>
                    <td>{{ $loan->maturity_date->format('F d, Y') }}</td>
                </tr>
                @endif
                @if($loan->purpose)
                <tr>
                    <th>Loan Purpose</th>
                    <td>{{ $loan->purpose }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Collateral Information -->
    @if($loan->collateral_description || $loan->collateral_value)
    <div class="section">
        <div class="section-header">Collateral Information</div>
        <div class="section-content">
            <table>
                @if($loan->collateral_description)
                <tr>
                    <th style="width: 30%;">Collateral Description</th>
                    <td>{{ $loan->collateral_description }}</td>
                </tr>
                @endif
                @if($loan->collateral_value)
                <tr>
                    <th>Collateral Value</th>
                    <td><strong>{{ number_format($loan->collateral_value, 2) }} TZS</strong></td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    @endif

    <!-- Guarantor Information -->
    @if($loan->guarantor_name || $loan->guarantor_phone || $loan->guarantor_email)
    <div class="section">
        <div class="section-header">Guarantor Information</div>
        <div class="section-content">
            <table>
                @if($loan->guarantor_name)
                <tr>
                    <th style="width: 30%;">Guarantor Name</th>
                    <td><strong>{{ $loan->guarantor_name }}</strong></td>
                </tr>
                @endif
                @if($loan->guarantor_phone)
                <tr>
                    <th>Phone</th>
                    <td>{{ $loan->guarantor_phone }}</td>
                </tr>
                @endif
                @if($loan->guarantor_email)
                <tr>
                    <th>Email</th>
                    <td>{{ $loan->guarantor_email }}</td>
                </tr>
                @endif
                @if($loan->guarantor_address)
                <tr>
                    <th>Address</th>
                    <td>{{ $loan->guarantor_address }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    @endif

    <!-- Business Plan & Repayment -->
    @if($loan->business_plan || $loan->repayment_source)
    <div class="section">
        <div class="section-header">Business Plan & Repayment Source</div>
        <div class="section-content">
            @if($loan->business_plan)
            <div class="info-box">
                <strong>Business Plan / Project Description:</strong><br>
                {{ $loan->business_plan }}
            </div>
            @endif
            @if($loan->repayment_source)
            <div class="info-box">
                <strong>Repayment Source:</strong><br>
                {{ $loan->repayment_source }}
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Additional Notes -->
    @if($loan->additional_notes)
    <div class="section">
        <div class="section-header">Additional Notes</div>
        <div class="section-content">
            <div class="info-box">
                {{ $loan->additional_notes }}
            </div>
        </div>
    </div>
    @endif

    <!-- Document Information -->
    <div class="section">
        <div class="section-header">Attached Documents</div>
        <div class="section-content">
            <table>
                <tr>
                    <th style="width: 40%;">Document Type</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td>Application Document</td>
                    <td>{{ $loan->application_document ? '✓ Attached' : '✗ Not provided' }}</td>
                </tr>
                <tr>
                    <td>ID Document</td>
                    <td>{{ $loan->id_document ? '✓ Attached' : '✗ Not provided' }}</td>
                </tr>
                <tr>
                    <td>Proof of Income</td>
                    <td>{{ $loan->proof_of_income ? '✓ Attached' : '✗ Not provided' }}</td>
                </tr>
                <tr>
                    <td>Collateral Document</td>
                    <td>{{ $loan->collateral_document ? '✓ Attached' : '✗ Not provided' }}</td>
                </tr>
                <tr>
                    <td>Guarantor Document</td>
                    <td>{{ $loan->guarantor_document ? '✓ Attached' : '✗ Not provided' }}</td>
                </tr>
                <tr>
                    <td>Supporting Documents</td>
                    <td>
                        @if($loan->supporting_documents && count($loan->supporting_documents) > 0)
                            ✓ {{ count($loan->supporting_documents) }} file(s) attached
                        @else
                            ✗ Not provided
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Payment History -->
    @if($loan->transactions->count() > 0)
    <div class="section">
        <div class="section-header">Payment History</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th style="text-align: right;">Amount (TZS)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loan->transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $transaction->type ?? 'payment')) }}</td>
                        <td style="text-align: right;"><strong>{{ number_format($transaction->amount ?? 0, 2) }}</strong></td>
                        <td>{{ ucfirst($transaction->status ?? 'completed') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="font-weight: bold;">Total Paid</td>
                        <td style="text-align: right; font-weight: bold;">{{ number_format($loan->paid_amount, 2) }} TZS</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    <!-- Rejection Reason -->
    @if($loan->rejection_reason)
    <div class="section">
        <div class="section-header">Rejection Reason</div>
        <div class="section-content">
            <div class="info-box" style="background: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                {{ $loan->rejection_reason }}
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group</strong></p>
        <p>{{ $organizationInfo['address'] }}</p>
        <p>Email: {{ $organizationInfo['email'] }} | Phone: {{ $organizationInfo['phone'] }}</p>
        <p style="margin-top: 8px;">Serial No: FCMGLAD-{{ date('Ymd') }}-{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}</p>
        <p style="margin-top: 8px; font-size: 6pt;">Document generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
    </div>
</body>
</html>

