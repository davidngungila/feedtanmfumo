@extends('layouts.member')

@section('page-title', 'Ticket Analysis')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumbs & Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-[0.2em]">
                    <li><a href="{{ route('member.issues.index') }}" class="text-gray-400 hover:text-[#015425] transition">Support</a></li>
                    <li class="text-gray-300">/</li>
                    <li class="text-[#015425]">Ticket #{{ $issue->issue_number }}</li>
                </ol>
            </nav>
            <h1 class="text-3xl font-black text-gray-900">Ticket Analysis</h1>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('member.issues.index') }}" class="flex-1 md:flex-none px-6 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 transition text-center text-xs">
                Back to Desk
            </a>
            <button class="flex-1 md:flex-none px-6 py-2.5 bg-[#015425] text-white rounded-xl font-bold hover:bg-[#013019] transition text-center text-xs shadow-lg">
                Mark Duplicate
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Detail Card -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 sm:p-10 border-b border-gray-50">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                        <h2 class="text-2xl font-black text-gray-900 leading-tight">{{ $issue->title }}</h2>
                        <div class="shrink-0">
                            @if($issue->status === 'resolved')
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-full">Resolved</span>
                            @elseif($issue->status === 'in_progress')
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-full">In Progress</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-50 text-yellow-600 text-[10px] font-black uppercase tracking-widest rounded-full">Awaiting Review</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Created</p>
                            <p class="text-xs font-black text-gray-900">{{ $issue->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Priority</p>
                            <span class="text-xs font-black uppercase tracking-tighter {{ $issue->priority === 'urgent' ? 'text-red-500' : 'text-gray-900' }}">
                                {{ $issue->priority }}
                            </span>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Category</p>
                            <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ $issue->category }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Asset ID</p>
                            <p class="text-xs font-black text-gray-400 font-mono">{{ $issue->issue_number }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 sm:p-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Internal Log</h3>
                    <div class="bg-gray-50 rounded-3xl p-6 sm:p-8 text-sm text-gray-700 leading-relaxed font-medium whitespace-pre-wrap border border-gray-100">
                        {{ $issue->description }}
                    </div>
                </div>
            </div>

            <!-- Resolution Section -->
            @if($issue->resolution)
            <div class="bg-indigo-50/50 rounded-[2.5rem] p-8 sm:p-10 border border-indigo-100 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-indigo-400 uppercase tracking-widest">Resolution Summary</h3>
                        <p class="text-sm font-black text-indigo-900">Officer Endorsed Solution</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 sm:p-8 text-sm text-gray-700 leading-relaxed font-medium whitespace-pre-wrap shadow-sm">
                    {{ $issue->resolution }}
                </div>
            </div>
            @else
             <div class="bg-gray-50 rounded-[2.5rem] p-8 sm:p-10 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-1">Awaiting Resolution</h3>
                <p class="text-xs text-gray-300">A community officer has not yet provided a final resolution for this ticket.</p>
            </div>
            @endif
        </div>

        <!-- Sidebar Insights -->
        <div class="space-y-6">
            <!-- Timeline -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-8 border-b border-gray-50 pb-4">Audit History</h3>
                <div class="space-y-8">
                    <div class="relative pl-6 border-l-2 border-green-500">
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-green-500 border-4 border-white"></div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Recorded</p>
                        <p class="text-xs font-black text-gray-900">{{ $issue->created_at->format('d M, Y — H:i') }}</p>
                    </div>
                    
                    <div class="relative pl-6 border-l-2 {{ $issue->status !== 'pending' ? 'border-indigo-500' : 'border-gray-200' }}">
                         <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full {{ $issue->status !== 'pending' ? 'bg-indigo-500' : 'bg-gray-200' }} border-4 border-white"></div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Escalation</p>
                        <p class="text-xs font-black text-gray-900">{{ $issue->status !== 'pending' ? 'Processing initiated' : 'Queued for review' }}</p>
                    </div>

                    <div class="relative pl-6 border-l-2 {{ $issue->status === 'resolved' ? 'border-green-500' : 'border-gray-200' }}">
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full {{ $issue->status === 'resolved' ? 'bg-green-500' : 'bg-gray-200' }} border-4 border-white"></div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Maturity</p>
                        <p class="text-xs font-black text-gray-900">{{ $issue->status === 'resolved' ? 'Case closed' : 'Awaiting finality' }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Card -->
            <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/5 rounded-full group-hover:scale-110 transition-transform"></div>
                <h3 class="text-sm font-black uppercase tracking-widest mb-6 text-gray-500">Add Addenum</h3>
                <p class="text-xs text-gray-400 leading-relaxed mb-8">If you have additional evidence or information regarding this case, please contact the community desk directly.</p>
                <div class="flex gap-2">
                    <button class="flex-1 py-4 bg-white text-gray-900 rounded-2xl font-black text-[10px] hover:bg-gray-100 transition-all uppercase tracking-widest">Call Office</button>
                    <button class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white hover:bg-white/20 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
