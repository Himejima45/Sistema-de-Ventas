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
<<<<<<< HEAD
=======

        dd($client);
>>>>>>> 297e68f7f57f7ca13172559bba6a59959bfb7596
    }

    public function render()
    {

        return view('livewire.search');
    }
}
