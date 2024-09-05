<?php

namespace App\Http\Livewire;

use App\Models\Client;
use Livewire\Component;

class SearchController extends Component
{
    public $search, $clients, $type, $selected_client;

    protected $listeners = [
        'selectClient',
    ];

    public function mount()
    {
        $this->clients = Client::all('id', 'name', 'document');
        $this->selected_client = null;
        $this->type = 'Elegir';
    }

    protected $rules = [
        'selected_client' => 'required|exists:clients,id'
    ];

    public function selectClient($id)
    {
        $this->validate();

        $client = Client::find($id);
        $this->selected_client = $client;
        $this->emit('client-selected', $this->selected_client->id);
        $this->selected_client = $client->id;
    }

    public function setType($value)
    {
        $this->emit('type-selected', $value);
    }

    public function render()
    {
        return view('livewire.search');
    }
}
