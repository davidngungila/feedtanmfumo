<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Membership Application - {{ $user->membership_code ?? $user->id }}</title>
    <style>
        @page {
            margin: 20mm 15mm;
            size: A4;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #1a1a1a;
            background: #ffffff;
        }
        
        /* Header Styles */
        .document-header {
            border-bottom: 4px solid #015425;
            padding-bottom: 15px;
            margin-bottom: 25px;
            position: relative;
        }
        
        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }
        
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 40%;
        }
        
        .company-name {
            font-size: 24pt;
            font-weight: bold;
            color: #015425;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        
        .company-tagline {
            font-size: 9pt;
            color: #666;
            font-style: italic;
        }
        
        .document-title {
            font-size: 18pt;
            font-weight: bold;
            color: #015425;
            text-align: center;
            margin: 15px 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .application-info {
            display: table;
            width: 100%;
            margin-top: 10px;
            font-size: 9pt;
        }
        
        .info-left, .info-right {
            display: table-cell;
            width: 50%;
        }
        
        .info-right {
            text-align: right;
        }
        
        .info-label {
            font-weight: bold;
            color: #015425;
        }
        
        /* Section Styles */
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .section-header {
            background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #013019;
        }
        
        .section-content {
            padding: 15px;
            background: #fafafa;
        }
        
        /* Table Styles */
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
        
        .info-table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .info-table td {
            padding: 10px 12px;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            font-weight: bold;
            width: 35%;
            color: #015425;
            background: #f0f7f4;
            border-right: 2px solid #e5e7eb;
            text-transform: uppercase;
            font-size: 9pt;
            letter-spacing: 0.5px;
        }
        
        .info-table td:last-child {
            color: #1a1a1a;
            font-size: 10pt;
        }
        
        /* Two Column Layout */
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            padding: 0 10px;
            vertical-align: top;
        }
        
        .column:first-child {
            padding-left: 0;
        }
        
        .column:last-child {
            padding-right: 0;
        }
        
        /* Financial Highlight */
        .financial-box {
            background: linear-gradient(135deg, #f0f7f4 0%, #e8f5f0 100%);
            border: 2px solid #015425;
            border-radius: 5px;
            padding: 12px;
            margin: 10px 0;
        }
        
        .financial-label {
            font-size: 9pt;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .financial-value {
            font-size: 14pt;
            font-weight: bold;
            color: #015425;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        
        .status-approved {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        .status-uploaded {
            background: #d1fae5;
            color: #065f46;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8pt;
        }
        
        .status-missing {
            background: #fee2e2;
            color: #991b1b;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8pt;
        }
        
        /* Beneficiary Card */
        .beneficiary-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 5px;
            padding: 12px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        
        .beneficiary-header {
            background: #015425;
            color: white;
            padding: 8px 12px;
            margin: -12px -12px 10px -12px;
            font-weight: bold;
            font-size: 10pt;
        }
        
        /* Signature Section */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-box {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        
        .signature-block {
            display: table-cell;
            width: 50%;
            padding: 0 20px;
            vertical-align: top;
        }
        
        .signature-line {
            border-top: 2px solid #1a1a1a;
            width: 100%;
            margin: 50px 0 10px 0;
        }
        
        .signature-label {
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
            color: #015425;
            text-transform: uppercase;
        }
        
        .signature-date {
            text-align: center;
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
        }
        
        /* Footer */
        .document-footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 3px solid #e5e7eb;
            text-align: center;
            font-size: 8pt;
            color: #666;
            page-break-inside: avoid;
        }
        
        .footer-info {
            margin: 5px 0;
        }
        
        .footer-note {
            margin-top: 10px;
            font-style: italic;
            color: #999;
        }
        
        /* Page Break */
        .page-break {
            page-break-before: always;
        }
        
        /* Highlight Box */
        .highlight-box {
            background: #fff9e6;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin: 15px 0;
            border-radius: 3px;
        }
        
        .highlight-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 5px;
            font-size: 10pt;
        }
        
        /* Divider */
        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #015425, transparent);
            margin: 20px 0;
        }
        
        /* Verification Section */
        .verification-box {
            background: #f9fafb;
            border: 2px dashed #015425;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        .verification-title {
            font-weight: bold;
            color: #015425;
            font-size: 10pt;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .verification-code {
            font-family: 'Courier New', monospace;
            font-size: 16pt;
            font-weight: bold;
            color: #015425;
            letter-spacing: 3px;
            margin: 10px 0;
        }
        
        /* Utility Classes */
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-bold {
            font-weight: bold;
        }
        
        .text-uppercase {
            text-transform: uppercase;
        }
        
        .mb-10 {
            margin-bottom: 10px;
        }
        
        .mt-10 {
            margin-top: 10px;
        }
        
        /* Print Specific */
        @media print {
            .section {
                page-break-inside: avoid;
            }
            
            .beneficiary-card {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Document Header -->
    <div class="document-header">
        <div class="header-top">
            <div class="header-left">
                <div class="company-name">FEEDTAN DIGITAL</div>
                <div class="company-tagline">Empowering Communities Through Digital Solutions</div>
            </div>
            <div class="header-right">
                <div style="font-size: 8pt; color: #666; line-height: 1.8;">
                    <div><strong>Document ID:</strong> APP-{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div><strong>Generated:</strong> {{ date('d M Y, H:i:s') }}</div>
                    <div><strong>Status:</strong> 
                        <span class="status-badge status-{{ $user->membership_status ?? 'pending' }}">
                            {{ strtoupper($user->membership_status ?? 'PENDING') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <div class="document-title">Membership Application Form</div>
        
        <div class="application-info">
            <div class="info-left">
                <span class="info-label">Application Date:</span> {{ date('d F Y') }}<br>
                <span class="info-label">Application Reference:</span> {{ $user->membership_code ?? 'PENDING' }}
            </div>
            <div class="info-right">
                <span class="info-label">Applicant ID:</span> {{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}<br>
                <span class="info-label">Form Version:</span> 1.0
            </div>
        </div>
    </div>

    <!-- Verification Section -->
    <div class="verification-box">
        <div class="verification-title">Application Verification Code</div>
        <div class="verification-code">{{ $user->membership_code ?? 'PENDING-' . str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div style="font-size: 8pt; color: #666;">Use this code to track your application status</div>
    </div>

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
                    <td><strong style="font-family: 'Courier New', monospace; font-size: 12pt; color: #015425;">{{ $user->membership_code ?? 'Pending Assignment' }}</strong></td>
                </tr>
            </table>
            
            @if($user->membershipType)
            <div class="two-column" style="margin-top: 15px;">
                <div class="column">
                    <div class="financial-box">
                        <div class="financial-label">Entrance Fee</div>
                        <div class="financial-value">{{ number_format($user->membershipType->entrance_fee ?? 0) }} TZS</div>
                    </div>
                </div>
                <div class="column">
                    <div class="financial-box">
                        <div class="financial-label">Capital Contribution</div>
                        <div class="financial-value">{{ number_format($user->membershipType->capital_contribution ?? 0) }} TZS</div>
                    </div>
                </div>
            </div>
            @if($user->membershipType->minimum_shares > 0)
            <div class="highlight-box" style="margin-top: 15px;">
                <div class="highlight-title">Minimum Shares Requirement</div>
                <div style="font-size: 11pt; color: #92400e;">This membership type requires a minimum of <strong>{{ $user->membershipType->minimum_shares }} shares</strong> to be purchased.</div>
            </div>
            @endif
            @endif
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
                            <td>{{ $user->date_of_birth ? $user->date_of_birth->format('d F Y') . ' (Age: ' . $user->date_of_birth->age . ' years)' : 'Not provided' }}</td>
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
                            <strong style="font-size: 12pt; color: #015425;">{{ number_format($user->monthly_income) }} TZS</strong>
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
                    <td style="font-family: 'Courier New', monospace; font-weight: bold; font-size: 11pt;">{{ $user->bank_account_number ?? 'Not provided' }}</td>
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
                    <td>
                        <strong>{{ ucfirst($user->statement_preference ?? 'Not provided') }}</strong>
                        @if($user->statement_preference === 'email')
                            (Email Delivery)
                        @elseif($user->statement_preference === 'sms')
                            (SMS Delivery)
                        @elseif($user->statement_preference === 'postal')
                            (Postal Mail)
                        @endif
                    </td>
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
                        <td><strong style="font-size: 12pt; color: #015425;">{{ $beneficiary['allocation'] ?? 0 }}%</strong></td>
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

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-label">Applicant Signature</div>
                <div class="signature-date">Date: _______________</div>
            </div>
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-label">Authorized Officer Signature</div>
                <div class="signature-date">Date: _______________</div>
            </div>
        </div>
    </div>

    <!-- Declaration -->
    <div class="highlight-box" style="margin-top: 30px;">
        <div class="highlight-title">Declaration</div>
        <div style="font-size: 9pt; line-height: 1.6; text-align: justify;">
            I hereby declare that all the information provided in this application form is true, accurate, and complete to the best of my knowledge. 
            I understand that any false or misleading information may result in the rejection of my application or termination of membership. 
            I agree to abide by the rules and regulations of FEEDTAN DIGITAL and authorize the organization to verify the information provided.
        </div>
    </div>

    <!-- Document Footer -->
    <div class="document-footer">
        <div class="footer-info"><strong>FEEDTAN DIGITAL</strong> - Membership Application Form</div>
        <div class="footer-info">This is a computer-generated document. Generated on {{ date('d F Y, H:i:s') }}</div>
        <div class="footer-info">Document ID: APP-{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }} | Application Code: {{ $user->membership_code ?? 'PENDING' }}</div>
        <div class="footer-note">
            This document is confidential and intended solely for the use of the applicant and authorized personnel of FEEDTAN DIGITAL.
        </div>
    </div>
</body>
</html>
