<?php

namespace App\Http\Livewire;

use App\Models\Provider;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProviderController extends Component
{
    use WithPagination;

    public $name, $address, $phone, $rif, $document, $search, $selected_id, $pageTitle, $componentName;
    private $pagination = 20;
    public $rules = [
        'name' => [
            'required',
            'min:2',
            'max:40',
            'regex:/^(?=.*[a-zA-Z])(?=\S*\s?\S*$)(?!.*\s{2,}).*$/',
            'unique:providers,name'
        ],
        'phone' => ['required', 'numeric', 'digits:11', 'unique:providers,phone'],
        'address' => ['required', 'min:2', 'max:100', 'regex:/^(?=.*[a-zA-Z])(?=\S*\s?\S*$)(?!.*\s{2,}).*$/'],
        'rif' => ['required', 'numeric', 'digits:10', 'unique:providers,rif'],
        'document' => ['required', "in:J,V,G,E"]
    ];

    // ! TODO 10
    public $messages = [
        'name.required' => 'El monto es requerido',
        'name.min' => 'El monto debe ser al menos 1',
    ];

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Proveedores';
    }


    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }



    public function render()
    {
        if (strlen($this->search) > 0)
            $data = Provider::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        else
            $data = Provider::orderBy('id', 'desc')->paginate($this->pagination);

        foreach ($data as $provider) {
            $code = substr($provider->phone, 0, 4);
            $phone = substr($provider->phone, 4);
            $provider->phone = "{$code}-{$phone}";

            $rif = $provider->rif;
            $rest = substr($rif, 0, Str::length($rif) - 2);
            $last_value = substr($rif, Str::length($rif) - 1);
            $provider->rif = "{$provider->document}-{$rest}-{$last_value}";
        }

        return view('livewire.providers.component', ['providers' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }


    public function Edit(Provider $provider)
    {
        $record = $provider;
        $this->name = $record->name;
        $this->phone = $record->phone;
        $this->rif = $record->rif;
        $this->address = $record->address;
        $this->selected_id = $record->id;
        $this->document = $record->document;

        $this->emit('show-modal', 'show modal!');
    }

    public function Store()
    {
        $data = $this->validate();
        Provider::create($data);

        $this->resetUI();
        $this->emit('provider-added', 'Denominacion Registrada');
    }

    public function Update()
    {
        $rules = array_merge(
            $this->rules,
            [
                'name' => "required|min:2|max:40|regex:/^(?=.*[a-zA-Z])(?=\S*\s?\S*$)(?!.*\s{2,}).*$/|unique:providers,name,{$this->selected_id}",
                'rif' => "required|digits:10|numeric|unique:providers,rif,{$this->selected_id}",
                'phone' => "required|digits:11|numeric|unique:providers,phone,{$this->selected_id}"
            ]
        );

        $data = $this->validate($rules);
        Provider::find($this->selected_id)
            ->update($data);

        $this->resetUI();
        $this->emit('provider-updated', 'Denominacion Actualizada');
    }

    public function resetUI()
    {
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->rif = '';
        $this->search = '';
        $this->selected_id = 0;
        $this->document = 'V';
    }
    protected $listeners = [
        'Destroy'
    ];

    public function Destroy(Provider $provider)
    {
        $provider->delete();
        $this->resetUI();
        $this->emit('provider-deleted', 'Denominacion Eliminada');
    }
}
