<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thibitisho ya Malipo ya FIA - Feedtan CMG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
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
        .nunito {
            font-family: 'Nunito', sans-serif;
        }
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 50;
        }
    </style>
</head>
<body class="bg-gray-50 nunito">
    <!-- Header -->
    <header class="bg-green-600 text-white shadow-lg sticky-header">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-green-600 font-bold text-lg nunito">FC</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold nunito">FEEDTAN CMG</h1>
                        <p class="text-green-100 text-sm nunito">Thibitisho za Malipo ya FIA</p>
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
                <h2 class="text-3xl font-bold text-gray-800 mb-2 nunito">Thibitisho la Malipo ya FIA</h2>
                <p class="text-gray-600 nunito">Wasilisha maelezo ya malipo yako kwa ajili ya uthibitisho na usindikaji</p>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button onclick="showTab('verification')" id="verification-tab" class="tab-active py-4 px-6 text-sm font-medium border-b-3 border-transparent hover:text-green-600 hover:border-green-600 transition-colors duration-200 nunito">
                            <i class="fas fa-file-alt mr-2"></i>
                            Wasilisha Thibitisho la Malipo
                        </button>
                    </nav>
                </div>

                <!-- Verification Form Tab -->
                <div id="verification-content" class="p-6 fade-in">
                    <form id="verification-form" class="space-y-6">
                        <!-- Membership Code Input -->
                        <div class="max-w-md mx-auto">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center nunito">Weka Nambari ya Uanachama</h3>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2 nunito">Nambari ya Uanachama *</label>
                                <div class="flex gap-3">
                                    <input type="text" id="membership-code" name="membership_code" required
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-lg nunito"
                                        placeholder="Weka nambari yako ya uanachama">
                                    <button type="button" onclick="lookupMembership()" id="lookup-btn"
                                        class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium nunito">
                                        <i class="fas fa-search mr-2"></i>
                                        <span id="lookup-btn-text">Tafuta</span>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 nunito">Weka nambari yako ya uanachama kuona maelezo ya malipo</p>
                            </div>
                            
                            <!-- Hidden member ID field -->
                            <input type="hidden" id="member-id" name="member_id">

                            <!-- Loading State -->
                            <div id="lookup-loading" class="hidden text-center">
                                <div class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                    <i class="fas fa-spinner fa-spin text-green-600 mr-2"></i>
                                    <span class="text-green-700 font-medium nunito">Inatafuta akaunti yako...</span>
                                </div>
                            </div>

                            <!-- Error State -->
                            <div id="lookup-error" class="hidden text-center">
                                <div class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    <span class="text-red-700 font-medium nunito" id="error-message">Nambari ya uanachama haipatikani</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details (Hidden initially) -->
                        <div id="payment-details" class="hidden">
                            <!-- Member Info -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 nunito">Maelezo ya Mwanachama</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600 nunito">Jina la Mwanachama:</p>
                                        <p class="font-semibold nunito" id="member-name">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 nunito">Nambari ya Uanachama:</p>
                                        <p class="font-semibold nunito" id="member-code">-</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Breakdown -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 nunito">Maelezo ya Malipo</h3>
                                <div id="payment-breakdown" class="space-y-2">
                                    <!-- Payment items will be loaded here -->
                                </div>
                                <div class="mt-4 pt-4 border-t border-blue-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-800 nunito">Jumla ya Malipo:</span>
                                        <span class="text-xl font-bold text-blue-600 nunito" id="total-amount">TZS 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Distribution -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 nunito">Mgawanyo wa Malipo</h3>
                                <p class="text-sm text-gray-600 mb-4 nunito">Kiasi Kinachopatikana kwa Ombi: <span id="remaining-amount" class="font-bold text-yellow-600">TZS 0.00</span></p>
                                <p class="text-xs text-gray-500 mb-4 nunito">Kiasi Baki ndicho pekee kinachoweza kuombwa na kugawanywa kama Akiba, Uwekezaji, na Cash.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Savings -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2 nunito">
                                            <i class="fas fa-piggy-bank text-green-600 mr-1"></i>
                                            Akiba (Ongeza kwenye Akiba yako)
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium nunito">TZS</span>
                                            <input type="number" id="savings-amount" name="savings_amount" min="0" step="0.01"
                                                class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 nunito"
                                                placeholder="0.00" onchange="updateRemainingAmount()">
                                        </div>
                                    </div>

                                    <!-- Investment -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2 nunito">
                                            <i class="fas fa-chart-line text-blue-600 mr-1"></i>
                                            Uwekezaji (Vipande FIA)
                                        </label>
                                        
                                        <select id="fia-type" name="fia_type" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2 nunito">
                                            <option value="">Chagua Muda wa Uwekezaji</option>
                                            <option value="4-years">Miaka 4</option>
                                            <option value="6-years">Miaka 6</option>
                                        </select>
                                        
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium nunito">TZS</span>
                                            <input type="number" id="investment-amount" name="investment_amount" min="0" step="0.01"
                                                class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 nunito"
                                                placeholder="0.00" onchange="updateRemainingAmount()">
                                        </div>
                                    </div>
                                    
                                    <!-- SWF Contribution -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2 nunito">
                                            <i class="fas fa-hand-holding-usd text-green-600 mr-1"></i>
                                            Nachangia SWF
                                        </label>
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <p class="text-sm font-medium text-green-800 nunito">
                                                        Nachangia SWF
                                                    </p>
                                                </div>
                                                <div class="relative">
                                                    <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-500 text-xs font-medium nunito">TZS</span>
                                                    <input type="number" id="swf-amount" name="swf_amount" min="0" step="0.01"
                                                        class="w-32 pl-8 pr-2 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 nunito"
                                                        placeholder="0.00" onchange="updateRemainingAmount()">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Loan Repayment -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2 nunito">
                                            <i class="fas fa-money-check-alt text-yellow-600 mr-1"></i>
                                            Nalipa Rejesho La Mkopo
                                        </label>
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <p class="text-sm font-medium text-yellow-800 nunito">
                                                        Nalipa Rejesho La Mkopo
                                                    </p>
                                                </div>
                                                <div class="relative">
                                                    <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-500 text-xs font-medium nunito">TZS</span>
                                                    <input type="number" id="loan-repayment-amount" name="loan_repayment_amount" min="0" step="0.01"
                                                        class="w-32 pl-8 pr-2 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 nunito"
                                                        placeholder="0.00" onchange="updateRemainingAmount()">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cash Amount -->
                                <div class="mt-4 p-4 bg-white rounded-lg border border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 nunito">
                                                <i class="fas fa-money-bill-wave text-yellow-600 mr-1"></i>
                                                Cash (Furahia pesa yako)
                                            </p>
                                            <p class="text-xs text-gray-500 nunito">Hii itatumwa kwako</p>
                                        </div>
                                        <span class="text-xl font-bold text-yellow-600 nunito" id="cash-amount">TZS 0.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 nunito">Njia ya Malipo</h3>
                                <p class="text-sm text-gray-600 mb-4 nunito">Chagua njia unayotaka kupokea Cash yako</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <button type="button" onclick="selectPaymentMethod('bank')" id="bank-btn"
                                        class="payment-method-btn p-4 border-2 border-gray-300 rounded-lg hover:border-green-500 transition-colors text-left">
                                        <i class="fas fa-university text-gray-600 mr-2"></i>
                                        <span class="font-medium nunito">Benki (Bank Transfer)</span>
                                    </button>
                                    <button type="button" onclick="selectPaymentMethod('mobile')" id="mobile-btn"
                                        class="payment-method-btn p-4 border-2 border-gray-300 rounded-lg hover:border-green-500 transition-colors text-left">
                                        <i class="fas fa-mobile-alt text-gray-600 mr-2"></i>
                                        <span class="font-medium nunito">Simu ya Mkononi (Mobile Money)</span>
                                    </button>
                                </div>

                                <!-- Bank Details -->
                                <div id="bank-details" class="hidden space-y-3">
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                        <p class="text-green-700 font-medium nunito">
                                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                            Tunayo taarifa za benki yako. Tutatumia akaunti yako iliyosajiliwa.
                                        </p>
                                    </div>
                                </div>

                                <!-- Mobile Money Details -->
                                <div id="mobile-details" class="hidden space-y-3">
                                    <select id="mobile-network" name="mobile_network"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 nunito">
                                        <option value="">Chagua Mtandao</option>
                                        <option value="mix">Mix by Yas</option>
                                        <option value="halotel">Halotel</option>
                                    </select>
                                    <input type="tel" id="mobile-number" name="mobile_number" placeholder="0712345678"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 nunito">
                                    <p class="text-xs text-gray-500 nunito">Weka namba yako ya simu (mfano: 0712345678)</p>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2 nunito">
                                        <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                        Barua pepe
                                    </label>
                                    <input type="email" id="email" name="email" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 nunito"
                                        placeholder="barua.pepe@example.com">
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 nunito">
                                    <i class="fas fa-comment text-gray-400 mr-1"></i>
                                    Maelezo ya Ziada (Hiari)
                                </label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 nunito"
                                    placeholder="Maelezo yoyote ya ziada au maagizo maalum..."></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-6">
                                <button type="button" onclick="submitConfirmation()" 
                                    class="w-full bg-green-600 text-white py-4 px-6 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-bold text-lg nunito">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Thibitisha Malipo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="mb-4">
                    <i class="fas fa-spinner fa-spin text-green-600 text-4xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2 nunito">Inatuma Ombi Lako...</h3>
                <p class="text-gray-600 mb-4 nunito">Tafadhali subiri, tunakamilisha ombi lako</p>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                    <div id="progress-bar" class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p id="progress-text" class="text-sm text-gray-600 nunito">0%</p>
            </div>
        </div>
    </div>

    <!-- Success Popup -->
    <div id="success-popup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 transform scale-0 transition-transform duration-300" id="success-popup-content">
            <div class="text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-green-600 text-6xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2 nunito">Ombi Limekamilika!</h3>
                <p class="text-gray-600 mb-6 nunito">Email sent to your email. Thank you!</p>
                <button onclick="closeSuccessPopup()" 
                    class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 font-medium nunito">
                    Sawa
                </button>
            </div>
        </div>
    </div>

    <script>
        let totalAmount = 0;
        let selectedPaymentMethod = '';

        // Tab management (simplified since we only have one tab)
        function showTab(tabName) {
            // Only one tab, so no switching needed
        }

        // Reset form
        function resetForm() {
            document.getElementById('verification-form').reset();
            document.getElementById('payment-details').classList.add('hidden');
            document.getElementById('lookup-error').classList.add('hidden');
            closeModal('success-modal');
        }

        // Close modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
        }

        // Show success popup
        function showSuccessPopup() {
            const popup = document.getElementById('success-popup');
            const content = document.getElementById('success-popup-content');
            
            popup.classList.remove('hidden');
            popup.classList.add('flex');
            
            // Animate popup appearance
            setTimeout(() => {
                content.classList.remove('scale-0');
                content.classList.add('scale-100');
            }, 100);
        }

        // Close success popup
        function closeSuccessPopup() {
            const popup = document.getElementById('success-popup');
            const content = document.getElementById('success-popup-content');
            
            content.classList.remove('scale-100');
            content.classList.add('scale-0');
            
            setTimeout(() => {
                popup.classList.add('hidden');
                popup.classList.remove('flex');
            }, 300);
        }

        // Lookup membership
        async function lookupMembership() {
            const membershipCode = document.getElementById('membership-code').value.trim();
            
            if (!membershipCode) {
                showError('Tafadhali weka nambari ya uanachama');
                return;
            }

            const lookupBtn = document.getElementById('lookup-btn');
            const btnText = document.getElementById('lookup-btn-text');
            const originalText = btnText.textContent;
            
            // Show loading
            document.getElementById('lookup-loading').classList.remove('hidden');
            document.getElementById('lookup-error').classList.add('hidden');
            lookupBtn.disabled = true;
            btnText.textContent = 'Inatafuta...';

            try {
                const response = await fetch(`/fia/lookup-membership/${membershipCode}`);
                const data = await response.json();

                if (data.success) {
                    displayPaymentDetails(data.member);
                    document.getElementById('payment-details').classList.remove('hidden');
                } else {
                    showError(data.message || 'Nambari ya uanachama haipatikani');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Hitilafu wakati wa kutafuta uanachama. Tafadhali jaribu tena.');
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
            
            // Set email if available
            if (memberData.member_email) {
                document.getElementById('email').value = memberData.member_email;
            }

            // Calculate total amount from FIA record - show ALL payment items
            totalAmount = 0;
            const paymentItems = [];
            let kiasiBakiAmount = 0;
            
            // Add ALL payment amounts with proper labels
            if (memberData.gawio_la_fia && Number(memberData.gawio_la_fia) > 0) {
                paymentItems.push({ 
                    description: 'Gawio la FIA', 
                    amount: Number(memberData.gawio_la_fia) 
                });
            }
            
            if (memberData.fia_iliyokomaa && Number(memberData.fia_iliyokomaa) > 0) {
                paymentItems.push({ 
                    description: 'FIA Ilivyo Koma', 
                    amount: Number(memberData.fia_iliyokomaa) 
                });
            }
            
            if (memberData.jumla && Number(memberData.jumla) > 0) {
                paymentItems.push({ 
                    description: 'Jumla', 
                    amount: Number(memberData.jumla) 
                });
            }
            
            if (memberData.malipo_ya_vipande_yaliyokuwa_yamepelea && Number(memberData.malipo_ya_vipande_yaliyokuwa_yamepelea) > 0) {
                paymentItems.push({ 
                    description: 'Malipo ya Vipande', 
                    amount: Number(memberData.malipo_ya_vipande_yaliyokuwa_yamepelea) 
                });
            }
            
            if (memberData.loan && Number(memberData.loan) > 0) {
                paymentItems.push({ 
                    description: 'LOAN', 
                    amount: Number(memberData.loan) 
                });
            }
            
            // Kiasi Baki - This is the amount the member can actually request
            if (memberData.kiasi_baki && Number(memberData.kiasi_baki) > 0) {
                kiasiBakiAmount = Number(memberData.kiasi_baki);
                paymentItems.push({ 
                    description: 'Kiasi Baki', 
                    amount: kiasiBakiAmount 
                });
            }
            
            // Set total amount to ONLY Kiasi Baki - this is what the member can distribute
            totalAmount = kiasiBakiAmount;
            
            // Display payment breakdown
            const breakdownContainer = document.getElementById('payment-breakdown');
            breakdownContainer.innerHTML = '';
            
            if (paymentItems.length === 0) {
                breakdownContainer.innerHTML = '<p class="text-gray-500 nunito">Hakuna rekodi za malipo zilizopatikana kwa mwanachama huu.</p>';
            } else {
                paymentItems.forEach(payment => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex justify-between items-center py-2 border-b border-gray-200';
                    itemDiv.innerHTML = `
                        <div>
                            <p class="font-medium text-gray-800 nunito">${payment.description}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900 nunito">TSh ${payment.amount.toLocaleString('sw-TZ', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                        </div>
                    `;
                    breakdownContainer.appendChild(itemDiv);
                });
            }
            
            // Update amounts
            document.getElementById('total-amount').textContent = `TZS ${totalAmount.toLocaleString('sw-TZ', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            document.getElementById('remaining-amount').textContent = `TZS ${totalAmount.toLocaleString('sw-TZ', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            updateRemainingAmount();
        }

        // Update remaining amount
        function updateRemainingAmount() {
            const savingsAmount = Number(document.getElementById('savings-amount').value) || 0;
            const investmentAmount = Number(document.getElementById('investment-amount').value) || 0;
            const swfAmount = Number(document.getElementById('swf-amount').value) || 0;
            const loanRepaymentAmount = Number(document.getElementById('loan-repayment-amount').value) || 0;
            
            const totalDeductions = savingsAmount + investmentAmount + swfAmount + loanRepaymentAmount;
            const cashAmount = totalAmount - totalDeductions;
            
            document.getElementById('cash-amount').textContent = `TZS ${Math.max(0, cashAmount).toLocaleString('sw-TZ', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            
            // Update colors based on amounts
            if (cashAmount < 0) {
                document.getElementById('cash-amount').className = 'text-xl font-bold text-red-600 nunito';
            } else if (cashAmount === 0) {
                document.getElementById('cash-amount').className = 'text-xl font-bold text-yellow-600 nunito';
            } else {
                document.getElementById('cash-amount').className = 'text-xl font-bold text-yellow-600 nunito';
            }
        }

        // Select payment method
        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;
            
            // Reset all buttons
            document.querySelectorAll('.payment-method-btn').forEach(btn => {
                btn.classList.remove('border-green-500', 'bg-green-50');
                btn.classList.add('border-gray-300');
            });
            
            // Highlight selected button
            const selectedBtn = document.getElementById(`${method}-btn`);
            selectedBtn.classList.remove('border-gray-300');
            selectedBtn.classList.add('border-green-500', 'bg-green-50');
            
            // Show/hide relevant details
            document.getElementById('bank-details').classList.add('hidden');
            document.getElementById('mobile-details').classList.add('hidden');
            
            if (method === 'bank') {
                document.getElementById('bank-details').classList.remove('hidden');
            } else if (method === 'mobile') {
                document.getElementById('mobile-details').classList.remove('hidden');
            }
        }

        // Submit confirmation
        async function submitConfirmation() {
            try {
                // Check if totalAmount is set
                if (totalAmount === 0) {
                    alert('Tafadhali tafuta mwanachama kwanza.');
                    return;
                }

                const savingsAmount = Number(document.getElementById('savings-amount').value) || 0;
                const investmentAmount = Number(document.getElementById('investment-amount').value) || 0;
                const swfAmount = Number(document.getElementById('swf-amount').value) || 0;
                const loanRepaymentAmount = Number(document.getElementById('loan-repayment-amount').value) || 0;
                
                const totalDeductions = savingsAmount + investmentAmount + swfAmount + loanRepaymentAmount;
                const cashAmount = totalAmount - totalDeductions;
                
                if (cashAmount < 0) {
                    alert('Mgawanyo wako umepita kiasi kilichopo. Tafadhali punguza akiba au uwekezaji.');
                    return;
                }
                
                if (!selectedPaymentMethod) {
                    alert('Tafadhali chagua njia ya malipo.');
                    return;
                }
                
                // Validate payment method details
                if (selectedPaymentMethod === 'mobile') {
                    if (!document.getElementById('mobile-network').value || !document.getElementById('mobile-number').value) {
                        alert('Tafadhali jaza maelezo yote ya simu ya mkononi.');
                        return;
                    }
                }
                
                // Validate email
                const email = document.getElementById('email').value;
                if (!email) {
                    alert('Tafadhali weka barua pepe.');
                    return;
                }
                
                // Prepare form data
                const formData = {
                    member_id: document.getElementById('member-id').value,
                    amount_to_pay: totalAmount,
                    savings_amount: savingsAmount,
                    investment_amount: investmentAmount,
                    cash_amount: cashAmount,
                    payment_method: selectedPaymentMethod,
                    email: document.getElementById('email').value,
                    phone: '', // Phone field removed
                    notes: document.getElementById('notes').value,
                    fia_type: document.getElementById('fia-type').value,
                    swf_amount: document.getElementById('swf-amount').value,
                    loan_repayment_amount: document.getElementById('loan-repayment-amount').value
                };
                
                // Add payment method specific details
                if (selectedPaymentMethod === 'mobile') {
                    formData.mobile_network = document.getElementById('mobile-network').value;
                    formData.mobile_number = document.getElementById('mobile-number').value;
                }
                
                // Show loading with splash progress
                document.getElementById('loading-overlay').classList.remove('hidden');
                document.getElementById('loading-overlay').classList.add('flex');
                
                // Start splash progress from 0-100
                let progress = 0;
                const progressBar = document.getElementById('progress-bar');
                const progressText = document.getElementById('progress-text');
                
                const progressInterval = setInterval(() => {
                    progress += Math.random() * 15; // Random increment for realistic effect
                    if (progress >= 100) {
                        progress = 100;
                        clearInterval(progressInterval);
                    }
                    
                    progressBar.style.width = progress + '%';
                    progressText.textContent = Math.round(progress) + '%';
                }, 200);
                
                const response = await fetch('/fia/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                // Ensure progress reaches 100%
                clearInterval(progressInterval);
                progressBar.style.width = '100%';
                progressText.textContent = '100%';

                setTimeout(() => {
                    document.getElementById('loading-overlay').classList.add('hidden');
                    document.getElementById('loading-overlay').classList.remove('flex');

                    if (result.status === 'success') {
                        // Show success popup
                        showSuccessPopup();
                        // Reset form
                        resetForm();
                    } else {
                        alert('Hitilafu: ' + result.message);
                    }
                }, 500);

            } catch (error) {
                console.error('Submit error:', error);
                alert('Hitilafu wakati wa kutuma: ' + error.message);
            }
        }

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
