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
        $relations = PersonRelations::with(['person', 'relatedPerson'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('person', function ($personQuery) {
                        $personQuery->where('name', 'like', "%{$this->search}%")
                            ->orWhere('full_name', 'like', "%{$this->search}%");
                    })
                        ->orWhereHas('relatedPerson', function ($relatedQuery) {
                            $relatedQuery->where('name', 'like', "%{$this->search}%")
                                ->orWhere('full_name', 'like', "%{$this->search}%");
                        })
                        ->orWhere('related_person_name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->relationType, function ($query) {
                $query->where('relation_type', $this->relationType);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

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
