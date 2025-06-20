<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = Carbon::now();

        $categories = [
            'EARRINGS' => [
                'Studs', 'Hoops', 'Halo', 'Fashion', 'Ear Cuffs', 'Stackable', 'Gemstone', 'Luxe', 'Ready to Ship', 'Create Your Own', 'SHOP ALL',
            ],
            'BRACELETS' => [
                'Tennis', 'Mixed Shape', 'Bangle', 'Bolo', 'Fashion', 'Luxe', 'Ready to Ship', 'SHOP ALL',
            ],
            'RINGS' => [
                'Anniversary', 'Eternity', 'Stackable', 'Fashion', 'Gemstone', 'Luxe', 'Ready to Ship', 'Create Your Own', 'SHOP ALL',
            ],
            'NECKLACES' => [
                'Halo', 'Solitaire', 'Tennis', 'Fashion', 'Gemstone', 'Luxe', 'Ready to Ship', 'Create Your Own', 'SHOP ALL',
            ],
        ];

        foreach ($categories as $mainCategory => $subcategories) {
            // Insert parent category
            $parentId = DB::table('categories')->insertGetId([
                'category_name' => $mainCategory,
                'parent_id' => null,
                'category_status' => 1,
                'deleted' => false,
                'category_date_added' => $timestamp,
                'category_date_modified' => $timestamp,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            // Insert subcategories
            foreach ($subcategories as $subcategory) {
                DB::table('categories')->insert([
                    'category_name' => $subcategory,
                    'parent_id' => $parentId,
                    'category_status' => 1,
                    'deleted' => false,
                    'category_date_added' => $timestamp,
                    'category_date_modified' => $timestamp,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }
        }
    }
}
