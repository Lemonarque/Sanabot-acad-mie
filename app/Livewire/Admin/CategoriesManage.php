<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;

class CategoriesManage extends Component
{
    public $categories;
    public $name = '';
    public $description = '';
    public $editingId = null;

    public function mount()
    {
        $this->categories = Category::latest()->get();
    }

    public function create()
    {
        $this->validate([
            'name' => 'required',
        ]);

        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
        ]);

        $this->reset(['name', 'description']);
        $this->mount();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
        ]);

        $category = Category::findOrFail($this->editingId);
        $category->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
        ]);

        $this->reset(['name', 'description', 'editingId']);
        $this->mount();
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'description', 'editingId']);
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->mount();
    }

    public function render()
    {
        return view('components.admin.categories-manage');
    }
}
