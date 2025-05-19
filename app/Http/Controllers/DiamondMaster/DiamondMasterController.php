<?php

namespace App\Http\Controllers\DiamondMaster;

use DataTables;
use App\Models\DiamondLab;
use Illuminate\Http\Request;
use App\Models\DiamondMaster;
use App\Models\DiamondVendor;
use App\Models\DiamondLabMaster;
use App\Http\Controllers\Controller;

class DiamondMasterController extends Controller
{
    public function index()
    {
        $data = [
            'vendors' => DiamondVendor::pluck('vendorid'),
            'shapes' => \App\Models\DiamondShape::pluck('name', 'id'),
            'colors' => \App\Models\DiamondColor::pluck('name', 'id'),
            'clarities' => \App\Models\DiamondClarityMaster::pluck('name', 'id'),
            'cuts' => \App\Models\DiamondCut::pluck('name', 'id'),
            'polishes' => \App\Models\DiamondPolish::pluck('name', 'id'),
            'symmetries' => \App\Models\DiamondSymmetry::pluck('name', 'id'),
            'fluorescences' => \App\Models\DiamondFlourescence::pluck('name', 'id'),
            'certificate_companies' => DiamondLab::pluck('dl_name', 'dl_id'),
            'diamonds' => DiamondMaster::with(['vendor', 'shape', 'color', 'clarity', 'certificateCompany'])->get()
        ];

        return view('admin.DiamondMaster.master.index', $data);
    }


    public function data(Request $request)
    {
       
        $query = DiamondMaster::with([
            'vendor', 'shape', 'color', 'cut', 'clarity',
            'certificateCompany', 'polish', 'symmetry', 'fluorescence'
        ]);

        if ($request->filled('active_tab')) {
            $query->where('diamond_type', (int) $request->get('active_tab'));
        }

        if ($request->filled('selectedReport')) {
            $report = (int) $request->get('selectedReport');

            if ($report !== 0) {
                $query->where('certificate_company', $report);
            }
        }

        if ($request->filled('price')) {
            $price = $request->get('price'); // expects [min, max]
            $query->whereBetween('price', $price);
        }

        if ($request->has('carat') && is_array($request->carat) && count($request->carat) === 2) {
            $carat = array_map('floatval', $request->carat); // expects [min, max]
            $query->whereBetween('carat_weight', $carat);
        }

        if (
            $request->has('cut') &&
            is_array($request->cut) &&
            count($request->cut) === 2 &&
            is_numeric($request->cut[0]) &&
            is_numeric($request->cut[1])
        ) {
            $start = (int) $request->cut[0];
            $end = (int) $request->cut[1];

            // Reduce upper limit by 1, but ensure it doesn't go below start
            $end = max($end - 1, $start);

            $query->whereBetween('cut', [$start, $end]);
        }


        if ($request->has('color') && is_array($request->color)) {
            $query->whereBetween('color', array_map('intval', $request->color));
        }

        if ($request->has('clarity') && is_array($request->clarity) && count($request->clarity) === 2) {
            $clarity = array_map('intval', $request->clarity);
            $query->whereBetween('clarity', $clarity);
        }

        if ($request->filled('shapes')) {
            $query->whereIn('shape', $request->get('shapes'));
        }

        if ($request->filled('certificate')) {
            $query->where('certificate_number', 'like', '%' . $request->get('certificate') . '%');
        }

        if (
            $request->has('polish') &&
            is_array($request->polish) &&
            count($request->polish) === 2 &&
            is_numeric($request->polish[0]) &&
            is_numeric($request->polish[1])
        ) {
            $start = (int) $request->polish[0];
            $end = (int) $request->polish[1];

            // Reduce last value by 1 but don't go below the start value
            $end = max($end - 1, $start);

            $query->whereBetween('polish', [$start, $end]);
        }

        if (
            $request->has('symmetry') &&
            is_array($request->symmetry) &&
            count($request->symmetry) === 2 &&
            is_numeric($request->symmetry[0]) &&
            is_numeric($request->symmetry[1])
        ) {
            $start = (int) $request->symmetry[0];
            $end = (int) $request->symmetry[1];

            // Reduce last value by 1 but don't go below the start value
            $end = max($end - 1, $start);

            $query->whereBetween('symmetry', [$start, $end]);
        }
        
        if ($request->has('fluorescence') && is_array($request->fluorescence)) {
            $fluorescence = $request->fluorescence;
            // Reduce the last value by 1 dynamically
            $fluorescence[1] = max($fluorescence[1] - 1, $fluorescence[0]); 
            // Ensure the last value doesn't go below the first value
            $query->whereBetween('fluorescence', $fluorescence);
        }

        if (
            $request->has('ratio') &&
            is_array($request->ratio) &&
            count($request->ratio) === 2 &&
            is_numeric($request->ratio[0]) &&
            is_numeric($request->ratio[1])
        ) {
            $start = (float) $request->ratio[0];
            $end = (float) $request->ratio[1];

            $query->whereNotNull('measurement_l')
                ->whereNotNull('measurement_w')
                ->where('measurement_w', '>', 0)
                ->whereRaw('(measurement_l / measurement_w) BETWEEN ? AND ?', [$start, $end]);
        }

        if (
            $request->has('table') &&
            is_array($request->table) &&
            count($request->table) === 2 &&
            is_numeric($request->table[0]) &&
            is_numeric($request->table[1])
        ) {
            $start = (int) $request->table[0];
            $end = (int) $request->table[1];

            $query->whereBetween('table_diamond', [$start, $end]);
        }

        if (
            $request->has('depth') &&
            is_array($request->depth) &&
            count($request->depth) === 2 &&
            is_numeric($request->depth[0]) &&
            is_numeric($request->depth[1])
        ) {
            $start = (int) $request->depth[0];
            $end = (int) $request->depth[1];

            $query->whereBetween('depth', [$start, $end]);
        }


        if ($request->boolean('featured')) {
            $query->where('is_superdeal', 1);
        }

        if ($request->boolean('showOnlyChecked')) {
            $checked = $request->checkedDiamonds ?? [];

            if (is_array($checked) && count($checked) > 0) {
                $query->whereIn('diamondid', $checked);
            } else {
                // If showOnlyChecked is true but no IDs, return no results
                $query->whereRaw('0 = 1'); // always false condition
            }
        }

        $perPage = $request->get('per_page', 20);
        $diamonds = $query->paginate($perPage);

        return response()->json($diamonds);
        // return response()->json([
        //     'request_data' => $request->all(),
        //     'diamonds' => $query->paginate($perPage),
        // ]);
    }


    public function store(Request $request)
    {
        $validated = $this->validateDiamondMaster($request);

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
        $validated = $this->validateDiamondMaster($request);
        $diamond = DiamondMaster::findOrFail($id);
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
        $diamond->status = $request->status;
        $diamond->save();
        return response()->json(['success' => true]);
    }
    
    private function validateDiamondMaster(Request $request, $isUpdate = false)
    {
        // Define validation rules
        $rules = [
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
            'certificate_company' => 'required|exists:diamond_lab_master,dl_id',
            'polish' => 'nullable|integer',
            'symmetry' => 'nullable|integer',
            'fluorescence' => 'nullable|integer',
            'availability' => 'nullable|in:0,1,2',
            'is_superdeal' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'date_added' => 'nullable|date',
            'date_updated' => 'nullable|date',
        ];

        if ($isUpdate) {
            $statusUpdate = $request->has('status') && count($request->only('status')) === 1;
    
            if ($statusUpdate) {
                $rules = [
                    'status' => 'nullable|boolean',
                ];
            }
        }


        return $request->validate($rules);
    }

}
