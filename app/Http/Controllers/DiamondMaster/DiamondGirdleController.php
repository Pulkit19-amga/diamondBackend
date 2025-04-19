<?php

namespace App\Http\Controllers\DiamondMaster;

use Illuminate\Http\Request;
use App\Models\DiamondGirdle;
use App\Http\Controllers\Controller;

class DiamondGirdleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $girdle = DiamondGirdle::all();
            return response()->json($girdle);
        }
        return view('admin.DiamondMaster.girdle.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dg_name' => 'nullable|string|max:250',
            'dg_alise' => 'nullable|string',
            'dg_short_name' => 'nullable|string|max:250',
            'dg_remark' => 'nullable|string',
            'dg_display_in_front' => 'nullable|integer',
            'dg_sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);
    
        DiamondGirdle::create($data);
    
        return response()->json(['message' => 'Record added successfully.'], 200);
    }
    
    public function update(Request $request, $id)
    {
        $girdle = DiamondGirdle::findOrFail($id);
    
        $data = $request->validate([
            'dg_name' => 'nullable|string|max:250',
            'dg_alise' => 'nullable|string',
            'dg_short_name' => 'nullable|string|max:250',
            'dg_remark' => 'nullable|string',
            'dg_display_in_front' => 'nullable|integer',
            'dg_sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);
    
        $girdle->update($data);
    
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }
    public function destroy($id)
    {
        $girdle = DiamondGirdle::findOrFail($id);
        $girdle->delete();
        if (request()->ajax()) {
            return response()->json(['message' => 'Record deleted successfully.'], 200);
        }

        return redirect()->route('girdle.index')
        ->with('success', 'Record deleted successfully.');
    }
    public function show(DiamondGirdle $id)
    {
        if (request()->ajax()) {
            return response()->json($id);
        }
        return view('admin.DiamondMaster.girdle.index', compact('id'));
    }
}
