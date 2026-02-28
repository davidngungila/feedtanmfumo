@extends('layouts.member')

@section('page-title', 'Open Support Ticket')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 sm:p-8 text-white">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Open Issue</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Submit a ticket to the community support desk and track resolution progress.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="{{ route('member.issues.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white/10 text-white rounded-md border border-white/20 hover:bg-white/20 transition font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Issues
                </a>
                <button type="button" onclick="document.getElementById('issue-form')?.scrollIntoView({behavior: 'smooth', block: 'start'});" class="inline-flex items-center justify-center px-4 py-2 bg-white text-[#015425] rounded-md hover:bg-gray-100 transition font-medium">
                    Review & Submit
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-100 p-4 sm:p-5">
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
            <a href="{{ route('member.loans.index') }}" class="px-3 py-2 rounded-md bg-blue-50 text-blue-700 text-xs font-bold text-center hover:bg-blue-100 transition">Loans</a>
            <a href="{{ route('member.savings.index') }}" class="px-3 py-2 rounded-md bg-green-50 text-[#015425] text-xs font-bold text-center hover:bg-green-100 transition">Savings</a>
            <a href="{{ route('member.savings.create') }}" class="px-3 py-2 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold text-center hover:bg-emerald-100 transition">Saving Plan</a>
            <a href="{{ route('member.investments.index') }}" class="px-3 py-2 rounded-md bg-purple-50 text-purple-700 text-xs font-bold text-center hover:bg-purple-100 transition">Investments</a>
            <a href="{{ route('member.welfare.index') }}" class="px-3 py-2 rounded-md bg-amber-50 text-amber-800 text-xs font-bold text-center hover:bg-amber-100 transition">SWF</a>
            <a href="{{ route('member.issues.index') }}" class="px-3 py-2 rounded-md bg-orange-50 text-orange-700 text-xs font-bold text-center hover:bg-orange-100 transition">Issues</a>
            <a href="{{ route('member.monthly-deposits.index') }}" class="px-3 py-2 rounded-md bg-slate-50 text-slate-700 text-xs font-bold text-center hover:bg-slate-100 transition">Transactions</a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 sm:p-8 overflow-hidden">
        <form action="{{ route('member.issues.store') }}" method="POST" id="issue-form">
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
                    <a href="{{ route('member.issues.index') }}" class="flex-1 sm:flex-none px-6 py-3 bg-gray-100 text-gray-600 rounded-md font-bold text-sm hover:bg-gray-200 transition-all text-center">
                        Discard Case
                    </a>
                    <button type="submit" class="flex-1 sm:flex-none px-6 py-3 bg-[#015425] text-white rounded-md font-bold text-sm shadow-md hover:bg-[#013019] transition-all">
                        Submit Ticket
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Help Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-900 rounded-lg p-8 text-white">
            <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4">AVG RESPONSE</h4>
            <p class="text-2xl font-black">24 Hours</p>
            <p class="text-[9px] text-gray-500 mt-2 font-bold">Standard community response time</p>
        </div>
        <div class="bg-indigo-600 rounded-lg p-8 text-white">
            <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-300 mb-4">PRIVACY</h4>
            <p class="text-2xl font-black">Encrypted</p>
            <p class="text-[9px] text-indigo-300 mt-2 font-bold">Your tickets are strictly confidential</p>
        </div>
        <div class="bg-white rounded-lg p-8 border border-gray-100">
            <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">FOLLOW-UP</h4>
            <p class="text-2xl font-black text-gray-900">Live Status</p>
            <p class="text-[9px] text-gray-400 mt-2 font-bold">Track stage-by-stage progress</p>
        </div>
    </div>
</div>
@endsection
