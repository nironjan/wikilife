<?php

namespace App\Livewire\Admin\Fact;

use App\Models\LesserKnownFact;
use App\Models\People;
use Exception;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Manage extends Component
{
    public $factId;
    public $fact;
    public $people;

    // Form Fields
    public $person_id = '';
    public $title = '';
    public $fact_text = '';
    public $category = '';

    // Search fields
    public $person_search = '';


    protected function rules()
    {
        return [
            'person_id' => 'required|exists:people,id',
            'title' => 'required|string|max:255',
            'fact_text' => 'required|string',
            'category' => 'nullable|string|max:100',
        ];
    }

    public function mount($id = null)
    {
        $this->people = People::where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($id) {
            $this->factId = $id;
            $this->fact = LesserKnownFact::findOrFail($id);
            $this->loadFactData($id);
        }
    }

    public function loadFactData($id)
    {
        $data = LesserKnownFact::findOrFail($id);

        $this->person_id = $data->person_id;
        $this->title = $data->title;
        $this->fact_text = $data->fact;
        $this->category = $data->category;

        // Pre-fill search field
        if ($data->person) {
            $this->person_search = $data->person->display_name;
        }
    }

    public function saveFact()
    {
        $this->validate();

        try {
            $factData = [
                'person_id' => $this->person_id,
                'title' => $this->title,
                'fact' => $this->fact_text,
                'category' => $this->category ?: null,
            ];

            if ($this->factId) {
                $this->fact->update($factData);
            } else {
                LesserKnownFact::create($factData);
            }

            Toaster::success(
                $this->factId ? 'Fact updated successfully.' : 'Fact created successfully.'
            );

            return $this->redirectIntended(route('webmaster.persons.facts.index'));

            if (!$this->factId) {
                return redirect()->route('webmaster.persons.facts.index');
            }

        } catch (Exception $e) {
            Toaster::error('Failed to save fact: ' . $e->getMessage());
        }
    }


    public function render()
    {
        $categories = LesserKnownFact::whereNotNull('category')
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        return view('livewire.admin.fact.manage', compact('categories'));
    }
}
