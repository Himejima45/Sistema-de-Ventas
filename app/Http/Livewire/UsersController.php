<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersController extends Component
{

    use WithPagination;
    use WithFileUploads;

    public $name, $phone, $status, $image, $password, $search, $email, $selected_id, $pageTitle, $componentName, $fileLoaded, $profile;

    public $rules = [
        'name' => ['required', 'min:2', 'max:30', 'regex:/^(?=.*[a-zA-Z])(?=\S*\s?\S*$)(?!.*\s{2,}).*$/'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:3', 'max:12', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/
'],
        'phone' => ['required', 'digits:11', 'unique:users,phone', 'numeric']
    ];
    public $messages = [
        'name.required' => 'Ingresa el nombre',
        'name.min' => 'El nombre de usuario debe tener al menos 3 caracteres',
        'email.required' => 'Ingresa el correo',
        'email.email' => 'Ingresa un correo valido',
        'email.unique' => 'El email ya existe en el sistema',
        'password.required' => 'Ingresa la contraseña',
        'password.min' => 'La contraseña debe tener al menos 3 caracteres',
    ];

    private $pagination = 5;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Usuarios';
        $this->status = 'Elegir';
    }

    public function render()
    {
        if (strlen($this->search) > 0)
            $data = User::where('name', 'like', '%' . $this->search . '%')
                ->select('*')->orderBy('name', 'asc')->paginate($this->pagination);
        else
            $data = User::select('*')->orderBy('name', 'asc')->paginate($this->pagination);

        return view('livewire.users.component', [
            'data' => $data,
            'roles' => Role::orderBy('name', 'asc')->get()
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function resetUI()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->phone = '';
        $this->image = '';
        $this->search = '';
        $this->status = 'Elegir';
        $this->selected_id = 0;
        $this->resetValidation();
        $this->resetPage();
    }

    public function Edit(User $user)
    {
        $this->selected_id = $user->id;
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->profile = $user->profile;
        $this->image = '';
        $this->status = $user->status;
        $this->email = $user->email;
        $this->password = '';

        $this->emit('show-modal', 'show modal!');
    }

    public function Store()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'active' => 1,
            'password' => bcrypt($this->password),
        ])->assignRole('Employee');


        $this->resetUI();
        $this->emit('user-added', 'Usuario Registrado');
    }

    public function Update()
    {
        $rules = array_merge(
            $this->rules,
            [
                'email' => ['required', 'email', "unique:users,email,{$this->selected_id}"],
                'phone' => ['required', 'digits:11', "unique:users,phone,{$this->selected_id}", 'numeric']
            ]
        );

        $this->validate($rules);

        $user = User::find($this->selected_id);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => bcrypt($this->password)
        ]);

        // if ($this->image) {
        //     $customFileName = uniqid() . '_.' . $this->image->extension();
        //     $this->image->storeAs('public/users', $customFileName);
        //     $imageTemp = $user->image;

        //     $user->image = $customFileName;
        //     $user->save();


        //     if ($imageTemp != null) {
        //         if (file_exists('storage/users/' . $imageTemp)) {
        //             unlink('storage/users/' . $imageTemp);
        //         }
        //     }
        // }



        $this->resetUI();
        $this->emit('user-updated', 'Usuario Actualizado');
    }

    protected $listeners = ['Destroy', 'resetUI'];

    public function Destroy(User $user)
    {
        if ($user) {
            $sales = Sale::Where('user_id', $user->id)->count();
            if ($sales > 0) {
                $this->emit('user-withsales', 'No es posible eliminar usuario por que tiene ventas registradras');
            } else {
                $user->delete();
                $this->resetUI();
                $this->emit('user-deleted', 'Usuario eliminado');
            }
        }
    }
}
