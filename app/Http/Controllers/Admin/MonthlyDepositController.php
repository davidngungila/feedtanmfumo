<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyDeposit;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class MonthlyDepositController extends Controller
{
    public function index()
    {
        $months = MonthlyDeposit::select('month', 'year')
            ->groupBy('month', 'year')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.monthly-deposits.index', compact('months'));
    }

    public function show($year, $month)
    {
        $deposits = MonthlyDeposit::where('year', $year)
            ->where('month', $month)
            ->paginate(50);

        return view('admin.monthly-deposits.show', compact('deposits', 'year', 'month'));
    }

    public function create()
    {
        return view('admin.monthly-deposits.create');
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

            // Assume first row is header
            $header = array_shift($rows);
            
            // Map headers to columns (flexible mapping)
            $map = $this->mapHeaders($header);

            if (!isset($map['member_id'])) {
                return back()->with('error', 'Column "Member ID" or "ID" not found in Excel.');
            }

            DB::beginTransaction();

            // Clear existing records for this month/year to avoid duplicates
            MonthlyDeposit::where('month', $month)->where('year', $year)->delete();

            foreach ($rows as $row) {
                if (empty(array_filter($row))) continue;

                $memberId = trim($row[$map['member_id']] ?? '');
                if (empty($memberId)) continue;

                // Try to link to user
                $user = User::where('member_number', $memberId)
                    ->orWhere('membership_code', $memberId)
                    ->first();

                MonthlyDeposit::create([
                    'user_id' => $user?->id,
                    'member_id' => $memberId,
                    'name' => $row[$map['name'] ?? -1] ?? 'N/A',
                    'email' => $row[$map['email'] ?? -1] ?? null,
                    'month' => $month,
                    'year' => $year,
                    'savings' => $this->parseAmount($row[$map['savings'] ?? -1] ?? 0),
                    'shares' => $this->parseAmount($row[$map['shares'] ?? -1] ?? 0),
                    'welfare' => $this->parseAmount($row[$map['welfare'] ?? -1] ?? 0),
                    'loan_principal' => $this->parseAmount($row[$map['loan_principal'] ?? -1] ?? 0),
                    'loan_interest' => $this->parseAmount($row[$map['loan_interest'] ?? -1] ?? 0),
                    'fine_penalty' => $this->parseAmount($row[$map['fine_penalty'] ?? -1] ?? 0),
                    'total' => $this->parseAmount($row[$map['total'] ?? -1] ?? 0),
                    'statement_pdf' => $row[$map['statement_pdf'] ?? -1] ?? null,
                    'generated_message' => $row[$map['generated_message'] ?? -1] ?? null,
                    'notes' => $row[$map['notes'] ?? -1] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.monthly-deposits.index')->with('success', 'Monthly deposits uploaded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing file: ' . $e->getMessage());
        }
    }

    public function destroy($year, $month)
    {
        MonthlyDeposit::where('year', $year)->where('month', $month)->delete();
        return back()->with('success', 'Records for the selected month deleted.');
    }

    private function mapHeaders($header)
    {
        $map = [];
        foreach ($header as $index => $col) {
            $col = strtolower(trim($col));
            
            // Member ID / Customer ID
            if (str_contains($col, 'member id') || str_contains($col, 'customer id') || $col == 'id' || str_contains($col, 'namba')) $map['member_id'] = $index;
            
            // Name
            if (str_contains($col, 'name') || str_contains($col, 'jina') || str_contains($col, 'customer name')) $map['name'] = $index;
            
            // Email
            if (str_contains($col, 'email')) $map['email'] = $index;
            
            // Amounts
            if (str_contains($col, 'savings') || str_contains($col, 'akiba')) $map['savings'] = $index;
            if (str_contains($col, 'shares') || str_contains($col, 'hisa')) $map['shares'] = $index;
            if (str_contains($col, 'welfare') || str_contains($col, 'jamii')) $map['welfare'] = $index;
            if (str_contains($col, 'loan principal') || str_contains($col, 'mkopo')) $map['loan_principal'] = $index;
            if (str_contains($col, 'loan interest') || str_contains($col, 'riba')) $map['loan_interest'] = $index;
            if (str_contains($col, 'fine') || str_contains($col, 'penalty') || str_contains($col, 'faini')) $map['fine_penalty'] = $index;
            if (str_contains($col, 'total') || str_contains($col, 'jumla')) $map['total'] = $index;
            
            // System specific columns
            if (str_contains($col, 'generated message') || str_contains($col, 'statement generated message')) $map['generated_message'] = $index;
            if (str_contains($col, 'statement pdf') || str_contains($col, 'pdf')) $map['statement_pdf'] = $index;
            
            // Notes
            if (str_contains($col, 'note') || str_contains($col, 'maelezo')) $map['notes'] = $index;
        }
        return $map;
    }

    private function parseAmount($value)
    {
        return (float) str_replace(',', '', $value);
    }
}
