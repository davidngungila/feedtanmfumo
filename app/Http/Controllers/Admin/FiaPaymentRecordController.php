<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FiaPaymentRecordController extends Controller
{
    /**
     * Display FIA Payment Records dashboard
     */
    public function index()
    {
        $totalRecords = DB::table('fia_payment_records')->count();
        $totalFiaGawio = DB::table('fia_payment_records')->sum('gawio_la_fia');
        $totalFiaIlivyoKoma = DB::table('fia_payment_records')->sum('fia_iliyokomaa');
        $totalJumla = DB::table('fia_payment_records')->sum('jumla');
        $totalLoanBalance = DB::table('fia_payment_records')->sum('kiasi_baki');
        
        // Get recent records
        $recentRecords = DB::table('fia_payment_records')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($record) {
                $record->created_at = \Carbon\Carbon::parse($record->created_at);
                return $record;
            });
        
        return view('admin.fia-payment-records.index', compact(
            'totalRecords',
            'totalFiaGawio', 
            'totalFiaIlivyoKoma',
            'totalJumla',
            'totalLoanBalance',
            'recentRecords'
        ));
    }

    /**
     * Display all payment records
     */
    public function records()
    {
        $records = DB::table('fia_payment_records')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.fia-payment-records.records', compact('records'));
    }

    /**
     * Show upload form
     */
    public function upload()
    {
        return view('admin.fia-payment-records.upload');
    }

    /**
     * Process Excel upload
     */
    public function processUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('excel_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('fia_payment_records', $filename, 'public');

            // Process the file
            $result = $this->processFile($file);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully imported {$result['imported']} payment records. {$result['skipped']} rows were skipped.",
                    'filename' => $filename,
                    'imported' => $result['imported'],
                    'skipped' => $result['skipped']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process uploaded file (CSV/XLSX)
     */
    private function processFile($file)
    {
        $imported = 0;
        $skipped = 0;
        $errors = [];

        try {
            $filePath = $file->getRealPath();
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'csv') {
                $result = $this->processCsvFile($filePath);
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                $result = $this->processExcelFile($filePath);
            } else {
                return [
                    'success' => false,
                    'message' => 'Unsupported file format'
                ];
            }

            return $result;

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error processing file: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process CSV file
     */
    private function processCsvFile($filePath)
    {
        $imported = 0;
        $skipped = 0;
        
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ','); // Get header row
            
            // Create column mapping (case-insensitive)
            $headerMap = [];
            foreach ($header as $index => $columnName) {
                $cleanName = strtolower(trim($columnName));
                $headerMap[$cleanName] = $index;
            }

            // Define possible column names for each field
            $columnMappings = [
                'member_id' => ['id', 'member id', 'member_id', 'memberid', 'member-number', 'member number'],
                'member_name' => ['name', 'member name', 'member_name', 'membername', 'full name', 'fullname'],
                'gawio_la_fia' => ['gawio la fia', 'gawio', 'fia gawio', 'gawio_fia', 'gawio-lafia'],
                'fia_iliyokomaa' => ['fia iliyokomaa', 'fia komaa', 'fia_komaa', 'fia-iliyokomaa', 'fia komaa'],
                'jumla' => ['jumla', 'total', 'sum', 'amount', 'jumla/kiasi', 'jumla kiasi'],
                'malipo_ya_vipande_yaliyokuwa_yamepelea' => [
                    'malipo ya vipande yailiyakuwa yamepelea', 
                    'malipo ya vipande', 
                    'vipande malipo', 
                    'malipo_vipande', 
                    'installments paid',
                    'paid installments'
                ],
                'loan' => ['loan', 'loan amount', 'kopo', 'kopo kiasi', 'loan_amount'],
                'kiasi_baki' => ['kiasi baki', 'balance', 'remaining', 'baki', 'kiasi_baki', 'amount remaining']
            ];

            // Find column indices for each field
            $columnIndices = [];
            foreach ($columnMappings as $field => $possibleNames) {
                $columnIndices[$field] = null;
                foreach ($possibleNames as $name) {
                    if (isset($headerMap[strtolower($name)])) {
                        $columnIndices[$field] = $headerMap[strtolower($name)];
                        break;
                    }
                }
            }

            // Check if we found at least member_id and member_name
            if ($columnIndices['member_id'] === null || $columnIndices['member_name'] === null) {
                return [
                    'success' => false,
                    'message' => 'Could not find Member ID or Member Name columns. Please ensure your file has columns with names like: ID, Member ID, Name, Member Name, etc.'
                ];
            }

            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $recordData = [];
                foreach ($columnIndices as $field => $index) {
                    if ($index !== null && isset($data[$index])) {
                        $recordData[$field] = trim($data[$index]);
                    } else {
                        $recordData[$field] = 0; // Default for numeric fields
                    }
                }

                $result = $this->savePaymentRecord([
                    'member_id' => $recordData['member_id'],
                    'member_name' => $recordData['member_name'],
                    'gawio_la_fia' => $this->parseAmount($recordData['gawio_la_fia']),
                    'fia_iliyokomaa' => $this->parseAmount($recordData['fia_iliyokomaa']),
                    'jumla' => $this->parseAmount($recordData['jumla']),
                    'malipo_ya_vipande_yaliyokuwa_yamepelea' => $this->parseAmount($recordData['malipo_ya_vipande_yaliyokuwa_yamepelea']),
                    'loan' => $this->parseAmount($recordData['loan']),
                    'kiasi_baki' => $this->parseAmount($recordData['kiasi_baki']),
                ]);

                if ($result) {
                    $imported++;
                } else {
                    $skipped++;
                }
            }
            fclose($handle);
        }

        return [
            'success' => true,
            'imported' => $imported,
            'skipped' => $skipped
        ];
    }

    /**
     * Process Excel file using PhpSpreadsheet
     */
    private function processExcelFile($filePath)
    {
        $imported = 0;
        $skipped = 0;

        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            // Get header row
            $header = [];
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $header[] = $worksheet->getCell($col . '1')->getValue();
            }

            // Create column mapping (case-insensitive)
            $headerMap = [];
            foreach ($header as $index => $columnName) {
                $cleanName = strtolower(trim($columnName));
                $headerMap[$cleanName] = $index;
            }

            // Define possible column names for each field
            $columnMappings = [
                'member_id' => ['id', 'member id', 'member_id', 'memberid', 'member-number', 'member number'],
                'member_name' => ['name', 'member name', 'member_name', 'membername', 'full name', 'fullname'],
                'gawio_la_fia' => ['gawio la fia', 'gawio', 'fia gawio', 'gawio_fia', 'gawio-lafia'],
                'fia_iliyokomaa' => ['fia iliyokomaa', 'fia komaa', 'fia_komaa', 'fia-iliyokomaa', 'fia komaa'],
                'jumla' => ['jumla', 'total', 'sum', 'amount', 'jumla/kiasi', 'jumla kiasi'],
                'malipo_ya_vipande_yaliyokuwa_yamepelea' => [
                    'malipo ya vipande yailiyakuwa yamepelea', 
                    'malipo ya vipande', 
                    'vipande malipo', 
                    'malipo_vipande', 
                    'installments paid',
                    'paid installments'
                ],
                'loan' => ['loan', 'loan amount', 'kopo', 'kopo kiasi', 'loan_amount'],
                'kiasi_baki' => ['kiasi baki', 'balance', 'remaining', 'baki', 'kiasi_baki', 'amount remaining']
            ];

            // Find column indices for each field
            $columnIndices = [];
            foreach ($columnMappings as $field => $possibleNames) {
                $columnIndices[$field] = null;
                foreach ($possibleNames as $name) {
                    if (isset($headerMap[strtolower($name)])) {
                        $columnIndices[$field] = $headerMap[strtolower($name)];
                        break;
                    }
                }
            }

            // Check if we found at least member_id and member_name
            if ($columnIndices['member_id'] === null || $columnIndices['member_name'] === null) {
                return [
                    'success' => false,
                    'message' => 'Could not find Member ID or Member Name columns. Please ensure your file has columns with names like: ID, Member ID, Name, Member Name, etc.'
                ];
            }

            // Process data rows
            for ($row = 2; $row <= $highestRow; $row++) {
                $recordData = [];
                foreach ($columnIndices as $field => $index) {
                    if ($index !== null) {
                        $cellValue = $worksheet->getCell([$index + 1, $row])->getValue();
                        $recordData[$field] = trim($cellValue);
                    } else {
                        $recordData[$field] = 0; // Default for numeric fields
                    }
                }

                $result = $this->savePaymentRecord([
                    'member_id' => $recordData['member_id'],
                    'member_name' => $recordData['member_name'],
                    'gawio_la_fia' => $this->parseAmount($recordData['gawio_la_fia']),
                    'fia_iliyokomaa' => $this->parseAmount($recordData['fia_iliyokomaa']),
                    'jumla' => $this->parseAmount($recordData['jumla']),
                    'malipo_ya_vipande_yaliyokuwa_yamepelea' => $this->parseAmount($recordData['malipo_ya_vipande_yaliyokuwa_yamepelea']),
                    'loan' => $this->parseAmount($recordData['loan']),
                    'kiasi_baki' => $this->parseAmount($recordData['kiasi_baki']),
                ]);

                if ($result) {
                    $imported++;
                } else {
                    $skipped++;
                }
            }

            return [
                'success' => true,
                'imported' => $imported,
                'skipped' => $skipped
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error processing Excel file: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate CSV headers
     */
    private function validateHeaders($actualHeaders, $expectedHeaders)
    {
        $normalizedActual = array_map('strtolower', array_map('trim', $actualHeaders));
        $normalizedExpected = array_map('strtolower', $expectedHeaders);
        
        foreach ($normalizedExpected as $expected) {
            if (!in_array($expected, $normalizedActual)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Parse amount from string
     */
    private function parseAmount($value)
    {
        // Remove commas, spaces, and other formatting
        $cleaned = preg_replace('/[^0-9.-]/', '', $value);
        return is_numeric($cleaned) ? floatval($cleaned) : 0;
    }

    /**
     * Save payment record to database
     */
    private function savePaymentRecord($data)
    {
        try {
            // Validate required fields
            if (empty($data['member_id']) || empty($data['member_name'])) {
                return false;
            }

            // Check if record already exists
            $existing = DB::table('fia_payment_records')
                ->where('member_id', $data['member_id'])
                ->first();

            if ($existing) {
                // Update existing record
                DB::table('fia_payment_records')
                    ->where('member_id', $data['member_id'])
                    ->update([
                        'member_name' => $data['member_name'],
                        'gawio_la_fia' => $data['gawio_la_fia'],
                        'fia_iliyokomaa' => $data['fia_iliyokomaa'],
                        'jumla' => $data['jumla'],
                        'malipo_ya_vipande_yaliyokuwa_yamepelea' => $data['malipo_ya_vipande_yaliyokuwa_yamepelea'],
                        'loan' => $data['loan'],
                        'kiasi_baki' => $data['kiasi_baki'],
                        'updated_at' => now(),
                    ]);
            } else {
                // Insert new record
                DB::table('fia_payment_records')->insert([
                    'member_id' => $data['member_id'],
                    'member_name' => $data['member_name'],
                    'gawio_la_fia' => $data['gawio_la_fia'],
                    'fia_iliyokomaa' => $data['fia_iliyokomaa'],
                    'jumla' => $data['jumla'],
                    'malipo_ya_vipande_yaliyokuwa_yamepelea' => $data['malipo_ya_vipande_yaliyokuwa_yamepelea'],
                    'loan' => $data['loan'],
                    'kiasi_baki' => $data['kiasi_baki'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Error saving payment record: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payment records data for AJAX
     */
    public function getRecords(Request $request)
    {
        $query = DB::table('fia_payment_records');
        
        // Search by ID or name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                  ->orWhere('member_name', 'like', "%{$search}%");
            });
        }
        
        $records = $query->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $records
        ]);
    }

    /**
     * Get member payment details for public confirmation
     */
    public function getMemberPayments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Member ID is required',
            ], 422);
        }

        $memberId = trim($request->input('member_id'));
        
        $record = DB::table('fia_payment_records')
            ->where('member_id', $memberId)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found. Please check your member ID or contact the administrator.',
            ], 404);
        }

        // Check if member has already submitted confirmation
        $existingConfirmation = DB::table('payment_confirmations')
            ->where('member_id', $memberId)
            ->first();

        return response()->json([
            'success' => true,
            'member' => [
                'id' => $record->id,
                'member_id' => $record->member_id,
                'name' => $record->member_name,
                'gawio_la_fia' => $record->gawio_la_fia,
                'fia_iliyokomaa' => $record->fia_iliyokomaa,
                'jumla' => $record->jumla,
                'malipo_ya_vipande' => $record->malipo_ya_vipande_yaliyokuwa_yamepelea,
                'loan' => $record->loan,
                'kiasi_baki' => $record->kiasi_baki,
                'has_existing_confirmation' => $existingConfirmation ? true : false,
                'existing_confirmation' => $existingConfirmation,
            ],
        ]);
    }

    /**
     * Export payment records to Excel
     */
    public function exportRecords(Request $request)
    {
        $query = DB::table('fia_payment_records');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                  ->orWhere('member_name', 'like', "%{$search}%");
            });
        }

        $records = $query->orderBy('created_at', 'desc')->get();
        
        // Create CSV content
        $filename = 'fia_payment_records_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($records) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'S/N', 'Member ID', 'Name', 'Gawio la FIA', 'FIA iliyokomaa', 
                'Jumla', 'Malipo ya vipande yailiyakuwa Yamepelea', 'LOAN', 'Kiasi baki'
            ]);
            
            // CSV Data
            foreach ($records as $index => $record) {
                fputcsv($file, [
                    $index + 1,
                    $record->member_id,
                    $record->member_name,
                    $record->gawio_la_fia,
                    $record->fia_iliyokomaa,
                    $record->jumla,
                    $record->malipo_ya_vipande_yaliyokuwa_yamepelea,
                    $record->loan,
                    $record->kiasi_baki
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get payment records statistics
     */
    public function getStatistics()
    {
        $stats = [
            'total_records' => DB::table('fia_payment_records')->count(),
            'total_gawio' => DB::table('fia_payment_records')->sum('gawio_la_fia'),
            'total_fia_komaa' => DB::table('fia_payment_records')->sum('fia_iliyokomaa'),
            'total_jumla' => DB::table('fia_payment_records')->sum('jumla'),
            'total_loan_balance' => DB::table('fia_payment_records')->sum('kiasi_baki'),
            'recent_records' => DB::table('fia_payment_records')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Delete a payment record
     */
    public function destroy($id)
    {
        try {
            $deleted = DB::table('fia_payment_records')->where('id', $id)->delete();
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment record deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment record not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
