<?php

namespace App\Livewire\Front;

use App\Models\Menu;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class FooterSection extends Component
{
    public $footerMenus = [];
    public $siteSettings;
    public $socialLinks = [];
    public $menuIcons = [];
    public $socialIcons = [];

    // Profession categories - can be moved to config for better scalability
    public $professions = [
        'actor' => 'Actor',
        'politician' => 'Politician',
        'sports' => 'Sports Person',
        'entrepreneur' => 'Entrepreneur',
        'scientist' => 'Scientist',
        'artist' => 'Artist',
    ];

    public function mount()
    {
        $this->loadMenuIcons();
        $this->loadSocialIcons();
        $this->loadFooterMenus();
        $this->loadSiteSettings();
    }

    public function placeholder(){
        return view('livewire.footer-skeleton');
    }

    public function loadMenuIcons()
    {
        $this->menuIcons = config('menu-icons.icons', []);
    }

    public function loadSocialIcons(){
        $this->socialIcons = config('social-icons.icons', []);
    }

    public function loadFooterMenus()
    {
        $this->footerMenus = Menu::with(['children' => function($query) {
                $query->active()->ordered();
            }])
            ->footer()
            ->active()
            ->root()
            ->ordered()
            ->get()
            ->map(function($menu) {
                return $this->formatMenuData($menu);
            })
            ->toArray();
    }

    protected function formatMenuData($menu)
    {
        return [
            'id' => $menu->id,
            'name' => $menu->name,
            'url' => $menu->full_url,
            'target' => $menu->target,
            'rel' => $menu->rel,
            'icon' => $menu->icon,
            'svg_path' => $this->getMenuIcon($menu->icon),
            'children' => $menu->children->map(function($child) {
                return $this->formatMenuData($child);
            })->toArray(),
        ];
    }

    protected function getMenuIcon($iconName)
    {
        Log::debug('Looking for icon:', [
            'icon_name' => $iconName,
            'available_icons' => array_keys($this->menuIcons)
        ]);

        return $this->menuIcons[$iconName] ?? null;
    }

    public function getSocialIcon($platform){
        if(isset($this->socialIcons[$platform])){
            return $this->socialIcons[$platform];
        }

        return [
            'svg' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2v-6h2v6zm-6-8h2l-3-3-3 3h2v4h2v-4z',
            'color' => '#6B7280',
            'name' => ucfirst($platform),
        ];
    }

    public function loadSiteSettings()
    {
        $this->siteSettings = SiteSetting::first();

        if ($this->siteSettings && $this->siteSettings->social_links) {
            $this->socialLinks = $this->siteSettings->social_links;
        }
    }

    public function getProfessionUrl($profession)
    {
        return route('people.profession.details', ['professionName' => $profession]);
    }


    public function render()
    {
        return view('livewire.front.footer-section');
    }
}
