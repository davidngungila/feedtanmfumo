<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uthibitisho wa Malipo</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 700px; margin: 0 auto; padding: 20px; background-color: #f4f4f4;">
    <div style="background: linear-gradient(135deg, #015425 0%, #027a3a 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h1 style="margin: 0; font-size: 26px; text-transform: uppercase; letter-spacing: 1px;">Uthibitisho wa Malipo</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 16px;">{{ $organizationInfo['name'] }}</p>
    </div>

    <div style="background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <p style="margin-top: 0; font-size: 16px;">Habari <strong>{{ $paymentConfirmation->member_name }}</strong>,</p>
        
        <p style="font-size: 15px;">Tunapenda kukutaarifu kuwa mchakato wako wa mgawanyo wa malipo umekamilika kikamilifu. Hapa chini ni muhtasari wa taarifa zako:</p>

        <div style="margin: 25px 0; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; border: 1px solid #e5e7eb;">
                <thead>
                    <tr style="background-color: #015425; color: white;">
                        <th style="padding: 12px 8px; border: 1px solid #015425; text-align: center;">S/N</th>
                        <th style="padding: 12px 8px; border: 1px solid #015425; text-align: left;">Member's Name</th>
                        <th style="padding: 12px 8px; border: 1px solid #015425; text-align: left;">Type</th>
                        <th style="padding: 12px 8px; border: 1px solid #015425; text-align: left;">ID</th>
                        <th style="padding: 12px 8px; border: 1px solid #015425; text-align: right;">Amount</th>
                        <th style="padding: 12px 8px; border: 1px solid #015425; text-align: right;">SWF</th>
                        <th style="padding: 12px 8px; border: 1px solid #015425; text-align: right;">Loan</th>
                        <th style="padding: 12px 8px; border: 1px solid #015425; text-align: right;">Capital</th>
                        <th style="padding: 12px 8px; border: 1px solid #015425; text-align: right;">Fine</th>
                        <th style="padding: 12px 8px; border: 1px solid #015425; text-align: right; background-color: #027a3a;">Net Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 10px 8px; border: 1px solid #e5e7eb; text-align: center;">{{ $paymentConfirmation->id }}</td>
                        <td style="padding: 10px 8px; border: 1px solid #e5e7eb; font-weight: bold;">{{ $paymentConfirmation->member_name }}</td>
                        <td style="padding: 10px 8px; border: 1px solid #e5e7eb;">{{ $paymentConfirmation->member_type ?? 'N/A' }}</td>
                        <td style="padding: 10px 8px; border: 1px solid #e5e7eb;">{{ $paymentConfirmation->member_id }}</td>
                        <td style="padding: 10px 8px; border: 1px solid #e5e7eb; text-align: right;">{{ number_format($paymentConfirmation->amount_to_pay, 0) }}</td>
                        <td style="padding: 10px 8px; border: 1px solid #e5e7eb; text-align: right;">{{ number_format($paymentConfirmation->swf_contribution, 0) }}</td>
                        <td style="padding: 10px 8px; border: 1px solid #e5e7eb; text-align: right;">{{ number_format($paymentConfirmation->loan_repayment, 0) }}</td>
                        <td style="padding: 10px 8px; border: 1px solid #e5e7eb; text-align: right;">{{ number_format($paymentConfirmation->capital_contribution, 0) }}</td>
                        <td style="padding: 10px 8px; border: 1px solid #e5e7eb; text-align: right;">{{ number_format($paymentConfirmation->fine_penalty, 0) }}</td>
                        <td style="padding: 10px 8px; border: 1px solid #e5e7eb; text-align: right; font-weight: bold; color: #015425; background-color: #f0fdf4;">{{ number_format($paymentConfirmation->cash_amount, 0) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #015425;">
            <h3 style="margin-top: 0; color: #015425; font-size: 16px;">Maelezo ya Malipo (Payment Method)</h3>
            <p style="margin: 5px 0; font-size: 14px;"><strong>Njia iliyotumika:</strong> {{ $paymentConfirmation->payment_method === 'bank' ? 'Benki (Bank Transfer)' : 'Simu ya Mkononi (Mobile Money)' }}</p>
            @if($paymentConfirmation->payment_method === 'bank')
                <p style="margin: 5px 0; font-size: 14px;"><strong>Akaunti:</strong> {{ $paymentConfirmation->bank_account_number }}</p>
            @else
                <p style="margin: 5px 0; font-size: 14px;"><strong>Mtandao:</strong> {{ ucfirst($paymentConfirmation->mobile_provider) }} ({{ $paymentConfirmation->mobile_number }})</p>
            @endif
        </div>

        <p style="font-size: 15px;">Tumekuambatanishia cheti (PDF document) chenye mchanganuo kamili kwa ajili ya kumbukumbu zako.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/') }}" style="background-color: #015425; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">Login to Portal</a>
        </div>

        <p style="font-size: 14px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 20px;">
            Hii ni barua pepe ya kiotomatiki, tafadhali usijibu. Kama una swali lolote, wasiliana na ofisi ya FEEDTAN.
        </p>

        <p style="font-size: 15px; margin-top: 20px;">Wako katika ujenzi wa uchumi,<br>
        <strong>Management - {{ $organizationInfo['name'] }}</strong></p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af; text-align: center;">
            <p style="margin: 5px 0;">{{ $organizationInfo['address'] }} | {{ $organizationInfo['city'] }}, {{ $organizationInfo['region'] }}</p>
            <p style="margin: 5px 0;">&copy; {{ date('Y') }} {{ $organizationInfo['name'] }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
