<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CategoriesController extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $name, $search, $selected_id, $pageTitle, $componentName, $customFileName;
    private $pagination = 20;

    public $rules = [
        'name' => ['required', 'min:2', 'max:30', 'regex:/^(?=.*[a-zA-Z])(?=\S*\s?\S*$)(?!.*\s{2,}).*$/', 'unique:categories,name'],
    ];

    // ! TODO 10
    public $messages = [
        'name.required' => 'El Nombre es requerido',
        'name.unique' => 'Ya existe el nombre',
        'name.min' => 'El nombre debe tener al menos 2 caracteres',
    ];

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Categorias';
    }


    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if (strlen($this->search) > 0)
            $data = Category::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        else
            $data = Category::orderBy('id', 'desc')->paginate($this->pagination);


        return view('livewire.category.categories', ['categories' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }


    public function Edit($id)
    {
        $record = Category::find($id, ['id', 'name']);
        $this->name = $record->name;
        $this->selected_id = $record->id;

        $this->emit('show-modal', 'show modal!');
    }


    public function Store()
    {
        $data = $this->validate();
        Category::create($data);

        $this->resetUI();
        $this->emit('category-added', 'Categoria Registrada');
    }

    public function Update()
    {
        $rules = [
            'name' => "required|min:2|max:30|regex:/^(?=.*[a-zA-Z])(?=\S*\s?\S*$)(?!.*\s{2,}).*$/|unique:categories,name,{$this->selected_id}",
        ];

        $data = $this->validate($rules);
        $category = Category::find($this->selected_id);
        $category->update($data);

        $this->resetUI();
        $this->emit('category-updated', 'Categoria Actualizada');
    }

    public function resetUI()
    {
        $this->name = '';
        $this->search = '';
        $this->selected_id = 0;
        $this->resetErrorBag();
        $this->resetValidation();
    }
    protected $listeners = [
        'Destroy'
    ];

    public function Destroy(Category $category)
    {
        $category->delete();
        $this->resetUI();
        $this->emit('category-deleted', 'Categoria Eliminada');
    }
}
