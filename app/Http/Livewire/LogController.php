<?php

namespace App\Http\Livewire;

use App\Models\Binnacle;
use Livewire\Component;

class LogController extends Component
{
    public $pagination = 20;
    public $pageTitle, $componentName;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Bitácora';
    }

    public function render()
    {
        $data = Binnacle::paginate($this->pagination);

        return view('livewire.log', ['data' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
