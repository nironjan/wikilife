<?php

namespace App\Livewire\Front\PopularPerson;

use App\Models\People;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Index extends Component
{
    public $title = 'Popular Persons';
    public $description = 'Discover the most viewed and admired personalities from various fields';
    public $limit = 8; // Configurable limit
    public $excludeBornToday = true; // Configurable filter
    public $layout = 'default'; // 'default', 'compact', 'minimal'
    public $showStats = true;
    public $showRankings = true;

    public function placeholder()
    {
        return view('livewire.front.popular-person.popular-person-skeleton', [
            'layout' => $this->layout,
            'limit' => $this->limit
        ]);
    }

    public function getPopularPersonsProperty()
    {
        $query = People::active()
            ->verified()
            ->orderBy('view_count', 'desc')
            ->orderBy('like_count', 'desc')
            ->orderBy('created_at', 'desc');

        // Configurable filter
        if ($this->excludeBornToday) {
            $today = now();
            $query->where(function ($q) use ($today) {
                $q->whereMonth('birth_date', '!=', $today->month)
                  ->orWhereDay('birth_date', '!=', $today->day);
            });
        }

        return $query->limit($this->limit)->get();
    }

    public function render()
    {
        return view('livewire.front.popular-person.index', [
            'popularPersons' => $this->getPopularPersonsProperty()
        ]);
    }
}
