<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\SocialWelfare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WelfareController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $welfares = SocialWelfare::where('user_id', $user->id)
            ->with('approver')
            ->latest()
            ->paginate(15);

        $stats = [
            'total_contributions' => SocialWelfare::where('user_id', $user->id)->where('type', 'contribution')->sum('amount'),
            'total_benefits' => SocialWelfare::where('user_id', $user->id)->where('type', 'benefit')->sum('amount'),
            'pending' => SocialWelfare::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => SocialWelfare::where('user_id', $user->id)->where('status', 'approved')->count(),
        ];

        return view('member.welfare.index', compact('welfares', 'stats'));
    }

    public function show(SocialWelfare $welfare)
    {
        if ($welfare->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $welfare->load(['user', 'approver']);

        return view('member.welfare.show', compact('welfare'));
    }

    public function create()
    {
        return view('member.welfare.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:contribution,benefit',
            'benefit_type' => 'nullable|in:medical,funeral,educational,other|required_if:type,benefit',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        SocialWelfare::create([
            'user_id' => Auth::id(),
            'welfare_number' => 'WF-'.strtoupper(Str::random(8)),
            'type' => $validated['type'],
            'benefit_type' => $validated['benefit_type'] ?? null,
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'status' => $validated['type'] === 'contribution' ? 'completed' : 'pending',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('member.welfare.index')->with('success', 'Welfare record created successfully.');
    }
}
