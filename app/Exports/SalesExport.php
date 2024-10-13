<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesExport implements FromCollection
{
    protected $start;
    protected $end;
    public function __construct(String  $start, String $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Define headers
        $headers = ['Nro Venta', 'Vendedor', 'Hora', 'Fecha'];

        // Fetch sales data and transform it
        $salesData = Sale::whereBetween('created_at', [$this->start  . '00:00:00', $this->end  . ' 23:59:59'])->get()->map(function ($sale) {
            return [
                $sale->id,
                $sale->user->name,
                $sale->created_at->format('H:i:s'),
                $sale->created_at->format('d-m-Y')
            ];
        });

        // Create a new collection with headers and data
        $result = new Collection();
        $result->push($headers); // Add headers as the first row
        foreach ($salesData as $sale) {
            $result->push($sale); // Add each sale data
        }

        return $result;
    }
}
