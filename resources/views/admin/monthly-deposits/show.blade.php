@extends('layouts.admin')

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('page-title')
    Records for {{ date("F", mktime(0, 0, 0, $month, 10)) }} {{ $year }}
@endsection

@section('content')
<div x-data="{ 
    selectedRecord: null,
    loading: false,
    async selectRecord(id) {
        this.loading = true;
        try {
            // Simplified for now: just use data from the row or a small object
            // In a real app, this could be an AJAX call to get fresh data
            const row = document.querySelector(`[data-id='${id}']`);
            this.selectedRecord = JSON.parse(row.dataset.info);
        } finally {
            this.loading = false;
        }
    }
}" class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.monthly-deposits.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center font-medium">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
        <form action="{{ route('admin.monthly-deposits.destroy', ['year' => $year, 'month' => $month]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all records for this month?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-4 py-2 rounded-lg transition font-medium border border-red-100">
                Delete All Month Records
            </button>
        </form>
    </div>

    <!-- Split Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 relative">
        <!-- List View -->
        <div :class="selectedRecord ? 'lg:col-span-8' : 'lg:col-span-12'" class="transition-all duration-300">
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Member</th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Docs</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($deposits as $deposit)
                            @php
                                $info = $deposit->only(['id', 'member_id', 'name', 'email', 'savings', 'shares', 'welfare', 'loan_principal', 'loan_interest', 'fine_penalty', 'total', 'statement_pdf', 'generated_message', 'notes']);
                                $info['month_name'] = $deposit->month_name;
                                $info['record_url'] = route('admin.monthly-deposits.record', $deposit->id);
                            @endphp
                            <tr 
                                data-id="{{ $deposit->id }}" 
                                data-info='@json($info)'
                                @click="selectRecord({{ $deposit->id }})"
                                :class="selectedRecord && selectedRecord.id == {{ $deposit->id }} ? 'bg-green-50 border-l-4 border-l-[#015425]' : 'hover:bg-gray-50'"
                                class="cursor-pointer transition-all border-l-4 border-l-transparent"
                            >
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900">{{ $deposit->name }}</span>
                                        <span class="text-xs font-mono text-[#015425]">{{ $deposit->member_id }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-black text-gray-900">{{ number_format($deposit->total, 2) }}</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($deposit->generated_message)
                                            <span class="w-2 h-2 bg-blue-500 rounded-full" title="Has Message"></span>
                                        @endif
                                        @if($deposit->statement_pdf)
                                            <span class="w-2 h-2 bg-orange-500 rounded-full" title="Has PDF"></span>
                                        @endif
                                        @if($deposit->user_id)
                                            <span class="w-2 h-2 bg-green-500 rounded-full" title="Linked"></span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('admin.monthly-deposits.record', $deposit->id) }}" class="text-xs font-bold text-[#015425] hover:underline" @click.stop>
                                        Full Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $deposits->links() }}
                </div>
            </div>
        </div>

        <!-- Preview Panel -->
        <div 
            x-show="selectedRecord" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-12"
            x-transition:enter-end="opacity-100 translate-x-0"
            class="lg:col-span-4 sticky top-24 h-fit space-y-4 no-print"
        >
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 bg-[#015425] text-white flex justify-between items-center">
                    <h3 class="font-bold uppercase tracking-tight">Quick Preview</h3>
                    <button @click="selectedRecord = null" class="text-white hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 space-y-6">
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Member</p>
                        <h4 class="text-xl font-black text-gray-900" x-text="selectedRecord?.name"></h4>
                        <p class="text-sm font-mono text-[#015425]" x-text="selectedRecord?.member_id"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Savings</p>
                            <p class="text-sm font-bold text-gray-900" x-text="new Intl.NumberFormat().format(selectedRecord?.savings)"></p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Shares</p>
                            <p class="text-sm font-bold text-gray-900" x-text="new Intl.NumberFormat().format(selectedRecord?.shares)"></p>
                        </div>
                    </div>

                    <div class="p-4 bg-green-50 rounded-xl border border-green-100 flex justify-between items-center">
                        <span class="text-xs font-black text-[#015425] uppercase tracking-widest">Total</span>
                        <span class="text-lg font-black text-[#015425]" x-text="new Intl.NumberFormat().format(selectedRecord?.total) + ' TZS'"></span>
                    </div>

                    <template x-if="selectedRecord?.generated_message">
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 italic text-xs text-blue-800" x-text="selectedRecord?.generated_message"></div>
                    </template>

                    <div class="flex flex-col gap-2">
                        <a :href="selectedRecord?.record_url" class="w-full text-center py-3 bg-[#015425] text-white rounded-xl font-bold hover:bg-[#027a3a] transition-all">
                            View Full Details
                        </a>
                        <template x-if="selectedRecord?.statement_pdf">
                            <a :href="selectedRecord?.statement_pdf" target="_blank" class="w-full text-center py-3 bg-orange-600 text-white rounded-xl font-bold hover:bg-orange-700 transition-all flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Official PDF
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
