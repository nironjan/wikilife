<?php

namespace App\Livewire\Front\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.front')]
class Details extends Component
{
    public $slug;
    public $page;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->loadPage();
    }

    public function loadPage()
    {
        $this->page = Page::with(['user'])
            ->where('slug', $this->slug)
            ->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->firstOrFail();
    }

    #[Title('page.title')]
    public function render()
    {
        // Determine which template to use
        $template = $this->page->template ?: 'custom';

        // Check if template view exists, fallback to custom
        $view = "livewire.front.pages.templates.{$template}";
        if (!view()->exists($view)) {
            $view = 'livewire.front.pages.templates.custom';
        }

        return view($view, [
            'page' => $this->page
        ]);
    }
}
