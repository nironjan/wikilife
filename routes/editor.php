<?php

use Illuminate\Support\Facades\Route;

Route::prefix('editor')->name('editor.')->middleware(['auth', 'verified', 'can:editorAccess'])->group(function () {

    // Editor Dashboard
    Route::get('/editor-dashboard', \App\Livewire\Editor\Dashboard::class)->name('dashboard');

    // Person Management
    Route::get('/people', \App\Livewire\Editor\Person\Index::class)->name('persons.index');
    Route::get('/people/create', \App\Livewire\Editor\Person\Manage::class)->name('persons.create');
    Route::get('/people/{id}/edit', \App\Livewire\Editor\Person\Manage::class)->name('persons.edit');
});
