<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DollarAPIFetch
{
    const API_URL = "https://api.dolarvzla.com/public/exchange-rate";
    public function handle(Request $request, Closure $next)
    {
        if (!$this->isConnected()) {
            Log::warning('DNS resolution failed.');
            return $next($request);
        }


        $currency = Currency::orderByDesc('created_at')->first();

        if (is_null($currency) || $this->isCurrencyOutdated($currency)) {
            $response = $this->fetchCurrencyData(DollarAPIFetch::API_URL);

            if ($response) {
                $data = json_decode($response->body())->current;
                $last_update = Carbon::createFromFormat('Y-m-d', $data->date);

                Currency::create([
                    'value' => $data->usd,
                    'last_update' => $last_update,
                ]);
            } else {
                Log::error('Failed to fetch currency data from API.');
            }
        }

        return $next($request);
    }

    private function isConnected()
    {
        $domain = parse_url(DollarAPIFetch::API_URL, PHP_URL_HOST);

        // Check DNS resolution
        if (!checkdnsrr($domain, "A")) {
            Log::warning("DNS resolution failed for: {$domain}");
            return false;
        }

        // Check if we can connect to the API endpoint
        try {
            $response = Http::timeout(5)->get(DollarAPIFetch::API_URL);
            return $response->successful();
        } catch (\Exception $e) {
            Log::warning("API connection failed: " . $e->getMessage());
            return false;
        }
    }

    private function isCurrencyOutdated($currency)
    {
        return Carbon::parse($currency->last_update)
            ->lt(now()->timeZone('America/Caracas'));
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
