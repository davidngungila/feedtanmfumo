<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Membership Application - {{ $user->membership_code ?? $user->id }}</title>
    <style>
        @if(isset($fontRegular) && $fontRegular)
        @font-face {
            font-family: 'Quicksand';
            font-style: normal;
            font-weight: 400;
            src: url('{{ $fontRegular }}') format('truetype');
        }
        @endif
        @if(isset($fontMedium) && $fontMedium)
        @font-face {
            font-family: 'Quicksand';
            font-style: normal;
            font-weight: 500;
            src: url('{{ $fontMedium }}') format('truetype');
        }
        @endif
        @if(isset($fontSemiBold) && $fontSemiBold)
        @font-face {
            font-family: 'Quicksand';
            font-style: normal;
            font-weight: 600;
            src: url('{{ $fontSemiBold }}') format('truetype');
        }
        @endif
        @if(isset($fontBold) && $fontBold)
        @font-face {
            font-family: 'Quicksand';
            font-style: normal;
            font-weight: 700;
            src: url('{{ $fontBold }}') format('truetype');
        }
        @endif
        
        @page {
            margin: 10mm 12mm;
            size: A4;
        }
        body {
            font-family: 'Quicksand', 'DejaVu Sans', Arial, Helvetica, sans-serif;
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
        .logo-box {
            display: inline-block;
            background: #015425;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 10px;
        }
        .header-info {
            font-size: 10pt;
            color: #666;
            margin-top: 8px;
        }
        .title {
            font-size: 18pt;
            font-weight: bold;
            color: #015425;
            margin: 15px 0 10px 0;
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
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            background: white;
        }
        .info-table tr {
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table tr:last-child {
            border-bottom: none;
        }
        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .info-table td:first-child {
            font-weight: 600;
            width: 35%;
            color: #374151;
            background: #f9fafb;
            border-right: 1px solid #e5e7eb;
        }
        .info-table td:last-child {
            color: #1a1a1a;
        }
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .column {
            display: table-cell;
            width: 50%;
            padding: 0 6px;
            vertical-align: top;
        }
        .column:first-child {
            padding-left: 0;
        }
        .column:last-child {
            padding-right: 0;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-uploaded {
            background: #d4edda;
            color: #155724;
            padding: 3px 8px;
            border-radius: 8px;
            font-size: 8pt;
        }
        .status-missing {
            background: #f8d7da;
            color: #721c24;
            padding: 3px 8px;
            border-radius: 8px;
            font-size: 8pt;
        }
        .beneficiary-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 6px;
            margin-bottom: 6px;
            page-break-inside: avoid;
        }
        .beneficiary-header {
            background: #015425;
            color: white;
            padding: 4px 8px;
            margin: -6px -6px 5px -6px;
            font-weight: bold;
            font-size: 8.5pt;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 15px;">
            @if(isset($headerBase64) && $headerBase64)
            <img src="{{ $headerBase64 }}" alt="FeedTan Header" style="width: 100%; max-width: 100%; height: auto; display: block; margin: 0 auto;">
            @else
            <div class="logo-box" style="margin: 0 auto 10px auto;">FD</div>
            @endif
        </div>
        @if(isset($documentTitle))
        <div class="title">{{ $documentTitle }}</div>
        @else
        <div class="title">Membership Application</div>
        @endif
        <div class="serial-number">Serial No: {{ $serialNumber ?? 'FCMGMA'.date('dmy').str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Member: {{ $user->name }} | Status: {{ strtoupper($user->membership_status ?? 'PENDING') }}
        </div>
    </div>

    <!-- Member Summary Stats -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Member Name:</div>
            <div class="stats-cell"><strong>{{ strtoupper($user->name) }}</strong></div>
            <div class="stats-cell stats-label">Membership Type:</div>
            <div class="stats-cell">{{ $user->membershipType->name ?? 'Not Selected' }}</div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Membership Code:</div>
            <div class="stats-cell"><strong>{{ $user->membership_code ?? 'Pending' }}</strong></div>
            <div class="stats-cell stats-label">Application Status:</div>
            <div class="stats-cell">{{ strtoupper($user->membership_status ?? 'PENDING') }}</div>
            <div class="stats-cell stats-label">Application Date:</div>
            <div class="stats-cell">{{ $user->created_at->format('Y-m-d') }}</div>
        </div>
    </div>

    @if($user->membershipType)
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Entrance Fee:</div>
            <div class="stats-cell">{{ number_format($user->membershipType->entrance_fee ?? 0) }} TZS</div>
            <div class="stats-cell stats-label">Capital Contribution:</div>
            <div class="stats-cell">{{ number_format($user->membershipType->capital_contribution ?? 0) }} TZS</div>
        </div>
    </div>
    @endif

    <!-- Section 1: Membership Type -->
    <div class="section">
        <div class="section-header">1. Membership Type Selection</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Membership Type</td>
                    <td><strong>{{ $user->membershipType->name ?? 'Not selected' }}</strong></td>
                </tr>
                @if($user->membershipType && $user->membershipType->description)
                <tr>
                    <td>Description</td>
                    <td>{{ $user->membershipType->description }}</td>
                </tr>
                @endif
                <tr>
                    <td>Membership Code</td>
                    <td><strong style="font-family: 'Courier New', monospace; font-size: 11pt; color: #015425;">{{ $user->membership_code ?? 'Pending Assignment' }}</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Section 2: Personal Information -->
    <div class="section">
        <div class="section-header">2. Personal Information</div>
        <div class="section-content">
            <div class="two-column">
                <div class="column">
                    <table class="info-table">
                        <tr>
                            <td>Full Name</td>
                            <td><strong>{{ $user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Email Address</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td>Primary Phone</td>
                            <td style="font-family: 'Courier New', monospace;">{{ $user->phone ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td>Alternate Phone</td>
                            <td style="font-family: 'Courier New', monospace;">{{ $user->alternate_phone ?? 'Not provided' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="column">
                    <table class="info-table">
                        <tr>
                            <td>Gender</td>
                            <td>{{ ucfirst($user->gender ?? 'Not provided') }}</td>
                        </tr>
                        <tr>
                            <td>Date of Birth</td>
                            <td>{{ $user->date_of_birth ? $user->date_of_birth->format('d F Y') : 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td>National ID (NIDA)</td>
                            <td style="font-family: 'Courier New', monospace; font-weight: bold;">{{ $user->national_id ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td>Marital Status</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $user->marital_status ?? 'Not provided')) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Address Information -->
    <div class="section">
        <div class="section-header">3. Address Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Street Address</td>
                    <td>{{ $user->address ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <td>City / Town</td>
                    <td><strong>{{ $user->city ?? 'Not provided' }}</strong></td>
                </tr>
                <tr>
                    <td>Region</td>
                    <td><strong>{{ $user->region ?? 'Not provided' }}</strong></td>
                </tr>
                <tr>
                    <td>Postal Code</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $user->postal_code ?? 'Not provided' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Section 4: Employment Information -->
    <div class="section">
        <div class="section-header">4. Employment Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Occupation / Profession</td>
                    <td>{{ $user->occupation ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <td>Employer / Organization</td>
                    <td>{{ $user->employer ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <td>Monthly Income</td>
                    <td>
                        @if($user->monthly_income)
                            <strong style="font-size: 11pt; color: #015425;">{{ number_format($user->monthly_income) }} TZS</strong>
                        @else
                            Not provided
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Section 5: Bank Information -->
    <div class="section">
        <div class="section-header">5. Bank Account Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Bank Name</td>
                    <td><strong>{{ $user->bank_name ?? 'Not provided' }}</strong></td>
                </tr>
                <tr>
                    <td>Bank Branch</td>
                    <td>{{ $user->bank_branch ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <td>Account Number</td>
                    <td style="font-family: 'Courier New', monospace; font-weight: bold; font-size: 10pt;">{{ $user->bank_account_number ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <td>Payment Reference Number</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $user->payment_reference_number ?? 'Not provided' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Section 6: Additional Information -->
    <div class="section">
        <div class="section-header">6. Additional Information & Preferences</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Statement Preference</td>
                    <td><strong>{{ ucfirst($user->statement_preference ?? 'Not provided') }}</strong></td>
                </tr>
                @if($user->short_bibliography)
                <tr>
                    <td>Short Biography</td>
                    <td style="text-align: justify;">{{ $user->short_bibliography }}</td>
                </tr>
                @endif
                @if($user->introduced_by)
                <tr>
                    <td>Introduced By</td>
                    <td>{{ $user->introduced_by }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Section 7: Beneficiaries -->
    @if($user->beneficiaries_info && count($user->beneficiaries_info) > 0)
    <div class="section">
        <div class="section-header">7. Beneficiaries Information</div>
        <div class="section-content">
            @foreach($user->beneficiaries_info as $index => $beneficiary)
            <div class="beneficiary-card">
                <div class="beneficiary-header">Beneficiary {{ $index + 1 }}</div>
                <table class="info-table">
                    <tr>
                        <td>Full Name</td>
                        <td><strong>{{ $beneficiary['name'] ?? 'Not provided' }}</strong></td>
                    </tr>
                    <tr>
                        <td>Relationship</td>
                        <td>{{ ucfirst($beneficiary['relationship'] ?? 'Not provided') }}</td>
                    </tr>
                    <tr>
                        <td>Allocation Percentage</td>
                        <td><strong style="font-size: 11pt; color: #015425;">{{ $beneficiary['allocation'] ?? 0 }}%</strong></td>
                    </tr>
                    <tr>
                        <td>Contact Information</td>
                        <td style="font-family: 'Courier New', monospace;">{{ $beneficiary['contact'] ?? 'Not provided' }}</td>
                    </tr>
                </table>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Section 8: Group Information -->
    <div class="section">
        <div class="section-header">8. Group Registration Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Group Registered</td>
                    <td>
                        <span class="status-badge {{ $user->is_group_registered ? 'status-approved' : 'status-pending' }}">
                            {{ $user->is_group_registered ? 'Yes' : 'No' }}
                        </span>
                    </td>
                </tr>
                @if($user->is_group_registered)
                <tr>
                    <td>Group Name</td>
                    <td><strong>{{ $user->group_name ?? 'Not provided' }}</strong></td>
                </tr>
                <tr>
                    <td>Group Leaders</td>
                    <td>{{ $user->group_leaders ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <td>Group Bank Account</td>
                    <td style="font-family: 'Courier New', monospace;">{{ $user->group_bank_account ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <td>Group Contact Information</td>
                    <td>{{ $user->group_contacts ?? 'Not provided' }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Section 9: Documents -->
    <div class="section">
        <div class="section-header">9. Document Upload Status</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Passport Picture</td>
                    <td>
                        <span class="{{ $user->passport_picture_path ? 'status-uploaded' : 'status-missing' }}">
                            {{ $user->passport_picture_path ? '✓ Uploaded' : '✗ Not Uploaded' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>NIDA Picture</td>
                    <td>
                        <span class="{{ $user->nida_picture_path ? 'status-uploaded' : 'status-missing' }}">
                            {{ $user->nida_picture_path ? '✓ Uploaded' : '✗ Not Uploaded' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Application Letter</td>
                    <td>
                        <span class="{{ $user->application_letter_path ? 'status-uploaded' : 'status-missing' }}">
                            {{ $user->application_letter_path ? '✓ Uploaded' : '✗ Not Uploaded' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Payment Slip</td>
                    <td>
                        <span class="{{ $user->payment_slip_path ? 'status-uploaded' : 'status-missing' }}">
                            {{ $user->payment_slip_path ? '✓ Uploaded' : '✗ Not Uploaded' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Standing Order</td>
                    <td>
                        <span class="{{ $user->standing_order_path ? 'status-uploaded' : 'status-missing' }}">
                            {{ $user->standing_order_path ? '✓ Uploaded' : '✗ Not Uploaded' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Section 10: Additional Options -->
    <div class="section">
        <div class="section-header">10. Additional Options</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Ordinary Membership Request</td>
                    <td>
                        <span class="status-badge {{ $user->wants_ordinary_membership ? 'status-approved' : 'status-pending' }}">
                            {{ $user->wants_ordinary_membership ? 'Yes' : 'No' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Membership Application</p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Serial No: {{ $serialNumber ?? 'FCMGMA'.date('dmy').str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>
</body>
</html>
