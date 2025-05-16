<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\DiamondMaster;
use App\Models\DiamondShape;
use App\Models\DiamondColor;
use App\Models\DiamondClarityMaster;
use App\Models\DiamondCut;
use App\Models\DiamondPolish;
use App\Models\DiamondSymmetry;
use App\Models\DiamondFlourescence;

class ImportDiamondData extends Command
{
    protected $signature = 'import:diamond-data';
    protected $description = 'Import paginated diamond data from OnePriceLab API into diamond_master table';

    public function handle()
    {
        $page = 1;
        $lastPage = 1;

        do {
            $this->info("Fetching page {$page}...");
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
                ->post('https://www.onepricelab.com/api/search', [
                    'page' => $page,
                ]);

            if ($response->failed()) {
                $this->error("Page {$page} fetch failed: " . $response->status());
                $this->error("Response: " . $response->body());
                return;
            }

            $json = $response->json();
            $lastPage = $json['last_page'] ?? 1;
            $items = $json['data'] ?? [];

            foreach ($items as $item) {
                DiamondMaster::updateOrCreate(
                    ['certificate_number' => $item['certificate']],
                    [
                        'diamond_type' => 'Standard', // or other derived type
                        'quantity' => 1,  // Set to 1 as default
                        'vendor_id' => $this->getVendorId($item['vendor_name']),
                        'vendor_stock_number' => $item['stock_id'],
                        'stock_number' => $item['sku'],
                        'shape' => $this->getId('DiamondShape', $item['shape']),
                        'carat_weight' => $item['weight']['$numberDecimal'] ?? null,
                        'color' => $this->getId('DiamondColor', $item['color']),
                        'clarity' => $this->getId('DiamondClarityMaster', $item['clarity']),
                        'cut' => $this->getId('DiamondCut', $item['cut']),
                        'polish' => $this->getId('DiamondPolish', $item['polish']),
                        'symmetry' => $this->getId('DiamondSymmetry', $item['symmetry']),
                        'fluorescence' => $this->getId('DiamondFlourescence', $item['flour']),
                        'price' => $item['cash_price'] ?? null,
                        'msrp_price' => $item['rap_price'] ?? null,
                        'price_per_carat' => $item['price_per_cts'] ?? null,
                        'image_link' => $item['image'] ?? null,
                        'cert_link' => $item['certificateurl'] ?? null,
                        'video_link' => $item['video'] ?? null,
                        'certificate_number' => $item['certificate'] ?? null,
                        'measurements' => $item['measurements'] ?? null,
                        'depth' => $item['depth'] ?? null,
                        'table_diamond' => $item['tables'] ?? null,
                        'date_added' => now(), // Current timestamp
                        // Add more fields as needed
                    ]
                );
            }

            $this->info("Page {$page} imported (" . count($items) . " items).");
            $page++;
            sleep(1);
        } while ($page <= $lastPage);

        $this->info('All pages imported successfully.');
    }

    public function sanitizeFloat($value)
    {
        return $value ? (float) $value : null;
    }

    private function getId(string $modelName, $name, string $pkColumn = 'id')
    {
        if (empty($name)) {
            return null;
        }

        $modelClass = "App\\Models\\{$modelName}";

        // Normalize the name to match DB casing (e.g., 'Round' instead of 'round')
        $name = ucfirst(strtolower(trim($name)));

        return $modelClass::where('name', $name)->value($pkColumn);
    }

    private function getVendorId($vendorName)
    {
        // Implement logic to fetch or create the vendor if needed.
        return 0; // Example: replace with actual logic
    }
}
