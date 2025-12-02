<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportProductsFromJson extends Command
{
    protected $signature = 'import:products-json {file=products.json} {--chunk=1000}';
    protected $description = 'Import products from JSON file to database with performance optimization';

    private $defaultCategoryId = 1;
    private $defaultProviderId = 1;

    public function handle()
    {
        $fileName = $this->argument('file');
        $chunkSize = (int) $this->option('chunk');

        if (!file_exists($fileName)) {
            $this->error("File not found: {$fileName}");
            return 1;
        }

        $this->info("Reading JSON file: {$fileName}");
        $startTime = microtime(true);

        try {
            // Read and decode JSON
            $jsonContent = file_get_contents($fileName);
            $products = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('Invalid JSON format: ' . json_last_error_msg());
                return 1;
            }

            $totalProducts = count($products);
            $this->info("Found {$totalProducts} products in JSON file");

            if ($totalProducts === 0) {
                $this->warn('No products found in JSON file');
                return 0;
            }

            // Start transaction for data integrity
            DB::beginTransaction();

            // Disable foreign key checks for faster import (MySQL specific)
            if (DB::connection()) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            }

            // Truncate table if needed (optional)
            // DB::table('products')->truncate();

            $bar = $this->output->createProgressBar($totalProducts);
            $bar->start();

            $chunks = array_chunk($products, $chunkSize);
            $importedCount = 0;
            $failedCount = 0;

            foreach ($chunks as $chunk) {
                $batchData = [];

                foreach ($chunk as $product) {
                    try {
                        $batchData[] = $this->prepareProductData($product);
                    } catch (\Exception $e) {
                        Log::error('Failed to prepare product: ' . $e->getMessage(), ['product' => $product]);
                        $failedCount++;
                        continue;
                    }
                }

                if (!empty($batchData)) {
                    try {
                        // Use insert ignore for duplicates or update for existing
                        DB::table('products')->insertOrIgnore($batchData);
                        $importedCount += count($batchData);
                    } catch (\Exception $e) {
                        Log::error('Batch insert failed: ' . $e->getMessage());
                        $failedCount += count($batchData);
                    }
                }

                $bar->advance(count($chunk));

                // Clear memory
                unset($batchData);
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
            }

            // Re-enable foreign key checks
            if (DB::connection()) {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            DB::commit();

            $bar->finish();
            $this->newLine();

            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            $this->info("Import completed!");
            $this->info("Execution time: {$executionTime} seconds");
            $this->info("Successfully imported: {$importedCount} products");
            $this->info("Failed: {$failedCount} products");
            $this->info("Chunk size used: {$chunkSize}");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            Log::error('Import failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return 1;
        }

        return 0;
    }

    private function prepareProductData(array $product): array
    {
        // Clean and prepare data
        $codigo = $product['Codigo'] ?? null;
        $descripcion = $this->cleanString($product['Descripcion'] ?? '');
        $costo = $this->extractPrice($product['Precio Costo'] ?? '0');
        $precio = $this->extractPrice($product['Precio Venta'] ?? '0');
        $inventario = $this->parseInventory($product['Inventario'] ?? '0');
        $minStock = intval($product['Inv. Minimo'] ?? 1);
        $departamento = $this->cleanString($product['Departamento'] ?? '');

        // Validate required fields
        if (empty($codigo) || empty($descripcion)) {
            throw new \Exception("Missing required fields for product: " . json_encode($product));
        }

        $code = uniqid();
        $originalPath = "/images/$codigo.png";
        $newPath = "/public/products/{$code}.png";
        $image = false;
        if (Storage::exists($originalPath)) {
            $image = true;
            Storage::move($originalPath, $newPath);
        }

        return [
            'name' => substr($descripcion, 0, 255),
            'barcode' => (string) $codigo, // Using Codigo as barcode
            'cost' => $costo,
            'price' => $precio,
            'warranty' => 1, // Default warranty
            'stock' => $inventario,
            'min_stock' => max(1, $minStock),
            'image' => $image ? "{$code}.png" : null, // No image in JSON
            'category_id' => $this->getCategoryId($departamento),
            'provider_id' => $this->defaultProviderId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function extractPrice(string $priceString): float
    {
        // Remove currency symbols and commas, keep only numbers and decimal point
        $clean = preg_replace('/[^0-9.]/', '', $priceString);
        return floatval($clean);
    }

    private function parseInventory($inventory): int
    {
        if ($inventory === 'N/A' || $inventory === null || $inventory === '') {
            return 0;
        }

        if (is_numeric($inventory)) {
            return intval($inventory);
        }

        // Try to extract numbers from string
        if (preg_match('/(\d+)/', (string) $inventory, $matches)) {
            return intval($matches[1]);
        }

        return 0;
    }

    private function cleanString(string $string): string
    {
        // Remove extra whitespace and trim
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    private function getCategoryId(string $departamento): int
    {
        // You can implement your category mapping logic here
        // For now, returning default category ID
        // Consider creating categories based on Departamento values
        return $this->defaultCategoryId;

        // Example of dynamic category creation:
        // $category = \App\Models\Category::firstOrCreate(
        //     ['name' => $departamento],
        //     ['description' => $departamento]
        // );
        // return $category->id;
    }
}