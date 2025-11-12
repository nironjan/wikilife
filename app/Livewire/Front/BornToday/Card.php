<?php

namespace App\Livewire\Front\BornToday;

use App\Models\People;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
class Card extends Component
{
    use WithPagination;

    public $peopleBornToday = [];
    public $loading = true;

    public function mount()
    {
        $this->loadPeopleBornToday();
    }

    public function placeholder(){
        return view('livewire.front.born-today.born-today-skeleton');
    }

    public function loadPeopleBornToday()
    {
        $today = now('Asia/Kolkata');

        $this->peopleBornToday = People::active()
            ->verified()
            ->whereMonth('birth_date', $today->month)
            ->whereDay('birth_date', $today->day)
            ->orderBy('view_count', 'desc')
            ->limit(12)
            ->get(['id', 'name', 'slug', 'professions', 'profile_image', 'birth_date', 'view_count']);

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.front.born-today.card');
    }
}
