<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guarantee Agreement - {{ $assessment->ulid }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11pt; color: #1a202c; line-height: 1.5; margin: 0; padding: 0; }
        .page { padding: 40px; }
        .header { border-bottom: 2px solid #047857; padding-bottom: 20px; margin-bottom: 30px; text-align: center; }
        .logo { font-size: 24pt; font-bold: bold; color: #047857; text-transform: uppercase; letter-spacing: 2px; }
        .doc-title { font-size: 18pt; font-weight: bold; color: #1a202c; margin-top: 10px; text-transform: uppercase; }
        .badge { display: inline-block; background: #ecfdf5; color: #047857; padding: 5px 15px; border-radius: 4px; font-size: 9pt; font-weight: bold; margin-top: 10px; border: 1px solid #10b981; }
        
        .section { margin-bottom: 25px; }
        .section-title { font-size: 12pt; font-weight: bold; color: #065f46; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 15px; text-transform: uppercase; }
        
        .grid { width: 100%; border-collapse: collapse; }
        .grid td { vertical-align: top; padding: 8px 0; }
        .label { font-weight: bold; color: #4a5568; font-size: 9pt; display: block; }
        .value { font-size: 11pt; color: #1a202c; }
        
        .agreement-terms { background: #f9fafb; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb; font-size: 10pt; }
        .agreement-terms p { margin-bottom: 10px; }
        
        .signatures { margin-top: 50px; }
        .sig-box { width: 45%; display: inline-block; vertical-align: top; }
        .sig-line { border-bottom: 1px solid #1a202c; height: 40px; margin-bottom: 10px; }
        .sig-text { font-size: 9pt; color: #4a5568; }
        
        .digital-signature { background: #fffcf0; border: 1px dashed #d97706; padding: 15px; margin-top: 30px; text-align: center; }
        .ds-stamp { font-family: 'Courier New', Courier, monospace; font-size: 14pt; color: #b45309; }
        .ds-meta { font-size: 8pt; color: #92400e; margin-top: 5px; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8pt; color: #a0aec0; padding-bottom: 20px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="logo">FEEDTAN CMG</div>
            <div class="doc-title">Loan Guarantee Agreement</div>
            <div class="badge">LEGALLY BINDING INSTRUMENT</div>
        </div>

        <div class="section">
            <div class="section-title">1. THE PARTIES</div>
            <table class="grid">
                <tr>
                    <td width="33%">
                        <span class="label">GUARANTOR</span>
                        <span class="value">{{ $assessment->guarantor->name }}</span>
                    </td>
                    <td width="33%">
                        <span class="label">BORROWER</span>
                        <span class="value">{{ $assessment->loan->user->name }}</span>
                    </td>
                    <td width="33%">
                        <span class="label">LENDER</span>
                        <span class="value">FeedTan Community Microfinance</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">MEMBER NUMBER</span>
                        <span class="value">{{ $assessment->guarantor->member_number }}</span>
                    </td>
                    <td>
                        <span class="label">LOAN ACCOUNT</span>
                        <span class="value">{{ $assessment->loan->loan_number }}</span>
                    </td>
                    <td>
                        <span class="label">AGREEMENT REF</span>
                        <span class="value">#{{ $assessment->ulid }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">2. LOAN SPECIFICATIONS</div>
            <table class="grid">
                <tr>
                    <td width="50%">
                        <span class="label">PRINCIPAL LOAN AMOUNT</span>
                        <span class="value">TZS {{ number_format($assessment->loan->principal_amount, 2) }}</span>
                    </td>
                    <td width="50%">
                        <span class="label">LOAN PURPOSE</span>
                        <span class="value">{{ $assessment->loan_purpose === 'Other' ? $assessment->loan_purpose_other : $assessment->loan_purpose }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">LOAN DURATION</span>
                        <span class="value">{{ $assessment->loan->term_months }} Months</span>
                    </td>
                    <td>
                        <span class="label">GUARANTOR CAPACITY</span>
                        <span class="value">{{ $assessment->sufficient_savings === 'Yes' ? 'Verified (Savings Covered)' : 'Unverified' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">3. COVENANTS OF THE GUARANTOR</div>
            <div class="agreement-terms">
                <p><strong>3.1 Guarantee:</strong> The Guarantor irrevocably and unconditionally guarantees the prompt payment of the Principal and Interest when due. The Guarantor acknowledges that this is an absolute guarantee of payment and not merely of collection.</p>
                <p><strong>3.2 Primary Obligation:</strong> The liability of the Guarantor hereunder is that of a primary obligor. The Lender shall not be required to proceed against the Borrower or exhaust any other remedy before making a demand upon the Guarantor.</p>
                <p><strong>3.3 Recovery Authorization:</strong> The Guarantor hereby authorizes FeedTan CMG to recover any outstanding balance of this loan by deducting directly from the Guarantor's savings accounts, capital shares, or any other assets held with the group in the event of Borrower default.</p>
                <p><strong>3.4 Relationship Statement:</strong> The Guarantor declares their relationship to the Borrower as: "{{ $assessment->relationship === 'Other' ? $assessment->relationship_other : $assessment->relationship }}".</p>
            </div>
        </div>

        <div class="section" style="page-break-inside: avoid;">
            <div class="section-title">4. CONTINGENCY PLANS</div>
            <table class="grid">
                <tr>
                    <td>
                        <span class="label">BORROWER'S BACKUP PLAN (AS UNDERSTOOD BY GUARANTOR)</span>
                        <p class="value italic">"{{ $assessment->borrower_backup_plan }}"</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">GUARANTOR'S BACKUP PLAN</span>
                        <p class="value italic">"{{ $assessment->guarantor_backup_plan }}"</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="digital-signature">
            <div class="ds-stamp">/S/ {{ strtoupper($assessment->guarantor->name) }}</div>
            <div class="ds-meta">
                Executed Digitally on {{ $assessment->submitted_at->format('F d, Y @ H:i:s') }}<br>
                Unique Assessment ULID: {{ $assessment->ulid }}<br>
                Recorded IP: {{ request()->ip() }} | System Verification Code: {{ substr($assessment->ulid, -6) }}
            </div>
        </div>

        <div class="footer">
            FeedTan Community Microfinance Group | info@feedtan.or.tz | +255 622 239 304<br>
            This document is electronically generated and is legally binding under the Electronic Transactions Act.
        </div>
    </div>
</body>
</html>
