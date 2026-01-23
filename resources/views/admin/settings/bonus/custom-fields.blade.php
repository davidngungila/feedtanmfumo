@extends('layouts.admin')

@section('page-title', 'Custom Fields')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Custom Fields</h1>
        <p class="text-white text-opacity-90">Manage custom fields for different models</p>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Custom Fields</h2>
            <button class="px-4 py-2 bg-[#015425] text-white rounded-md hover:bg-[#027a3a]">Add Field</button>
        </div>
        <div class="p-6 space-y-6">
            @foreach($fields as $modelType => $modelFields)
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">{{ class_basename($modelType) }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($modelFields as $field)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="font-medium text-gray-900">{{ $field->label }}</div>
                        <div class="text-sm text-gray-500 mt-1">{{ ucfirst($field->field_type) }}</div>
                        <div class="text-xs text-gray-400 mt-2">{{ $field->is_required ? 'Required' : 'Optional' }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

