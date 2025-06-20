<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Step 1: Read filters and pagination parameters
        $filters = $request->input('filters', []);
        $perPage = (int) $request->input('perPage', 20);
        $page = (int) $request->input('page', 1);

        // Step 2: Build the base variation query with filters
        $variationQuery = ProductVariation::query();

        if (in_array('Under $1000', $filters)) {
            $variationQuery->where('price', '<', 1000);
        }

        // Optional: Enable this filter if needed later
        // if (in_array('Quantity 2-4', $filters)) {
        //     $variationQuery->whereBetween('quantity', [2, 4]);
        // }

        // Step 3: Get total unique product count for pagination
        $totalProducts = (clone $variationQuery)
            ->distinct('product_id')
            ->count('product_id');

        // Step 4: Get only paginated product IDs from variations
        $paginatedProductIds = (clone $variationQuery)
            ->select('product_id')
            ->distinct()
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->pluck('product_id');

        // Step 5: Fetch the products and apply same filter to their variations
        $products = Product::with(['variations' => function ($query) use ($filters) {
            if (in_array('Under $1000', $filters)) {
                $query->where('price', '<', 1000);
            }
            $query->orderBy('carat');
            // Optional quantity filter
            // if (in_array('Quantity 2-4', $filters)) {
            //     $query->whereBetween('quantity', [2, 4]);
            // }
        }])
        ->whereIn('products_id', $paginatedProductIds)
        ->orderBy('products_id')
        ->get();

        // Step 6: Format the data — only include products with at least one matching variation
        $validProducts = [];

        foreach ($products as $product) {
            $variations = $product->variations()->orderBy('carat')->get();

            if ($variations->isNotEmpty()) {
                $validProducts[] = [
                    'id' => $product->products_id,
                    'product' => [
                        'id' => $product->products_id,
                        'name' => $product->products_name,
                        'master_sku' => $product->master_sku,
                        'description' => $product->products_description,
                        'ready_to_ship' => $product->ready_to_ship,
                    ],
                    'variations' => $variations->values(),
                ];
            }
        }

        // Step 7: Return paginated JSON response
        return response()->json([
            'data' => $validProducts,
            'totalProducts' => $totalProducts,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($totalProducts / $perPage),
        ]);
    }
}