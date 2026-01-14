@extends('layouts.admin')

@section('page-title', 'User Permissions')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">User Permissions</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage permissions for officials and staff members</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition font-medium">
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <form method="GET" action="{{ route('admin.users.permissions') }}" class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.users.permissions') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Users with Permissions -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-[#015425]">Officials & Staff Permissions</h2>
            <p class="text-sm text-gray-600 mt-1">View and manage permissions assigned to each user through their roles</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
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
                                        <div class="text-xs text-gray-400 mt-1">{{ ucfirst($user->role) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400">No roles assigned</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $allPermissions = collect();
                                    foreach ($user->roles as $role) {
                                        $allPermissions = $allPermissions->merge($role->permissions);
                                    }
                                    $uniquePermissions = $allPermissions->unique('id');
                                @endphp
                                <div class="max-w-md">
                                    <div class="text-xs text-gray-600 mb-1">
                                        {{ $uniquePermissions->count() }} permission(s)
                                    </div>
                                    <div class="flex flex-wrap gap-1 max-h-20 overflow-y-auto">
                                        @foreach($uniquePermissions->take(5) as $permission)
                                            <span class="px-2 py-0.5 text-xs rounded bg-green-100 text-green-800">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                        @if($uniquePermissions->count() > 5)
                                            <span class="px-2 py-0.5 text-xs text-gray-500">
                                                +{{ $uniquePermissions->count() - 5 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-[#015425] hover:text-[#013019]">
                                    Manage Roles
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                No officials or staff members found
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

    <!-- Permission Groups Overview -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h2 class="text-lg font-semibold text-[#015425] mb-4">Available Permissions by Category</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($allPermissions as $group => $permissions)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">{{ ucfirst($group) }}</h3>
                    <div class="space-y-2">
                        @foreach($permissions as $permission)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700">{{ $permission->name }}</span>
                                <span class="text-xs text-gray-500">{{ $permission->roles->count() }} role(s)</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

