<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('explorer', 'pages::companion.⚡explorer')->name('explorer');
});

require __DIR__.'/settings.php';
