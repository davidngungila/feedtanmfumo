<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanStatement;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class LoanStatementController extends Controller
{
    public function index()
    {
        $months = LoanStatement::select('month', 'year')
            ->groupBy('month', 'year')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.loan-statements.index', compact('months'));
    }

    public function show($year, $month)
    {
        $statements = LoanStatement::where('year', $year)
            ->where('month', $month)
            ->paginate(100);

        return view('admin.loan-statements.show', compact('statements', 'year', 'month'));
    }

    public function create()
    {
        return view('admin.loan-statements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
        ]);

        $file = $request->file('excel_file');
        $month = $request->input('month');
        $year = $request->input('year');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $header = array_shift($rows);
            $map = $this->mapHeaders($header);

            if (!isset($map['member_id'])) {
                return back()->with('error', 'Column "Member ID" or "ID" not found in Excel.');
            }

            DB::beginTransaction();

            LoanStatement::where('month', $month)->where('year', $year)->delete();

            foreach ($rows as $row) {
                if (empty(array_filter($row))) continue;

                $memberId = trim($row[$map['member_id']] ?? '');
                if (empty($memberId)) continue;

                $user = User::where('membership_code', $memberId)
                    ->orWhere('member_number', $memberId)
                    ->first();

                LoanStatement::create([
                    'user_id' => $user?->id,
                    'member_id' => $user?->membership_code ?? $memberId,
                    'name' => $row[$map['name'] ?? -1] ?? 'N/A',
                    'month' => $month,
                    'year' => $year,
                    'opening_balance' => $this->parseAmount($row[$map['opening_balance'] ?? -1] ?? 0),
                    'principal_paid' => $this->parseAmount($row[$map['principal'] ?? -1] ?? 0),
                    'interest_paid' => $this->parseAmount($row[$map['interest'] ?? -1] ?? 0),
                    'penalty_paid' => $this->parseAmount($row[$map['penalty'] ?? -1] ?? 0),
                    'total_paid' => $this->parseAmount($row[$map['total_paid'] ?? -1] ?? 0),
                    'closing_balance' => $this->parseAmount($row[$map['closing_balance'] ?? -1] ?? 0),
                    'notes' => 'Imported from Excel',
                ]);
            }

            DB::commit();
            return redirect()->route('admin.loan-statements.index')->with('success', 'Loan statements imported successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error importing Excel: ' . $e->getMessage());
        }
    }

    private function mapHeaders($header)
    {
        $map = [];
        foreach ($header as $index => $col) {
            $col = strtolower(trim($col));
            if (str_contains($col, 'member id') || str_contains($col, 'id') || str_contains($col, 'code')) $map['member_id'] = $index;
            if (str_contains($col, 'name')) $map['name'] = $index;
            if (str_contains($col, 'opening')) $map['opening_balance'] = $index;
            if (str_contains($col, 'principal')) $map['principal'] = $index;
            if (str_contains($col, 'interest')) $map['interest'] = $index;
            if (str_contains($col, 'penalty') || str_contains($col, 'fine')) $map['penalty'] = $index;
            if (str_contains($col, 'total') || str_contains($col, 'paid')) $map['total_paid'] = $index;
            if (str_contains($col, 'closing')) $map['closing_balance'] = $index;
        }
        return $map;
    }

    private function parseAmount($value)
    {
        return (float) str_replace(',', '', $value);
    }
}
