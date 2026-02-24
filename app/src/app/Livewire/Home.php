<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Home extends Component
{
    public function mount()
    {
        // Removed redirect so Home is the dashboard
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $vehicles = \Illuminate\Support\Facades\Auth::user()
                ->vehicles()
                ->with('activeReminders')
                ->get();
            
            $totalVehicles = $vehicles->count();
            $hasVehicles = $totalVehicles > 0;
            $healthyVehicles = $hasVehicles ? $vehicles->filter(fn($v) => $v->activeReminders->isEmpty())->count() : 0;
            $healthScore = $hasVehicles ? round(($healthyVehicles / $totalVehicles) * 100) : 100;

            return view('livewire.home', [
                'vehicles' => $vehicles->take(5),
                'needsAttention' => $vehicles->filter(fn($v) => $v->activeReminders->isNotEmpty()),
                'healthScore' => $healthScore,
                'healthyVehicles' => $healthyVehicles,
                'totalVehicles' => $totalVehicles,
                'hasVehicles' => $hasVehicles,
            ]);
        }

        return view('livewire.home');
    }
}
