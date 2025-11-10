<?php

namespace App\Livewire\Partials;

use App\Models\People;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class RecentPersonList extends Component
{
    public $persons;

    public function mount()
    {
        $this->loadRecentPersons();
    }

    public function placeholder(){
        return view('livewire.partials.recent-person-skeleton');
    }

    public function loadRecentPersons()
    {
        $this->persons = People::with(['seo'])
            ->active()
            ->verified()
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get()
            ->map(function ($person) {
                return [
                    'id' => $person->id,
                    'name' => $person->display_name,
                    'slug' => $person->slug,
                    'profile_image' => $person->profile_image_url,
                    'profile_image_small' => $person->imageSize(300, 300, 80),
                    'primary_profession' => $person->primary_profession,
                    'age' => $person->age,
                    'is_alive' => $person->is_alive,
                    'seo_title' => $person->seo->meta_title ?? $person->display_name,
                ];
            });
    }


    public function render()
    {
        return view('livewire.partials.recent-person-list');
    }
}
