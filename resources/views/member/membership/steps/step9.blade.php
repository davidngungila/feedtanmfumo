@php
    $currentStep = 9;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header-banner">
    <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Vault Verification</h2>
    <p class="text-gray-500 font-medium max-w-2xl leading-relaxed">Deposit your evidentiary documents into our secure vault. High-fidelity scans ensure rapid authentication and final membership certification.</p>
</div>

<div class="step-body">
    <form method="POST" action="{{ route('member.membership.store-step9') }}" id="step9Form" class="ajax-form" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Passport Pic -->
            <div class="space-y-4">
                <label for="passport_picture" class="premium-label">Biometric Visual (Passport) <span class="text-red-500">*</span></label>
                <div class="group relative bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] p-8 transition-all hover:border-[#015425] hover:bg-white text-center">
                    <input type="file" id="passport_picture" name="passport_picture" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                    <div class="pointer-events-none">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3 group-hover:text-[#015425] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Upload Profile Image</p>
                        <p class="text-[9px] text-gray-300 font-bold mt-1">(JPG, PNG max 2MB)</p>
                    </div>
                </div>
                @if($user->passport_picture_path)
                    <p class="text-[10px] font-black text-green-600 uppercase tracking-widest px-2 mt-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                        Verified: {{ basename($user->passport_picture_path) }}
                    </p>
                @endif
            </div>
            
            <!-- NIDA Pic -->
            <div class="space-y-4">
                <label for="nida_picture" class="premium-label">NIDA Credential Scan <span class="text-red-500">*</span></label>
                <div class="group relative bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] p-8 transition-all hover:border-[#015425] hover:bg-white text-center">
                    <input type="file" id="nida_picture" name="nida_picture" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                    <div class="pointer-events-none">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3 group-hover:text-[#015425] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Upload NIDA ID</p>
                        <p class="text-[9px] text-gray-300 font-bold mt-1">(Scan or Clear Photo)</p>
                    </div>
                </div>
                @if($user->nida_picture_path)
                    <p class="text-[10px] font-black text-green-600 uppercase tracking-widest px-2 mt-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                        Verified: {{ basename($user->nida_picture_path) }}
                    </p>
                @endif
            </div>
            
            <!-- App Letter -->
            <div class="space-y-4">
                <label for="application_letter" class="premium-label">Formal Application Decree (PDF)</label>
                <div class="group relative bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] p-8 transition-all hover:border-[#015425] hover:bg-white text-center">
                    <input type="file" id="application_letter" name="application_letter" accept=".pdf,.doc,.docx" class="absolute inset-0 opacity-0 cursor-pointer">
                    <div class="pointer-events-none">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3 group-hover:text-[#015425] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Upload Formal Letter</p>
                        <p class="text-[9px] text-gray-300 font-bold mt-1">(PDF Preferred)</p>
                    </div>
                </div>
                @if($user->application_letter_path)
                    <p class="text-[10px] font-black text-green-600 uppercase tracking-widest px-2 mt-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                        Verified: {{ basename($user->application_letter_path) }}
                    </p>
                @endif
            </div>
            
            <!-- Payment Slip -->
            <div class="space-y-4">
                <label for="payment_slip" class="premium-label">Settlement Evidence (Slip)</label>
                <div class="group relative bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] p-8 transition-all hover:border-[#015425] hover:bg-white text-center">
                    <input type="file" id="payment_slip" name="payment_slip" accept="image/*,.pdf" class="absolute inset-0 opacity-0 cursor-pointer">
                    <div class="pointer-events-none">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3 group-hover:text-[#015425] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Upload Payment Proof</p>
                        <p class="text-[9px] text-gray-300 font-bold mt-1">(Bank Slip/Screenshot)</p>
                    </div>
                </div>
                @if($user->payment_slip_path)
                    <p class="text-[10px] font-black text-green-600 uppercase tracking-widest px-2 mt-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                        Verified: {{ basename($user->payment_slip_path) }}
                    </p>
                @endif
            </div>
            
            <!-- Standing Order -->
            <div class="space-y-4 md:col-span-2">
                <label for="standing_order" class="premium-label">Bank Standing Order (Mandatory for Debt Protocols)</label>
                <div class="group relative bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] p-8 transition-all hover:border-[#015425] hover:bg-white text-center">
                    <input type="file" id="standing_order" name="standing_order" accept=".pdf,.doc,.docx,image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                    <div class="pointer-events-none flex items-center justify-center gap-6">
                        <svg class="w-10 h-10 text-gray-300 group-hover:text-[#015425] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        <div class="text-left">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Upload Certified Standing Order</p>
                            <p class="text-[9px] text-gray-300 font-bold mt-1">Maximum data volume: 5.0 MB</p>
                        </div>
                    </div>
                </div>
                @if($user->standing_order_path)
                    <p class="text-[10px] font-black text-green-600 uppercase tracking-widest px-2 mt-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                        Verified: {{ basename($user->standing_order_path) }}
                    </p>
                @endif
            </div>
        </div>
        
        <div class="mt-12 p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 flex items-center justify-between">
            <div class="hidden sm:flex items-center gap-4">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#015425] shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-relaxed">System Verification Phase 09 of 10<br><span class="text-gray-900">Archive Integrity Maintenance</span></p>
            </div>
            
            <div class="flex gap-4 w-full sm:w-auto">
                <a href="{{ route('member.membership.step8') }}" class="flex-1 sm:flex-none px-10 py-5 bg-white border border-gray-200 text-gray-400 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-100 transition-all text-center">
                    REV 08
                </a>
                <button type="submit" class="luxury-button flex-1 sm:flex-none flex items-center justify-center gap-4">
                    DEPOSIT & COMMIT
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
