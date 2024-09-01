<?php

namespace App\Http\Livewire;

use App\Models\Client;
use Livewire\Component;

class SearchController extends Component
{
    public $search, $clients, $client;

    protected $listeners = [
        'selectClient',
    ];

    public function mount()
    {
        $this->clients = Client::all('id', 'name', 'document');
        $this->client = null;
    }

    protected $rules = [
        'client' => 'required|exists:clients,id'
    ];

    public function selectClient($id)
    {
        $this->validate();
        $this->client = Client::find($id);
        $this->emit('client-selected', $this->client->id);
    }

    public function render()
    {
        return view('livewire.search');
    }
}
