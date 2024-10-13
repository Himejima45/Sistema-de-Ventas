<?php

namespace App\Http\Livewire;

use App\Exports\SalesExport;
use Livewire\Component;
use App\Models\User;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;

class CashoutController extends Component
{

    public $fromDate, $toDate, $userid, $total, $items, $sales, $details;

    public function mount()
    {
        $this->fromDate = null;
        $this->toDate = null;
        $this->userid = 0;
        $this->total = null;
        $this->sales = [];
        $this->details = [];
    }
    public function render()
    {
        return view('livewire.cashout.component', [
            'users' => User::orderBY('name', 'asc')->get(),
        ])->extends('layouts.theme.app')
            ->section('content');
    }

    public function download()
    {
        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';

        $this->fromDate = $fi;
        $this->toDate = $ff;

        return Excel::download(new SalesExport($this->fromDate, $this->toDate), 'ventas.xlsx');
    }

    public function pdf()
    {
        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';

        $this->fromDate = $fi;
        $this->toDate = $ff;
        $sales = Sale::whereBetween('created_at', [$this->fromDate . '00:00:00', $this->toDate . ' 23:59:59'])->with('user')->get()->map(function ($sale) {
            $total = 0;
            foreach ($sale->products as $product) {
                $total += $product->price * $product->quantity;
            }

            return [
                'id' => $sale->id,
                'name' => $sale->user->name,
                'date' => $sale->created_at->format('H:i:s d-m-Y'),
                'total' => $total,
                'items' => $sale->getTotalProducts(),
                'status' => $sale->status
            ];
        });

        $start = $this->fromDate;
        $end = $this->toDate;

        return response()->streamDownload(function () use ($sales, $start, $end) {
            $pdf = App::make('dompdf.wrapper');
            $start = Carbon::parse($start)->translatedFormat('D d, F Y - h:i:s a');
            $end = Carbon::parse($end)->translatedFormat('D d, F Y - h:i:s a');
            $pdf->loadView('pdf', compact('sales', 'start', 'end'));
            echo $pdf->stream();
        }, 'test.pdf');
    }

    public function Consultar()
    {
        // ! TODO 7
        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';

        $this->fromDate = $fi;
        $this->toDate = $ff;
        // dd($this->fromDate, $this->toDate);

        // ! TODO 6
        $this->sales = Sale::whereBetween('created_at', [$fi, $ff])
            ->where('status', 'PAID')
            ->where('user_id', $this->userid)
            ->get();

        $this->total = $this->sales ? $this->sales->sum('total') : 0;
        $this->items = $this->sales ? $this->sales->sum('items') : 0;
    }

    // ! TODO 11
    public function viewDetails(Sale $sale)
    {
        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . '00:00:00';
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . '23:59:59';

        $this->sales = Sale::join('sale_details as d', 'd.sale_id', 'sales.id')
            ->join('products as p', 'p.id', 'd.product_id')
            ->select('d.sale_id', 'p.name as product', 'd.quantity', 'd.price')
            ->where('sales.id', $sale->id)
            ->get();

        $this->emit('show-modal', 'open modal');
    }

    // ! TODO 8
    public function Print()
    {
        //
    }
}
