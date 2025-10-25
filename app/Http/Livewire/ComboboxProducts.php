<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

class ComboboxProducts extends Component
{
  public $search = '';
  public $showDropdown = false;
  public $options = [];
  public $cart;
  public $selectedOptions = [];

  public function mount()
  {
    $this->selectedOptions = collect($this->cart)->pluck('id')->toArray();
    $this->getItems();
  }

  public function updatedSearch()
  {
    $this->getItems();
  }

  public function selectOption($value, $id)
  {
    $this->search = '';
    $this->showDropdown = false;
    $this->emit('scan-code', $value);
    array_push($this->selectedOptions, $id);
    $this->getItems();
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
    return view('livewire.products.combobox');
  }

  private function getItems()
  {
    $this->options = Product::where(function ($q) {
      $q->where('name', 'like', "%{$this->search}%")
        ->orWhere('barcode', 'like', "%{$this->search}%");
    })
      ->where('stock', '>', 0)
      ->when($this->selectedOptions, fn($q, $ids) => $q->whereNotIn('id', $ids))
      ->limit(10)
      ->get(['id', 'name', 'barcode'])
      ->toArray();
  }
}