<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guarantor Assessment - FeedTan CMG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .step-active {
            background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
            color: white;
        }
        .step-completed {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .step-pending {
            background: #f3f4f6;
            color: #6b7280;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .loading-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }
        .member-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-left: 4px solid #015425;
        }
        .verified-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-green-600 text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-green-600 font-bold text-lg">FC</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">FEEDTAN CMG</h1>
                        <p class="text-green-100 text-sm">Guarantor Assessment System</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">Loan Reference: {{ $loan->ulid }}</span>
                    <span class="text-sm">Borrower: {{ $loan->user->name }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Progress Steps -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div id="step-1" class="step-active flex items-center justify-center w-10 h-10 rounded-full font-semibold transition-all duration-300">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 rounded" id="progress-1"></div>
                    <div id="step-2" class="step-pending flex items-center justify-center w-10 h-10 rounded-full font-semibold transition-all duration-300">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 rounded" id="progress-2"></div>
                    <div id="step-3" class="step-pending flex items-center justify-center w-10 h-10 rounded-full font-semibold transition-all duration-300">
                        <i class="fas fa-file-contract text-sm"></i>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    Step <span id="current-step-text">1</span> of 3
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Step 1: Member Code Verification -->
            <div id="step-1-content" class="fade-in">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Step 1: Member Verification</h2>
                        <p class="text-gray-600">Enter your member code to verify your identity and continue with the assessment</p>
                    </div>

                    <div class="max-w-md mx-auto">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Member Code *</label>
                            <div class="flex gap-3">
                                <input type="text" id="member-code" 
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-lg"
                                    placeholder="Enter your member code">
                                <button type="button" onclick="verifyMember()" 
                                    class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                                    <i class="fas fa-search mr-2"></i>
                                    Verify
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Enter your unique member identification code</p>
                        </div>

                        <!-- Member Verification Result -->
                        <div id="member-verification-result" class="hidden">
                            <!-- Member details will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Personal Information -->
            <div id="step-2-content" class="hidden fade-in">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Step 2: Personal Information</h2>
                        <p class="text-gray-600">Please provide your personal and contact information</p>
                    </div>

                    <form id="personal-info-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" id="full-name" readonly
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Member Code *</label>
                                <input type="text" id="member-code-display" readonly
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <div class="flex">
                                    <div class="flex">
                                        <button type="button" class="flex items-center gap-2 px-4 py-3 bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg">
                                            <span class="w-6 h-4">
                                                <svg viewBox="0 85.333 512 341.333" class="w-full h-full">
                                                    <title>TZ</title>
                                                    <path fill="#338AF3" d="M0 85.337h512v341.326H0z"></path>
                                                    <path fill="#6DA544" d="M0 426.663V85.337h512z"></path>
                                                    <path fill="#FFDA44" d="M512 152.222V85.337H411.67L0 359.778v66.885h100.33z"></path>
                                                    <path d="M512 85.337v40.125L60.193 426.663H0v-40.125L451.807 85.337z"></path>
                                                </svg>
                                            </span>
                                            <span class="text-sm font-medium">+255</span>
                                        </button>
                                        <input type="tel" id="phone-number" required
                                            class="flex-1 px-4 py-3 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                            placeholder="712 345 678">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Format: 712 345 678</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email-address" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="your.email@example.com">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Relationship to Borrower *</label>
                                <select id="relationship" required onchange="toggleOtherField('relationship', 'relationship-other')"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">-- Select Relationship --</option>
                                    <option value="spouse">Spouse</option>
                                    <option value="parent">Parent</option>
                                    <option value="sibling">Sibling</option>
                                    <option value="child">Child</option>
                                    <option value="relative">Relative</option>
                                    <option value="friend">Friend</option>
                                    <option value="colleague">Colleague</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div id="relationship-other" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Please specify relationship *</label>
                                <input type="text" id="relationship-other-text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Specify your relationship">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Address *</label>
                                <textarea id="address" required rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Enter your current residential address"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Occupation *</label>
                                <input type="text" id="occupation" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Your current occupation">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Income (TZS) *</label>
                                <input type="number" id="monthly-income" required min="0" step="1000"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Enter your monthly income">
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="previousStep()" 
                                class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Previous
                            </button>
                            <button type="button" onclick="nextStep()" 
                                class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium">
                                Next
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Step 3: Assessment Questions -->
            <div id="step-3-content" class="hidden fade-in">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Step 3: Assessment Questions</h2>
                        <p class="text-gray-600">Please answer the following questions to complete your guarantor assessment</p>
                    </div>

                    <form id="assessment-form" class="space-y-6">
                        <!-- Loan Purpose -->
                        <div class="border rounded-lg p-6">
                            <h3 class="font-semibold text-gray-800 mb-4">Loan Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Do you understand the purpose of this loan? *</label>
                                    <select id="loan-purpose" required onchange="toggleOtherField('loan-purpose', 'loan-purpose-other')"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">-- Select --</option>
                                        <option value="business">Business Development</option>
                                        <option value="education">Education</option>
                                        <option value="medical">Medical Expenses</option>
                                        <option value="home">Home Improvement</option>
                                        <option value="agriculture">Agriculture</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div id="loan-purpose-other" class="hidden">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Please specify loan purpose *</label>
                                    <input type="text" id="loan-purpose-other-text"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="Specify loan purpose">
                                </div>
                            </div>
                        </div>

                        <!-- Financial Assessment -->
                        <div class="border rounded-lg p-6">
                            <h3 class="font-semibold text-gray-800 mb-4">Financial Assessment</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Have you reviewed the borrower's loan repayment history? *</label>
                                    <select id="repayment-history" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">-- Select --</option>
                                        <option value="yes">Yes, I have reviewed</option>
                                        <option value="no">No, I have not reviewed</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Do you have any existing debts or financial obligations? *</label>
                                    <select id="existing-debts" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">-- Select --</option>
                                        <option value="none">No existing debts</option>
                                        <option value="minor">Minor debts (under TZS 100,000)</option>
                                        <option value="moderate">Moderate debts (TZS 100,000 - 500,000)</option>
                                        <option value="significant">Significant debts (over TZS 500,000)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Do you have sufficient savings to cover the guarantee if needed? *</label>
                                    <select id="sufficient-savings" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">-- Select --</option>
                                        <option value="yes">Yes, I have sufficient savings</option>
                                        <option value="partial">Partially sufficient</option>
                                        <option value="no">No, I don't have sufficient savings</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Understanding and Agreement -->
                        <div class="border rounded-lg p-6">
                            <h3 class="font-semibold text-gray-800 mb-4">Understanding and Agreement</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Do you understand that you will be solely responsible if the borrower defaults? *</label>
                                    <select id="sole-responsibility" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">-- Select --</option>
                                        <option value="yes">Yes, I understand and accept</option>
                                        <option value="no">No, I don't understand</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Do you understand how the loan recovery process works? *</label>
                                    <select id="recovery-process" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">-- Select --</option>
                                        <option value="yes">Yes, I understand</option>
                                        <option value="no">No, I need clarification</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Are you providing this guarantee voluntarily without any pressure? *</label>
                                    <select id="voluntary-guarantee" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">-- Select --</option>
                                        <option value="yes">Yes, voluntarily</option>
                                        <option value="no">Under pressure</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Comments -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Comments (Optional)</label>
                            <textarea id="additional-comments" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Any additional information you would like to provide"></textarea>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="previousStep()" 
                                class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Previous
                            </button>
                            <button type="submit" 
                                class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium">
                                <i class="fas fa-check-circle mr-2"></i>
                                Submit Assessment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 loading-overlay hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 text-center">
            <i class="fas fa-spinner fa-spin text-4xl text-green-600 mb-4"></i>
            <p class="text-lg font-medium text-gray-800">Processing...</p>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 fade-in">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Assessment Submitted!</h3>
                <p class="text-gray-600 mb-6">Your guarantor assessment has been submitted successfully. We will review your application and contact you soon.</p>
                <button onclick="closeModal('success-modal')" 
                    class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Got it
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center">
                <div class="mb-2">
                    <span class="font-bold">FEEDTAN CMG</span>
                    <span class="text-gray-400">|</span>
                    <span class="text-sm">Feedtan Community Microfinance Group</span>
                </div>
                <p class="text-gray-400 text-sm">Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY</p>
            </div>
        </div>
    </footer>

    <script>
        // Global variables
        let currentStep = 1;
        let verifiedMember = null;

        // Step navigation
        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < 3) {
                    hideStep(currentStep);
                    currentStep++;
                    showStep(currentStep);
                    updateProgress();
                }
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                hideStep(currentStep);
                currentStep--;
                showStep(currentStep);
                updateProgress();
            }
        }

        function showStep(step) {
            document.getElementById(`step-${step}-content`).classList.remove('hidden');
            document.getElementById('current-step-text').textContent = step;
        }

        function hideStep(step) {
            document.getElementById(`step-${step}-content`).classList.add('hidden');
        }

        function updateProgress() {
            // Update step indicators
            for (let i = 1; i <= 3; i++) {
                const stepElement = document.getElementById(`step-${i}`);
                const progressElement = document.getElementById(`progress-${i}`);
                
                if (i < currentStep) {
                    stepElement.className = 'step-completed flex items-center justify-center w-10 h-10 rounded-full font-semibold transition-all duration-300';
                    stepElement.innerHTML = '<i class="fas fa-check text-sm"></i>';
                    progressElement.className = 'flex-1 h-1 bg-green-500 rounded';
                } else if (i === currentStep) {
                    stepElement.className = 'step-active flex items-center justify-center w-10 h-10 rounded-full font-semibold transition-all duration-300';
                    progressElement.className = 'flex-1 h-1 bg-green-500 rounded';
                } else {
                    stepElement.className = 'step-pending flex items-center justify-center w-10 h-10 rounded-full font-semibold transition-all duration-300';
                    stepElement.innerHTML = i;
                    progressElement.className = 'flex-1 h-1 bg-gray-200 rounded';
                }
            }
        }

        // Member verification
        async function verifyMember() {
            const memberCode = document.getElementById('member-code').value.trim();
            
            if (!memberCode) {
                alert('Please enter a member code');
                return;
            }

            showLoading(true);

            try {
                const response = await fetch(`/api/verify-member/${memberCode}`);
                const result = await response.json();

                const resultDiv = document.getElementById('member-verification-result');
                
                if (result.status === 'success') {
                    verifiedMember = result.data;
                    displayVerifiedMember(result.data);
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                                <div>
                                    <p class="font-semibold text-red-800">Verification Failed</p>
                                    <p class="text-red-600 text-sm">${result.message || 'Member code not found'}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    resultDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Verification error:', error);
                const resultDiv = document.getElementById('member-verification-result');
                resultDiv.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                            <div>
                                <p class="font-semibold text-red-800">Network Error</p>
                                <p class="text-red-600 text-sm">Unable to verify member. Please try again.</p>
                            </div>
                        </div>
                    </div>
                `;
                resultDiv.classList.remove('hidden');
            } finally {
                showLoading(false);
            }
        }

        function displayVerifiedMember(member) {
            const resultDiv = document.getElementById('member-verification-result');
            resultDiv.innerHTML = `
                <div class="member-card rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-user-check text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">${member.name}</h3>
                                <p class="text-sm text-gray-600">Member Code: ${member.membership_code}</p>
                            </div>
                        </div>
                        <div class="verified-badge px-3 py-1 rounded-full text-white text-sm">
                            <i class="fas fa-check-circle mr-1"></i>
                            Verified
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Status:</p>
                            <p class="font-medium">${member.membership_status || 'Active'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Member Since:</p>
                            <p class="font-medium">${member.created_at ? new Date(member.created_at).toLocaleDateString() : 'N/A'}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button onclick="proceedToStep2()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                            Continue to Step 2
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            `;
            resultDiv.classList.remove('hidden');
        }

        function proceedToStep2() {
            if (verifiedMember) {
                // Populate personal information form
                document.getElementById('full-name').value = verifiedMember.name;
                document.getElementById('member-code-display').value = verifiedMember.membership_code;
                document.getElementById('phone-number').value = verifiedMember.phone ? verifiedMember.phone.replace('+255', '') : '';
                document.getElementById('email-address').value = verifiedMember.email || '';
                
                // Move to step 2
                hideStep(1);
                currentStep = 2;
                showStep(2);
                updateProgress();
            }
        }

        // Form validation
        function validateCurrentStep() {
            if (currentStep === 1) {
                if (!verifiedMember) {
                    alert('Please verify your member code first');
                    return false;
                }
                return true;
            } else if (currentStep === 2) {
                const requiredFields = ['phone-number', 'email-address', 'relationship', 'address', 'occupation', 'monthly-income'];
                for (const field of requiredFields) {
                    const element = document.getElementById(field);
                    if (!element.value.trim()) {
                        alert('Please fill in all required fields');
                        element.focus();
                        return false;
                    }
                }
                
                // Validate relationship other field
                if (document.getElementById('relationship').value === 'other') {
                    const otherField = document.getElementById('relationship-other-text');
                    if (!otherField.value.trim()) {
                        alert('Please specify your relationship');
                        otherField.focus();
                        return false;
                    }
                }
                
                return true;
            }
            return true;
        }

        // Toggle other field
        function toggleOtherField(selectId, otherFieldId) {
            const select = document.getElementById(selectId);
            const otherField = document.getElementById(otherFieldId);
            
            if (select.value === 'other') {
                otherField.classList.remove('hidden');
            } else {
                otherField.classList.add('hidden');
                otherField.querySelector('input').value = '';
            }
        }

        // Form submission
        document.getElementById('assessment-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!validateCurrentStep()) {
                return;
            }

            showLoading(true);

            try {
                const formData = {
                    loan_ulid: '{{ $loan->ulid }}',
                    guarantor_id: verifiedMember.id,
                    member_code: verifiedMember.membership_code,
                    full_name: verifiedMember.name,
                    phone: document.getElementById('phone-number').value,
                    email: document.getElementById('email-address').value,
                    relationship: document.getElementById('relationship').value,
                    relationship_other: document.getElementById('relationship').value === 'other' ? document.getElementById('relationship-other-text').value : null,
                    address: document.getElementById('address').value,
                    occupation: document.getElementById('occupation').value,
                    monthly_income: document.getElementById('monthly-income').value,
                    loan_purpose: document.getElementById('loan-purpose').value,
                    loan_purpose_other: document.getElementById('loan-purpose').value === 'other' ? document.getElementById('loan-purpose-other-text').value : null,
                    repayment_history: document.getElementById('repayment-history').value,
                    existing_debts: document.getElementById('existing-debts').value,
                    sufficient_savings: document.getElementById('sufficient-savings').value,
                    sole_responsibility: document.getElementById('sole-responsibility').value,
                    recovery_process: document.getElementById('recovery-process').value,
                    voluntary_guarantee: document.getElementById('voluntary-guarantee').value,
                    additional_comments: document.getElementById('additional-comments').value
                };

                const response = await fetch('/guarantor-assessment/store/{{ $loan->ulid }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    document.getElementById('success-modal').classList.remove('hidden');
                    document.getElementById('success-modal').classList.add('flex');
                } else {
                    alert('Error: ' + (result.message || 'Failed to submit assessment'));
                }
            } catch (error) {
                console.error('Submission error:', error);
                alert('Network error. Please try again.');
            } finally {
                showLoading(false);
            }
        });

        // UI helpers
        function showLoading(show) {
            const overlay = document.getElementById('loading-overlay');
            if (show) {
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            } else {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
        }

        // Add enter key support for member code verification
        document.getElementById('member-code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                verifyMember();
            }
        });
    </script>
</body>
</html>
