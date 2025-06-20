<?php

namespace App\Http\Controllers\Jewellery;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Category;  
use App\Models\ProductImage;
use App\Models\DiamondMaster;
use App\Models\Country;
use App\Models\DiamondVendor;
use App\Models\ProductsToMetalType;
use App\Models\ProductToCategory;
use App\Models\ProductToOption;
use App\Models\ProductToShape;
use App\Models\ProductToStoneType;
use App\Models\ProductToStyleCategory;
use App\Models\ProductToStyleGroup;
use App\Models\ProductMetalColor;
use App\Models\ShopZonesToGeoZone;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
public function index(Request $request)
{
    if ($request->ajax()) {
        $products = Product::with(['images'])->orderBy('products_id', 'DESC')->get();

        return DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('featured_image', function ($product) {
                $featured = $product->images->where('is_featured', 1)->first();
                if ($featured) {
                    $url = url('storage/' . $featured->image_path);
                    return '<img src="' . $url . '" width="50" height="50" class="rounded" style="object-fit:cover;">';
                }
    return '<span class="text-muted">No Image</span>';
})


            ->addColumn('action', function ($product) {
                $edit = '<a href="' . route('product.edit', $product->products_id) . '" class="btn btn-sm btn-primary">Edit</a>';
                $delete = '<button data-id="' . $product->products_id . '" class="btn btn-sm btn-danger deleteBtn">Delete</button>';
                return $edit . ' ' . $delete;
            })

            ->editColumn('products_status', function ($product) {
                return $product->products_status
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })

            ->editColumn('products_price', function ($product) {
                $price = $product->products_price;
                if (is_null($price) || $price === '' || !is_numeric($price)) {
                    return '₹0.00';
                }
                return '₹' . number_format((float) $price, 2);
            })

            ->editColumn('date_added', function ($product) {
                return $product->date_added
                    ? date('d M Y', strtotime($product->date_added))
                    : '';
            })

            ->rawColumns(['featured_image','action', 'products_status']) // only keep HTML-rendered columns
            ->make(true);
    }

    return view('admin.Jewellery.Product.index');
}

    public function create()
    {
        $product = new Product();
        // dd($product);
         $vendors = DiamondVendor::select('vendorid', 'vendor_name')->get();
        $stock_numbers = DiamondMaster::select('diamondid','vendor_stock_number')->limit(100)->get();

        // $stock_numbers = DiamondMaster::select('diamondid', 'vendor_stock_number')->get();
        $vendor_prices = DiamondMaster::select('vendor_price')
                    ->distinct()
                    ->orderBy('vendor_price', 'asc')
                    ->limit(100)
                    ->get();
        // $vendor_prices = DiamondMaster::select('diamondid', 'vendor_price')->get();
        $countries = Country::select('country_id', 'country_name')->get();
        $categories = Category::select('id', 'category_name')->get();
        $diamond_qualities = \App\Models\DiamondQualityGroup::pluck('dqg_name', 'dqg_id');
        $diamond_clarities = \App\Models\ProductClarityMaster::pluck('name', 'id');
        $diamond_colors = \App\Models\ProductsColorMaster::pluck('name', 'id');
        $diamond_cuts = \App\Models\ProductsCutMaster::pluck('name', 'id');
        $stone_types = \App\Models\ProductStoneType::pluck('pst_name', 'pst_id');
        $metal_types = \App\Models\MetalType::pluck('dmt_name', 'dmt_id');
        $metal_colors = \App\Models\MetalType::pluck('color_code', 'dmt_id');
        $shapes = \App\Models\DiamondShape::pluck('name', 'id');
        $shopZones = \App\Models\ShopZone::pluck('zone_name', 'zone_id');

        $options = \App\Models\ProductToOption::pluck('products_to_option_id');
        $style_categories = \App\Models\ProductToStyleCategory::pluck('sptsc_id');
        $style_groups = \App\Models\ProductToStyleGroup::pluck('sptsg_id');
        $geo_zones = \App\Models\ShopZonesToGeoZone::pluck('association_id');
        $metal_to_types = \App\Models\ProductsToMetalType::pluck('sptmt_id');
        $product_to_categories = \App\Models\ProductToCategory::pluck('id');
        $shape_types = \App\Models\ProductToShape::pluck('pts_id');
        $stone_to_types = \App\Models\ProductToStoneType::pluck('sptst_id');
        $metalColor = \App\Models\ProductMetalColor::pluck('dmc_name', 'dmc_id');


        return view('admin.Jewellery.Product.create', 
        compact(
            'product', 'vendors', 
            'stock_numbers',
            'vendor_prices',
            'countries', 
            'categories',
            'diamond_qualities',
            'diamond_clarities',
            'diamond_colors',
            'diamond_cuts',
            'stone_types',
            'metal_types',
            'metal_colors',
            'shapes',
            'shopZones',
            'metal_to_types',
            'stone_to_types',
            'options',
            'style_categories',
            'style_groups',
            'geo_zones',
            'product_to_categories',
            'shape_types',
            'metalColor',
        ));
    }


    public function store(Request $request)
    {
        $rules = $this->getValidationRules();
        $messages = $this->getValidationMessages();
    
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $data = $request->all();
        $data['added_by'] = Auth::id();
        $data['date_added'] = now();
        $data['date_updated'] = now();


        $product = Product::create($data);

            // Handle featured image
         if ($request->hasFile('featured_image')) {
        $path = $request->file('featured_image')->store('product_images', 'public');
        ProductImage::create([
            'products_id' => $product->products_id,
            'image_path' => $path,
            'is_featured' => 1
        ]);
    }
        
        if ($request->has('variations')) {
            foreach ($request->variations as $variation) {
                $newVariation = $product->variations()->create([
                'carat' => $variation['carat'],
                'price' => $variation['price'],
                'sku' => $variation['sku'],
                'master_sku' => $variation['master_sku'] ?? null, // Added
                'stock' => $variation['stock'],
                'weight' => $variation->weight = [
                    'carat' => (float) $request->input('weight.carat'),
                    'grams' => (float) $request->input('weight.grams'),
                ],
                'shape_id' => $variation['shape_id'] ?? null,
                'category_id' => $variation['category_id'] ?? null,
                'metal_color_id' => $variation['metal_color_id'] ?? null, // Added
                'vendor_id' => $variation['vendor_id'] ?? null, // Added
            ]);
            
                // Handle variation images
                 if (isset($variation['images'])) {
                foreach ($variation['images'] as $image) {
                    $path = $image->store('variation_images', 'public');
                    ProductImage::create([
                        'variation_id' => $newVariation->id,
                        'image_path' => $path,
                    ]);
                }
            }
            }
        }

        if ($request->filled('metal_type_id')) {
            ProductsToMetalType::create([
                'sptmt_products_id' => $product->products_id,
                'sptmt_metal_type_id' => $request->metal_type_id
            ]);
        }

        ProductToCategory::create([
            'products_id' => $product->products_id,
            'categories_id' => $request->categories_id
        ]);

        if ($request->filled('options_id')) {
            ProductToOption::create([
                'products_id' => $product->products_id,
                'options_id' => $request->options_id,
            ]);
        }

        ProductToShape::create([
            'products_id' => $product->products_id,
            'shape_id' => $request->shape_id
        ]);

        ProductToStoneType::create([
            'sptst_products_id' => $product->products_id,
            'sptst_stone_type_id' => $request->stone_type_id
        ]);

        if ($request->filled('style_category_id')) {
            ProductToStyleCategory::create([
                'sptsc_products_id' => $product->products_id,
                'sptsc_style_category_id' => $request->style_category_id
            ]);
        }

        if ($request->filled('style_group_id')) {
            ProductToStyleGroup::create([
                'sptsg_products_id' => $product->products_id,
                'sptsg_style_category_id' => $request->style_group_id
            ]);
        }

        if ($request->filled('shop_zone_id') && $request->filled('geo_zone_id')) {
            ShopZonesToGeoZone::create([
                'zone_id' => $request->shop_zone_id,
                'geo_zone_id' => $request->geo_zone_id,
                'products_id' => $product->products_id
            ]);
        }

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $path = $image->store('product_images', 'public');

                ProductImage::create([
                    'products_id' => $product->products_id, 
                    'image_path' => $path,
                    'is_featured' => 0
                ]);
            } 
        }

        return response()->json([
            'redirect' => route('product.index'),
            'message' => 'Product added successfully.'
        ]);
    }

     public function edit($id)
    {
         $product = Product::with([
            'variations.images',
            'images'
        ])->findOrFail($id);
        
        // Ensure images relation is always a collection
        if (!$product->images) {
            $product->setRelation('images', collect());
        }
        
        // Ensure each variation has images collection
        foreach ($product->variations as $variation) {
            if (!$variation->images) {
                $variation->setRelation('images', collect());
            }
        }
        
        $vendors = DiamondVendor::select('vendorid', 'vendor_name')->get();
        $stock_numbers = DiamondMaster::select('diamondid','vendor_stock_number')->limit(100)->get();
        $vendor_prices = DiamondMaster::select('vendor_price')
            ->distinct()
            ->orderBy('vendor_price', 'asc')
            ->limit(100)
            ->get();
        $countries = Country::select('country_id', 'country_name')->get();
        $categories = Category::select('id', 'category_name')->get();
        $diamond_qualities = \App\Models\DiamondQualityGroup::pluck('dqg_name', 'dqg_id');
        $diamond_clarities = \App\Models\ProductClarityMaster::pluck('name', 'id');
        $diamond_colors = \App\Models\ProductsColorMaster::pluck('name', 'id');
        $diamond_cuts = \App\Models\ProductsCutMaster::pluck('name', 'id');
        $stone_types = \App\Models\ProductStoneType::pluck('pst_name', 'pst_id');
        $metal_types = \App\Models\MetalType::pluck('dmt_name', 'dmt_id');
        $metal_colors = \App\Models\MetalType::pluck('color_code', 'dmt_id');
        $shapes = \App\Models\DiamondShape::pluck('name', 'id');
        $shopZones = \App\Models\ShopZone::pluck('zone_name', 'zone_id');
        $options = \App\Models\ProductToOption::pluck('products_to_option_id');
        $style_categories = \App\Models\ProductToStyleCategory::pluck('sptsc_id');
        $style_groups = \App\Models\ProductToStyleGroup::pluck('sptsg_id');
        $geo_zones = \App\Models\ShopZonesToGeoZone::pluck('association_id');
        $metal_to_types = \App\Models\ProductsToMetalType::pluck('sptmt_id');
        $product_to_categories = \App\Models\ProductToCategory::pluck('id');
        $shape_types = \App\Models\ProductToShape::pluck('pts_id');
        $stone_to_types = \App\Models\ProductToStoneType::pluck('sptst_id');
        $metalColor = \App\Models\ProductMetalColor::pluck('dmc_name', 'dmc_id');

        return view('admin.Jewellery.Product.edit', compact(
            'product',
            'vendors',
            'stock_numbers',
            'vendor_prices',
            'countries',
            'categories',
            'diamond_qualities',
            'diamond_clarities',
            'diamond_colors',
            'diamond_cuts',
            'stone_types',
            'metal_types',
            'metal_colors',
            'shapes',
            'shopZones',
            'metal_to_types',
            'stone_to_types',
            'options',
            'style_categories',
            'style_groups',
            'geo_zones',
            'product_to_categories',
            'shape_types',
            'metalColor',
        ));
        return view('admin.Jewellery.Product.edit', compact('product'));
    }


    // public function edit($id)
    // {
    //     $product = Product::findOrFail($id);
    //     $categories = Category::select('id', 'category_name')->get();
    //     $vendors = DiamondVendor::select('vendorid', 'vendor_name')->get();
    //     $vendor_prices = DiamondMaster::select('diamondid', 'vendor_price')->get();
    //     $vendor_prices = DiamondMaster::select('vendor_price')->distinct()->orderBy('vendor_price', 'asc')->limit(100)->get();
    //     $countries = Country::select('country_id', 'country_name')->get();
    //     $diamond_qualities = \App\Models\DiamondQualityGroup::pluck('dqg_name', 'dqg_id');
    //     $diamond_clarities = \App\Models\ProductClarityMaster::pluck('name', 'id');
    //     $diamond_colors = \App\Models\ProductsColorMaster::pluck('name', 'id');
    //     $diamond_cuts = \App\Models\ProductsCutMaster::pluck('name', 'id');
    //     $stone_types = \App\Models\ProductStoneType::pluck('pst_name', 'pst_id');
    //     $shapes = \App\Models\DiamondShape::pluck('name', 'id');
    //     $shopZones  = \App\Models\ShopZone::pluck('zone_name', 'zone_id');
    //     $metal_types = \App\Models\MetalType::pluck('dmt_name', 'dmt_id');
    //     $metal_colors = \App\Models\MetalType::pluck('color_code', 'dmt_id');
    //     $options = \App\Models\ProductToOption::pluck('products_to_option_id');
    //     $style_categories = \App\Models\ProductToStyleCategory::pluck('sptsc_id');
    //     $style_groups = \App\Models\ProductToStyleGroup::pluck('sptsg_id');
    //     $geo_zones = \App\Models\ShopZonesToGeoZone::pluck('association_id');
    //     $metal_to_types = \App\Models\ProductsToMetalType::pluck('sptmt_id');
    //     $product_to_categories = \App\Models\ProductToCategory::pluck('id');
    //     $shape_types = \App\Models\ProductToShape::pluck('pts_id');
    //     $stone_to_types = \App\Models\ProductToStoneType::pluck('sptst_id');
    //     $variations = \App\Models\ProductVariation::pluck('id');
    //     $metalColor = \App\Models\ProductMetalColor::pluck('dmc_name', 'dmc_id');


    //     return view('admin.Jewellery.Product.edit', compact(
    //         'variations',
    //         'product',
    //         'categories',
    //         'vendors',
    //         'vendor_prices',
    //         'countries',
    //         'diamond_qualities',
    //         'diamond_clarities',
    //         'diamond_colors',
    //         'diamond_cuts',
    //         'stone_types',
    //         'metal_types',
    //         'metal_colors',
    //         'metal_to_types',
    //         'shapes',
    //         'shopZones',
    //         'stone_to_types',
    //         'options',
    //         'style_categories',
    //         'style_groups',
    //         'geo_zones',
    //         'product_to_categories',
    //         'shape_types',
    //         'metalColor',
    //     ));
    //     return view('admin.Jewellery.Product.edit', compact('product'));
    // }

