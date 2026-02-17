@extends('layouts.app')

@push('styles')
<style>
    :root {
        --primary-color: #015425;
        --primary-dark: #013019;
        --primary-light: #027a3a;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-600: #4b5563;
        --gray-800: #1f2937;
    }

    .payment-container {
        background: linear-gradient(135deg, #013019 0%, #015425 25%, #027a3a 50%, #015425 75%, #013019 100%);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding: 2rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .payment-card {
        backdrop-filter: blur(20px) saturate(180%);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        border-radius: 1rem;
        padding: 2rem;
        max-width: 800px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .input-group {
        position: relative;
        margin-bottom: 1rem;
    }

    .input-field {
        width: 100%;
        padding: 0.875rem 0.75rem;
        font-size: 14px;
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }

    .input-field:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(1, 84, 37, 0.1);
    }

    .input-label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--gray-600);
        font-size: 13px;
        font-weight: 600;
    }

    .btn-submit {
        width: 100%;
        padding: 0.75rem 1.5rem;
        font-size: 14px;
        font-weight: 600;
        color: white;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 4px 15px rgba(1, 84, 37, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(1, 84, 37, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .balance-display {
        background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .distribution-item {
        background: var(--gray-50);
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        border: 2px solid var(--gray-200);
    }

    .distribution-item.active {
        border-color: var(--primary-color);
        background: rgba(1, 84, 37, 0.05);
    }

    .total-display {
        background: var(--gray-100);
        padding: 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        margin-top: 1rem;
    }

    .total-display.valid {
        background: #d1fae5;
        color: #065f46;
    }

    .total-display.invalid {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Splash Screen */
    .splash-screen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(1, 48, 25, 0.95);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        flex-direction: column;
    }

    .splash-screen.active {
        display: flex;
    }

    .splash-progress {
        font-size: 3rem;
        font-weight: bold;
        color: white;
        margin-bottom: 1rem;
    }

    .splash-message {
        color: white;
        font-size: 1.2rem;
        text-align: center;
    }

    /* Success Modal */
    .success-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    }

    .success-modal.active {
        display: flex;
    }

    .success-modal-content {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        max-width: 500px;
        width: 90%;
        text-align: center;
    }

    .error-message {
        font-size: 12px;
        color: #dc2626;
        margin-top: 0.25rem;
    }

    .notification {
        padding: 0.75rem;
        border-radius: 0.5rem;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .notification.success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .notification.error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .cursor-not-allowed {
        cursor: not-allowed;
    }

    .bg-gray-50 {
        background-color: #f9fafb !important;
    }
</style>
@endpush

@section('content')
<div class="payment-container">
    <div class="payment-card">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-[#015425] mb-2">Uthibitishaji wa Malipo</h1>
            <p class="text-sm text-gray-600">Thibitisha mgawanyo wa malipo yako</p>
        </div>

        <!-- Member ID Lookup Section -->
        <div id="lookupSection">
            <div class="input-group">
                <label for="member_id" class="input-label">Namba ya Uanachama (Member ID)</label>
                <input 
                    type="text" 
                    id="member_id" 
                    name="member_id" 
                    class="input-field" 
                    placeholder="Weka namba yako ya uanachama"
                    required
                >
                <div id="member_id_error" class="error-message"></div>
            </div>
            <button 
                type="button" 
                id="lookupBtn" 
                class="btn-submit"
            >
                Tafuta Mwanachama
            </button>
        </div>

        <!-- Member Info Display -->
        <div id="memberInfo" style="display: none;">
            <div class="balance-display text-center">
                <!-- <div class="text-sm mb-2">Taarifa za Mwanachama</div> -->
                <div class="text-2xl font-bold mb-1" id="memberName"></div>
                <div class="text-sm opacity-90" id="memberDetails"></div>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <div class="text-sm opacity-90 uppercase tracking-wide">Hongera Gawio lako ni</div>
                    <div class="text-4xl font-extrabold mt-1">TZS <span id="gawioAmount">0.00</span></div>
                </div>
            </div>

            <!-- Payment Form -->
            <form id="paymentForm" style="display: none;">
                <input type="hidden" id="user_id" name="user_id">
                <input type="hidden" id="member_id_hidden" name="member_id">
                
                <!-- Hidden Amount Input as it is displayed in header -->
                <input type="hidden" id="amount_to_pay" name="amount_to_pay">
                
                <div class="mb-6">
                    <label for="member_email" class="input-label">Barua Pepe (Email)</label>
                    <input 
                        type="email" 
                        id="member_email" 
                        name="member_email" 
                        class="input-field" 
                        placeholder="Weka barua pepe yako"
                        required
                    >
                    <div id="member_email_error" class="error-message"></div>
                </div>

                <!-- SECTION 1: Madeni unayo daiwa -->
                <div class="mb-8 p-4 bg-red-50 rounded-lg border border-red-100">
                    <div class="text-lg font-bold mb-3 text-red-800 border-b border-red-200 pb-2">Madeni unayo daiwa</div>
                    
                    <!-- SWF -->
                    <div class="distribution-item bg-white/50">
                        <label class="block text-sm font-semibold mb-1 text-red-700">Social Welfare (SWF)</label>
                        <input type="number" id="swf_contribution" name="swf_contribution" class="input-field bg-gray-50 cursor-not-allowed font-bold" placeholder="0.00" step="0.01" min="0" readonly>
                    </div>

                    <!-- Loan -->
                    <div class="distribution-item bg-white/50">
                        <label class="block text-sm font-semibold mb-1 text-red-700">Rejesho la Mkopo</label>
                        <input type="number" id="loan_repayment" name="loan_repayment" class="input-field bg-gray-50 cursor-not-allowed font-bold" placeholder="0.00" step="0.01" min="0" readonly>
                    </div>

                    <!-- Capital -->
                    <div class="distribution-item bg-white/50">
                        <label class="block text-sm font-semibold mb-1 text-red-700">Hisa FeedTan</label>
                        <input type="number" id="capital_contribution" name="capital_contribution" class="input-field bg-gray-50 cursor-not-allowed font-bold" placeholder="0.00" step="0.01" min="0" readonly>
                    </div>

                    <!-- Fine -->
                    <div class="distribution-item bg-white/50">
                        <label class="block text-sm font-semibold mb-1 text-red-700">Fine/Penalty</label>
                        <input type="number" id="fine_penalty" name="fine_penalty" class="input-field bg-gray-50 cursor-not-allowed font-bold" placeholder="0.00" step="0.01" min="0" readonly>
                    </div>

                    <!-- Total Deductions Display -->
                    <div class="flex justify-between items-center font-bold text-red-800 mt-2 px-1">
                        <span>Jumla ya Madeni:</span>
                        <span id="totalDeductions" class="text-xl">TZS 0.00</span>
                    </div>
                </div>

                <!-- SECTION 2: Kiasi Kilichobaki -->
                <div class="mb-8 p-4 bg-green-50 rounded-lg border border-green-100 text-center">
                    <div class="text-sm font-semibold text-green-800 uppercase">Kiasi Kilichobaki</div>
                    <div class="text-3xl font-bold text-green-900 mt-1" id="remainingBalanceDisplay">TZS 0.00</div>
                    <div class="text-xs text-green-700 mt-1">Hiki ndicho kiasi unachoweza kugawa hapa chini</div>
                </div>

                <!-- SECTION 3: Mgawanyo wa Salio -->
                <div class="mb-4">
                    <div class="text-lg font-bold mb-3 text-[#015425] border-b border-[#015425]/20 pb-2">Sasa unaweza gawa Kiasi kinachobaki utakavyo</div>
                    <p class="text-sm text-gray-600 mb-4 bg-blue-50 p-3 rounded border-l-4 border-blue-500">
                        Tunashauri weka 20%-30% au zaidi kama akiba. Endelea na mgawanyo.
                    </p>

                    <!-- Akiba (Re-deposit) -->
                    <div class="distribution-item">
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="checkbox" id="enable_re_deposit" class="w-4 h-4 text-[#015425]">
                            <span class="font-semibold">Akiba (Ongeza kwenye Akiba yako)</span>
                        </label>
                        <input type="number" id="re_deposit" name="re_deposit" class="input-field" placeholder="0.00" step="0.01" min="0" disabled>
                    </div>

                    <!-- FIA Investment -->
                    <div class="distribution-item">
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="checkbox" id="enable_fia" class="w-4 h-4 text-[#015425]">
                            <span class="font-semibold">Uwekezaji (Vipande FIA)</span>
                        </label>
                        <select id="fia_type" name="fia_type" class="input-field mb-2" disabled>
                            <option value="">Chagua Aina ya FIA</option>
                            <option value="4_year">Uwekezaji wa Miaka 4</option>
                            <option value="6_year">Uwekezaji wa Miaka 6</option>
                        </select>
                        <input type="number" id="fia_investment" name="fia_investment" class="input-field" placeholder="0.00" step="0.01" min="0" disabled>
                    </div>

                    <!-- Cash (Remaining) -->
                    <div class="distribution-item bg-green-100 border-green-300">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold text-lg text-green-900">Cash (Furahia pesa yako)</span>
                            <span class="text-xs bg-green-200 text-green-800 px-2 py-1 rounded">Hii itatumwa kwako</span>
                        </div>
                        <div class="text-2xl font-bold text-green-800 text-right" id="cashAmountDisplay">TZS 0.00</div>
                        <input type="hidden" id="cash_amount" name="cash_amount">
                        <div id="cash_error" class="error-message text-right"></div>
                    </div>
                </div>

                <!-- Payment Method Section -->
                <div class="mb-4 mt-6 pt-6 border-t border-gray-200">
                    <div class="text-lg font-semibold mb-3 text-[#015425]">Njia ya Malipo</div>
                    <div class="text-sm text-gray-600 mb-4">Chagua njia unayotaka kupokea Cash yako</div>
                    
                    <!-- Payment Method Selection -->
                <div class="flex justify-between items-center mb-2">
                    <label class="input-label mb-0">Chagua Njia ya Malipo</label>
                    <button type="button" id="clearPaymentMethod" class="text-xs text-red-600 hover:underline">Clear Selection</button>
                </div>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="radio" 
                            name="payment_method" 
                            value="bank" 
                            id="payment_method_bank"
                            class="w-4 h-4 text-[#015425]"
                        >
                        <span>Benki (Bank Transfer)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="radio" 
                            name="payment_method" 
                            value="mobile" 
                            id="payment_method_mobile"
                            class="w-4 h-4 text-[#015425]"
                        >
                        <span>Simu ya Mkononi (Mobile Money)</span>
                    </label>
                </div>
                        <div id="payment_method_error" class="error-message"></div>
                    </div>

                    <!-- Bank Account Fields -->
                    <div id="bankFields" style="display: none;">
                        <div class="mb-4">
                            <label for="bank_account_number" class="input-label">Bank Account Number</label>
                            <input 
                                type="text" 
                                id="bank_account_number" 
                                name="bank_account_number" 
                                class="input-field" 
                                placeholder="Enter your bank account number"
                            >
                            <div id="bank_account_number_error" class="error-message"></div>
                        </div>
                    </div>

                    <!-- Mobile Money Fields -->
                    <div id="mobileFields" style="display: none;">
                        <div class="mb-4">
                            <label for="mobile_provider" class="input-label">Mtandao wa Simu</label>
                            <select 
                                id="mobile_provider" 
                                name="mobile_provider" 
                                class="input-field"
                            >
                                <option value="">Chagua Mtandao</option>
                                <option value="halopesa">HaloPesa</option>
                                <option value="yas">Yas</option>
                            </select>
                            <div id="mobile_provider_error" class="error-message"></div>
                        </div>
                        <div class="mb-4">
                            <label for="mobile_number" class="input-label">Mobile Number</label>
                            <input 
                                type="text" 
                                id="mobile_number" 
                                name="mobile_number" 
                                class="input-field" 
                                placeholder="Weka namba yako ya simu (mfano: 0712345678)"
                                maxlength="20"
                            >
                            <div class="text-xs text-gray-600 mt-1">Weka namba bila kodi ya nchi ongeza Yas</div>
                            <div id="mobile_number_error" class="error-message"></div>
                        </div>
                    </div>
                </div>


                <button 
                    type="submit" 
                    id="submitBtn" 
                    class="btn-submit mt-6"
                    disabled
                >
                    Thibitisha Malipo
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Splash Screen -->
<div class="splash-screen" id="splashScreen">
    <div class="splash-progress" id="splashProgress">0%</div>
    <div class="splash-message" id="splashMessage">Tunashughulikia ombi lako...</div>
</div>

<!-- Success Modal -->
<div class="success-modal" id="successModal">
    <div class="success-modal-content">
        <div class="mb-4">
            <svg class="w-16 h-16 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold mb-2 text-[#015425]">Uthibitisho Umekamilika!</h2>
        <p class="text-gray-600 mb-4">Ombi lako limepokelewa na kufanyiwa kazi. Tumekutumia muhtasari wa malipo haya kwenye barua pepe yako (E-mail) kwa ajili ya kumbukumbu zako.</p>
        <button 
            type="button" 
            onclick="location.reload()" 
            class="btn-submit"
        >
            Wasilisha Nyingine
        </button>
    </div>
</div>

<!-- Error Modal -->
<div class="success-modal" id="errorModal">
    <div class="success-modal-content">
        <div class="mb-4">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-2 text-gray-800">Taarifa ya Mfumo</h2>
        <p class="text-gray-600 mb-6" id="errorModalMessage"></p>
        <button 
            type="button" 
            onclick="document.getElementById('errorModal').classList.remove('active')" 
            class="w-full py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-colors shadow-lg"
        >
            Funga
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const memberIdInput = document.getElementById('member_id');
    const lookupBtn = document.getElementById('lookupBtn');
    const lookupSection = document.getElementById('lookupSection');
    const memberInfo = document.getElementById('memberInfo');
    const paymentForm = document.getElementById('paymentForm');
    const zeroBalanceMessage = document.getElementById('zeroBalanceMessage');
    const splashScreen = document.getElementById('splashScreen');
    const splashProgress = document.getElementById('splashProgress');
    const successModal = document.getElementById('successModal');
    const errorModal = document.getElementById('errorModal');
    const errorModalMessage = document.getElementById('errorModalMessage');
    const submitBtn = document.getElementById('submitBtn');

    let memberData = null;

    // Lookup member
    lookupBtn.addEventListener('click', async function() {
        const memberId = memberIdInput.value.trim();
        if (!memberId) {
            showError('member_id_error', 'Please enter a member ID');
            return;
        }

        lookupBtn.disabled = true;
        lookupBtn.textContent = 'Looking up...';

        try {
            const response = await fetch('{{ route("payment-confirmation.lookup") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ member_id: memberId })
            });

            const data = await response.json();

            if (data.success) {
                memberData = data.member;
                
                // Handle user_id (may be null for unregistered members)
                document.getElementById('user_id').value = memberData.id || '';
                document.getElementById('member_id_hidden').value = memberData.member_id;
                document.getElementById('memberName').textContent = memberData.name;
                document.getElementById('memberDetails').textContent = `${memberData.member_id} - ${memberData.member_type}`;
                document.getElementById('member_email').value = memberData.email || '';
                
                // If there's an existing payment confirmation with amount_to_pay, pre-fill it
                if (memberData.amount_to_pay) {
                    document.getElementById('amount_to_pay').value = memberData.amount_to_pay;
                    document.getElementById('gawioAmount').textContent = parseFloat(memberData.amount_to_pay).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }

                // Pre-fill distribution fields if available
                const fields = [
                    { key: 'swf_contribution', id: 'swf_contribution' },
                    { key: 'capital_contribution', id: 'capital_contribution' },
                    { key: 'loan_repayment', id: 'loan_repayment' },
                    { key: 'fine_penalty', id: 'fine_penalty' }
                ];

                fields.forEach(field => {
                    const value = parseFloat(memberData[field.key]) || 0;
                    const input = document.getElementById(field.id);
                    input.value = value;
                });

                // Pre-fill Allocation fields
                const allocationFields = [
                    { key: 're_deposit', id: 're_deposit', check: 'enable_re_deposit' },
                    { key: 'fia_investment', id: 'fia_investment', check: 'enable_fia' }
                ];

                allocationFields.forEach(field => {
                    const value = parseFloat(memberData[field.key]) || 0;
                    if (value > 0) {
                        document.getElementById(field.check).checked = true;
                        const input = document.getElementById(field.id);
                        input.disabled = false;
                        input.value = value;
                        
                        if (field.key === 'fia_investment' && memberData.fia_type) {
                            const fiaSelect = document.getElementById('fia_type');
                            fiaSelect.disabled = false;
                            fiaSelect.value = memberData.fia_type;
                        }
                    }
                });

                lookupSection.style.display = 'none';
                memberInfo.style.display = 'block';
                paymentForm.style.display = 'block';
                
                // Update total immediately
                updateTotal();
            } else {
                showError('member_id_error', data.message || 'Member not found');
            }
        } catch (error) {
            showError('member_id_error', 'An error occurred. Please try again.');
        } finally {
            lookupBtn.disabled = false;
            lookupBtn.textContent = 'Tafuta Mwanachama';
        }
    });

    // Enable/disable allocation inputs
    ['re_deposit', 'fia'].forEach(type => {
        const checkbox = document.getElementById(`enable_${type}`);
        const input = document.getElementById(type === 're_deposit' ? 're_deposit' : 'fia_investment');
        const select = type === 'fia' ? document.getElementById('fia_type') : null;

        checkbox.addEventListener('change', function() {
            input.disabled = !this.checked;
            if (select) select.disabled = !this.checked;
            if (!this.checked) {
                input.value = '';
                if (select) select.value = '';
            }
            updateTotal();
        });
    });

    // Update total on input change
    ['amount_to_pay', 'swf_contribution', 're_deposit', 'fia_investment', 'capital_contribution', 'loan_repayment', 'fine_penalty'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', updateTotal);
    });

    // Payment method selection handlers
    const paymentMethodBank = document.getElementById('payment_method_bank');
    const paymentMethodMobile = document.getElementById('payment_method_mobile');
    const bankFields = document.getElementById('bankFields');
    const mobileFields = document.getElementById('mobileFields');
    const bankAccountNumber = document.getElementById('bank_account_number');
    const mobileProvider = document.getElementById('mobile_provider');
    const mobileNumber = document.getElementById('mobile_number');

    // Handle payment method selection
    [paymentMethodBank, paymentMethodMobile].forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'bank') {
                bankFields.style.display = 'block';
                mobileFields.style.display = 'none';
                // Clear mobile fields
                mobileProvider.value = '';
                mobileNumber.value = '';
                clearError('mobile_provider_error');
                clearError('mobile_number_error');
            } else if (this.value === 'mobile') {
                bankFields.style.display = 'none';
                mobileFields.style.display = 'block';
                // Clear bank fields
                bankAccountNumber.value = '';
                clearError('bank_account_number_error');
            }
            validatePaymentMethod();
            checkFormValidity();
        });
    });

    // Clear payment method selection
    document.getElementById('clearPaymentMethod').addEventListener('click', function() {
        paymentMethodBank.checked = false;
        paymentMethodMobile.checked = false;
        bankFields.style.display = 'none';
        mobileFields.style.display = 'none';
        
        // Clear all fields
        bankAccountNumber.value = '';
        mobileProvider.value = '';
        mobileNumber.value = '';
        
        // Clear all errors
        clearError('payment_method_error');
        clearError('bank_account_number_error');
        clearError('mobile_provider_error');
        clearError('mobile_number_error');
        
        checkFormValidity();
    });

    // Bank account validation removed check for confirmation
    bankAccountNumber.addEventListener('input', function() {
        const accountNumber = this.value.trim();
        const errorElement = document.getElementById('bank_account_number_error');
        
        if (!accountNumber && paymentMethodBank.checked) {
            errorElement.textContent = 'Bank account number is required';
            this.style.borderColor = '#dc2626';
        } else {
            errorElement.textContent = '';
            this.style.borderColor = '';
        }
        checkFormValidity();
    });

    // Validate mobile fields
    mobileProvider.addEventListener('change', function() {
        const errorElement = document.getElementById('mobile_provider_error');
        if (!this.value && paymentMethodMobile.checked) {
            errorElement.textContent = 'Please select a mobile money provider';
            this.style.borderColor = '#dc2626';
        } else {
            errorElement.textContent = '';
            this.style.borderColor = '';
        }
        checkFormValidity();
    });

    mobileNumber.addEventListener('input', function() {
        const errorElement = document.getElementById('mobile_number_error');
        const value = this.value.trim();
        
        if (!value && paymentMethodMobile.checked) {
            errorElement.textContent = 'Mobile number is required';
            this.style.borderColor = '#dc2626';
        } else if (value && !/^[0-9]{9,12}$/.test(value)) {
            errorElement.textContent = 'Please enter a valid mobile number (9-12 digits)';
            this.style.borderColor = '#dc2626';
        } else {
            errorElement.textContent = '';
            this.style.borderColor = '';
        }
        checkFormValidity();
    });

    function validatePaymentMethod() {
        const errorElement = document.getElementById('payment_method_error');
        const cashValue = parseFloat(document.getElementById('cash_amount').value) || 0;
        
        // Only require payment method if cash > 0
        if (cashValue > 0.01) {
            if (!paymentMethodBank.checked && !paymentMethodMobile.checked) {
                errorElement.textContent = 'Please select a payment method for your cash payout';
                return false;
            }
        } else {
            // Not needed if no cash
            errorElement.textContent = '';
            return true;
        }
        
        errorElement.textContent = '';
        
        // Validate bank fields if bank is selected
        if (paymentMethodBank.checked) {
            if (!accountNumber) {
                showError('bank_account_number_error', 'Bank account number is required');
                return false;
            }
            
            // Clear errors if valid
            clearError('bank_account_number_error');
        }
        
        // Validate mobile fields if mobile is selected
        if (paymentMethodMobile.checked) {
            const provider = mobileProvider.value;
            const mobile = mobileNumber.value.trim();
            
            if (!provider) {
                showError('mobile_provider_error', 'Please select a mobile money provider');
                return false;
            }
            
            if (!mobile) {
                showError('mobile_number_error', 'Mobile number is required');
                return false;
            }
            
            if (!/^[0-9]{9,12}$/.test(mobile)) {
                showError('mobile_number_error', 'Please enter a valid mobile number (9-12 digits)');
                return false;
            }
            
            // Clear errors if valid
            clearError('mobile_provider_error');
            clearError('mobile_number_error');
        }
        
        return true;
    }

    // Validate amount to pay
    document.getElementById('amount_to_pay').addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        const errorElement = document.getElementById('amount_to_pay_error');
        
        if (amount <= 0) {
            errorElement.textContent = 'Amount must be greater than 0';
            this.style.borderColor = '#dc2626';
        } else {
            errorElement.textContent = '';
            this.style.borderColor = '';
        }
        updateTotal();
    });

    function updateTotal() {
        const amountToPay = parseFloat(document.getElementById('amount_to_pay').value) || 0;
        
        // Madeni (Deductions)
        const swf = parseFloat(document.getElementById('swf_contribution').value) || 0;
        const loan = parseFloat(document.getElementById('loan_repayment').value) || 0;
        const capital = parseFloat(document.getElementById('capital_contribution').value) || 0;
        const fine = parseFloat(document.getElementById('fine_penalty').value) || 0;
        
        const totalDeductions = swf + loan + capital + fine;
        document.getElementById('totalDeductions').textContent = `TZS ${totalDeductions.toLocaleString('en-US', {minimumFractionDigits: 2})}`;

        // Remaining Balance for user to split
        const remainingBalance = amountToPay - totalDeductions;
        document.getElementById('remainingBalanceDisplay').textContent = `TZS ${remainingBalance.toLocaleString('en-US', {minimumFractionDigits: 2})}`;

        // Allocation options
        const reDeposit = parseFloat(document.getElementById('re_deposit').value) || 0;
        const fia = parseFloat(document.getElementById('fia_investment').value) || 0;
        
        // Cash is the remainder of the Remaining Balance (after re-deposit and fia)
        const cash = remainingBalance - (reDeposit + fia);
        
        document.getElementById('cashAmountDisplay').textContent = `TZS ${Math.max(0, cash).toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        document.getElementById('cash_amount').value = Math.max(0, cash);

        const cashError = document.getElementById('cash_error');
        const submitBtn = document.getElementById('submitBtn');

        if (cash < -0.01) { // Allowing small float error
            cashError.textContent = 'Umeweka kiasi kikubwa kuliko salio lililobaki.';
            submitBtn.disabled = true;
        } else {
            cashError.textContent = '';
            // Only enable if logic allows (method check called in checkFormValidity)
            checkFormValidity();
        }
    }

    function clearError(elementId) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = '';
        }
    }

    function checkFormValidity() {
        // Calculate current values
        const amountToPay = parseFloat(document.getElementById('amount_to_pay').value) || 0;
        
        // Madeni
        const swf = parseFloat(document.getElementById('swf_contribution').value) || 0;
        const loan = parseFloat(document.getElementById('loan_repayment').value) || 0;
        const capital = parseFloat(document.getElementById('capital_contribution').value) || 0;
        const fine = parseFloat(document.getElementById('fine_penalty').value) || 0;
        
        // Allocated
        const reDeposit = parseFloat(document.getElementById('re_deposit').value) || 0;
        const fia = parseFloat(document.getElementById('fia_investment').value) || 0;
        
        const totalAllocated = swf + loan + capital + fine + reDeposit + fia;
        const cashValue = amountToPay - totalAllocated;

        // Valid if cash is non-negative (allowing small float error)
        const distributionValid = cashValue >= -0.01 && amountToPay > 0;
        
        // Payment method required only if cash > 0
        let paymentMethodValid = true;
        if (cashValue > 0.01) {
            paymentMethodValid = validatePaymentMethod();
        } else {
             // If no cash payout, method is valid (not needed)
             // But we might want to clear errors?
             // clearError('payment_method_error');
             paymentMethodValid = true;
        }
        
        submitBtn.disabled = !(distributionValid && paymentMethodValid);
    }

    // Form submission
    document.getElementById('paymentForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear all previous errors
        document.querySelectorAll('.error-message').forEach(el => {
            el.textContent = '';
        });

        // Validate form before submission
        const amountToPay = parseFloat(document.getElementById('amount_to_pay').value) || 0;
        const swf = parseFloat(document.getElementById('swf_contribution').value) || 0;
        const reDeposit = parseFloat(document.getElementById('re_deposit').value) || 0;
        const fia = parseFloat(document.getElementById('fia_investment').value) || 0;
        const capital = parseFloat(document.getElementById('capital_contribution').value) || 0;
        const loan = parseFloat(document.getElementById('loan_repayment').value) || 0;
        const fine = parseFloat(document.getElementById('fine_penalty').value) || 0;
        const total = swf + reDeposit + fia + capital + loan + fine;
        const difference = Math.abs(amountToPay - total);
        
        // Validate distribution
        if (amountToPay <= 0) {
            showModalError('Kiwango cha malipo lazima kiwe zaidi ya 0.');
            return;
        }

        const cashValue = parseFloat(document.getElementById('cash_amount').value) || 0;
        if (cashValue < 0) {
            showModalError('Umeweka kiasi kikubwa kuliko salio.');
            return;
        }
        
        // Validate payment method only if receiving cash
        if (cashValue > 0 && !validatePaymentMethod()) {
            // Scroll to first error
            const firstError = document.querySelector('.error-message:not(:empty)');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }

        // Show splash screen
        splashScreen.classList.add('active');
        let progress = 0;
        const interval = setInterval(() => {
            if (progress < 98) {
                progress += 2;
                splashProgress.textContent = progress + '%';
            }
        }, 100);

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        // Get payment method data
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
        
        if (cashValue > 0 && !paymentMethod) {
            clearInterval(interval);
            splashScreen.classList.remove('active');
            showError('payment_method_error', 'Tafadhali chagua njia ya malipo');
            return;
        }
        
        if (!paymentMethod && cashValue <= 0) {
             data.payment_method = 'bank'; // Dummy
             data.bank_account_number = 'N/A';
             data.bank_account_confirmation = 'N/A';
        } else {
             data.payment_method = paymentMethod;
        }
        
        if (data.payment_method === 'bank' && cashValue > 0) {
            if (!accountNumber) {
                showError('bank_account_number_error', 'Bank account number is required');
                return false;
            }
            
            // Clear errors if valid
            clearError('bank_account_number_error');
        }
            // Clear mobile fields
            data.mobile_provider = '';
            data.mobile_number = '';
        } else if (paymentMethod === 'mobile') {
            const provider = mobileProvider.value;
            const mobile = mobileNumber.value.trim();
            
            if (!provider || !mobile || !/^[0-9]{9,12}$/.test(mobile)) {
                clearInterval(interval);
                splashScreen.classList.remove('active');
                showError('mobile_number_error', 'Please enter a valid mobile number');
                return;
            }
            
            data.mobile_provider = provider;
            data.mobile_number = mobile;
            // Clear bank fields
            data.bank_account_number = '';
            data.bank_account_confirmation = '';
        }
        
        // Convert to numbers
        data.swf_contribution = parseFloat(data.swf_contribution) || 0;
        data.re_deposit = parseFloat(data.re_deposit) || 0;
        data.fia_investment = parseFloat(data.fia_investment) || 0;
        data.capital_contribution = parseFloat(data.capital_contribution) || 0;
        data.loan_repayment = parseFloat(data.loan_repayment) || 0;
        data.fine_penalty = parseFloat(data.fine_penalty) || 0;
        data.amount_to_pay = parseFloat(data.amount_to_pay);

        try {
            const response = await fetch('{{ route("payment-confirmation.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            let result;
            try {
                result = await response.json();
            } catch (jsonError) {
                console.error('JSON parsing error:', jsonError);
                throw new Error('Server returned an invalid response. Please contact the administrator.');
            }

            // Complete progress
            clearInterval(interval);
            splashProgress.textContent = '100%';

            setTimeout(() => {
                splashScreen.classList.remove('active');
                
                if (result.success) {
                    successModal.classList.add('active');
                } else {
                    // Handle validation errors
                    if (response.status === 422 && result.errors) {
                        // Clear all previous errors
                        document.querySelectorAll('.error-message').forEach(el => {
                            el.textContent = '';
                        });
                        
                        // Display field-specific errors
                        Object.keys(result.errors).forEach(field => {
                            const errorMessages = result.errors[field];
                            const errorMessage = Array.isArray(errorMessages) ? errorMessages[0] : errorMessages;
                            
                            // Map field names to error element IDs
                            const fieldMap = {
                                'payment_method': 'payment_method_error',
                                'bank_account_number': 'bank_account_number_error',
                                'mobile_provider': 'mobile_provider_error',
                                'mobile_number': 'mobile_number_error',
                                'member_email': 'member_email_error',
                                'amount_to_pay': 'amount_to_pay_error',
                                'swf_contribution': 'swf_contribution_error',
                                're_deposit': 're_deposit_error',
                                'fia_investment': 'fia_investment_error',
                                'capital_contribution': 'capital_contribution_error',
                                'loan_repayment': 'loan_repayment_error',
                                'fine_penalty': 'fine_penalty_error',
                                'user_id': 'member_id_error',
                                'fia_type': 'fia_investment_error',
                            };
                            
                            const errorElementId = fieldMap[field];
                            if (errorElementId) {
                                showError(errorElementId, errorMessage);
                            } else {
                                // Show general error if field not mapped
                                alert(errorMessage);
                            }
                        });
                        
                        // Scroll to first error
                        const firstError = document.querySelector('.error-message:not(:empty)');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    } else {
                        showModalError(result.message || 'An error occurred. Please try again.');
                    }
                }
            }, 500);
        } catch (error) {
            clearInterval(interval);
            splashScreen.classList.remove('active');
            console.error('Submission error:', error);
            showModalError(error.message || 'An error occurred. Please try again.');
        }
    });

    function showModalError(message) {
        errorModalMessage.textContent = message;
        errorModal.classList.add('active');
    }

    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
            setTimeout(() => {
                errorElement.textContent = '';
            }, 5000);
        }
    }
});
</script>
@endpush
@endsection

