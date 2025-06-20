<?php

namespace App\Http\Controllers\Jewellery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductStyleCategory;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ProductStyleCategoryController extends Controller
{
    public function index(Request $request)
    {
        // Agar AJAX request hai tab DataTables ke liye JSON bhejein
        if ($request->ajax()) {
            $categories = ProductStyleCategory::orderBy('psc_id', 'DESC');
            
            return DataTables::of($categories)
                ->addColumn('action', function ($row) {
                    $editBtn = '<button data-id="'.$row->psc_id.'" class="btn btn-sm btn-primary editBtn">Edit</button>';
                    $deleteBtn = '<button data-id="'.$row->psc_id.'" class="btn btn-sm btn-danger deleteBtn">Delete</button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->editColumn('psc_status', function($row) {
                    return $row->psc_status ? 'Yes' : 'No';
                })
                ->editColumn('psc_display_in_front', function($row) {
                    return $row->psc_display_in_front ? 'Yes' : 'No';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.jewellery.ProductStyleCategory.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'psc_name' => 'required|string|max:250',
            'psc_alias' => 'required|string|max:250',
            // Aap aur validation rules add kar sakte hain
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        ProductStyleCategory::create([
            'psc_category_id' => $request->psc_category_id,
            'psc_name' => $request->psc_name,
            'psc_image' => $request->psc_image,
            'psc_status' => $request->psc_status ?? 0,
            'psc_sort_order' => $request->psc_sort_order ?? 0,
            'psc_alias' => $request->psc_alias,
            'psc_display_in_front' => $request->psc_display_in_front ?? 0,
            'date_added' => Carbon::now(),
            'added_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Product Style Category added successfully.']);
    }

    public function edit($id)
    {
        $data = ProductStyleCategory::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'psc_name' => 'required|string|max:250',
            'psc_alias' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = ProductStyleCategory::findOrFail($id);
        $category->update([
            'psc_category_id' => $request->psc_category_id,
            'psc_name' => $request->psc_name,
            'psc_image' => $request->psc_image,
            'psc_status' => $request->psc_status ?? 0,
            'psc_sort_order' => $request->psc_sort_order ?? 0,
            'psc_alias' => $request->psc_alias,
            'psc_display_in_front' => $request->psc_display_in_front ?? 0,
            'date_modified' => Carbon::now(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Product Style Category updated successfully.']);
    }

    public function destroy($id)
    {
        ProductStyleCategory::destroy($id);
        return response()->json(['message' => 'Product Style Category deleted successfully.']);
    }
}
