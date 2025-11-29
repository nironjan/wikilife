<?php

namespace App\Livewire\Admin\Interview;

use App\Models\People;
use App\Models\SpeechesInterview;
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

    // Form Fields
    public $person_id = '';
    public $type = 'interview';
    public $title = '';
    public $description = '';
    public $content = '';
    public $location = '';
    public $date = '';
    public $url = '';
    public $thumbnail = null;
    public $existing_thumbnail = null;

    // Search fields
    public $person_search = '';

    // Image optimization
    public $imageWidth = 800;
    public $imageHeight = 600;
    public $imageQuality = 80;

    protected $imageKitService;

    protected function rules()
    {
        return [
            'person_id' => 'required|exists:people,id',
            'type' => 'required|in:interview,speech',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'url' => 'nullable|url|max:500',
            'thumbnail' => 'nullable|image|max:5120',

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
            $this->loadInterview($id);
        }
    }

    public function loadInterview($id)
    {
        $interview = SpeechesInterview::findOrFail($id);

        $this->person_id = $interview->person_id;
        $this->type = $interview->type;
        $this->title = $interview->title;
        $this->description = $interview->description;
        $this->content = $interview->content;
        $this->location = $interview->location;
        $this->date = optional($interview->date)->format('Y-m-d');
        $this->url = $interview->url;
        $this->existing_thumbnail = $interview->thumbnail_url;

        // Pre-fill search field
        if ($interview->person) {
            $this->person_search = $interview->person->display_name;
        }
    }

    /**
     * Get filtered people based on search
     */
    public function getPeopleProperty()
    {
        // Get all active people first
        $allPeople = People::active()
            ->orderBy('name')
            ->limit(50)
            ->get();

        // If no search term, return all people
        if (empty($this->person_search)) {
            return $allPeople;
        }

        $searchTerm = strtolower(trim($this->person_search));

        // Apply the same PHP filtering logic as search suggestions
        return $allPeople->filter(function($person) use ($searchTerm) {
            // Check name (case insensitive)
            if (stripos($person->name, $searchTerm) !== false) {
                return true;
            }

            // Check full_name (case insensitive)
            if ($person->full_name && stripos($person->full_name, $searchTerm) !== false) {
                return true;
            }

            // Check nicknames (case insensitive)
            $nicknames = $person->nicknames ?? [];
            foreach ($nicknames as $nickname) {
                if (stripos($nickname, $searchTerm) !== false) {
                    return true;
                }
            }

            // Check professions (case insensitive)
            $professions = $person->professions ?? [];
            foreach ($professions as $profession) {
                if (stripos($profession, $searchTerm) !== false) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * When person is selected, update search field
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
     * Clear person selection
     */
    public function clearPerson()
    {
        $this->person_id = '';
        $this->person_search = '';
    }

    /**
     * Remove thumbnail image from ImageKit and database
     */
    public function removeThumbnail()
    {
        try {
            if ($this->editingId && $this->existing_thumbnail) {
                $interview = SpeechesInterview::find($this->editingId);

                if ($interview && $interview->thumb_file_id) {
                    // Delete from ImageKit using the stored file ID
                    $this->imageKitService->deleteFile($interview->thumb_file_id);
                }

                // Update the interview record
                $interview->update([
                    'thumbnail_url' => null,
                    'thumb_file_id' => null,
                ]);

                $this->existing_thumbnail = null;
                Toaster::success('Thumbnail image removed successfully.');
            } else {
                Toaster::warning('No thumbnail found to remove.');
            }

            $this->thumbnail = null;

        } catch (Exception $e) {
            Toaster::error('Failed to remove thumbnail: ' . $e->getMessage());
        }
    }

    public function saveInterview()
    {
        $this->validate();

        try {
            $data = [
                'person_id' => $this->person_id,
                'type' => $this->type,
                'title' => $this->title,
                'description' => $this->description,
                'content' => $this->content,
                'location' => $this->location,
                'date' => $this->date ?: null,
                'url' => $this->url,
            ];

            $interview = DB::transaction(function () use ($data) {
                $interview = $this->editingId
                    ? tap(SpeechesInterview::findOrFail($this->editingId))->update($data)
                    : SpeechesInterview::create($data);

                // Upload thumbnail if provided
                if ($this->thumbnail) {
                    $this->uploadThumbnail($interview);
                }

                return $interview;
            });

            Toaster::success(
                $this->editingId ? 'Interview updated successfully.' : 'Interview created successfully.'
            );

            return redirect()->route('webmaster.persons.interviews.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save interview: ' . $e->getMessage());
        }
    }

    protected function uploadThumbnail(SpeechesInterview $interview)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->thumbnail,
                'interviews/thumbnails/',
                $this->imageWidth ?: null,
                $this->imageHeight ?: null,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old thumbnail from ImageKit if exists
            if ($interview->thumb_file_id) {
                $this->imageKitService->deleteFile($interview->thumb_file_id);
            }

            // Update interview with new thumbnail data
            $interview->update([
                'thumbnail_url' => $upload->url, // or optimizedUrl based on your ImageKitService
                'thumb_file_id' => $upload->fileId,
            ]);

            // Update the existing thumbnail display
            $this->existing_thumbnail = $upload->url;
            $this->thumbnail = null;

        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
     * Remove the newly selected thumbnail (before saving)
     */
    public function removeNewThumbnail()
    {
        $this->thumbnail = null;
        Toaster::info('New image selection removed.');
    }

    /**
     * Clean up temporary files when component is destroyed
     */
    public function cleanup()
    {
        if ($this->thumbnail) {
            $this->thumbnail->delete();
        }
    }

    public function render()
    {
        $types = [
            'interview' => 'Interview',
            'speech' => 'Speech',
        ];

        return view('livewire.admin.interview.manage', compact('types'));
    }
}
