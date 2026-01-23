@extends('layouts.admin')

@section('page-title', 'Roles Management')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Roles Management</h1>
        <p class="text-white text-opacity-90">Manage user roles and their permissions</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Roles List -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Roles</h2>
            <div class="space-y-3">
                @forelse($roles as $role)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-[#015425] transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $role->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $role->description }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $role->permissions->count() }} permissions assigned</p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500">No roles found</p>
                @endforelse
            </div>
        </div>

        <!-- Permissions by Group -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Available Permissions</h2>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @foreach($permissions as $group => $perms)
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">{{ ucfirst($group) }}</h3>
                    <div class="space-y-1">
                        @foreach($perms as $permission)
                        <div class="text-sm text-gray-600 pl-4">{{ $permission->name }}</div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

