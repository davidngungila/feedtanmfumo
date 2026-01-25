@extends('layouts.admin')

@section('page-title', 'Permissions Management')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Permissions Management</h1>
        <p class="text-white text-opacity-90">View and manage system permissions</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="space-y-6">
            @foreach($permissions as $group => $perms)
            <div class="border-b border-gray-200 pb-4 last:border-0">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ ucfirst($group) }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($perms as $permission)
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $permission->name }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $permission->description }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $permission->roles->count() }} roles have this permission</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection


