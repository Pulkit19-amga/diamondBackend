<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiamondColor;
use Illuminate\Http\Request;
use App\Models\DiamondShape;
use Illuminate\Http\JsonResponse;

class ShapeController extends Controller
{
    public function getFrontShapes(Request $request): JsonResponse
    {
        try {
            // Fetch all active DB shapes with their aliases
            $shapesFromDb = DiamondShape::where('display_in_front', 1)->get();
    
            // Fetch shapes from the external API
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://www.onepricelab.com/api/search',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
    
            $apiData = json_decode($response, true);
            $shapesFromApi = collect($apiData['data'])->pluck('shape')->unique();
    
            $resolvedShapes = [];
    
            foreach ($shapesFromApi as $apiShape) {
                $apiShapeUpper = strtoupper(trim($apiShape));
                foreach ($shapesFromDb as $dbShape) {
                    $dbNameUpper = strtoupper(trim($dbShape->name));
    
                    if ($apiShapeUpper === $dbNameUpper) {
                        $resolvedShapes[] = $dbShape->name;
                        break;
                    }
    
                    $aliases = array_map('trim', explode(',', $dbShape->ALIAS ?? ''));
                    foreach ($aliases as $alias) {
                        if (strtoupper($alias) === $apiShapeUpper) {
                            $resolvedShapes[] = $dbShape->name;
                            break 2; 
                        }
                    }
                }
            }
    
            // Merge shapes from DB and Api, remove duplicates
            $finalShapes = $shapesFromDb->pluck('name')
                ->merge($resolvedShapes)
                ->unique()
                ->values(); 
    
            return response()->json([
                'success' => true,
                'message' => 'Diamond shapes fetched successfully.',
                'data' => $finalShapes,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching diamond shapes.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getFrontColors(Request $request): JsonResponse
    {
        try {
            // Fetch all active DB shapes with their aliases
            $shapesFromDb = DiamondColor::where('display_in_front', 1)->get();
    
            // Fetch shapes from the external API
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://www.onepricelab.com/api/search',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
    
            $apiData = json_decode($response, true);
            $shapesFromApi = collect($apiData['data'])->pluck('shape')->unique();
    
            $resolvedShapes = [];
    
            foreach ($shapesFromApi as $apiShape) {
                $apiShapeUpper = strtoupper(trim($apiShape));
                foreach ($shapesFromDb as $dbShape) {
                    $dbNameUpper = strtoupper(trim($dbShape->name));
    
                    if ($apiShapeUpper === $dbNameUpper) {
                        $resolvedShapes[] = $dbShape->name;
                        break;
                    }
    
                    $aliases = array_map('trim', explode(',', $dbShape->ALIAS ?? ''));
                    foreach ($aliases as $alias) {
                        if (strtoupper($alias) === $apiShapeUpper) {
                            $resolvedShapes[] = $dbShape->name;
                            break 2; 
                        }
                    }
                }
            }
    
            // Merge shapes from DB and Api, remove duplicates
            $finalShapes = $shapesFromDb->pluck('name')
                ->merge($resolvedShapes)
                ->unique()
                ->values(); 
    
            return response()->json([
                'success' => true,
                'message' => 'Diamond shapes fetched successfully.',
                'data' => $finalShapes,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching diamond shapes.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
