@extends('layouts.member')

@section('page-title', 'Support Resolution Center')

@section('content')
<div class="space-y-8">
    <!-- Premium Header -->
    <div class="bg-gradient-to-br from-[#015425] via-[#027a3a] to-[#013019] rounded-[2.5rem] shadow-2xl p-8 sm:p-12 text-white relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-black opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <div class="max-w-2xl">
                    <h1 class="text-4xl sm:text-5xl font-black mb-4 tracking-tight">Resolution Center</h1>
                    <p class="text-green-50 text-base sm:text-xl opacity-80 leading-relaxed font-medium">Your voice matters. Report issues, track disputes, and get the support you need from our community desk.</p>
                </div>
                <div class="w-full md:w-auto">
                     <a href="{{ route('member.issues.create') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-white text-[#015425] rounded-2xl font-black shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Report New Issue
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Total Tickets</p>
            <p class="text-3xl font-black text-gray-900">{{ $stats['total'] }}</p>
            <div class="mt-2 h-1 w-12 bg-gray-200 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">In Queue</p>
            <p class="text-3xl font-black text-yellow-500">{{ $stats['pending'] }}</p>
            <div class="mt-2 h-1 w-12 bg-yellow-500 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Processing</p>
            <p class="text-3xl font-black text-blue-600">{{ $stats['in_progress'] }}</p>
            <div class="mt-2 h-1 w-12 bg-blue-500 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Resolved</p>
            <p class="text-3xl font-black text-green-600">{{ $stats['resolved'] }}</p>
            <div class="mt-2 h-1 w-12 bg-green-500 rounded-full"></div>
        </div>
    </div>

    <!-- Issues Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="text-xl font-black text-gray-900">Support Tickets</h2>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Filter:</span>
                <select class="text-xs font-bold text-gray-600 bg-gray-50 border-none rounded-xl focus:ring-0">
                    <option>All Tickets</option>
                    <option>Urgent Only</option>
                    <option>Unresolved</option>
                </select>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ticket Details</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Priority</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Category</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Current State</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($issues as $issue)
                        <tr class="group hover:bg-gray-50/50 transition-all duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 font-black text-[10px]">
                                        {{ substr($issue->issue_number, -2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-900">{{ \Illuminate\Support\Str::limit($issue->title, 40) }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $issue->issue_number }} — {{ $issue->created_at->format('d M, Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $prioColors = [
                                        'urgent' => 'bg-red-50 text-red-600',
                                        'high' => 'bg-orange-50 text-orange-600',
                                        'medium' => 'bg-yellow-50 text-yellow-600',
                                        'low' => 'bg-gray-50 text-gray-600'
                                    ];
                                    $prioColor = $prioColors[$issue->priority] ?? 'bg-gray-50 text-gray-600';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $prioColor }}">
                                    {{ $issue->priority }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter">{{ $issue->category }}</span>
                            </td>
                            <td class="px-8 py-6">
                                @if($issue->status === 'resolved')
                                    <span class="flex items-center gap-1.5 text-[10px] font-black text-green-600 uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Closed
                                    </span>
                                @elseif($issue->status === 'in_progress')
                                    <span class="flex items-center gap-1.5 text-[10px] font-black text-blue-600 uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Escalated
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 text-[10px] font-black text-yellow-600 uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                        In Queue
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('member.issues.show', $issue) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#015425] group-hover:text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                         <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 mb-2">Internal Peace of Mind</h3>
                                    <p class="text-xs text-gray-400 leading-relaxed mb-8">You haven't reported any issues. Everything looks operative on your end.</p>
                                    <a href="{{ route('member.issues.create') }}" class="inline-block px-10 py-4 bg-[#015425] text-white rounded-2xl font-black text-xs shadow-xl hover:shadow-2xl transition-all">Create Ticket</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($issues->hasPages())
            <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">
                {{ $issues->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
