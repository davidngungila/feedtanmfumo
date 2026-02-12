<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #015425 0%, #027a3a 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">Payment Confirmation</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">{{ $organizationInfo['name'] }}</p>
    </div>

    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px;">
        <p style="margin-top: 0;">Dear {{ $paymentConfirmation->member_name }},</p>
        
        <p>This email confirms that your payment distribution has been received and processed.</p>

        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #015425;">
            <h2 style="margin-top: 0; color: #015425;">Member Information</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; width: 40%;">Member ID:</td>
                    <td style="padding: 8px 0;">{{ $paymentConfirmation->member_id }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Member Name:</td>
                    <td style="padding: 8px 0;">{{ $paymentConfirmation->member_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Member Type:</td>
                    <td style="padding: 8px 0;">{{ $paymentConfirmation->member_type ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Deposit Balance:</td>
                    <td style="padding: 8px 0;">TZS {{ number_format($paymentConfirmation->deposit_balance, 2) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Amount to be Paid:</td>
                    <td style="padding: 8px 0; font-size: 18px; color: #015425; font-weight: bold;">TZS {{ number_format($paymentConfirmation->amount_to_pay, 2) }}</td>
                </tr>
            </table>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #027a3a;">
            <h2 style="margin-top: 0; color: #015425;">Payment Distribution</h2>
            <table style="width: 100%; border-collapse: collapse;">
                @if($paymentConfirmation->swf_contribution > 0)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; width: 50%;">SWF Contribution:</td>
                    <td style="padding: 8px 0; text-align: right;">TZS {{ number_format($paymentConfirmation->swf_contribution, 2) }}</td>
                </tr>
                @endif
                @if($paymentConfirmation->re_deposit > 0)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Re-deposit:</td>
                    <td style="padding: 8px 0; text-align: right;">TZS {{ number_format($paymentConfirmation->re_deposit, 2) }}</td>
                </tr>
                @endif
                @if($paymentConfirmation->fia_investment > 0)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">FIA Investment ({{ $paymentConfirmation->fia_type === '4_year' ? '4 Year' : '6 Year' }}):</td>
                    <td style="padding: 8px 0; text-align: right;">TZS {{ number_format($paymentConfirmation->fia_investment, 2) }}</td>
                </tr>
                @endif
                @if($paymentConfirmation->capital_contribution > 0)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Capital Contribution (Share):</td>
                    <td style="padding: 8px 0; text-align: right;">TZS {{ number_format($paymentConfirmation->capital_contribution, 2) }}</td>
                </tr>
                @endif
                @if($paymentConfirmation->loan_repayment > 0)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Loan Repayment:</td>
                    <td style="padding: 8px 0; text-align: right;">TZS {{ number_format($paymentConfirmation->loan_repayment, 2) }}</td>
                </tr>
                @endif
                <tr style="border-top: 2px solid #e5e7eb; margin-top: 10px;">
                    <td style="padding: 12px 0; font-weight: bold; font-size: 16px;">Total Distribution:</td>
                    <td style="padding: 12px 0; text-align: right; font-weight: bold; font-size: 16px; color: #015425;">TZS {{ number_format($paymentConfirmation->total_distribution, 2) }}</td>
                </tr>
            </table>
        </div>

        @if($paymentConfirmation->notes)
        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <strong>Notes:</strong>
            <p style="margin: 5px 0 0 0;">{{ $paymentConfirmation->notes }}</p>
        </div>
        @endif

        <p style="margin-top: 30px;">This confirmation has been recorded in our system. If you have any questions or concerns, please contact us.</p>

        <p style="margin-top: 30px;">Best regards,<br>
        <strong>{{ $organizationInfo['name'] }}</strong></p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; text-align: center;">
            <p style="margin: 5px 0;">{{ $organizationInfo['address'] }}</p>
            <p style="margin: 5px 0;">{{ $organizationInfo['po_box'] }}</p>
            <p style="margin: 5px 0;">{{ $organizationInfo['city'] }}, {{ $organizationInfo['region'] }}, {{ $organizationInfo['country'] }}</p>
        </div>
    </div>
</body>
</html>

