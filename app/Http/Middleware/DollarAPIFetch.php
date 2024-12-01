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
        $api = 'https://ve.dolarapi.com/v1/dolares/paralelo';

        if (is_null($currency)) {
            $response = Http::get($api);

            if ($response->successful()) {
                $data = json_decode($response->body());
                $currency = Currency::create([
                    'value' => $data->promedio,
                    'created_at' => Carbon::parse($data->fechaActualizacion)->setTimezone('America/Caracas'),
                ]);
                // session()->put('fetch_status', 'success');
            } else {
                // session()->put('fetch_status', 'error');
            }
        } else {
            $currencyDate = Carbon::parse($currency->created_at)->setTimezone('America/Caracas');

            if (
                $currencyDate->format('d-m-Y') === $currentDate &&
                !in_array($currencyDate->format('H:i:s'), $availableHours)
            ) {
                $response = Http::get($api);

                if ($response->successful()) {
                    $data = json_decode($response->body());
                    $currency->value = $data->promedio;
                    $currency->created_at = Carbon::parse($data->fechaActualizacion)->setTimezone('America/Caracas');
                    $currency->save();
                    // session()->put('fetch_status', 'success');
                } else {
                    // session()->put('fetch_status', 'error');
                }
            }
        }

        return $next($request);
    }
}
