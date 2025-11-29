<?php

namespace App\Livewire\Admin\Relation;

use App\Models\PersonRelations;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $relationType = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function deleteRelation($id)
    {
        try {
            $relation = PersonRelations::findOrFail($id);
            $relationName = $relation->related_person_name;
            $relation->delete();

            Toaster::success("Relation '{$relationName}' deleted successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to delete relation: " . $e->getMessage());
        }
    }

    public function toggleReciprocal($id)
    {
        try {
            $relation = PersonRelations::findOrFail($id);
            $relation->is_reciprocal = !$relation->is_reciprocal;
            $relation->save();

            $status = $relation->is_reciprocal ? 'marked as reciprocal' : 'marked as non-reciprocal';
            Toaster::success("Relation {$status} successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update relation: " . $e->getMessage());
        }
    }


    public function render()
    {
        try {
            $relations = PersonRelations::with(['person', 'relatedPerson'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $searchTerm = $this->search;
                        $searchTermLower = strtolower($searchTerm);
                        $searchTermTitle = ucwords($searchTermLower);

                        $q->whereHas('person', function ($personQuery) use ($searchTermLower, $searchTerm, $searchTermTitle) {
                            // Person name search
                            $personQuery->whereRaw('LOWER(name) LIKE ?', ["%{$searchTermLower}%"])
                                ->orWhereRaw('LOWER(full_name) LIKE ?', ["%{$searchTermLower}%"])

                                // Person nicknames search
                                ->orWhereJsonContains('nicknames', $searchTerm)
                                ->orWhereJsonContains('nicknames', $searchTermLower)
                                ->orWhereJsonContains('nicknames', $searchTermTitle)

                                // Person professions search
                                ->orWhere(function ($professionQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                                    $professionQuery->orWhereRaw('professions::text LIKE ?', ["%{$searchTerm}%"])
                                                ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermLower}%"])
                                                ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermTitle}%"]);
                                });
                        })
                        ->orWhereHas('relatedPerson', function ($relatedQuery) use ($searchTermLower, $searchTerm, $searchTermTitle) {
                            // Related person name search
                            $relatedQuery->whereRaw('LOWER(name) LIKE ?', ["%{$searchTermLower}%"])
                                ->orWhereRaw('LOWER(full_name) LIKE ?', ["%{$searchTermLower}%"])

                                // Related person nicknames search
                                ->orWhereJsonContains('nicknames', $searchTerm)
                                ->orWhereJsonContains('nicknames', $searchTermLower)
                                ->orWhereJsonContains('nicknames', $searchTermTitle)

                                // Related person professions search
                                ->orWhere(function ($professionQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                                    $professionQuery->orWhereRaw('professions::text LIKE ?', ["%{$searchTerm}%"])
                                                ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermLower}%"])
                                                ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermTitle}%"]);
                                });
                        })
                        ->orWhereRaw('LOWER(related_person_name) LIKE ?', ["%{$searchTermLower}%"]);
                    });
                })
                ->when($this->relationType, function ($query) {
                    $query->where('relation_type', $this->relationType);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(15);

        } catch (\Exception $e) {
            logger("PersonRelations search error: " . $e->getMessage());
            $relations = PersonRelations::where('id', 0)->paginate(15);
        }

        $relationTypes = [
            'parent' => 'Parent',
            'sibling' => 'Sibling',
            'spouse' => 'Spouse',
            'child' => 'Child',
            'other' => 'Other',
        ];
        return view('livewire.admin.relation.index', compact('relations', 'relationTypes'));
    }
}
