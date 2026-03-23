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
        // Use payment_confirmations table instead of fia_payment_records
        $totalRecords = DB::table('payment_confirmations')->where('fia_investment', '>', 0)->count();
        $totalFiaGawio = DB::table('payment_confirmations')->where('fia_investment', '>', 0)->sum('fia_investment');
        $totalFiaIlivyoKoma = DB::table('payment_confirmations')->where('fia_investment', '>', 0)->sum('capital_contribution');
        $totalJumla = DB::table('payment_confirmations')->where('fia_investment', '>', 0)->sum('amount_to_pay');
        $totalLoanBalance = DB::table('payment_confirmations')->where('fia_investment', '>', 0)->sum('loan_repayment');
        
        // Get recent records
        $recentRecords = DB::table('payment_confirmations')
            ->where('fia_investment', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($record) {
                $record->created_at = \Carbon\Carbon::parse($record->created_at);
                // Map fields to match expected view structure
                $record->gawio_la_fia = $record->fia_investment;
                $record->fia_iliyokomaa = $record->capital_contribution;
                $record->jumla = $record->amount_to_pay;
                $record->kiasi_baki = $record->loan_repayment;
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
        $records = DB::table('payment_confirmations')
            ->where('fia_investment', '>', 0)
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
        \Log::info('Upload process started', [
            'request_data' => $request->all(),
            'has_file' => $request->hasFile('excel_file'),
            'file_info' => $request->hasFile('excel_file') ? [
                'name' => $request->file('excel_file')->getClientOriginalName(),
                'size' => $request->file('excel_file')->getSize(),
                'type' => $request->file('excel_file')->getMimeType()
            ] : 'No file'
        ]);

        // If this is a column mapping request, process the mapping
        if ($request->has('column_mapping')) {
            return $this->processUploadWithMapping($request);
        }

        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        if ($validator->fails()) {
            \Log::error('Upload validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('excel_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('payment_confirmations', $filename, 'public');

            \Log::info('File saved, analyzing columns', ['filename' => $filename]);

            // Analyze the file to get column headers
            $columnAnalysis = $this->analyzeFileColumns($file);

            if (!$columnAnalysis['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $columnAnalysis['message']
                ], 500);
            }

            // Check if we can auto-map columns
            $autoMapping = $this->tryAutoMapping($columnAnalysis['headers']);
            
            if ($autoMapping['all_mapped']) {
                // All columns mapped automatically, process directly
                \Log::info('All columns auto-mapped, processing directly', [
                    'mapping' => $autoMapping['mapping'],
                    'mapping_types' => array_map('gettype', $autoMapping['mapping'])
                ]);
                $result = $this->processFileWithMapping($file, $autoMapping['mapping']);
                
                if ($result['success']) {
                    $message = "Successfully imported {$result['imported']} payment records. {$result['skipped']} rows were skipped.";
                
                    // Add skip reasons to message if any
                    if (!empty($result['skip_reasons'])) {
                        $message .= "\n\nSkip Reasons:\n" . implode("\n", array_slice($result['skip_reasons'], 0, 10));
                        if (count($result['skip_reasons']) > 10) {
                            $message .= "\n... and " . (count($result['skip_reasons']) - 10) . " more";
                        }
                    }
                
                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'filename' => $filename,
                        'imported' => $result['imported'],
                        'skipped' => $result['skipped'],
                        'skip_reasons' => $result['skip_reasons'] ?? []
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => $result['message']
                    ], 500);
                }
            } else {
                // Need user to map columns
                \Log::info('Column mapping required', [
                    'headers' => $columnAnalysis['headers'],
                    'auto_mapping' => $autoMapping
                ]);
                
                return response()->json([
                    'success' => true,
                    'requires_mapping' => true,
                    'message' => 'Please map your Excel columns to the database fields',
                    'headers' => $columnAnalysis['headers'],
                    'sample_data' => $columnAnalysis['sample_data'],
                    'auto_mapping' => $autoMapping['mapping'],
                    'filename' => $filename
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Upload process exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error processing file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Analyze file columns and return headers with sample data
     */
    private function analyzeFileColumns($file)
    {
        try {
            $filePath = $file->getRealPath();
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'csv') {
                return $this->analyzeCsvColumns($filePath);
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                return $this->analyzeExcelColumns($filePath);
            } else {
                return [
                    'success' => false,
                    'message' => 'Unsupported file format'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error analyzing file: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Analyze Excel file columns
     */
    private function analyzeExcelColumns($filePath)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            // Get headers
            $headers = [];
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $headerValue = trim($worksheet->getCell($col . '1')->getValue());
                if ($headerValue) {
                    $headers[$col] = $headerValue;
                }
            }

            // Get sample data from first few rows
            $sampleData = [];
            for ($row = 2; $row <= min(4, $highestRow); $row++) {
                $rowData = [];
                foreach ($headers as $col => $header) {
                    $cellValue = trim($worksheet->getCell($col . $row)->getValue());
                    $rowData[$col] = $cellValue;
                }
                $sampleData[] = $rowData;
            }

            return [
                'success' => true,
                'headers' => $headers,
                'sample_data' => $sampleData,
                'total_rows' => $highestRow - 1 // Subtract header row
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error analyzing Excel file: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Analyze CSV file columns
     */
    private function analyzeCsvColumns($filePath)
    {
        try {
            if (($handle = fopen($filePath, 'r')) !== FALSE) {
                $headers = fgetcsv($handle, 1000, ',');
                $sampleData = [];
                $rowCount = 0;
                
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE && $rowCount < 3) {
                    $rowData = [];
                    foreach ($headers as $index => $header) {
                        $rowData[$index] = isset($data[$index]) ? trim($data[$index]) : '';
                    }
                    $sampleData[] = $rowData;
                    $rowCount++;
                }
                
                fclose($handle);
                
                // Convert indexed headers to associative
                $headerMap = [];
                foreach ($headers as $index => $header) {
                    if (trim($header)) {
                        $headerMap[$index] = trim($header);
                    }
                }
                
                return [
                    'success' => true,
                    'headers' => $headerMap,
                    'sample_data' => $sampleData,
                    'total_rows' => $rowCount
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error analyzing CSV file: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Try to auto-map columns based on common names
     */
    private function tryAutoMapping($headers)
    {
        $requiredFields = [
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

        $mapping = [];
        $allMapped = true;

        foreach ($requiredFields as $field => $possibleNames) {
            $found = false;
            foreach ($headers as $index => $header) {
                $cleanHeader = strtolower(trim($header));
                if (in_array($cleanHeader, array_map('strtolower', $possibleNames))) {
                    $mapping[$field] = $index;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $mapping[$field] = null;
                if (in_array($field, ['member_id', 'member_name'])) {
                    $allMapped = false; // Required fields not found
                }
            }
        }

        return [
            'all_mapped' => $allMapped,
            'mapping' => $mapping
        ];
    }

    /**
     * Process upload with user-defined column mapping
     */
    private function processUploadWithMapping(Request $request)
    {
        try {
            $filename = $request->input('filename');
            $columnMapping = $request->input('column_mapping');
            
            // Get the uploaded file
            $filePath = storage_path('app/public/payment_confirmations/' . $filename);
            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uploaded file not found'
                ], 404);
            }

            // Create a temporary file object
            $file = new \Illuminate\Http\UploadedFile(
                $filePath,
                $filename,
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                null,
                true
            );

            // Process with mapping
            $result = $this->processFileWithMapping($file, $columnMapping);

            if ($result['success']) {
                $message = "Successfully imported {$result['imported']} payment records. {$result['skipped']} rows were skipped.";
                
                // Add skip reasons to message if any
                if (!empty($result['skip_reasons'])) {
                    $message .= "\n\nSkip Reasons:\n" . implode("\n", array_slice($result['skip_reasons'], 0, 10));
                    if (count($result['skip_reasons']) > 10) {
                        $message .= "\n... and " . (count($result['skip_reasons']) - 10) . " more";
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'filename' => $filename,
                    'imported' => $result['imported'],
                    'skipped' => $result['skipped'],
                    'skip_reasons' => $result['skip_reasons'] ?? []
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
                'message' => 'Error processing mapped upload: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process file with explicit column mapping
     */
    private function processFileWithMapping($file, $columnMapping)
    {
        $imported = 0;
        $skipped = 0;

        try {
            $filePath = $file->getRealPath();
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'csv') {
                $result = $this->processCsvFileWithMapping($filePath, $columnMapping);
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                $result = $this->processExcelFileWithMapping($filePath, $columnMapping);
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
     * Process Excel file with explicit column mapping
     */
    private function processExcelFileWithMapping($filePath, $columnMapping)
    {
        $imported = 0;
        $skipped = 0;

        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            // Process data rows
            $skipReasons = [];
            for ($row = 2; $row <= $highestRow; $row++) {
                $recordData = [];
                
                foreach ($columnMapping as $field => $colIndex) {
                    if ($colIndex !== null && $colIndex !== '') {
                        \Log::info("Processing field: $field, colIndex: $colIndex, type: " . gettype($colIndex));
                        // Convert colIndex to integer to avoid string + int error
                        $colIndex = (int)$colIndex;
                        $cellValue = $worksheet->getCell([$colIndex + 1, $row])->getValue();
                        $recordData[$field] = trim($cellValue);
                    } else {
                        $recordData[$field] = $field === 'member_id' || $field === 'member_name' ? '' : 0;
                    }
                }

                // Track skip reasons
                $originalData = [
                    'member_id' => $recordData['member_id'],
                    'member_name' => $recordData['member_name'],
                    'gawio_la_fia' => $this->parseAmount($recordData['gawio_la_fia']),
                    'fia_iliyokomaa' => $this->parseAmount($recordData['fia_iliyokomaa']),
                    'jumla' => $this->parseAmount($recordData['jumla']),
                    'malipo_ya_vipande_yaliyokuwa_yamepelea' => $this->parseAmount($recordData['malipo_ya_vipande_yaliyokuwa_yamepelea']),
                    'loan' => $this->parseAmount($recordData['loan']),
                    'kiasi_baki' => $this->parseAmount($recordData['kiasi_baki']),
                ];

                $result = $this->savePaymentRecord($originalData);

                if ($result) {
                    $imported++;
                } else {
                    $skipped++;
                    // Collect skip reason
                    if (empty($recordData['member_id']) && empty($recordData['member_name'])) {
                        $skipReasons[] = "Row $row: Missing both Member ID and Name";
                    } elseif (empty($recordData['member_id'])) {
                        $skipReasons[] = "Row $row: Missing Member ID (Name: '{$recordData['member_name']}')";
                    } elseif (empty($recordData['member_name'])) {
                        $skipReasons[] = "Row $row: Missing Member Name (ID: '{$recordData['member_id']}')";
                    } else {
                        $skipReasons[] = "Row $row: Database error (ID: '{$recordData['member_id']}', Name: '{$recordData['member_name']}')";
                    }
                }
            }

            return [
                'success' => true,
                'imported' => $imported,
                'skipped' => $skipped,
                'skip_reasons' => $skipReasons
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error processing Excel file: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process CSV file with explicit column mapping
     */
    private function processCsvFileWithMapping($filePath, $columnMapping)
    {
        $imported = 0;
        $skipped = 0;

        try {
            if (($handle = fopen($filePath, 'r')) !== FALSE) {
                // Skip header row
                fgetcsv($handle, 1000, ',');
                
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $recordData = [];
                    
                    foreach ($columnMapping as $field => $colIndex) {
                        if ($colIndex !== null && $colIndex !== '' && isset($data[$colIndex])) {
                            // Convert colIndex to integer to avoid array access issues
                            $colIndex = (int)$colIndex;
                            $recordData[$field] = trim($data[$colIndex]);
                        } else {
                            $recordData[$field] = $field === 'member_id' || $field === 'member_name' ? '' : 0;
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

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error processing CSV file: ' . $e->getMessage()
            ];
        }
    }

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

            // Log detected columns for debugging
            \Log::info('Excel processing - Detected columns:', [
                'headers' => $header,
                'headerMap' => $headerMap,
                'columnIndices' => $columnIndices
            ]);

            // Check if we found at least member_id and member_name
            if ($columnIndices['member_id'] === null || $columnIndices['member_name'] === null) {
                \Log::error('Required columns not found:', [
                    'member_id_found' => $columnIndices['member_id'] !== null,
                    'member_name_found' => $columnIndices['member_name'] !== null,
                    'available_headers' => array_keys($headerMap)
                ]);
                return [
                    'success' => false,
                    'message' => 'Could not find Member ID or Member Name columns. Please ensure your file has columns with names like: ID, Member ID, Name, Member Name, etc. Found headers: ' . implode(', ', $header)
                ];
            }

            // Log the first few rows to see what data looks like
            \Log::info('Sample data from first few rows:');
            for ($row = 2; $row <= min(4, $highestRow); $row++) {
                $rowData = [];
                foreach ($columnIndices as $field => $index) {
                    if ($index !== null) {
                        $cellValue = $worksheet->getCell([$index + 1, $row])->getValue();
                        $rowData[$field] = trim($cellValue);
                    } else {
                        $rowData[$field] = 'NOT_FOUND';
                    }
                }
                \Log::info("Row $row data:", $rowData);
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

                // Log first few rows for debugging
                if ($row <= 3) {
                    \Log::info("Processing row $row:", $recordData);
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
            // Log the incoming data for debugging
            \Log::info('Attempting to save payment record:', $data);
            
            // Validate required fields
            if (empty($data['member_id']) || empty($data['member_name'])) {
                $reason = [];
                if (empty($data['member_id'])) $reason[] = 'member_id is empty';
                if (empty($data['member_name'])) $reason[] = 'member_name is empty';
                
                \Log::warning('Skipping record - missing required fields:', [
                    'member_id' => $data['member_id'] ?? 'empty',
                    'member_name' => $data['member_name'] ?? 'empty',
                    'reason' => implode(', ', $reason),
                    'full_data' => $data
                ]);
                return false;
            }

            // Check if record already exists
            $existing = DB::table('payment_confirmations')
                ->where('member_id', $data['member_id'])
                ->first();

            if ($existing) {
                \Log::info('Updating existing record for member: ' . $data['member_id']);
                // Update existing record - map fields to payment_confirmations table structure
                $updated = DB::table('payment_confirmations')
                    ->where('member_id', $data['member_id'])
                    ->update([
                        'member_name' => $data['member_name'],
                        'fia_investment' => $data['gawio_la_fia'],
                        'capital_contribution' => $data['fia_iliyokomaa'],
                        'amount_to_pay' => $data['jumla'],
                        'loan_repayment' => $data['loan'],
                        'updated_at' => now(),
                    ]);
                
                \Log::info('Update result: ' . ($updated ? 'success' : 'failed'));
            } else {
                \Log::info('Inserting new record for member: ' . $data['member_id']);
                // Insert new record - map fields to payment_confirmations table structure
                $inserted = DB::table('payment_confirmations')->insert([
                    'member_id' => $data['member_id'],
                    'member_name' => $data['member_name'],
                    'fia_investment' => $data['gawio_la_fia'],
                    'capital_contribution' => $data['fia_iliyokomaa'],
                    'amount_to_pay' => $data['jumla'],
                    'loan_repayment' => $data['loan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                \Log::info('Insert result: ' . ($inserted ? 'success' : 'failed'));
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Error saving payment record: ' . $e->getMessage());
            \Log::error('Exception details:', [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Get payment records data for AJAX
     */
    public function getRecords(Request $request)
    {
        $query = DB::table('payment_confirmations')->where('fia_investment', '>', 0);
        
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
        
        $record = DB::table('payment_confirmations')
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
                'gawio_la_fia' => $record->fia_investment,
                'fia_iliyokomaa' => $record->capital_contribution,
                'jumla' => $record->amount_to_pay,
                'malipo_ya_vipande' => $record->re_deposit,
                'loan' => $record->loan_repayment,
                'kiasi_baki' => $record->loan_repayment,
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
        $query = DB::table('payment_confirmations')->where('fia_investment', '>', 0);
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                  ->orWhere('member_name', 'like', "%{$search}%");
            });
        }

        $records = $query->orderBy('created_at', 'desc')->get();
        
        // Create CSV content
        $filename = 'payment_confirmations_' . date('Y-m-d_H-i-s') . '.csv';
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
                    $record->fia_investment,
                    $record->capital_contribution,
                    $record->amount_to_pay,
                    $record->re_deposit,
                    $record->loan_repayment,
                    $record->loan_repayment
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
            'total_records' => DB::table('payment_confirmations')->where('fia_investment', '>', 0)->count(),
            'total_gawio' => DB::table('payment_confirmations')->where('fia_investment', '>', 0)->sum('fia_investment'),
            'total_fia_komaa' => DB::table('payment_confirmations')->where('fia_investment', '>', 0)->sum('capital_contribution'),
            'total_jumla' => DB::table('payment_confirmations')->where('fia_investment', '>', 0)->sum('amount_to_pay'),
            'total_loan_balance' => DB::table('payment_confirmations')->where('fia_investment', '>', 0)->sum('loan_repayment'),
            'recent_records' => DB::table('payment_confirmations')
                ->where('fia_investment', '>', 0)
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
     * Bulk delete payment records
     */
    public function bulkDestroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $deleted = DB::table('payment_confirmations')
                ->whereIn('id', $request->ids)
                ->delete();

            if ($deleted > 0) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully deleted {$deleted} payment record(s)"
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No records found to delete'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting records: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a payment record
     */
    public function destroy($id)
    {
        try {
            $deleted = DB::table('payment_confirmations')->where('id', $id)->delete();
            
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
