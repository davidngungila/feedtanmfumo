@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <img src="{{ asset('feedtan_logo.png') }}" alt="FeedTan Logo" class="h-20 mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Loan Guarantor Assessment</h1>
            <p class="text-lg text-gray-600">FeedTan Community Microfinance Group</p>
            <div class="mt-4 p-4 bg-orange-50 border-l-4 border-orange-400 text-orange-700 text-sm text-left inline-block">
                <p class="font-bold">Important:</p>
                <p>This form must be completed by the prospective guarantor BEFORE providing any guarantee. It is designed to help you understand the full responsibility and risks involved. Completion is mandatory for the loan application to proceed.</p>
            </div>
        </div>

        <form action="{{ route('guarantor-assessment.store', $loan->ulid) }}" method="POST" class="space-y-8 bg-white shadow-2xl rounded-2xl p-8 border border-gray-100">
            @csrf

            <!-- Section 1: Guarantor & Borrower Information -->
            <div class="section">
                <h2 class="text-xl font-semibold text-blue-800 border-b pb-2 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-800 rounded-full h-8 w-8 flex items-center justify-center mr-3 text-sm">1</span>
                    Guarantor & Borrower Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="guarantor_id" class="block text-sm font-medium text-gray-700 mb-1">Full Name of Guarantor</label>
                        <select id="guarantor_id" name="guarantor_id" required class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            <option value="">-- Select Your Name --</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('guarantor_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }} ({{ $member->membership_code ?? 'No Code' }})
                                </option>
                            @endforeach
                        </select>
                        @error('guarantor_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name of Borrower</label>
                        <input type="text" value="{{ $loan->user->name }}" readonly class="block w-full px-4 py-3 rounded-lg border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label for="relationship" class="block text-sm font-medium text-gray-700 mb-1">Relationship to Borrower</label>
                        <select id="relationship" name="relationship" required onchange="toggleField('relationship', 'relationship_other_div')" class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Select Relationship --</option>
                            <option value="Spouse">Spouse</option>
                            <option value="Sibling">Sibling</option>
                            <option value="Parent/Child">Parent/Child</option>
                            <option value="Close Friend">Close Friend</option>
                            <option value="Business Partner">Business Partner</option>
                            <option value="Other">Other (Specify)</option>
                        </select>
                    </div>

                    <div id="relationship_other_div" class="hidden">
                        <label for="relationship_other" class="block text-sm font-medium text-gray-700 mb-1">Specify Relationship</label>
                        <input type="text" id="relationship_other" name="relationship_other" class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Section 2: Assessment of Borrower & Loan Purpose -->
            <div class="section pt-6">
                <h2 class="text-xl font-semibold text-blue-800 border-b pb-2 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-800 rounded-full h-8 w-8 flex items-center justify-center mr-3 text-sm">2</span>
                    Assessment of Borrower & Loan Purpose
                </h2>

                <div class="bg-gray-50 rounded-xl p-6 mb-8 grid grid-cols-2 md:grid-cols-3 gap-4 border border-gray-100">
                    <div class="text-sm">
                        <span class="block text-gray-500 font-medium uppercase tracking-wider text-xs">Loan Amount</span>
                        <span class="text-lg font-bold text-gray-900">TZS {{ number_format($loan->principal_amount) }}</span>
                    </div>
                    <div class="text-sm">
                        <span class="block text-gray-500 font-medium uppercase tracking-wider text-xs">Loan Term</span>
                        <span class="text-lg font-bold text-gray-900">{{ $loan->term_months }} Months</span>
                    </div>
                    <div class="text-sm">
                        <span class="block text-gray-500 font-medium uppercase tracking-wider text-xs">Monthly Installment</span>
                        <span class="text-lg font-bold text-gray-900">TZS {{ number_format($loan->total_amount / $loan->term_months, 2) }}</span>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="loan_purpose" class="block text-sm font-medium text-gray-700 mb-2">What is the PRIMARY purpose of the borrower's loan?</label>
                        <select id="loan_purpose" name="loan_purpose" required onchange="toggleField('loan_purpose', 'loan_purpose_other_div')" class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm">
                            <option value="">-- Select Purpose --</option>
                            <option value="Business Investment/Expansion">Business Investment/Expansion</option>
                            <option value="Education Fees">Education Fees</option>
                            <option value="Medical Expenses">Medical Expenses</option>
                            <option value="Home Improvement">Home Improvement</option>
                            <option value="Asset Purchase">Asset Purchase (e.g., vehicle)</option>
                            <option value="Debt Consolidation">Debt Consolidation</option>
                            <option value="Agriculture/Farming">Agriculture/Farming</option>
                            <option value="Other">Other (Specify)</option>
                        </select>
                    </div>

                    <div id="loan_purpose_other_div" class="hidden">
                        <label for="loan_purpose_other" class="block text-sm font-medium text-gray-700 mb-1">Specify Purpose</label>
                        <input type="text" id="loan_purpose_other" name="loan_purpose_other" class="block w-full px-4 py-3 rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Have you personally reviewed the borrower's FeedTan savings/credit history and are confident in their repayment ability?</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50 transition cursor-pointer">
                                <input type="radio" name="reviewed_history" value="Yes, I have reviewed and am confident" required class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Yes, I have reviewed and am confident</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50 transition cursor-pointer">
                                <input type="radio" name="reviewed_history" value="Yes, but I have some concerns" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Yes, but I have some concerns</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50 transition cursor-pointer">
                                <input type="radio" name="reviewed_history" value="No, I have not reviewed it" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">No, I have not reviewed it</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To your knowledge, does the borrower have other significant debts?</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50 transition cursor-pointer">
                                <input type="radio" name="other_debts" value="No" required class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">No</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50 transition cursor-pointer">
                                <input type="radio" name="other_debts" value="Yes, but manageable" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">Yes, but manageable</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50 transition cursor-pointer">
                                <input type="radio" name="other_debts" value="Yes, and I am concerned" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">Yes, and I am concerned</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50 transition cursor-pointer">
                                <input type="radio" name="other_debts" value="I don't know" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">I don't know</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Guarantor's Financial Self-Assessment -->
            <div class="section pt-6">
                <h2 class="text-xl font-semibold text-blue-800 border-b pb-2 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-800 rounded-full h-8 w-8 flex items-center justify-center mr-3 text-sm">3</span>
                    Guarantor's Financial Self-Assessment
                </h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Do you have sufficient savings/capital/investments in FeedTan to cover the loan repayment IN FULL if the borrower defaults?</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="sufficient_savings" value="Yes, easily" required class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">Yes, easily</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="sufficient_savings" value="Yes, but it would significantly deplete my savings" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">Yes, but it would significantly deplete my savings</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="sufficient_savings" value="No" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700 text-red-600 font-semibold">No (May require counseling)</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">If you had to repay this loan, would it prevent you from meeting your own essential financial obligations?</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="financial_obligation_impact" value="No" required class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">No</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="financial_obligation_impact" value="Yes, temporarily" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">Yes, temporarily</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="financial_obligation_impact" value="Yes, severely" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">Yes, severely</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Are you currently a guarantor for any other loans (inside or outside FeedTan)?</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="other_guarantees" value="No" required class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">No</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="other_guarantees" value="Yes, for 1 other loan" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">1 other</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="other_guarantees" value="Yes, for 2 or more loans" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">2 or more</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Understanding of Terms & Contingency -->
            <div class="section pt-6">
                <h2 class="text-xl font-semibold text-blue-800 border-b pb-2 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-800 rounded-full h-8 w-8 flex items-center justify-center mr-3 text-sm">4</span>
                    Understanding of Terms & Contingency
                </h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">I understand that as a guarantor, I am SOLELY responsible for the FULL loan amount, interest, and any penalties if the borrower defaults.</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="solely_responsible_understanding" value="I fully understand and accept" required class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">I fully understand and accept</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="solely_responsible_understanding" value="I need clarification" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-orange-600 font-semibold">I need clarification</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">I understand that in case of default, FeedTan will legally deduct the owed amount first from the borrower's savings, and then from MY savings and capital without further notice.</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="recovery_mechanism_understanding" value="I fully understand and accept" required class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">I fully understand and accept</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="recovery_mechanism_understanding" value="I need clarification" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-orange-600 font-semibold">I need clarification</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Has the borrower discussed with you a specific backup plan in case they face difficulty repaying?</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="borrower_backup_plan" value="Yes, and it is reasonable" required class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">Yes, and it is reasonable</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="borrower_backup_plan" value="Yes, but it is vague" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700">Yes, but it is vague</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg border border-gray-200">
                                <input type="radio" name="borrower_backup_plan" value="No" class="h-4 w-4 text-blue-600">
                                <span class="ml-3 text-sm text-gray-700 text-red-600 font-semibold">No</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="guarantor_backup_plan" class="block text-sm font-medium text-gray-700 mb-2">Do you have a personal backup plan to recover funds if you have to pay on the borrower's behalf?</label>
                        <textarea id="guarantor_backup_plan" name="guarantor_backup_plan" rows="3" required class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Describe your plan..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Section 5: Final Declaration -->
            <div class="section pt-6">
                <h2 class="text-xl font-semibold text-blue-800 border-b pb-2 mb-6 flex items-center">
                    <span class="bg-blue-100 text-blue-800 rounded-full h-8 w-8 flex items-center justify-center mr-3 text-sm">5</span>
                    Final Declaration
                </h2>

                <div class="space-y-6">
                    <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                        <label class="flex items-start cursor-pointer">
                            <div class="flex items-center h-5">
                                <input type="checkbox" id="final_declaration" name="final_declaration" required class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="font-medium text-blue-900">Binding Commitment</span>
                                <p class="text-blue-700 mt-1">By submitting this form, I acknowledge that this is a legally binding financial commitment and I am acting willingly, without pressure, and with full understanding of the potential consequences.</p>
                            </div>
                        </label>
                    </div>

                    <div>
                        <label for="additional_comments" class="block text-sm font-medium text-gray-700 mb-2">Additional Comments or Concerns</label>
                        <textarea id="additional_comments" name="additional_comments" rows="3" class="block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Optional..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-8">
                <button type="submit" class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-lg font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 transform hover:scale-[1.02]">
                    Submit Assessment & Sign Agreement
                </button>
                <p class="text-center text-xs text-gray-500 mt-4">
                    Securely processed by FeedTan Digital. Your responses are legally binding.
                </p>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleField(selectId, divId) {
        const select = document.getElementById(selectId);
        const div = document.getElementById(divId);
        if (select.value === 'Other') {
            div.classList.remove('hidden');
            div.querySelector('input').setAttribute('required', 'required');
        } else {
            div.classList.add('hidden');
            div.querySelector('input').removeAttribute('required');
        }
    }
</script>

<style>
    .section {
        animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    input[type="radio"]:checked + span {
        font-weight: 600;
        color: #1e40af;
    }
</style>
@endsection
