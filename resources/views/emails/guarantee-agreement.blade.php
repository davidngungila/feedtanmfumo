<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; mx-auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; margin: 20px auto; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { max-height: 60px; }
        .title { color: #1e40af; font-size: 20px; font-weight: bold; text-transform: uppercase; margin-top: 10px; }
        .section-title { background: #f8fafc; padding: 8px 12px; border-left: 4px solid #3b82f6; font-weight: bold; margin: 20px 0 10px 0; color: #1e3a8a; }
        .field { margin-bottom: 8px; }
        .label { font-weight: bold; color: #64748b; font-size: 12px; text-transform: uppercase; display: block; }
        .value { font-size: 15px; color: #1e293b; }
        .agreement-box { background: #fffaf0; border: 1px solid #feebc8; padding: 15px; border-radius: 6px; font-size: 13px; color: #744210; margin-top: 20px; }
        .footer { text-align: center; font-size: 11px; color: #94a3b8; margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; }
        .badge-success { background: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('feedtan_logo.png')) }}" alt="FeedTan Logo" class="logo">
            <div class="title">Loan Guarantee Agreement</div>
            <div class="badge badge-success">LEGAL DOCUMENT - DIGITAL SIGNATURE RECORDED</div>
        </div>

        <p>This agreement serves as a formal record of the guarantee provided for the loan detailed below.</p>

        <div class="section-title">PARTIES</div>
        <div class="field">
            <span class="label">The Guarantor</span>
            <span class="value">{{ $assessment->guarantor->name }} (Member No: {{ $assessment->guarantor->member_number }})</span>
        </div>
        <div class="field">
            <span class="label">The Principal Borrower</span>
            <span class="value">{{ $assessment->loan->user->name }}</span>
        </div>
        <div class="field">
            <span class="label">The Lender</span>
            <span class="value">FeedTan Community Microfinance Group</span>
        </div>

        <div class="section-title">LOAN DETAILS</div>
        <div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="field">
                <span class="label">Agreement ID</span>
                <span class="value">#{{ $assessment->ulid }}</span>
            </div>
            <div class="field">
                <span class="label">Execution Date</span>
                <span class="value">{{ $assessment->submitted_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="field">
                <span class="label">Loan Amount</span>
                <span class="value">TZS {{ number_format($assessment->loan->principal_amount) }}</span>
            </div>
            <div class="field">
                <span class="label">Loan Purpose</span>
                <span class="value">{{ $assessment->loan_purpose === 'Other' ? $assessment->loan_purpose_other : $assessment->loan_purpose }}</span>
            </div>
        </div>

        <div class="section-title">TERMS OF GUARANTEE</div>
        <div style="font-size: 13px; color: #475569;">
            <p><strong>1. Guarantor's Unconditional Obligation:</strong> The Guarantor hereby irrevocably and unconditionally guarantees the due and punctual payment of all sums payable by the Borrower under the loan agreement.</p>
            <p><strong>2. Primary Responsibility:</strong> The Guarantor's liability is as a primary obligor. FeedTan may demand payment from the Guarantor without first exhausting its recourse against the Borrower.</p>
            <p><strong>3. Recovery Mechanism:</strong> In the event of default, FeedTan is authorized to recover the outstanding amount by deducting from the Borrower's savings first, and then from the Guarantor's savings and capital without further notice.</p>
        </div>

        <div class="agreement-box">
            <strong>ACKNOWLEDGEMENT OF RISKS UNDERSTOOD:</strong><br>
            <ul>
                <li>Sole Responsibility: {{ $assessment->solely_responsible_understanding }}</li>
                <li>Recovery Mechanism: {{ $assessment->recovery_mechanism_understanding }}</li>
                <li>Financial Capacity: {{ $assessment->sufficient_savings }}</li>
            </ul>
        </div>

        <div style="margin-top: 30px; text-align: center; border: 1px dashed #cbd5e1; padding: 15px; background: #fcfcfc;">
            <div style="font-family: 'Courier New', Courier, monospace; font-size: 18px; color: #1e3a8a;">
                /s/ {{ $assessment->guarantor->name }}
            </div>
            <div style="font-size: 10px; color: #94a3b8; margin-top: 5px;">
                Digitally signed via FeedTan Internal Assessment Portal<br>
                IP Address: {{ request()->ip() }} | Timestamp: {{ now() }}
            </div>
        </div>

        <p style="font-size: 13px; text-align: center; color: #64748b; margin-top: 20px;">
            <em>A formal copy of this agreement is attached to this email as a PDF document for your records.</em>
        </p>

        <div class="footer">
            FeedTan Community Microfinance Group<br>
            P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania<br>
            Email: feedtan15@gmail.com | Phone: +255622239304
        </div>
    </div>
</body>
</html>
