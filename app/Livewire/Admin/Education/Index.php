<?php

namespace App\Livewire\Admin\Education;

use App\Models\PersonEducation;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $institution = '';
    public string $degree = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'institution' => ['except' => ''],
        'degree' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingInstitution()
    {
        $this->resetPage();
    }

    public function updatingDegree()
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

    public function deleteEducation($id)
    {
        try {
            $education = PersonEducation::findOrFail($id);
            $educationName = $education->degree . ' at ' . $education->institution;
            $education->delete();

            Toaster::success("Education '{$educationName}' deleted successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to delete education: " . $e->getMessage());
        }
    }

    public function toggleCompletion($id)
    {
        try {
            $education = PersonEducation::findOrFail($id);

            if ($education->end_year) {
                $education->end_year = null;
                $status = 'marked as ongoing';
            } else {
                $education->end_year = date('Y');
                $status = 'marked as completed';
            }

            $education->save();

            Toaster::success("Education {$status} successfully.");
        } catch (\Exception $e) {
            Toaster::error("Failed to update education: " . $e->getMessage());
        }
    }

    public function render()
    {
        $educations = PersonEducation::with(['person'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('degree', 'like', "%{$this->search}%")
                        ->orWhere('institution', 'like', "%{$this->search}%")
                        ->orWhere('field_of_study', 'like', "%{$this->search}%")
                        ->orWhere('grade_or_honors', 'like', "%{$this->search}%")
                        ->orWhere('location', 'like', "%{$this->search}%")
                        ->orWhereHas('person', function ($personQuery) {
                            $personQuery->where('name', 'like', "%{$this->search}%")
                                ->orWhere('full_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->institution, function ($query) {
                $query->where('institution', 'like', "%{$this->institution}%");
            })
            ->when($this->degree, function ($query) {
                $query->where('degree', 'like', "%{$this->degree}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $institutions = PersonEducation::distinct()->pluck('institution')->filter()->toArray();
        $degrees = PersonEducation::distinct()->pluck('degree')->filter()->toArray();

        return view('livewire.admin.education.index', compact('educations', 'institutions', 'degrees'));
    }
}
