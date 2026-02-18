@extends('layouts.member')

@section('page-title', 'Digital Identity')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Premium Header -->
    <div class="bg-gradient-to-br from-[#015425] via-[#027a3a] to-[#013019] rounded-[2.5rem] shadow-2xl p-10 sm:p-14 text-white relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
            <div class="relative">
                <div class="w-32 h-32 md:w-40 md:h-40 rounded-[2.5rem] bg-white/10 backdrop-blur-xl border-4 border-white/20 flex items-center justify-center text-4xl md:text-5xl font-black shadow-2xl overflow-hidden group">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    @endif
                    <div class="absolute inset-0 bg-[#015425]/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                         <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                    </div>
                </div>
            </div>
            
            <div class="text-center md:text-left flex-1">
                <div class="flex flex-wrap justify-center md:justify-start gap-2 mb-4">
                     <span class="px-4 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-[9px] font-black uppercase tracking-[0.2em] text-green-200">
                        Primary Account
                    </span>
                    @if($user->membership_code)
                        <span class="px-4 py-1.5 bg-green-500/20 backdrop-blur-md border border-green-500/30 rounded-full text-[9px] font-black uppercase tracking-[0.2em] text-white">
                            {{ $user->membership_code }}
                        </span>
                    @endif
                </div>
                <h1 class="text-4xl md:text-6xl font-black mb-2 tracking-tight">{{ $user->name }}</h1>
                <p class="text-green-50 text-lg opacity-80 font-medium mb-8">{{ $user->email }}</p>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <a href="{{ route('member.profile.edit') }}" class="px-10 py-4 bg-white text-[#015425] rounded-2xl font-black text-xs shadow-xl hover:-translate-y-1 transition-all">Identity Edit</a>
                    <a href="{{ route('member.profile.settings') }}" class="px-10 py-4 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-2xl font-black text-xs hover:bg-white/20 transition-all">Security Portal</a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-3xl font-bold text-sm animate-in fade-in slide-in-from-top duration-500 flex items-center gap-3">
             <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
             {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Analysis -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Asset Matrix -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between group cursor-default">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4">Liability</p>
                    <div>
                        <p class="text-3xl font-black text-[#015425]">{{ $stats['loans'] }}</p>
                        <p class="text-[10px] text-gray-400 font-bold mt-1">Total Credit</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between group cursor-default">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4">Liquidity</p>
                    <div>
                        <p class="text-3xl font-black text-green-600">{{ $stats['savings'] }}</p>
                        <p class="text-[10px] text-gray-400 font-bold mt-1">Savings Acc</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between group cursor-default">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4">Capital</p>
                    <div>
                        <p class="text-3xl font-black text-purple-600">{{ $stats['investments'] }}</p>
                        <p class="text-[10px] text-gray-400 font-bold mt-1">Fixed Plans</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between group cursor-default">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4">Feedback</p>
                    <div>
                        <p class="text-3xl font-black text-orange-600">{{ $stats['issues'] }}</p>
                        <p class="text-[10px] text-gray-400 font-bold mt-1">Open Tickets</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Credentials -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 sm:p-12 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="text-2xl font-black text-gray-900">Digital Credentials</h2>
                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3a9.99 9.99 0 00-4.534 1.08l.094.05c1.415.79 2.44 2.15 2.44 3.73a4.43 4.43 0 00-4.46 4.48c0 2.21 1.76 4 3.93 4z"></path></svg>
                    </div>
                </div>
                <div class="p-8 sm:p-12 grid grid-cols-1 sm:grid-cols-2 gap-12">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Legal Identity</p>
                        <p class="text-xl font-black text-gray-900">{{ $user->name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold">Authorized Account Holder</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Email Relay</p>
                        <p class="text-xl font-black text-gray-900 break-all">{{ $user->email }}</p>
                        <p class="text-[10px] text-gray-400 font-bold">Primary Communication Channel</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Cellular Contact</p>
                        <p class="text-xl font-black text-gray-900">{{ $user->phone ?? 'Un-registered' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold">Mobile Authentication Link</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Member Since</p>
                        <p class="text-xl font-black text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
                        <p class="text-[10px] text-gray-400 font-bold">Origin Date of Membership</p>
                    </div>
                </div>
            </div>

            <!-- Status Badges -->
            <div class="bg-gray-50 rounded-[2.5rem] p-8 sm:p-12 border border-gray-100">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-8">Role Clearance</h3>
                <div class="flex flex-wrap gap-4">
                    @forelse($user->roles as $role)
                        <div class="px-8 py-5 bg-white rounded-3xl shadow-sm border border-gray-200 text-center min-w-[140px] hover:-translate-y-1 transition-all group">
                             <div class="w-8 h-8 bg-green-50 rounded-full mx-auto mb-3 flex items-center justify-center text-[#015425] group-hover:bg-[#015425] group-hover:text-white transition-colors">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                             </div>
                             <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ $role->name }}</p>
                             <p class="text-[9px] text-gray-400 font-bold tracking-widest uppercase mt-1">Authorized</p>
                        </div>
                    @empty
                        <div class="w-full flex items-center gap-4 text-gray-400">
                            <svg class="w-12 h-12 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <p class="text-sm font-bold italic">No specialized roles assigned. Standard Member Protocol active.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Insights -->
        <div class="space-y-8">
            <!-- Security Audit -->
            <div class="bg-white rounded-[2rem] p-10 shadow-sm border border-gray-100">
                 <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-10 border-b border-gray-50 pb-6">Account Maturity</h3>
                 <div class="space-y-8">
                     <div class="flex items-center gap-6 group">
                         <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 transition-all group-hover:scale-110">
                              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                         </div>
                         <div>
                             <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Email Verification</p>
                             <p class="text-sm font-black {{ $user->email_verified_at ? 'text-green-600' : 'text-yellow-600' }} uppercase tracking-tighter">
                                {{ $user->email_verified_at ? 'Verified Trust' : 'Action Required' }}
                             </p>
                         </div>
                     </div>

                     <div class="flex items-center gap-6 group">
                         <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 transition-all group-hover:scale-110">
                              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                         </div>
                         <div>
                             <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Access Protocol</p>
                             <p class="text-sm font-black text-gray-900 uppercase tracking-tighter">Standard Auth</p>
                         </div>
                     </div>

                     <div class="flex items-center gap-6 group">
                         <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 transition-all group-hover:scale-110">
                              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                         </div>
                         <div>
                             <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Last Update</p>
                             <p class="text-sm font-black text-gray-900 uppercase tracking-tighter">{{ $user->updated_at->diffForHumans() }}</p>
                         </div>
                     </div>
                 </div>
            </div>

            <!-- Narrative Bio -->
            @if($user->bio)
            <div class="bg-gray-900 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full group-hover:scale-125 transition-transform duration-1000"></div>
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-8 border-b border-white/10 pb-4">Personal Narrative</h3>
                <p class="text-sm text-gray-300 leading-relaxed font-medium italic opacity-80">"{{ $user->bio }}"</p>
            </div>
            @endif

            <!-- Governance Card -->
            <div class="bg-indigo-600 rounded-[2.5rem] p-10 text-white shadow-xl relative overflow-hidden group cursor-pointer">
                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>
                <h3 class="text-2xl font-black mb-4">Core Safety</h3>
                <p class="text-xs text-indigo-100 leading-relaxed opacity-70">Your profile is your reputation within the community. Higher verification levels unlock tiered financial benefits and priority processing.</p>
                <div class="mt-8 flex items-center gap-2 text-[10px] font-black uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    Governance Tier: Member
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
