<?php

namespace App\Livewire\Admin\Literature;

use App\Models\LiteratureCareer;
use App\Models\People;
use App\Models\PersonAward;
use App\Services\ImageKitService;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class Manage extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;
    public int $currentStep = 1;

    // Literature Career fields
    public $person_id = '';
    public $award_ids = [];
    public $role = '';
    public $media_type = '';
    public $start_date = '';
    public $end_date = '';
    public $title = '';
    public $work_type = '';
    public $publishing_year = '';
    public $language = '';
    public $genre = '';
    public $isbn = '';
    public $link = '';
    public $description = '';
    public $is_verified = false;
    public $sort_order = 0;

    // Search fields
    public $person_search = '';
    public $award_search = '';

    // Image field
    public $cover_image = null;
    public $existing_cover_image = null;

    // Image optimization
    public $imageWidth = 400;
    public $imageHeight = 600;
    public $imageQuality = 80;

    protected $imageKitService;


    protected function rules()
    {
        return [
            'person_id' => 'required|exists:people,id',
            'award_ids' => 'nullable|array',
            'award_ids.*' => 'exists:person_awards,id',
            'role' => 'required|string|max:255',
            'media_type' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'title' => 'required|string|max:255',
            'work_type' => 'required|string|max:255',
            'publishing_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'language' => 'required|string|max:100',
            'genre' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'cover_image' => 'nullable|image|max:2048',
            'link' => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'is_verified' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',

            'imageWidth' => 'nullable|integer|min:100|max:4000',
            'imageHeight' => 'nullable|integer|min:100|max:4000',
            'imageQuality' => 'nullable|integer|min:10|max:100',
        ];
    }

    public function boot()
    {
        $this->imageKitService = new ImageKitService();
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadLiteratureCareer($id);
        } else {
            $this->award_ids = [];
            $this->is_verified = false;
            $this->sort_order = 0;
        }
    }

    public function loadLiteratureCareer($id)
    {
        $literatureCareer = LiteratureCareer::findOrFail($id);

        $this->person_id = $literatureCareer->person_id;
        $this->award_ids = $literatureCareer->award_ids ?? [];
        $this->role = $literatureCareer->role;
        $this->media_type = $literatureCareer->media_type;
        $this->start_date = optional($literatureCareer->start_date)->format('Y-m-d');
        $this->end_date = optional($literatureCareer->end_date)->format('Y-m-d');
        $this->title = $literatureCareer->title;
        $this->work_type = $literatureCareer->work_type;
        $this->publishing_year = $literatureCareer->publishing_year;
        $this->language = $literatureCareer->language;
        $this->genre = $literatureCareer->genre;
        $this->isbn = $literatureCareer->isbn;
        $this->existing_cover_image = $literatureCareer->cover_image;
        $this->link = $literatureCareer->link;
        $this->description = $literatureCareer->description;
        $this->is_verified = $literatureCareer->is_verified;
        $this->sort_order = $literatureCareer->sort_order;

        if ($literatureCareer->person) {
            $this->person_search = $literatureCareer->person->display_name;
        }
    }

    public function getPeopleProperty()
    {
        return People::active()
            ->when($this->person_search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->person_search}%")
                        ->orWhere('full_name', 'like', "%{$this->person_search}%");
                });
            })
            ->byProfessionCategory('literature')
            ->orderBy('name')
            ->limit(50)
            ->get();
    }

    public function getAwardsProperty()
    {
        return PersonAward::with('person')
            ->when($this->award_search, function ($query) {
                $query->where(function ($q) {
                    $q->where('award_name', 'like', "%{$this->award_search}%")
                        ->orWhere('organization', 'like', "%{$this->award_search}%");
                });
            })
            ->whereHas('person', function ($query) {
                $query->byProfessionCategory('literature');
            })
            ->orderBy('awarded_at', 'desc')
            ->limit(50)
            ->get();
    }

    public function getSelectedAwardsProperty()
    {
        if (empty($this->award_ids)) {
            return collect();
        }
        return PersonAward::whereIn('id', $this->award_ids)->get();
    }

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

    public function clearPerson()
    {
        $this->person_id = '';
        $this->person_search = '';
    }

    public function addAward($awardId)
    {
        if (!in_array($awardId, $this->award_ids)) {
            $this->award_ids[] = $awardId;
            $this->award_search = '';
        }
    }

    public function removeAward($awardId)
    {
        $this->award_ids = array_filter($this->award_ids, function ($id) use ($awardId) {
            return $id != $awardId;
        });
    }

    public function clearAwards()
    {
        $this->award_ids = [];
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
        $stepRules = [
            1 => [
                'person_id' => 'required|exists:people,id',
                'role' => 'required|string|max:255',
                'media_type' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'work_type' => 'required|string|max:255',
            ],
            2 => [
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'publishing_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
                'language' => 'required|string|max:100',
                'genre' => 'required|string|max:255',
                'isbn' => 'nullable|string|max:20',
                'cover_image' => 'nullable|image|max:2048',
                'link' => 'nullable|url|max:500',
            ],
            3 => [
                'award_ids' => 'nullable|array',
                'award_ids.*' => 'exists:person_awards,id',
                'description' => 'nullable|string',
                'is_verified' => 'boolean',
                'sort_order' => 'nullable|integer|min:0',
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
                'award_ids' => $this->award_ids,
                'role' => $this->role,
                'media_type' => $this->media_type,
                'start_date' => $this->start_date ?: null,
                'end_date' => $this->end_date ?: null,
                'title' => $this->title,
                'work_type' => $this->work_type,
                'publishing_year' => $this->publishing_year ?: null,
                'language' => $this->language,
                'genre' => $this->genre,
                'isbn' => $this->isbn,
                'link' => $this->link,
                'description' => $this->description,
                'is_verified' => $this->is_verified,
                'sort_order' => $this->sort_order ?: 0,
            ];



            $literature = DB::transaction(function () use ($data) {
                $literature = $this->editingId
                    ? tap(LiteratureCareer::findOrFail($this->editingId))->update($data)
                    : LiteratureCareer::create($data);

                // Uplod images if provided
                if ($this->cover_image) {
                    $this->uploadCoverImage($literature);
                }

                return $literature;
            });

            Toaster::success($this->editingId ? 'Literature career updated successfully.' : 'Literature career created successfully.');

            return redirect()->route('webmaster.persons.literature.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save literature career:' . $e->getMessage());
        }
    }

    protected function uploadCoverImage(LiteratureCareer $literature)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->cover_image,
                'literatuire/cover/',
                $this->imageWidth ?: null,
                $this->imageHeight ?: null,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old cover image if exists
            if ($literature->img_file_id) {
                $this->imageKitService->deleteFile($literature->img_file_id);
            }

            $literature->update([
                'cover_image' => $upload->optimizedUrl,
                'img_file_id' => $upload->fileId,
            ]);

        } catch (Exception $e) {
            throw $e;
        }

    }

    public function removeCoverImage()
    {
        if ($this->editingId && $this->existing_cover_image) {
            $literature = LiteratureCareer::find($this->editingId);
            if ($literature->img_file_id) {
                $this->imageKitService->deleteFile($literature->img_file_id);
            }
            $literature->update([
                'cover_image' => null,
                'img_file_id' => null,
            ]);
            $this->existing_cover_image = null;
        }
        $this->cover_image = null;
        Toaster::success('Cover image removed successfully.');
    }

    /**
     * Clean up temporary files when component is destroyed
     */
    public function cleanup()
    {
        if ($this->cover_image) {
            $this->cover_image->delete();
        }
    }

    public function render()
    {
        $commonRoles = [
            'Author',
            'Writer',
            'Novelist',
            'Poet',
            'Playwright',
            'Journalist',
            'Editor',
            'Columnist',
            'Blogger',
            'Content Writer',
            'Screenwriter',
            'Copywriter',
            'Ghostwriter',
            'Translator',
            'Critic',
        ];

        $commonMediaTypes = [
            'Print',
            'Digital',
            'TV',
            'Radio',
            'Online Portal',
            'Newspaper',
            'Magazine',
            'Book',
            'E-book',
            'Audiobook',
        ];

        $commonWorkTypes = [
            'Novel',
            'Short Story',
            'Poetry',
            'Play',
            'Screenplay',
            'Article',
            'Blog Post',
            'Column',
            'Essay',
            'Review',
            'Biography',
            'Autobiography',
            'Memoir',
            'Textbook',
            'Research Paper',
        ];

        $commonGenres = [
            'Fiction',
            'Non-Fiction',
            'Science Fiction',
            'Fantasy',
            'Mystery',
            'Thriller',
            'Romance',
            'Horror',
            'Historical',
            'Biography',
            'Autobiography',
            'Poetry',
            'Drama',
            'Comedy',
            'Tragedy',
            'Young Adult',
            'Children',
            'Self-Help',
            'Business',
            'Technology',
        ];

        $commonLanguages = [
            'English',
            'Hindi',
            'Bengali',
            'Telugu',
            'Marathi',
            'Tamil',
            'Urdu',
            'Gujarati',
            'Kannada',
            'Odia',
            'Malayalam',
            'Punjabi',
            'Sanskrit',
            'Spanish',
            'French',
            'German',
            'Chinese',
            'Japanese',
        ];
        return view('livewire.admin.literature.manage', compact(
            'commonRoles',
            'commonMediaTypes',
            'commonWorkTypes',
            'commonGenres',
            'commonLanguages'
        ));
    }
}
