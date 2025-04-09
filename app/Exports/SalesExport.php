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
    public function __construct($start, $end, int $user_id = 0, $budget = false)
    {
        $this->start = $start ?: now()->format('Y-m-d');
        $this->end = $end ?: now()->format('Y-m-d');
        $this->user_id = $user_id;
        $this->budget = $budget;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $headers = $this->budget
            ? ['Nro Venta', 'Total', 'Items', 'Tipo', 'Empleado', 'Cliente', 'Hora', 'Fecha']
            : ['Nro Venta', 'Total', 'Items', 'Tipo', 'Estado', 'Empleado', 'Cliente', 'Hora', 'Fecha'];

        $salesData = Sale::where(function ($query) {
            if ($this->user_id > 0) {
                $query->where('user_id', $this->user_id);
            }

            if ($this->budget) {
                $query->where('type', 'BUDGET');
            }

            if (!$this->budget) {
                $query->where('status', 'PAID');
            }

            $query->whereBetween('updated_at', [$this->start . ' 00:00:00', $this->end . ' 23:59:59']);
        })
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($sale, $index) {
                if ($this->budget) {
                    return [
                        ++$index,
                        number_format($sale->total, 2),
                        $sale->getTotalProducts(),
                        $sale->type === 'SALE' ? 'VENTA' : 'CARRITO',
                        $sale->user->name,
                        $sale->client->name,
                        $sale->created_at->format('h:i:s a'),
                        $sale->created_at->format('d-m-Y')
                    ];
                }

                return [
                    ++$index,
                    number_format($sale->total, 2),
                    $sale->getTotalProducts(),
                    $sale->type === 'SALE' ? 'VENTA' : 'CARRITO',
                    $sale->status === 'PAID' ? 'Pagado' : ($sale->status === 'PENDING' ? 'Pendiente' : 'Cancelado'),
                    $sale->user->name,
                    $sale->client->name,
                    $sale->created_at->format('h:i:s a'),
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
