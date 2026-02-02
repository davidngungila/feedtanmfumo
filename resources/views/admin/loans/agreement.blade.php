<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loan Agreement - {{ $loan->loan_number }}</title>
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
        .clause-content {
            text-align: justify;
            margin: 8px 0;
            line-height: 1.6;
        }
        .signatures-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .signatures-table td {
            padding: 15px;
            vertical-align: top;
            border: 1px solid #ddd;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            width: 200px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
        .amount {
            font-weight: bold;
            color: #015425;
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
        <div class="title">LOAN AGREEMENT</div>
        <div class="serial-number">Serial No: FCMGLA{{ $loan->application_date->format('Ymd') }}{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Loan Number: {{ $loan->loan_number }} | Status: {{ strtoupper($loan->status ?? 'PENDING') }}
        </div>
    </div>

    <!-- Loan Summary Stats -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Borrower:</div>
            <div class="stats-cell"><strong>{{ strtoupper($loan->user->name) }}</strong></div>
            <div class="stats-cell stats-label">Loan Type:</div>
            <div class="stats-cell">{{ $loan->loan_type ?? 'Standard Loan' }}</div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Principal Amount:</div>
            <div class="stats-cell"><strong class="amount">{{ number_format($loan->principal_amount, 2) }} TZS</strong></div>
            <div class="stats-cell stats-label">Interest Rate:</div>
            <div class="stats-cell">{{ number_format($monthlyInterestRate, 2) }}% per month</div>
            <div class="stats-cell stats-label">Total Amount:</div>
            <div class="stats-cell"><strong class="amount">{{ number_format($loan->total_amount, 2) }} TZS</strong></div>
        </div>
    </div>

    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Term:</div>
            <div class="stats-cell">{{ $loan->term_months }} months</div>
            <div class="stats-cell stats-label">Payment Frequency:</div>
            <div class="stats-cell">{{ ucfirst(str_replace('-', ' ', $loan->payment_frequency)) }}</div>
            <div class="stats-cell stats-label">Monthly Payment:</div>
            <div class="stats-cell"><strong class="amount">{{ number_format($monthlyPayment, 2) }} TZS</strong></div>
        </div>
    </div>

    <!-- Section 1: Agreement Parties -->
    <div class="section">
        <div class="section-header">1. Agreement Parties</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Agreement Date</td>
                    <td><strong>{{ $loan->application_date->format('F d, Y') }}</strong></td>
                </tr>
                <tr>
                    <td>Lender</td>
                    <td><strong>FeedTan Community Microfinance Group</strong>, located in Moshi (hereinafter referred to as "the Lender")</td>
                </tr>
                <tr>
                    <td>Borrower</td>
                    <td><strong>{{ $loan->user->name }}</strong>, residing at {{ $loan->user->address ?? 'N/A' }} (hereinafter referred to as "the Borrower")</td>
                </tr>
            </table>
            <p class="clause-content" style="margin-top: 10px;">
                IN CONSIDERATION of the Lender loaning certain monies (the "Loan") to the Borrower, and the Borrower agreeing to repay the Loan to the Lender, both parties hereby agree to keep, perform, and fulfill the promises and conditions set out in this Agreement.
            </p>
        </div>
    </div>

    <!-- Section 2: Loan Amount and Interest -->
    <div class="section">
        <div class="section-header">2. Loan Amount and Interest</div>
        <div class="section-content">
            <p class="clause-content">
                The Lender agrees to loan <span class="amount">TSh {{ number_format($loan->principal_amount, 2) }}</span> to the Borrower, and the Borrower agrees to repay this principal amount to the Lender, with interest payable on the principal at a rate of <span class="amount">{{ number_format($monthlyInterestRate, 2) }}%</span> per month. The loan type is <strong>{{ $loan->loan_type ?? 'Standard Loan' }}</strong>.
            </p>
        </div>
    </div>

    <!-- Section 3: Payment Terms -->
    <div class="section">
        <div class="section-header">3. Payment Terms</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Payment Schedule</td>
                    <td>Consecutive {{ str_replace('-', ' ', $loan->payment_frequency) }} installments of <strong class="amount">{{ number_format($monthlyPayment, 2) }} TZS</strong></td>
                </tr>
                <tr>
                    <td>Start Date</td>
                    <td><strong>{{ $startDate->format('F d, Y') }}</strong></td>
                </tr>
                <tr>
                    <td>End Date</td>
                    <td><strong>{{ $endDate->format('F d, Y') }}</strong></td>
                </tr>
            </table>
            <p class="clause-content" style="margin-top: 10px;">
                The Borrower may, at any time while not in default under this Agreement, repay the outstanding balance to the Lender without incurring any additional charges or penalties. In the event of a delayed installment, the Lender shall recover the due amount from the Borrower's savings, but only from savings exceeding those designated as loan security.
            </p>
            <p class="clause-content">
                If the excess savings are insufficient to cover the overdue amount, the Lender shall charge a late repayment fine, calculated as the product of the unpaid installment amount and the loan's monthly interest rate. In the case of Non-Performing Loans, Guarantors shall be responsible for paying the outstanding amount.
            </p>
        </div>
    </div>

    <!-- Section 4: Default Provisions -->
    <div class="section">
        <div class="section-header">4. Default Provisions</div>
        <div class="section-content">
            <p class="clause-content">
                Notwithstanding any provisions to the contrary in this Agreement, if the Borrower defaults in the performance of any obligation under this Agreement, the Lender may declare the entire principal and accrued interest to be immediately due and payable.
            </p>
            <p class="clause-content">
                Furthermore, in the event of persistent failure to pay or non-performance, the Lender reserves the right to contact the Borrower's employer to facilitate recovery of the outstanding debt. This action will be taken only after all other reasonable attempts to recover the debt have been exhausted and will be conducted in accordance with the laws of the United Republic of Tanzania.
            </p>
        </div>
    </div>

    <!-- Section 5: Governing Law -->
    <div class="section">
        <div class="section-header">5. Governing Law</div>
        <div class="section-content">
            <p class="clause-content">
                This Agreement shall be governed by and construed in accordance with the laws of the United Republic of Tanzania.
            </p>
        </div>
    </div>

    <!-- Section 6: Costs -->
    <div class="section">
        <div class="section-header">6. Costs</div>
        <div class="section-content">
            <p class="clause-content">
                All costs, expenses, and legal fees incurred by the Lender in enforcing this Agreement due to any default by the Borrower shall be added to the outstanding loan balance and shall be immediately payable by the Borrower.
            </p>
        </div>
    </div>

    <!-- Section 7: Binding Effect -->
    <div class="section">
        <div class="section-header">7. Binding Effect</div>
        <div class="section-content">
            <p class="clause-content">
                This Agreement shall bind and benefit the respective heirs, executors, administrators, successors, and permitted assigns of both the Borrower and the Lender.
            </p>
        </div>
    </div>

    <!-- Section 8: Amendments -->
    <div class="section">
        <div class="section-header">8. Amendments</div>
        <div class="section-content">
            <p class="clause-content">
                This Agreement may only be amended or modified by a written instrument signed by both the Borrower and the Lender.
            </p>
        </div>
    </div>

    <!-- Section 9: Severability -->
    <div class="section">
        <div class="section-header">9. Severability</div>
        <div class="section-content">
            <p class="clause-content">
                Each clause and paragraph of this Agreement is intended to be read and construed independently. If any term or provision is deemed invalid, void, or unenforceable by a court of competent jurisdiction, it shall be modified only to the extent necessary to render it reasonable and enforceable. All remaining provisions shall remain in full force and effect.
            </p>
        </div>
    </div>

    <!-- Section 10: Entire Agreement -->
    <div class="section">
        <div class="section-header">10. Entire Agreement</div>
        <div class="section-content">
            <p class="clause-content">
                This Agreement constitutes the entire understanding between the parties. No other terms, conditions, or agreements—oral or written—shall be binding unless included herein.
            </p>
        </div>
    </div>

    <!-- Section 11: Signatures -->
    <div class="section">
        <div class="section-header">11. Signatures</div>
        <div class="section-content">
            <table class="signatures-table">
                <tr>
                    <td style="width: 50%;">
                        <p><strong>1. Borrower</strong></p>
                        <p>{{ $loan->user->name }}</p>
                        <div class="signature-line"></div>
                        <p style="margin-top: 5px; font-size: 8pt;">(sign)</p>
                    </td>
                    <td style="width: 50%;"></td>
                </tr>
                <tr>
                    <td>
                        <p><strong>2. Chairperson</strong></p>
                        <p>{{ $chairpersonName ?? '_________________' }}</p>
                        <div class="signature-line"></div>
                        <p style="margin-top: 5px; font-size: 8pt;">(Signed, {{ $loan->application_date->format('F d, Y') }})</p>
                    </td>
                    <td>
                        <p><strong>3. Secretary</strong></p>
                        <p>{{ $secretaryName ?? '_________________' }}</p>
                        <div class="signature-line"></div>
                        <p style="margin-top: 5px; font-size: 8pt;">(signed, {{ $loan->application_date->format('F d, Y') }})</p>
                    </td>
                </tr>
            </table>
            <div style="text-align: center; margin-top: 20px;">
                @if(isset($stampBase64) && $stampBase64)
                <img src="{{ $stampBase64 }}" alt="Office Stamp" style="max-width: 200px; width: 200px; height: auto; display: block; margin: 0 auto;">
                @else
                <p>__________________________</p>
                <p style="font-size: 8pt;">Office Stamp</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Section 12: Guarantee Agreement -->
    @if($loan->guarantor)
    <div class="section">
        <div class="section-header">12. Guarantee Agreement and Signature</div>
        <div class="section-content">
            <p class="clause-content">
                We (I), <strong>{{ $loan->guarantor->name }}</strong>, a Ordinary member of FeedTan Community Microfinance Group (hereinafter referred to as the Guarantor), do hereby personally guarantee to ensure loan repayment based on the terms of the loan agreement between {{ $loan->user->name }} and FeedTan Community Microfinance Group.
            </p>
            <p class="clause-content">
                In the event that {{ $loan->user->name }} fails to repay the loan, the Guarantor(s) hereby promise to fulfill all payment obligations in the same manner as stipulated in the original loan agreement.
            </p>
            <p class="clause-content" style="margin-top: 15px;">
                IN WITNESS WHEREOF, this personal guaranty is entered into on {{ $loan->application_date->format('F d, Y') }}.
            </p>
            <table class="info-table" style="margin-top: 15px;">
                <tr>
                    <td>Guarantor Signature</td>
                    <td>________________________________</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><strong>{{ $loan->guarantor->name }}</strong></td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td><strong>{{ $loan->application_date->format('F d, Y') }}</strong></td>
                </tr>
            </table>
        </div>
    </div>
    @endif

    <!-- Section 13: Office Use Only -->
    <div class="section">
        <div class="section-header">13. FOR OFFICE USE ONLY</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Approval by Officer</td>
                    <td>Signature: ____________________________</td>
                </tr>
                <tr>
                    <td>Name of Officer</td>
                    <td>{{ $loan->approver->name ?? '____________________________' }}</td>
                </tr>
                <tr>
                    <td>Date of Approval</td>
                    <td>{{ $loan->approval_date ? $loan->approval_date->format('F d, Y') : '__________________' }}</td>
                </tr>
                <tr>
                    <td>Generated And Approved On</td>
                    <td>{{ $loan->approval_date ? $loan->approval_date->format('m/d/Y H:i:s') : now()->format('m/d/Y H:i:s') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Loan Agreement</p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Serial No: FCMGLA{{ $loan->application_date->format('Ymd') }}{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>
</body>
</html>
