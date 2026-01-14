<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MembershipType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MembershipController extends Controller
{
    /**
     * Display all membership applications
     */
    public function index(Request $request)
    {
        $query = User::whereNotNull('membership_type_id')
            ->with('membershipType')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('membership_status', $request->status);
        }

        // Filter by membership type
        if ($request->has('membership_type') && $request->membership_type !== '') {
            $query->where('membership_type_id', $request->membership_type);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('membership_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(20);
        $membershipTypes = MembershipType::where('is_active', true)->get();
        
        $stats = [
            'pending' => User::where('membership_status', 'pending')->whereNotNull('membership_type_id')->count(),
            'approved' => User::where('membership_status', 'approved')->whereNotNull('membership_type_id')->count(),
            'rejected' => User::where('membership_status', 'rejected')->whereNotNull('membership_type_id')->count(),
            'suspended' => User::where('membership_status', 'suspended')->whereNotNull('membership_type_id')->count(),
        ];

        return view('admin.memberships.index', compact('applications', 'membershipTypes', 'stats'));
    }

    /**
     * Show membership application details
     */
    public function show(User $user)
    {
        $user->load('membershipType', 'membershipApprovedBy');
        return view('admin.memberships.show', compact('user'));
    }

    /**
     * Approve membership application
     */
    public function approve(Request $request, User $user)
    {
        $request->validate([
            'membership_code' => 'nullable|string|max:50',
            'number_of_shares' => 'nullable|integer|min:0',
            'entrance_fee' => 'nullable|numeric|min:0',
            'capital_contribution' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $capitalContribution = $request->capital_contribution ?? $user->capital_contribution ?? 0;
        
        $user->update([
            'membership_status' => 'approved',
            'membership_approved_at' => now(),
            'membership_approved_by' => auth()->id(),
            'membership_code' => $request->membership_code ?? $user->membership_code,
            'number_of_shares' => $request->number_of_shares ?? $user->number_of_shares ?? 0,
            'entrance_fee' => $request->entrance_fee ?? $user->entrance_fee ?? 0,
            'capital_contribution' => $capitalContribution,
            'capital_outstanding' => $capitalContribution, // Initially, outstanding equals contribution
            'notes' => $request->notes ?? $user->notes,
        ]);

        // Generate member number if not exists
        if (!$user->member_number) {
            $membershipType = $user->membershipType;
            if ($membershipType) {
                $prefix = strtoupper(substr($membershipType->slug, 0, 3));
            } else {
                $prefix = 'MEM';
            }
            $user->member_number = $prefix . '-' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
            $user->save();
        }

        return redirect()->route('admin.memberships.show', $user)
            ->with('success', 'Membership application approved successfully!');
    }

    /**
     * Reject membership application
     */
    public function reject(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'membership_status' => 'rejected',
            'status_reason' => $request->rejection_reason,
            'status_changed_at' => now(),
        ]);

        return redirect()->route('admin.memberships.show', $user)
            ->with('success', 'Membership application rejected.');
    }

    /**
     * Suspend membership
     */
    public function suspend(Request $request, User $user)
    {
        $request->validate([
            'suspension_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'membership_status' => 'suspended',
            'status_reason' => $request->suspension_reason,
            'status_changed_at' => now(),
        ]);

        return redirect()->route('admin.memberships.show', $user)
            ->with('success', 'Membership suspended.');
    }

    /**
     * Reactivate suspended membership
     */
    public function reactivate(User $user)
    {
        $user->update([
            'membership_status' => 'approved',
            'status_reason' => null,
            'status_changed_at' => now(),
        ]);

        return redirect()->route('admin.memberships.show', $user)
            ->with('success', 'Membership reactivated.');
    }
}
