<?php

namespace App\Http\Controllers\DiamondMaster;

use DataTables;
use Illuminate\Http\Request;
use App\Models\DiamondMaster;
use App\Models\DiamondVendor;
use App\Http\Controllers\Controller;

class DiamondMasterController extends Controller
{
    public function index()
    {
        $data = [
            'vendors'       => DiamondVendor::pluck('vendorid'),
            'shapes'        => \App\Models\DiamondShape::pluck('name', 'id'),
            'colors'        => \App\Models\DiamondColor::pluck('name', 'id'),
            'clarities'     => \App\Models\DiamondClarityMaster::pluck('name', 'id'),
            'cuts'          => \App\Models\DiamondCut::pluck('name', 'id'),
            'polishes'      => \App\Models\DiamondPolish::pluck('name', 'id'),
            'symmetries'    => \App\Models\DiamondSymmetry::pluck('name', 'id'),
            'fluorescences' => \App\Models\DiamondFlourescence::pluck('name', 'id'),
            'diamonds'      => DiamondMaster::with(['vendor', 'shape', 'color', 'clarity'])->get()
        ];
    
        return view('admin.DiamondMaster.master.index', $data);
    }
    
    

    public function data(Request $request)
    {
        $query = DiamondMaster::with([
            'vendor', 'shape', 'color', 'clarity'
        ]);

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'diamond_type' => 'required|in:1,2',
            'quantity' => 'required|integer|min:1',
            'vendor_id' => 'required|exists:vendor_master,vendorid',
            'shape' => 'required|integer',
            'color' => 'required|integer',
            'clarity' => 'required|integer',
            'cut' => 'nullable|integer',
            'carat_weight' => 'required|numeric',
            'price_per_carat' => 'nullable|numeric',
            'vendor_price' => 'nullable|numeric',
            'certificate_number' => 'nullable|string',
            'certificate_date' => 'nullable|date',
            'polish' => 'nullable|integer',
            'symmetry' => 'nullable|integer',
            'fluorescence' => 'nullable|integer',
            'availability' => 'nullable|in:0,1,2',
            'is_superdeal' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'date_added' => 'nullable|date',
            'date_updated' => 'nullable|date',
        ]);

        DiamondMaster::create($validated);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $diamond = DiamondMaster::findOrFail($id);
        return response()->json($diamond);
    }

    public function update(Request $request, $id)
    {
        $diamond = DiamondMaster::findOrFail($id);

        $validated = $request->validate([
            'diamond_type' => 'required|in:1,2',
            'quantity' => 'required|integer|min:1',
            'vendor_id' => 'required|exists:vendor_master,vendorid',
            'shape' => 'required|integer',
            'color' => 'required|integer',
            'clarity' => 'required|integer',
            'cut' => 'nullable|integer',
            'carat_weight' => 'required|numeric',
            'price_per_carat' => 'nullable|numeric',
            'vendor_price' => 'nullable|numeric',
            'certificate_number' => 'nullable|string',
            'certificate_date' => 'nullable|date',
            'polish' => 'nullable|integer',
            'symmetry' => 'nullable|integer',
            'fluorescence' => 'nullable|integer',
            'availability' => 'nullable|in:0,1,2',
            'is_superdeal' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'date_added' => 'nullable|date',
            'date_updated' => 'nullable|date',
        ]);

        $diamond->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $diamond = DiamondMaster::findOrFail($id);
        $diamond->delete();
        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
    {
        $diamond = DiamondMaster::findOrFail($id);
        $diamond->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }
}
