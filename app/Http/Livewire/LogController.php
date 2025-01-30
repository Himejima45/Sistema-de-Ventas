<?php

namespace App\Http\Livewire;

use App\Models\Binnacle;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class LogController extends Component
{
    use WithPagination;
    public $pageTitle, $componentName;
    private $pagination = 20;
    public $state, $role, $user, $module, $date;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Bitácora';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function clear_filters()
    {
        $this->state = '';
        $this->role = '';
        $this->user = '';
        $this->module = '';
        $this->date = '';
    }

    public function render()
    {
        $query = Binnacle::query();
        $query->where(function ($q) {
            if ($this->role) {
                $q->where('rol', 'like', '%' . $this->role . '%');
            }
            if ($this->state) {
                $q->where('status', 'like', '%' . $this->state . '%');
            }
            if ($this->user) {
                $q->where('user', 'like', '%' . $this->user . '%');
            }
            if ($this->module) {
                $q->where('module', 'like', '%' . $this->module . '%');
            }
            if ($this->date) {
                $q->whereDate('created_at', 'like', '%' . $this->date . '%');
            }
        });

        $data = $query->latest()->paginate($this->pagination);
        $roles = Role::all();
        $users = User::all();

        return view('livewire.log', [
            'data' => $data,
            'roles' => $roles,
            'users' => $users
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
