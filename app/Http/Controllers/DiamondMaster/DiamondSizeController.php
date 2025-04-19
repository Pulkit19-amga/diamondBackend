<?php

namespace App\Http\Controllers\DiamondMaster;

use App\Models\DiamondSize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiamondSizeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sizes = DiamondSize::all();
            return response()->json($sizes);
        }
        return view('admin.DiamondMaster.Size.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'title' => 'nullable|string|max:250',
            'size1' => 'nullable|numeric',
            'size2' => 'nullable|numeric',
            'retailer_id' => 'nullable|integer',
            'status' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_updated' => 'nullable|date',
            'added_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
        ]);

        DiamondSize::create($data);

        return redirect()->route('sizes.index')
            ->with('success', 'Record added successfully.');
    }

    public function update(Request $request, $id)
    {
        $size = DiamondSize::findOrFail($id);

        $data = $request->validate([
            'title' => 'nullable|string|max:250',
            'size1' => 'nullable|numeric',
            'size2' => 'nullable|numeric',
            'retailer_id' => 'nullable|integer',
            'status' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_updated' => 'nullable|date',
            'added_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $size->update($data);

        return redirect()->route('sizes.index')
            ->with('success', 'Record updated successfully.');
    }

    public function destroy($id)
    {
        $size = DiamondSize::findOrFail($id);
        $size->delete();
        if (request()->ajax()) {
            return response()->json(['message' => 'Record deleted successfully.'], 200);
        }

        return redirect()->route('sizes.index')
        ->with('success', 'Record deleted successfully.');
    }

    public function show(DiamondSize $id)
    {
        if (request()->ajax()) {
            return response()->json($id);
        }
        return view('admin.DiamondMaster.Size.index', compact('id'));
    }
}
