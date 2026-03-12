<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guarantor Assessment Confirmation - FeedTan CMG</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Poppins', sans-serif; color: #333; line-height: 1.6; }
        .email-container { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08); border: 1px solid #e2e8f0; }
        .header { background: #015425; padding: 30px 25px; text-align: center; color: white; }
        .header .title { font-size: 26px; font-weight: 700; margin-bottom: 5px; }
        .header .sub-title { font-size: 14px; opacity: 0.9; }
        .content { padding: 30px 25px; }
        .greeting { font-size: 18px; font-weight: 600; color: #2d3748; margin-bottom: 15px; }
        
        .card { background-color: #f7fafc; border: 1px solid #edf2f7; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
        .card-header { display: flex; align-items: center; margin-bottom: 15px; }
        .card-header .icon { font-size: 24px; margin-right: 12px; color: #015425; }
        .card-header h4 { margin: 0; font-size: 16px; font-weight: 600; color: #2d3748; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .info-item { padding: 10px; background: #f8fafc; border-radius: 6px; border-left: 3px solid #015425; }
        .info-label { font-size: 12px; font-weight: 600; color: #4a5568; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { font-size: 14px; color: #2d3748; font-weight: 500; }
        
        .assessment-summary { background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%); border: 2px solid #4caf50; border-radius: 12px; padding: 25px; margin: 25px 0; text-align: center; }
        .assessment-summary h3 { color: #015425; font-size: 18px; font-weight: 700; margin-bottom: 15px; }
        .assessment-summary .status { font-size: 16px; color: #2e7d32; font-weight: 600; margin: 10px 0; }
        .assessment-summary .reference { font-size: 14px; color: #4a5568; font-family: 'Courier New', monospace; background: white; padding: 8px 12px; border-radius: 4px; display: inline-block; margin: 10px 0; }
        
        .security-notice { background-color: #fff3cd; border-left: 5px solid #ffc107; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .security-notice h4 { margin-top: 0; font-size: 16px; color: #856404; font-weight: 600; display: flex; align-items: center; }
        .security-notice .icon { font-size: 20px; margin-right: 8px; }
        .security-notice ul { margin: 10px 0; padding-left: 20px; color: #856404; font-size: 14px; line-height: 1.8; }
        .security-notice li { margin-bottom: 8px; }
        
        .info-box { background-color: #e3f2fd; border-left: 5px solid #2196f3; padding: 20px; border-radius: 8px; margin: 25px 0; }
        .info-box p { margin: 0; font-size: 14px; color: #1565c0; line-height: 1.6; }
        
        .button-container { text-align: center; margin: 30px 0; }
        .action-button { display: inline-block; padding: 12px 25px; background-color: #015425; color: white !important; font-weight: 600; border-radius: 6px; text-decoration: none; transition: background-color 0.3s ease; }
        .action-button:hover { background-color: #027a3a; }
        
        .signature { margin-top: 40px; font-size: 14px; color: #4a5568; }
        .signature p { margin: 5px 0; }
        .footer { background-color: #015425; color: white; text-align: center; padding: 15px; font-size: 12px; letter-spacing: 0.5px; opacity: 0.8; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="title">FeedTan Community Microfinance Group</div>
            <div class="sub-title">{{ $organizationInfo['po_box'] ?? 'P.O.Box 7744' }}, {{ $organizationInfo['address'] ?? 'Ushirika Sokoine Road' }}, {{ $organizationInfo['city'] ?? 'Moshi' }}, {{ $organizationInfo['region'] ?? 'Kilimanjaro' }}, {{ $organizationInfo['country'] ?? 'Tanzania' }}</div>
        </div>
        
        <div class="content">
            <div class="greeting">Dear {{ $assessment->full_name }},</div>
            
            <p>Thank you for completing the guarantor assessment for the loan application referenced below. Your submission has been received and is currently under review by our loan committee.</p>
            
            <div class="card">
                <div class="card-header">
                    <div class="icon">📋</div>
                    <h4>Assessment Details</h4>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Assessment ID</div>
                        <div class="info-value">FCMG-GA-{{ str_pad($assessment->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Loan Reference</div>
                        <div class="info-value">{{ $loan->ulid }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Borrower Name</div>
                        <div class="info-value">{{ $loan->user->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Submission Date</div>
                        <div class="info-value">{{ $assessment->assessment_date->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="assessment-summary">
                <h3>🎯 Assessment Status</h3>
                <div class="status">✅ Successfully Submitted</div>
                <div class="reference">Reference: {{ $assessment->ulid }}</div>
                <p style="margin-top: 15px; font-size: 14px;">Your guarantor assessment has been recorded and will be reviewed by our loan committee within 2-3 business days.</p>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="icon">👤</div>
                    <h4>Your Information</h4>
                </div>
                
                <div class="info-grid">
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
                        <div class="info-label">Relationship</div>
                        <div class="info-value">{{ $assessment->relationship }}{{ $assessment->relationship_other ? ' (' . $assessment->relationship_other . ')' : '' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="security-notice">
                <h4><span class="icon">🔒</span>Important Security Notice</h4>
                <ul>
                    <li>Your guarantor assessment is now part of our official records</li>
                    <li>The attached PDF document contains your complete assessment details</li>
                    <li>Please keep this document for your records</li>
                    <li>Any changes to your assessment must be submitted in writing</li>
                    <li>Your personal information is protected under our privacy policy</li>
                </ul>
            </div>
            
            <div class="info-box">
                <p><strong>Next Steps:</strong> Our loan committee will review your assessment and the borrower's loan application. You will be notified via email and SMS once a decision has been made. This process typically takes 2-3 business days.</p>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="icon">📞</div>
                    <h4>Contact Information</h4>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Office Phone</div>
                        <div class="info-value">{{ $organizationInfo['phone'] ?? '+255 27 275 0010' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Mobile Phone</div>
                        <div class="info-value">{{ $organizationInfo['mobile'] ?? '+255 754 0010' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $organizationInfo['email'] ?? 'info@feedtancmg.org' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Website</div>
                        <div class="info-value">{{ $organizationInfo['website'] ?? 'www.feedtancmg.org' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="signature">
                <p><strong>FeedTan Community Microfinance Group</strong></p>
                <p>{{ $organizationInfo['city'] ?? 'Moshi' }}, {{ $organizationInfo['region'] ?? 'Kilimanjaro' }}</p>
                <p>{{ $organizationInfo['country'] ?? 'Tanzania' }}</p>
                <p style="margin-top: 10px; font-size: 12px; color: #718096;">This email was sent to {{ $assessment->email }} on {{ now()->format('d M Y, H:i') }}</p>
            </div>
        </div>
        
        <div class="footer">
            <div>© {{ date('Y') }} FeedTan Community Microfinance Group. All rights reserved.</div>
            <div>Powered by FeedTan CMG @2026 SECURED PAYMENT GATEWAY</div>
        </div>
    </div>
</body>
</html>
