<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 50);
        $search = $request->get('search', '');
        $regionFilter = $request->get('region', '');
        $districtFilter = $request->get('district', '');

        // Start building the query
        $query = DB::table('tanzania_locations');

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('region', 'like', '%' . $search . '%')
                  ->orWhere('district', 'like', '%' . $search . '%')
                  ->orWhere('ward', 'like', '%' . $search . '%')
                  ->orWhere('street', 'like', '%' . $search . '%')
                  ->orWhere('places', 'like', '%' . $search . '%');
            });
        }

        // Apply region filter
        if (!empty($regionFilter)) {
            $query->where('region', $regionFilter);
        }

        // Apply district filter
        if (!empty($districtFilter)) {
            $query->where('district', $districtFilter);
        }

        // Get locations with pagination
        $locations = $query->orderBy('region')
                        ->orderBy('district')
                        ->orderBy('ward')
                        ->paginate($perPage);

        // Calculate stats for the dashboard
        $stats = [
            'total' => DB::table('tanzania_locations')->count(),
            'regions' => DB::table('tanzania_locations')->distinct('region')->count('region'),
            'districts' => DB::table('tanzania_locations')->distinct('district')->count('district'),
            'wards' => DB::table('tanzania_locations')->distinct('ward')->count('ward'),
        ];

        return view('admin.settings.locations.index', compact('locations', 'stats'));
    }

    public function create()
    {
        return view('admin.settings.locations.create');
    }

    public function importPage()
    {
        return view('admin.settings.locations.import');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'region' => 'required|string|max:100',
            'regioncode' => 'required|string|max:5|unique:tanzania_locations,regioncode',
            'district' => 'required|string|max:100',
            'districtcode' => 'required|string|max:5|unique:tanzania_locations,districtcode',
            'ward' => 'required|string|max:100',
            'wardcode' => 'required|string|max:5|unique:tanzania_locations,wardcode',
            'street' => 'required|string|max:255',
            'places' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('tanzania_locations')->insert([
            'region' => $request->region,
            'regioncode' => strtoupper($request->regioncode),
            'district' => $request->district,
            'districtcode' => strtoupper($request->districtcode),
            'ward' => $request->ward,
            'wardcode' => strtoupper($request->wardcode),
            'street' => $request->street,
            'places' => $request->places,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location created successfully!');
    }

    public function edit($id)
    {
        $location = DB::table('tanzania_locations')->find($id);
        
        if (!$location) {
            return redirect()->route('admin.locations.index')
                ->with('error', 'Location not found!');
        }

        return view('admin.settings.locations.edit', compact('location'));
    }

    public function update(Request $request, $id)
    {
        $location = DB::table('tanzania_locations')->find($id);
        
        if (!$location) {
            return redirect()->route('admin.locations.index')
                ->with('error', 'Location not found!');
        }

        $validator = Validator::make($request->all(), [
            'region' => 'required|string|max:100',
            'regioncode' => 'required|string|max:5|unique:tanzania_locations,regioncode,' . $id,
            'district' => 'required|string|max:100',
            'districtcode' => 'required|string|max:5|unique:tanzania_locations,districtcode,' . $id,
            'ward' => 'required|string|max:100',
            'wardcode' => 'required|string|max:5|unique:tanzania_locations,wardcode,' . $id,
            'street' => 'required|string|max:255',
            'places' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('tanzania_locations')
            ->where('id', $id)
            ->update([
                'region' => $request->region,
                'regioncode' => strtoupper($request->regioncode),
                'district' => $request->district,
                'districtcode' => strtoupper($request->districtcode),
                'ward' => $request->ward,
                'wardcode' => strtoupper($request->wardcode),
                'street' => $request->street,
                'places' => $request->places,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location updated successfully!');
    }

    public function destroy($id)
    {
        $location = DB::table('tanzania_locations')->find($id);
        
        if (!$location) {
            return redirect()->route('admin.locations.index')
                ->with('error', 'Location not found!');
        }

        DB::table('tanzania_locations')->where('id', $id)->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $locations = DB::table('tanzania_locations')
            ->where('region', 'LIKE', "%{$query}%")
            ->orWhere('district', 'LIKE', "%{$query}%")
            ->orWhere('ward', 'LIKE', "%{$query}%")
            ->orWhere('street', 'LIKE', "%{$query}%")
            ->orWhere('places', 'LIKE', "%{$query}%")
            ->orderBy('region')
            ->orderBy('district')
            ->orderBy('ward')
            ->limit(20)
            ->get();

        return response()->json($locations);
    }

    public function export()
    {
        $locations = DB::table('tanzania_locations')
            ->orderBy('region')
            ->orderBy('district')
            ->orderBy('ward')
            ->get();

        $csvContent = "region,regioncode,district,districtcode,ward,wardcode,street,places\n";
        
        foreach ($locations as $location) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,\"%s\",\"%s\"\n",
                $location->region,
                $location->regioncode,
                $location->district,
                $location->districtcode,
                $location->ward,
                $location->wardcode,
                str_replace('"', '""', $location->street),
                str_replace('"', '""', $location->places)
            );
        }

        $filename = 'tanzania_locations_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_files' => 'required|array|min:1',
            'csv_files.*' => 'file|mimes:csv,txt|max:10240', // Max 10MB per file
        ]);

        try {
            $files = $request->file('csv_files');
            $totalImported = 0;
            $totalSkipped = 0;
            $totalErrors = [];
            $processedFiles = 0;

            Log::info('CSV import started', [
                'files_count' => count($files),
                'user_id' => auth()->id(),
            ]);

            foreach ($files as $fileIndex => $file) {
                $filePath = $file->getRealPath();
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                
                Log::info('Processing CSV file', [
                    'file_index' => $fileIndex,
                    'file_name' => $fileName,
                    'file_size' => $fileSize,
                    'file_path' => $filePath,
                ]);
                
                // Open and read the CSV file
                $csvData = [];
                $header = null;
                
                if (($handle = fopen($filePath, 'r')) !== false) {
                    Log::info('CSV file opened successfully', [
                        'file_name' => $fileName,
                        'handle' => 'opened',
                    ]);
                    
                    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                        if (!$header) {
                            $header = $row;
                            Log::info('CSV header found', [
                                'file_name' => $fileName,
                                'header' => $header,
                            ]);
                            
                            // Clean header values and validate required columns
                            $cleanHeader = array_map(function($value) {
                                return trim(str_replace(["\xEF\xBB\xBF", "\0"], '', $value));
                            }, $header);
                            
                            // Convert both arrays to lowercase for case-insensitive comparison
                            $cleanHeaderLower = array_map('strtolower', $cleanHeader);
                            
                            // Handle duplicate column names and alternative column names
                            $columnMapping = [];
                            $columnCounts = array_count_values($cleanHeaderLower);
                            
                            // Map columns considering duplicates and alternative names
                            foreach ($cleanHeaderLower as $index => $columnName) {
                                $baseColumn = $columnName;
                                $suffix = '';
                                
                                // Handle duplicate POSTCODE columns and alternative mapping
                                if ($columnName === 'postcode') {
                                    if ($columnCounts['postcode'] > 1) {
                                        // Find which POSTCODE this is (first, second, or third)
                                        $postcodeIndex = 0;
                                        for ($i = 0; $i < $index; $i++) {
                                            if ($cleanHeaderLower[$i] === 'postcode') {
                                                $postcodeIndex++;
                                            }
                                        }
                                        
                                        // Map POSTCODE columns based on position
                                        if ($postcodeIndex === 1) {
                                            $baseColumn = 'regioncode'; // First POSTCODE
                                            $suffix = ' (1st)';
                                        } elseif ($postcodeIndex === 2) {
                                            $baseColumn = 'districtcode'; // Second POSTCODE
                                            $suffix = ' (2nd)';
                                        } else {
                                            $baseColumn = 'wardcode'; // Third POSTCODE
                                            $suffix = ' (3rd)';
                                        }
                                    }
                                }
                                
                                // Handle alternative column name variations
                                if ($columnName === 'regioncode' && !in_array('regioncode', $cleanHeaderLower)) {
                                    // If regioncode is missing but POSTCODE exists, map first POSTCODE to regioncode
                                    $baseColumn = 'postcode';
                                    $suffix = ' (mapped to regioncode)';
                                }
                                
                                $columnMapping[$baseColumn] = $columnName;
                            }
                            
                            // Check for required columns with flexible mapping
                            $requiredColumns = ['region', 'regioncode', 'district', 'districtcode', 'ward', 'wardcode', 'street', 'places'];
                            $missingColumns = [];
                            
                            foreach ($requiredColumns as $col) {
                                if (!isset($columnMapping[$col])) {
                                    $missingColumns[] = $col;
                                }
                            }
                            
                            if (!empty($missingColumns)) {
                                Log::error('Required column missing', [
                                    'file_name' => $fileName,
                                    'missing_columns' => $missingColumns,
                                    'found_columns' => $cleanHeaderLower,
                                    'clean_header' => $cleanHeader,
                                    'column_mapping' => $columnMapping,
                                ]);
                                $totalErrors[] = "File '{$fileName}': CSV must contain column: " . implode(', ', $missingColumns) . ". Found columns: " . implode(', ', $cleanHeader);
                                continue 2; // Skip to next file
                            }
                        } else {
                            // Clean row data and combine with cleaned header
                            $cleanRow = array_map(function($value) {
                                return trim(str_replace(["\xEF\xBB\xBF", "\0"], '', $value));
                            }, $row);
                            $csvData[] = array_combine($header, $cleanRow);
                        }
                    }
                    fclose($handle);
                    
                    Log::info('CSV file processed', [
                        'file_name' => $fileName,
                        'total_rows' => count($csvData),
                        'header' => $header,
                    ]);
                } else {
                    Log::error('Failed to open CSV file', [
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'error' => 'fopen returned false',
                    ]);
                    $totalErrors[] = "File '{$fileName}': Could not open file for reading";
                    continue;
                }

                if (empty($csvData)) {
                    $totalErrors[] = "File '{$fileName}': CSV file is empty or contains no valid data.";
                    continue;
                }

                // Process and insert data for this file
                $fileImported = 0;
                $fileSkipped = 0;
                $fileErrors = [];

                Log::info('Starting data processing for file', [
                    'file_name' => $fileName,
                    'total_csv_rows' => count($csvData),
                ]);

                foreach ($csvData as $index => $row) {
                    try {
                        Log::info('Processing CSV row', [
                            'file_name' => $fileName,
                            'row_index' => $index + 2, // +2 because header is row 1
                            'raw_row' => $row,
                        ]);
                        
                        // Create flexible case-insensitive row access with column mapping
                        $rowLower = array_change_key_case('strtolower', $row);
                        
                        // Helper function to get value from row with column mapping
                        $getValue = function($key, $default = '') use ($rowLower, $columnMapping) {
                            // Check if column exists in mapping (for POSTCODE handling)
                            if (isset($columnMapping[$key])) {
                                $actualColumn = $columnMapping[$key];
                                return trim(str_replace(["\xEF\xBB\xBF", "\0"], '', $rowLower[$actualColumn] ?? $default));
                            }
                            return trim(str_replace(["\xEF\xBB\xBF", "\0"], '', $rowLower[$key] ?? $default));
                        };
                        
                        // Clean and validate required fields
                        $region = $getValue('region');
                        $district = $getValue('district');
                        $ward = $getValue('ward');
                        $regioncode = $getValue('regioncode');
                        $districtcode = $getValue('districtcode');
                        $wardcode = $getValue('wardcode');
                        $street = $getValue('street');
                        $places = $getValue('places');
                        
                        Log::info('Validating required fields', [
                            'file_name' => $fileName,
                            'row_index' => $index + 2,
                            'region' => $region,
                            'district' => $district,
                            'ward' => $ward,
                            'row_keys' => array_keys($rowLower),
                        ]);
                        
                        if (empty($region) || empty($district) || empty($ward)) {
                            Log::warning('Missing required fields', [
                                'file_name' => $fileName,
                                'row_index' => $index + 2,
                                'error' => 'Region, District, and Ward are required',
                                'available_keys' => array_keys($rowLower),
                            ]);
                            $fileErrors[] = "File '{$fileName}', Row " . ($index + 2) . ": Region, District, and Ward are required";
                            $fileSkipped++;
                            continue;
                        }

                        // Check for duplicates
                        $exists = DB::table('tanzania_locations')
                            ->where('region', $region)
                            ->where('district', $district)
                            ->where('ward', $ward)
                            ->exists();

                        if ($exists) {
                            Log::warning('Duplicate location found', [
                                'file_name' => $fileName,
                                'row_index' => $index + 2,
                                'region' => $region,
                                'district' => $district,
                                'ward' => $ward,
                            ]);
                            $fileErrors[] = "File '{$fileName}', Row " . ($index + 2) . ": Location already exists";
                            $fileSkipped++;
                            continue;
                        }

                        Log::info('Inserting new location', [
                            'file_name' => $fileName,
                            'row_index' => $index + 2,
                            'region' => $region,
                            'district' => $district,
                            'ward' => $ward,
                        ]);

                        // Insert new location
                        DB::table('tanzania_locations')->insert([
                            'region' => $region,
                            'regioncode' => strtoupper($regioncode),
                            'district' => $district,
                            'districtcode' => strtoupper($districtcode),
                            'ward' => $ward,
                            'wardcode' => strtoupper($wardcode),
                            'street' => $street,
                            'places' => $places,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $fileImported++;
                        Log::info('Successfully inserted location', [
                            'file_name' => $fileName,
                            'row_index' => $index + 2,
                            'region' => $region,
                            'district' => $district,
                            'ward' => $ward,
                        ]);
                        
                    } catch (\Exception $e) {
                        Log::error('Error processing CSV row', [
                            'file_name' => $fileName,
                            'row_index' => $index + 2,
                            'error' => $e->getMessage(),
                            'row_data' => $row,
                        ]);
                        $fileErrors[] = "File '{$fileName}', Row " . ($index + 2) . ": " . $e->getMessage();
                        $fileSkipped++;
                    }
                }

                // Add file results to totals
                $totalImported += $fileImported;
                $totalSkipped += $fileSkipped;
                $totalErrors = array_merge($totalErrors, $fileErrors);
                $processedFiles++;

                Log::info("CSV file processed: {$fileName}", [
                    'imported' => $fileImported,
                    'skipped' => $fileSkipped,
                    'errors' => count($fileErrors),
                    'file_errors' => $fileErrors,
                    'user_id' => auth()->id(),
                ]);
            }

            // Build success message
            $message = "Import completed successfully! ";
            $message .= "Processed {$processedFiles} file(s). ";
            $message .= "{$totalImported} locations imported, {$totalSkipped} skipped.";
            
            if (!empty($totalErrors)) {
                $message .= " Errors: " . implode('; ', array_slice($totalErrors, 0, 3));
                if (count($totalErrors) > 3) {
                    $message .= " ... and " . (count($totalErrors) - 3) . " more errors.";
                }
            }

            Log::info('Multiple CSV import completed', [
                'files_processed' => $processedFiles,
                'total_imported' => $totalImported,
                'total_skipped' => $totalSkipped,
                'total_errors' => count($totalErrors),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Multiple CSV import failed: ' . $e->getMessage(), [
                'files_count' => count($request->file('csv_files')),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:tanzania_locations,id'
        ]);

        try {
            $ids = $request->input('ids');
            $deletedCount = DB::table('tanzania_locations')->whereIn('id', $ids)->delete();

            Log::info('Bulk delete completed', [
                'deleted_count' => $deletedCount,
                'ids' => $ids,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()->with('success', "Successfully deleted {$deletedCount} location(s).");

        } catch (\Exception $e) {
            Log::error('Bulk delete failed: ' . $e->getMessage(), [
                'ids' => $request->input('ids'),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()->with('error', 'Failed to delete selected locations: ' . $e->getMessage());
        }
    }
}
