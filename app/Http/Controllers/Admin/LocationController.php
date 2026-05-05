<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function index()
    {
        $locations = DB::table('tanzania_locations')
            ->orderBy('region')
            ->orderBy('district')
            ->orderBy('ward')
            ->paginate(50);

        return view('admin.settings.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.settings.locations.create');
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
}
