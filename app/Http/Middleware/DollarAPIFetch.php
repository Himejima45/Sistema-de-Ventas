<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DollarAPIFetch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $currency = Currency::orderByDesc('created_at')->first();
        $availableHours = ["10:00:00", "15:00:00"];

        if (in_array(now()->format('H:i:s'), $availableHours)) {
            $response = Http::get('https://ve.dolarapi.com/v1/dolares/paralelo');

            if ($response->status() === 200 && !$response->failed()) {
                $data = json_decode($response->body());
                $currency = Currency::create([
                    'value' => $data->promedio,
                ]);

                $format_date = Carbon::parse($data->fechaActualizacion)->setTimezone('America/Caracas')->format('d-m-Y H:i:s');
                $currency->created_at = $format_date;
                $currency->save();
                session()->flash('fetch_status', 'success');
            } else {
                session()->flash('fetch_status', 'error');
            }
        }

        return $next($request);
    }
}
