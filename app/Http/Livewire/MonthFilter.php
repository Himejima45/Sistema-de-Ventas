<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class MonthFilter extends Component
{
    public $month, $year, $years = [];

    public function mount()
    {
        $this->month = date('m');
        $this->year = date('Y');

        $firstRecordYear = User::find(1)->get('created_at')->first()['created_at']->year;
        $currentYear = date('Y');
        $this->years = range($firstRecordYear, $firstRecordYear);
    }

    public function updated()
    {
        $this->emit('dateChanged', [
            'month' => $this->month,
            'year' => $this->year
        ]);
    }

    public function render()
    {
        return view('livewire.month-filter');
    }
}
