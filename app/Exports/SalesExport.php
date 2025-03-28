<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesExport implements FromCollection
{
    protected $start;
    protected $end;
    protected $user_id;
    protected $budget;
    public function __construct(String  $start, String $end, int $user_id = 0, $budget = false)
    {
        $this->start = $start;
        $this->end = $end;
        $this->user_id = $user_id;
        $this->budget = $budget;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $headers = $this->budget
            ? ['Nro Venta', 'Total', 'Items', 'Cliente', 'Empleado', 'Hora', 'Fecha']
            : ['Nro Venta', 'Total', 'Items', 'Estado', 'Cliente', 'Empleado', 'Hora', 'Fecha'];

        $salesData = Sale::where(function ($query) {
            if ($this->user_id > 0) {
                $query->where('user_id', $this->user_id);
            }

            $query->whereBetween('created_at', [$this->start  . ' 00:00:00', $this->end  . ' 23:59:59']);
        })
            ->where('type', $this->budget ? 'BUDGET' : 'SALE')
            ->get()
            ->map(function ($sale, $index) {
                if ($this->budget) {
                    return [
                        ++$index,
                        number_format($sale->total, 2),
                        $sale->getTotalProducts(),
                        $sale->client->name,
                        $sale->user->name,
                        $sale->created_at->format('H:i:s'),
                        $sale->created_at->format('d-m-Y')
                    ];
                }

                return [
                    ++$index,
                    number_format($sale->total, 2),
                    $sale->getTotalProducts(),
                    $sale->status === 'PAID' ? 'Pagado' : ($sale->status === 'PENDING' ? 'Pendiente' : 'Cancelado'),
                    $sale->client->name,
                    $sale->user->name,
                    $sale->created_at->format('H:i:s'),
                    $sale->created_at->format('d-m-Y')
                ];
            });

        $result = new Collection();
        $result->push($headers);
        foreach ($salesData as $sale) {
            $result->push($sale);
        }

        return $result;
    }
}
