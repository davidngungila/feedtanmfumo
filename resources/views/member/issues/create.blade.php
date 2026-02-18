@extends('layouts.member')

@section('page-title', 'Open Support Ticket')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Premium Header -->
    <div class="bg-gradient-to-br from-[#015425] via-[#027a3a] to-[#013019] rounded-[2.5rem] shadow-2xl p-10 sm:p-14 text-white relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <h1 class="text-4xl sm:text-5xl font-black mb-6 tracking-tight">Support Desk</h1>
            <p class="text-green-50 text-lg sm:text-xl opacity-80 max-w-xl leading-relaxed font-medium">Identify concerns, suggest improvements, or ask for guidance. Our resolution team is ready to assist.</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-12 overflow-hidden">
        <form action="{{ route('member.issues.store') }}" method="POST">
            @csrf
            
            <div class="space-y-10">
                <!-- Section: Core Details -->
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Case Title</label>
                        <input type="text" name="title" required 
                            class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl text-lg font-black text-gray-900 focus:ring-2 focus:ring-[#015425] transition-all"
                            placeholder="Brief summary of the concern...">
                        @error('title')<p class="mt-2 text-[10px] font-bold text-red-600 ml-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Detailed Narrative</label>
                        <textarea name="description" rows="6" required 
                            class="w-full px-6 py-5 bg-gray-50 border-none rounded-3xl text-sm font-medium text-gray-700 focus:ring-2 focus:ring-[#015425] transition-all"
                            placeholder="Provide a comprehensive breakdown of the situation, including any relevant dates, amounts, or names..."></textarea>
                        @error('description')<p class="mt-2 text-[10px] font-bold text-red-600 ml-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Section: Categorization -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-10 border-t border-gray-50">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Issue Domain</label>
                        <select name="category" required 
                            class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-[#015425] transition-all">
                            <option value="complaint">Complaint</option>
                            <option value="suggestion">Community Suggestion</option>
                            <option value="inquiry">General Inquiry</option>
                            <option value="request">Formal Request</option>
                            <option value="other">Miscellaneous</option>
                        </select>
                         @error('category')<p class="mt-2 text-[10px] font-bold text-red-600 ml-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Filing Priority</label>
                        <select name="priority" required 
                            class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-[#015425] transition-all">
                            <option value="low">Standard Review</option>
                            <option value="medium">Medium Priority</option>
                            <option value="high">High Priority</option>
                            <option value="urgent">Urgent Escalation</option>
                        </select>
                         @error('priority')<p class="mt-2 text-[10px] font-bold text-red-600 ml-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Security & Submission -->
            <div class="mt-12 pt-10 border-t border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-4 text-xs text-gray-400 font-bold max-w-sm">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 shrink-0">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    Reporting issues helps us improve community governance for everyone.
                </div>
                <div class="flex gap-4 w-full sm:w-auto">
                    <a href="{{ route('member.issues.index') }}" class="flex-1 sm:flex-none px-10 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-xs hover:bg-gray-200 transition-all text-center">
                        Discard Case
                    </a>
                    <button type="submit" class="flex-1 sm:flex-none px-12 py-4 bg-[#015425] text-white rounded-2xl font-black text-xs shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all">
                        Initialize Ticket
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Help Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-900 rounded-[2rem] p-8 text-white">
            <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4">AVG RESPONSE</h4>
            <p class="text-2xl font-black">24 Hours</p>
            <p class="text-[9px] text-gray-500 mt-2 font-bold">Standard community response time</p>
        </div>
        <div class="bg-indigo-600 rounded-[2rem] p-8 text-white">
            <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-300 mb-4">PRIVACY</h4>
            <p class="text-2xl font-black">Encrypted</p>
            <p class="text-[9px] text-indigo-300 mt-2 font-bold">Your tickets are strictly confidential</p>
        </div>
        <div class="bg-white rounded-[2rem] p-8 border border-gray-100">
            <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">FOLLOW-UP</h4>
            <p class="text-2xl font-black text-gray-900">Live Status</p>
            <p class="text-[9px] text-gray-400 mt-2 font-bold">Track stage-by-stage progress</p>
        </div>
    </div>
</div>
@endsection
