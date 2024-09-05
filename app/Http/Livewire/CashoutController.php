<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Sale;
use Carbon\Carbon;

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
            'users' => User::orderBY('name', 'asc')->get()
        ])->extends('layouts.theme.app')
            ->section('content');
    }

    public function Consultar()
    {
        // ! TODO 7
        $fi = Carbon::parse($this->fromDate)->addDays(1)->format('Y-m-d') . ' 00:00:00';
        $ff = Carbon::parse($this->toDate)->addDays(1)->format('Y-m-d') . ' 23:59:59';

        $this->fromDate = $fi;
        $this->toDate = $ff;

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
