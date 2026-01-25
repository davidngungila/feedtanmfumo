@extends('layouts.admin')

@section('page-title', 'Menu Builder')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Menu Builder</h1>
        <p class="text-white text-opacity-90">Customize menu structure</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="space-y-4">
            <p class="text-sm text-gray-600">Menu builder functionality coming soon. This will allow you to customize the navigation menu structure.</p>
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">Features</h3>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Drag and drop menu items</li>
                    <li>Add custom menu items</li>
                    <li>Reorder menu structure</li>
                    <li>Set menu permissions</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection


