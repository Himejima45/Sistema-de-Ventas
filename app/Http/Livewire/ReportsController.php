<?php

namespace App\Http\Livewire;

use App\Exports\SalesExport;
use Livewire\Component;
use App\Models\User;
use App\Models\Sale;
use App\Models\SaleDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Component
{

    public $componentName, $data, $details, $sumDetails, $countDetails, $reportType, $userId, $dateFrom, $dateTo, $saleId;

    protected $fillable = ['getDetails'];

    public function mount()
    {
        $this->componentName = 'Reportes de Ventas';
        $this->data = [];
        $this->details = [];
        $this->sumDetails = 0;
        $this->countDetails = 0;
        $this->reportType = 0;
        $this->userId = 0;
        $this->saleId = 0;
    }
    public function render()
    {
        $this->SalesByDate();

        $employees = User::whereHas('roles', function ($query) {
            $query->where('name', 'Employee')
                ->orWhere('name', 'Admin');
        })
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.reports.component', [
            'users' => $employees
        ])->extends('layouts.theme.app')
            ->section('content');
    }

    public function SalesByDate()
    {

        if ($this->reportType == 0) {
            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';
        } else {
            $from = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($this->dateTo)->format('Y-m-d') . ' 23:59:59';
        }

        if ($this->reportType == 1 && ($this->dateFrom == '' || $this->dateTo == '')) {
            $this->data = [];
            return;
        }

        if ($this->userId == 0) {
            $this->data = Sale::join('users as c', 'c.id', 'sales.client_id')
                ->select('sales.*')
                ->whereBetween('sales.created_at', [$from, $to])
                ->get()
                ->map(function ($sale, $index) {
                    $sale['number']  = ++$index;
                    return $sale;
                });
        } else {
            $this->data = Sale::join('users as c', 'c.id', 'sales.client_id')
                ->select('sales.*')
                ->whereBetween('sales.created_at', [$from, $to])
                ->where('sales.user_id', $this->userId)
                ->get()
                ->map(function ($sale, $index) {
                    $sale['number']  = ++$index;
                    return $sale;
                });
        }
    }

    public function getDetails($saleId)
    {
        $this->details = SaleDetails::join('products as p', 'p.id', 'sale_details.product_id')
            ->select('sale_details.id', 'sale_details.price', 'sale_details.quantity', 'p.name as product')
            ->where('sale_details.sale_id', $saleId)
            ->get();

        $suma = $this->details->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $this->sumDetails = $suma;
        $this->countDetails = $this->details->sum('quantity');
        $this->saleId = $saleId;
        $this->emit('show-modal', 'details loaded');
    }

    public function excel()
    {
        return Excel::download(new SalesExport($this->dateFrom, $this->dateTo, $this->userId), 'Reporte de ventas.xlsx');
    }

    public function pdf()
    {
        $sales = Sale::where(function ($query) {
            if ($this->userId > 0) {
                $query->where('user_id', $this->userId);
            }

            $query->whereBetween('created_at', [$this->dateFrom  . ' 00:00:00', $this->dateTo  . ' 23:59:59']);
        })
            ->get()
            ->map(function ($sale, $index) {
                $total = 0;
                foreach ($sale->products as $product) {
                    $total += $product->price * $product->quantity;
                }

                return [
                    'id' => ++$index,
                    'name' => $sale->user->name,
                    'client' => $sale->client->name,
                    'date' => $sale->created_at->format('H:i:s d-m-Y'),
                    'total' => $total,
                    'items' => $sale->getTotalProducts(),
                    'status' => $sale->status
                ];
            });

        $start = $this->dateFrom;
        $end = $this->dateTo;

        return response()->streamDownload(function () use ($sales, $start, $end) {
            $pdf = App::make('dompdf.wrapper');
            $start = Carbon::parse($start)->translatedFormat('D d, F Y - h:i:s a');
            $end = Carbon::parse($end)->translatedFormat('D d, F Y - h:i:s a');
            $pdf->loadView('pdf', compact('sales', 'start', 'end'));
            echo $pdf->stream();
        }, 'Reporte de ventas.pdf');
    }
}
