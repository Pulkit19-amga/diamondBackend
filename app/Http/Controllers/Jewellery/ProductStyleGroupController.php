<?php

namespace App\Http\Controllers\Jewellery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductStyleGroup;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ProductStyleGroupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductStyleGroup::orderBy('psg_id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('psg_status', function($row){
                    return $row->psg_status ? 'Yes' : 'No';
                })
                ->addColumn('psg_display_in_front', function($row){
                    return $row->psg_display_in_front ? 'Yes' : 'No';
                })
                ->addColumn('action', function($row){
                    $btn = '<button class="btn btn-sm btn-primary editBtn" data-id="'.$row->psg_id.'">Edit</button>';
                    $btn .= '<button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->psg_id.'">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.jewellery.ProductStyleGroup.index');
    }

    // Store Method: नया रिकॉर्ड सेव करता है
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'psg_name'  => 'required|string|max:250',
            'psg_alias' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        ProductStyleGroup::create([
            'psg_category_id'      => $request->psg_category_id,
            'psg_name'             => $request->psg_name,
            'psg_image'            => $request->psg_image,
            'psg_status'           => $request->psg_status,
            'psg_sort_order'       => $request->psg_sort_order,
            'psg_alias'            => $request->psg_alias,
            'psg_display_in_front' => $request->psg_display_in_front,
            'date_added'           => Carbon::now(),
            'added_by'             => auth()->id(),
        ]);

        return response()->json(['message' => 'Product Style Group added successfully.']);
    }

    // Edit Method: एक रिकॉर्ड का डेटा JSON में रिटर्न करता है
    public function edit($id)
    {
        $data = ProductStyleGroup::findOrFail($id);
        return response()->json($data);
    }

    // Update Method: मौजूदा रिकॉर्ड को अपडेट करता है
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'psg_name'  => 'required|string|max:250',
            'psg_alias' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $psg = ProductStyleGroup::findOrFail($id);
        $psg->update([
            'psg_category_id'      => $request->psg_category_id,
            'psg_name'             => $request->psg_name,
            'psg_image'            => $request->psg_image,
            'psg_status'           => $request->psg_status,
            'psg_sort_order'       => $request->psg_sort_order,
            'psg_alias'            => $request->psg_alias,
            'psg_display_in_front' => $request->psg_display_in_front,
            'date_modified'        => Carbon::now(),
            'updated_by'           => auth()->id(),
        ]);

        return response()->json(['message' => 'Product Style Group updated successfully.']);
    }

    // Destroy Method: रिकॉर्ड को डिलीट करता है
    public function destroy($id)
    {
        ProductStyleGroup::destroy($id);
        return response()->json(['message' => 'Product Style Group deleted successfully.']);
    }
}
