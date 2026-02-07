<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return $this->redirect(route('home'), navigate: true);
    }

    public function render()
    {
        return <<<'HTML'
        <button wire:click="logout" class="text-gray-700 hover:text-blue-600">Logout</button>
        HTML;
    }
}
