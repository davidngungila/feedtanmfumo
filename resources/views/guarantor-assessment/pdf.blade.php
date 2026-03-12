<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Guarantor Agreement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #015425;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #015425;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #015425;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
        }
        .info-value {
            color: #333;
            font-size: 14px;
        }
        .agreement-text {
            text-align: justify;
            margin-bottom: 20px;
            font-size: 12px;
            line-height: 1.5;
        }
        .signature-section {
            margin-top: 50px;
            border-top: 2px solid #015425;
            padding-top: 30px;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin: 40px 0 10px 0;
            height: 30px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">FEEDTAN CMG</div>
        <div>Feedtan Community Microfinance Group</div>
        <div style="font-size: 14px; color: #666;">Guarantor Agreement</div>
        <div style="font-size: 12px; color: #999;">Document ID: {{ $assessment->ulid }}</div>
    </div>

    <div class="section">
        <div class="section-title">Loan Information</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Loan Reference</div>
                <div class="info-value">{{ $loan->ulid }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Borrower Name</div>
                <div class="info-value">{{ $loan->user->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Loan Amount</div>
                <div class="info-value">TZS {{ number_format($loan->amount ?? 0, 2) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Assessment Date</div>
                <div class="info-value">{{ $assessment->assessment_date->format('d M Y') }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Guarantor Information</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Full Name</div>
                <div class="info-value">{{ $assessment->full_name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Membership Code</div>
                <div class="info-value">{{ $assessment->member_code }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Phone Number</div>
                <div class="info-value">{{ $assessment->phone }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email Address</div>
                <div class="info-value">{{ $assessment->email }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Relationship to Borrower</div>
                <div class="info-value">{{ $assessment->relationship }}{{ $assessment->relationship_other ? ' (' . $assessment->relationship_other . ')' : '' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Address</div>
                <div class="info-value">{{ $assessment->address }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Occupation</div>
                <div class="info-value">{{ $assessment->occupation }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Monthly Income</div>
                <div class="info-value">TZS {{ number_format($assessment->monthly_income, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Assessment Responses</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Loan Purpose Understanding</div>
                <div class="info-value">{{ $assessment->loan_purpose }}{{ $assessment->loan_purpose_other ? ' (' . $assessment->loan_purpose_other . ')' : '' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Repayment History Reviewed</div>
                <div class="info-value">{{ $assessment->repayment_history }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Existing Debts</div>
                <div class="info-value">{{ $assessment->existing_debts }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Sufficient Savings</div>
                <div class="info-value">{{ $assessment->sufficient_savings }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Sole Responsibility Understanding</div>
                <div class="info-value">{{ $assessment->sole_responsibility }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Recovery Process Understanding</div>
                <div class="info-value">{{ $assessment->recovery_process }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Voluntary Guarantee</div>
                <div class="info-value">{{ $assessment->voluntary_guarantee }}</div>
            </div>
        </div>
        @if($assessment->additional_comments)
        <div style="margin-top: 20px;">
            <div class="info-label">Additional Comments</div>
            <div class="info-value" style="margin-top: 5px;">{{ $assessment->additional_comments }}</div>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Guarantor Agreement Terms</div>
        <div class="agreement-text">
            <p><strong>1. Guarantee Undertaking:</strong> I, the undersigned, hereby irrevocably and unconditionally guarantee to Feedtan Community Microfinance Group (hereinafter referred to as "the Lender") the prompt payment of all principal, interest, costs, charges, and other amounts now or hereafter due and payable by the borrower to the Lender under the loan agreement referenced above.</p>
            
            <p><strong>2. Scope of Guarantee:</strong> This guarantee shall cover the entire outstanding loan amount together with all accrued interest, penalties, costs, charges, and expenses that may become due and payable by the borrower to the Lender under the loan agreement.</p>
            
            <p><strong>3. Continuing Guarantee:</strong> This guarantee shall continue in full force and effect until the loan, together with all interest, costs, charges, and other amounts payable thereunder, have been fully paid and discharged to the Lender.</p>
            
            <p><strong>4. No Notice Required:</strong> The Lender shall not be required to give any notice to the guarantor of any default by the borrower or of any demand made upon the borrower, and the guarantor hereby waives any such notice.</p>
            
            <p><strong>5. Immediate Payment:</strong> Upon demand by the Lender, the guarantor shall immediately pay to the Lender all amounts due and payable by the borrower under the loan agreement without any demand or notice being required to be made upon the borrower.</p>
            
            <p><strong>6. Waiver of Rights:</strong> The guarantor waives all rights to require the Lender to proceed against the borrower or any security held by the Lender before proceeding against the guarantor.</p>
            
            <p><strong>7. Acknowledgment:</strong> The guarantor acknowledges that they have read and understood the terms of this guarantee and that they are signing this agreement voluntarily and without any duress or undue influence.</p>
        </div>
    </div>

    <div class="signature-section">
        <div class="section-title">Signatures</div>
        <div class="signature-grid">
            <div class="signature-box">
                <div class="info-label">Guarantor Signature</div>
                <div class="signature-line"></div>
                <div class="info-value">{{ $assessment->full_name }}</div>
                <div class="info-label" style="margin-top: 10px;">Date: {{ $assessment->assessment_date->format('d M Y') }}</div>
            </div>
            <div class="signature-box">
                <div class="info-label">Witness Signature</div>
                <div class="signature-line"></div>
                <div class="info-value">_________________________</div>
                <div class="info-label" style="margin-top: 10px;">Date: _______________</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div><strong>FEEDTAN CMG</strong></div>
        <div>Feedtan Community Microfinance Group</div>
        <div>Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY</div>
        <div style="margin-top: 10px;">This document was generated electronically on {{ now()->format('d M Y, H:i') }}</div>
    </div>
</body>
</html>
