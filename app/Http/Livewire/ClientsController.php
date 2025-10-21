<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ClientsController extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $name, $last_name, $document, $phone, $email, $address, $selected_id, $pageTitle, $componentName, $search, $password, $password_confirmation;
    private $pagination = 20;
    public $rules = [
        'name' => ['required', 'min:2', 'max:30', 'regex:/^[\p{L}]+(?: [\p{L}]+)*$/u',],
        'last_name' => 'required|min:2|max:30|alpha',
        'document' => 'required|digits_between:6,8|numeric|unique:users,document',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'required|digits:11|numeric|unique:users,phone',
        'password' => 'required|string|min:8|confirmed',
        'password_confirmation' => 'required|string|min:8',
        'address' => ['required', 'min:3', 'max:100', 'regex:/^[\p{L}]+(?: [\p{L}]+)*$/u',]
    ];

    protected $validationAttributes = [
        'name' => 'nombre',
        'last_name' => 'apellido',
        'document' => 'cédula',
        'email' => 'correo electrónico',
        'phone' => 'teléfono',
        'address' => 'dirección',
        'password' => 'contraseña',
        'password_confirmation' => 'confirmar contraseña'
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
        $this->email = $record->email;
        $this->selected_id = $record->id;

        $this->emit('show-modal', 'show modal!');
        $this->emit('modal-show', 'show modal!');
    }

    public function Store()
    {
        $data = $this->validate();
        $data['password'] = bcrypt($this->password);
        User::create($data)
            ->assignRole('Client');

        $this->resetUI();
        $this->emit('record-created', 'Cliente Registrado');
    }
    public function Update()
    {
        $user = User::find($this->selected_id);

        $rules = array_merge(
            $this->rules,
            [
                'document' => "required|digits_between:6,8|numeric|unique:users,document,{$this->selected_id}",
                'phone' => "required|digits:11|numeric|unique:users,phone,{$this->selected_id}",
                'email' => $user->email === $this->email
                    ? 'required|string|email|max:255'
                    : 'required|string|email|max:255|unique:users',
            ]
        );

        if (!$this->password) {
            unset($rules['password']);
        }

        $data = $this->validate($rules);

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        $this->resetUI();
        $this->emit('record-updated', 'Cliente Actualizado');
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
        $this->password = '';
        $this->password_confirmation = '';
    }

    protected $listeners = ['Destroy' => 'delete'];

    public function delete(User $client)
    {
        $client->delete();

        $this->resetUI();
    }
}
