<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PdfHelper;
use App\Http\Controllers\Controller;
use App\Models\SocialWelfare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SocialWelfareController extends Controller
{
    public function index(Request $request)
    {
        $query = SocialWelfare::with('user', 'approver');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('welfare_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Benefit type filter
        if ($request->filled('benefit_type')) {
            $query->where('benefit_type', $request->benefit_type);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $welfares = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total_contributions' => SocialWelfare::where('type', 'contribution')->sum('amount'),
            'total_benefits' => SocialWelfare::where('type', 'benefit')->sum('amount'),
            'pending' => SocialWelfare::where('status', 'pending')->count(),
            'approved' => SocialWelfare::where('status', 'approved')->count(),
            'disbursed' => SocialWelfare::where('status', 'disbursed')->count(),
            'rejected' => SocialWelfare::where('status', 'rejected')->count(),
            'medical' => SocialWelfare::where('benefit_type', 'medical')->sum('amount'),
            'funeral' => SocialWelfare::where('benefit_type', 'funeral')->sum('amount'),
            'educational' => SocialWelfare::where('benefit_type', 'educational')->sum('amount'),
            'total_records' => SocialWelfare::count(),
        ];

        return view('admin.welfare.index', compact('welfares', 'stats'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();

        return view('admin.welfare.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:contribution,benefit',
            'benefit_type' => 'nullable|in:medical,funeral,educational,other|required_if:type,benefit',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
            'eligibility_notes' => 'nullable|string',
        ]);

        SocialWelfare::create([
            'user_id' => $validated['user_id'],
            'welfare_number' => 'WF-'.strtoupper(Str::random(8)),
            'type' => $validated['type'],
            'benefit_type' => $validated['benefit_type'] ?? null,
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'status' => $validated['type'] === 'contribution' ? 'approved' : 'pending',
            'description' => $validated['description'] ?? null,
            'eligibility_notes' => $validated['eligibility_notes'] ?? null,
        ]);

        return redirect()->route('admin.welfare.index')->with('success', 'Social welfare record created successfully.');
    }

    public function show(SocialWelfare $welfare)
    {
        $welfare->load(['user', 'approver', 'transactions']);

        return view('admin.welfare.show', compact('welfare'));
    }

    public function edit(SocialWelfare $welfare)
    {
        $welfare->load(['user', 'approver']);
        $users = User::where('role', 'user')->get();
        $staff = User::whereIn('role', ['admin', 'staff'])->get();

        return view('admin.welfare.edit', compact('welfare', 'users', 'staff'));
    }

    public function update(Request $request, SocialWelfare $welfare)
    {
        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric|min:0',
            'transaction_date' => 'sometimes|required|date',
            'description' => 'nullable|string',
            'benefit_type' => 'nullable|in:medical,funeral,educational,other',
            'status' => 'required|in:pending,approved,disbursed,rejected',
            'approval_date' => 'nullable|date',
            'disbursement_date' => 'nullable|date|required_if:status,disbursed',
            'rejection_reason' => 'nullable|string|required_if:status,rejected',
            'eligibility_notes' => 'nullable|string',
            'approved_by' => 'nullable|exists:users,id',
        ]);

        if ($validated['status'] === 'approved' && ! $welfare->approval_date) {
            $validated['approval_date'] = now();
            if (! isset($validated['approved_by'])) {
                $validated['approved_by'] = auth()->id();
            }
        }

        if ($validated['status'] === 'disbursed' && ! $welfare->disbursement_date) {
            $validated['disbursement_date'] = now();
        }

        $welfare->update($validated);

        return redirect()->route('admin.welfare.show', $welfare)->with('success', 'Social welfare record updated successfully.');
    }

    public function destroy(SocialWelfare $welfare)
    {
        $welfare->delete();

        return redirect()->route('admin.welfare.index')->with('success', 'Social welfare record deleted successfully.');
    }

    /**
     * Fund Management - Overview of welfare fund
     */
    public function fundManagement(Request $request)
    {
        $query = SocialWelfare::with('user');

        // Date range filter
        $dateFrom = $request->get('date_from', now()->startOfYear()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $query->whereBetween('transaction_date', [$dateFrom, $dateTo]);

        // Get fund statistics
        $totalContributions = SocialWelfare::where('type', 'contribution')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');

        $totalBenefits = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');

        $netBalance = $totalContributions - $totalBenefits;

        // Monthly breakdown
        $monthlyContributions = SocialWelfare::where('type', 'contribution')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyBenefits = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Recent transactions
        $recentTransactions = SocialWelfare::with('user')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->latest('transaction_date')
            ->limit(20)
            ->get();

        // Benefit type breakdown
        $benefitBreakdown = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('benefit_type, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('benefit_type')
            ->get();

        $stats = [
            'total_contributions' => $totalContributions,
            'total_benefits' => $totalBenefits,
            'net_balance' => $netBalance,
            'pending_benefits' => SocialWelfare::where('type', 'benefit')
                ->where('status', 'pending')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->sum('amount'),
            'approved_benefits' => SocialWelfare::where('type', 'benefit')
                ->where('status', 'approved')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->sum('amount'),
            'disbursed_benefits' => SocialWelfare::where('type', 'benefit')
                ->where('status', 'disbursed')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->sum('amount'),
            'total_transactions' => SocialWelfare::whereBetween('transaction_date', [$dateFrom, $dateTo])->count(),
        ];

        return view('admin.welfare.fund-management', compact('stats', 'monthlyContributions', 'monthlyBenefits', 'recentTransactions', 'benefitBreakdown', 'dateFrom', 'dateTo'));
    }

    /**
     * Welfare Services - Available services and eligibility
     */
    public function services()
    {
        // Get all benefit types with statistics
        $services = [
            'medical' => [
                'name' => 'Medical Support',
                'description' => 'Financial assistance for medical expenses and healthcare needs',
                'total_benefits' => SocialWelfare::where('benefit_type', 'medical')->sum('amount'),
                'total_claims' => SocialWelfare::where('benefit_type', 'medical')->count(),
                'pending_claims' => SocialWelfare::where('benefit_type', 'medical')->where('status', 'pending')->count(),
                'eligibility_criteria' => 'Active member for at least 6 months, minimum contribution of TZS 50,000',
            ],
            'funeral' => [
                'name' => 'Funeral Support',
                'description' => 'Financial assistance for funeral expenses and bereavement support',
                'total_benefits' => SocialWelfare::where('benefit_type', 'funeral')->sum('amount'),
                'total_claims' => SocialWelfare::where('benefit_type', 'funeral')->count(),
                'pending_claims' => SocialWelfare::where('benefit_type', 'funeral')->where('status', 'pending')->count(),
                'eligibility_criteria' => 'Active member, immediate family member (spouse, parent, child)',
            ],
            'educational' => [
                'name' => 'Educational Support',
                'description' => 'Scholarships and educational grants for members and their dependents',
                'total_benefits' => SocialWelfare::where('benefit_type', 'educational')->sum('amount'),
                'total_claims' => SocialWelfare::where('benefit_type', 'educational')->count(),
                'pending_claims' => SocialWelfare::where('benefit_type', 'educational')->where('status', 'pending')->count(),
                'eligibility_criteria' => 'Active member, student enrollment proof, academic performance',
            ],
            'other' => [
                'name' => 'Other Support',
                'description' => 'General welfare support for various needs and emergencies',
                'total_benefits' => SocialWelfare::where('benefit_type', 'other')->sum('amount'),
                'total_claims' => SocialWelfare::where('benefit_type', 'other')->count(),
                'pending_claims' => SocialWelfare::where('benefit_type', 'other')->where('status', 'pending')->count(),
                'eligibility_criteria' => 'Case-by-case evaluation, member in good standing',
            ],
        ];

        // Recent service requests
        $recentRequests = SocialWelfare::where('type', 'benefit')
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.welfare.services', compact('services', 'recentRequests'));
    }

    /**
     * Claims Processing - Process and manage benefit claims
     */
    public function claimsProcessing(Request $request)
    {
        $query = SocialWelfare::where('type', 'benefit')->with('user', 'approver');

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'approved']);
        }

        // Benefit type filter
        if ($request->filled('benefit_type')) {
            $query->where('benefit_type', $request->benefit_type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('welfare_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $claims = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'pending' => SocialWelfare::where('type', 'benefit')->where('status', 'pending')->count(),
            'approved' => SocialWelfare::where('type', 'benefit')->where('status', 'approved')->count(),
            'disbursed' => SocialWelfare::where('type', 'benefit')->where('status', 'disbursed')->count(),
            'rejected' => SocialWelfare::where('type', 'benefit')->where('status', 'rejected')->count(),
            'total_pending_amount' => SocialWelfare::where('type', 'benefit')->where('status', 'pending')->sum('amount'),
            'total_approved_amount' => SocialWelfare::where('type', 'benefit')->where('status', 'approved')->sum('amount'),
        ];

        return view('admin.welfare.claims-processing', compact('claims', 'stats'));
    }

    /**
     * Welfare Reports - Comprehensive reports and analytics
     */
    public function reports(Request $request)
    {
        // Date range
        $dateFrom = $request->get('date_from', now()->subYear()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Overall statistics
        $totalContributions = SocialWelfare::where('type', 'contribution')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');

        $totalBenefits = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');

        // Monthly trends
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'contributions' => SocialWelfare::where('type', 'contribution')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->sum('amount'),
                'benefits' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->sum('amount'),
            ];
        }

        // Benefit type breakdown
        $benefitTypes = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('benefit_type, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('benefit_type')
            ->get();

        // Status breakdown
        $statusBreakdown = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        // Top beneficiaries
        $topBeneficiaries = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user')
            ->selectRaw('user_id, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top contributors
        $topContributors = SocialWelfare::where('type', 'contribution')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user')
            ->selectRaw('user_id, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $stats = [
            'total_contributions' => $totalContributions,
            'total_benefits' => $totalBenefits,
            'net_balance' => $totalContributions - $totalBenefits,
            'total_contribution_records' => SocialWelfare::where('type', 'contribution')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->count(),
            'total_benefit_records' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->count(),
            'average_contribution' => SocialWelfare::where('type', 'contribution')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->avg('amount'),
            'average_benefit' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->avg('amount'),
        ];

        return view('admin.welfare.reports', compact('stats', 'monthlyData', 'benefitTypes', 'statusBreakdown', 'topBeneficiaries', 'topContributors', 'dateFrom', 'dateTo'));
    }

    /**
     * Export welfare record as PDF
     */
    public function exportPdf(SocialWelfare $welfare)
    {
        $welfare->load(['user', 'approver', 'transactions']);

        // Get organization info
        $organizationInfo = [
            'name' => 'FeedTan Community Microfinance Group',
            'address' => 'P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania',
            'email' => 'feedtan15@gmail.com',
            'phone' => '+255622239304',
        ];

        // Generate QR code data
        $qrData = [
            'type' => 'welfare',
            'welfare_number' => $welfare->welfare_number,
            'member_id' => $welfare->user->membership_code ?? $welfare->user->id,
            'amount' => $welfare->amount,
            'date' => $welfare->transaction_date->format('Y-m-d'),
            'status' => $welfare->status,
        ];
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data='.urlencode(json_encode($qrData));

        // 80mm receipt size: 80mm = 226.77 points (1mm = 2.83465 points)
        // DomPDF format: [x, y, width, height] in points
        // x=0, y=0, width=226.77 (80mm), height=841.89 (A4 height for auto)
        $receiptSize = [0, 0, 226.77, 841.89]; // 80mm width, auto height

        return PdfHelper::downloadPdf('admin.welfare.pdf', [
            'welfare' => $welfare,
            'organizationInfo' => $organizationInfo,
            'documentTitle' => 'Social Welfare Record',
            'documentSubtitle' => ucfirst($welfare->type).' - '.($welfare->benefit_type_name ?? 'Record'),
            'qrCodeUrl' => $qrCodeUrl,
        ], 'welfare-'.$welfare->welfare_number.'-'.date('Y-m-d-His').'.pdf', $receiptSize, 'portrait');
    }

    /**
     * Fund Status Report
     */
    public function fundStatusReport(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        // Total contributions
        $totalContributions = SocialWelfare::where('type', 'contribution')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');

        // Total benefits
        $totalBenefits = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');

        // Net balance
        $netBalance = $totalContributions - $totalBenefits;

        // Opening balance (before date range)
        $openingBalance = SocialWelfare::where('type', 'contribution')
            ->where('transaction_date', '<', $dateFrom)
            ->sum('amount') - SocialWelfare::where('type', 'benefit')
            ->where('transaction_date', '<', $dateFrom)
            ->sum('amount');

        // Closing balance
        $closingBalance = $openingBalance + $netBalance;

        // Monthly breakdown
        $monthlyData = [];
        $startDate = \Carbon\Carbon::parse($dateFrom);
        $endDate = \Carbon\Carbon::parse($dateTo);

        for ($month = $startDate->copy()->startOfMonth(); $month->lte($endDate); $month->addMonth()) {
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'contributions' => SocialWelfare::where('type', 'contribution')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->sum('amount'),
                'benefits' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->sum('amount'),
            ];
        }

        // Contribution records
        $contributionRecords = SocialWelfare::where('type', 'contribution')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user')
            ->orderBy('transaction_date', 'desc')
            ->paginate(20);

        // Benefit records
        $benefitRecords = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user')
            ->orderBy('transaction_date', 'desc')
            ->paginate(20);

        $stats = [
            'opening_balance' => $openingBalance,
            'total_contributions' => $totalContributions,
            'total_benefits' => $totalBenefits,
            'net_balance' => $netBalance,
            'closing_balance' => $closingBalance,
            'contribution_count' => SocialWelfare::where('type', 'contribution')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->count(),
            'benefit_count' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->count(),
            'average_contribution' => SocialWelfare::where('type', 'contribution')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->avg('amount'),
            'average_benefit' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->avg('amount'),
        ];

        return view('admin.welfare.reports.fund-status', compact('stats', 'monthlyData', 'contributionRecords', 'benefitRecords', 'dateFrom', 'dateTo'));
    }

    /**
     * Fund Status Report PDF
     */
    public function fundStatusReportPdf(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        // Same data as report method
        $totalContributions = SocialWelfare::where('type', 'contribution')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');
        $totalBenefits = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');
        $netBalance = $totalContributions - $totalBenefits;
        $openingBalance = SocialWelfare::where('type', 'contribution')
            ->where('transaction_date', '<', $dateFrom)
            ->sum('amount') - SocialWelfare::where('type', 'benefit')
            ->where('transaction_date', '<', $dateFrom)
            ->sum('amount');
        $closingBalance = $openingBalance + $netBalance;

        $monthlyData = [];
        $startDate = \Carbon\Carbon::parse($dateFrom);
        $endDate = \Carbon\Carbon::parse($dateTo);

        for ($month = $startDate->copy()->startOfMonth(); $month->lte($endDate); $month->addMonth()) {
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'contributions' => SocialWelfare::where('type', 'contribution')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->sum('amount'),
                'benefits' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->sum('amount'),
            ];
        }

        $contributionRecords = SocialWelfare::where('type', 'contribution')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user')
            ->orderBy('transaction_date', 'desc')
            ->limit(50)
            ->get();

        $benefitRecords = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user')
            ->orderBy('transaction_date', 'desc')
            ->limit(50)
            ->get();

        $stats = [
            'opening_balance' => $openingBalance,
            'total_contributions' => $totalContributions,
            'total_benefits' => $totalBenefits,
            'net_balance' => $netBalance,
            'closing_balance' => $closingBalance,
            'contribution_count' => SocialWelfare::where('type', 'contribution')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->count(),
            'benefit_count' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->count(),
        ];

        $organizationInfo = [
            'name' => 'FeedTan Community Microfinance Group',
            'address' => 'P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania',
            'email' => 'feedtan15@gmail.com',
            'phone' => '+255622239304',
        ];

        return PdfHelper::downloadPdf('admin.welfare.reports.pdf.fund-status', [
            'stats' => $stats,
            'monthlyData' => $monthlyData,
            'contributionRecords' => $contributionRecords,
            'benefitRecords' => $benefitRecords,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'organizationInfo' => $organizationInfo,
            'documentTitle' => 'Welfare Fund Status Report',
            'documentSubtitle' => 'Period: '.\Carbon\Carbon::parse($dateFrom)->format('M j, Y').' - '.\Carbon\Carbon::parse($dateTo)->format('M j, Y'),
        ], 'welfare-fund-status-'.date('Y-m-d-His').'.pdf');
    }

    /**
     * Claim Analysis Report
     */
    public function claimAnalysisReport(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        // Total claims
        $totalClaims = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->count();

        // Status breakdown
        $statusBreakdown = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        // Benefit type breakdown
        $benefitTypeBreakdown = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('benefit_type, COUNT(*) as count, SUM(amount) as total, AVG(amount) as average')
            ->groupBy('benefit_type')
            ->get();

        // Approval rate
        $approvedCount = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->where('status', 'approved')
            ->count();
        $approvalRate = $totalClaims > 0 ? ($approvedCount / $totalClaims) * 100 : 0;

        // Processing time analysis
        $processingTimes = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->whereNotNull('approval_date')
            ->whereNotNull('created_at')
            ->get()
            ->map(function ($welfare) {
                return $welfare->created_at->diffInDays($welfare->approval_date);
            });

        $avgProcessingDays = $processingTimes->count() > 0 ? $processingTimes->avg() : 0;

        // Monthly claims trend
        $monthlyClaims = [];
        $startDate = \Carbon\Carbon::parse($dateFrom);
        $endDate = \Carbon\Carbon::parse($dateTo);

        for ($month = $startDate->copy()->startOfMonth(); $month->lte($endDate); $month->addMonth()) {
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyClaims[] = [
                'month' => $month->format('M Y'),
                'total' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->count(),
                'approved' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->where('status', 'approved')
                    ->count(),
                'rejected' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->where('status', 'rejected')
                    ->count(),
                'pending' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->where('status', 'pending')
                    ->count(),
            ];
        }

        // Recent claims
        $recentClaims = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user', 'approver')
            ->orderBy('transaction_date', 'desc')
            ->paginate(20);

        $stats = [
            'total_claims' => $totalClaims,
            'approved_count' => $approvedCount,
            'rejected_count' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->where('status', 'rejected')
                ->count(),
            'pending_count' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->where('status', 'pending')
                ->count(),
            'approval_rate' => $approvalRate,
            'avg_processing_days' => round($avgProcessingDays, 1),
            'total_amount' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->sum('amount'),
            'avg_amount' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->avg('amount'),
        ];

        return view('admin.welfare.reports.claim-analysis', compact('stats', 'statusBreakdown', 'benefitTypeBreakdown', 'monthlyClaims', 'recentClaims', 'dateFrom', 'dateTo'));
    }

    /**
     * Claim Analysis Report PDF
     */
    public function claimAnalysisReportPdf(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        $totalClaims = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->count();

        $statusBreakdown = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        $benefitTypeBreakdown = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('benefit_type, COUNT(*) as count, SUM(amount) as total, AVG(amount) as average')
            ->groupBy('benefit_type')
            ->get();

        $approvedCount = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->where('status', 'approved')
            ->count();
        $approvalRate = $totalClaims > 0 ? ($approvedCount / $totalClaims) * 100 : 0;

        $processingTimes = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->whereNotNull('approval_date')
            ->whereNotNull('created_at')
            ->get()
            ->map(function ($welfare) {
                return $welfare->created_at->diffInDays($welfare->approval_date);
            });
        $avgProcessingDays = $processingTimes->count() > 0 ? $processingTimes->avg() : 0;

        $monthlyClaims = [];
        $startDate = \Carbon\Carbon::parse($dateFrom);
        $endDate = \Carbon\Carbon::parse($dateTo);

        for ($month = $startDate->copy()->startOfMonth(); $month->lte($endDate); $month->addMonth()) {
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyClaims[] = [
                'month' => $month->format('M Y'),
                'total' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->count(),
                'approved' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->where('status', 'approved')
                    ->count(),
                'rejected' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->where('status', 'rejected')
                    ->count(),
            ];
        }

        $recentClaims = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user', 'approver')
            ->orderBy('transaction_date', 'desc')
            ->limit(50)
            ->get();

        $stats = [
            'total_claims' => $totalClaims,
            'approved_count' => $approvedCount,
            'rejected_count' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->where('status', 'rejected')
                ->count(),
            'pending_count' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->where('status', 'pending')
                ->count(),
            'approval_rate' => $approvalRate,
            'avg_processing_days' => round($avgProcessingDays, 1),
            'total_amount' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->sum('amount'),
        ];

        $organizationInfo = [
            'name' => 'FeedTan Community Microfinance Group',
            'address' => 'P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania',
            'email' => 'feedtan15@gmail.com',
            'phone' => '+255622239304',
        ];

        return PdfHelper::downloadPdf('admin.welfare.reports.pdf.claim-analysis', [
            'stats' => $stats,
            'statusBreakdown' => $statusBreakdown,
            'benefitTypeBreakdown' => $benefitTypeBreakdown,
            'monthlyClaims' => $monthlyClaims,
            'recentClaims' => $recentClaims,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'organizationInfo' => $organizationInfo,
            'documentTitle' => 'Welfare Claim Analysis Report',
            'documentSubtitle' => 'Period: '.\Carbon\Carbon::parse($dateFrom)->format('M j, Y').' - '.\Carbon\Carbon::parse($dateTo)->format('M j, Y'),
        ], 'welfare-claim-analysis-'.date('Y-m-d-His').'.pdf');
    }

    /**
     * Beneficiary Report
     */
    public function beneficiaryReport(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        // Total beneficiaries
        $totalBeneficiaries = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->distinct('user_id')
            ->count('user_id');

        // Top beneficiaries by amount
        $topBeneficiaries = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user')
            ->selectRaw('user_id, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(20)
            ->get();

        // Top beneficiaries by frequency
        $topByFrequency = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user')
            ->selectRaw('user_id, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        // Beneficiary distribution by benefit type
        $beneficiaryByType = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('benefit_type, COUNT(DISTINCT user_id) as beneficiary_count, SUM(amount) as total')
            ->groupBy('benefit_type')
            ->get();

        // Beneficiary distribution by status
        $beneficiaryByStatus = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(DISTINCT user_id) as beneficiary_count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        // Monthly beneficiary trend
        $monthlyBeneficiaries = [];
        $startDate = \Carbon\Carbon::parse($dateFrom);
        $endDate = \Carbon\Carbon::parse($dateTo);

        for ($month = $startDate->copy()->startOfMonth(); $month->lte($endDate); $month->addMonth()) {
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyBeneficiaries[] = [
                'month' => $month->format('M Y'),
                'count' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->distinct('user_id')
                    ->count('user_id'),
                'total_amount' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->sum('amount'),
            ];
        }

        // All beneficiaries with details
        $beneficiaries = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user')
            ->selectRaw('user_id, SUM(amount) as total, COUNT(*) as count, MIN(transaction_date) as first_benefit, MAX(transaction_date) as last_benefit')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->paginate(20);

        $stats = [
            'total_beneficiaries' => $totalBeneficiaries,
            'total_benefits' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->sum('amount'),
            'avg_benefit_per_beneficiary' => $totalBeneficiaries > 0 ? SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->sum('amount') / $totalBeneficiaries : 0,
            'total_claims' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->count(),
        ];

        return view('admin.welfare.reports.beneficiary', compact('stats', 'topBeneficiaries', 'topByFrequency', 'beneficiaryByType', 'beneficiaryByStatus', 'monthlyBeneficiaries', 'beneficiaries', 'dateFrom', 'dateTo'));
    }

    /**
     * Beneficiary Report PDF
     */
    public function beneficiaryReportPdf(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        $totalBeneficiaries = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->distinct('user_id')
            ->count('user_id');

        $topBeneficiaries = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user')
            ->selectRaw('user_id, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(50)
            ->get();

        $beneficiaryByType = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('benefit_type, COUNT(DISTINCT user_id) as beneficiary_count, SUM(amount) as total')
            ->groupBy('benefit_type')
            ->get();

        $beneficiaryByStatus = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(DISTINCT user_id) as beneficiary_count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        $monthlyBeneficiaries = [];
        $startDate = \Carbon\Carbon::parse($dateFrom);
        $endDate = \Carbon\Carbon::parse($dateTo);

        for ($month = $startDate->copy()->startOfMonth(); $month->lte($endDate); $month->addMonth()) {
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyBeneficiaries[] = [
                'month' => $month->format('M Y'),
                'count' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->distinct('user_id')
                    ->count('user_id'),
                'total_amount' => SocialWelfare::where('type', 'benefit')
                    ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                    ->sum('amount'),
            ];
        }

        $beneficiaries = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->with('user')
            ->selectRaw('user_id, SUM(amount) as total, COUNT(*) as count, MIN(transaction_date) as first_benefit, MAX(transaction_date) as last_benefit')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(100)
            ->get();

        $stats = [
            'total_beneficiaries' => $totalBeneficiaries,
            'total_benefits' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->sum('amount'),
            'avg_benefit_per_beneficiary' => $totalBeneficiaries > 0 ? SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->sum('amount') / $totalBeneficiaries : 0,
            'total_claims' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->count(),
        ];

        $organizationInfo = [
            'name' => 'FeedTan Community Microfinance Group',
            'address' => 'P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania',
            'email' => 'feedtan15@gmail.com',
            'phone' => '+255622239304',
        ];

        return PdfHelper::downloadPdf('admin.welfare.reports.pdf.beneficiary', [
            'stats' => $stats,
            'topBeneficiaries' => $topBeneficiaries,
            'beneficiaryByType' => $beneficiaryByType,
            'beneficiaryByStatus' => $beneficiaryByStatus,
            'monthlyBeneficiaries' => $monthlyBeneficiaries,
            'beneficiaries' => $beneficiaries,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'organizationInfo' => $organizationInfo,
            'documentTitle' => 'Welfare Beneficiary Report',
            'documentSubtitle' => 'Period: '.\Carbon\Carbon::parse($dateFrom)->format('M j, Y').' - '.\Carbon\Carbon::parse($dateTo)->format('M j, Y'),
        ], 'welfare-beneficiary-'.date('Y-m-d-His').'.pdf');
    }

    /**
     * Utilization Statistics Report
     */
    public function utilizationReport(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        // Total contributions
        $totalContributions = SocialWelfare::where('type', 'contribution')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');

        // Total benefits
        $totalBenefits = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');

        // Utilization rate
        $utilizationRate = $totalContributions > 0 ? ($totalBenefits / $totalContributions) * 100 : 0;

        // Benefit type utilization
        $benefitTypeUtilization = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('benefit_type, COUNT(*) as count, SUM(amount) as total, (SUM(amount) / ? * 100) as percentage', [$totalContributions])
            ->groupBy('benefit_type')
            ->get();

        // Monthly utilization trend
        $monthlyUtilization = [];
        $startDate = \Carbon\Carbon::parse($dateFrom);
        $endDate = \Carbon\Carbon::parse($dateTo);

        for ($month = $startDate->copy()->startOfMonth(); $month->lte($endDate); $month->addMonth()) {
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthContributions = SocialWelfare::where('type', 'contribution')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                ->sum('amount');
            $monthBenefits = SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                ->sum('amount');
            $monthUtilizationRate = $monthContributions > 0 ? ($monthBenefits / $monthContributions) * 100 : 0;

            $monthlyUtilization[] = [
                'month' => $month->format('M Y'),
                'contributions' => $monthContributions,
                'benefits' => $monthBenefits,
                'utilization_rate' => $monthUtilizationRate,
                'net' => $monthContributions - $monthBenefits,
            ];
        }

        // Peak utilization periods
        $peakPeriods = collect($monthlyUtilization)
            ->sortByDesc('utilization_rate')
            ->take(5)
            ->values();

        // Average utilization
        $avgUtilization = collect($monthlyUtilization)->avg('utilization_rate');

        // Utilization by status
        $utilizationByStatus = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        $stats = [
            'total_contributions' => $totalContributions,
            'total_benefits' => $totalBenefits,
            'utilization_rate' => $utilizationRate,
            'avg_utilization_rate' => $avgUtilization,
            'net_balance' => $totalContributions - $totalBenefits,
            'contribution_count' => SocialWelfare::where('type', 'contribution')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->count(),
            'benefit_count' => SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                ->count(),
        ];

        return view('admin.welfare.reports.utilization', compact('stats', 'benefitTypeUtilization', 'monthlyUtilization', 'peakPeriods', 'utilizationByStatus', 'dateFrom', 'dateTo'));
    }

    /**
     * Utilization Statistics Report PDF
     */
    public function utilizationReportPdf(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        $totalContributions = SocialWelfare::where('type', 'contribution')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');
        $totalBenefits = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');
        $utilizationRate = $totalContributions > 0 ? ($totalBenefits / $totalContributions) * 100 : 0;

        $benefitTypeUtilization = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('benefit_type, COUNT(*) as count, SUM(amount) as total, (SUM(amount) / ? * 100) as percentage', [$totalContributions])
            ->groupBy('benefit_type')
            ->get();

        $monthlyUtilization = [];
        $startDate = \Carbon\Carbon::parse($dateFrom);
        $endDate = \Carbon\Carbon::parse($dateTo);

        for ($month = $startDate->copy()->startOfMonth(); $month->lte($endDate); $month->addMonth()) {
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthContributions = SocialWelfare::where('type', 'contribution')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                ->sum('amount');
            $monthBenefits = SocialWelfare::where('type', 'benefit')
                ->whereBetween('transaction_date', [$monthStart, $monthEnd])
                ->sum('amount');
            $monthUtilizationRate = $monthContributions > 0 ? ($monthBenefits / $monthContributions) * 100 : 0;

            $monthlyUtilization[] = [
                'month' => $month->format('M Y'),
                'contributions' => $monthContributions,
                'benefits' => $monthBenefits,
                'utilization_rate' => $monthUtilizationRate,
                'net' => $monthContributions - $monthBenefits,
            ];
        }

        $peakPeriods = collect($monthlyUtilization)
            ->sortByDesc('utilization_rate')
            ->take(5)
            ->values();

        $avgUtilization = collect($monthlyUtilization)->avg('utilization_rate');

        $utilizationByStatus = SocialWelfare::where('type', 'benefit')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();

        $stats = [
            'total_contributions' => $totalContributions,
            'total_benefits' => $totalBenefits,
            'utilization_rate' => $utilizationRate,
            'avg_utilization_rate' => $avgUtilization,
            'net_balance' => $totalContributions - $totalBenefits,
        ];

        $organizationInfo = [
            'name' => 'FeedTan Community Microfinance Group',
            'address' => 'P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania',
            'email' => 'feedtan15@gmail.com',
            'phone' => '+255622239304',
        ];

        return PdfHelper::downloadPdf('admin.welfare.reports.pdf.utilization', [
            'stats' => $stats,
            'benefitTypeUtilization' => $benefitTypeUtilization,
            'monthlyUtilization' => $monthlyUtilization,
            'peakPeriods' => $peakPeriods,
            'utilizationByStatus' => $utilizationByStatus,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'organizationInfo' => $organizationInfo,
            'documentTitle' => 'Welfare Utilization Statistics Report',
            'documentSubtitle' => 'Period: '.\Carbon\Carbon::parse($dateFrom)->format('M j, Y').' - '.\Carbon\Carbon::parse($dateTo)->format('M j, Y'),
        ], 'welfare-utilization-'.date('Y-m-d-His').'.pdf');
    }
}
