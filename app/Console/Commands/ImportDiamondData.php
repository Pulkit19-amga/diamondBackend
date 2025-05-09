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
                        'table_diamond' => $item['tables'] ?? null,
                        'depth' => $item['depth'] ?? null,
                        'measurement_l' => $item['length'] ?? null,
                        'measurement_w' => $item['width'] ?? null,
                        'measurement_h' => $item['height'] ?? null,
                        'measurements' => $item['measurements'] ?? null,
                        'certificate_number' => $item['certificate'] ?? null,
                        'cert_link' => $item['certificateurl'] ?? null,
                        'image_link' => $item['image'] ?? null,
                        'video_link' => $item['video'] ?? null,
                        'msrp_price' => $item['rap_price'] ?? null,

                        'shape' => $this->getId('DiamondShape', $item['shape']) ?? 0,
                        'carat_weight' => $item['weight']['$numberDecimal'] ?? null,
                        'color' => $this->getId('DiamondColor', $item['color']) ?? 0,
                        'clarity' => $this->getId('DiamondClarityMaster', $item['clarity']) ?? 0,
                        'cut' => $this->getId('DiamondCut', $item['cut']) ?? 0,
                        'polish' => $this->getId('DiamondPolish', $item['polish']) ?? 0,
                        'symmetry' => $this->getId('DiamondSymmetry', $item['symmetry']) ?? 0,
                        'fluorescence' => $this->getId('DiamondFlourescence', $item['flour']) ?? 0,

                        'price' => $item['cash_price'] ?? null,
                        'locationid' => $item['Location'] ?? null,
                        'stock_number' => $item['sku'] ?? null,
                        'price_per_carat' => $item['price_per_cts'] ?? null,
                        'cost_discount' => $item['cost_discount'] ?? null,
                    ]
                );
            }

            $this->info("Page {$page} imported (" . count($items) . " items).");
            $page++;
            sleep(1);
        } while ($page <= $lastPage);

        $this->info('All pages imported successfully.');
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

}
