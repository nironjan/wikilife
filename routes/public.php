<?php

use App\Livewire\Partials\SearchResult;
use Illuminate\Support\Facades\Route;

// homepage
Route::get('/', \App\Livewire\Front\Homepage::class)->name('home');


Route::prefix('people')->name('people.')->middleware(['web', 'cache.headers:no_store'])->group(function () {

    // People route= meta checked
    Route::get('/', \App\Livewire\Front\Person\Index::class)->name('people.index');

    // meta checked
    Route::get('/born-today', \App\Livewire\Front\BornToday\Index::class)->name('born-today');

    // Professions --- meta checked
    Route::get('/profession', \App\Livewire\Front\Professions\Index::class)->name('profession.index');

    // Profession details route -- meta checked
    Route::get('/profession/{professionName}', \App\Livewire\Front\Professions\Details::class)->name('profession.details')->where('professionName', '[a-z0-9-]+');

    // Latest Updates -- meta checked
    Route::get('/{personSlug}/updates/', \App\Livewire\Front\Person\LatestUpdate\Index::class)->name('updates.index');
    Route::get('/{personSlug}/updates/{slug}', \App\Livewire\Front\Person\LatestUpdate\Details::class)->name('updates.show');

    // Controversy -- meta checked
    Route::get('/{personSlug}/controversies/{slug}', \App\Livewire\Front\Person\ControversyDetails::class)->name('controversies.show');

    // Career -- meta checked
    Route::get('/{personSlug}/career/{slug}', \App\Livewire\Front\Person\Career\Details::class)->name('career.show');

    // Search within people
    Route::get('/search/{query}', SearchResult::class)->name('people.search');

    Route::get('/search/', function () {
        return redirect()->route('people.index');
    })->name('people.search.redirect');

    // Individual person routes -- meta cheked
    Route::get('/{slug}', \App\Livewire\Front\Person\Details::class)->name('people.show');
    Route::get('/{slug}/{tab}', \App\Livewire\Front\Person\Details::class)->name('details.tab');

});


// Blogs -- meta checked
Route::prefix('articles')->name('articles.')->middleware(['web'])->group(function(){
    Route::get('/', \App\Livewire\Front\Blogs\Index::class )->name('index');
    Route::get('/category/{slug}', \App\Livewire\Front\Blogs\Category::class)->name('category');
    Route::get('/{slug}', \App\Livewire\Front\Blogs\Details::class )->name('show');
});

// Pages routes
Route::middleware(['web'])->group(function(){
    Route::get('/{slug}', \App\Livewire\Front\Pages\Details::class)->name('page.details')->where('slug', '[a-z0-9-]+');
});
