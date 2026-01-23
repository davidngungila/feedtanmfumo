@extends('layouts.admin')

@section('page-title', 'Logo & Branding')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Logo & Branding</h1>
        <p class="text-white text-opacity-90">Customize organization logo and branding</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.settings.organization.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Organization Logo</label>
                    @if($settings['logo']->value ?? null)
                    <div class="mb-4">
                        <img src="{{ $settings['logo']->value }}" alt="Logo" class="h-32 w-auto">
                    </div>
                    @endif
                    <input type="file" name="logo" accept="image/*" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                    <p class="mt-1 text-sm text-gray-500">Upload a new logo (PNG, JPG, max 2MB)</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

