<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MembershipType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    /**
     * Show membership application form (Step 1: Basic Info)
     */
    public function create()
    {
        $user = Auth::user();

        // If user already has approved membership, redirect
        if ($user->membership_status === 'approved') {
            return redirect()->route('member.dashboard')->with('info', 'You already have an approved membership.');
        }

        $membershipTypes = MembershipType::where('is_active', true)->orderBy('sort_order')->get();

        return view('member.membership.create', compact('membershipTypes', 'user'));
    }

    /**
     * Store basic registration (Step 1)
     */
    public function storeBasic(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'user',
            'membership_status' => 'pending',
        ]);

        Auth::login($user);

        return redirect()->route('member.membership.application');
    }

    /**
     * Show membership application form (Step 2: Detailed Information)
     */
    public function application()
    {
        $user = Auth::user();
        $membershipTypes = MembershipType::where('is_active', true)->orderBy('sort_order')->get();

        return view('member.membership.application', compact('membershipTypes', 'user'));
    }

    /**
     * Store membership application
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'membership_type_id' => 'required|exists:membership_types,id',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'national_id' => 'required|string|max:50',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'address' => 'required|string',
            'city' => 'required|string',
            'region' => 'required|string',
            'occupation' => 'nullable|string',
            'employer' => 'nullable|string',
            'monthly_income' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string',
            'bank_branch' => 'nullable|string',
            'bank_account_number' => 'nullable|string',
            'payment_reference_number' => 'nullable|string',
            'statement_preference' => 'required|in:email,sms,postal',
        ]);

        // Handle file uploads
        $data = $request->all();

        if ($request->hasFile('passport_picture')) {
            $data['passport_picture_path'] = $request->file('passport_picture')->store('membership/passport', 'public');
        }

        if ($request->hasFile('nida_picture')) {
            $data['nida_picture_path'] = $request->file('nida_picture')->store('membership/nida', 'public');
        }

        if ($request->hasFile('application_letter')) {
            $data['application_letter_path'] = $request->file('application_letter')->store('membership/letters', 'public');
        }

        if ($request->hasFile('payment_slip')) {
            $data['payment_slip_path'] = $request->file('payment_slip')->store('membership/payments', 'public');
        }

        if ($request->hasFile('standing_order')) {
            $data['standing_order_path'] = $request->file('standing_order')->store('membership/standing-orders', 'public');
        }

        // Process beneficiaries info
        if ($request->has('beneficiaries')) {
            $beneficiaries = [];
            foreach ($request->beneficiaries as $beneficiary) {
                if (! empty($beneficiary['name'])) {
                    $beneficiaries[] = $beneficiary;
                }
            }
            if (! empty($beneficiaries)) {
                $data['beneficiaries_info'] = $beneficiaries;
            }
        }

        // Add additional fields
        $additionalFields = [
            'short_bibliography',
            'introduced_by',
            'guarantor_name',
            'group_name',
            'group_leaders',
            'group_bank_account',
            'group_contacts',
        ];

        foreach ($additionalFields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        // Handle boolean fields
        $data['wants_ordinary_membership'] = $request->has('wants_ordinary_membership') ? (bool) $request->input('wants_ordinary_membership') : false;
        $data['is_group_registered'] = $request->has('is_group_registered') ? (bool) $request->input('is_group_registered') : false;

        // Generate membership code if not exists
        if (! $user->membership_code) {
            $membershipType = MembershipType::find($request->membership_type_id);
            if ($membershipType) {
                $data['membership_code'] = strtoupper(substr($membershipType->slug, 0, 3)).'-'.str_pad($user->id, 6, '0', STR_PAD_LEFT);
            }
        }

        // Update user with membership data
        $user->update($data);

        return redirect()->route('member.membership.application')->with('success', 'Membership application submitted successfully! It will be reviewed by administrators.');
    }

    /**
     * Show application status
     */
    public function status()
    {
        $user = Auth::user();

        return view('member.membership.status', compact('user'));
    }
}
