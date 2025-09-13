<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use App\Models\Menu;

class Sidebar extends Component
{
    public $menus;

    public function mount()
    {
        $this->menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
    }

    public function render()
    {
        return view('livewire.layout.sidebar');
    }
}
