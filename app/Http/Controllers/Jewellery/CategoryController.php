<?php

namespace App\Http\Controllers\Jewellery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $categories = Category::orderBy('id', 'desc')->get();
            return response()->json($categories);
        }

        return view('admin.Jewellery.Category.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
            'category_alias' => 'nullable|string|max:255',
            'category_status' => 'required|boolean',
            'is_display_front' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['category_date_added'] = now();
        $data['added_by'] = Auth::id();

        Category::create($data);

        return response()->json(['success' => true, 'message' => 'Category added successfully.']);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

     public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // PARTIAL update - only when NOT full update
        if ($request->input('update_mode') !== 'full') {
            $validator = Validator::make($request->all(), [
                'category_status' => 'sometimes|boolean',
                'is_display_front' => 'sometimes|boolean',
                'sort_order' => 'sometimes|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $request->only(['category_status', 'is_display_front', 'sort_order']);
            $data['category_date_modified'] = now();
            $data['updated_by'] = auth()->id();

            $category->update($data);

            return response()->json(['success' => true, 'message' => 'Category field(s) updated successfully.']);
        }

        // FULL update
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
            'category_alias' => 'nullable|string|max:255',
            'category_description' => 'nullable|string',
            'category_image' => 'nullable|string',
            'category_header_banner' => 'nullable|string',
            'category_status' => 'required|boolean',
            'is_display_front' => 'required|boolean',
            'seo_url' => 'nullable|string',
            'category_meta_title' => 'nullable|string',
            'category_meta_description' => 'nullable|string',
            'category_meta_keyword' => 'nullable|string',
            'category_h1_tag' => 'nullable|string',
            'sort_order' => 'required|integer',
            'deleted' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'category_name',
            'category_alias',
            'category_description',
            'category_image',
            'category_header_banner',
            'category_status',
            'is_display_front',
            'seo_url',
            'category_meta_title',
            'category_meta_description',
            'category_meta_keyword',
            'category_h1_tag',
            'sort_order',
            'deleted',
        ]);

        $data['category_date_modified'] = now();
        $data['updated_by'] = auth()->id();

        $category->update($data);

        return response()->json(['success' => true, 'message' => 'Category fully updated successfully.']);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
    }
}
