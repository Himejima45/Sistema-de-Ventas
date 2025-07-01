<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class ComboboxClients extends Component
{
    public $search = '';
    public $selectedValue = '';
    public $showDropdown = false;
    public $options = [];

    public function mount()
    {
        $this->options = User::where('name', 'like', '%' . $this->search . '%')
            ->with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Client');
            })
            ->limit(10)
            ->get(['id', 'name', 'last_name', 'document'])
            ->toArray();

        $genericClient = User::where('document', '999999999')->first();
        $this->selectedValue = $genericClient->id ?? '';
        $this->search = $genericClient ? $genericClient->name . ' ' . $genericClient->last_name . ' ' . $genericClient->document : '';

        $this->selectOption($this->selectedValue, $this->search);
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->options = User::where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('document', 'like', '%' . $this->search . '%');
            })
                ->with('roles')
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Client');
                })
                ->limit(10)
                ->get(['id', 'name', 'last_name', 'document'])
                ->toArray();
        } else {
            $this->options = User::where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('document', 'like', '%' . $this->search . '%');
            })
                ->with('roles')
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Client');
                })->limit(10)->get(['id', 'name', 'last_name', 'document'])->toArray();
        }
    }

    public function selectOption($value, $display)
    {
        $this->selectedValue = $value;
        $this->search = $display;
        $this->showDropdown = false;
        $this->emit('client-selected', $value);
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    public function render()
    {
        return view('livewire.combo-box');
    }
}