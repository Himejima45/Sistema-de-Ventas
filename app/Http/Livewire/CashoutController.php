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

    public $fromDate, $toDate, $userid, $total, $items, $sales = [], $details, $modal;

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
        $users = User::whereHas('roles', function ($query) {
            $query->where('reference', 'employee')
                ->orWhere('reference', 'admin');
        })
            ->orderBy('name', 'asc')->get();

        $this->total = 0;
        $this->items = 0;
        $paginator = Sale::whereBetween('updated_at', [$this->fromDate, $this->toDate])
            ->where('status', 'PAID')
            ->when($this->userid > 0, function ($query) {
                $query->where('user_id', $this->userid);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        $this->sales = [
            'data' => $paginator->getCollection()
                ->transform(function ($sale, $index) use ($paginator) {
                    $arr['number'] = $index + 1 + (($paginator->currentPage() - 1) * $paginator->perPage());
                    $arr['items'] = $sale->getTotalProducts();
                    $arr['user'] = $sale->user->name;
                    $arr['client'] = $sale->client->name;
                    $arr['total'] = $sale->total;
                    $arr['status'] = $sale->status;
                    $arr['id'] = $sale->id;
                    $arr['updated_at'] = $sale->updated_at;
                    $arr['type'] = $sale->type;
                    $this->items += $sale->getTotalProducts();
                    $this->total += $sale->total;
                    return $arr;
                })->toArray(),
            'links' => $paginator->links('pagination::bootstrap-4')->render()
        ];


        return view('livewire.cashout.component', [
            'users' => $users,
        ])->extends('layouts.theme.app')
            ->section('content');
    }

    public function download()
    {
        return Excel::download(new SalesExport($this->fromDate, $this->toDate, $this->userid), 'Reporte de ventas.xlsx');
    }

    public function pdf()
    {
        $sales = Sale::whereBetween('updated_at', [$this->fromDate, $this->toDate])
            ->with('user')
            ->where('status', 'PAID')
            ->when($this->userid > 0, function ($query) {
                $query->where('user_id', $this->userid);
            })
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($sale, $index) {
                return [
                    'id' => ++$index,
                    'name' => $sale->user->name,
                    'date' => $sale->updated_at->format('h:i:s d-m-Y a'),
                    'total' => $sale->total,
                    'items' => $sale->getTotalProducts(),
                    'type' => $sale->type,
                    'status' => $sale->status,
                    'client' => $sale->client->name
                ];
            });

        $start = $this->fromDate;
        $end = $this->toDate;
        $budget = false;

        return response()->streamDownload(function () use ($sales, $start, $end, $budget) {
            $pdf = App::make('dompdf.wrapper');
            $start = Carbon::parse($start)->translatedFormat('D d, F Y - h:i:s a');
            $end = Carbon::parse($end)->translatedFormat('D d, F Y - h:i:s a');
            $pdf->loadView('pdf', compact('sales', 'start', 'end', 'budget'));
            echo $pdf->stream();
        }, 'Reporte de ventas.pdf');
    }

    // ! TODO 11
    public function viewDetails(Sale $sale)
    {
        $this->details = Sale::join('sale_details as d', 'd.sale_id', 'sales.id')
            ->join('products as p', 'p.id', 'd.product_id')
            ->select('d.sale_id', 'p.name as product', 'd.quantity', 'd.price')
            ->where('sales.id', $sale->id)
            ->get();

        $this->emit('show-modal', 'open modal');
    }
}
