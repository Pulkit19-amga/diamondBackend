<?php

namespace App\Http\Controllers\DiamondMaster;

use Illuminate\Http\Request;
use App\Models\DiamondFlourescence;
use App\Http\Controllers\Controller;

class DiamondFlourescenceController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $flourescence = DiamondFlourescence::all();
            return response()->json($flourescence);
        }
        return view('admin.DiamondMaster.flourescence.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:250',
            'alias' => 'nullable|string|max:250',
            'short_name' => 'nullable|string|max:150',
            'full_name' => 'nullable|string|max:250',
            'fluo_status' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);
    
        DiamondFlourescence::create($data);
    
        return response()->json(['message' => 'Record added successfully.'], 200);
    }
    

    public function update(Request $request, $id)
    {
        $flourescence = DiamondFlourescence::findOrFail($id);
    
        $data = $request->validate([
            'name' => 'nullable|string|max:250',
            'alias' => 'nullable|string|max:250',
            'short_name' => 'nullable|string|max:150',
            'full_name' => 'nullable|string|max:250',
            'fluo_status' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);
    
        $flourescence->update($data);
    
        return response()->json(['message' => 'Record updated successfully.'], 200);
    }
    

    public function destroy($id)
    {
        $flourescence = DiamondFlourescence::findOrFail($id);
        $flourescence->delete();
        if (request()->ajax()) {
            return response()->json(['message' => 'Record deleted successfully.'], 200);
        }

        return redirect()->route('flourescence.index')
        ->with('success', 'Record deleted successfully.');
    }

    public function show(DiamondFlourescence $id)
    {
        if (request()->ajax()) {
            return response()->json($id);
        }
        return view('admin.DiamondMaster.flourescence.index', compact('id'));
    }
}
