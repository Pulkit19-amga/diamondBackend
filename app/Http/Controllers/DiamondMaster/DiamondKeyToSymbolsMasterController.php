<?php

namespace App\Http\Controllers\DiamondMaster;

use App\Http\Controllers\Controller;
use App\Models\DiamondKeyToSymbols;
use Illuminate\Http\Request;

class DiamondKeyToSymbolsMasterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $records = DiamondKeyToSymbols::all();
            return response()->json($records);
        }
        return view('admin.DiamondMaster.KeyToSymbols.index');
    }

    public function create()
    {
        return view('admin.DiamondMaster.KeyToSymbols.create');
    }

    // Store new record
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'nullable|string',
            'alias'      => 'nullable|string',
            'short_name' => 'nullable|string',
            'sym_status' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify'=> 'nullable|date',
        ]);

        DiamondKeyToSymbols::create($data);

        return redirect()->route('keytosymbols.index')
                         ->with('success', 'Record added successfully.');
    }

    public function edit($id)
    {
        $record = DiamondKeyToSymbols::findOrFail($id);
        return view('admin.DiamondMaster.KeyToSymbols.index', compact('record'));
    }

    public function show(DiamondKeyToSymbols $id)
    {
        if (request()->ajax()) {
            return response()->json($id);
        }
        $record = $id;
        return view('admin.DiamondMaster.KeyToSymbols.index', compact('record'));
    }

    // Update operation
    public function update(Request $request, $id)
    {
        $record = DiamondKeyToSymbols::findOrFail($id);

        $data = $request->validate([
            'name'       => 'nullable|string',
            'alias'      => 'nullable|string',
            'short_name' => 'nullable|string',
            'sym_status' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'date_added' => 'nullable|date',
            'date_modify'=> 'nullable|date',
        ]);

        $record->update($data);

        return redirect()->route('keytosymbols.index')
                         ->with('success', 'Record updated successfully.');
    }

    // Delete operation
    public function destroy($id)
    {
        $record = DiamondKeyToSymbols::findOrFail($id);
        $record->delete();

        return redirect()->route('keytosymbols.index')
                         ->with('success', 'Record deleted successfully.');
    }
}
