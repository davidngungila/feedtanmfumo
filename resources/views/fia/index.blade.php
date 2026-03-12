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
                            Admin Uploaded Payments
                        </button>
                        <button onclick="showTab('verified')" id="verified-tab" class="py-4 px-6 text-sm font-medium border-b-3 border-transparent hover:text-green-600 hover:border-green-600 transition-colors duration-200">
                            <i class="fas fa-check-circle mr-2"></i>
                            Member Verified Payments
                        </button>
                    </nav>
                </div>

                <!-- Verification Form Tab -->
                <div id="verification-content" class="p-6 fade-in">
                    <form id="verification-form" class="space-y-6">
                        <!-- Membership Code Input -->
                        <div class="max-w-md mx-auto">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">Enter Membership Code</h3>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Membership Code *</label>
                                <div class="flex gap-3">
                                    <input type="text" id="membership-code" name="membership_code" required
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-lg"
                                        placeholder="Enter your membership code">
                                    <button type="button" onclick="lookupMembership()"
                                        class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                                        <i class="fas fa-search mr-2"></i>
                                        Lookup
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Enter your membership code to view payment details</p>
                            </div>
                        </div>

                        <!-- Payment Details (Hidden initially) -->
                        <div id="payment-details" class="hidden">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Details</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Member Name:</p>
                                        <p class="font-semibold" id="member-name">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Membership Code:</p>
                                        <p class="font-semibold" id="member-code">-</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Breakdown -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                                <h4 class="font-semibold text-gray-800 mb-4">Payment Breakdown</h4>
                                <div id="payment-breakdown" class="space-y-3">
                                    <!-- Payment items will be loaded here -->
                                </div>
                                <div class="border-t pt-3 mt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold text-lg">Total Amount:</span>
                                        <span class="font-bold text-xl text-green-600" id="total-amount">TSh 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Verification Form -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="font-semibold text-gray-800 mb-4">Verify Payment</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                                <input type="tel" id="phone" name="phone" required
                                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                    placeholder="712 345 678">
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Format: 712 345 678</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                        <input type="email" id="email" name="email" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                            placeholder="your.email@example.com">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reference *</label>
                                        <input type="text" id="payment-reference" name="payment_reference" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                            placeholder="Enter payment reference number">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                                        <input type="date" id="payment-date" name="payment_date" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                                        <select id="payment-method" name="payment_method" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                            <option value="">Select payment method</option>
                                            <option value="mobile_money">Mobile Money</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="cash">Cash</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                        <textarea id="description" name="description" rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                            placeholder="Enter payment description (optional)"></textarea>
                                    </div>
                                </div>

                                <!-- Hidden fields -->
                                <input type="hidden" id="amount" name="amount">
                                <input type="hidden" id="member-id" name="member_id">

                                <!-- Submit Button -->
                                <div class="flex justify-center mt-6">
                                    <button type="submit" 
                                        class="bg-green-600 text-white px-8 py-3 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Verify Payment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Admin Uploaded Payments Tab -->
                <div id="payments-content" class="p-6 hidden">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">All Payments Uploaded by Admin</h3>
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-gray-600">Payment records uploaded and managed by administrators</p>
                            <button onclick="importPayments()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                                <i class="fas fa-upload mr-2"></i>Import Payments
                            </button>
                        </div>
                    </div>
                    <div id="payments-loading" class="text-center py-8 hidden">
                        <i class="fas fa-spinner fa-spin text-3xl text-green-600"></i>
                        <p class="mt-2 text-gray-600">Loading payments data...</p>
                    </div>
                    <div id="payments-data" class="hidden">
                        <!-- Payments will be loaded here via JavaScript -->
                    </div>
                </div>

                <!-- Member Verified Payments Tab -->
                <div id="verified-content" class="p-6 hidden">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Verified Payments by Members</h3>
                        <p class="text-gray-600">Payment verifications submitted and confirmed by members</p>
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

        // Lookup membership code
        async function lookupMembership() {
            const membershipCode = document.getElementById('membership-code').value.trim();
            
            if (!membershipCode) {
                alert('Please enter a membership code');
                return;
            }

            // Show loading
            const lookupBtn = event.target;
            lookupBtn.disabled = true;
            lookupBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Looking up...';

            try {
                const response = await fetch(`/fia/lookup-membership/${membershipCode}`);
                const result = await response.json();

                if (result.status === 'success') {
                    displayPaymentDetails(result.data);
                } else {
                    alert('Membership code not found: ' + (result.message || 'Please check your membership code'));
                }
            } catch (error) {
                console.error('Lookup error:', error);
                alert('Error looking up membership code. Please try again.');
            } finally {
                // Reset button
                lookupBtn.disabled = false;
                lookupBtn.innerHTML = '<i class="fas fa-search mr-2"></i>Lookup';
            }
        }

        // Display payment details after lookup
        function displayPaymentDetails(memberData) {
            // Show payment details section
            document.getElementById('payment-details').classList.remove('hidden');
            
            // Set member information
            document.getElementById('member-name').textContent = memberData.name;
            document.getElementById('member-code').textContent = memberData.membership_code;
            document.getElementById('member-id').value = memberData.id;
            
            // Set contact information if available
            if (memberData.phone) {
                document.getElementById('phone').value = memberData.phone.replace('+255', '');
            }
            if (memberData.email) {
                document.getElementById('email').value = memberData.email;
            }
            
            // Display payment breakdown
            const breakdownContainer = document.getElementById('payment-breakdown');
            breakdownContainer.innerHTML = '';
            
            let totalAmount = 0;
            const payments = memberData.payments || [];
            
            if (payments.length === 0) {
                breakdownContainer.innerHTML = '<p class="text-gray-500">No payment records found for this member.</p>';
            } else {
                payments.forEach(payment => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex justify-between items-center py-2 border-b';
                    itemDiv.innerHTML = `
                        <div>
                            <p class="font-medium">${payment.description}</p>
                            <p class="text-sm text-gray-600">${payment.due_date}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">TSh ${payment.amount.toLocaleString()}</p>
                            <p class="text-sm ${payment.status === 'paid' ? 'text-green-600' : 'text-orange-600'}">${payment.status}</p>
                        </div>
                    `;
                    breakdownContainer.appendChild(itemDiv);
                    
                    if (payment.status !== 'paid') {
                        totalAmount += payment.amount;
                    }
                });
            }
            
            // Update total amount
            document.getElementById('total-amount').textContent = `TSh ${totalAmount.toLocaleString()}`;
            document.getElementById('amount').value = totalAmount;
            
            // Scroll to payment details
            document.getElementById('payment-details').scrollIntoView({ behavior: 'smooth' });
        }

        // Import payments (admin function)
        function importPayments() {
            // Create file input
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = '.xlsx,.xls,.csv';
            fileInput.onchange = function(e) {
                const file = e.target.files[0];
                if (file) {
                    uploadPaymentsFile(file);
                }
            };
            fileInput.click();
        }

        // Upload payments file
        async function uploadPaymentsFile(file) {
            const formData = new FormData();
            formData.append('payments_file', file);
            
            // Show loading
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50';
            loadingDiv.innerHTML = `
                <div class="bg-white rounded-lg p-8 text-center">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
                    <p class="text-lg font-medium text-gray-800">Uploading payments file...</p>
                </div>
            `;
            document.body.appendChild(loadingDiv);
            
            try {
                const response = await fetch('/fia/import-payments', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    alert('Payments uploaded successfully!');
                    loadPayments(); // Refresh the payments list
                } else {
                    alert('Error uploading payments: ' + (result.message || 'Please try again'));
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Error uploading payments file. Please try again.');
            } finally {
                // Remove loading
                document.body.removeChild(loadingDiv);
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S/N</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gawio la FIA</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FIA iliyokomaa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumla</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Malipo ya vipande yailiyokuwa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">LOAN</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kiasi baki</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${payments.map((payment, index) => `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.fia_gawio || '-'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.fia_iliyokomaa || '-'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TSh ${payment.jumla ? payment.jumla.toLocaleString() : '0'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TSh ${payment.malipo_vipande ? payment.malipo_vipande.toLocaleString() : '0'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TSh ${payment.loan ? payment.loan.toLocaleString() : '0'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TSh ${payment.kiasi_baki ? payment.kiasi_baki.toLocaleString() : '0'}</td>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S/N</th>
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
                            ${payments.map((payment, index) => `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.phone}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.email}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.payment_reference}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TSh ${payment.amount.toLocaleString()}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.payment_date}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.payment_method_label}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">${payment.verified_at ? new Date(payment.verified_at).toLocaleDateString() : '-'}</td>
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
                    document.getElementById('payment-details').classList.add('hidden');
                    
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
