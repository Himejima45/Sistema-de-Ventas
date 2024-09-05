<?php

namespace App\Http\Livewire;

use App\Models\Currency;
use App\Models\Denomination;
use Carbon\Carbon;
use Closure;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CurrenciesController extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $type, $value, $search, $image, $selected_id, $pageTitle, $componentName;
    private $pagination = 5;

    public $rules = [
        'value' => [
            'required',
            'min:2',
            'max:100',
            'numeric',
        ],
    ];

    // ! TODO 10
    public $messages = [
        'value.required' => 'El monto es requerido',
        'value.min' => 'El monto debe ser al menos 1',
    ];

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Tasas';
    }


    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }



    public function render()
    {
        if (strlen($this->search) > 0)
            $data = Currency::where('type', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        else
            $data = Currency::orderBy('id', 'desc')->paginate($this->pagination);

        return view('livewire.currency.component', ['currencies' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }


    public function Edit(Currency $currency)
    {
        $record = $currency;
        $this->type = $record->type;
        $this->value = $record->value;
        $this->selected_id = $record->id;
        $this->image = null;

        $this->emit('show-modal', 'show modal!');
    }

    public function Store()
    {
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                if (Currency::whereBetween('created_at', [Carbon::now()->startOfDay()->format('Y-m-d H:m:s'), Carbon::now()->endOfDay()->format('Y-m-d H:m:s')])->exists()) {
                    $validator->errors()->add('value', 'Ya se hizo el registro del día de hoy');
                }
            });
        })->validate();
        $data = $this->validate();
        Currency::create($data);

        $this->resetUI();
        $this->emit('currency-added', 'Denominacion Registrada');
    }

    public function Update()
    {
        $data = $this->validate();
        Currency::find($this->selected_id)
            ->update($data);

        $this->resetUI();
        $this->emit('currency-updated', 'Denominacion Actualizada');
    }

    public function resetUI()
    {
        $this->type = '';
        $this->value = '';
        $this->image = null;
        $this->search = '';
        $this->selected_id = 0;
    }
    protected $listeners = [
        'Destroy'
    ];

    public function Destroy(Currency $currency)
    {
        $currency->delete();
        $this->resetUI();
        $this->emit('currency-deleted', 'Denominacion Eliminada');
    }
}
