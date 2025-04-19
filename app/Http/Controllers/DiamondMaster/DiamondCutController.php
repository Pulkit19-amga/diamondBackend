<?php

namespace App\Http\Controllers\DiamondMaster;

use App\Models\DiamondCut;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiamondCutController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $cut = DiamondCut::all();
            return response()->json($cut);
        }
        return view('admin.DiamondMaster.cut.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:250',
            'shortname' => 'nullable|string|max:250',
            'full_name' => 'nullable|string|max:250',
            'ALIAS' => 'nullable|string',
            'remark' => 'nullable|string',
            'display_in_front' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);

        DiamondCut::create($data);

        return response()->json(['message' => 'Record added successfully.'], 200);
    }

    public function update(Request $request, $id)
    {
        $cut = DiamondCut::findOrFail($id);

        $data = $request->validate([
            'name' => 'nullable|string|max:250',
            'shortname' => 'nullable|string|max:250',
            'full_name' => 'nullable|string|max:250',
            'ALIAS' => 'nullable|string',
            'remark' => 'nullable|string',
            'display_in_front' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);

        $cut->update($data);

        return response()->json(['message' => 'Record updated successfully.'], 200);
    }
    public function destroy($id)
    {
        $cut = DiamondCut::findOrFail($id);
        $cut->delete();
        if (request()->ajax()) {
            return response()->json(['message' => 'Record deleted successfully.'], 200);
        }

        return redirect()->route('cut.index')
            ->with('success', 'Record deleted successfully.');
    }
    public function show(DiamondCut $id)
    {
        if (request()->ajax()) {
            return response()->json($id);
        }
        return view('admin.DiamondMaster.cut.index', compact('id'));
    }
}
