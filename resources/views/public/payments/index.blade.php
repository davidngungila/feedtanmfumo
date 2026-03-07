<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FEEDTAN | {{ $merchantName }} - Secure Payment</title>
    <meta name="description" content="FEEDTAN — Secure payment powered by Snippe. Pay with M-Pesa, Tigo Pesa, Airtel Money, or card. Fast, reliable, and secure transactions.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .tabular-nums { font-variant-numeric: tabular-nums; }
        .payment-method-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .payment-method-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .payment-method-card.selected {
            border-color: #16a34a;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(22, 163, 74, 0); }
            100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
        }
        .loading-dots::after {
            content: '';
            animation: dots 1.5s steps(4, end) infinite;
        }
        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }
        .security-badge {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        }
        .error-shake {
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
    </style>
</head>
<body class="min-h-screen bg-white">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <!-- Main Payment Container -->
        <div class="w-full max-w-6xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-200">
                <div class="grid md:grid-cols-2 gap-0">
                    
                    <!-- Left Panel - Brand & Amount -->
                    <div class="bg-gradient-to-br from-green-700 to-green-900 p-8 md:p-12 text-white">
                        <div class="h-full flex flex-col justify-between">
                            <!-- Header -->
                            <div>
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                                        <span class="text-2xl font-bold">FC</span>
                                    </div>
                                    <div>
                                        <h1 class="text-2xl font-bold">{{ $merchantName }}</h1>
                                        <p class="text-green-200 text-sm">Trusted Payment Partner</p>
                                    </div>
                                </div>

                                <!-- Payment Amount -->
                                <div class="mb-8">
                                    <label class="block text-green-200 text-sm font-medium mb-3">Payment Amount</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 font-medium">TZS</span>
                                        <input 
                                            type="text" 
                                            id="amount-input"
                                            class="w-full bg-white/10 backdrop-blur border border-white/20 rounded-xl px-4 py-4 pl-16 text-2xl font-bold text-white placeholder-white/50 focus:outline-none focus:border-white/40 focus:bg-white/20 transition-all"
                                            inputMode="numeric" 
                                            pattern="[0-9,]*" 
                                            placeholder="500" 
                                            value="{{ $defaultAmount }}"
                                            data-min-amount="500"
                                            data-max-amount="5000000"
                                        >
                                    </div>
                                    <p class="text-green-200 text-sm mt-2">Min: TSh 500 | Max: TSh 5,000,000</p>
                                </div>

                                <!-- Features -->
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-green-400/20 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-shield-alt text-green-300 text-sm"></i>
                                        </div>
                                        <span class="text-sm">Bank-level security encryption</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-green-400/20 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-bolt text-green-300 text-sm"></i>
                                        </div>
                                        <span class="text-sm">Instant payment processing</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-green-400/20 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-mobile-alt text-green-300 text-sm"></i>
                                        </div>
                                        <span class="text-sm">All major mobile money networks</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="mt-8 pt-8 border-t border-white/20">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-green-200">Powered by</p>
                                        <p class="text-sm font-semibold">Snippe Payment Gateway</p>
                                    </div>
                                    <div class="security-badge px-3 py-1 rounded-full text-xs font-medium">
                                        <i class="fas fa-lock mr-1"></i> Secured
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel - Payment Form -->
                    <div class="bg-white p-8 md:p-12">
                        <div class="max-w-md mx-auto">
                            <!-- Form Header -->
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Complete Your Payment</h2>
                                <p class="text-gray-600">Choose your preferred payment method</p>
                            </div>

                            <form id="payment-form" class="space-y-6">
                                <!-- Payment Methods -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-4">Select Payment Method</label>
                                    <div class="space-y-3">
                                        <!-- Mobile Money (Auto-selected) -->
                                        <div class="payment-method-card selected rounded-xl p-4 cursor-pointer" data-method="mobile">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                                        <i class="fas fa-mobile-alt text-green-600 text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900 text-sm">Mobile Money</p>
                                                        <p class="text-xs text-gray-500">M-Pesa, Airtel Money<br>YAS, Halotel</p>
                                                    </div>
                                                </div>
                                                <div class="w-6 h-6 rounded-full border-2 border-green-600 bg-green-600 flex items-center justify-center">
                                                    <div class="w-2 h-2 rounded-full bg-white"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="payment_method" id="payment_method" value="mobile" required>
                                </div>

                                <!-- Phone Number (for Mobile Money) -->
                                <div id="phone-field" class="transition-all duration-300">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                    <div class="flex">
                                        <div class="flex">
                                            <button type="button" class="flex items-center gap-2 px-4 py-3 bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg">
                                                <span class="w-6 h-4">
                                                    <svg viewBox="0 85.333 512 341.333" class="w-full h-full">
                                                        <title>TZ</title>
                                                        <path fill="#338AF3" d="M0 85.337h512v341.326H0z"></path>
                                                        <path fill="#6DA544" d="M0 426.663V85.337h512"></path>
                                                        <path fill="#FFDA44" d="M512 152.222V85.337H411.67L0 359.778v66.885h100.33z"></path>
                                                        <path d="M512 85.337v40.125L60.193 426.663H0v-40.125L451.807 85.337z"></path>
                                                    </svg>
                                                </span>
                                                <span class="text-sm font-medium">+255</span>
                                            </button>
                                            <input 
                                                type="tel" 
                                                id="customer_phone"
                                                name="phone_number"
                                                class="flex-1 px-4 py-3 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                placeholder="712 345 678"
                                                required
                                            >
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Format: 712 345 678</p>
                                </div>

                                <!-- Customer Information -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Information</label>
                                    <div class="space-y-3">
                                        <input 
                                            type="text" 
                                            id="customer_name"
                                            name="customer_name"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            placeholder="Full Name"
                                            required
                                        >
                                        <input 
                                            type="email" 
                                            id="customer_email"
                                            name="customer_email"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            placeholder="Email Address"
                                            required
                                        >
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button 
                                    type="submit" 
                                    id="pay-button"
                                    class="w-full bg-gradient-to-r from-green-600 to-green-800 text-white font-semibold py-4 px-6 rounded-xl hover:from-green-700 hover:to-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed pulse-animation"
                                >
                                    <span id="pay-button-text">Pay TSh {{ number_format($defaultAmount) }}</span>
                                    <div id="pay-button-loading" class="hidden flex items-center justify-center gap-2">
                                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Processing<span class="loading-dots"></span></span>
                                    </div>
                                </button>
                            </form>

                            <!-- Trust Badges -->
                            <div class="mt-8 pt-8 border-t border-gray-200">
                                <div class="flex flex-wrap items-center justify-center gap-4">
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <i class="fas fa-shield-alt text-green-500"></i>
                                        <span>SSL Secured</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <i class="fas fa-lock text-blue-500"></i>
                                        <span>256-bit Encryption</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <i class="fas fa-award text-purple-500"></i>
                                        <span>PCI DSS Compliant</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Progress Stages Modal -->
    <div id="progress-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Processing Payment</h3>
                <p class="text-gray-600 text-sm">Please wait while we process your payment securely</p>
            </div>
            
            <!-- Progress Steps -->
            <div class="space-y-4">
                <div class="flex items-center gap-3" id="stage-1">
                    <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-sm font-semibold">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Initializing Payment</p>
                        <p class="text-xs text-gray-500">Setting up secure connection</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3" id="stage-2">
                    <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-sm font-semibold">
                        <span class="loading-dots">2</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Processing Payment</p>
                        <p class="text-xs text-gray-500">Communicating with payment gateway</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3" id="stage-3">
                    <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-semibold">
                        3
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Checking Payment Status</p>
                        <p class="text-xs text-gray-500">Verifying payment completion</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3" id="stage-4">
                    <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-semibold">
                        4
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Generating Receipt & Sending Notifications</p>
                        <p class="text-xs text-gray-500">Creating receipt and sending email/SMS</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500 mb-2">Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY</p>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progress-bar" class="bg-green-600 h-2 rounded-full transition-all duration-500" style="width: 25%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Modal -->
    <div id="response-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all">
            <div id="modal-content"></div>
        </div>
    </div>

    <!-- Payment Processing Modal -->
    <div id="processing-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Processing Payment</h3>
            <p class="text-gray-600 mb-4">Please wait while we process your payment<span class="loading-dots"></span></p>
            <div id="processing-status" class="text-sm text-gray-500"></div>
        </div>
    </div>

    <script>
        // Payment form handling
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('payment-form');
            const amountInput = document.getElementById('amount-input');
            const payButton = document.getElementById('pay-button');
            const payButtonText = document.getElementById('pay-button-text');
            const payButtonLoading = document.getElementById('pay-button-loading');
            const phoneField = document.getElementById('phone-field');
            const modal = document.getElementById('response-modal');
            const processingModal = document.getElementById('processing-modal');
            const progressModal = document.getElementById('progress-modal');
            const modalContent = document.getElementById('modal-content');
            const processingStatus = document.getElementById('processing-status');
            const progressBar = document.getElementById('progress-bar');

            // Hide splash screen after page loads (removed - no splash screen)
            // Page loads directly without splash screen

            // Initialize phone field as visible by default (mobile money auto-selected)
            if (phoneField) {
                phoneField.style.display = 'block';
                phoneField.style.opacity = '1';
                phoneField.style.transform = 'translateY(0)';
            }

            // Payment method selection (removed - only mobile money available)
            // Mobile money is auto-selected and always visible
            // Phone field is already initialized above

            // Amount formatting and validation
            amountInput.addEventListener('input', function() {
                let value = this.value.replace(/[^0-9]/g, '');
                const minAmount = parseInt(this.dataset.minAmount);
                const maxAmount = parseInt(this.dataset.maxAmount);
                
                if (value && parseInt(value) >= minAmount && parseInt(value) <= maxAmount) {
                    payButtonText.textContent = `Pay TSh ${parseInt(value).toLocaleString()}`;
                    this.classList.remove('border-red-500');
                } else {
                    payButtonText.textContent = `Pay TSh ${minAmount.toLocaleString()}`;
                    if (value && (parseInt(value) < minAmount || parseInt(value) > maxAmount)) {
                        this.classList.add('border-red-500');
                    } else {
                        this.classList.remove('border-red-500');
                    }
                }
            });

            // Phone number formatting
            document.getElementById('customer_phone').addEventListener('input', function() {
                let value = this.value.replace(/[^0-9]/g, '');
                if (value.length > 0 && value.length <= 9) {
                    // Format as 712 345 678
                    if (value.length >= 3) {
                        value = value.slice(0, 3) + ' ' + value.slice(3);
                    }
                    if (value.length >= 7) {
                        value = value.slice(0, 7) + ' ' + value.slice(7);
                    }
                    this.value = value;
                }
            });

            // Update progress stages
            function updateProgressStage(stage, isComplete = false) {
                for (let i = 1; i <= 4; i++) {
                    const stageElement = document.getElementById(`stage-${i}`);
                    const circleElement = stageElement.querySelector('.rounded-full');
                    const textElements = stageElement.querySelectorAll('p');
                    
                    if (i < stage) {
                        // Completed stages
                        circleElement.className = 'w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-sm font-semibold';
                        circleElement.innerHTML = '<i class="fas fa-check text-xs"></i>';
                        textElements.forEach(el => el.classList.remove('text-gray-400'));
                        textElements.forEach(el => el.classList.add('text-gray-900'));
                    } else if (i === stage) {
                        // Current stage
                        if (isComplete) {
                            circleElement.className = 'w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-sm font-semibold';
                            circleElement.innerHTML = '<i class="fas fa-check text-xs"></i>';
                            textElements.forEach(el => el.classList.remove('text-gray-400'));
                            textElements.forEach(el => el.classList.add('text-gray-900'));
                        } else {
                            circleElement.className = 'w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold';
                            circleElement.innerHTML = '<span class="loading-dots">' + i + '</span>';
                            textElements.forEach(el => el.classList.remove('text-gray-400'));
                            textElements.forEach(el => el.classList.add('text-gray-900'));
                        }
                    } else {
                        // Future stages
                        circleElement.className = 'w-8 h-8 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-semibold';
                        circleElement.textContent = i;
                        textElements.forEach(el => el.classList.add('text-gray-400'));
                        textElements.forEach(el => el.classList.remove('text-gray-900'));
                    }
                }
                
                // Update progress bar
                const progressPercentage = (stage / 4) * 100;
                progressBar.style.width = `${progressPercentage}%`;
            }

            // Form submission
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const paymentType = document.getElementById('payment_method').value;
                const amount = parseInt(amountInput.value.replace(/[^0-9]/g, ''));
                const phoneNumber = document.getElementById('customer_phone').value;
                const customerName = document.getElementById('customer_name').value;
                const customerEmail = document.getElementById('customer_email').value;

                // Enhanced validation
                if (amount < 500 || amount > 5000000) {
                    showError('Invalid Amount', 'Amount must be between TSh 500 and TSh 5,000,000');
                    amountInput.classList.add('error-shake');
                    setTimeout(() => amountInput.classList.remove('error-shake'), 500);
                    return;
                }

                if (paymentType === 'mobile') {
                    const cleanPhone = phoneNumber.replace(/[^0-9]/g, '');
                    if (cleanPhone.length !== 9) {
                        showError('Invalid Phone', 'Please enter a valid 9-digit phone number');
                        document.getElementById('customer_phone').classList.add('error-shake');
                        setTimeout(() => document.getElementById('customer_phone').classList.remove('error-shake'), 500);
                        return;
                    }
                }

                if (!customerName.trim() || customerName.length < 2) {
                    showError('Invalid Name', 'Please enter your full name');
                    document.getElementById('customer_name').classList.add('error-shake');
                    setTimeout(() => document.getElementById('customer_name').classList.remove('error-shake'), 500);
                    return;
                }

                if (!customerEmail.trim() || !isValidEmail(customerEmail)) {
                    showError('Invalid Email', 'Please enter a valid email address');
                    document.getElementById('customer_email').classList.add('error-shake');
                    setTimeout(() => document.getElementById('customer_email').classList.remove('error-shake'), 500);
                    return;
                }

                // Always use mobile money as payment type
                const paymentType = 'mobile';

                // Show processing modal
                showProcessingModal(paymentType);

                // Update progress
                updateProgressStage(2, true);
                setTimeout(() => updateProgressStage(3), 1000);

                try {
                    const response = await fetch('/api/payments/process', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            payment_type: paymentType,
                            amount: amount,
                            phone_number: formatPhoneNumber(phoneNumber),
                            customer_name: customerName.trim(),
                            customer_email: customerEmail.trim()
                        })
                    });

                    const result = await response.json();

                    // Update progress
                    updateProgressStage(3, true);
                    setTimeout(() => updateProgressStage(4), 1000);

                    if (result.status === 'success') {
                        // Check payment status before generating receipt
                        setTimeout(() => {
                            checkPaymentStatusAndGenerateReceipt(result.data, customerName, customerEmail, formattedPhone, amount, paymentType);
                        }, 2000);
                    } else {
                        setTimeout(() => {
                            showError('Payment Failed', result.message || 'An error occurred while processing your payment. Please try again.');
                        }, 2000);
                    }
                } catch (error) {
                    setTimeout(() => {
                        console.error('Payment error:', error);
                        showError('Network Error', 'Unable to connect to payment service. Please check your internet connection and try again.');
                    }, 2000);
                } finally {
                    // Reset button after delay
                    setTimeout(() => {
                        payButton.disabled = false;
                        payButton.classList.add('pulse-animation');
                        payButtonText.classList.remove('hidden');
                        payButtonLoading.classList.add('hidden');
                        progressModal.classList.add('hidden');
                        progressModal.classList.remove('flex');
                    }, 5000);
                }
            });

            // Check payment status and generate receipt only if completed
            async function checkPaymentStatusAndGenerateReceipt(paymentData, customerName, customerEmail, phoneNumber, amount, paymentType) {
                try {
                    // Update progress stage 3 (checking status)
                    updateProgressStage(3, true);
                    
                    // Check payment status via API
                    const statusResponse = await fetch(`/api/payments/status/${paymentData.reference}`);
                    const statusResult = await statusResponse.json();
                    
                    if (statusResult.status === 'success' && statusResult.data.status === 'completed') {
                        // Payment is completed, generate receipt
                        updateProgressStage(4, true);
                        
                        // Generate and send receipt
                        const receiptResponse = await fetch('/api/payments/receipt', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            },
                            body: JSON.stringify({
                                payment_data: paymentData,
                                customer_name: customerName,
                                customer_email: customerEmail,
                                phone_number: phoneNumber,
                                amount: amount,
                                payment_type: paymentType
                            })
                        });
                        
                        const receiptResult = await receiptResponse.json();
                        
                        if (receiptResult.status === 'success') {
                            // Hide progress modal and show success
                            setTimeout(() => {
                                hideProgressModal();
                                showSuccessModal(paymentType, paymentData);
                            }, 1000);
                        } else {
                            console.error('Receipt generation failed:', receiptResult.message);
                            // Still show success but log the error
                            setTimeout(() => {
                                hideProgressModal();
                                showSuccessModal(paymentType, paymentData);
                            }, 1000);
                        }
                    } else if (statusResult.data.status === 'pending') {
                        // Payment is still pending, check again after delay
                        setTimeout(() => {
                            checkPaymentStatusAndGenerateReceipt(paymentData, customerName, customerEmail, phoneNumber, amount, paymentType);
                        }, 3000); // Check again after 3 seconds
                    } else {
                        // Payment failed or expired
                        updateProgressStage(4, false);
                        setTimeout(() => {
                            hideProgressModal();
                            showError('Payment Failed', `Payment status: ${statusResult.data.status}. Please try again.`);
                        }, 1000);
                    }
                } catch (error) {
                    console.error('Status check error:', error);
                    updateProgressStage(4, false);
                    setTimeout(() => {
                        hideProgressModal();
                        showError('Status Check Failed', 'Unable to verify payment status. Please contact support.');
                    }, 1000);
                }
            }

            // Generate and send receipt (legacy function - kept for compatibility)
            async function generateAndSendReceipt(paymentData, customerName, customerEmail, phoneNumber, amount, paymentType) {
                try {
                    const response = await fetch('/api/payments/receipt', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            payment_data: paymentData,
                            customer_name: customerName,
                            customer_email: customerEmail,
                            phone_number: phoneNumber,
                            amount: amount,
                            payment_type: paymentType
                        })
                    });
                    
                    const result = await response.json();
                    console.log('Receipt generation result:', result);
                } catch (error) {
                    console.error('Receipt generation error:', error);
                }
            }

            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            function showProcessingModal(paymentType) {
                const messages = {
                    mobile: 'Connecting to mobile money network...',
                    card: 'Redirecting to secure payment page...',
                    qr: 'Generating QR code...'
                };
                
                processingStatus.textContent = messages[paymentType] || 'Processing payment...';
                processingModal.classList.remove('hidden');
                processingModal.classList.add('flex');
            }

            function hideProcessingModal() {
                processingModal.classList.add('hidden');
                processingModal.classList.remove('flex');
            }

            function showSuccessModal(paymentType, data) {
                const messages = {
                    mobile: {
                        title: 'Payment Completed Successfully!',
                        message: 'Your payment has been processed successfully. A receipt has been sent to your email and phone.',
                        icon: 'fa-mobile-alt',
                        color: 'green'
                    },
                    card: {
                        title: 'Payment Completed Successfully!',
                        message: 'Your card payment has been processed successfully. A receipt has been sent to your email.',
                        icon: 'fa-credit-card',
                        color: 'blue'
                    },
                    qr: {
                        title: 'Payment Completed Successfully!',
                        message: 'Your QR code payment has been processed successfully. A receipt has been sent to your email.',
                        icon: 'fa-qrcode',
                        color: 'purple'
                    }
                };

                const config = messages[paymentType];
                const bgColor = `bg-${config.color}-50`;
                const textColor = `text-${config.color}-800`;

                modalContent.innerHTML = `
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full ${bgColor} mb-4">
                            <i class="fas ${config.icon} text-${config.color}-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">${config.title}</h3>
                        <p class="text-gray-600 mb-6">${config.message}</p>
                        ${data.reference ? `<p class="text-sm text-gray-500 mb-4">Reference: ${data.reference}</p>` : ''}
                        <div class="space-y-3">
                            <button onclick="closeModal()" class="w-full bg-gradient-to-r from-green-600 to-green-800 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-green-900 transition-all">
                                Got it
                            </button>
                            <button onclick="location.reload()" class="w-full bg-gray-100 text-gray-700 font-medium py-3 px-6 rounded-lg hover:bg-gray-200 transition-all">
                                Make Another Payment
                            </button>
                        </div>
                    </div>
                `;
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function showError(title, message) {
                modalContent.innerHTML = `
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-50 mb-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">${title}</h3>
                        <p class="text-gray-600 mb-6">${message}</p>
                        <div class="space-y-3">
                            <button onclick="closeModal()" class="w-full bg-red-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-red-700 transition-all">
                                Try Again
                            </button>
                            <button onclick="location.reload()" class="w-full bg-gray-100 text-gray-700 font-medium py-3 px-6 rounded-lg hover:bg-gray-200 transition-all">
                                Start Over
                            </button>
                        </div>
                    </div>
                `;
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            window.closeModal = function() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };
        });
    </script>
</body>
</html>
