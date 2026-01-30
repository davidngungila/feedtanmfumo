<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsMessageTemplate;
use Illuminate\Http\Request;

class SmsMessageTemplateController extends Controller
{
    public function index()
    {
        $templates = SmsMessageTemplate::orderBy('priority')->orderBy('template_name')->get();

        return view('admin.sms.templates', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'behavior_type' => 'nullable|string|max:255',
            'message_content' => 'required|string',
            'language' => 'required|string|in:sw,en',
            'priority' => 'required|integer|min:1|max:10',
            'variables' => 'nullable|string',
        ]);

        // Convert comma-separated variables string to array
        $variables = [];
        if ($request->variables) {
            $variables = array_map('trim', explode(',', $request->variables));
            $variables = array_filter($variables); // Remove empty values
        }

        SmsMessageTemplate::create([
            'template_name' => $request->template_name,
            'behavior_type' => $request->behavior_type,
            'message_content' => $request->message_content,
            'language' => $request->language,
            'priority' => $request->priority,
            'variables' => array_values($variables), // Re-index array
            'last_modified' => now(),
        ]);

        return back()->with('success', 'Template created successfully.');
    }

    public function update(Request $request, SmsMessageTemplate $smsMessageTemplate)
    {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'behavior_type' => 'nullable|string|max:255',
            'message_content' => 'required|string',
            'language' => 'required|string|in:sw,en',
            'priority' => 'required|integer|min:1|max:10',
            'variables' => 'nullable|string',
        ]);

        // Convert comma-separated variables string to array
        $variables = [];
        if ($request->variables) {
            $variables = array_map('trim', explode(',', $request->variables));
            $variables = array_filter($variables); // Remove empty values
        }

        $smsMessageTemplate->update([
            'template_name' => $request->template_name,
            'behavior_type' => $request->behavior_type,
            'message_content' => $request->message_content,
            'language' => $request->language,
            'priority' => $request->priority,
            'variables' => array_values($variables), // Re-index array
            'last_modified' => now(),
        ]);

        return back()->with('success', 'Template updated successfully.');
    }

    public function destroy(SmsMessageTemplate $smsMessageTemplate)
    {
        $smsMessageTemplate->delete();

        return back()->with('success', 'Template deleted successfully.');
    }
}
