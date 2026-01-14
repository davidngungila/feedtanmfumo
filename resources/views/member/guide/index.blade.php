@extends('layouts.member')

@section('page-title', 'Member Guide')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Member Guide</h1>
        <p class="text-white text-opacity-90">Everything you need to know about using the FEEDTAN DIGITAL platform</p>
    </div>

    <!-- What Members Can Do -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-[#015425] mb-4">What Members Can Do</h2>
        
        <div class="space-y-4">
            <!-- Register & Verify Profile -->
            <div class="border-l-4 border-blue-500 pl-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">1. Register & Verify Profile</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>Complete KYC (Know Your Customer) details</li>
                    <li>Upload identification documents (National ID, Passport)</li>
                    <li>Set preferred contact methods</li>
                    <li>Choose membership type</li>
                </ul>
            </div>

            <!-- Dashboard Snapshot -->
            <div class="border-l-4 border-green-500 pl-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">2. Dashboard Snapshot</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>View balances (loans, savings, investments)</li>
                    <li>See upcoming payments and due dates</li>
                    <li>Track recent transactions</li>
                    <li>Receive alerts and notifications</li>
                </ul>
            </div>

            <!-- Loans -->
            <div class="border-l-4 border-purple-500 pl-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">3. Loans</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>Apply for loans with flexible terms</li>
                    <li>Upload supporting documents</li>
                    <li>Choose repayment frequency (weekly, bi-weekly, monthly)</li>
                    <li>View repayment schedules</li>
                    <li>Make installment payments</li>
                    <li>Track loan status (pending, approved, disbursed, active, completed, overdue)</li>
                </ul>
            </div>

            <!-- Savings -->
            <div class="border-l-4 border-yellow-500 pl-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">4. Savings</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>Open savings accounts (Emergency, RDA, Flex, Business)</li>
                    <li>Make deposits and withdrawals</li>
                    <li>View account balance and statements</li>
                    <li>Track interest earned and fees</li>
                    <li>Freeze/unfreeze accounts (if allowed)</li>
                </ul>
            </div>

            <!-- Investments -->
            <div class="border-l-4 border-pink-500 pl-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">5. Investments</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>View available investment plans (4-year, 6-year)</li>
                    <li>Subscribe to investment plans</li>
                    <li>Track principal amount, expected returns, and profit share</li>
                    <li>Receive maturity alerts</li>
                </ul>
            </div>

            <!-- Social Welfare -->
            <div class="border-l-4 border-red-500 pl-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">6. Social Welfare</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>View eligibility criteria</li>
                    <li>Request benefits (medical, funeral, educational, other)</li>
                    <li>Record contributions</li>
                    <li>Track approval and disbursement status</li>
                </ul>
            </div>

            <!-- Issues/Feedback -->
            <div class="border-l-4 border-indigo-500 pl-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">7. Issues/Feedback</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>Submit complaints, suggestions, or inquiries</li>
                    <li>Attach supporting documents</li>
                    <li>Track resolution status</li>
                    <li>Message officers for updates</li>
                </ul>
            </div>

            <!-- Notifications -->
            <div class="border-l-4 border-teal-500 pl-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">8. Notifications</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>Receive push notifications, SMS, and email alerts</li>
                    <li>Get notified about loan approvals and disbursements</li>
                    <li>Receive due payment reminders</li>
                    <li>Get welfare updates</li>
                </ul>
            </div>

            <!-- Profile & Security -->
            <div class="border-l-4 border-cyan-500 pl-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">9. Profile & Security</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>Update contact information</li>
                    <li>Change password</li>
                    <li>Manage 2FA (Two-Factor Authentication) if enabled</li>
                    <li>Set language preferences (English, Swahili)</li>
                </ul>
            </div>

            <!-- Documents -->
            <div class="border-l-4 border-orange-500 pl-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">10. Documents</h3>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>Download account statements</li>
                    <li>View loan repayment schedules</li>
                    <li>Download payment receipts</li>
                    <li>Access all your financial documents</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Minimum Data Requirements -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-[#015425] mb-4">Minimum Data Requirements</h2>
        
        <div class="space-y-4">
            <!-- Registration/KYC -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Registration/KYC</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <ul class="list-disc list-inside space-y-1 text-gray-600 text-sm">
                        <li>Name, Email, Phone Number</li>
                        <li>Date of Birth</li>
                        <li>National ID Number</li>
                        <li>Address (City, Region, Postal Code)</li>
                        <li>Occupation and Monthly Income</li>
                        <li>Next-of-Kin/Beneficiary Information</li>
                        <li>Selfie and ID Images</li>
                    </ul>
                </div>
            </div>

            <!-- Loan Application -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Loan Application</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <ul class="list-disc list-inside space-y-1 text-gray-600 text-sm">
                        <li>Loan Amount</li>
                        <li>Purpose of Loan</li>
                        <li>Term (in months)</li>
                        <li>Repayment Frequency</li>
                        <li>Supporting Documents</li>
                        <li>Preferred Disbursement Method</li>
                        <li>Guarantors (if required)</li>
                    </ul>
                </div>
            </div>

            <!-- Savings Actions -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Savings Actions</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <ul class="list-disc list-inside space-y-1 text-gray-600 text-sm">
                        <li>Amount</li>
                        <li>Payment Method (Cash, Mobile Money, Bank Transfer)</li>
                        <li>Reference Number</li>
                        <li>Transaction Date</li>
                    </ul>
                </div>
            </div>

            <!-- Investment Subscription -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Investment Subscription</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <ul class="list-disc list-inside space-y-1 text-gray-600 text-sm">
                        <li>Plan Type (4-year or 6-year)</li>
                        <li>Principal Amount</li>
                        <li>Start Date</li>
                        <li>Payment Method</li>
                    </ul>
                </div>
            </div>

            <!-- Welfare Request -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Welfare Request</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <ul class="list-disc list-inside space-y-1 text-gray-600 text-sm">
                        <li>Benefit Type (Medical, Funeral, Educational, Other)</li>
                        <li>Amount Requested</li>
                        <li>Description</li>
                        <li>Supporting Documents</li>
                    </ul>
                </div>
            </div>

            <!-- Issue Ticket -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Issue Ticket</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <ul class="list-disc list-inside space-y-1 text-gray-600 text-sm">
                        <li>Category</li>
                        <li>Priority Level</li>
                        <li>Description</li>
                        <li>Attachments</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile App Sections -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-[#015425] mb-4">Mobile App Sections</h2>
        
        <div class="grid md:grid-cols-2 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">🏠 Home</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• KPIs (Loan Outstanding, Savings Balance, Next Due)</li>
                    <li>• Alerts and Notifications</li>
                    <li>• Quick Actions</li>
                </ul>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">💰 Loans</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Loan List</li>
                    <li>• Loan Details</li>
                    <li>• Repayment Schedule</li>
                    <li>• Pay Installment</li>
                </ul>
            </div>

            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">💳 Savings</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Account List</li>
                    <li>• Deposit/Withdraw</li>
                    <li>• Statements</li>
                </ul>
            </div>

            <div class="bg-pink-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">📈 Investments</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Investment List</li>
                    <li>• Investment Details</li>
                    <li>• Maturity Timeline</li>
                </ul>
            </div>

            <div class="bg-red-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">🤝 Welfare</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Contributions/Benefits</li>
                    <li>• Request Flow</li>
                </ul>
            </div>

            <div class="bg-indigo-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">💬 Support</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Create/View Issues</li>
                    <li>• Chat/Comments</li>
                </ul>
            </div>

            <div class="bg-teal-50 p-4 rounded-lg md:col-span-2">
                <h3 class="font-semibold text-gray-800 mb-2">👤 Profile</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• KYC Information</li>
                    <li>• Documents</li>
                    <li>• Security Settings</li>
                    <li>• Language Preferences</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- API Integration -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-[#015425] mb-4">API Integration for Mobile App</h2>
        
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Authentication</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Login with email/password to receive session token:</p>
                    <code class="text-xs bg-gray-800 text-green-400 p-2 rounded block">POST /api/mobile/v1/auth/login</code>
                    <p class="text-xs text-gray-500 mt-2">Handles 401/419 errors gracefully. Supports remember-me option.</p>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Endpoints Available</h3>
                <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                    <div class="text-sm">
                        <strong class="text-gray-800">Dashboard:</strong>
                        <code class="text-xs bg-gray-800 text-green-400 p-1 rounded ml-2">GET /api/mobile/v1/dashboard</code>
                    </div>
                    <div class="text-sm">
                        <strong class="text-gray-800">Loans:</strong>
                        <code class="text-xs bg-gray-800 text-green-400 p-1 rounded ml-2">GET /api/mobile/v1/loans</code>
                    </div>
                    <div class="text-sm">
                        <strong class="text-gray-800">Savings:</strong>
                        <code class="text-xs bg-gray-800 text-green-400 p-1 rounded ml-2">GET /api/mobile/v1/savings</code>
                    </div>
                    <div class="text-sm">
                        <strong class="text-gray-800">Investments:</strong>
                        <code class="text-xs bg-gray-800 text-green-400 p-1 rounded ml-2">GET /api/mobile/v1/investments</code>
                    </div>
                    <div class="text-sm">
                        <strong class="text-gray-800">Welfare:</strong>
                        <code class="text-xs bg-gray-800 text-green-400 p-1 rounded ml-2">GET /api/mobile/v1/welfare</code>
                    </div>
                    <div class="text-sm">
                        <strong class="text-gray-800">Issues:</strong>
                        <code class="text-xs bg-gray-800 text-green-400 p-1 rounded ml-2">GET /api/mobile/v1/issues</code>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">File Uploads</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Upload endpoints for IDs, slips, supporting docs using multipart form data:</p>
                    <ul class="text-xs text-gray-600 space-y-1 list-disc list-inside">
                        <li><code class="bg-gray-800 text-green-400 p-1 rounded">POST /api/mobile/v1/upload/kyc</code></li>
                        <li><code class="bg-gray-800 text-green-400 p-1 rounded">POST /api/mobile/v1/upload/loan-document</code></li>
                        <li><code class="bg-gray-800 text-green-400 p-1 rounded">POST /api/mobile/v1/upload/welfare-document</code></li>
                    </ul>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Notifications</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Register device token for push notifications:</p>
                    <code class="text-xs bg-gray-800 text-green-400 p-2 rounded block">POST /api/mobile/v1/notifications/register-device</code>
                    <p class="text-xs text-gray-500 mt-2">Supports opt-in/opt-out preferences.</p>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Localization</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Pass language preference in header or parameter. Persists on user profile.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-xl font-bold mb-4">Quick Links</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <a href="{{ route('member.dashboard') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 transition">
                <h3 class="font-semibold mb-1">Dashboard</h3>
                <p class="text-sm text-white text-opacity-90">View your financial overview</p>
            </a>
            <a href="{{ route('member.loans.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 transition">
                <h3 class="font-semibold mb-1">My Loans</h3>
                <p class="text-sm text-white text-opacity-90">Manage your loans</p>
            </a>
            <a href="{{ route('member.savings.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 transition">
                <h3 class="font-semibold mb-1">My Savings</h3>
                <p class="text-sm text-white text-opacity-90">View savings accounts</p>
            </a>
            <a href="{{ route('member.profile.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 transition">
                <h3 class="font-semibold mb-1">My Profile</h3>
                <p class="text-sm text-white text-opacity-90">Update your information</p>
            </a>
        </div>
    </div>
</div>
@endsection

