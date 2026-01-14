@extends('layouts.admin')

@section('page-title', 'Role Management')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Role Management</h1>
                <p class="text-white text-opacity-90 text-sm sm:text-base">Manage roles and their associated permissions</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-md transition font-medium">
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <!-- Roles Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach($roles->take(4) as $role)
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">{{ $role->name }}</p>
                        <p class="text-2xl font-bold text-[#015425] mt-1">{{ $role->users_count }}</p>
                        <p class="text-xs text-gray-500 mt-1">users assigned</p>
                    </div>
                    <div class="w-12 h-12 bg-[#015425]/10 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#015425]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Roles Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        @foreach($roles as $role)
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex justify-between items-start mb-4 pb-4 border-b border-gray-200">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-[#015425]">{{ $role->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $role->description ?? 'No description provided' }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-xs text-gray-400">
                                <span class="font-semibold">{{ $role->users_count }}</span> users
                            </span>
                            <span class="text-xs text-gray-400">
                                <span class="font-semibold">{{ $role->permissions->count() }}</span> permissions
                            </span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.users.roles.update', $role) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        @foreach($permissions as $group => $groupPermissions)
                            <div class="border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                                <h4 class="font-semibold text-gray-900 mb-3 text-sm uppercase tracking-wide">{{ ucfirst($group) }}</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($groupPermissions as $permission)
                                        <label class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded cursor-pointer transition">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                   {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-[#015425] focus:ring-[#015425]">
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="mt-6 w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition font-medium">
                        Update Permissions for {{ $role->name }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
