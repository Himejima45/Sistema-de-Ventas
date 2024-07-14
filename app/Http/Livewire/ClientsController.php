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

    public $name, $last_name, $document, $phone, $address, $selected_id, $pageTitle, $componentName;
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
        $data = Client::all();

        return view('livewire.client.clientes', ['clients'=> $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }
}
