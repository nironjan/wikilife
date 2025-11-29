<?php

use App\Livewire\Partials\SearchResult;
use Illuminate\Support\Facades\Route;

// homepage
Route::get('/', \App\Livewire\Front\Homepage::class)->name('home');

Route::prefix('people')->name('people.')->middleware(['web', 'cache.headers:no_store'])->group(function () {

    // People route
    Route::get('/', \App\Livewire\Front\Person\Index::class)->name('people.index');

    // Born Today
    Route::get('/born-today', \App\Livewire\Front\BornToday\Index::class)->name('born-today');

    // Professions
    Route::get('/profession', \App\Livewire\Front\Professions\Index::class)->name('profession.index');

    // Profession details route
    Route::get('/profession/{professionName}', \App\Livewire\Front\Professions\Details::class)
        ->name('profession.details')
        ->where('professionName', '[a-z0-9-]+');

    // Search within people
    Route::get('/search', SearchResult::class)->name('people.search');

    // Feedback routes - place BEFORE personSlug group
    Route::get('/{slug}/suggest-edit', \App\Livewire\Front\Person\FeedbackForm::class)
        ->name('suggest-edit')
        ->where('slug', '[a-z0-9-]+');

    Route::get('/{slug}/edit', \App\Livewire\Front\Person\FeedbackForm::class)
        ->name('feedback')
        ->where('slug', '[a-z0-9-]+');

    // Person sub-routes - must come AFTER specific routes but BEFORE general ones
    Route::prefix('{personSlug}')->where(['personSlug' => '[a-z0-9-]+'])->group(function(){
        // Latest Updates
        Route::get('/updates/', \App\Livewire\Front\Person\LatestUpdate\Index::class)->name('updates.index');
        Route::get('/updates/{slug}', \App\Livewire\Front\Person\LatestUpdate\Details::class)->name('updates.show');

        // Controversy
        Route::get('/controversies/{slug}', \App\Livewire\Front\Person\ControversyDetails::class)->name('controversies.show');

        // Speeches & Interviews
        Route::get('/speeches-interviews/{slug}', \App\Livewire\Front\Person\Speeches::class)->name('speeches.show');

        // Career
        Route::get('/career/{slug}', \App\Livewire\Front\Person\Career\Details::class)->name('career.show');
    });

    // Individual routes (KEEP THESE LAST - most general routes)
    Route::get('/{slug}', \App\Livewire\Front\Person\Details::class)
        ->name('people.show')
        ->where('slug', '[a-z0-9-]+');

    Route::get('/{slug}/{tab}', \App\Livewire\Front\Person\Details::class)
        ->name('details.tab')
        ->where('slug', '[a-z0-9-]+');
});

// Blogs
Route::prefix('articles')->name('articles.')->middleware(['web'])->group(function(){
    Route::get('/', \App\Livewire\Front\Blogs\Index::class)->name('index');
    Route::get('/category/{slug}', \App\Livewire\Front\Blogs\Category::class)->name('category');
    Route::get('/{slug}', \App\Livewire\Front\Blogs\Details::class)->name('show');
});

// Pages
Route::prefix('p')->middleware(['web'])->group(function(){
    Route::get('/{slug}', App\Livewire\Front\Pages\Details::class)
        ->where('slug', '^(?!people|articles|api|admin)[A-Za-z0-9\-]+$')
        ->name('page.details');
});

// Contact Form
Route::get('/contact', \App\Livewire\Front\Contact\ContactForm::class)->name('contact');

// Catch-all 404 route (MUST be the very last route)
Route::fallback(\App\Livewire\Front\NotFoundPage::class);
