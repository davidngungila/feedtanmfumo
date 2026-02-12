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
</style>
@endpush

@section('content')
<div class="payment-container">
    <div class="payment-card">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-[#015425] mb-2">Payment Confirmation</h1>
            <p class="text-sm text-gray-600">Confirm your payment distribution from deposit balance</p>
        </div>

        <!-- Member ID Lookup Section -->
        <div id="lookupSection">
            <div class="input-group">
                <label for="member_id" class="input-label">Member ID</label>
                <input 
                    type="text" 
                    id="member_id" 
                    name="member_id" 
                    class="input-field" 
                    placeholder="Enter your Member ID"
                    required
                >
                <div id="member_id_error" class="error-message"></div>
            </div>
            <button 
                type="button" 
                id="lookupBtn" 
                class="btn-submit"
            >
                Lookup Member
            </button>
        </div>

        <!-- Member Info Display -->
        <div id="memberInfo" style="display: none;">
            <div class="balance-display">
                <div class="text-sm mb-2">Member Information</div>
                <div class="text-2xl font-bold mb-1" id="memberName"></div>
                <div class="text-sm opacity-90" id="memberDetails"></div>
                <div class="mt-3 pt-3 border-t border-white/20">
                    <div class="text-sm opacity-90">Deposit Balance</div>
                    <div class="text-3xl font-bold" id="depositBalance"></div>
                </div>
            </div>

            <!-- Zero Balance Message -->
            <div id="zeroBalanceMessage" style="display: none;">
                <div class="notification success">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <div class="font-semibold">Thank you for your interest!</div>
                        <div class="text-sm">Your deposit balance is zero. You can make a deposit now to proceed with payment confirmation.</div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form id="paymentForm" style="display: none;">
                <input type="hidden" id="user_id" name="user_id">
                <input type="hidden" id="member_id_hidden" name="member_id">
                
                <div class="mb-4">
                    <label for="amount_to_pay" class="input-label">Amount to be Paid (2026)</label>
                    <input 
                        type="number" 
                        id="amount_to_pay" 
                        name="amount_to_pay" 
                        class="input-field" 
                        placeholder="Enter amount to be paid"
                        step="0.01"
                        min="0.01"
                        required
                    >
                    <div class="text-xs text-gray-600 mt-1">Maximum: <span id="maxAmount">0.00</span></div>
                    <div id="amount_to_pay_error" class="error-message"></div>
                </div>

                <div class="mb-4">
                    <label for="member_email" class="input-label">Your Email Address</label>
                    <input 
                        type="email" 
                        id="member_email" 
                        name="member_email" 
                        class="input-field" 
                        placeholder="Enter your email address"
                        required
                    >
                    <div id="member_email_error" class="error-message"></div>
                </div>

                <div class="mb-4">
                    <div class="text-lg font-semibold mb-3 text-[#015425]">Payment Distribution</div>
                    <div class="text-sm text-gray-600 mb-4">Select one or more options. Total must equal Amount to be Paid.</div>
                    
                    <!-- SWF Contribution -->
                    <div class="distribution-item">
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="checkbox" id="enable_swf" class="w-4 h-4 text-[#015425]">
                            <span class="font-semibold">Contribute SWF (Social Welfare Fund)</span>
                        </label>
                        <input 
                            type="number" 
                            id="swf_contribution" 
                            name="swf_contribution" 
                            class="input-field" 
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            disabled
                        >
                    </div>

                    <!-- Re-deposit -->
                    <div class="distribution-item">
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="checkbox" id="enable_re_deposit" class="w-4 h-4 text-[#015425]">
                            <span class="font-semibold">Re-deposit Again</span>
                        </label>
                        <input 
                            type="number" 
                            id="re_deposit" 
                            name="re_deposit" 
                            class="input-field" 
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            disabled
                        >
                    </div>

                    <!-- FIA Investment -->
                    <div class="distribution-item">
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="checkbox" id="enable_fia" class="w-4 h-4 text-[#015425]">
                            <span class="font-semibold">Invest in FIA</span>
                        </label>
                        <select id="fia_type" name="fia_type" class="input-field mb-2" disabled>
                            <option value="">Select FIA Type</option>
                            <option value="4_year">4 Year Investment</option>
                            <option value="6_year">6 Year Investment</option>
                        </select>
                        <input 
                            type="number" 
                            id="fia_investment" 
                            name="fia_investment" 
                            class="input-field" 
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            disabled
                        >
                    </div>

                    <!-- Capital Contribution -->
                    <div class="distribution-item">
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="checkbox" id="enable_capital" class="w-4 h-4 text-[#015425]">
                            <span class="font-semibold">Contribute Capital (Share)</span>
                        </label>
                        <input 
                            type="number" 
                            id="capital_contribution" 
                            name="capital_contribution" 
                            class="input-field" 
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            disabled
                        >
                    </div>

                    <!-- Loan Repayment -->
                    <div class="distribution-item">
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="checkbox" id="enable_loan" class="w-4 h-4 text-[#015425]">
                            <span class="font-semibold">Loan Repayment</span>
                        </label>
                        <input 
                            type="number" 
                            id="loan_repayment" 
                            name="loan_repayment" 
                            class="input-field" 
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            disabled
                        >
                    </div>
                </div>

                <div class="total-display" id="totalDisplay">
                    <div class="flex justify-between items-center">
                        <span>Total Distribution:</span>
                        <span id="totalAmount">0.00</span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span>Amount to be Paid:</span>
                        <span id="amountToPayDisplay">0.00</span>
                    </div>
                    <div class="flex justify-between items-center mt-2 font-bold text-lg" id="differenceDisplay">
                        <span>Difference:</span>
                        <span id="differenceAmount">0.00</span>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="notes" class="input-label">Notes (Optional)</label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        class="input-field" 
                        rows="3"
                        placeholder="Any additional notes or comments..."
                    ></textarea>
                </div>

                <button 
                    type="submit" 
                    id="submitBtn" 
                    class="btn-submit mt-6"
                    disabled
                >
                    Submit Payment Confirmation
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Splash Screen -->
<div class="splash-screen" id="splashScreen">
    <div class="splash-progress" id="splashProgress">0%</div>
    <div class="splash-message" id="splashMessage">Processing your payment confirmation...</div>
</div>

<!-- Success Modal -->
<div class="success-modal" id="successModal">
    <div class="success-modal-content">
        <div class="mb-4">
            <svg class="w-16 h-16 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold mb-2">Payment Confirmation Successful!</h2>
        <p class="text-gray-600 mb-4">Your payment confirmation has been submitted successfully. An email has been sent to you and the administrators.</p>
        <button 
            type="button" 
            onclick="location.reload()" 
            class="btn-submit"
        >
            Submit Another Confirmation
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
    const submitBtn = document.getElementById('submitBtn');

    let memberData = null;
    let maxDepositBalance = 0;

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
                maxDepositBalance = parseFloat(memberData.deposit_balance_raw);
                
                document.getElementById('user_id').value = memberData.id;
                document.getElementById('member_id_hidden').value = memberData.member_id;
                document.getElementById('memberName').textContent = memberData.name;
                document.getElementById('memberDetails').textContent = `${memberData.member_id} - ${memberData.member_type}`;
                document.getElementById('depositBalance').textContent = `TZS ${memberData.deposit_balance}`;
                document.getElementById('maxAmount').textContent = `TZS ${memberData.deposit_balance}`;
                document.getElementById('member_email').value = memberData.email || '';

                lookupSection.style.display = 'none';
                memberInfo.style.display = 'block';

                if (maxDepositBalance > 0) {
                    paymentForm.style.display = 'block';
                    zeroBalanceMessage.style.display = 'none';
                } else {
                    paymentForm.style.display = 'none';
                    zeroBalanceMessage.style.display = 'block';
                }
            } else {
                showError('member_id_error', data.message || 'Member not found');
            }
        } catch (error) {
            showError('member_id_error', 'An error occurred. Please try again.');
        } finally {
            lookupBtn.disabled = false;
            lookupBtn.textContent = 'Lookup Member';
        }
    });

    // Enable/disable distribution inputs
    ['swf', 're_deposit', 'fia', 'capital', 'loan'].forEach(type => {
        const checkbox = document.getElementById(`enable_${type}`);
        const input = document.getElementById(type === 'swf' ? 'swf_contribution' : 
                                               type === 're_deposit' ? 're_deposit' :
                                               type === 'fia' ? 'fia_investment' :
                                               type === 'capital' ? 'capital_contribution' : 'loan_repayment');
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
    ['amount_to_pay', 'swf_contribution', 're_deposit', 'fia_investment', 'capital_contribution', 'loan_repayment'].forEach(id => {
        document.getElementById(id).addEventListener('input', updateTotal);
    });

    // Validate amount to pay
    document.getElementById('amount_to_pay').addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        const errorElement = document.getElementById('amount_to_pay_error');
        
        if (amount > maxDepositBalance) {
            errorElement.textContent = `Amount cannot exceed deposit balance of TZS ${maxDepositBalance.toFixed(2)}`;
            this.style.borderColor = '#dc2626';
        } else if (amount <= 0) {
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
        const swf = parseFloat(document.getElementById('swf_contribution').value) || 0;
        const reDeposit = parseFloat(document.getElementById('re_deposit').value) || 0;
        const fia = parseFloat(document.getElementById('fia_investment').value) || 0;
        const capital = parseFloat(document.getElementById('capital_contribution').value) || 0;
        const loan = parseFloat(document.getElementById('loan_repayment').value) || 0;

        const total = swf + reDeposit + fia + capital + loan;
        const difference = amountToPay - total;
        
        document.getElementById('amountToPayDisplay').textContent = `TZS ${amountToPay.toFixed(2)}`;

        document.getElementById('totalAmount').textContent = `TZS ${total.toFixed(2)}`;
        document.getElementById('differenceAmount').textContent = `TZS ${Math.abs(difference).toFixed(2)}`;

        const totalDisplay = document.getElementById('totalDisplay');
        if (Math.abs(difference) < 0.01) {
            totalDisplay.classList.remove('invalid');
            totalDisplay.classList.add('valid');
            submitBtn.disabled = false;
        } else {
            totalDisplay.classList.remove('valid');
            totalDisplay.classList.add('invalid');
            submitBtn.disabled = true;
        }
    }

    // Form submission
    document.getElementById('paymentForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Show splash screen
        splashScreen.classList.add('active');
        let progress = 0;
        const interval = setInterval(() => {
            progress += 2;
            if (progress <= 100) {
                splashProgress.textContent = progress + '%';
            } else {
                clearInterval(interval);
            }
        }, 50);

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        // Convert to numbers
        data.swf_contribution = parseFloat(data.swf_contribution) || 0;
        data.re_deposit = parseFloat(data.re_deposit) || 0;
        data.fia_investment = parseFloat(data.fia_investment) || 0;
        data.capital_contribution = parseFloat(data.capital_contribution) || 0;
        data.loan_repayment = parseFloat(data.loan_repayment) || 0;
        data.amount_to_pay = parseFloat(data.amount_to_pay);

        try {
            const response = await fetch('{{ route("payment-confirmation.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            // Complete progress
            clearInterval(interval);
            splashProgress.textContent = '100%';

            setTimeout(() => {
                splashScreen.classList.remove('active');
                if (result.success) {
                    successModal.classList.add('active');
                } else {
                    alert(result.message || 'An error occurred. Please try again.');
                }
            }, 500);
        } catch (error) {
            clearInterval(interval);
            splashScreen.classList.remove('active');
            alert('An error occurred. Please try again.');
        }
    });

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

