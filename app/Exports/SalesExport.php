<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SalesExport implements FromCollection, WithDrawings, WithCustomStartCell
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

        \Log::info('headers', $headers);
        $salesData = Sale::where(function ($query) {
            if ($this->user_id > 0) {
                \Log::info('user id greather than 0');
                \Log::info($this->user_id);
                $query->where('user_id', $this->user_id);
            }

            if ($this->budget) {
                \Log::info('is budget');
                $query->where('type', 'BUDGET');
                $query->where('status', 'PENDING');
            }

            if (!$this->budget) {
                \Log::info('not budget');
                $query->where('status', 'PAID');
            }

            \Log::info('time', [$this->start . ' 00:00:00', $this->end . ' 23:59:59']);
            $query->whereBetween('updated_at', [$this->start . ' 00:00:00', $this->end . ' 23:59:59']);
        })
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($sale, $index) {
                \Log::info($sale);

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
            \Log::info('data', ['value' => $sale]);
            $result->push($sale);
        }

        return $result;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path() . '/assets/img/document_bg.png');
        $drawing->setHeight(95);
        $drawing->setCoordinates('A1');

        $drawing1 = new Drawing();
        $drawing1->setName('Logo');
        $drawing1->setDescription('This is my logo');
        $drawing1->setPath(public_path() . '/assets/img/Logo2.png');
        $drawing1->setHeight(45);
        $drawing1->setCoordinates('D2');

        return [$drawing, $drawing1];
    }
}
