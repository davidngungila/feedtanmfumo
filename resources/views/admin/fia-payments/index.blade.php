@extends('layouts.admin')

@section('page-title', 'FIA Payment Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">FIA Payment Dashboard</h1>
                <p class="text-gray-600 mt-1">Manage and verify FIA payment confirmations</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.fia-payments.confirmations') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    View All Confirmations
                </a>
                <a href="{{ route('admin.fia-payment-records.upload') }}" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Upload Excel Sheet
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Confirmations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalConfirmations) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Verified Confirmations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($verifiedConfirmations) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Confirmations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($pendingConfirmations) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Amount</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalAmount, 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Recent Payment Activity</h2>
        </div>
        <div class="p-6">
            <div id="recent-activity" class="space-y-4">
                @forelse($recentConfirmations as $confirmation)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-600 font-medium text-sm">{{ substr($confirmation->member_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $confirmation->member_name }}</p>
                                <p class="text-sm text-gray-500">ID: {{ $confirmation->member_id }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">{{ number_format($confirmation->amount_to_pay, 0) }}</p>
                            <p class="text-sm text-gray-500">{{ $confirmation->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500">No recent activity found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load recent activity
    loadRecentActivity();
    
    // Auto-refresh every 30 seconds
    setInterval(loadRecentActivity, 30000);
});

function loadRecentActivity() {
    fetch('{{ route("admin.fia-payments.statistics") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data.recent_payments) {
            const container = document.getElementById('recent-activity');
            
            if (data.data.recent_payments.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500">No recent payment activity</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = data.data.recent_payments.map(payment => {
                const statusColor = payment.status === 'verified' ? 'green' : 
                                  payment.status === 'rejected' ? 'red' : 'yellow';
                const statusBg = payment.status === 'verified' ? 'bg-green-100 text-green-800' : 
                               payment.status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800';
                
                return `
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-${statusColor}-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-${statusColor}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${payment.status === 'verified' ? 
                                      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' :
                                      payment.status === 'rejected' ?
                                      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' :
                                      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                                    }
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">${payment.member_name || 'Unknown Member'}</p>
                                <p class="text-sm text-gray-500">${payment.membership_code} • ${payment.reference_number}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">${payment.amount.toLocaleString()}</p>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full ${statusBg}">
                                ${payment.status}
                            </span>
                        </div>
                    </div>
                `;
            }).join('');
        }
    })
    .catch(error => {
        console.error('Error loading recent activity:', error);
        document.getElementById('recent-activity').innerHTML = `
            <div class="text-center py-8">
                <p class="text-red-500">Error loading recent activity</p>
            </div>
        `;
    });
}
</script>
@endsection
