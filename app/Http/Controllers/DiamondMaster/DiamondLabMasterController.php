<?php

namespace App\Http\Controllers\DiamondMaster;

use App\Http\Controllers\Controller;
use App\Models\DiamondLab;
use Illuminate\Http\Request;

class DiamondLabMasterController extends Controller
{
    // List all records: For AJAX (JSON) or normal view
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $labs = DiamondLab::all();
            return response()->json($labs);
        }
        return view('admin.DiamondMaster.Lab.index');
    }

    // Create: (Agar alag create view chahiye; yahan hum AJAX modal approach use kar rahe hain, isliye ye optional)
    public function create()
    {
        return view('admin.DiamondMaster.Lab.create');
    }

    // Store new record
    public function store(Request $request)
    {
        $data = $request->validate([
            'dl_name'              => 'nullable|string|max:250',
            'dl_display_in_front'  => 'nullable|integer',
            'dl_sort_order'        => 'nullable|integer',
            'image'                => 'required|string|max:255', // image field required
            'cert_url'             => 'nullable|string|max:255',
            'date_added'           => 'nullable|date',
            'date_modify'          => 'nullable|date',
        ]);

        DiamondLab::create($data);

        return redirect()->route('diamondlab.index')
                         ->with('success', 'Record added successfully.');
    }

    // Edit form: Return view (here same index view is used for modal editing)
    public function edit($id)
    {
        $lab = DiamondLab::findOrFail($id);
        return view('admin.DiamondMaster.Lab.index', compact('lab'));
    }

    // Show: For AJAX request to get record details
    public function show(DiamondLab $id)
    {
        if (request()->ajax()) {
            return response()->json($id);
        }
        $lab = $id;
        return view('admin.DiamondMaster.Lab.index', compact('lab'));
    }

    // Update record
    public function update(Request $request, $id)
    {
        $lab = DiamondLab::findOrFail($id);
    
        // If only sort/display are being updated (like in inline AJAX), relax validation
        if ($request->has('dl_sort_order') || $request->has('dl_display_in_front')) {
            $data = $request->validate([
                'dl_sort_order'       => 'nullable|integer',
                'dl_display_in_front' => 'nullable|integer',
            ]);
        } else {
            // Full form validation
            $data = $request->validate([
                'dl_name'              => 'nullable|string|max:250',
                'dl_display_in_front'  => 'nullable|integer',
                'dl_sort_order'        => 'nullable|integer',
                'image'                => 'required|string|max:255',
                'cert_url'             => 'nullable|string|max:255',
                'date_added'           => 'nullable|date',
                'date_modify'          => 'nullable|date',
            ]);
        }
    
        $lab->update($data);
    
        return response()->json(['success' => true, 'message' => 'Record updated successfully.']);
    }
    

    // Delete record
    public function destroy($id)
    {
        $lab = DiamondLab::findOrFail($id);
        $lab->delete();

        return redirect()->route('diamondlab.index')
                         ->with('success', 'Record deleted successfully.');
    }
}
