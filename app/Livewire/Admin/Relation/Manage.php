<?php

namespace App\Livewire\Admin\Relation;

use App\Models\People;
use App\Models\PersonRelations;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Manage extends Component
{

    public ?int $editingId = null;
    public int $currentStep = 1;

    // Relation fields
    public $person_id = '';
    public $related_person_id = '';
    public $related_person_name = '';
    public $marital_status = '';
    public $relation_type = '';
    public $is_reciprocal = false;
    public $notes = '';
    public $since = '';
    public $until = '';
    public $related_person_death_year = '';

    public $person_search = '';
    public $related_person_search = '';

    /**
     * Get validation rules
     */
    protected function rules()
    {
        $currentYear = date('Y');
        $maxYear = $currentYear + 1;

        return [
            'person_id' => 'required|exists:people,id',
            'related_person_id' => 'nullable|exists:people,id',
            'related_person_name' => 'required_without:related_person_id|string|max:255',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,other',
            'relation_type' => 'required|in:parent,sibling,spouse,child,other',
            'is_reciprocal' => 'boolean',
            'notes' => 'nullable|string|max:1000',
            'since' => 'nullable|integer|min:1900|max:' . $maxYear,
            'until' => 'nullable|integer|min:1900|max:' . $maxYear,
            'related_person_death_year' => 'nullable|integer|min:1900|max:' . $maxYear,
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadRelation($id);
        }
    }

    public function loadRelation($id)
    {
        $relation = PersonRelations::findOrFail($id);

        $this->person_id = $relation->person_id;
        $this->related_person_id = $relation->related_person_id;
        $this->related_person_name = $relation->related_person_name;
        $this->marital_status = $relation->marital_status;
        $this->relation_type = $relation->relation_type;
        $this->is_reciprocal = $relation->is_reciprocal;
        $this->notes = $relation->notes;
        $this->since = $relation->since;
        $this->until = $relation->until;
        $this->related_person_death_year = $relation->related_person_death_year;

        if ($relation->person) {
            $this->person_search = $relation->person->display_name;
        }

        if ($relation->relatedPerson) {
            $this->related_person_search = $relation->relatedPerson->display_name;
        }
    }

    /**
     * Get filtered people based on search
     */
    public function getPeopleProperty()
    {
        return People::active()
            ->when($this->person_search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->person_search}%")
                        ->orWhere('full_name', 'like', "%{$this->person_search}%")
                        ->orWhereJsonContains('nicknames', $this->person_search);
                });
            })
            ->orderBy('name')
            ->limit(20) // Limit results for performance
            ->get();
    }

    /**
     * Get filtered related people based on search
     */
    public function getRelatedPeopleProperty()
    {
        return People::active()
            ->when($this->related_person_search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->related_person_search}%")
                        ->orWhere('full_name', 'like', "%{$this->related_person_search}%")
                        ->orWhereJsonContains('nicknames', $this->related_person_search);
                });
            })
            ->orderBy('name')
            ->limit(20) // Limit results for performance
            ->get();
    }

    /**
     * When person is selected, update the search field
     */
    public function updatedPersonId($value)
    {
        if ($value) {
            $person = People::find($value);
            if ($person) {
                $this->person_search = $person->display_name;
            }
        } else {
            $this->person_search = '';
        }
    }


    /**
     * When related person is selected, update the search field
     */
    public function updatedRelatedPersonId($value)
    {
        if ($value) {
            $person = People::find($value);
            if ($person) {
                $this->related_person_search = $person->display_name;
            }
        } else {
            $this->related_person_search = '';
        }
    }

    /**
     * Clear person selection and search
     */
    public function clearPerson()
    {
        $this->person_id = '';
        $this->person_search = '';
    }

    /**
     * Clear related person selection and search
     */
    public function clearRelatedPerson()
    {
        $this->related_person_id = '';
        $this->related_person_search = '';
        $this->related_person_name = '';
    }

    /**
     * When related person name is manually entered, clear the related person ID
     */
    public function updatedRelatedPersonName($value)
    {
        if (!empty($value)) {
            $this->related_person_id = '';
            $this->related_person_search = '';
        }
    }

    public function nextStep()
    {
        $this->validateCurrentStep();

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function validateCurrentStep()
    {
        $currentYear = date('Y');
        $maxYear = $currentYear + 1;

        $stepRules = [
            1 => [
                'person_id' => 'required|exists:people,id',
                'related_person_id' => 'nullable|exists:people,id',
                'related_person_name' => 'required_without:related_person_id|string|max:255',
            ],
            2 => [
                'relation_type' => 'required|in:parent,sibling,spouse,child,other',
                'marital_status' => 'nullable|in:single,married,divorced,widowed,other',
                'is_reciprocal' => 'boolean',
            ],
            3 => [
                'since' => 'nullable|integer|min:1900|max:' . $maxYear,
                'until' => 'nullable|integer|min:1900|max:' . $maxYear,
                'related_person_death_year' => 'nullable|integer|min:1900|max:' . $maxYear,
                'notes' => 'nullable|string|max:1000',
            ]
        ];

        $this->validate($stepRules[$this->currentStep]);
    }


    public function save()
    {
        $this->validate();

        try {
            $data = [
                'person_id' => $this->person_id,
                'related_person_id' => $this->related_person_id ?: null,
                'related_person_name' => $this->related_person_name,
                'marital_status' => $this->marital_status ?: null,
                'relation_type' => $this->relation_type,
                'is_reciprocal' => $this->is_reciprocal,
                'notes' => $this->notes,
                'since' => $this->since ?: null,
                'until' => $this->until ?: null,
                'related_person_death_year' => $this->related_person_death_year ?: null,
            ];

            $relation = DB::transaction(function () use ($data) {
                return $this->editingId
                    ? tap(PersonRelations::findOrFail($this->editingId))->update($data)
                    : PersonRelations::create($data);
            });

            Toaster::success($this->editingId ? 'Relation updated successfully.' : 'Relation created successfully.');

            return redirect()->route('webmaster.persons.relation.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save relation: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $people = People::active()->orderBy('name')->get();

        $relationTypes = [
            'parent' => 'Parent',
            'sibling' => 'Sibling',
            'spouse' => 'Spouse',
            'child' => 'Child',
            'other' => 'Other',
        ];

        $maritalStatuses = [
            'single' => 'Single',
            'married' => 'Married',
            'divorced' => 'Divorced',
            'widowed' => 'Widowed',
            'other' => 'Other',
        ];

        return view('livewire.admin.relation.manage', compact('people', 'relationTypes', 'maritalStatuses'));
    }
}
