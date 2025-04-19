<?php

namespace App\Http\Controllers\DiamondMaster;

use Illuminate\Http\Request;
use App\Models\DiamondSymmetry;
use App\Http\Controllers\Controller;

class DiamondSymmetryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $symmetry = DiamondSymmetry::all();
            return response()->json($symmetry);
        }
        return view('admin.DiamondMaster.symmetry.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:250',
            'alias' => 'nullable|string|max:250',
            'short_name' => 'nullable|string|max:150',
            'full_name' => 'nullable|string|max:250',
            'sym_ststus' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);
    
        DiamondSymmetry::create($data);
    
        return response()->json(['message' => 'Record added successfully.'], 200);
    }
    

    public function update(Request $request, $id)
    {
        $symmetry = DiamondSymmetry::findOrFail($id);
    
        $data = $request->validate([
            'name' => 'nullable|string|max:250',
            'alias' => 'nullable|string|max:250',
            'short_name' => 'nullable|string|max:150',
            'full_name' => 'nullable|string|max:250',
            'sym_ststus' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);
    
        $symmetry->update($data);
    
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }
    

    public function destroy($id)
    {
        $symmetry = DiamondSymmetry::findOrFail($id);
        $symmetry->delete();
        if (request()->ajax()) {
            return response()->json(['message' => 'Record deleted successfully.'], 200);
        }

        return redirect()->route('symmetry.index')
        ->with('success', 'Record deleted successfully.');
    }

    public function show(DiamondSymmetry $id)
    {
        if (request()->ajax()) {
            return response()->json($id);
        }
        return view('admin.DiamondMaster.symmetry.index', compact('id'));
    }
}
