@extends('layouts.admin')

@section('page-title', 'Share Certificate Template')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#015425] to-[#027a3a] rounded-lg shadow-lg p-6 text-white">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Share Certificate Template</h1>
            <p class="text-white text-opacity-90 text-sm sm:text-base">Design and manage share certificate templates</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Certificate Template Design</h2>
        <form method="POST" action="#" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Title</label>
                    <input type="text" name="title" value="SHARE CERTIFICATE" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Organization Name</label>
                    <input type="text" name="org_name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Text Template</label>
                <textarea name="template_text" rows="8" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">This certifies that {member_name} is the owner of {share_count} shares of {organization_name}...</textarea>
                <p class="text-xs text-gray-500 mt-1">Available variables: {member_name}, {share_count}, {certificate_number}, {issue_date}, {organization_name}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Footer Text</label>
                <input type="text" name="footer_text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#015425] focus:border-[#015425]">
            </div>
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <button type="button" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">Preview</button>
                <button type="submit" class="px-6 py-2 bg-[#015425] text-white rounded-md hover:bg-[#013019] transition">Save Template</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-[#015425] mb-6">Template Preview</h2>
        <div class="border-2 border-gray-300 rounded-lg p-8 bg-gray-50">
            <div class="text-center">
                <h3 class="text-2xl font-bold mb-4">SHARE CERTIFICATE</h3>
                <p class="mb-8">This certifies that <strong>[Member Name]</strong> is the owner of <strong>[Share Count]</strong> shares...</p>
                <div class="mt-8 pt-8 border-t">
                    <p>Certificate #: <strong>[Certificate Number]</strong></p>
                    <p>Date: <strong>[Issue Date]</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

