<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Home extends Component
{
    public function mount()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('vehicles.index');
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.home');
    }
}
