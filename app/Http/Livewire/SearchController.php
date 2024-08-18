<?php

namespace App\Http\Livewire;

use App\Models\Client;
use Livewire\Component;

class SearchController extends Component
{
   public $search, $clients, $client;

   protected $listeners = [
    'selectClient'
] ;

   public function mount()
    {
        $this->clients = Client::all('id', 'name', 'document');
    }

    public function selectClient($id) {
        $this->client = Client::find($id);
    }

    public function render()
    {

        return view('livewire.search');
    }
}
