@extends('layouts.admin')

@section('page-title', 'Activity Logs')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Activity Logs</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Comprehensive activity tracking for all users</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition font-medium">
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <form method="GET" action="{{ route('admin.users.activity-logs') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <select name="user_id" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                <option value="">All Users</option>
                @foreach($users as $userOption)
                    <option value="{{ $userOption->id }}" {{ request('user_id') == $userOption->id ? 'selected' : '' }}>
                        {{ $userOption->name }}
                    </option>
                @endforeach
            </select>
            <select name="activity_type" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                <option value="">All Activities</option>
                <option value="profile_update" {{ request('activity_type') == 'profile_update' ? 'selected' : '' }}>Profile Updates</option>
                <option value="loan" {{ request('activity_type') == 'loan' ? 'selected' : '' }}>Loan Activities</option>
                <option value="savings" {{ request('activity_type') == 'savings' ? 'selected' : '' }}>Savings Activities</option>
                <option value="investment" {{ request('activity_type') == 'investment' ? 'selected' : '' }}>Investment Activities</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            <div class="md:col-span-4 flex justify-end space-x-4">
                <a href="{{ route('admin.users.activity-logs') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Clear Filters
                </a>
                <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Related Items</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#015425] to-[#027a3a] flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Profile Update
                                    </span>
                                    @if($user->loans_count > 0)
                                        <div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Loans ({{ $user->loans_count }})
                                            </span>
                                        </div>
                                    @endif
                                    @if($user->savings_accounts_count > 0)
                                        <div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Savings ({{ $user->savings_accounts_count }})
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">
                                <div class="space-y-1">
                                    <div>Account last updated</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $user->updated_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->updated_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">
                                <div class="space-y-1">
                                    @if($user->loans_count > 0)
                                        <div>Loans: {{ $user->loans_count }}</div>
                                    @endif
                                    @if($user->savings_accounts_count > 0)
                                        <div>Savings: {{ $user->savings_accounts_count }}</div>
                                    @endif
                                    @if($user->roles->count() > 0)
                                        <div>Roles: {{ $user->roles->count() }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-[#015425] hover:text-[#013019]">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                No activity logs found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-4 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Activity Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Total Users Tracked</h3>
            <p class="text-2xl font-bold text-[#015425]">{{ $users->total() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Active Today</h3>
            <p class="text-2xl font-bold text-green-600">{{ $users->where('updated_at', '>=', now()->startOfDay())->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <h3 class="text-sm font-medium text-gray-600 mb-2">This Week</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $users->where('updated_at', '>=', now()->startOfWeek())->count() }}</p>
        </div>
    </div>
</div>
@endsection

