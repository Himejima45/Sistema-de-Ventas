<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Client;
use App\Models\User;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ClientsController extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $name, $last_name, $document, $phone, $address, $selected_id, $pageTitle, $componentName, $search;
    private $pagination = 10;
    public $rules = [
        'name' => ['required', 'min:2', 'max:30', 'regex:/^(?=.*[a-zA-Z])(?=\S*\s?\S*$)(?!.*\s{2,}).*$/'],
        'last_name' => 'required|min:2|max:30|alpha',
        'document' => 'required|digits_between:6,8,|numeric|unique:clients,document',
        'phone' => 'required|digits:11|numeric|unique:clients,phone',
        'address' => ['required', 'min:3', 'max:100', 'regex:/^(?=.*[a-zA-Z])(?=\S*\s?\S*$)(?!.*\s{2,}).*$/']
    ];

    // ! TODO 10
    public $messages = [
        'name.required' => 'El Nombre es requerido',
        'name.unique' => 'Ya existe el nombre',
        'name.min' => 'El nombre debe tener al menos 2 caracteres',
        'last_name.required' => 'El apellido es requerido',
        'document.required' => 'La cedula es requerida',
        'phone.required' => 'El numero de telefono es requerido',
        'address.required' => 'La direccion es requerida'
    ];

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Clientes';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if (strlen($this->search) > 0)
            $data = User::where('name', 'like', '%' . $this->search . '%')
                ->with('roles')
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Client');
                })
                ->paginate($this->pagination);
        else
            $data = User::orderBy('id', 'desc')
                ->with('roles')
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Client');
                })
                ->paginate($this->pagination);

        return view('livewire.client.clientes', ['clients' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Edit($id)
    {
        $record = User::find($id);
        $this->name = $record->name;
        $this->last_name = $record->last_name;
        $this->document = $record->document;
        $this->phone = $record->phone;
        $this->address = $record->address;
        $this->selected_id = $record->id;

        $this->emit('show-modal', 'show modal!');
    }

    public function Store()
    {
        $data = $this->validate();
        User::create($data)
            ->assignRole('Client');

        $this->resetUI();
        $this->emit('client-added', 'Cliente Registrado');
    }
    public function Update()
    {
        $rules = array_merge(
            $this->rules,
            [
                'document' => "required|digits_between:6,8|numeric|unique:clients,document,{$this->selected_id}",
                'phone' => "required|digits:11|numeric|unique:clients,phone,{$this->selected_id}"
            ]
        );
        $data = $this->validate($rules);

        User::find($this->selected_id)
            ->update($data);

        $this->resetUI();
        $this->emit('client-updated', 'Cliente Actualizada');
    }

    public function resetUI()
    {

        $this->name = '';
        $this->last_name = '';
        $this->document = '';
        $this->phone = '';
        $this->address = '';
        $this->search = '';
        $this->selected_id = 0;
    }

    protected $listeners = ['Destroy'];

    public function Destroy(User $client)
    {
        $client->delete();

        $this->resetUI();
        $this->emit('client-deleted', 'Cliente Eliminado');
    }
}
