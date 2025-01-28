<?php

namespace App\Http\Livewire;

use App\Models\Binnacle;
use Livewire\Component;

class LogController extends Component
{
    public $pageTitle, $componentName;
    private $pagination = 20;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Bitácora';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        $data = Binnacle::paginate($this->pagination);

        return view('livewire.log', ['data' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
