<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use App\Models\User;
use DB;

class PermisosController extends Component
{ 
    
    use WithPagination;

    public $permissionName, $search, $selected_id, $pageTitle, $componentName;
    private $pagination = 10;
    
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Permisos';
    }
    public function render()
    {
        if(strlen($this->search) > 0)
           $permisos = Permission::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        else
           $permisos = Permission::orderBy('name','desc')->paginate($this->pagination);


        return view('livewire.permisos.component',[
            'permisos' => $permisos
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function CreatePermission()
    {
        $rules = [
            'permissionName' => 'required|min:2|unique:permissions,name'
        ];
        $messages = [
            'permissionName.required' => 'El Nombre de permiso es requerido',
            'permissionName.unique' => 'Ya existe el permiso',
            'permissionName.min' => 'El nombre del permiso debe tener al menos 3 caracteres'
        ];

        $this->validate($rules, $messages);

        Permission::create([
        'name' => $this->permissionName
        ]);

        $this->resetUI();
        $this->emit('permiso-added', 'Se registro el permiso con exito');

    }

    public function Edit(Permission $permiso)
    {
        
        $this->selected_id = $permiso->id;
        $this->permissionName = $permiso->name;

        $this->emit('show-modal', 'Show modal');
    }

    public function UpdatePermission()
    {
        $rules = [
            'permissionName' => "required|min:2|unique:permissions,name, {$this->selected_id}"
        ];
        $messages = [
            'permissionName.required' => 'El Nombre de permiso es requerido',
            'permissionName.unique' => 'Ya existe el permiso',
            'permissionName.min' => 'El nombre del permiso debe tener al menos 3 caracteres'
        ];

        $this->validate($rules, $messages);

        $permiso = Permission::find($this->selected_id);
        $permiso->name = $this->permissionName;
        $permiso->save();

        $this->resetUI(); 
        $this->emit('permiso-updated', 'Se actualizó el permiso con exito');
    }

    protected $listeners = ['Destroy'];

    public function Destroy($id)
    { 
        $rolesCount = Permission::find($id)->getRoleNames()->count();
        if($rolesCount > 0)
        {
            $this->emit('permiso-error', 'No se puede eliminar el permiso porque tiene roles asociados');
            return;
        }

        Permission::find($id)->delete();
        $this->resetUI();
        $this->emit('permiso-deleted', 'Se elimino con exito');
    }

    
    public function resetUI()
    {
 
        $this->permissionName ='';
        $this->search ='';
        $this->selected_id = 0;
        $this->resetValidation();
        
    }


   /* public function AsignarRoles($rolesList)
    {
        if($this->userSelected > 0)
        {
            $user = User::find($this->userSelected);
            if($user) {
                $user->syncRoles($rolesList);
                $this->emit('msg-ok', 'Roles asignados correctamente');
                $this->resetUI();
            }
        }
    } */

}
