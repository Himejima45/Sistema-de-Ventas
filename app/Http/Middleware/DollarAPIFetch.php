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
    public function handle(Request $request, Closure $next)
    {
        if (!$this->isConnected()) {
            Log::warning('DNS resolution failed.');
            return $next($request);
        }

        $currency = Currency::orderByDesc('created_at')->first();
        $api = 'https://pydolarve.org/api/v1/dollar?monitor=bcv';

        if (is_null($currency) || $this->isCurrencyOutdated($currency)) {
            $response = $this->fetchCurrencyData($api);

            if ($response) {
                $data = json_decode($response->body());
                $last_update = Carbon::createFromFormat('d/m/Y, h:i A', $data->last_update);

                Currency::create([
                    'value' => $data->price,
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
        return checkdnsrr("pydolarve.org", "A");
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
