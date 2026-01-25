@extends('layouts.admin')

@section('page-title', 'IP Whitelisting')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">IP Whitelisting / Blacklisting</h1>
        <p class="text-white text-opacity-90">Manage IP address access control</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Add IP Address -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Add IP Address</h2>
            <form action="{{ route('admin.system-settings.add-ip-address') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IP Address</label>
                        <input type="text" name="ip_address" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                            <option value="whitelist">Whitelist</option>
                            <option value="blacklist">Blacklist</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="2" 
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]"></textarea>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">
                        Add IP Address
                    </button>
                </div>
            </form>
        </div>

        <!-- IP Lists -->
        <div class="space-y-6">
            <!-- Whitelist -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Whitelist</h2>
                <div class="space-y-2">
                    @forelse($whitelist as $ip)
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">{{ $ip->ip_address }}</div>
                            <div class="text-sm text-gray-500">{{ $ip->description }}</div>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $ip->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $ip->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">No whitelisted IPs</p>
                    @endforelse
                </div>
            </div>

            <!-- Blacklist -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Blacklist</h2>
                <div class="space-y-2">
                    @forelse($blacklist as $ip)
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">{{ $ip->ip_address }}</div>
                            <div class="text-sm text-gray-500">{{ $ip->description }}</div>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $ip->is_active ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $ip->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">No blacklisted IPs</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


