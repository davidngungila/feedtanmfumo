<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Guarantor Assessment Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #015425;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #015425;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 30px;
        }
        .brand-name {
            font-weight: bold;
            color: #015425;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FeedTan CMG</h1>
        <p>Guarantor Assessment Submitted</p>
    </div>
    
    <div class="content">
        <h2>Guarantor Assessment Received</h2>
        
        <div class="details">
            <p><strong>Loan Reference:</strong> {{ $loan->ulid }}</p>
            <p><strong>Guarantor Name:</strong> {{ $assessment->full_name }}</p>
            <p><strong>Member Code:</strong> {{ $assessment->member_code }}</p>
            <p><strong>Relationship:</strong> {{ $assessment->relationship }}{{ $assessment->relationship_other ? ' (' . $assessment->relationship_other . ')' : '' }}</p>
            <p><strong>Assessment Date:</strong> {{ $assessment->assessment_date->format('d M Y, H:i') }}</p>
        </div>
        
        <p>Good news! A guarantor assessment has been submitted for your loan application.</p>
        
        <p><strong>Guarantor Details:</strong></p>
        <ul>
            <li>Name: {{ $assessment->full_name }}</li>
            <li>Member Code: {{ $assessment->member_code }}</li>
            <li>Phone: {{ $assessment->phone }}</li>
            <li>Email: {{ $assessment->email }}</li>
            <li>Relationship: {{ $assessment->relationship }}</li>
        </ul>
        
        <p>The assessment will be reviewed by our loan committee. You will be notified once the review process is complete.</p>
        
        <p><strong>Next Steps:</strong></p>
        <ul>
            <li>Our team will review the guarantor assessment</li>
            <li>You will receive notification of the review outcome</li>
            <li>If approved, your loan processing will continue</li>
        </ul>
        
        <div class="footer">
            <div class="brand-name">FEEDTAN CMG</div>
            <div>Feedtan Community Microfinance Group</div>
            <div>Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY</div>
        </div>
    </div>
</body>
</html>
