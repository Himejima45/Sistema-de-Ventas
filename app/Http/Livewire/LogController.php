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
    public $state, $role, $user, $module, $month, $year;

    protected $listeners = ['dateChanged'];

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
        $this->month = '';
        $this->year = '';
    }

    public function dateChanged($data)
    {
        $this->month = $data['month'];
        $this->year = $data['year'];
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
            if ($this->month && $this->month !== 'Seleccione' && $this->year) {
                $q->whereMonth('created_at', $this->month)
                    ->whereYear('created_at', $this->year);
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
