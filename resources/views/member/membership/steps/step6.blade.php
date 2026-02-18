@php
    $currentStep = 6;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header-banner">
    <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Social Parameters</h2>
    <p class="text-gray-500 font-medium max-w-2xl leading-relaxed">Configure your social profile and communication preferences. Trust-based introduction data reinforces our community integrity protocols.</p>
</div>

<div class="step-body">
    <form method="POST" action="{{ route('member.membership.store-step6') }}" id="step6Form" class="ajax-form">
        @csrf
        
        <div class="space-y-10">
            <!-- Biography -->
            <div class="space-y-4">
                <label for="short_bibliography" class="premium-label">Community Biography (Optional)</label>
                <textarea id="short_bibliography" name="short_bibliography" class="premium-input min-h-[120px] resize-none" 
                          placeholder="A brief strategic summary of your professional or community background...">{{ old('short_bibliography', $user->short_bibliography) }}</textarea>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-2 italic">May be utilized in official certification and community directory documents.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Introduced By -->
                <div class="space-y-4">
                    <label for="introduced_by" class="premium-label">Guarantor / Strategic Referrer</label>
                    <div class="relative">
                        <input type="text" id="introduced_by" name="introduced_by" value="{{ old('introduced_by', $user->introduced_by) }}" 
                               class="premium-input pl-14" placeholder="Name of identifying member">
                        <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                </div>
                
                <!-- Statement Preference -->
                <div class="space-y-4">
                    <label for="statement_preference" class="premium-label">Intelligence Delivery Protocol <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="statement_preference" name="statement_preference" class="premium-input appearance-none pr-10" required>
                            <option value="">-- Select Channel --</option>
                            <option value="email" {{ old('statement_preference', $user->statement_preference) == 'email' ? 'selected' : '' }}>SECURE EMAIL</option>
                            <option value="sms" {{ old('statement_preference', $user->statement_preference) == 'sms' ? 'selected' : '' }}>MOBILE SMS GLOBULES</option>
                            <option value="postal" {{ old('statement_preference', $user->statement_preference) == 'postal' ? 'selected' : '' }}>POSTAL ARCHIVE</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('statement_preference')<p class="text-red-500 text-[10px] font-bold mt-1 ml-2">{{ $message }}</p>@enderror
                </div>
            </div>
            
            <div class="mt-12 p-10 bg-gray-900 rounded-[3rem] text-white flex flex-col md:flex-row items-center justify-between gap-10 relative overflow-hidden group">
                 <div class="absolute inset-0 bg-gradient-to-r from-green-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
                 <div class="relative z-10">
                    <h4 class="text-xl font-black mb-1">Community Context</h4>
                    <p class="text-xs text-gray-400 font-medium max-w-sm">These parameters refine your social footprint and ensure seamless intelligence flow across the network.</p>
                 </div>
                 
                 <div class="flex gap-4 relative z-10">
                    <a href="{{ route('member.membership.step5') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                        BACK 05
                    </a>
                    <button type="submit" class="luxury-button flex items-center gap-4">
                        COMMIT PROFILE
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </button>
                 </div>
            </div>
        </div>
    </form>
</div>
@endsection
