<?php

namespace App\Http\Livewire;

use App\Exports\SalesExport;
use App\Models\Currency;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class BudgetsController extends Component
{
    public $search = '', $selected_id = 0, $products = [], $total = 0, $iva = 0, $subtotal = 0, $cash = 0, $bs = 0, $change = 0, $currency = 0, $fromDate, $toDate;
    protected $listeners = ['products', 'edit', 'update', 'pdf', 'download'];

    public $messages = [
        'bs.required' => 'Debe colocar un monto',
        'bs.min' => 'Debe colocar un monto superior a 0',
        'bs.numeric' => 'Debe ser un número',
        'cash.required' => 'Debe colocar un monto',
        'cash.min' => 'Debe colocar un monto superior a 0',
        'cash.numeric' => 'Debe ser un número',
        'change.required' => 'Debe colocar un monto',
        'change.min' => 'Debe colocar un monto superior a 0',
        'change.numeric' => 'Debe ser un número',
    ];

    public function rules()
    {
        return [
            'bs' => $this->cash > 0 ? 'nullable' : 'required|min:1|numeric',
            'cash' => $this->bs > 0 ? 'nullable' : 'required|min:1|numeric',
            'change' => $this->change > 0 ? 'required|min:1|numeric' : 'nullable',
        ];
    }

    public function edit(Sale $record)
    {
        $this->selected_id = $record->id;
        $this->currency = Currency::latest()->first()->value;
        $this->cash = $record->cash;
        $this->bs = $record->bs;
        $this->change = $record->change;
        $this->get_products($record);
        $this->emit('show-modal');
    }

    public function update()
    {
        $this->validate();
        $record = Sale::find($this->selected_id);
        $total_payed = $this->total <= $this->cash + ($this->bs / $this->currency) - $this->change;

        $new_bs = floatval($this->bs ?? 0);
        $new_cash = floatval($this->cash ?? 0);
        $new_total = floatval($this->total ?? 0);
        $bs_to_usd = $new_bs > 0 ? round($new_bs / $this->currency, 2) : 0;
        $total_to_pay = round($new_total - $new_cash - $bs_to_usd, 2);
        round($new_total - $new_cash - $bs_to_usd, 2);
        $record->update([
            'status' => $total_payed ? 'PAID' : 'PENDING',
            'type' => 'SALE',
            'cash' => $this->cash,
            'bs' => $this->bs,
            'change' => $total_to_pay,
        ]);

        $this->bs = 0;
        $this->cash = 0;
        $this->change = 0;
        $this->emit('close-modal');
    }

    public function products(Sale $record)
    {
        $this->get_products($record);
        $this->emit('show-products');
    }

    public function get_products(Sale $record)
    {
        $this->total = 0;
        $this->subtotal = 0;
        $this->iva = 0;
        $this->products = $record->products->map(function ($detail) {
            $detail->product['quantity'] = $detail->quantity;
            $prev_total = $detail->quantity * $detail->product->price;
            $this->subtotal += $prev_total;
            $this->iva += $prev_total * 0.16;

            return $detail->product;
        });

        $this->total = $this->subtotal + $this->iva;
    }

    public function download()
    {
        return Excel::download(new SalesExport($this->fromDate, $this->toDate, 0, true), 'Reporte de cuentas por cobrar.xlsx');
    }

    public function pdf()
    {
        $sales = Sale::whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->with('user')
            ->where('type', 'BUDGET')
            ->get()
            ->map(function ($sale, $index) {
                return [
                    'id' => ++$index,
                    'name' => $sale->user->name,
                    'date' => $sale->created_at->format('H:i:s d-m-Y'),
                    'total' => $sale->total,
                    'items' => $sale->getTotalProducts(),
                    'client' => $sale->client->name
                ];
            });

        $start = $this->fromDate;
        $end = $this->toDate;
        $budget = true;

        return response()->streamDownload(function () use ($sales, $start, $end, $budget) {
            $pdf = App::make('dompdf.wrapper');
            $start = Carbon::parse($start)->translatedFormat('D d, F Y - h:i:s a');
            $end = Carbon::parse($end)->translatedFormat('D d, F Y - h:i:s a');
            $pdf->loadView('pdf', compact('sales', 'start', 'end', 'budget'));
            echo $pdf->stream();
        }, 'Reporte de cuentas por cobrar.pdf');
    }

    public function render()
    {
        $this->fromDate = now()->startOfDay();
        $this->toDate = now()->endOfDay();

        $budgets = Sale::where('type', 'BUDGET')
            ->whereHas('client', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(20);

        return view('livewire.budgets', [
            'budgets' => $budgets
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
