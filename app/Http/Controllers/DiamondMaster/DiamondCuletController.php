<?php

namespace App\Http\Controllers\DiamondMaster;

use App\Models\DiamondCulet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiamondCuletController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $culet = DiamondCulet::all();
            return response()->json($culet);
        }
        return view('admin.DiamondMaster.culet.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dc_name' => 'nullable|string|max:250',
            'dc_short_name' => 'nullable|string|max:250',
            'dc_alise' => 'nullable|string',
            'dc_remark' => 'nullable|string',
            'dc_display_in_front' => 'nullable|integer',
            'dc_sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);
    
        DiamondCulet::create($data);
    
        return response()->json(['message' => 'Record added successfully.'], 200);
    }
    
    public function update(Request $request, $id)
    {
        $culet = DiamondCulet::findOrFail($id);
    
        $data = $request->validate([
            'dc_name' => 'nullable|string|max:250',
            'dc_short_name' => 'nullable|string|max:250',
            'dc_alise' => 'nullable|string',
            'dc_remark' => 'nullable|string',
            'dc_display_in_front' => 'nullable|integer',
            'dc_sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);
    
        $culet->update($data);
    
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }
    public function destroy($id)
    {
        $culet = DiamondCulet::findOrFail($id);
        $culet->delete();
        if (request()->ajax()) {
            return response()->json(['message' => 'Record deleted successfully.'], 200);
        }

        return redirect()->route('culet.index')
        ->with('success', 'Record deleted successfully.');
    }
    public function show(DiamondCulet $id)
    {
        if (request()->ajax()) {
            return response()->json($id);
        }
        return view('admin.DiamondMaster.culet.index', compact('id'));
    }
}
