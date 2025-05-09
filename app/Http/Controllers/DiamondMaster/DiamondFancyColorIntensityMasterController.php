<?php

namespace App\Http\Controllers\DiamondMaster;

use App\Http\Controllers\Controller;
use App\Models\DiamondFancyColorIntensity;
use Illuminate\Http\Request;

class DiamondFancyColorIntensityMasterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DiamondFancyColorIntensity::all();
            return response()->json(['data' => $data]);
        }
        return view('admin.DiamondMaster.FancyColorIntensity.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fci_name' => 'nullable|string|max:250',
            'fci_short_name' => 'nullable|string|max:250',
            'fci_alias' => 'nullable|string',
            'fci_remark' => 'nullable|string',
            'fci_display_in_front' => 'nullable|boolean',
            'fci_sort_order' => 'nullable|integer',
        ]);
        $validated['date_added'] = now();
        DiamondFancyColorIntensity::create($validated);
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'fci_name' => 'nullable|string|max:250',
            'fci_short_name' => 'nullable|string|max:250',
            'fci_alias' => 'nullable|string',
            'fci_remark' => 'nullable|string',
            'fci_display_in_front' => 'nullable|boolean',
            'fci_sort_order' => 'nullable|integer',
        ]);
        $validated['date_modify'] = now();
        $intensity = DiamondFancyColorIntensity::findOrFail($id);
        $intensity->update($validated);
        return response()->json(['success' => true]);
    }

    public function show($id)
{
    $data = DiamondFancyColorIntensity::findOrFail($id);
    return response()->json($data);
}

    public function destroy($id)
    {
        DiamondFancyColorIntensity::findOrFail($id)->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        }
        return redirect()->route('fancy-color-intensity.index')
        ->with('success', 'Record deleted successfully.');
    }
}