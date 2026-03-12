<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIA Payments Verification - Feedtan CMG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .tab-active {
            border-bottom: 3px solid #015425;
            color: #015425;
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .loading-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-green-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-green-600 font-bold text-lg">FC</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">FEEDTAN CMG</h1>
                        <p class="text-green-100 text-sm">Financial Intelligence Agency Payments</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Page Title -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">FIA Payments Verification</h2>
                <p class="text-gray-600">Submit your payment details for verification and processing</p>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button onclick="showTab('verification')" id="verification-tab" class="tab-active py-4 px-6 text-sm font-medium border-b-3 border-transparent hover:text-green-600 hover:border-green-600 transition-colors duration-200">
                            <i class="fas fa-file-alt mr-2"></i>
                            Submit Verification
                        </button>
                        <button onclick="showTab('payments')" id="payments-tab" class="py-4 px-6 text-sm font-medium border-b-3 border-transparent hover:text-green-600 hover:border-green-600 transition-colors duration-200">
                            <i class="fas fa-list mr-2"></i>
                            All Payments
                        </button>
                        <button onclick="showTab('verified')" id="verified-tab" class="py-4 px-6 text-sm font-medium border-b-3 border-transparent hover:text-green-600 hover:border-green-600 transition-colors duration-200">
                            <i class="fas fa-check-circle mr-2"></i>
                            Verified Payments
                        </button>
                    </nav>
                </div>

                <!-- Verification Form Tab -->
                <div id="verification-content" class="p-6 fade-in">
                    <form id="verification-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" name="name" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="Enter your full name">
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
                                            <input type="tel" name="phone" required
                                                class="flex-1 px-4 py-3 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                placeholder="712 345 678">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Format: 712 345 678</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" name="email" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="your.email@example.com">
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Information</h3>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reference *</label>
                                    <input type="text" name="payment_reference" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="Enter payment reference number">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount (TZS) *</label>
                                    <input type="number" name="amount" required min="1000" step="0.01"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="Enter amount">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                                    <input type="date" name="payment_date" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                                    <select name="payment_method" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">Select payment method</option>
                                        <option value="mobile_money">Mobile Money</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="cash">Cash</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea name="description" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="Enter payment description (optional)"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center">
                            <button type="submit" 
                                class="bg-green-600 text-white px-8 py-3 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Submit Verification
                            </button>
                        </div>
                    </form>
                </div>

                <!-- All Payments Tab -->
                <div id="payments-content" class="p-6 hidden">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">All FIA Payments</h3>
                    </div>
                    <div id="payments-loading" class="text-center py-8 hidden">
                        <i class="fas fa-spinner fa-spin text-3xl text-green-600"></i>
                        <p class="mt-2 text-gray-600">Loading payments data...</p>
                    </div>
                    <div id="payments-data" class="hidden">
                        <!-- Payments will be loaded here via JavaScript -->
                    </div>
                </div>

                <!-- Verified Payments Tab -->
                <div id="verified-content" class="p-6 hidden">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Verified FIA Payments</h3>
                    </div>
                    <div id="verified-loading" class="text-center py-8 hidden">
                        <i class="fas fa-spinner fa-spin text-3xl text-green-600"></i>
                        <p class="mt-2 text-gray-600">Loading verified payments...</p>
                    </div>
                    <div id="verified-data" class="hidden">
                        <!-- Verified payments will be loaded here via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 fade-in">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Verification Submitted!</h3>
                <p class="text-gray-600 mb-6">Your FIA payment verification has been submitted successfully. We will process it and notify you of the outcome.</p>
                <button onclick="closeModal('success-modal')" 
                    class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Got it
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 loading-overlay hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 text-center">
            <i class="fas fa-spinner fa-spin text-4xl text-green-600 mb-4"></i>
            <p class="text-lg font-medium text-gray-800">Processing...</p>
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
        // Tab switching
        function showTab(tabName) {
            // Hide all content
            document.getElementById('verification-content').classList.add('hidden');
            document.getElementById('payments-content').classList.add('hidden');
            document.getElementById('verified-content').classList.add('hidden');
            
            // Remove active class from all tabs
            document.getElementById('verification-tab').classList.remove('tab-active');
            document.getElementById('payments-tab').classList.remove('tab-active');
            document.getElementById('verified-tab').classList.remove('tab-active');
            
            // Show selected content and activate tab
            document.getElementById(tabName + '-content').classList.remove('hidden');
            document.getElementById(tabName + '-tab').classList.add('tab-active');
            
            // Load data for payments tabs
            if (tabName === 'payments') {
                loadPayments();
            } else if (tabName === 'verified') {
                loadVerifiedPayments();
            }
        }

        // Load all payments
        async function loadPayments() {
            document.getElementById('payments-loading').classList.remove('hidden');
            document.getElementById('payments-data').classList.add('hidden');
            
            try {
                const response = await fetch('/fia/payments');
                const result = await response.json();
                
                if (result.status === 'success') {
                    displayPayments(result.data);
                } else {
                    document.getElementById('payments-loading').innerHTML = 
                        '<p class="text-red-600">Failed to load payments data</p>';
                }
            } catch (error) {
                console.error('Error loading payments:', error);
                document.getElementById('payments-loading').innerHTML = 
                    '<p class="text-red-600">Error loading payments data</p>';
            }
        }

        // Load verified payments
        async function loadVerifiedPayments() {
            document.getElementById('verified-loading').classList.remove('hidden');
            document.getElementById('verified-data').classList.add('hidden');
            
            try {
                const response = await fetch('/fia/verified-payments');
                const result = await response.json();
                
                if (result.status === 'success') {
                    displayVerifiedPayments(result.data);
                } else {
                    document.getElementById('verified-loading').innerHTML = 
                        '<p class="text-red-600">Failed to load verified payments</p>';
                }
            } catch (error) {
                console.error('Error loading verified payments:', error);
                document.getElementById('verified-loading').innerHTML = 
                    '<p class="text-red-600">Error loading verified payments data</p>';
            }
        }

        // Display payments in table
        function displayPayments(payments) {
            const html = `
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${payments.map(payment => `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.phone}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.email}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.payment_reference}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TSh ${payment.amount.toLocaleString()}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.payment_date}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.payment_method_label}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">${payment.status_label}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            
            document.getElementById('payments-loading').classList.add('hidden');
            document.getElementById('payments-data').innerHTML = html;
            document.getElementById('payments-data').classList.remove('hidden');
        }

        // Display verified payments in table
        function displayVerifiedPayments(payments) {
            const html = `
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${payments.map(payment => `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.phone}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.email}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.payment_reference}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TSh ${payment.amount.toLocaleString()}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.payment_date}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.payment_method_label}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">Verified</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            
            document.getElementById('verified-loading').classList.add('hidden');
            document.getElementById('verified-data').innerHTML = html;
            document.getElementById('verified-data').classList.remove('hidden');
        }

        // Close modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
        }

        // Handle form submission
        document.getElementById('verification-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Show loading
            document.getElementById('loading-overlay').classList.remove('hidden');
            document.getElementById('loading-overlay').classList.add('flex');
            
            try {
                const response = await fetch('/fia/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    // Reset form
                    this.reset();
                    
                    // Hide loading and show success
                    document.getElementById('loading-overlay').classList.add('hidden');
                    document.getElementById('loading-overlay').classList.remove('flex');
                    document.getElementById('success-modal').classList.remove('hidden');
                    document.getElementById('success-modal').classList.add('flex');
                } else {
                    // Hide loading and show error
                    document.getElementById('loading-overlay').classList.add('hidden');
                    document.getElementById('loading-overlay').classList.remove('flex');
                    alert('Error: ' + (result.message || 'Failed to submit verification'));
                }
            } catch (error) {
                console.error('Submission error:', error);
                document.getElementById('loading-overlay').classList.add('hidden');
                document.getElementById('loading-overlay').classList.remove('flex');
                alert('Network error. Please try again.');
            }
        });
    </script>
</body>
</html>
