@extends('layouts.admin')

@section('page-title', 'Timezone & Date Format')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Timezone & Date Format</h1>
        <p class="text-white text-opacity-90">Configure timezone and date display format</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.system-settings.timezone-date-format.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                    <select name="timezone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="Africa/Dar_es_Salaam" {{ ($settings['timezone']->value ?? 'Africa/Dar_es_Salaam') === 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Africa/Dar_es_Salaam (EAT)</option>
                        <option value="UTC" {{ ($settings['timezone']->value ?? '') === 'UTC' ? 'selected' : '' }}>UTC</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                    <select name="date_format" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#015425] focus:ring-[#015425]">
                        <option value="Y-m-d" {{ ($settings['date_format']->value ?? 'Y-m-d') === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                        <option value="d/m/Y" {{ ($settings['date_format']->value ?? '') === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                        <option value="m/d/Y" {{ ($settings['date_format']->value ?? '') === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                    </select>
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

