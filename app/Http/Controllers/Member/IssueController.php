<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class IssueController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $issues = Issue::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => $issues->total(),
            'pending' => Issue::where('user_id', $user->id)->where('status', 'pending')->count(),
            'in_progress' => Issue::where('user_id', $user->id)->where('status', 'in_progress')->count(),
            'resolved' => Issue::where('user_id', $user->id)->where('status', 'resolved')->count(),
        ];

        return view('member.issues.index', compact('issues', 'stats'));
    }

    public function show(Issue $issue)
    {
        if ($issue->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $issue->load(['user', 'assignedTo']);

        return view('member.issues.show', compact('issue'));
    }

    public function create()
    {
        $staff = User::whereHas('roles', function ($q) {
            $q->whereIn('slug', ['admin', 'secretary', 'chairperson']);
        })->get();

        return view('member.issues.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:complaint,suggestion,inquiry,request,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        Issue::create([
            'user_id' => Auth::id(),
            'issue_number' => 'ISS-'.strtoupper(Str::random(8)),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'pending',
            'assigned_to' => $validated['assigned_to'] ?? null,
        ]);

        return redirect()->route('member.issues.index')->with('success', 'Issue submitted successfully.');
    }
}
