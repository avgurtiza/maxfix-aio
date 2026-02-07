<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Reminders\ReminderForm;
use App\Livewire\Reminders\ReminderList;
use App\Livewire\Services\ServiceForm;
use App\Livewire\Services\ServiceHistory;
use App\Livewire\Shops\ShopMap;
use App\Livewire\Shops\ShopSearch;
use App\Livewire\Vehicles\VehicleForm;
use App\Livewire\Vehicles\VehicleList;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/vehicles', VehicleList::class)->name('vehicles.index');
    Route::get('/vehicles/create', VehicleForm::class)->name('vehicles.create');
    Route::get('/vehicles/{vehicle}/edit', VehicleForm::class)->name('vehicles.edit');

    Route::get('/vehicles/{vehicle}/services', ServiceHistory::class)->name('services.history');
    Route::get('/vehicles/{vehicle}/services/create', ServiceForm::class)->name('services.create');
    Route::get('/vehicles/{vehicle}/services/{service}/edit', ServiceForm::class)->name('services.edit');

    Route::get('/vehicles/{vehicle}/reminders', ReminderList::class)->name('reminders.index');
    Route::get('/vehicles/{vehicle}/reminders/create', ReminderForm::class)->name('reminders.create');
    Route::get('/reminders/{reminder}/edit', ReminderForm::class)->name('reminders.edit');

    Route::get('/shops', ShopSearch::class)->name('shops.index');
    Route::get('/shops/map', ShopMap::class)->name('shops.map');
});
