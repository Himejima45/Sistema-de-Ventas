<?php

namespace App\Http\Livewire;

use App\Exports\SalesExport;
use Livewire\Component;
use App\Models\User;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;

class CashoutController extends Component
{

    public $fromDate, $toDate, $userid, $total, $items, $sales, $details, $modal;

    protected $listeners = ['viewDetails'];

    public function mount()
    {
        $this->userid = 0;
        $this->total = null;
        $this->sales = [];
        $this->details = [];
        $this->fromDate = now()->startOfDay();
        $this->toDate = now()->endOfDay();
        $this->modal = false;
    }
    public function render()
    {
        $users = User::orderBy('name', 'asc')->get();
        $this->userid = $users[0]->id;

        $this->sales = Sale::where('type', 'SALE')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->where('status', 'PAID')
            ->where('user_id', $this->userid)
            ->get();

        $this->total = $this->sales ? $this->sales->sum('total') : 0;
        $this->items = $this->sales ? $this->sales->sum('items') : 0;

        return view('livewire.cashout.component', [
            'users' => $users,
        ])->extends('layouts.theme.app')
            ->section('content');
    }

    public function download()
    {
        return Excel::download(new SalesExport($this->fromDate, $this->toDate), 'ventas.xlsx');
    }

    public function pdf()
    {
        $sales = Sale::whereBetween('created_at', [$this->fromDate, $this->toDate])->with('user')->get()->map(function ($sale) {
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

    // ! TODO 11
    public function viewDetails(Sale $sale)
    {
        $this->details = Sale::join('sale_details as d', 'd.sale_id', 'sales.id')
            ->join('products as p', 'p.id', 'd.product_id')
            ->select('d.sale_id', 'p.name as product', 'd.quantity', 'd.price')
            ->where('sales.id', $sale->id)
            ->get();

        // dd($this->sales);
        $this->emit('show-modal', 'open modal');
    }
}
