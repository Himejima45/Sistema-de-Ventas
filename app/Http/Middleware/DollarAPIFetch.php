<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DollarAPIFetch
{
    public function handle(Request $request, Closure $next)
    {
        $currency = Currency::orderByDesc('created_at')->first();
        $availableHours = ["10:00:00", "15:00:00"];
        $currentDate = now()->format('d-m-Y');
        $api = 'https://pydolarve.org/api/v1/dollar?monitor=bcv';

        if (is_null($currency)) {
            $response = Http::get($api);

            if ($response->successful()) {
                $data = json_decode($response->body());

                $last_update = Carbon::createFromFormat('d/m/Y, h:i A', $data->last_update);

                $currency = Currency::create([
                    'value' => $data->price,
                    'last_update' => $last_update,
                ]);
                // session()->put('fetch_status', 'success');
            } else {
                // session()->put('fetch_status', 'error');
            }
        } else {
            $response = Http::get($api);
            $currencyDate = Carbon::parse($currency->last_update)
                ->format('d-m-Y h:i a');
            $currentDate = now()
                ->timeZone('America/Caracas')
                ->format('d-m-Y h:i a');

            if ($currentDate > $currencyDate) {
                $response = Http::get($api);

                if ($response->successful()) {
                    $data = json_decode($response->body());

                    $last_update = Carbon::createFromFormat('d/m/Y, h:i A', $data->last_update);

                    $currency = Currency::create([
                        'value' => $data->price,
                        'last_update' => $last_update,
                    ]);
                    // session()->put('fetch_status', 'success');
                } else {
                    // session()->put('fetch_status', 'error');
                }
            }
        }

        return $next($request);
    }
}
