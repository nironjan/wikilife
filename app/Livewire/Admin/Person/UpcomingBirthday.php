<?php

namespace App\Livewire\Admin\Person;

use App\Models\People;
use Carbon\Carbon;
use Livewire\Component;

class UpcomingBirthday extends Component
{
    public $upcomingBirthdays = [];

    public function mount()
    {
        $this->loadUpcomingBirthdays();
    }

    public function loadUpcomingBirthdays()
{
    $today = Carbon::today();
    $next30Days = Carbon::today()->addDays(30);

    $this->upcomingBirthdays = People::with(['creator'])
        ->active()
        ->verified()
        ->whereNotNull('birth_date')
        ->get()
        ->filter(function ($person) use ($today, $next30Days) {
            $birthDate = Carbon::parse($person->birth_date);
            $currentYearBirthday = Carbon::create($today->year, $birthDate->month, $birthDate->day);

            // Adjust for birthdays that have already passed this year
            if ($currentYearBirthday->lt($today)) {
                $currentYearBirthday->addYear();
            }

            // Check if birthday is within the next 30 days
            return $currentYearBirthday->between($today, $next30Days);
        })
        ->sortBy(function ($person) use ($today) {
            $birthDate = Carbon::parse($person->birth_date);
            $nextBirthday = Carbon::create($today->year, $birthDate->month, $birthDate->day);

            if ($nextBirthday->lt($today)) {
                $nextBirthday->addYear();
            }

            return $nextBirthday->format('m-d');
        })
        ->take(10)
        ->map(function ($person) use ($today) {
            $birthDate = Carbon::parse($person->birth_date);
            $nextBirthday = Carbon::create($today->year, $birthDate->month, $birthDate->day);

            if ($nextBirthday->lt($today)) {
                $nextBirthday->addYear();
            }

            return [
                'id' => $person->id,
                'name' => $person->display_name,
                'slug' => $person->slug,
                'profile_image' => $person->profile_image_url,
                'professions' => $person->professions,
                'birth_date' => $person->birth_date,
                'formatted_birth_date' => $person->birth_date->format('M d'),
                'next_birthday' => $nextBirthday->format('M d'),
                'days_until_birthday' => $today->diffInDays($nextBirthday),
                'age' => $person->age,
                'next_age' => $nextBirthday->diffInYears($birthDate),
                'created_by' => $person->creator->name ?? 'System',
                'manage_url' => route('webmaster.persons.manage', $person->id)
            ];
        })
        ->sortBy('days_until_birthday')
        ->values()
        ->toArray();
}

    public function render()
    {
        return view('livewire.admin.person.upcoming-birthday');
    }
}
