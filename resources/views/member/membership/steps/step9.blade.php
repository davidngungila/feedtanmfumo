@php
    $currentStep = 9;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header">
    <h2>Step 9: Documents Upload</h2>
    <p>Upload required documents to complete your membership application. All documents should be clear and legible.</p>
</div>

<div class="info-box">
    <p><strong>📄 Document Requirements:</strong> Please ensure all documents are in PDF, JPG, or PNG format. Maximum file size is 5MB per document.</p>
</div>

<form method="POST" action="{{ route('member.membership.store-step9') }}" id="step9Form" class="ajax-form" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="form-group">
            <label for="passport_picture" class="form-label">
                Passport Size Picture <span class="required">*</span>
            </label>
            <input type="file" id="passport_picture" name="passport_picture" accept="image/*" class="form-input">
            @if($user->passport_picture_path)
                <p class="text-sm text-green-600 mt-1">✓ Current: {{ basename($user->passport_picture_path) }}</p>
            @endif
            <p class="help-text">Recent passport-sized photo (JPG, PNG, max 2MB)</p>
        </div>
        
        <div class="form-group">
            <label for="nida_picture" class="form-label">
                NIDA ID Picture <span class="required">*</span>
            </label>
            <input type="file" id="nida_picture" name="nida_picture" accept="image/*" class="form-input">
            @if($user->nida_picture_path)
                <p class="text-sm text-green-600 mt-1">✓ Current: {{ basename($user->nida_picture_path) }}</p>
            @endif
            <p class="help-text">Clear photo of your NIDA card (JPG, PNG, max 2MB)</p>
        </div>
        
        <div class="form-group">
            <label for="application_letter" class="form-label">Membership Application Letter</label>
            <input type="file" id="application_letter" name="application_letter" accept=".pdf,.doc,.docx" class="form-input">
            @if($user->application_letter_path)
                <p class="text-sm text-green-600 mt-1">✓ Current: {{ basename($user->application_letter_path) }}</p>
            @endif
            <p class="help-text">Formal application letter (PDF, DOC, DOCX, max 5MB)</p>
        </div>
        
        <div class="form-group">
            <label for="payment_slip" class="form-label">Payment Slip/Evidence</label>
            <input type="file" id="payment_slip" name="payment_slip" accept="image/*,.pdf" class="form-input">
            @if($user->payment_slip_path)
                <p class="text-sm text-green-600 mt-1">✓ Current: {{ basename($user->payment_slip_path) }}</p>
            @endif
            <p class="help-text">Proof of payment (PDF, JPG, PNG, max 5MB)</p>
        </div>
        
        <div class="form-group md:col-span-2">
            <label for="standing_order" class="form-label">Bank Standing Order (if applicable)</label>
            <input type="file" id="standing_order" name="standing_order" accept=".pdf,.doc,.docx,image/*" class="form-input">
            @if($user->standing_order_path)
                <p class="text-sm text-green-600 mt-1">✓ Current: {{ basename($user->standing_order_path) }}</p>
            @endif
            <p class="help-text">Bank standing order document (PDF, DOC, DOCX, JPG, PNG, max 5MB)</p>
        </div>
    </div>
    
    <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
        <a href="{{ route('member.membership.step8') }}" class="btn-secondary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            Previous
        </a>
        <button type="submit" class="btn-primary">
            Continue to Step 10
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </button>
    </div>
</form>
@endsection

