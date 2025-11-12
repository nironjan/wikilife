<?php


use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Person\Index;
use App\Livewire\Admin\Person\Manage;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Services\SitemapService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;


// Sitemap
Route::get('/sitemap.xml', function(){
    $sitemapPath = storage_path('app/public/sitemap.xml');

    if(!file_exists($sitemapPath)){
        app(SitemapService::class)->generateSitemap();
    }

    return response(file_get_contents($sitemapPath), 200)
        ->header('Content-Type', 'application/xml');
});



// Admin Routes
Route::prefix('webmaster')->name('webmaster.')->middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    // Site Setting
    Route::get('/site-seetings', \App\Livewire\Settings\SiteSetting::class)->name('site-setting');

    // Menu
    Route::get('/menu-list', \App\Livewire\Admin\Menu\Index::class)->name('menus');
    Route::get('/menu-list/manage/{id?}', \App\Livewire\Admin\Menu\Form::class)->name('menus.manage');

    // Person
    Route::prefix('persons')->name('persons.')->group(function () {
        Route::get('/', Index::class)->name('index');
        Route::get('/manage/{id?}', Manage::class)->name('manage');


        Route::get('/latest-update', \App\Livewire\Admin\LatestUpdate\Index::class)->name('latest-update.index');
        Route::get('/latest-update/manage/{id?}', \App\Livewire\Admin\LatestUpdate\Manage::class)->name('latest-update.manage');

        Route::get('/education', \App\Livewire\Admin\Education\Index::class)->name('education.index');
        Route::get('/education/manage/{id?}', \App\Livewire\Admin\Education\Manage::class)->name('education.manage');

        Route::get('/relations', \App\Livewire\Admin\Relation\Index::class)->name('relation.index');
        Route::get('/relations/manage/{id?}', \App\Livewire\Admin\Relation\Manage::class)->name('relation.manage');

        Route::get('/filmography', \App\Livewire\Admin\Filmography\Index::class)->name('filmography.index');
        Route::get('/filmography/manage/{id?}', \App\Livewire\Admin\Filmography\Manage::class)->name('filmography.manage');

        Route::get('/award', \App\Livewire\Admin\Award\Index::class)->name('award.index');
        Route::get('/award/manage/{id?}', \App\Livewire\Admin\Award\Manage::class)->name('award.manage');

        Route::get('/entrepreneur', \App\Livewire\Admin\Entrepreneur\Index::class)->name('entrepreneur.index');
        Route::get('/entrepreneur/manage/{id?}', \App\Livewire\Admin\Entrepreneur\Manage::class)->name('entrepreneur.manage');

        Route::get('/politicians', \App\Livewire\Admin\Politician\Index::class)->name('politician.index');
        Route::get('/politicians/manage/{id?}', \App\Livewire\Admin\Politician\Manage::class)->name('politician.manage');

        Route::get('/sports', \App\Livewire\Admin\Sports\Index::class)->name('sports.index');
        Route::get('/sports/manage/{id?}', \App\Livewire\Admin\Sports\Manage::class)->name('sports.manage');

        Route::get('/literature', \App\Livewire\Admin\Literature\Index::class)->name('literature.index');
        Route::get('/literature/manage/{id?}', \App\Livewire\Admin\Literature\Manage::class)->name('literature.manage');

        Route::get('/settings', \App\Livewire\Admin\Setting\Index::class)->name('settings.index');
        Route::get('/settings/{id}/manage', \App\Livewire\Admin\Setting\Manage::class)->name('settings.manage');

        Route::get('/interviews', \App\Livewire\Admin\Interview\Index::class)->name('interviews.index');
        Route::get('/interviews/manage/{id?}', \App\Livewire\Admin\Interview\Manage::class)->name('interviews.manage');

        Route::get('/facts', \App\Livewire\Admin\Fact\Index::class)->name('facts.index');
        Route::get('/facts/manage/{id?}', \App\Livewire\Admin\Fact\Manage::class)->name('facts.manage');

        Route::get('/controversies', \App\Livewire\Admin\Controversy\Index::class)->name('controversies.index');
        Route::get('/controversies/manage/{id?}', \App\Livewire\Admin\Controversy\Manage::class)->name('controversies.manage');

    });

    // CMS
    Route::prefix('blog/categories')->name('blog.categories.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Blog\Categories\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Admin\Blog\Categories\Manage::class)->name('create');
        Route::get('/{id}/edit', \App\Livewire\Admin\Blog\Categories\Manage::class)->name('edit');
    });

    // CMS
    Route::prefix('blog/posts')->name('blog.posts.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Blog\Posts\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Admin\Blog\Posts\Manage::class)->name('create');
        Route::get('/{id}/edit', \App\Livewire\Admin\Blog\Posts\Manage::class)->name('edit');
    });

    // CMS
    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Pages\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Admin\Pages\Manage::class)->name('manage');
        Route::get('/{id}/edit', \App\Livewire\Admin\Pages\Manage::class)->name('edit');
    });

    // CMS
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Users\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Admin\Users\Manage::class)->name('manage');
        Route::get('/{id}/edit', \App\Livewire\Admin\Users\Manage::class)->name('edit');
    });

    // Feedback
    Route::prefix('feedback')->name('feedback.')->group(function () {
    Route::get('/', \App\Livewire\Admin\Feedback\Index::class)->name('index');
    Route::get('/review/{id}', \App\Livewire\Admin\Feedback\Manage::class)->name('review'); // Changed from 'manage' to 'review'
});
});


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});




