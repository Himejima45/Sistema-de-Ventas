<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class ComboboxController extends Component
{
    public $options, $selected, $searched;

    public function mount()
    {
        $this->options = collect();
        $this->selected = collect();
        $this->searched = '';
    }

    public function updatedSearched()
    {
        if (strlen($this->searched) > 0) {
            $excludedIds = $this->selected->pluck('id');

            $this->options = Product::where('name', 'LIKE', "%$this->searched%")
                ->whereNotIn('id', $excludedIds)
                ->get();
        } else {
            $this->options = collect();
        }
    }

    public function addOption($optionId)
    {
        $product = Product::find($optionId);

        if ($product && !$this->selected->contains('id', $product->id)) {
            $this->selected->push($product);
        }
    }

    public function removeOption($optionId)
    {
        $this->selected = $this->selected->reject(function ($product) use ($optionId) {
            return $product['id'] === $optionId;
        });
    }

    public function resetUI()
    {
        $this->searched = '';
        $this->options = collect();
    }

    protected $listeners = [
        'resetUI',
    ];

    public function render()
    {
        $this->updatedSearched();

        return view('livewire.combobox', [
            'options' => $this->options,
            'selected' => $this->selected,
        ]);
    }
}
