<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Services\ImageKitService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

#[Title("User Management")]
class Manage extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;
    public int $currentStep = 1;

    // Step 1: Basic Information
    public $name = '';
    public $email = '';
    public $role = 'author';
    public $status = 'active';
    public $is_team_member = false;

    // Step 2: Profile & Bio
    public $bio = '';
    public $profile_image = null;
    public $existing_profile_image = null;

    // Step 3: Security
    public $password = '';
    public $password_confirmation = '';

    // Image optimization
    public $imageWidth = 400;
    public $imageHeight = 400;
    public $imageQuality = 85;

    protected $imageKitService;

    protected $rules = [
        // Step 1
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,editor,author,user',
        'status' => 'required|in:active,inactive',
        'is_team_member' => 'boolean',

        // Step 2
        'bio' => 'nullable|string|max:500',
        'profile_image' => 'nullable|image|max:2048',

        // Step 3
        'password' => 'nullable|confirmed|min:8',
        'password_confirmation' => 'nullable',

        // Image settings
        'imageWidth' => 'nullable|integer|min:100|max:2000',
        'imageHeight' => 'nullable|integer|min:100|max:2000',
        'imageQuality' => 'nullable|integer|min:10|max:100',
    ];

    public function boot()
    {
        $this->imageKitService = new ImageKitService();
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->editingId = $id;
            $this->loadUser($id);
        }
    }

    public function loadUser($id)
    {
        $user = User::findOrFail($id);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->status = $user->status;
        $this->is_team_member = $user->is_team_member;
        $this->bio = $user->bio;
        $this->existing_profile_image = $user->profile_image_url;
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
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', $this->editingId ?
                    \Illuminate\Validation\Rule::unique('users', 'email')->ignore($this->editingId) :
                    'unique:users,email'
                ],
                'role' => 'required|in:admin,editor,author,user',
                'status' => 'required|in:active,inactive',
                'is_team_member' => 'boolean',
            ],
            2 => [
                'bio' => 'nullable|string|max:500',
                'profile_image' => 'nullable|image|max:2048',
            ],
            3 => [
                'password' => 'nullable|confirmed|min:8',
                'password_confirmation' => 'nullable',
            ]
        ];

        $this->validate($stepRules[$this->currentStep]);
    }

    public function save()
    {
        // Validate all steps before saving
        $rules = $this->rules;

        if ($this->editingId) {
            $rules['email'] = ['required', 'email', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($this->editingId)];

            // Only validate password if it's provided during edit
            if (empty($this->password)) {
                unset($rules['password']);
                unset($rules['password_confirmation']);
            }
        }

        $this->validate($rules);

        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'status' => $this->status,
                'is_team_member' => $this->is_team_member,
                'bio' => $this->bio,
            ];

            // Handle password
            if ($this->password) {
                $data['password'] = \Illuminate\Support\Facades\Hash::make($this->password);
            }

            $user = DB::transaction(function () use ($data) {
                $user = $this->editingId
                    ? tap(User::findOrFail($this->editingId))->update($data)
                    : User::create($data);

                // Upload profile image if provided
                if ($this->profile_image) {
                    $this->uploadProfileImage($user);
                }

                return $user;
            });

            Toaster::success($this->editingId ? 'User updated successfully.' : 'User created successfully.');

            return redirect()->route('webmaster.users.index');

        } catch (Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to save user: ' . $e->getMessage());
        }
    }

    protected function uploadProfileImage(User $user)
    {
        try {
            $upload = $this->imageKitService->uploadFile(
                $this->profile_image,
                'users/profile/',
                $this->imageWidth ?: 400,
                $this->imageHeight ?: 400,
                $this->imageQuality,
            );

            if (!$upload->success) {
                throw new Exception($upload->error);
            }

            // Delete old profile image if exists
            if ($user->profile_image_file_id) {
                $this->imageKitService->deleteFile($user->profile_image_file_id);
            }

            $user->update([
                'profile_image' => $upload->optimizedUrl,
                'profile_image_file_id' => $upload->fileId,
            ]);

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function removeProfileImage()
    {
        if ($this->editingId && $this->existing_profile_image) {
            $user = User::find($this->editingId);
            if ($user->profile_image_file_id) {
                $this->imageKitService->deleteFile($user->profile_image_file_id);
            }
            $user->update([
                'profile_image' => null,
                'profile_image_file_id' => null,
            ]);
            $this->existing_profile_image = null;
        }
        $this->profile_image = null;
        Toaster::success('Profile image removed successfully.');
    }

    public function render()
    {
        return view('livewire.admin.users.manage');
    }
}
