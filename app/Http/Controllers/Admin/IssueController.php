<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        $query = Issue::with(['user', 'assignedTo', 'resolvedBy']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority !== '') {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('issue_number', 'like', '%' . $request->search . '%');
            });
        }

        $issues = $query->latest()->paginate(20)->withQueryString();
        
        $stats = [
            'total' => Issue::count(),
            'pending' => Issue::where('status', 'pending')->count(),
            'in_progress' => Issue::where('status', 'in_progress')->count(),
            'resolved' => Issue::where('status', 'resolved')->count(),
            'high_priority' => Issue::where('priority', 'high')->orWhere('priority', 'urgent')->count(),
        ];

        $staff = User::where('role', '!=', 'user')->orWhereHas('roles', function($q) {
            $q->where('slug', '!=', 'member');
        })->get();

        return view('admin.issues.index', compact('issues', 'stats', 'staff'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        $staff = User::where('role', '!=', 'user')->orWhereHas('roles', function($q) {
            $q->where('slug', '!=', 'member');
        })->get();
        return view('admin.issues.create', compact('users', 'staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:complaint,suggestion,inquiry,request,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        Issue::create([
            'user_id' => $validated['user_id'],
            'issue_number' => 'ISS-' . strtoupper(Str::random(8)),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'pending',
            'assigned_to' => $validated['assigned_to'] ?? null,
        ]);

        return redirect()->route('admin.issues.index')->with('success', 'Issue created successfully.');
    }

    public function show(Issue $issue)
    {
        $issue->load(['user', 'assignedTo', 'resolvedBy']);
        $staff = User::where('role', '!=', 'user')->orWhereHas('roles', function($q) {
            $q->where('slug', '!=', 'member');
        })->get();
        return view('admin.issues.show', compact('issue', 'staff'));
    }

    public function edit(Issue $issue)
    {
        $users = User::where('role', 'user')->get();
        $staff = User::where('role', '!=', 'user')->orWhereHas('roles', function($q) {
            $q->where('slug', '!=', 'member');
        })->get();
        return view('admin.issues.edit', compact('issue', 'users', 'staff'));
    }

    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:complaint,suggestion,inquiry,request,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,resolved,closed,rejected',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution' => 'nullable|string',
        ]);

        if ($validated['status'] === 'resolved' && !$issue->resolved_at) {
            $validated['resolved_at'] = now();
            $validated['resolved_by'] = auth()->id();
        }

        $issue->update($validated);

        return redirect()->route('admin.issues.index')->with('success', 'Issue updated successfully.');
    }

    public function destroy(Issue $issue)
    {
        $issue->delete();
        return redirect()->route('admin.issues.index')->with('success', 'Issue deleted successfully.');
    }

    /**
     * Show issue tracking page with advanced tracking features
     */
    public function tracking(Request $request)
    {
        $query = Issue::with(['user', 'assignedTo', 'resolvedBy']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority !== '') {
            $query->where('priority', $request->priority);
        }

        // Filter by assigned user
        if ($request->has('assigned_to') && $request->assigned_to !== '') {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('issue_number', 'like', '%' . $request->search . '%');
            });
        }

        $issues = $query->latest()->paginate(20)->withQueryString();
        
        $stats = [
            'total' => Issue::count(),
            'pending' => Issue::where('status', 'pending')->count(),
            'in_progress' => Issue::where('status', 'in_progress')->count(),
            'resolved' => Issue::where('status', 'resolved')->count(),
            'closed' => Issue::where('status', 'closed')->count(),
            'rejected' => Issue::where('status', 'rejected')->count(),
            'high_priority' => Issue::where('priority', 'high')->orWhere('priority', 'urgent')->count(),
            'avg_resolution_time' => Issue::whereNotNull('resolved_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
                ->value('avg_hours'),
        ];

        $staff = User::where('role', '!=', 'user')->orWhereHas('roles', function($q) {
            $q->where('slug', '!=', 'member');
        })->get();

        return view('admin.issues.tracking', compact('issues', 'stats', 'staff'));
    }

    /**
     * Show resolution status page with resolution statistics
     */
    public function resolutionStatus(Request $request)
    {
        $query = Issue::whereIn('status', ['resolved', 'closed'])
            ->with(['user', 'assignedTo', 'resolvedBy']);

        // Filter by resolved date range
        if ($request->has('resolved_from') && $request->resolved_from !== '') {
            $query->whereDate('resolved_at', '>=', $request->resolved_from);
        }
        if ($request->has('resolved_to') && $request->resolved_to !== '') {
            $query->whereDate('resolved_at', '<=', $request->resolved_to);
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Filter by resolved by
        if ($request->has('resolved_by') && $request->resolved_by !== '') {
            $query->where('resolved_by', $request->resolved_by);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('issue_number', 'like', '%' . $request->search . '%');
            });
        }

        $issues = $query->latest('resolved_at')->paginate(20)->withQueryString();
        
        $stats = [
            'total_resolved' => Issue::whereIn('status', ['resolved', 'closed'])->count(),
            'resolved_this_month' => Issue::whereIn('status', ['resolved', 'closed'])
                ->whereMonth('resolved_at', now()->month)
                ->whereYear('resolved_at', now()->year)
                ->count(),
            'resolved_last_month' => Issue::whereIn('status', ['resolved', 'closed'])
                ->whereMonth('resolved_at', now()->subMonth()->month)
                ->whereYear('resolved_at', now()->subMonth()->year)
                ->count(),
            'avg_resolution_hours' => Issue::whereNotNull('resolved_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
                ->value('avg_hours'),
            'avg_resolution_days' => Issue::whereNotNull('resolved_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(DAY, created_at, resolved_at)) as avg_days')
                ->value('avg_days'),
            'by_category' => Issue::whereIn('status', ['resolved', 'closed'])
                ->selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category'),
            'by_resolver' => Issue::whereIn('status', ['resolved', 'closed'])
                ->whereNotNull('resolved_by')
                ->selectRaw('resolved_by, COUNT(*) as count')
                ->groupBy('resolved_by')
                ->get()
                ->mapWithKeys(function($item) {
                    $resolver = User::find($item->resolved_by);
                    return [$resolver->name ?? 'Unknown' => $item->count];
                }),
        ];

        $staff = User::where('role', '!=', 'user')->orWhereHas('roles', function($q) {
            $q->where('slug', '!=', 'member');
        })->get();

        return view('admin.issues.resolution-status', compact('issues', 'stats', 'staff'));
    }

    /**
     * Show issue categories page with issues grouped by category
     */
    public function categories(Request $request)
    {
        // Map database categories to display labels
        $categories = [
            'complaint' => 'Complaints',
            'suggestion' => 'Suggestions',
            'inquiry' => 'Inquiries',
            'request' => 'Requests',
            'other' => 'Other Issues',
        ];

        $selectedCategory = $request->get('category', 'all');
        
        $query = Issue::with(['user', 'assignedTo']);

        if ($selectedCategory !== 'all' && array_key_exists($selectedCategory, $categories)) {
            $query->where('category', $selectedCategory);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('issue_number', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $issues = $query->latest()->paginate(20)->withQueryString();

        // Statistics by category
        $statsByCategory = [];
        foreach ($categories as $key => $label) {
            $categoryQuery = Issue::where('category', $key);
            $statsByCategory[$key] = [
                'label' => $label,
                'total' => $categoryQuery->count(),
                'pending' => Issue::where('category', $key)->where('status', 'pending')->count(),
                'in_progress' => Issue::where('category', $key)->where('status', 'in_progress')->count(),
                'resolved' => Issue::where('category', $key)->whereIn('status', ['resolved', 'closed'])->count(),
            ];
        }

        // Overall stats
        $overallStats = [
            'total' => Issue::count(),
            'pending' => Issue::where('status', 'pending')->count(),
            'in_progress' => Issue::where('status', 'in_progress')->count(),
            'resolved' => Issue::whereIn('status', ['resolved', 'closed'])->count(),
        ];

        return view('admin.issues.categories', compact('issues', 'categories', 'selectedCategory', 'statsByCategory', 'overallStats'));
    }
    /**
     * Update multiple issues in bulk
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'issue_ids' => 'required|array',
            'issue_ids.*' => 'exists:issues,id',
            'action' => 'required|in:status,priority,assign',
            'status' => 'required_if:action,status|nullable|in:pending,in_progress,resolved,closed,rejected',
            'priority' => 'required_if:action,priority|nullable|in:low,medium,high,urgent',
            'assigned_to' => 'required_if:action,assign|nullable|exists:users,id',
        ]);

        $issues = Issue::whereIn('id', $validated['issue_ids'])->get();
        $updatedCount = 0;

        foreach ($issues as $issue) {
            $data = [];
            
            if ($validated['action'] === 'status') {
                $data['status'] = $validated['status'];
                if ($validated['status'] === 'resolved' && !$issue->resolved_at) {
                    $data['resolved_at'] = now();
                    $data['resolved_by'] = auth()->id();
                }
            } elseif ($validated['action'] === 'priority') {
                $data['priority'] = $validated['priority'];
            } elseif ($validated['action'] === 'assign') {
                $data['assigned_to'] = $validated['assigned_to'];
            }

            if (!empty($data)) {
                $issue->update($data);
                $updatedCount++;
            }
        }

        return back()->with('success', "{$updatedCount} issues updated successfully.");
    }
}
