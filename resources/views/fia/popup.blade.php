<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIA Payment Confirmation - Feedtan CMG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #015425 0%, #0a7d3a 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <!-- Main Container -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="glass-effect rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            
            <!-- Header -->
            <div class="gradient-bg text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center">
                            <span class="text-green-600 font-bold text-xl">FC</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">FIA Payment Confirmation</h1>
                            <p class="text-green-100">Submit your payment details for verification</p>
                        </div>
                    </div>
                    <button onclick="window.close()" class="text-white hover:text-green-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-8">
                <!-- Step 1: Member Lookup -->
                <div id="step1" class="slide-in">
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 pulse-animation">
                            <i class="fas fa-search text-green-600 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Find Your Account</h2>
                        <p class="text-gray-600">Enter your membership code to continue</p>
                    </div>

                    <div class="max-w-md mx-auto">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Membership Code</label>
                        <div class="flex gap-3">
                            <input type="text" id="membership-code" name="membership_code" required
                                class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-lg font-medium"
                                placeholder="e.g., PRM1, FND7">
                            <button type="button" onclick="lookupMembership()" id="lookup-btn"
                                class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                                <i class="fas fa-search mr-2"></i>
                                <span id="lookup-btn-text">Lookup</span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Enter your membership code as shown on your records</p>
                    </div>

                    <!-- Loading State -->
                    <div id="lookup-loading" class="hidden text-center mt-6">
                        <div class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                            <i class="fas fa-spinner fa-spin text-green-600 mr-2"></i>
                            <span class="text-green-700 font-medium">Looking up your account...</span>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div id="lookup-error" class="hidden text-center mt-6">
                        <div class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                            <span class="text-red-700 font-medium" id="error-message">Membership code not found</span>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Payment Details -->
                <div id="step2" class="hidden">
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user text-blue-600 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Your Payment Details</h2>
                        <p class="text-gray-600">Review your payment information below</p>
                    </div>

                    <!-- Member Info Card -->
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 mb-6 border border-green-200">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-md">
                                <span class="text-green-600 font-bold text-xl" id="member-initial">-</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800" id="member-name">-</h3>
                                <p class="text-gray-600">ID: <span id="member-code" class="font-mono font-semibold">-</span></p>
                            </div>
                            <button onclick="resetForm()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Payment Breakdown -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                            Payment Breakdown
                        </h4>
                        <div id="payment-breakdown" class="space-y-3">
                            <!-- Payment items will be loaded here -->
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-800">Total Amount:</span>
                                <span class="text-2xl font-bold text-green-600" id="total-amount">TSh 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Form -->
                    <form id="confirmation-form" class="space-y-6">
                        <input type="hidden" id="member-id" name="member_id">
                        <input type="hidden" id="amount-to-pay" name="amount_to_pay">
                        
                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    Email Address
                                </label>
                                <input type="email" id="email" name="email" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="your.email@example.com">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    Phone Number
                                </label>
                                <input type="tel" id="phone" name="phone" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="255 XXX XXX XXX">
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-credit-card text-gray-400 mr-1"></i>
                                Payment Method
                            </label>
                            <select id="payment-method" name="payment_method" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Select payment method</option>
                                <option value="mobile-money">Mobile Money</option>
                                <option value="bank-transfer">Bank Transfer</option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-comment text-gray-400 mr-1"></i>
                                Additional Notes (Optional)
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Any additional information or special instructions..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center">
                            <button type="submit" id="submit-btn"
                                class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-4 rounded-xl hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 font-semibold text-lg shadow-lg hover:shadow-xl">
                                <i class="fas fa-check-circle mr-2"></i>
                                Submit Payment Confirmation
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 3: Success -->
                <div id="step3" class="hidden text-center">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 pulse-animation">
                        <i class="fas fa-check text-green-600 text-4xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Confirmation Submitted!</h2>
                    <p class="text-lg text-gray-600 mb-8">Your payment confirmation has been successfully submitted and is being processed.</p>
                    
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8">
                        <h4 class="font-semibold text-green-800 mb-2">What happens next?</h4>
                        <ul class="text-left text-green-700 space-y-2">
                            <li><i class="fas fa-check-circle text-green-600 mr-2"></i>Your confirmation will be reviewed by our team</li>
                            <li><i class="fas fa-check-circle text-green-600 mr-2"></i>You will receive a confirmation email shortly</li>
                            <li><i class="fas fa-check-circle text-green-600 mr-2"></i>Payment verification typically takes 1-2 business days</li>
                        </ul>
                    </div>
                    
                    <div class="flex gap-4 justify-center">
                        <button onclick="resetForm()" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-300 transition-colors font-semibold">
                            <i class="fas fa-plus mr-2"></i>
                            Submit Another Confirmation
                        </button>
                        <button onclick="window.close()" class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-colors font-semibold">
                            <i class="fas fa-times mr-2"></i>
                            Close Window
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 text-center">
            <i class="fas fa-spinner fa-spin text-4xl text-green-600 mb-4"></i>
            <p class="text-lg font-medium text-gray-800">Processing your confirmation...</p>
        </div>
    </div>

    <script>
        // Step management
        function showStep(stepNumber) {
            document.querySelectorAll('[id^="step"]').forEach(step => {
                step.classList.add('hidden');
            });
            document.getElementById(`step${stepNumber}`).classList.remove('hidden');
        }

        // Reset form
        function resetForm() {
            document.getElementById('confirmation-form').reset();
            document.getElementById('membership-code').value = '';
            document.getElementById('payment-details').classList.add('hidden');
            document.getElementById('lookup-error').classList.add('hidden');
            showStep(1);
        }

        // Lookup membership
        async function lookupMembership() {
            const membershipCode = document.getElementById('membership-code').value.trim();
            
            if (!membershipCode) {
                showError('Please enter a membership code');
                return;
            }

            const lookupBtn = document.getElementById('lookup-btn');
            const btnText = document.getElementById('lookup-btn-text');
            const originalText = btnText.textContent;
            
            // Show loading
            document.getElementById('lookup-loading').classList.remove('hidden');
            document.getElementById('lookup-error').classList.add('hidden');
            lookupBtn.disabled = true;
            btnText.textContent = 'Searching...';

            try {
                const response = await fetch(`/fia/lookup-membership/${membershipCode}`);
                const data = await response.json();

                if (data.success) {
                    displayPaymentDetails(data.member);
                    showStep(2);
                } else {
                    showError(data.message || 'Membership code not found');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Error looking up membership. Please try again.');
            } finally {
                document.getElementById('lookup-loading').classList.add('hidden');
                lookupBtn.disabled = false;
                btnText.textContent = originalText;
            }
        }

        // Show error
        function showError(message) {
            document.getElementById('error-message').textContent = message;
            document.getElementById('lookup-error').classList.remove('hidden');
        }

        // Display payment details
        function displayPaymentDetails(memberData) {
            // Set member info
            document.getElementById('member-name').textContent = memberData.member_name;
            document.getElementById('member-code').textContent = memberData.member_id;
            document.getElementById('member-id').value = memberData.member_id;
            document.getElementById('member-initial').textContent = memberData.member_name.charAt(0).toUpperCase();
            
            // Set email if available
            if (memberData.member_email) {
                document.getElementById('email').value = memberData.member_email;
            }

            // Display payment breakdown
            const breakdownContainer = document.getElementById('payment-breakdown');
            breakdownContainer.innerHTML = '';
            
            let totalAmount = 0;
            const paymentItems = [
                { description: 'Gawio la FIA', amount: memberData.gawio_la_fia || 0 },
                { description: 'FIA Ilivyo Koma', amount: memberData.fia_iliyokomaa || 0 },
                { description: 'Jumla', amount: memberData.jumla || 0 },
                { description: 'Malipo ya Vipande', amount: memberData.malipo_ya_vipande_yaliyokuwa_yamepelea || 0 },
                { description: 'Loan', amount: memberData.loan || 0 },
                { description: 'Kiasi Baki', amount: memberData.kiasi_baki || 0 }
            ];
            
            paymentItems.forEach(payment => {
                if (payment.amount > 0) {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex justify-between items-center py-2 border-b border-gray-100';
                    itemDiv.innerHTML = `
                        <div>
                            <p class="font-medium text-gray-800">${payment.description}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">TSh ${Number(payment.amount).toLocaleString()}</p>
                        </div>
                    `;
                    breakdownContainer.appendChild(itemDiv);
                    totalAmount += Number(payment.amount);
                }
            });
            
            // Update total amount
            document.getElementById('total-amount').textContent = `TSh ${totalAmount.toLocaleString()}`;
            document.getElementById('amount-to-pay').value = totalAmount;
        }

        // Handle form submission
        document.getElementById('confirmation-form').addEventListener('submit', async function(e) {
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
                    showStep(3);
                } else {
                    alert('Error: ' + (result.message || 'Failed to submit confirmation'));
                }
            } catch (error) {
                console.error('Submission error:', error);
                alert('Network error. Please try again.');
            } finally {
                document.getElementById('loading-overlay').classList.add('hidden');
                document.getElementById('loading-overlay').classList.remove('flex');
            }
        });

        // Handle Enter key in membership code input
        document.getElementById('membership-code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                lookupMembership();
            }
        });
    </script>
</body>
</html>
