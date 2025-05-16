<?php
namespace App\Http\Controllers\DiamondMaster;

use App\Http\Controllers\Controller;
use App\Models\DiamondMaster;
use App\Models\DiamondVendor;
use App\Models\DiamondLab;
use App\Models\DiamondShape;
use App\Models\DiamondColor;
use App\Models\DiamondClarityMaster;
use Illuminate\Http\Request;

class DiamondMasterController extends Controller
{
    public function index()
    {
        return view('admin.DiamondMaster.master.index', [
            'vendors'    => DiamondVendor::pluck('vendor_name','vendorid'),
            'labs'       => DiamondLab::pluck('dl_name','dl_id'),
            'shapes'     => DiamondShape::pluck('name','id'),
            'colors'     => DiamondColor::pluck('name','id'),
            'clarities'  => DiamondClarityMaster::pluck('name','id'),
        ]);
    }

    public function data(Request $request)
    {
        $query = DiamondMaster::with([
            'vendor', 'shape', 'color', 'cut', 'clarity',
            'certificateCompany', 'polish', 'symmetry', 'fluorescence'
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
            'vendors'   => DiamondVendor::pluck('vendor_name','vendorid'),
            'labs'      => DiamondLab::pluck('dl_name','dl_id'),
            'shapes'    => DiamondShape::pluck('name','id'),
            'colors'    => DiamondColor::pluck('name','id'),
            'clarities' => DiamondClarityMaster::pluck('name','id'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'diamond_type'        => 'required|in:1,2',
            'quantity'            => 'required|integer|min:1',
            'vendor_id'           => 'required|exists:vendor_master,vendorid',
            'shape'               => 'required|exists:diamond_shape_master,id',
            'color'               => 'nullable|exists:diamond_color_master,id',
            'clarity'             => 'nullable|exists:diamond_clarity_master,id',
            'certificate_company' => 'nullable|exists:diamond_lab_master,dl_id',
            'carat_weight'        => 'required|numeric|min:0.01',
            'price_per_carat'     => 'nullable|numeric|min:0',
            'vendor_price'        => 'nullable|numeric|min:0',
            'certificate_number'  => 'nullable|string|max:100',
            'certificate_date'    => 'nullable|date',
            'availability'        => 'nullable|in:0,1,2',
            'status'              => 'nullable|in:0,1',
        ]);

        DiamondMaster::create($data);
        return redirect()->route('diamond-master.index')
                         ->with('success','Diamond added successfully.');
    }

    public function edit($id)
    {
        $diamond = DiamondMaster::findOrFail($id);
        return view('admin.DiamondMaster.master.edit', array_merge(
            ['diamond' => $diamond],
            [
              'vendors'   => DiamondVendor::pluck('vendor_name','vendorid'),
              'labs'      => DiamondLab::pluck('dl_name','dl_id'),
              'shapes'    => DiamondShape::pluck('name','id'),
              'colors'    => DiamondColor::pluck('name','id'),
              'clarities' => DiamondClarityMaster::pluck('name','id'),
            ]
        ));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'diamond_type'        => 'required|in:1,2',
            'quantity'            => 'required|integer|min:1',
            'vendor_id'           => 'required|exists:vendor_master,vendorid',
            'shape'               => 'required|exists:diamond_shape_master,id',
            'color'               => 'nullable|exists:diamond_color_master,id',
            'clarity'             => 'nullable|exists:diamond_clarity_master,id',
            'certificate_company' => 'nullable|exists:diamond_lab_master,dl_id',
            'carat_weight'        => 'required|numeric|min:0.01',
            'price_per_carat'     => 'nullable|numeric|min:0',
            'vendor_price'        => 'nullable|numeric|min:0',
            'certificate_number'  => 'nullable|string|max:100',
            'certificate_date'    => 'nullable|date',
            'availability'        => 'nullable|in:0,1,2',
            'status'              => 'nullable|in:0,1',
        ]);

        DiamondMaster::findOrFail($id)->update($data);
        return redirect()->route('diamond-master.index')
                         ->with('success','Diamond updated successfully.');
    }

    public function destroy($id)
    {
        DiamondMaster::findOrFail($id)->delete();
        return redirect()->route('diamond-master.index')
                         ->with('success','Diamond deleted successfully.');
    }
}
