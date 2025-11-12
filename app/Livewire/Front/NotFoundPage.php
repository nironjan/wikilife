<?php

namespace App\Livewire\Front;

use App\Models\People;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('components.layouts.front')]
class NotFoundPage extends Component
{
    #[Url]
    public $search = '';

    public $suggestedPeople = [];
    public $popularPeople = [];

    public function mount()
    {
        $this->loadSuggestedPeople();
        $this->loadPopularPeople();
    }

    public function loadSuggestedPeople()
    {
        $this->suggestedPeople = People::active()
            ->verified()
            ->with(['creator'])
            ->inRandomOrder()
            ->limit(6)
            ->get()
            ->map(function ($person) {
                return [
                    'id' => $person->id,
                    'name' => $person->display_name,
                    'slug' => $person->slug,
                    'profile_image' => $person->profile_image_url,
                    'professions' => $person->professions,
                    'age' => $person->age,
                    'url' => route('people.people.show', $person->slug)
                ];
            })
            ->toArray();
    }

    public function loadPopularPeople()
    {
        $this->popularPeople = People::active()
            ->verified()
            ->with(['creator'])
            ->orderBy('view_count', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($person) {
                return [
                    'id' => $person->id,
                    'name' => $person->display_name,
                    'slug' => $person->slug,
                    'profile_image' => $person->profile_image_url,
                    'professions' => $person->professions,
                    'view_count' => $person->view_count,
                    'url' => route('people.people.show', $person->slug)
                ];
            })
            ->toArray();
    }

    public function performSearch()
    {
        if (empty($this->search)) {
            return;
        }

        return redirect()->route('people.search', ['q' => $this->search]);
    }

    public function render()
    {
        return view('livewire.front.not-found-page');
    }
}
