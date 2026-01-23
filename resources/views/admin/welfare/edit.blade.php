@extends('layouts.admin')

@section('page-title', 'Edit Social Welfare Record')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Edit Welfare Record</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">{{ $welfare->welfare_number }}</p>
            </div>
            <div class="mt-4 md:mt-0 md:ml-auto flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.welfare.show', $welfare) }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition font-medium">
                    View Details
                </a>
                <a href="{{ route('admin.welfare.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition font-medium">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Basic Information</h3>
                
                <form action="{{ route('admin.welfare.update', $welfare) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Read-only Fields -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Welfare Number</label>
                            <input type="text" value="{{ $welfare->welfare_number }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-500">This field cannot be changed</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <input type="text" value="{{ ucfirst($welfare->type) }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-500">This field cannot be changed</p>
                        </div>

                        <!-- Editable Fields -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount (TZS) *</label>
                            <input type="number" name="amount" step="0.01" min="0" value="{{ old('amount', $welfare->amount) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Date *</label>
                            <input type="date" name="transaction_date" value="{{ old('transaction_date', $welfare->transaction_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            @error('transaction_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        @if($welfare->type === 'benefit')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Benefit Type</label>
                            <select name="benefit_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                <option value="">Select Benefit Type</option>
                                <option value="medical" {{ old('benefit_type', $welfare->benefit_type) === 'medical' ? 'selected' : '' }}>Medical Support</option>
                                <option value="funeral" {{ old('benefit_type', $welfare->benefit_type) === 'funeral' ? 'selected' : '' }}>Funeral Support</option>
                                <option value="educational" {{ old('benefit_type', $welfare->benefit_type) === 'educational' ? 'selected' : '' }}>Educational Support</option>
                                <option value="other" {{ old('benefit_type', $welfare->benefit_type) === 'other' ? 'selected' : '' }}>Other Support</option>
                            </select>
                            @error('benefit_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('description', $welfare->description) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Provide a detailed description of this welfare record</p>
                        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Status Management Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                        <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Status & Workflow</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                    <option value="pending" {{ old('status', $welfare->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status', $welfare->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="disbursed" {{ old('status', $welfare->status) === 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                                    <option value="completed" {{ old('status', $welfare->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="rejected" {{ old('status', $welfare->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div id="approved_by_field" style="{{ in_array($welfare->status, ['approved', 'disbursed', 'completed']) ? '' : 'display: none;' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Approved By</label>
                                <select name="approved_by" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                    <option value="">Auto-assign (Current User)</option>
                                    @foreach($staff as $staffMember)
                                        <option value="{{ $staffMember->id }}" {{ old('approved_by', $welfare->approved_by) == $staffMember->id ? 'selected' : '' }}>
                                            {{ $staffMember->name }} ({{ $staffMember->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('approved_by')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div id="approval_date_field" style="{{ in_array($welfare->status, ['approved', 'disbursed', 'completed']) ? '' : 'display: none;' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Approval Date</label>
                                <input type="date" name="approval_date" value="{{ old('approval_date', $welfare->approval_date ? $welfare->approval_date->format('Y-m-d') : date('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                @error('approval_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div id="disbursement_date_field" style="{{ $welfare->status === 'disbursed' ? '' : 'display: none;' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Disbursement Date</label>
                                <input type="date" name="disbursement_date" value="{{ old('disbursement_date', $welfare->disbursement_date ? $welfare->disbursement_date->format('Y-m-d') : date('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                                @error('disbursement_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div id="rejection_reason_field" style="{{ $welfare->status === 'rejected' ? '' : 'display: none;' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                                <textarea name="rejection_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('rejection_reason', $welfare->rejection_reason) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Required when status is Rejected</p>
                                @error('rejection_reason')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Notes Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                        <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Additional Notes</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Eligibility Notes</label>
                            <textarea name="eligibility_notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('eligibility_notes', $welfare->eligibility_notes) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Add any notes about the member's eligibility or special circumstances</p>
                            @error('eligibility_notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-6 flex space-x-4">
                        <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Record
                            </span>
                        </button>
                        <a href="{{ route('admin.welfare.show', $welfare) }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition font-medium">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Member Information Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Member Information</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Name</p>
                        <p class="font-semibold text-gray-900">{{ $welfare->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <p class="font-semibold text-gray-900">{{ $welfare->user->email }}</p>
                    </div>
                    @if($welfare->user->phone)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Phone</p>
                        <p class="font-semibold text-gray-900">{{ $welfare->user->phone }}</p>
                    </div>
                    @endif
                    <div>
                        <a href="{{ route('admin.users.show', $welfare->user) }}" class="text-[#015425] hover:text-[#013019] font-medium text-sm inline-flex items-center">
                            View Full Profile
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Current Status Card -->
            <div class="bg-gradient-to-br from-[#015425] to-[#027a3a] rounded-lg shadow-md p-6 text-white">
                <h3 class="text-xl font-bold mb-4">Current Status</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-white text-opacity-80 text-sm mb-1">Status</p>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-white bg-opacity-20 inline-block">
                            {{ ucfirst($welfare->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-white border-opacity-20">
                        <span class="text-white text-opacity-80">Amount</span>
                        <span class="font-bold">{{ number_format($welfare->amount, 0) }} TZS</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white text-opacity-80">Created</span>
                        <span class="font-semibold">{{ $welfare->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($welfare->approval_date)
                    <div class="flex justify-between">
                        <span class="text-white text-opacity-80">Approved</span>
                        <span class="font-semibold">{{ $welfare->approval_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Approval Information -->
            @if($welfare->approver)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Approval Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Approved By</p>
                        <p class="font-semibold text-gray-900">{{ $welfare->approver->name }}</p>
                    </div>
                    @if($welfare->approval_date)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Approval Date</p>
                        <p class="font-semibold text-gray-900">{{ $welfare->approval_date->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $welfare->approval_date->diffForHumans() }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-[#015425] mb-4 pb-3 border-b">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.welfare.show', $welfare) }}" class="block w-full px-4 py-2 text-center bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium">
                        View Details
                    </a>
                    <a href="{{ route('admin.welfare.index') }}" class="block w-full px-4 py-2 text-center bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('status').addEventListener('change', function() {
        const approvalDateField = document.getElementById('approval_date_field');
        const approvedByField = document.getElementById('approved_by_field');
        const disbursementField = document.getElementById('disbursement_date_field');
        const rejectionField = document.getElementById('rejection_reason_field');
        const rejectionTextarea = rejectionField.querySelector('textarea');
        
        // Show/hide approval fields
        if (this.value === 'approved' || this.value === 'disbursed' || this.value === 'completed') {
            approvalDateField.style.display = 'block';
            approvedByField.style.display = 'block';
        } else {
            approvalDateField.style.display = 'none';
            approvedByField.style.display = 'none';
        }
        
        // Show/hide disbursement field
        if (this.value === 'disbursed') {
            disbursementField.style.display = 'block';
        } else {
            disbursementField.style.display = 'none';
        }
        
        // Show/hide rejection field
        if (this.value === 'rejected') {
            rejectionField.style.display = 'block';
            if (rejectionTextarea) {
                rejectionTextarea.required = true;
            }
        } else {
            rejectionField.style.display = 'none';
            if (rejectionTextarea) {
                rejectionTextarea.required = false;
            }
        }
    });
</script>
@endsection
