@extends('layouts.admin')

@section('page-title', 'Member Groups/Clusters')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Member Groups/Clusters</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">View and manage member groupings by region, occupation, and age with detailed analytics</p>
        </div>
    </div>

    <!-- Group Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- By Region -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-[#015425] mb-4">By Region</h2>
            <div class="space-y-3">
                @forelse($groups['by_region'] as $group)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                        <span class="text-sm font-medium text-gray-700">{{ $group->region ?? 'Unknown' }}</span>
                        <span class="px-2 py-1 bg-[#015425] text-white text-xs font-semibold rounded-full">{{ $group->count }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No regional data available</p>
                @endforelse
            </div>
        </div>

        <!-- By Occupation -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-[#015425] mb-4 pb-2 border-b">By Occupation</h2>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($groups['by_occupation'] as $group)
                    <a href="{{ route('admin.users.groups', ['group_type' => 'occupation', 'group_value' => $group->occupation]) }}" 
                       class="flex items-center justify-between p-3 bg-gray-50 rounded-md hover:bg-gray-100 transition">
                        <span class="text-sm font-medium text-gray-700">{{ $group->occupation ?? 'Unknown' }}</span>
                        <span class="px-2 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full">{{ $group->count }}</span>
                    </a>
                @empty
                    <p class="text-sm text-gray-500">No occupation data available</p>
                @endforelse
            </div>
        </div>

        <!-- By Age Group -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-[#015425] mb-4 pb-2 border-b">By Age Group</h2>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($groups['by_age_group'] as $group)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                        <span class="text-sm font-medium text-gray-700">{{ $group->age_group ?? 'Unknown' }}</span>
                        <span class="px-2 py-1 bg-purple-600 text-white text-xs font-semibold rounded-full">{{ $group->count }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No age group data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Filtered Members List -->
    @if(request()->filled('group_type'))
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-[#015425] mb-4">
                Members in {{ ucfirst(request('group_type')) }}: {{ request('group_value') }}
            </h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
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
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ ucfirst($user->status ?? 'active') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-[#015425] hover:text-[#013019] text-sm">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">No members found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="mt-4">{{ $users->links() }}</div>
            @endif
        </div>
    @endif
</div>
@endsection

