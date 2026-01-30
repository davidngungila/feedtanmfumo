@extends('pdf.base')

@section('title', 'Membership Application - '.($user->membership_code ?? $user->id))

@push('styles')
<style>
    /* Custom styles for membership application */
    .serial-number {
        text-align: center;
        font-size: 8pt;
        color: #666;
        margin-bottom: 15px;
        font-family: 'Courier New', monospace;
    }
    
    /* Member Details Table - Green Header */
    .member-details-section {
        margin-bottom: 12px;
    }
    
    .section-header-green {
        background: #015425;
        color: white;
        padding: 6px 10px;
        font-weight: bold;
        font-size: 10pt;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .member-details-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #e5e7eb;
    }
    
    .member-details-table td {
        padding: 6px 10px;
        border: 1px solid #e5e7eb;
        font-size: 9pt;
    }
    
    .member-details-table td:first-child {
        background: #f9fafb;
        font-weight: 600;
        width: 35%;
        color: #374151;
    }
    
    .member-details-table td:last-child {
        background: white;
        color: #1a1a1a;
    }
    
    /* Summary Section */
    .summary-section {
        margin: 12px 0;
        background: #f9fafb;
        border: 2px solid #015425;
        border-radius: 4px;
        padding: 10px;
    }
    
    .summary-title {
        font-size: 10pt;
        font-weight: bold;
        color: #015425;
        margin-bottom: 8px;
        text-transform: uppercase;
    }
    
    .summary-grid {
        display: table;
        width: 100%;
    }
    
    .summary-item {
        display: table-cell;
        width: 50%;
        padding: 0 8px;
        vertical-align: top;
    }
    
    .summary-label {
        font-size: 8pt;
        color: #6b7280;
        margin-bottom: 3px;
        text-transform: uppercase;
    }
    
    .summary-value {
        font-size: 12pt;
        font-weight: bold;
        color: #015425;
    }
    
    /* Custom Section Styles */
    .section {
        margin-bottom: 10px;
        page-break-inside: avoid;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .section-header {
        background: #015425;
        color: white;
        padding: 5px 10px;
        font-weight: bold;
        font-size: 9.5pt;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .section-content {
        padding: 8px;
        background: white;
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
    
    /* Two Column Layout */
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
    
    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 8pt;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-uploaded {
        background: #d1fae5;
        color: #065f46;
        padding: 3px 8px;
        border-radius: 8px;
        font-size: 8pt;
    }
    
    .status-missing {
        background: #fee2e2;
        color: #991b1b;
        padding: 3px 8px;
        border-radius: 8px;
        font-size: 8pt;
    }
    
    /* Beneficiary Card */
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
    
    /* Ensure content fits on page */
    .section, .member-details-section, .summary-section {
        page-break-inside: avoid;
    }
    
    /* Prevent orphaned headers */
    .section-header, .section-header-green {
        page-break-after: avoid;
    }
</style>
@endpush

@section('content')
    <!-- Serial Number -->
    <div class="serial-number">Serial No: {{ $serialNumber ?? 'FCMGMA'.date('dmy').str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>

    <!-- Member Details Section -->
    <div class="member-details-section">
        <div class="section-header-green">Member Details</div>
        <table class="member-details-table">
            <tr>
                <td>Member Name</td>
                <td><strong>{{ strtoupper($user->name) }}</strong></td>
            </tr>
            <tr>
                <td>Member Type</td>
                <td><strong>{{ $user->membershipType->name ?? 'Not Selected' }}</strong> ({{ $user->membership_code ?? 'Pending' }})</td>
            </tr>
            <tr>
                <td>Application Date</td>
                <td>{{ $user->created_at->format('F Y') }}</td>
            </tr>
            <tr>
                <td>Email Address</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td style="font-family: 'Courier New', monospace;">{{ $user->phone ?? 'Not provided' }}</td>
            </tr>
        </table>
    </div>

    <!-- Application Summary -->
    <div class="summary-section">
        <div class="summary-title">Application Summary</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Membership Type</div>
                <div class="summary-value" style="font-size: 14pt;">{{ $user->membershipType->name ?? 'Not Selected' }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Application Status</div>
                <div class="summary-value" style="font-size: 14pt;">{{ strtoupper($user->membership_status ?? 'PENDING') }}</div>
            </div>
        </div>
        @if($user->membershipType)
        <div class="summary-grid" style="margin-top: 6px;">
            <div class="summary-item">
                <div class="summary-label">Entrance Fee</div>
                <div class="summary-value" style="font-size: 11pt;">{{ number_format($user->membershipType->entrance_fee ?? 0) }} TZS</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Capital Contribution</div>
                <div class="summary-value" style="font-size: 11pt;">{{ number_format($user->membershipType->capital_contribution ?? 0) }} TZS</div>
            </div>
        </div>
        @endif
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
@endsection
