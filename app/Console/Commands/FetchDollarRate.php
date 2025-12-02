<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchDollarRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-dollar-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the latest dollar exchange rate from the API';

    const API_URL = "https://api.dolarvzla.com/public/exchange-rate";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->isConnected()) {
            $this->warn('DNS resolution failed or API unreachable.');
            Log::warning('DNS resolution failed or API unreachable during scheduled fetch.');
            return 1;
        }

        $currency = Currency::orderByDesc('created_at')->first();

        if (is_null($currency) || $this->isCurrencyOutdated($currency)) {
            $this->info('Fetching new currency data...');
            $response = $this->fetchCurrencyData(self::API_URL);

            if ($response) {
                $data = json_decode($response->body())->current;
                $last_update = Carbon::createFromFormat('Y-m-d', $data->date);

                // Check if we already have this update to avoid duplicates if run multiple times
                $existing = Currency::where('last_update', $last_update)->first();
                
                if (!$existing) {
                    Currency::create([
                        'value' => $data->usd,
                        'last_update' => $last_update,
                    ]);
                    $this->info("Currency updated: {$data->usd} (Date: {$data->date})");
                    Log::info("Currency updated via schedule: {$data->usd}");
                } else {
                     $this->info("Currency data for {$data->date} already exists.");
                }
            } else {
                $this->error('Failed to fetch currency data from API.');
                Log::error('Failed to fetch currency data from API during scheduled fetch.');
                return 1;
            }
        } else {
            $this->info('Currency is up to date.');
        }

        return 0;
    }

    private function isConnected()
    {
        $domain = parse_url(self::API_URL, PHP_URL_HOST);

        // Check DNS resolution
        if (!checkdnsrr($domain, "A")) {
            return false;
        }

        // Check if we can connect to the API endpoint
        try {
            $response = Http::timeout(5)->get(self::API_URL);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function isCurrencyOutdated($currency)
    {
        return Carbon::parse($currency->last_update)
            ->lt(now()->timeZone('America/Caracas')->startOfDay());
    }

    private function fetchCurrencyData($api)
    {
        try {
            $response = Http::timeout(5)->get($api);
            if ($response->successful()) {
                return $response;
            }
        } catch (\Exception $e) {
            Log::error('cURL error: ' . $e->getMessage());
        }

        return null;
    }
}
