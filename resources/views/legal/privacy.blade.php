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
                <h1>Privacy Policy</h1>
                <p>Last updated: {{ date('F d, Y') }}</p>
            </div>

            <div class="legal-content">
                <div class="highlight-box">
                    <p><strong>Your privacy is important to us.</strong> This Privacy Policy explains how FeedTan Community Microfinance Group ("we," "our," or "us") collects, uses, discloses, and safeguards your information when you use FEEDTAN DIGITAL.</p>
                </div>

                <h2>1. Information We Collect</h2>
                <h3>1.1 Personal Information</h3>
                <p>We collect information that you provide directly to us, including:</p>
                <ul>
                    <li><strong>Identity Information:</strong> Name, date of birth, gender, national ID number, marital status</li>
                    <li><strong>Contact Information:</strong> Email address, phone number, physical address, postal code</li>
                    <li><strong>Financial Information:</strong> Bank account details, income information, employment details</li>
                    <li><strong>Membership Information:</strong> Membership application data, membership status, member number</li>
                    <li><strong>Account Credentials:</strong> Username, password (encrypted), security questions</li>
                </ul>

                <h3>1.2 Automatically Collected Information</h3>
                <p>When you use our platform, we automatically collect:</p>
                <ul>
                    <li>Device information (IP address, browser type, operating system)</li>
                    <li>Usage data (pages visited, time spent, features used)</li>
                    <li>Log files and analytics data</li>
                    <li>Cookies and similar tracking technologies</li>
                </ul>

                <h3>1.3 Financial Transaction Data</h3>
                <p>We collect and process:</p>
                <ul>
                    <li>Loan applications and transaction history</li>
                    <li>Savings account transactions</li>
                    <li>Investment records</li>
                    <li>Payment and repayment information</li>
                    <li>Welfare contributions and claims</li>
                </ul>

                <h2>2. How We Use Your Information</h2>
                <p>We use the collected information for the following purposes:</p>
                <ul>
                    <li><strong>Service Provision:</strong> To provide, maintain, and improve our microfinance services</li>
                    <li><strong>Account Management:</strong> To create and manage your account, process applications, and maintain membership records</li>
                    <li><strong>Financial Services:</strong> To process loans, manage savings accounts, handle investments, and process welfare services</li>
                    <li><strong>Communication:</strong> To send you notifications, updates, statements, and important information about your account</li>
                    <li><strong>Compliance:</strong> To comply with legal obligations, regulatory requirements, and prevent fraud</li>
                    <li><strong>Security:</strong> To protect the security and integrity of our platform and prevent unauthorized access</li>
                    <li><strong>Analytics:</strong> To analyze usage patterns and improve our services</li>
                </ul>

                <h2>3. Information Sharing and Disclosure</h2>
                <h3>3.1 We Do Not Sell Your Information</h3>
                <p>We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p>

                <h3>3.2 Service Providers</h3>
                <p>We may share information with trusted service providers who assist us in:</p>
                <ul>
                    <li>Email and SMS delivery services</li>
                    <li>Payment processing</li>
                    <li>Data storage and hosting</li>
                    <li>Analytics and reporting</li>
                </ul>
                <p>These providers are contractually obligated to protect your information and use it only for specified purposes.</p>

                <h3>3.3 Legal Requirements</h3>
                <p>We may disclose your information if required by:</p>
                <ul>
                    <li>Law, regulation, or legal process</li>
                    <li>Government authorities or regulatory bodies</li>
                    <li>Court orders or subpoenas</li>
                    <li>To protect our rights, property, or safety</li>
                </ul>

                <h3>3.4 Business Transfers</h3>
                <p>In the event of a merger, acquisition, or sale of assets, your information may be transferred to the new entity, subject to the same privacy protections.</p>

                <h2>4. Data Security</h2>
                <p>We implement industry-standard security measures to protect your information:</p>
                <ul>
                    <li><strong>Encryption:</strong> All sensitive data is encrypted in transit and at rest</li>
                    <li><strong>Access Controls:</strong> Limited access to personal information on a need-to-know basis</li>
                    <li><strong>Secure Infrastructure:</strong> Protected servers and secure network connections</li>
                    <li><strong>Regular Audits:</strong> Security assessments and vulnerability testing</li>
                    <li><strong>Password Protection:</strong> Strong password requirements and secure authentication</li>
                </ul>
                <p>However, no method of transmission over the internet is 100% secure. While we strive to protect your information, we cannot guarantee absolute security.</p>

                <h2>5. Data Retention</h2>
                <p>We retain your personal information for as long as:</p>
                <ul>
                    <li>Necessary to provide our services</li>
                    <li>Required by law or regulatory obligations</li>
                    <li>Needed for legitimate business purposes</li>
                    <li>You maintain an active account with us</li>
                </ul>
                <p>Financial records are typically retained for a minimum of 7 years as required by financial regulations.</p>

                <h2>6. Your Rights and Choices</h2>
                <p>You have the following rights regarding your personal information:</p>
                <ul>
                    <li><strong>Access:</strong> Request access to your personal information</li>
                    <li><strong>Correction:</strong> Request correction of inaccurate or incomplete information</li>
                    <li><strong>Deletion:</strong> Request deletion of your information (subject to legal requirements)</li>
                    <li><strong>Objection:</strong> Object to certain processing of your information</li>
                    <li><strong>Portability:</strong> Request a copy of your data in a portable format</li>
                    <li><strong>Withdrawal of Consent:</strong> Withdraw consent where processing is based on consent</li>
                </ul>
                <p>To exercise these rights, please contact us using the information provided in Section 10.</p>

                <h2>7. Cookies and Tracking Technologies</h2>
                <p>We use cookies and similar technologies to:</p>
                <ul>
                    <li>Remember your preferences and settings</li>
                    <li>Analyze site traffic and usage patterns</li>
                    <li>Improve user experience</li>
                    <li>Provide personalized content</li>
                </ul>
                <p>You can control cookies through your browser settings. However, disabling cookies may affect platform functionality.</p>

                <h2>8. Children's Privacy</h2>
                <p>Our services are not intended for individuals under 18 years of age. We do not knowingly collect personal information from children. If you believe we have collected information from a child, please contact us immediately.</p>

                <h2>9. International Data Transfers</h2>
                <p>Your information is primarily stored and processed in Tanzania. If we transfer your information internationally, we ensure appropriate safeguards are in place to protect your data in accordance with this Privacy Policy.</p>

                <h2>10. Changes to This Privacy Policy</h2>
                <p>We may update this Privacy Policy from time to time. We will notify you of material changes by:</p>
                <ul>
                    <li>Posting the updated policy on our platform</li>
                    <li>Sending an email notification</li>
                    <li>Displaying a notice on the platform</li>
                </ul>
                <p>The "Last updated" date at the top indicates when changes were made. Continued use of our services after changes constitutes acceptance of the updated policy.</p>

                <h2>11. Contact Us</h2>
                <p>If you have questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:</p>
                <ul>
                    <li><strong>Email:</strong> info@feedtan.co.tz</li>
                    <li><strong>Address:</strong> P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania</li>
                    <li><strong>Phone:</strong> Available through your account dashboard</li>
                </ul>

                <h2>12. Consent</h2>
                <p>By using FEEDTAN DIGITAL and providing your information, you consent to the collection, use, and disclosure of your information as described in this Privacy Policy.</p>

                <div class="legal-footer">
                    <p>© {{ date('Y') }} FeedTan Community Microfinance Group. All rights reserved.</p>
                    <p class="mt-2">This Privacy Policy is effective as of {{ date('F d, Y') }} and applies to all users of FEEDTAN DIGITAL.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


