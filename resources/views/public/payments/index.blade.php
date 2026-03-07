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
                <div class="grid lg:grid-cols-2 gap-0">
                    
                    <!-- Left Panel - Brand & Amount -->
                    <div class="bg-gradient-to-br from-green-700 to-green-900 p-6 lg:p-12 text-white">
                        <div class="h-full flex flex-col justify-between">
                            <!-- Header -->
                            <div>
                                <div class="flex items-center gap-4 mb-6 lg:mb-8">
                                    <div class="w-12 lg:w-16 h-12 lg:h-16 bg-white/20 backdrop-blur rounded-xl lg:rounded-2xl flex items-center justify-center">
                                        <span class="text-lg lg:text-2xl font-bold">FC</span>
                                    </div>
                                    <div>
                                        <h1 class="text-lg lg:text-2xl font-bold">{{ $merchantName }}</h1>
                                        <p class="text-green-200 text-xs lg:text-sm">Trusted Payment Partner</p>
                                    </div>
                                </div>

                                <!-- Payment Amount -->
                                <div class="mb-6 lg:mb-8">
                                    <label class="block text-green-200 text-xs lg:text-sm font-medium mb-2 lg:mb-3">Payment Amount</label>
                                    <div class="relative">
                                        <span class="absolute left-3 lg:left-4 top-1/2 -translate-y-1/2 text-white/70 font-medium text-sm lg:text-base">TZS</span>
                                        <input 
                                            type="text" 
                                            id="amount-input"
                                            class="w-full bg-white/10 backdrop-blur border border-white/20 rounded-xl px-3 lg:px-4 py-3 lg:py-4 pl-14 lg:pl-16 text-lg lg:text-2xl font-bold text-white placeholder-white/50 focus:outline-none focus:border-white/40 focus:bg-white/20 transition-all"
                                            inputMode="numeric" 
                                            pattern="[0-9,]*" 
                                            placeholder="500" 
                                            value="{{ $defaultAmount }}"
                                            data-min-amount="500"
                                            data-max-amount="5000000"
                                        >
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <p class="text-green-200 text-xs lg:text-sm">Min: TSh 500 | Max: TSh 5,000,000</p>
                                    </div>
                                    <div class="mt-2 p-3 lg:p-4 bg-white/10 rounded-lg">
                                        <p class="text-green-100 text-xs lg:text-sm">Total Amount: <span id="total-amount" class="text-white font-bold">TSh {{ number_format($defaultAmount) }}</span></p>
                                    </div>
                                </div>

                                <!-- Features -->
                                <div class="space-y-3 lg:space-y-4">
                                    <div class="flex items-center gap-2 lg:gap-3">
                                        <div class="w-6 lg:w-8 h-6 lg:h-8 bg-green-400/20 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-shield-alt text-green-300 text-xs lg:text-sm"></i>
                                        </div>
                                        <span class="text-xs lg:text-sm">Bank-level security encryption</span>
                                    </div>
                                    <div class="flex items-center gap-2 lg:gap-3">
                                        <div class="w-6 lg:w-8 h-6 lg:h-8 bg-green-400/20 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-bolt text-green-300 text-xs lg:text-sm"></i>
                                        </div>
                                        <span class="text-xs lg:text-sm">Instant payment processing</span>
                                    </div>
                                    <div class="flex items-center gap-2 lg:gap-3">
                                        <div class="w-6 lg:w-8 h-6 lg:h-8 bg-green-400/20 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-mobile-alt text-green-300 text-xs lg:text-sm"></i>
                                        </div>
                                        <span class="text-xs lg:text-sm">All major mobile money networks</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="mt-4 lg:mt-8 pt-4 lg:pt-8 border-t border-white/20">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-green-200">Powered by</p>
                                        <p class="text-xs lg:text-sm font-semibold">Feedtan CMG @2026 SECURED PAYMENT GATEWAY</p>
                                    </div>
                                    <div class="security-badge px-2 lg:px-3 py-1 rounded-full text-xs font-medium">
                                        <i class="fas fa-lock mr-1"></i> Secured
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel - Payment Form -->
                    <div class="bg-white p-6 lg:p-12">
                        <div class="max-w-md mx-auto">
                            <!-- Step 1: Details -->
                            <div id="step-1" class="space-y-6">
                                <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6 text-center lg:text-left">Payment Details</h2>
                                
                                <!-- Payment Method Selection -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Choose Payment Method</label>
                                    <div class="space-y-3">
                                        <!-- Mobile Money (Auto-selected) -->
                                        <div class="payment-method-card selected rounded-xl p-3 lg:p-4 cursor-pointer" data-method="mobile">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2 lg:gap-4">
                                                    <div class="w-10 lg:w-12 h-10 lg:h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                                        <i class="fas fa-mobile-alt text-green-600 text-sm lg:text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900 text-sm lg:text-base">Mobile Money</p>
                                                        <p class="text-xs lg:text-sm text-gray-500">M-Pesa, Airtel Money, Tigo Pesa, Halotel</p>
                                                    </div>
                                                </div>
                                                <div class="w-5 lg:w-6 h-5 lg:h-6 rounded-full border-2 border-green-600 bg-green-600 flex items-center justify-center">
                                                    <div class="w-2 lg:w-2.5 h-2 lg:h-2.5 rounded-full bg-white"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- QR Code -->
                                        <div class="payment-method-card rounded-xl p-3 lg:p-4 cursor-pointer border-2 border-gray-200" data-method="qr">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2 lg:gap-4">
                                                    <div class="w-10 lg:w-12 h-10 lg:h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                                        <i class="fas fa-qrcode text-blue-600 text-sm lg:text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900 text-sm lg:text-base">Lipa Namba (TIPS)</p>
                                                        <p class="text-xs lg:text-sm text-gray-500">Scan QR code to pay</p>
                                                    </div>
                                                </div>
                                                <div class="w-5 lg:w-6 h-5 lg:h-6 rounded-full border-2 border-gray-300"></div>
                                            </div>
                                        </div>

                                        <!-- Card Payment -->
                                        <div class="payment-method-card rounded-xl p-3 lg:p-4 cursor-pointer border-2 border-gray-200" data-method="card">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2 lg:gap-4">
                                                    <div class="w-10 lg:w-12 h-10 lg:h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                                        <i class="fas fa-credit-card text-purple-600 text-sm lg:text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900 text-sm lg:text-base">Card Payment</p>
                                                        <p class="text-xs lg:text-sm text-gray-500">Visa, Mastercard</p>
                                                    </div>
                                                </div>
                                                <div class="w-5 lg:w-6 h-5 lg:h-6 rounded-full border-2 border-gray-300"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone Number Field (Mobile Money Only) -->
                                <div id="phone-field" class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Phone Number</label>
                                    <div class="flex">
                                        <button class="flex items-center gap-2 px-3 lg:px-4 py-3 lg:py-3 bg-gray-100 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill="#FFDA44" d="M512 152.222V85.337H411.67L0 359.778v66.885h100.33z"></path>
                                                <path d="M512 85.337v40.125L60.193 426.663H0v-40.125L451.807 85.337z"></path>
                                            </svg>
                                            <span class="text-sm font-medium">+255</span>
                                        </button>
                                        <input 
                                            type="tel" 
                                            id="customer_phone"
                                            name="phone_number"
                                            class="flex-1 px-3 lg:px-4 py-3 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm lg:text-base"
                                            placeholder="712 345 678"
                                            required
                                        >
                                    </div>
                                    <p class="text-xs text-gray-500">Format: 712 345 678</p>
                                </div>

                                <!-- Customer Information -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-700">Contact Information</label>
                                    <div class="space-y-3">
                                        <input 
                                            type="text" 
                                            id="customer_name"
                                            name="customer_name"
                                            class="w-full px-3 lg:px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm lg:text-base"
                                            placeholder="Full Name"
                                            required
                                        >
                                        <input 
                                            type="email" 
                                            id="customer_email"
                                            name="customer_email"
                                            class="w-full px-3 lg:px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm lg:text-base"
                                            placeholder="Email Address"
                                            required
                                        >
                                    </div>
                                </div>

                                <!-- Start Payment Button -->
                                <button 
                                    type="button"
                                    id="start-payment-btn"
                                    class="w-full bg-gradient-to-r from-green-600 to-green-800 text-white font-semibold py-3 lg:py-4 px-6 rounded-xl hover:from-green-700 hover:to-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 text-sm lg:text-base"
                                >
                                    Start Payment Now
                                </button>
                            </div>

                            <!-- Step 2: Complete Payment -->
                            <div id="step-2" class="hidden space-y-6">
                                <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6 text-center lg:text-left">Complete Your Payment</h2>
                                
                                <div class="bg-gray-50 rounded-xl p-4 lg:p-6">
                                    <div class="text-center space-y-4">
                                        <div class="w-16 lg:w-20 h-16 lg:h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                                            <i class="fas fa-check text-green-600 text-2xl lg:text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg lg:text-xl font-semibold text-gray-900">Ready to Complete Payment</h3>
                                        <p class="text-gray-600 text-sm lg:text-base">Amount to Pay: <span id="final-amount" class="font-bold text-green-600">TSh 0</span></p>
                                        <p class="text-gray-500 text-xs lg:text-sm">Payment Method: <span id="selected-method" class="font-medium">Mobile Money</span></p>
                                    </div>
                                </div>

                                <button 
                                    type="submit"
                                    id="complete-payment-btn"
                                    class="w-full bg-gradient-to-r from-green-600 to-green-800 text-white font-semibold py-3 lg:py-4 px-6 rounded-xl hover:from-green-700 hover:to-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 text-sm lg:text-base pulse-animation"
                                >
                                    <span id="complete-payment-text">Complete Payment</span>
                                    <div id="complete-payment-loading" class="hidden flex items-center justify-center gap-2">
                                        <svg class="animate-spin h-4 lg:h-5 w-4 lg:w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Processing<span class="loading-dots"></span></span>
                                    </div>
                                </button>

                                <button 
                                    type="button"
                                    id="back-to-details"
                                    class="w-full bg-gray-100 text-gray-700 font-medium py-3 lg:py-4 px-6 rounded-xl hover:bg-gray-200 transition-all text-sm lg:text-base"
                                >
                                    ← Back to Details
                                </button>
                            </div>
                        </div>
                    </div>

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
            <div id="processing-status" class="text-sm text-gray-500 mb-4"></div>
            
            <!-- Payment Stages -->
            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-3" id="stage-1">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                    <span class="text-sm text-gray-700">Payment initiated</span>
                </div>
                <div class="flex items-center gap-3" id="stage-2">
                    <div class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center" id="stage-2-icon">
                        <div class="w-3 h-3 rounded-full bg-white"></div>
                    </div>
                    <span class="text-sm text-gray-500" id="stage-2-text">Connecting to payment provider</span>
                </div>
                <div class="flex items-center gap-3" id="stage-3">
                    <div class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center" id="stage-3-icon">
                        <div class="w-3 h-3 rounded-full bg-white"></div>
                    </div>
                    <span class="text-sm text-gray-500" id="stage-3-text">Processing payment</span>
                </div>
                <div class="flex items-center gap-3" id="stage-4">
                    <div class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center" id="stage-4-icon">
                        <div class="w-3 h-3 rounded-full bg-white"></div>
                    </div>
                    <span class="text-sm text-gray-500" id="stage-4-text">Generating receipt</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Payment form handling
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('payment-form');
            const amountInput = document.getElementById('amount-input');
            const startPaymentBtn = document.getElementById('start-payment-btn');
            const completePaymentBtn = document.getElementById('complete-payment-btn');
            const backToDetailsBtn = document.getElementById('back-to-details');
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const phoneField = document.getElementById('phone-field');
            const modal = document.getElementById('response-modal');
            const processingModal = document.getElementById('processing-modal');
            const modalContent = document.getElementById('modal-content');
            const processingStatus = document.getElementById('processing-status');
            const totalAmount = document.getElementById('total-amount');
            const finalAmount = document.getElementById('final-amount');
            const selectedMethod = document.getElementById('selected-method');
            const completePaymentText = document.getElementById('complete-payment-text');
            const completePaymentLoading = document.getElementById('complete-payment-loading');

            let currentPaymentMethod = 'mobile';
            let currentAmount = parseInt(amountInput.value.replace(/[^0-9]/g, '')) || 500;

            // Update total amount display
            function updateTotalAmount() {
                let value = parseInt(amountInput.value.replace(/[^0-9]/g, '')) || 500;
                const minAmount = parseInt(amountInput.dataset.minAmount);
                const maxAmount = parseInt(amountInput.dataset.maxAmount);
                
                if (value && value >= minAmount && value <= maxAmount) {
                    currentAmount = value;
                    totalAmount.textContent = `TSh ${value.toLocaleString()}`;
                    finalAmount.textContent = `TSh ${value.toLocaleString()}`;
                    amountInput.classList.remove('border-red-500');
                } else {
                    totalAmount.textContent = `TSh ${minAmount.toLocaleString()}`;
                    finalAmount.textContent = `TSh ${minAmount.toLocaleString()}`;
                    if (value && (value < minAmount || value > maxAmount)) {
                        amountInput.classList.add('border-red-500');
                    } else {
                        amountInput.classList.remove('border-red-500');
                    }
                }
            }

            // Payment method selection
            const paymentCards = document.querySelectorAll('.payment-method-card');
            paymentCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove selected state from all cards
                    paymentCards.forEach(c => {
                        c.classList.remove('selected');
                        c.classList.add('border-gray-200');
                        const radio = c.querySelector('.rounded-full');
                        radio.classList.remove('border-green-600', 'bg-green-600');
                        radio.classList.add('border-gray-300');
                        const dot = radio.querySelector('.bg-white');
                        if (dot) dot.classList.add('hidden');
                    });

                    // Add selected state to clicked card
                    this.classList.add('selected');
                    this.classList.remove('border-gray-200');
                    const selectedRadio = this.querySelector('.rounded-full');
                    selectedRadio.classList.remove('border-gray-300');
                    selectedRadio.classList.add('border-green-600', 'bg-green-600');
                    const dot = selectedRadio.querySelector('.bg-white');
                    if (dot) dot.classList.remove('hidden');

                    // Update current payment method
                    currentPaymentMethod = this.dataset.method;
                    selectedMethod.textContent = this.querySelector('.font-semibold').textContent;

                    // Show/hide phone field
                    phoneField.style.display = currentPaymentMethod === 'mobile' ? 'block' : 'none';
                });
            });

            // Amount formatting and validation
            amountInput.addEventListener('input', function() {
                updateTotalAmount();
            });

            // Initialize amount display
            updateTotalAmount();

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
                }
                this.value = value;
            });

            // Start payment button
            startPaymentBtn.addEventListener('click', function() {
                // Validate form
                const customerName = document.getElementById('customer_name').value.trim();
                const customerEmail = document.getElementById('customer_email').value.trim();
                const phoneNumber = document.getElementById('customer_phone').value.replace(/[^0-9]/g, '');

                // Validation
                if (!customerName) {
                    document.getElementById('customer_name').classList.add('error-shake');
                    setTimeout(() => document.getElementById('customer_name').classList.remove('error-shake'), 500);
                    return;
                }

                if (!customerEmail || !isValidEmail(customerEmail)) {
                    document.getElementById('customer_email').classList.add('error-shake');
                    setTimeout(() => document.getElementById('customer_email').classList.remove('error-shake'), 500);
                    return;
                }

                if (currentPaymentMethod === 'mobile' && (!phoneNumber || phoneNumber.length < 9)) {
                    document.getElementById('customer_phone').classList.add('error-shake');
                    setTimeout(() => document.getElementById('customer_phone').classList.remove('error-shake'), 500);
                    return;
                }

                // Move to step 2
                step1.classList.add('hidden');
                step2.classList.remove('hidden');
            });

            // Back to details button
            backToDetailsBtn.addEventListener('click', function() {
                step2.classList.add('hidden');
                step1.classList.remove('hidden');
            });

            // Complete payment button
            completePaymentBtn.addEventListener('click', async function() {
                // Format phone number
                let formattedPhone = null;
                if (currentPaymentMethod === 'mobile') {
                    formattedPhone = '+255' + document.getElementById('customer_phone').value.replace(/[^0-9]/g, '');
                }

                const customerName = document.getElementById('customer_name').value.trim();
                const customerEmail = document.getElementById('customer_email').value.trim();

                // Show processing modal with stages
                showProcessingModal(currentPaymentMethod);
                simulatePaymentStages();

                // Disable button and show loading
                completePaymentBtn.disabled = true;
                completePaymentBtn.classList.remove('pulse-animation');
                completePaymentText.classList.add('hidden');
                completePaymentLoading.classList.remove('hidden');

                try {
                    const response = await fetch('/api/payments/process', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            payment_type: currentPaymentMethod,
                            amount: currentAmount,
                            phone_number: formattedPhone,
                            customer_name: customerName,
                            customer_email: customerEmail
                        })
                    });

                    const result = await response.json();

                    // Hide processing modal
                    hideProcessingModal();

                    if (result.status === 'success') {
                        // Show success modal
                        showSuccessModal(currentPaymentMethod, result.data);
                        
                        // After success, refresh page after delay
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        showError('Payment Failed', result.message || 'An error occurred while processing your payment. Please try again.');
                    }
                } catch (error) {
                    hideProcessingModal();
                    console.error('Payment error:', error);
                    showError('Network Error', 'Unable to connect to payment service. Please check your internet connection and try again.');
                } finally {
                    // Reset button
                    completePaymentBtn.disabled = false;
                    completePaymentBtn.classList.add('pulse-animation');
                    completePaymentText.classList.remove('hidden');
                    completePaymentLoading.classList.add('hidden');
                }
            });

            // Simulate payment stages
            function simulatePaymentStages() {
                const stages = [
                    { id: 'stage-2', text: 'stage-2-text', icon: 'stage-2-icon', delay: 1000 },
                    { id: 'stage-3', text: 'stage-3-text', icon: 'stage-3-icon', delay: 3000 },
                    { id: 'stage-4', text: 'stage-4-text', icon: 'stage-4-icon', delay: 5000 }
                ];

                stages.forEach(stage => {
                    setTimeout(() => {
                        // Complete stage
                        document.getElementById(stage.id).querySelector('.rounded-full').classList.remove('bg-gray-300');
                        document.getElementById(stage.id).querySelector('.rounded-full').classList.add('bg-green-500');
                        document.getElementById(stage.id).querySelector('.rounded-full').innerHTML = '<i class="fas fa-check text-white text-xs"></i>';
                        
                        // Update text
                        const textElement = document.getElementById(stage.text);
                        if (textElement) {
                            textElement.classList.remove('text-gray-500');
                            textElement.classList.add('text-gray-700');
                        }
                    }, stage.delay);
                });
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
                        title: 'Payment Initiated Successfully',
                        message: 'Please check your phone and complete the payment using the prompt sent to your device.',
                        icon: 'fa-mobile-alt',
                        color: 'green'
                    },
                    card: {
                        title: 'Redirecting to Payment',
                        message: 'You will be redirected to the secure payment page to complete your transaction.',
                        icon: 'fa-credit-card',
                        color: 'blue'
                    },
                    qr: {
                        title: 'QR Code Generated',
                        message: 'Scan the QR code with your banking app to complete the payment.',
                        icon: 'fa-qrcode',
                        color: 'purple'
                    }
                };

                const config = messages[paymentType] || messages.mobile;
                
                modalContent.innerHTML = `
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-${config.color}-100 mb-4">
                            <i class="fas ${config.icon} text-${config.color}-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">${config.title}</h3>
                        <p class="text-gray-600 mb-6">${config.message}</p>
                        <div class="space-y-3">
                            <button onclick="closeModal()" class="w-full bg-gradient-to-r from-green-600 to-green-800 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-green-900 transition-all">
                                Got it
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
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">${title}</h3>
                        <p class="text-gray-600 mb-6">${message}</p>
                        <div class="space-y-3">
                            <button onclick="closeModal()" class="w-full bg-gradient-to-r from-red-600 to-red-800 text-white font-semibold py-3 px-6 rounded-lg hover:from-red-700 hover:to-red-900 transition-all">
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

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    </script>
</body>
</html>
