@extends('layouts.member')

@section('page-title', 'Edit Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h2 class="text-xl sm:text-2xl font-bold text-[#015425] mb-6">Edit Profile</h2>
        
        <form action="{{ route('member.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            @error('name')<p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            @error('email')<p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            @error('phone')<p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Address</label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                            @error('address')<p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Bio</label>
                    <textarea name="bio" rows="4" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">{{ old('bio', $user->bio) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Tell us a little about yourself</p>
                    @error('bio')<p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-sm sm:text-base">
                    Save Changes
                </button>
                <a href="{{ route('member.profile.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-center text-sm sm:text-base">
                    Cancel
                </a>
            </div>
        </form>

        <!-- Password Change Section -->
        <div id="password" class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Change Password</h3>
            <form action="{{ route('member.profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                        <input type="password" name="current_password" required class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        @error('current_password')<p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">New Password *</label>
                        <input type="password" name="password" required class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                        <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                        @error('password')<p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
                        <input type="password" name="password_confirmation" required class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition text-sm sm:text-base">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

