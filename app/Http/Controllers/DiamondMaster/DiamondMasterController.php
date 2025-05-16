<?php
namespace App\Http\Controllers\DiamondMaster;

use App\Models\DiamondCulet;
use App\Models\DiamondCut;
use App\Models\DiamondFancyColor;
use App\Models\DiamondFancyColorIntensity;
use App\Models\DiamondFlourescence;
use App\Models\DiamondLab;
use App\Models\DiamondColor;
use App\Models\DiamondPolish;
use App\Models\DiamondShape;
use App\Models\DiamondSymmetry;
use Illuminate\Http\Request;
use App\Models\DiamondMaster;
use App\Models\DiamondVendor;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\DiamondClarityMaster;
use Illuminate\Support\Facades\Validator;


class DiamondMasterController extends Controller
{
    public function index()
    {
        return view('admin.DiamondMaster.master.index', [
            'vendors' => DiamondVendor::pluck('vendor_name', 'vendorid'),
            'labs' => DiamondLab::pluck('dl_name', 'dl_id'),
            'shapes' => DiamondShape::pluck('name', 'id'),
            'colors' => DiamondColor::pluck('name', 'id'),
            'clarities' => DiamondClarityMaster::pluck('name', 'id'),
            'cuts' => DiamondCut::pluck('name', 'id'),
            'polish' => DiamondPolish::pluck('name', 'id'),
            'symmetry' => DiamondSymmetry::pluck('name', 'id'),
            'fluorescence' => DiamondFlourescence::pluck('name', 'id'),
            'culet' => DiamondCulet::pluck('dc_name', 'dc_id'),
            'fancyColorIntensity' => DiamondFancyColorIntensity::pluck('fci_id', 'fci_name'),
            'fancycolorOvertones' => DiamondFancyColor::pluck('fco_name', 'fco_id'),
        ]);
    }

