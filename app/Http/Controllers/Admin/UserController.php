<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\MembershipType;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'membershipType'])->latest();
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%")
                  ->orWhere('membership_code', 'like', "%{$search}%");
            });
        }
        
        // Filter by role
        if ($request->filled('role')) {
            if ($request->role === 'admin') {
                $query->where(function($q) {
                    $q->where('role', 'admin')
                      ->orWhereHas('roles', function($subQ) {
                          $subQ->where('slug', 'admin');
                      });
                });
            } else {
                $query->where('role', $request->role);
            }
        }
        
        $users = $query->paginate(20)->withQueryString();
        
        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->orWhereHas('roles', function($q) {
                $q->where('slug', 'admin');
            })->count(),
            'members' => User::where('role', 'user')->whereNotNull('membership_type_id')->count(),
            'staff' => User::whereHas('roles', function($q) {
                $q->whereIn('slug', ['loan_officer', 'deposit_officer', 'investment_officer', 'chairperson', 'secretary', 'accountant']);
            })->count(),
        ];
        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $roles = Role::all();
        $membershipTypes = \App\Models\MembershipType::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.users.create', compact('roles', 'membershipTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'national_id' => 'required|string|max:50|unique:users,national_id',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'alternate_phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'employment_status' => 'nullable|in:employed,self_employed,unemployed,student,retired',
            'monthly_income' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,pending,suspended',
            'kyc_status' => 'nullable|in:verified,pending,rejected,expired',
            'member_number' => 'nullable|string|max:50|unique:users,member_number',
            'membership_type_id' => 'nullable|exists:membership_types,id',
            'membership_status' => 'nullable|in:pending,approved,rejected,suspended',
            'membership_code' => 'nullable|string|max:50|unique:users,membership_code',
            'number_of_shares' => 'nullable|integer|min:0',
            'entrance_fee' => 'nullable|numeric|min:0',
            'capital_contribution' => 'nullable|numeric|min:0',
            'introduced_by' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'payment_reference_number' => 'nullable|string|max:255',
            'group_name' => 'nullable|string|max:255',
            'group_registered' => 'nullable|boolean',
            'group_leaders' => 'nullable|string|max:255',
            'group_bank_account' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'passport_picture' => 'nullable|image|max:2048',
            'nida_picture' => 'nullable|image|max:2048',
            'application_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'payment_slips' => 'nullable|array',
            'payment_slips.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Generate member number if not provided
        $memberNumber = $request->member_number;
        if (!$memberNumber && $request->membership_type_id) {
            $membershipType = \App\Models\MembershipType::find($request->membership_type_id);
            if ($membershipType) {
                $prefix = strtoupper(substr($membershipType->slug, 0, 3));
                $memberNumber = $prefix . '-' . strtoupper(Str::random(8));
            }
        }
        if (!$memberNumber) {
            $memberNumber = 'MEM-' . strtoupper(Str::random(8));
        }

        // Generate membership code if membership type is selected
        $membershipCode = $request->membership_code;
        if (!$membershipCode && $request->membership_type_id) {
            $membershipType = \App\Models\MembershipType::find($request->membership_type_id);
            if ($membershipType) {
                $prefix = strtoupper(substr($membershipType->slug, 0, 3));
                $membershipCode = $prefix . '-' . str_pad(User::whereNotNull('membership_type_id')->count() + 1, 6, '0', STR_PAD_LEFT);
            }
        }

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'member_number' => $memberNumber,
            'status' => $validated['status'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'national_id' => $validated['national_id'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'region' => $validated['region'],
            'application_date' => now(),
        ];

        // Add optional fields
        $optionalFields = [
            'marital_status', 'alternate_phone', 'postal_code', 'occupation', 'job_title',
            'employer', 'employment_status', 'monthly_income', 'kyc_status',
            'membership_type_id', 'membership_status', 'membership_code',
            'number_of_shares', 'entrance_fee', 'capital_contribution',
            'introduced_by', 'bank_name', 'bank_branch', 'bank_account_number',
            'payment_reference_number', 'group_name', 'group_registered',
            'group_leaders', 'group_bank_account', 'notes'
        ];
        
        foreach ($optionalFields as $field) {
            if (isset($validated[$field])) {
                $userData[$field] = $validated[$field];
            }
        }

        // Handle file uploads
        $fileFields = [
            'passport_picture' => 'passport_picture_path',
            'nida_picture' => 'nida_picture_path',
            'application_letter' => 'application_letter_path',
        ];

        foreach ($fileFields as $requestField => $dbField) {
            if ($request->hasFile($requestField)) {
                $userData[$dbField] = $request->file($requestField)->store('membership_documents', 'public');
            }
        }

        if ($request->hasFile('payment_slips')) {
            $paths = [];
            foreach ($request->file('payment_slips') as $file) {
                $paths[] = $file->store('membership_documents/payment_slips', 'public');
            }
            $userData['payment_slips_paths'] = json_encode($paths);
        }

        $user = User::create($userData);

        // Assign roles if provided
        if ($request->has('roles') && !empty($request->roles)) {
            $user->roles()->sync($request->roles);
        }

        // Set capital_outstanding equal to capital_contribution initially
        if (isset($userData['capital_contribution']) && $userData['capital_contribution'] > 0) {
            $user->update(['capital_outstanding' => $userData['capital_contribution']]);
        }

        return redirect()->route('admin.users.show', $user)->with('success', 'Member registered successfully!');
    }

    public function show(User $user)
    {
        $user->load(['roles.permissions', 'loans', 'savingsAccounts', 'investments', 'socialWelfares', 'issues', 'transactions']);
        
        // Calculate comprehensive statistics
        $stats = [
            'loans' => [
                'total' => $user->loans->count(),
                'active' => $user->loans->where('status', 'active')->count(),
                'pending' => $user->loans->where('status', 'pending')->count(),
                'completed' => $user->loans->where('status', 'completed')->count(),
                'total_amount' => $user->loans->sum('principal_amount'),
                'paid_amount' => $user->loans->sum('paid_amount'),
                'remaining_amount' => $user->loans->sum('remaining_amount'),
            ],
            'savings' => [
                'total_accounts' => $user->savingsAccounts->count(),
                'total_balance' => $user->savingsAccounts->sum('balance'),
                'active_accounts' => $user->savingsAccounts->where('status', 'active')->count(),
            ],
            'investments' => [
                'total' => $user->investments->count(),
                'active' => $user->investments->where('status', 'active')->count(),
                'total_principal' => $user->investments->sum('principal_amount'),
                'total_profit' => $user->investments->sum('profit_share'),
            ],
            'welfare' => [
                'total' => $user->socialWelfares->count(),
                'total_contributions' => $user->socialWelfares->where('type', 'contribution')->sum('amount'),
                'total_benefits' => $user->socialWelfares->where('type', 'benefit')->sum('amount'),
            ],
            'issues' => [
                'total' => $user->issues->count(),
                'open' => $user->issues->where('status', 'open')->count(),
                'resolved' => $user->issues->where('status', 'resolved')->count(),
            ],
            'transactions' => [
                'total' => $user->transactions->count(),
                'total_amount' => $user->transactions->sum('amount'),
            ],
        ];

        // Get recent transactions
        $recentTransactions = $user->transactions()->latest()->limit(10)->get();
        
        // Get recent loans
        $recentLoans = $user->loans()->latest()->limit(5)->get();
        
        // Get recent savings accounts
        $recentSavings = $user->savingsAccounts()->latest()->limit(5)->get();

        return view('admin.users.show', compact('user', 'stats', 'recentTransactions', 'recentLoans', 'recentSavings'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
            'member_number' => 'nullable|string|max:50|unique:users,member_number,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'national_id' => 'nullable|string|max:50',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'alternate_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:100',
            'employer' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,pending,suspended',
            'kyc_status' => 'nullable|in:verified,pending,rejected,expired',
            'kyc_expiry_date' => 'nullable|date',
            'status_reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => $validated['status'],
        ];

        // Add optional fields
        $optionalFields = ['member_number', 'phone', 'date_of_birth', 'gender', 'national_id', 'marital_status', 
                          'alternate_phone', 'address', 'city', 'region', 'postal_code', 
                          'occupation', 'employer', 'monthly_income', 'kyc_status', 'kyc_expiry_date', 
                          'status_reason', 'notes', 'membership_code', 'membership_status', 
                          'number_of_shares', 'entrance_fee', 'capital_contribution',
                          'bank_name', 'bank_branch', 'bank_account_number'];
        
        foreach ($optionalFields as $field) {
            if (isset($validated[$field])) {
                $userData[$field] = $validated[$field];
            }
        }

        // Update status_changed_at if status changed
        if ($user->status != $validated['status']) {
            $userData['status_changed_at'] = now();
        }

        $user->update($userData);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        if ($request->has('roles') && $user->role !== 'admin' && !$user->isAdmin()) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('admin.users.directory')->with('success', 'Member updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin() || $user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Cannot delete admin user.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function roles()
    {
        $roles = Role::with('permissions')->withCount('users')->get();
        $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
        return view('admin.users.roles', compact('roles', 'permissions'));
    }

    public function updateRolePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('admin.users.roles')->with('success', 'Role permissions updated successfully.');
    }

    public function directory(Request $request)
    {
        $query = User::where('role', 'user')->with(['loans', 'savingsAccounts']);
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('group')) {
            $query->where('group_id', $request->group);
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        
        $stats = [
            'total' => User::where('role', 'user')->count(),
            'active' => User::where('role', 'user')->where('status', 'active')->count(),
            'inactive' => User::where('role', 'user')->where('status', 'inactive')->count(),
        ];

        return view('admin.users.directory', compact('users', 'stats'));
    }

    public function profiles(Request $request)
    {
        $query = User::where('role', 'user')
            ->with(['loans', 'savingsAccounts', 'investments', 'membershipType'])
            ->withCount(['loans', 'savingsAccounts', 'investments']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(12)->withQueryString();
        
        return view('admin.users.profiles', compact('users'));
    }

    public function status(Request $request)
    {
        $query = User::where('role', 'user')
            ->with(['membershipType'])
            ->withCount(['loans', 'savingsAccounts', 'investments']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        
        $statusCounts = [
            'active' => User::where('role', 'user')->where('status', 'active')->count(),
            'inactive' => User::where('role', 'user')->where('status', 'inactive')->count(),
            'pending' => User::where('role', 'user')->where('status', 'pending')->count(),
            'suspended' => User::where('role', 'user')->where('status', 'suspended')->count(),
        ];

        return view('admin.users.status', compact('users', 'statusCounts'));
    }

    public function groups(Request $request)
    {
        // Group users by various criteria
        $groups = [
            'by_region' => User::where('role', 'user')
                ->whereNotNull('region')
                ->selectRaw('region, count(*) as count')
                ->groupBy('region')
                ->get(),
            'by_occupation' => User::where('role', 'user')
                ->whereNotNull('occupation')
                ->selectRaw('occupation, count(*) as count')
                ->groupBy('occupation')
                ->get(),
            'by_age_group' => User::where('role', 'user')
                ->whereNotNull('date_of_birth')
                ->selectRaw('
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 25 THEN "18-24"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 25 AND 35 THEN "25-35"
                        WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 50 THEN "36-50"
                        ELSE "50+"
                    END as age_group,
                    count(*) as count
                ')
                ->groupBy('age_group')
                ->get(),
        ];

        $query = User::where('role', 'user');
        
        if ($request->filled('group_type') && $request->filled('group_value')) {
            if ($request->group_type === 'region') {
                $query->where('region', $request->group_value);
            } elseif ($request->group_type === 'occupation') {
                $query->where('occupation', $request->group_value);
            }
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.groups', compact('groups', 'users'));
    }

    public function kyc(Request $request)
    {
        $query = User::where('role', 'user')
            ->with(['membershipType'])
            ->withCount(['loans', 'savingsAccounts']);
        
        if ($request->filled('kyc_status')) {
            $query->where('kyc_status', $request->kyc_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        
        $kycStats = [
            'verified' => User::where('role', 'user')->where('kyc_status', 'verified')->count(),
            'pending' => User::where('role', 'user')->where('kyc_status', 'pending')->count(),
            'rejected' => User::where('role', 'user')->where('kyc_status', 'rejected')->count(),
            'expired' => User::where('role', 'user')->where('kyc_status', 'expired')->count(),
        ];

        return view('admin.users.kyc', compact('users', 'kycStats'));
    }

    public function history(Request $request)
    {
        $query = User::where('role', 'user');
        
        if ($request->filled('user_id')) {
            $query->where('id', $request->user_id);
        }

        if ($request->filled('activity_type')) {
            // This would need an activities/audit log table
        }

        $users = $query->latest()->get();
        
        return view('admin.users.history', compact('users'));
    }

    public function createOfficial()
    {
        $roles = Role::where('slug', '!=', 'member')->get();
        // Get only approved members for staff selection
        $approvedMembers = User::where('membership_status', 'approved')
            ->whereNotNull('membership_type_id')
            ->orderBy('name')
            ->get();
        return view('admin.users.officials.create', compact('roles', 'approvedMembers'));
    }

    public function storeOfficial(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:users,id',
            'role' => 'required|in:admin,loan_officer,deposit_officer,investment_officer,chairperson,secretary,accountant',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
        ]);

        // Get the selected member
        $member = User::findOrFail($validated['member_id']);
        
        // Verify member is approved
        if ($member->membership_status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved members can be assigned staff roles.');
        }

        // Update member to staff role
        $member->update([
            'role' => $validated['role'],
            'occupation' => $validated['position'] ?? $member->occupation,
            'region' => $validated['department'] ?? $member->region,
            'status' => 'active',
        ]);

        // Assign roles
        if ($request->has('roles')) {
            $member->roles()->sync($request->roles);
        } else {
            // Assign default role based on staff type
            $roleSlug = $validated['role'];
            $role = Role::where('slug', $roleSlug)->first();
            if ($role) {
                $member->roles()->sync([$role->id]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'Member assigned as staff successfully.');
    }

    public function permissions(Request $request)
    {
        $query = User::where(function($q) {
            $q->where('role', '!=', 'user')
              ->orWhereHas('roles', function($subQ) {
                  $subQ->where('slug', '!=', 'member');
              });
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->with(['roles.permissions'])->latest()->paginate(20)->withQueryString();

        $allPermissions = Permission::with('roles')->orderBy('group')->orderBy('name')->get()->groupBy('group');

        return view('admin.users.permissions', compact('users', 'allPermissions'));
    }

    public function loginHistory(Request $request)
    {
        // This would typically use a login_history or activity_logs table
        // For now, we'll create a basic structure
        $query = User::query();

        if ($request->filled('user_id')) {
            $query->where('id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $users = $query->with('roles')->latest()->paginate(20);
        
        $stats = [
            'total_logins' => User::count(),
            'today_logins' => User::whereDate('created_at', today())->count(),
            'this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.users.login-history', compact('users', 'stats'));
    }

    public function activityLogs(Request $request)
    {
        $query = User::query();

        if ($request->filled('user_id')) {
            $query->where('id', $request->user_id);
        }

        if ($request->filled('activity_type')) {
            // Filter by activity type if activity logs table exists
        }

        if ($request->filled('date_from')) {
            $query->whereDate('updated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('updated_at', '<=', $request->date_to);
        }

        $users = $query->with(['roles', 'loans', 'savingsAccounts'])->latest('updated_at')->paginate(20);

        return view('admin.users.activity-logs', compact('users'));
    }

    public function upload()
    {
        return view('admin.users.upload');
    }

    public function previewExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            
            $sheets = [];
            foreach ($spreadsheet->getSheetNames() as $index => $name) {
                $worksheet = $spreadsheet->getSheet($index);
                $rows = $worksheet->toArray(null, true, true, true);
                $headers = [];
                if (!empty($rows)) {
                    $firstRow = reset($rows);
                    $headers = array_values(array_filter($firstRow));
                }
                
                $sheets[] = [
                    'index' => $index,
                    'name' => $name,
                    'headers' => $headers,
                    'preview' => array_slice($rows, 0, 6)
                ];
            }

            return response()->json([
                'success' => true,
                'sheets' => $sheets,
                'filename' => $file->getClientOriginalName(),
                'size' => number_format($file->getSize() / 1024, 2) . ' KB'
            ]);
        } catch (\Exception $e) {
            Log::error('Excel preview error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to read Excel file: ' . $e->getMessage()
            ], 400);
        }
    }

    public function processUpload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240',
            'sheet_index' => 'required',
            'column_mapping' => 'required'
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            
            $sheetIndex = (int)$request->sheet_index;
            $worksheet = $spreadsheet->getSheet($sheetIndex);
            $rows = $worksheet->toArray(null, true, true, true);
            
            if (count($rows) < 2) {
                return response()->json(['success' => false, 'message' => 'The selected sheet has no data.'], 400);
            }

            $columnMapping = json_decode($request->column_mapping, true);
            $headers = array_shift($rows); // First row is headers
            
            $results = [
                'total' => count($rows),
                'success' => 0,
                'failed' => 0,
                'errors' => []
            ];

            $membershipTypes = MembershipType::all();

            foreach ($rows as $index => $row) {
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) continue;

                    $mappedData = [];
                    foreach ($columnMapping as $dbField => $excelCol) {
                        if ($excelCol && isset($row[$excelCol])) {
                            $mappedData[$dbField] = $row[$excelCol];
                        }
                    }

                    // Basic Validation
                    if (empty($mappedData['name']) || empty($mappedData['email'])) {
                        $results['failed']++;
                        $results['errors'][] = "Row " . ($index + 2) . ": Name and Email are required.";
                        continue;
                    }

                    // Check if user exists
                    $existingUser = User::where('email', $mappedData['email'])->first();
                    if ($existingUser) {
                        $results['failed']++;
                        $results['errors'][] = "Row " . ($index + 2) . ": User with email {$mappedData['email']} already exists (Member: {$existingUser->name}).";
                        continue;
                    }

                    DB::beginTransaction();

                    // Map Membership Type
                    if (!empty($mappedData['membership_type'])) {
                        $typeName = trim($mappedData['membership_type']);
                        $mType = $membershipTypes->first(function($t) use ($typeName) {
                            return strcasecmp($t->name, $typeName) === 0 || strcasecmp($t->slug, $typeName) === 0;
                        });
                        if ($mType) {
                            $mappedData['membership_type_id'] = $mType->id;
                        }
                    }

                    // Generate Password if not provided
                    $password = Str::random(10);
                    
                    // Sanitize Marital Status
                    $validMaritalStatuses = ['single', 'married', 'divorced', 'widowed'];
                    $maritalStatus = strtolower(trim($mappedData['marital_status'] ?? 'single'));
                    if (!in_array($maritalStatus, $validMaritalStatuses)) {
                        // If it contains "group" or is unrecognized, default to single or keep it null if we preferred
                        $maritalStatus = 'single';
                    }

                    // Prepare User Data
                    $userData = [
                        'name' => trim($mappedData['name']),
                        'email' => trim($mappedData['email']),
                        'password' => Hash::make($password),
                        'role' => 'user',
                        'status' => strtolower($mappedData['status'] ?? 'pending') === 'active' ? 'active' : 'pending',
                        'phone' => trim($mappedData['phone'] ?? ''),
                        'gender' => strtolower(substr($mappedData['gender'] ?? 'male', 0, 1)) === 'f' ? 'female' : 'male',
                        'membership_code' => $mappedData['membership_code'] ?? null,
                        'address' => $mappedData['address'] ?? null,
                        'membership_type_id' => $mappedData['membership_type_id'] ?? null,
                        'number_of_shares' => (int)($mappedData['number_of_shares'] ?? 0),
                        'entrance_fee' => (float)($mappedData['entrance_fee'] ?? 0),
                        'capital_contribution' => (float)($mappedData['capital_contribution'] ?? 0),
                        'capital_outstanding' => (float)($mappedData['capital_outstanding'] ?? ($mappedData['capital_contribution'] ?? 0)),
                        'membership_interest_percentage' => (float)($mappedData['interest_percentage'] ?? 0),
                        'bank_name' => $mappedData['bank_info'] ?? null,
                        'bank_account_number' => $mappedData['bank_account'] ?? null,
                        'date_of_birth' => !empty($mappedData['date_of_birth']) ? date('Y-m-d', strtotime($mappedData['date_of_birth'])) : null,
                        'national_id' => $mappedData['national_id'] ?? null,
                        'marital_status' => $maritalStatus,
                        'member_number' => 'MEM-' . strtoupper(Str::random(8)),
                        'application_date' => now(),
                    ];

                    $user = User::create($userData);
                    
                    // Assign 'member' role if it exists
                    $memberRole = Role::where('slug', 'member')->first();
                    if ($memberRole) {
                        $user->roles()->sync([$memberRole->id]);
                    }

                    DB::commit();
                    $results['success']++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    $results['failed']++;
                    $results['errors'][] = "Row " . ($index + 2) . ": " . $e->getMessage();
                    Log::error("Bulk import row error: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'results' => $results,
                'message' => "Successfully imported {$results['success']} members."
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk import process error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during processing: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadSample()
    {
        $headers = [
            'Time', 'Name of Member', 'Members\' email', 'Gender', 'Membership Code', 
            'Adress of Applicant', 'Type of Membership', 'Number of Shares', 'Entrance Fee', 
            'Capital Contrbution', 'Capital Outstanding', 'Status of Membership', 'Members\' Address', 
            'Date', 'Percentage of Membership interest', 'Gender_Word', 'Phone Number', 
            'Bank & Branch Name', 'Bank Account Number', 'BIRTH DATE', 'NIDA', 'MARITAL STATUS'
        ];

        $filename = "bulk_member_upload_sample.csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        fputcsv($handle, $headers);
        
        // Add a sample row
        fputcsv($handle, [
            now()->format('Y-m-d H:i:s'), 'John Doe', 'john.doe@example.com', 'Male', 'MEM001',
            '123 Street, City', 'Ordinary', '10', '10000', '50000', '50000', 'Active', '123 Street, City',
            now()->format('Y-m-d'), '10.5', 'Male', '255712345678', 'NMB Bank', '1234567890', '1990-01-01', '12345-67890-12345-1', 'Single'
        ]);
        
        fclose($handle);
        exit;
    }

    public function findColumnIndex($headers, $searchTerms)
    {
        foreach ($headers as $index => $header) {
            $header = strtolower(trim($header));
            foreach ($searchTerms as $term) {
                if ($header === strtolower($term) || str_contains($header, strtolower($term))) {
                    return $index;
                }
            }
        }
        return false;
    }

    /**
     * Bulk Generate New Passwords and send to email
     */
    public function bulkPasswordReset(Request $request)
    {
        // Only allow admins to do this
        if (auth()->user()->role !== 'admin' && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        // We target members primarily, or all users if requested
        $target = $request->input('target', 'members'); // members or all
        
        $query = User::query();
        if ($target === 'members') {
            $query->where('role', 'user');
        }

        $users = $query->get();
        $successCount = 0;
        $failCount = 0;

        $emailService = app(\App\Services\EmailNotificationService::class);

        foreach ($users as $user) {
            try {
                $newPassword = Str::random(10);
                $user->password = Hash::make($newPassword);
                $user->save();

                // Send Email
                $sent = $emailService->sendBulkPasswordNotification($user, $newPassword);
                
                if ($sent) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            } catch (\Exception $e) {
                Log::error("Failed to reset password for user {$user->id}: " . $e->getMessage());
                $failCount++;
            }
        }

        return back()->with('success', "Bulk password reset complete. Successfully sent: {$successCount}, Failed: {$failCount}.");
    }

    /**
     * Reset password for a single user and send to email
     */
    public function resetPassword(User $user)
    {
        // Only allow admins to do this
        if (auth()->user()->role !== 'admin' && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        try {
            $newPassword = Str::random(10);
            $user->password = Hash::make($newPassword);
            $user->save();

            $emailService = app(\App\Services\EmailNotificationService::class);
            $sent = $emailService->sendBulkPasswordNotification($user, $newPassword);

            if ($sent) {
                return back()->with('success', "Password for {$user->name} has been reset and sent to their email.");
            } else {
                return back()->with('error', "Password reset but failed to send email to {$user->email}. New password: {$newPassword}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to reset password for user {$user->id}: " . $e->getMessage());
            return back()->with('error', "An error occurred while resetting the password.");
        }
    }
}
