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
        try {
            $educations = PersonEducation::with(['person'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $searchTerm = $this->search;
                        $searchTermLower = strtolower($searchTerm);
                        $searchTermTitle = ucwords($searchTermLower);

                        // Case-sensitive search on education fields
                        $q->whereRaw('LOWER(degree) LIKE ?', ["%{$searchTermLower}%"])
                            ->orWhereRaw('LOWER(institution) LIKE ?', ["%{$searchTermLower}%"])
                            ->orWhereRaw('LOWER(field_of_study) LIKE ?', ["%{$searchTermLower}%"])
                            ->orWhereRaw('LOWER(grade_or_honors) LIKE ?', ["%{$searchTermLower}%"])
                            ->orWhereRaw('LOWER(location) LIKE ?', ["%{$searchTermLower}%"])

                            // Search by person name and full name
                            ->orWhereHas('person', function ($personQuery) use ($searchTermLower) {
                                $personQuery->whereRaw('LOWER(name) LIKE ?', ["%{$searchTermLower}%"])
                                    ->orWhereRaw('LOWER(full_name) LIKE ?', ["%{$searchTermLower}%"]);
                            })

                            // Search by person nicknames
                            ->orWhereHas('person', function ($personQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                                $personQuery->whereJsonContains('nicknames', $searchTerm)
                                    ->orWhereJsonContains('nicknames', $searchTermLower)
                                    ->orWhereJsonContains('nicknames', $searchTermTitle);
                            })

                            // Search by person professions
                            ->orWhereHas('person', function ($personQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                                $personQuery->where(function ($professionQuery) use ($searchTerm, $searchTermLower, $searchTermTitle) {
                                    $professionQuery->orWhereRaw('professions::text LIKE ?', ["%{$searchTerm}%"])
                                                ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermLower}%"])
                                                ->orWhereRaw('professions::text LIKE ?', ["%{$searchTermTitle}%"]);
                                });
                            });
                    });
                })
                ->when($this->institution, function ($query) {
                    $searchTerm = strtolower($this->institution);
                    $query->whereRaw('LOWER(institution) LIKE ?', ["%{$searchTerm}%"]);
                })
                ->when($this->degree, function ($query) {
                    $searchTerm = strtolower($this->degree);
                    $query->whereRaw('LOWER(degree) LIKE ?', ["%{$searchTerm}%"]);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(15);

        } catch (\Exception $e) {
            logger("PersonEducation search error: " . $e->getMessage());
            $educations = PersonEducation::where('id', 0)->paginate(15);
        }

        $institutions = PersonEducation::distinct()->pluck('institution')->filter()->toArray();
        $degrees = PersonEducation::distinct()->pluck('degree')->filter()->toArray();

        return view('livewire.admin.education.index', compact('educations', 'institutions', 'degrees'));
    }
}
