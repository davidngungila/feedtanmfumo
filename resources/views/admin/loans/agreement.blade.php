<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loan Agreement - {{ $loan->loan_number }}</title>
    <style>
        @page {
            margin: 15mm 20mm;
            size: A4;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 10px 0;
        }
        .header-info {
            font-size: 10pt;
            margin: 5px 0;
        }
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin: 20px 0 15px 0;
            text-transform: uppercase;
        }
        .serial-number {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .agreement-intro {
            margin: 20px 0;
            text-align: justify;
        }
        .parties {
            margin: 20px 0;
        }
        .party {
            margin: 10px 0;
            text-align: justify;
        }
        .clause {
            margin: 15px 0;
            text-align: justify;
        }
        .clause-number {
            font-weight: bold;
        }
        .clause-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .signatures {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-item {
            margin: 15px 0;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            width: 200px;
        }
        .guarantee-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .guarantee-title {
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 15px;
        }
        .office-use {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .office-use-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9pt;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .footer-line {
            margin: 3px 0;
        }
        .text-center {
            text-align: center;
        }
        .text-bold {
            font-weight: bold;
        }
        .amount {
            font-weight: bold;
        }
        table.signatures-table {
            width: 100%;
            margin-top: 20px;
        }
        table.signatures-table td {
            padding: 10px;
            vertical-align: top;
        }
        .stamp-area {
            margin-top: 20px;
            text-align: center;
            min-height: 60px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FeedTan Community Microfinance Group</h1>
        <div class="header-info">P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania</div>
        <div class="header-info">Email: Feedtan15@gmail.com | Mobile: 0717358865</div>
    </div>

    <div class="title">LOAN AGREEMENT</div>

    <div class="serial-number">
        Serial Number: FCMGLA{{ date('Ymd') }}{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}
    </div>

    <div class="agreement-intro">
        <p><strong>THIS LOAN AGREEMENT</strong> (this "Agreement") is made and entered into on <strong>{{ $loan->application_date->format('F d, Y') }}</strong>,</p>
        
        <p class="text-bold" style="margin-top: 15px;">BETWEEN:</p>
        
        <div class="parties">
            <p class="party">
                <strong>FeedTan Community Microfinance Group</strong>, located in Moshi (hereinafter referred to as "the Lender"), AND
            </p>
            
            <p class="party">
                <strong>{{ $loan->user->name }}</strong>, residing at {{ $loan->user->address ?? 'N/A' }} (hereinafter referred to as "the Borrower").
            </p>
        </div>
        
        <p style="margin-top: 15px;">
            IN CONSIDERATION of the Lender loaning certain monies (the "Loan") to the Borrower, and the Borrower agreeing to repay the Loan to the Lender, both parties hereby agree to keep, perform, and fulfill the promises and conditions set out in this Agreement.
        </p>
    </div>

    <!-- Clause 1: Loan Amount and Interest -->
    <div class="clause">
        <p class="clause-number">1. Loan Amount and Interest</p>
        <p>
            The Lender agrees to loan <span class="amount">TSh {{ number_format($loan->principal_amount, 2) }}</span> to the Borrower, and the Borrower agrees to repay this principal amount to the Lender, with interest payable on the principal at a rate of <span class="amount">{{ number_format(($loan->interest_rate / 12), 2) }}%</span> per month. The loan type is <strong>{{ $loan->loan_type ?? 'Standard Loan' }}</strong>.
        </p>
    </div>

    <!-- Clause 2: Payment -->
    <div class="clause">
        <p class="clause-number">2. Payment</p>
        <p>
            This Loan will be repaid in consecutive {{ str_replace('-', ' ', $loan->payment_frequency) }} installments of principal and interest totaling <span class="amount">TSh {{ number_format($monthlyPayment, 2) }}</span>, beginning on <strong>{{ $startDate->format('m/d/Y') }}</strong> and ending on <strong>{{ $endDate->format('m/d/Y') }}</strong>.
        </p>
        <p>
            The Borrower may, at any time while not in default under this Agreement, repay the outstanding balance to the Lender without incurring any additional charges or penalties. In the event of a delayed installment, the Lender shall recover the due amount from the Borrower's savings, but only from savings exceeding those designated as loan security.
        </p>
        <p>
            If the excess savings are insufficient to cover the overdue amount, the Lender shall charge a late repayment fine, calculated as the product of the unpaid installment amount and the loan's monthly interest rate. In the case of Non-Performing Loans, Guarantors shall be responsible for paying the outstanding amount.
        </p>
    </div>

    <!-- Clause 3: Default -->
    <div class="clause">
        <p class="clause-number">3. Default</p>
        <p>
            Notwithstanding any provisions to the contrary in this Agreement, if the Borrower defaults in the performance of any obligation under this Agreement, the Lender may declare the entire principal and accrued interest to be immediately due and payable.
        </p>
        <p>
            Furthermore, in the event of persistent failure to pay or non-performance, the Lender reserves the right to contact the Borrower's employer to facilitate recovery of the outstanding debt. This action will be taken only after all other reasonable attempts to recover the debt have been exhausted and will be conducted in accordance with the laws of the United Republic of Tanzania.
        </p>
    </div>

    <!-- Clause 4: Governing Law -->
    <div class="clause">
        <p class="clause-number">4. Governing Law</p>
        <p>
            This Agreement shall be governed by and construed in accordance with the laws of the United Republic of Tanzania.
        </p>
    </div>

    <!-- Clause 5: Costs -->
    <div class="clause">
        <p class="clause-number">5. Costs</p>
        <p>
            All costs, expenses, and legal fees incurred by the Lender in enforcing this Agreement due to any default by the Borrower shall be added to the outstanding loan balance and shall be immediately payable by the Borrower.
        </p>
    </div>

    <!-- Clause 6: Binding Effect -->
    <div class="clause">
        <p class="clause-number">6. Binding Effect</p>
        <p>
            This Agreement shall bind and benefit the respective heirs, executors, administrators, successors, and permitted assigns of both the Borrower and the Lender.
        </p>
    </div>

    <!-- Clause 7: Amendments -->
    <div class="clause">
        <p class="clause-number">7. Amendments</p>
        <p>
            This Agreement may only be amended or modified by a written instrument signed by both the Borrower and the Lender.
        </p>
    </div>

    <!-- Clause 8: Severability -->
    <div class="clause">
        <p class="clause-number">8. Severability</p>
        <p>
            Each clause and paragraph of this Agreement is intended to be read and construed independently. If any term or provision is deemed invalid, void, or unenforceable by a court of competent jurisdiction, it shall be modified only to the extent necessary to render it reasonable and enforceable. All remaining provisions shall remain in full force and effect.
        </p>
    </div>

    <!-- Clause 9: Entire Agreement -->
    <div class="clause">
        <p class="clause-number">9. Entire Agreement</p>
        <p>
            This Agreement constitutes the entire understanding between the parties. No other terms, conditions, or agreements—oral or written—shall be binding unless included herein.
        </p>
    </div>

    <!-- Signatures -->
    <div class="signatures">
        <p class="text-bold" style="margin-bottom: 20px;">Signatures</p>
        
        <table class="signatures-table">
            <tr>
                <td style="width: 50%;">
                    <p><strong>1. Borrower</strong> {{ $loan->user->name }}</p>
                    <div class="signature-line"></div>
                    <p style="margin-top: 5px; font-size: 9pt;">(sign)</p>
                </td>
                <td style="width: 50%;"></td>
            </tr>
            <tr>
                <td>
                    <p><strong>2. Chairperson</strong> {{ $chairpersonName ?? '_________________' }}</p>
                    <div class="signature-line"></div>
                    <p style="margin-top: 5px; font-size: 9pt;">(Signed, {{ $loan->application_date->format('F d, Y') }})</p>
                </td>
                <td>
                    <p><strong>3. Secretary</strong> {{ $secretaryName ?? '_________________' }}</p>
                    <div class="signature-line"></div>
                    <p style="margin-top: 5px; font-size: 9pt;">(signed, {{ $loan->application_date->format('F d, Y') }})</p>
                </td>
            </tr>
        </table>

        <div class="stamp-area">
            <p style="margin-top: 20px;">__________________________</p>
            <p>Office Stamp</p>
        </div>
    </div>

    <!-- Guarantee Agreement -->
    @if($loan->guarantor)
    <div class="guarantee-section">
        <p class="guarantee-title">Guarantee Agreement and Signature</p>
        
        <p>
            We (I), <strong>{{ $loan->guarantor->name }}</strong>, a Ordinary member of FeedTan Community Microfinance Group (hereinafter referred to as the Guarantor), do hereby personally guarantee to ensure loan repayment based on the terms of the loan agreement between {{ $loan->user->name }} and FeedTan Community Microfinance Group.
        </p>
        
        <p>
            In the event that {{ $loan->user->name }} fails to repay the loan, the Guarantor(s) hereby promise to fulfill all payment obligations in the same manner as stipulated in the original loan agreement.
        </p>
        
        <p style="margin-top: 15px;">
            IN WITNESS WHEREOF, this personal guaranty is entered into on {{ $loan->application_date->format('F d, Y') }}.
        </p>
        
        <div style="margin-top: 30px;">
            <p><strong>Guarantor Signature:</strong> ________________________________</p>
            <p><strong>Name:</strong> {{ $loan->guarantor->name }} <strong>Date:</strong> {{ $loan->application_date->format('F d, Y') }}</p>
        </div>
    </div>
    @endif

    <!-- Office Use Only -->
    <div class="office-use">
        <p class="office-use-title">────────────────────────────────────────────</p>
        <p class="office-use-title">FOR OFFICE USE ONLY</p>
        
        <div style="margin-top: 20px;">
            <p><strong>Approval by Officer:</strong></p>
            <p>Signature: ____________________________</p>
            <p>Name of Officer: {{ $loan->approver->name ?? '____________________________' }}</p>
            <p>Date of Approval: {{ $loan->approval_date ? $loan->approval_date->format('F d, Y') : '__________________' }}</p>
        </div>
        
        <p style="margin-top: 15px;">
            <strong>Generated And Approved On:</strong> {{ now()->format('m/d/Y H:i:s') }}.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-line">═══════════════════════════════════════════</div>
        <div class="footer-line"><strong>Celebrating 10 Years of Excellence and Members Impact!</strong></div>
        <div class="footer-line">Thank you for being part of our journey!</div>
        <div class="footer-line"><strong>Celebrating 10 Years of Excellence and Member Impact – 2025 | Let's Grow Together</strong></div>
    </div>
</body>
</html>

