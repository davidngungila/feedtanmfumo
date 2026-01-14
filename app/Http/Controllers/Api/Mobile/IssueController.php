<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $issues = Issue::where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($issue) {
                return [
                    'id' => $issue->id,
                    'title' => $issue->title,
                    'category' => $issue->category,
                    'priority' => $issue->priority,
                    'status' => $issue->status,
                    'created_at' => $issue->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $issues,
        ]);
    }

    public function show(Issue $issue)
    {
        if ($issue->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $issue->id,
                'title' => $issue->title,
                'description' => $issue->description,
                'category' => $issue->category,
                'priority' => $issue->priority,
                'status' => $issue->status,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $issue = Issue::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Issue submitted successfully',
            'data' => [
                'issue_id' => $issue->id,
            ],
        ], 201);
    }
}
