@php
    $currentStep = 1;
@endphp

@extends('member.membership.steps._step-layout')

@section('step-content')
<div class="step-header-banner">
    <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Initiate Tier Selection</h2>
    <p class="text-gray-500 font-medium max-w-2xl leading-relaxed">Define your level of engagement within the FEEDTAN REJESHO ecosystem. Each tier unlocks specialized liquidity protocols and community assets.</p>
</div>

<div class="step-body">
    <form method="POST" action="{{ route('member.membership.store-step1') }}" id="step1Form" class="ajax-form">
        @csrf
        
        <div class="space-y-10">
            <div>
                <span class="premium-label ml-0">Available Membership Tiers</span>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">
                    @foreach($membershipTypes as $type)
                        <label class="relative cursor-pointer group">
                            <input type="radio" 
                                   name="membership_type_id" 
                                   value="{{ $type->id }}" 
                                   class="absolute opacity-0 w-0 h-0 peer"
                                   {{ old('membership_type_id', $user->membership_type_id) == $type->id ? 'checked' : '' }}
                                   required>
                            <div class="h-full bg-gray-50 border-2 border-transparent rounded-[2.5rem] p-10 transition-all duration-500 group-hover:bg-gray-100 peer-checked:border-[#015425] peer-checked:bg-green-50/50 peer-checked:shadow-2xl peer-checked:shadow-green-900/5">
                                <div class="flex flex-col h-full">
                                    <div class="flex justify-between items-start mb-6">
                                        <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-[#015425] group-hover:scale-110 transition-transform duration-500 peer-checked:bg-[#015425] peer-checked:text-white">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A9 9 0 1120.364 6.364l-1.42 1.419a7 7 0 10-9.9 9.9l1.414-1.414L10 18a8 8 0 1118 0z"></path></svg>
                                        </div>
                                        <div class="w-6 h-6 rounded-full border-2 border-gray-200 bg-white flex items-center justify-center peer-checked:border-[#015425] peer-checked:bg-[#015425]">
                                             <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>

                                    <h3 class="text-xl font-black text-gray-900 mb-2 truncate">{{ $type->name }}</h3>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-6 h-8 line-clamp-2 leading-relaxed">{{ $type->description }}</p>
                                    
                                    <div class="space-y-3 pt-6 border-t border-gray-200/50 mt-auto">
                                        @if($type->entrance_fee > 0)
                                        <div class="flex justify-between items-center">
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Entrance</span>
                                            <span class="text-xs font-black text-gray-900">{{ number_format($type->entrance_fee, 0) }} <span class="text-[9px] opacity-60 font-normal">TZS</span></span>
                                        </div>
                                        @endif
                                        
                                        @if($type->capital_contribution > 0)
                                        <div class="flex justify-between items-center">
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Capital</span>
                                            <span class="text-xs font-black text-gray-900">{{ number_format($type->capital_contribution, 0) }} <span class="text-[9px] opacity-60 font-normal">TZS</span></span>
                                        </div>
                                        @endif
                                        
                                        @if($type->minimum_shares > 0)
                                        <div class="flex justify-between items-center">
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Min Shares</span>
                                            <span class="text-xs font-black text-[#015425]">{{ $type->minimum_shares }} Units</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    @if($type->features)
                                    <div class="mt-8 space-y-2">
                                        @foreach(array_slice(explode(',', $type->features), 0, 3) as $feature)
                                            <div class="flex items-center gap-2 text-[10px] font-bold text-gray-500">
                                                <span class="w-1 h-1 rounded-full bg-[#015425]"></span>
                                                {{ trim($feature) }}
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('membership_type_id')<p class="text-red-600 text-[10px] font-black mt-4 ml-1">{{ $message }}</p>@enderror
            </div>

            <div class="p-8 bg-blue-50/50 rounded-[2rem] border border-blue-100 flex items-start gap-5">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-xs text-blue-900 font-bold leading-relaxed opacity-80">
                    <strong>Institutional Tip:</strong> Each tier represents a different commitment to the group's collective growth. Your selection will define your borrowing capacity and dividend allocation throughout your tenure.
                </p>
            </div>
            
            <div class="flex justify-end pt-10 border-t border-gray-50">
                <button type="submit" class="luxury-button flex items-center gap-4">
                    Authenticate & Proceed
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    /* Radio logic reinforcement */
    input[type="radio"]:checked + div {
        border-color: #015425 !important;
        background: rgba(1, 84, 37, 0.03) !important;
        box-shadow: 0 30px 60px -12px rgba(1, 84, 37, 0.15) !important;
    }
    input[type="radio"]:checked + div .w-14 {
        background: #015425 !important;
        color: white !important;
    }
</style>
@endsection
