<?php

namespace App\Http\Controllers\DiamondMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiamondPolish;

class DiamondPolishMasterController extends Controller
{
    // List all records: For AJAX or normal view
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $polishes = DiamondPolish::all();
            return response()->json($polishes);
        }

        return view('admin.DiamondMaster.Polish.index');
    }

    // Create (optional if using AJAX modals)
    public function create()
    {
        return view('admin.DiamondMaster.Polish.create');
    }

    // Store new record
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:250',
            'alias'       => 'nullable|string|max:250',
            'short_name'  => 'nullable|string|max:150',
            'full_name'   => 'nullable|string|max:250',
            'pol_status'  => 'nullable|integer',
            'sort_order'  => 'nullable|integer',
            'date_added'  => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);

        DiamondPolish::create($data);

        return redirect()->route('diamondpolish.index')
                         ->with('success', 'Polish added successfully.');
    }

    // Edit: for modal or direct view
    public function edit($id)
    {
        $polish = DiamondPolish::findOrFail($id);
        return view('admin.DiamondMaster.Polish.index', compact('polish'));
    }

    // Show for AJAX detail
    public function show(DiamondPolish $id)
    {
        if (request()->ajax()) {
            return response()->json($id);
        }

        $polish = $id;
        return view('admin.DiamondMaster.Polish.index', compact('polish'));
    }

    // Update existing record
    public function update(Request $request, $id)
    {
        $polish = DiamondPolish::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:250',
            'alias'       => 'nullable|string|max:250',
            'short_name'  => 'nullable|string|max:150',
            'full_name'   => 'nullable|string|max:250',
            'pol_status'  => 'nullable|integer',
            'sort_order'  => 'nullable|integer',
            'date_added'  => 'nullable|date',
            'date_modify' => 'nullable|date',
        ]);

        $polish->update($data);

        return redirect()->route('diamondpolish.index')
                         ->with('success', 'Polish updated successfully.');
    }

    // Delete record
    public function destroy($id)
    {
        $polish = DiamondPolish::findOrFail($id);
        $polish->delete();

        return redirect()->route('diamondpolish.index')
                         ->with('success', 'Polish deleted successfully.');
    }
}
