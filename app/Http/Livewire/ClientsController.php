<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Client;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ClientsController extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $name, $last_name, $document, $phone, $address, $selected_id, $pageTitle, $componentName, $search;
    private $pagination = 10;

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
            $data = Client::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        else
            $data = Client::orderBy('id', 'desc')->paginate($this->pagination);

        return view('livewire.client.clientes', ['clients' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Edit($id)
    {
        $record = Client::find($id, ['id', 'name', 'last_name', 'document', 'phone', 'address']);
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

        $rules = [
            'name' => 'required|unique:categories|min:2',
            'last_name' => 'required',
            'document' => 'required',
            'phone' => 'required',
            'address' => 'required'

        ];
        $messages = [
            'name.required' => 'El Nombre es requerido',
            'name.unique' => 'Ya existe el nombre',
            'name.min' => 'El nombre debe tener al menos 2 caracteres',
            'last_name.required' => 'El apellido es requerido',
            'document.required' => 'La cedula es requerida',
            'phone.required' => 'El numero de telefono es requerido',
            'address.required' => 'La direccion es requerida'
        ];

        $this->validate($rules, $messages);

        Client::create([
            'name' => $this->name,
            'last_name' => $this->last_name,
            'document' => $this->document,
            'phone' => $this->phone,
            'address' => $this->address
        ]);

        $this->resetUI();
        $this->emit('client-added', 'Cliente Registrado');
    }
    public function Update()
    {
        $rules = [
            'name' => "required|min:2|unique:clients,name,{$this->selected_id}",
            'last_name' => 'required',
            'document' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ];

        $messages = [
            'name.required' => 'Nombre es requerido',
            'name.min' => 'El nombre debe tener al menos 2 caracteres',
            'name.unique' => 'El nombre ya existe',
            'last_name.required' => 'El apellido es requerido',
            'document.required' => 'La cedula es requerida',
            'phone.required' => 'El numero de telefono es requerido',
            'address.required' => 'La direccion es requerida'
        ];

        $this->validate($rules, $messages);

        $client = Client::find($this->selected_id);
        $client->update([
            'name' => $this->name,
            'last_name' => $this->last_name,
            'document' => $this->document,
            'phone' => $this->phone,
            'address' => $this->address
        ]);

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

    public function Destroy(Client $client)
    {
        $client->delete();

        $this->resetUI();
        $this->emit('client-deleted', 'Cliente Eliminado');
    }
}