    public function dataBackend(Request $request)
    {
        $query = DiamondMaster::with([
            'vendor',
            'shape',
            'color',
            'cut',
            'clarity',
            'certificateCompany',
            'polish',
            'symmetry',
            'fluorescence'
        ]);

        if ($request->filled('price')) {
            $price = $request->get('price'); // expects [min, max]
            $query->whereBetween('price', $price);
        }

        if ($request->has('carat') && is_array($request->carat) && count($request->carat) === 2) {
            $carat = array_map('floatval', $request->carat); // expects [min, max]
            $query->whereBetween('carat_weight', $carat);
        }

        if ($request->has('cut') && is_array($request->cut) && count($request->cut) === 2) {
            $cut = array_map('intval', $request->cut);
            $query->whereBetween('cut', $cut);
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

        $perPage = $request->get('per_page', 20);
        $diamonds = $query->paginate($perPage);

        return DataTables::of($query)->make(true);
    }
    public function data(Request $request)
    {
        $query = DiamondMaster::with([
            'vendor',
            'shape',
            'color',
            'cut',
            'clarity',
            'certificateCompany',
            'polish',
            'symmetry',
            'fluorescence'
        ]);

        if ($request->filled('price')) {
            $price = $request->get('price'); // expects [min, max]
            $query->whereBetween('price', $price);
        }

        if ($request->has('carat') && is_array($request->carat) && count($request->carat) === 2) {
            $carat = array_map('floatval', $request->carat); // expects [min, max]
            $query->whereBetween('carat_weight', $carat);
        }

        if ($request->has('cut') && is_array($request->cut) && count($request->cut) === 2) {
            $cut = array_map('intval', $request->cut);
            $query->whereBetween('cut', $cut);
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

        // Add similar checks for polish, symmetry, fluorescence, ratio, table, depth, featured, etc.

        // Sorting
        // if ($request->filled('sort')) {
        //     $sort = explode(':', $request->get('sort'));
        //     $column = $sort[0];
        //     $direction = $sort[1] ?? 'asc';
        //     $query->orderBy($column, $direction);
        // }

        $perPage = $request->get('per_page', 20);
        $diamonds = $query->paginate($perPage);

        return response()->json($diamonds);
        // return response()->json([
        //     'request_data' => $request->all(),
        //     'diamonds' => $query->paginate($perPage),
        // ]);
    }

    public function create()
    {
        return view('admin.DiamondMaster.master.create', [
            'vendors' => DiamondVendor::pluck('vendor_name', 'vendorid'),
            'labs' => DiamondLab::pluck('dl_name', 'dl_id'),
            'shapes' => DiamondShape::pluck('name', 'id'),
            'colors' => DiamondColor::pluck('name', 'id'),
            'clarities' => DiamondClarityMaster::pluck('name', 'id'),
            'cuts' => DiamondCut::pluck('name', 'id'),
            'polish' => DiamondPolish::pluck('name', 'id'),
            'symmetry' => DiamondSymmetry::pluck('name', 'id'),
            'fluorescence' => DiamondFlourescence::pluck('name', 'id'),
            'culet' => DiamondCulet::pluck('dc_name', 'dc_id'),
            'fancyColorIntensity' => DiamondFancyColorIntensity::pluck('fci_name', 'fci_id'),
            'fancycolorOvertones' => DiamondFancyColor::pluck('fco_name', 'fco_id'),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'diamond_type' => 'required|in:1,2',
            'quantity' => 'required|integer|min:1',
            'vendor_id' => 'required|exists:vendor_master,vendorid',
            'vendor_stock_number' => 'nullable|string|max:100',
            'stock_number' => 'nullable|string|max:100',
            'shape' => 'required|exists:diamond_shape_master,id',
            'carat_weight' => 'required|numeric|min:0.01',
            'color' => 'nullable|exists:diamond_color_master,id',
            'clarity' => 'nullable|exists:diamond_clarity_master,id',
            'cut' => 'required|integer',
            'polish' => 'nullable|integer',
            'symmetry' => 'nullable|integer',
            'fluorescence' => 'nullable|integer',
            'fancy_color_intensity' => 'nullable|integer',
            'fancy_color_overtone' => 'nullable|integer',
            'price' => 'nullable|numeric|min:0',
            'msrp_price' => 'nullable|numeric|min:0',
            'price_per_carat' => 'nullable|numeric|min:0',
            'image_link' => 'nullable|url',
            'cert_link' => 'nullable|url',
            'video_link' => 'nullable|url',
            'measurements' => 'nullable|string',
            'depth' => 'nullable|numeric|min:0',
            'vendor_rap_disc' => 'nullable|numeric|min:0',
            'table_diamond' => 'nullable|numeric|min:0',
            'certificate_company' => 'nullable|exists:diamond_lab_master,dl_id',
            'certificate_number' => 'nullable|string|max:100',
            'certificate_date' => 'nullable|date',
            'measurement_l' => 'nullable|numeric|min:0',
            'measurement_h' => 'nullable|numeric|min:0',
            'measurement_w' => 'nullable|numeric|min:0',
            'is_superdeal' => 'nullable|in:0,1',
            'availability' => 'nullable|in:0,1,2',
            'status' => 'nullable|in:0,1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validatedData = $validator->validated();
        $dimensions = [];

        if (!empty($validatedData['measurement_l'])) {
            $dimensions[] = $validatedData['measurement_l'];
        }
        if (!empty($validatedData['measurement_w'])) {
            $dimensions[] = $validatedData['measurement_w'];
        }
        if (!empty($validatedData['measurement_h'])) {
            $dimensions[] = $validatedData['measurement_h'];
        }

        if (count($dimensions) > 0) {
            $validatedData['measurements'] = implode(' x ', $dimensions);
        }


        $validatedData['date_added'] = now();
        $validatedData['added_by'] = auth()->id();
        DiamondMaster::create($validatedData);

        return response()->json(['message' => 'Diamond added successfully.']);
    }

    public function edit($id)
    {
        $diamond = DiamondMaster::findOrFail($id);
        return view('admin.DiamondMaster.master.edit', array_merge(
            ['diamond' => $diamond],
            [
                'vendors' => DiamondVendor::pluck('vendor_name', 'vendorid'),
                'labs' => DiamondLab::pluck('dl_name', 'dl_id'),
                'shapes' => DiamondShape::pluck('name', 'id'),
                'colors' => DiamondColor::pluck('name', 'id'),
                'clarities' => DiamondClarityMaster::pluck('name', 'id'),
                'cuts' => DiamondCut::pluck('name', 'id'),
                'polish' => DiamondPolish::pluck('name', 'id'),
                'symmetry' => DiamondSymmetry::pluck('name', 'id'),
                'fluorescence' => DiamondFlourescence::pluck('name', 'id'),
                'culet' => DiamondCulet::pluck('dc_name', 'dc_id'),
                'fancyColorIntensity' => DiamondFancyColorIntensity::pluck('fci_name', 'fci_id'),
                'fancycolorOvertones' => DiamondFancyColor::pluck('fco_name', 'fco_id'),
            ]
        ));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'diamond_type' => 'required|in:1,2',
            'quantity' => 'required|integer|min:1',
            'vendor_id' => 'required|exists:vendor_master,vendorid',
            'vendor_stock_number' => 'nullable|string|max:100',
            'stock_number' => 'nullable|string|max:100',
            'shape' => 'required|exists:diamond_shape_master,id',
            'carat_weight' => 'required|numeric|min:0.01',
            'color' => 'nullable|exists:diamond_color_master,id',
            'clarity' => 'nullable|exists:diamond_clarity_master,id',
            'culet' => 'nullable|exists:diamond_culet_master,dc_id',
            'cut' => 'required|integer',
            'polish' => 'nullable|integer',
            'symmetry' => 'nullable|integer',
            'fluorescence' => 'nullable|integer',
            'fancy_color_intensity' => 'nullable|integer',
            'fancy_color_overtone' => 'nullable|integer',
            'price' => 'nullable|numeric|min:0',
            'msrp_price' => 'nullable|numeric|min:0',
            'price_per_carat' => 'nullable|numeric|min:0',
            'image_link' => 'nullable|url',
            'cert_link' => 'nullable|url',
            'video_link' => 'nullable|url',
            'measurements' => 'nullable|string',
            'depth' => 'nullable|numeric|min:0',
            'vendor_rap_disc' => 'nullable|numeric|min:0',
            'table_diamond' => 'nullable|numeric|min:0',
            'certificate_company' => 'nullable|exists:diamond_lab_master,dl_id',
            'certificate_number' => 'nullable|string|max:100',
            'certificate_date' => 'nullable|date',
            'measurement_l' => 'nullable|numeric|min:0',
            'measurement_h' => 'nullable|numeric|min:0',
            'measurement_w' => 'nullable|numeric|min:0',
            'is_superdeal' => 'nullable|in:0,1',
            'availability' => 'nullable|in:0,1,2',
            'status' => 'nullable|in:0,1',
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();
        $dimensions = [];

        if (!empty($validatedData['measurement_l'])) {
            $dimensions[] = $validatedData['measurement_l'];
        }
        if (!empty($validatedData['measurement_w'])) {
            $dimensions[] = $validatedData['measurement_w'];
        }
        if (!empty($validatedData['measurement_h'])) {
            $dimensions[] = $validatedData['measurement_h'];
        }

        if (count($dimensions) > 0) {
            $validatedData['measurements'] = implode(' x ', $dimensions);
        }

        $validatedData['date_updated'] = now();
        $validatedData['updated_by'] = auth()->id();
        DiamondMaster::findOrFail($id)->update($validatedData);
        //return response()->json(['message' => 'Diamond updated successfully.']);
        return response()->json(['success' => true, 'message' => 'Record updated successfully.']);
    }

    public function destroy($id)
    {
        DiamondMaster::findOrFail($id)->delete();
        return redirect()->route('diamond-master.index')
            ->with('success', 'Diamond deleted successfully.');
    }
}
