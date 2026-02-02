@extends('layouts.app')

@push('styles')
<style>
    /* ===== CSS Variables ===== */
    :root {
        --primary-color: #015425;
        --primary-dark: #013019;
        --primary-light: #027a3a;
    }

    /* ===== Container & Layout ===== */
    .legal-container {
        background: linear-gradient(135deg, #013019 0%, #015425 25%, #027a3a 50%, #015425 75%, #013019 100%);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding: 2rem 1rem;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .legal-wrapper {
        max-width: 900px;
        margin: 0 auto;
    }

    /* ===== Card ===== */
    .legal-card {
        backdrop-filter: blur(20px) saturate(180%);
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        border-radius: 1.5rem;
        padding: 2.5rem;
        transition: all 0.3s ease;
    }

    @media (max-width: 640px) {
        .legal-card {
            padding: 1.5rem;
        }
    }

    /* ===== Header ===== */
    .legal-header {
        text-align: center;
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .legal-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .legal-header p {
        color: #6b7280;
        font-size: 0.95rem;
    }

    /* ===== Content ===== */
    .legal-content {
        line-height: 1.8;
        color: #374151;
    }

    .legal-content h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-top: 2rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .legal-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .legal-content p {
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .legal-content ul, .legal-content ol {
        margin: 1rem 0;
        padding-left: 1.5rem;
    }

    .legal-content li {
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .legal-content strong {
        color: var(--primary-dark);
        font-weight: 600;
    }

    /* ===== Back Button ===== */
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        color: white;
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(1, 84, 37, 0.3);
        margin-bottom: 2rem;
    }

    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(1, 84, 37, 0.4);
    }

    /* ===== Footer ===== */
    .legal-footer {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 2px solid #e5e7eb;
        text-align: center;
        color: #6b7280;
        font-size: 0.875rem;
    }

    /* ===== Highlight Box ===== */
    .highlight-box {
        background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
        border-left: 4px solid var(--primary-color);
        padding: 1.25rem;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
    }

    .highlight-box p {
        margin: 0;
        color: #1f2937;
    }
</style>
@endpush

@section('content')
<div class="legal-container">
    <div class="legal-wrapper">
        <a href="{{ route('register') }}" class="back-button">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Registration
        </a>

        <div class="legal-card">
            <div class="legal-header">
                <h1>Terms of Service</h1>
                <p>Last updated: {{ date('F d, Y') }}</p>
            </div>

            <div class="legal-content">
                <div class="highlight-box">
                    <p><strong>Welcome to FEEDTAN DIGITAL!</strong> By accessing or using our platform, you agree to be bound by these Terms of Service. Please read them carefully.</p>
                </div>

                <h2>1. Acceptance of Terms</h2>
                <p>By registering for an account, accessing, or using FEEDTAN DIGITAL services, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service and our Privacy Policy.</p>

                <h2>2. Description of Service</h2>
                <p>FEEDTAN DIGITAL is a community microfinance management platform that provides:</p>
                <ul>
                    <li>Membership management and registration</li>
                    <li>Loan application and management services</li>
                    <li>Savings account services</li>
                    <li>Investment opportunities</li>
                    <li>Social welfare services</li>
                    <li>Financial reporting and statements</li>
                </ul>

                <h2>3. User Accounts</h2>
                <h3>3.1 Account Registration</h3>
                <p>To use our services, you must:</p>
                <ul>
                    <li>Provide accurate, current, and complete information during registration</li>
                    <li>Maintain and update your information to keep it accurate</li>
                    <li>Be at least 18 years old or have parental consent</li>
                    <li>Maintain the security of your account credentials</li>
                </ul>

                <h3>3.2 Account Security</h3>
                <p>You are responsible for:</p>
                <ul>
                    <li>Maintaining the confidentiality of your login credentials</li>
                    <li>All activities that occur under your account</li>
                    <li>Notifying us immediately of any unauthorized access</li>
                    <li>Using a strong, unique password</li>
                </ul>

                <h2>4. Membership Requirements</h2>
                <p>To become a member of FeedTan Community Microfinance Group, you must:</p>
                <ul>
                    <li>Complete the membership application process</li>
                    <li>Provide all required documentation</li>
                    <li>Meet eligibility criteria as determined by the organization</li>
                    <li>Pay required membership fees and contributions</li>
                    <li>Comply with all membership obligations</li>
                </ul>

                <h2>5. Financial Services</h2>
                <h3>5.1 Loans</h3>
                <p>Loan services are subject to:</p>
                <ul>
                    <li>Credit assessment and approval processes</li>
                    <li>Terms and conditions specific to each loan product</li>
                    <li>Interest rates and fees as disclosed</li>
                    <li>Repayment schedules and obligations</li>
                </ul>

                <h3>5.2 Savings</h3>
                <p>Savings accounts are subject to:</p>
                <ul>
                    <li>Minimum balance requirements</li>
                    <li>Interest rates as disclosed</li>
                    <li>Withdrawal terms and conditions</li>
                    <li>Account maintenance fees (if applicable)</li>
                </ul>

                <h2>6. User Conduct</h2>
                <p>You agree not to:</p>
                <ul>
                    <li>Use the service for any illegal or unauthorized purpose</li>
                    <li>Violate any laws in your jurisdiction</li>
                    <li>Transmit any viruses, malware, or harmful code</li>
                    <li>Attempt to gain unauthorized access to the system</li>
                    <li>Interfere with or disrupt the service</li>
                    <li>Impersonate any person or entity</li>
                    <li>Provide false or misleading information</li>
                </ul>

                <h2>7. Fees and Charges</h2>
                <p>You agree to pay all fees and charges associated with:</p>
                <ul>
                    <li>Membership fees and contributions</li>
                    <li>Loan interest and processing fees</li>
                    <li>Transaction fees (where applicable)</li>
                    <li>Account maintenance fees (where applicable)</li>
                    <li>Any other charges as disclosed</li>
                </ul>
                <p>All fees are non-refundable unless otherwise stated.</p>

                <h2>8. Privacy and Data Protection</h2>
                <p>Your use of our services is also governed by our Privacy Policy. We are committed to protecting your personal information and financial data in accordance with applicable data protection laws.</p>

                <h2>9. Intellectual Property</h2>
                <p>All content, features, and functionality of FEEDTAN DIGITAL, including but not limited to text, graphics, logos, and software, are owned by FeedTan Community Microfinance Group and are protected by copyright, trademark, and other intellectual property laws.</p>

                <h2>10. Limitation of Liability</h2>
                <p>To the maximum extent permitted by law:</p>
                <ul>
                    <li>FEEDTAN DIGITAL is provided "as is" without warranties</li>
                    <li>We are not liable for indirect, incidental, or consequential damages</li>
                    <li>Our total liability is limited to the amount you paid for services</li>
                    <li>We are not responsible for third-party services or content</li>
                </ul>

                <h2>11. Termination</h2>
                <p>We reserve the right to:</p>
                <ul>
                    <li>Suspend or terminate your account for violations of these terms</li>
                    <li>Refuse service to anyone at any time</li>
                    <li>Modify or discontinue services with notice</li>
                </ul>
                <p>You may terminate your account at any time by contacting us.</p>

                <h2>12. Changes to Terms</h2>
                <p>We reserve the right to modify these Terms of Service at any time. We will notify users of material changes via email or through the platform. Continued use of the service after changes constitutes acceptance of the new terms.</p>

                <h2>13. Governing Law</h2>
                <p>These Terms of Service are governed by the laws of Tanzania. Any disputes shall be resolved in the courts of Tanzania.</p>

                <h2>14. Contact Information</h2>
                <p>If you have questions about these Terms of Service, please contact us:</p>
                <ul>
                    <li><strong>Email:</strong> info@feedtan.co.tz</li>
                    <li><strong>Address:</strong> P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania</li>
                </ul>

                <div class="legal-footer">
                    <p>© {{ date('Y') }} FeedTan Community Microfinance Group. All rights reserved.</p>
                    <p class="mt-2">By using FEEDTAN DIGITAL, you acknowledge that you have read and agree to these Terms of Service.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