public function update(Request $request, $id)
    {
        $rules = $this->getValidationRules();
        $messages = $this->getValidationMessages();
    
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($id);
        $data = $request->except([
            'featured_image', 
            'gallery_images', 
            'remove_images', 
            'variations',
            'removed_variation_images'
        ]);
        
        
 // Handle JSON weight field
        // if (isset($data['products_weight'])) {
        //     $data['products_weight'] = json_encode($data['products_weight']);
        // }
        
        $data['updated_by'] = Auth::id();
        $data['date_updated'] = now();
        $product->update($data);

        // Handle featured image update
        if ($request->hasFile('featured_image')) {
            // Delete existing featured image
            $existingFeatured = $product->images()->where('is_featured', 1)->first();
            if ($existingFeatured) {
                Storage::disk('public')->delete($existingFeatured->image_path);
                $existingFeatured->delete();
            }
            
            // Upload new featured image
            $path = $request->file('featured_image')->store('product_images', 'public');
            ProductImage::create([
                'products_id' => $product->products_id,
                'image_path' => $path,
                'is_featured' => 1
            ]);
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('product_images', 'public');
                ProductImage::create([
                    'products_id' => $product->products_id,
                    'image_path' => $path,
                    'is_featured' => 0
                ]);
            }
        }

        // Handle image removal
        if ($request->has('remove_images')) {
            $imagesToRemove = ProductImage::whereIn('id', $request->remove_images)->get();
            foreach ($imagesToRemove as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        // Handle variations
        $product->variations()->delete();
        
        if ($request->has('variations')) {
            foreach ($request->variations as $variation) {
                $newVariation = $product->variations()->create([
                    'carat' => $variation['carat'],
                    'price' => $variation['price'],
                    'sku' => $variation['sku'],
                    'master_sku' => $variation['master_sku'] ?? null,
                    'stock' => $variation['stock'],
                    'weight' => $variation->weight = [
                    'carat' => (float) $request->input('weight.carat'),
                    'grams' => (float) $request->input('weight.grams'),
                ],
                    'shape_id' => $variation['shape_id'] ?? null,
                    'category_id' => $variation['category_id'] ?? null,
                    'metal_color_id' => $variation['metal_color_id'] ?? null,
                    'vendor_id' => $variation['vendor_id'] ?? null,
                ]);
                
                // Handle variation images
                if (isset($variation['images'])) {
                    foreach ($variation['images'] as $image) {
                        $path = $image->store('variation_images', 'public');
                        ProductImage::create([
                            'variation_id' => $newVariation->id,
                            'image_path' => $path,
                        ]);
                    }
                }
            }
        }

        // Handle associations
        ProductsToMetalType::updateOrCreate(
            ['sptmt_products_id' => $id],
            ['sptmt_metal_type_id' => $request->metal_type_id]
        );

        ProductToCategory::updateOrCreate(
            ['products_id' => $id],
            ['categories_id' => $request->categories_id]
        );

        ProductToOption::updateOrCreate(
            ['products_id' => $id],
            ['options_id' => $request->options_id]
        );

        ProductToShape::updateOrCreate(
            ['products_id' => $id],
            ['shape_id' => $request->shape_id]
        );

        ProductToStoneType::updateOrCreate(
            ['sptst_products_id' => $id],
            ['sptst_stone_type_id' => $request->stone_type_id]
        );

        ProductToStyleCategory::updateOrCreate(
            ['sptsc_products_id' => $id],
            ['sptsc_style_category_id' => $request->style_category_id]
        );

        ProductToStyleGroup::updateOrCreate(
            ['sptsg_products_id' => $id],
            ['sptsg_style_category_id' => $request->style_group_id]
        );

        ShopZonesToGeoZone::updateOrCreate(
            ['zone_id' => $request->shop_zone_id],
            ['geo_zone_id' => $request->geo_zone_id],
            ['products_id' => $product->products_id]
        );

        return response()->json([
            'redirect' => route('product.index'),
            'message' => 'Product updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
    }

    private function getValidationRules()
    {
        return [
            'products_name'             => 'required|string|max:255',
            'products_description'      => 'nullable|string',
            'products_short_description'=> 'nullable|string|max:255',
            'available'                 => 'nullable|string|max:255',
            'products_quantity'         => 'nullable|integer',
            'products_model'            => 'nullable|string|max:150',
            'products_sku'              => 'nullable|string|max:150',
            'master_sku'                => 'nullable|string|max:255',
            'products_price'            => 'required|numeric|min:0',
            'products_price1'           => 'nullable|numeric',
            'products_price2'           => 'nullable|numeric',
            'products_price3'           => 'nullable|numeric',
            'products_price4'           => 'nullable|numeric',
            'products_weight'           => 'nullable|numeric',
            'products_status'           => 'required|in:0,1',
            'engraving_status'          => 'nullable|in:0,1',
            'products_slug'             => 'nullable|string|max:150',
            'catelog_no'                => 'nullable|string|max:255',
            'vendor_id'                 => 'nullable|integer',
            'vendor_stock_no'           => 'nullable|string|max:255',
            'vendor_price'              => 'nullable|numeric',
            // 'categories_id'             => 'required|integer',
            'shape_id'                  => 'required|integer',
            // 'shop_zone_id'              => 'required|integer',
            'country_of_origin'         => 'nullable|integer',
            'products_tax_class_id'     => 'nullable|integer',
            'products_tax'              => 'nullable|numeric',
            'is_bestseller'             => 'nullable|in:0,1',
            'is_featured'               => 'nullable|in:0,1',
            'ready_to_ship'             => 'nullable|boolean',
            'is_collection'             => 'nullable|in:0,1',
            'is_new'                    => 'nullable|in:0,1',
            'is_superdeals'             => 'nullable|in:0,1',
            'diamond_weight_group_id'   => 'nullable|integer',
            'diamond_quality_id'        => 'nullable|integer',
            'diamond_clarity_id'        => 'nullable|integer',
            'diamond_color_id'          => 'nullable|integer',
            'diamond_cut_id'            => 'nullable|integer',
            'diamond_pics'              => 'nullable|integer',
            'side_diamond_quality_id'   => 'nullable|integer',
            'side_diamond_breakdown'    => 'nullable|string',
            'semi_mount_ct_wt'          => 'nullable|numeric',
            'total_carat_weight'        => 'nullable|numeric',
            'semi_mount_price'          => 'nullable|numeric',
            'center_stone_price'        => 'nullable|numeric',
            'center_stone_weight'       => 'nullable|numeric',
            'center_stone_type_id'      => 'nullable|integer',
            'stone_type_id'             => 'nullable|integer',
            'metal_type_id'             => 'required|integer',
            // 'metal_color_id'            => 'required|integer',
            'metal_weight'              => 'nullable|numeric',
            'is_build_product'          => 'nullable|string|max:150',
            'build_product_type'        => 'nullable|string|max:250',
            'is_matching_set'           => 'nullable|in:0,1',
            'product_keywords'          => 'nullable|string',
            'product_promotion'         => 'nullable|string',
            'certified_lab'             => 'nullable|string',
            'certificate_number'        => 'nullable|string',
            'products_related_items'    => 'nullable|string|max:255',
            'related_master_sku'        => 'nullable|string|max:255',
            'products_meta_title'       => 'nullable|string',
            'products_meta_description' => 'nullable|string',
            'products_meta_keyword'     => 'nullable|string',
            'delivery_days'             => 'nullable|integer',
            'default_size'              => 'nullable|string|max:10',
            'deleted'                   => 'nullable|in:0,1',
            'sort_order'                => 'nullable|integer',
            'product_images.*'          => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'variations' => 'required|array|min:1',
            'variations.*.carat' => 'required|numeric|min:0.01',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.sku' => 'required|string|max:150|unique:product_variations,sku',
            'variations.*.stock' => 'nullable|integer|min:0',
            'variations.*weight' => 'nullable|array',
            'variations.*weight.carat' => 'nullable|numeric|min:0',
            'variations.*weight.grams' => 'nullable|numeric|min:0',
            'variations.*.shape_id' => 'nullable|exists:diamond_shape_master,id',
            // 'variations.*.category_id' => 'nullable|exists:categories,category_id',
            'variations.*.images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'variations.*.master_sku' => 'nullable|string|max:150',
            // 'variations.*.metal_color_id' => 'nullable|exists:products_metal_color,dmc_id',
            'variations.*.vendor_id' => 'nullable|exists:vendor_master,vendorid',
        ];
    }

    private function getValidationMessages()
    {
        return [

            'products_name.required'             => 'Product Name is required.',
            'products_name.string'               => 'Product Name must be a valid string.',
            'products_name.max'                  => 'Product Name may not exceed 255 characters.',
            'products_description.string'        => 'Description must be a valid string.',
            'products_short_description.string'  => 'Short Description must be a valid string.',
            'products_short_description.max'     => 'Short Description may not exceed 255 characters.',
            'available.string'                   => 'Availability must be a valid string.',
            'available.max'                      => 'Availability may not exceed 255 characters.',
            'products_quantity.integer'          => 'Quantity must be an integer.',
            'products_model.string'              => 'Model must be a valid string.',
            'products_model.max'                 => 'Model may not exceed 150 characters.',
            'products_sku.string'                => 'SKU must be a valid string.',
            'products_sku.max'                   => 'SKU may not exceed 150 characters.',
            'master_sku.string'                  => 'Master SKU must be a valid string.',
            'master_sku.max'                     => 'Master SKU may not exceed 255 characters.',
            'categories_id.integer'              => 'Category must be an integer.',
            'shape_id.integer'                   => 'Shape must be an integer.',
            'shop_zone_id.integer'               => 'Shop Zone must be an integer.',
            'ready_to_ship.boolean'              => 'Ready to Ship must be true or false.',
            'products_price.numeric'             => 'Price must be a valid number.',
            'products_price1.numeric'            => 'Price 1 must be a valid number.',
            'products_price2.numeric'            => 'Price 2 must be a valid number.',
            'products_price3.numeric'            => 'Price 3 must be a valid number.',
            'products_price4.numeric'            => 'Price 4 must be a valid number.',
            'products_weight.numeric'            => 'Weight must be a valid number.',
            'products_status.in'                 => 'Status must be either 0 (Inactive) or 1 (Active).',
            'engraving_status.in'                => 'Engraving Status must be either 0 (No) or 1 (Yes).',
            'products_slug.string'               => 'Slug must be a valid string.',
            'products_slug.max'                  => 'Slug may not exceed 150 characters.',
            'catelog_no.string'                  => 'Catalog Number must be a valid string.',
            'catelog_no.max'                     => 'Catalog Number may not exceed 255 characters.',
            'vendor_id.integer'                  => 'Vendor ID must be an integer.',
            'vendor_stock_no.string'             => 'Vendor Stock Number must be a valid string.',
            'vendor_stock_no.max'                => 'Vendor Stock Number may not exceed 255 characters.',
            'vendor_price.numeric'               => 'Vendor Price must be a valid number.',
            'categories_id.integer'              => 'Category must be an integer.',
            'country_of_origin.integer'          => 'Country of Origin must be an integer.',
            'products_tax_class_id.integer'      => 'Tax Class ID must be an integer.',
            'products_tax.numeric'               => 'Tax must be a valid number.',
            'is_bestseller.in'                   => 'Bestseller must be either 0 (No) or 1 (Yes).',
            'is_featured.in'                     => 'Featured must be either 0 (No) or 1 (Yes).',
            'ready_to_ship.boolean'              => 'Ready to Ship must be a boolean.',
            'is_collection.in'                   => 'Collection must be either 0 (No) or 1 (Yes).',
            'is_new.in'                          => 'New must be either 0 (No) or 1 (Yes).',
            'is_superdeals.in'                   => 'SuperDeals must be either 0 (No) or 1 (Yes).',
            'diamond_weight_group_id.integer'    => 'Diamond Weight Group ID must be an integer.',
            'diamond_quality_id.integer'         => 'Diamond Quality ID must be an integer.',
            'diamond_clarity_id.integer'         => 'Diamond Clarity ID must be an integer.',
            'diamond_color_id.integer'           => 'Diamond Color ID must be an integer.',
            'diamond_cut_id.integer'             => 'Diamond Cut ID must be an integer.',
            'diamond_pics.integer'               => 'Diamond Pics must be an integer.',
            'side_diamond_quality_id.integer'    => 'Side Diamond Quality ID must be an integer.',
            'side_diamond_breakdown.string'      => 'Side Diamond Breakdown must be a valid string.',
            'semi_mount_ct_wt.numeric'           => 'Semi Mount CT Weight must be a valid number.',
            'total_carat_weight.numeric'         => 'Total Carat Weight must be a valid number.',
            'semi_mount_price.numeric'           => 'Semi Mount Price must be a valid number.',
            'center_stone_price.numeric'         => 'Center Stone Price must be a valid number.',
            'center_stone_weight.numeric'        => 'Center Stone Weight must be a valid number.',
            'center_stone_type_id.integer'       => 'Center Stone Type ID must be an integer.',
            'stone_type_id.integer'              => 'Stone Type ID must be an integer.',
            'metal_type_id.integer'              => 'Metal Type ID must be an integer.',
            // 'metal_color_id.integer'             => 'Metal Color ID must be an integer.',
            'metal_weight.numeric'               => 'Metal Weight must be a valid number.',
            'is_build_product.string'            => 'Build Product must be a valid string.',
            'is_build_product.max'               => 'Build Product may not exceed 150 characters.',
            'build_product_type.string'          => 'Build Product Type must be a valid string.',
            'build_product_type.max'             => 'Build Product Type may not exceed 250 characters.',
            'is_matching_set.in'                 => 'Matching Set must be either 0 (No) or 1 (Yes).',
            'product_keywords.string'            => 'Product Keywords must be a valid string.',
            'product_promotion.string'           => 'Product Promotion must be a valid string.',
            'certified_lab.string'               => 'Certified Lab must be a valid string.',
            'certificate_number.string'          => 'Certificate Number must be a valid string.',
            'products_related_items.string'      => 'Related Items must be a valid string.',
            'products_related_items.max'         => 'Related Items may not exceed 255 characters.',
            'related_master_sku.string'          => 'Related Master SKU must be a valid string.',
            'related_master_sku.max'             => 'Related Master SKU may not exceed 255 characters.',
            'products_meta_title.string'         => 'Meta Title must be a valid string.',
            'products_meta_description.string'   => 'Meta Description must be a valid string.',
            'products_meta_keyword.string'       => 'Meta Keyword must be a valid string.',
            'delivery_days.integer'              => 'Delivery Days must be an integer.',
            'default_size.string'                => 'Default Size must be a valid string.',
            'default_size.max'                   => 'Default Size may not exceed 10 characters.',
            'deleted.in'                         => 'Deleted must be either 0 (No) or 1 (Yes).',
            'sort_order.integer'                 => 'Sort Order must be an integer.',

        ];
    }
}