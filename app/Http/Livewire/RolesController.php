<?php

namespace App\Http\Livewire;

use App\Models\Binnacle;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;

class RolesController extends Component
{
    use WithPagination;

    public $roleName, $search, $selected_id, $pageTitle, $componentName;
    private $pagination = 20;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Roles';
    }
    public function render()
    {
        if (strlen($this->search) > 0)
            $roles = Role::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        else
            $roles = Role::orderBy('name', 'asc')->paginate($this->pagination);

        return view('livewire.roles.component', [
            'roles' => $roles
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function CreateRole()
    {
        $rules = [
            'roleName' => 'required|min:2|unique:roles,name'
        ];
        $messages = [
            'roleName.required' => 'El Nombre de rol es requerido',
            'roleName.unique' => 'Ya existe el rol',
            'roleName.min' => 'El nombre del rol debe tener al menos 3 caracteres'
        ];

        $this->validate($rules, $messages);

        $model = Role::create([
            'name' => $this->roleName
        ]);

        Binnacle::create([
            'module' => 'Rol',
            'user' => auth()->user()->full_name,
            'rol' => auth()->user()->getRoleNames()[0],
            'action' => "Registro creado con el id: $model->id",
            'status' => 'successfull',
        ]);

        $this->resetUI();
        $this->emit('role-added', 'Se registro el role con exito');
    }

    public function Edit(Role $role)
    {
        $this->selected_id = $role->id;
        $this->roleName = $role->name;
        $this->emit('show.modal', 'Show modal');
    }

    public function UpdateRole()
    {
        $rules = [
            'roleName' => "required|min:2|unique:roles,name, {$this->selected_id}"
        ];
        $messages = [
            'roleName.required' => 'El Nombre de rol es requerido',
            'roleName.unique' => 'Ya existe el rol',
            'roleName.min' => 'El nombre del rol debe tener al menos 3 caracteres'
        ];

        $this->validate($rules, $messages);

        $role = Role::find($this->selected_id);
        $role->name = $this->roleName;
        $role->save();

        Binnacle::create([
            'module' => 'Rol',
            'user' => auth()->user()->full_name,
            'rol' => auth()->user()->getRoleNames()[0],
            'action' => "Registro actualizado con el id: $role->id",
            'status' => 'successfull',
        ]);

        $this->resetUI();
        $this->emit('role-updated', 'Se actualizó el rol con exito');
    }

    protected $listeners = ['Destroy', 'Edit'];

    public function Destroy($id)
    {
        $permissionsCount = Role::find($id)->permissions->count();
        if ($permissionsCount > 0) {
            $this->emit('role-error', 'No se puede eliminar el rol porque tiene permisos asociados');
            return;
        }

        Role::find($id)->delete();

        Binnacle::create([
            'module' => 'Rol',
            'user' => auth()->user()->full_name,
            'rol' => auth()->user()->getRoleNames()[0],
            'action' => "Registro actualizado con el id: $id",
            'status' => 'successfull',
        ]);

        $this->resetUI();
        $this->emit('role-deleted', 'Se elimino con exito');
    }


    public function resetUI()
    {
        $this->roleName = '';
        $this->search = '';
        $this->selected_id = 0;
        $this->resetValidation();
    }
}
