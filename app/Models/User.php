<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\ImageKitService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'avatar',
        'avatar_file_id', // ImageKit file ID for deletion
        'profile_image',
        'profile_image_file_id', // ImageKit file ID for deletion
        'status',
        'is_team_member',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_team_member' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }


    // ====================== ATTRIBUTES =====================

    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image ?? $this->avatar ?? null;
    }

    public function getProfileImageFileIdAttribute()
    {
        return $this->attributes['profile_image_file_id'] ?? $this->attributes['avatar_file_id'] ?? null;
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ?? null;
    }

    /**
     * Get image with custom size using ImageKitService
     */
    public function imageSize($width = null, $height = null, $quality = null): ?string
    {
        $imageUrl = $this->profile_image_url;

        if (!$imageUrl) {
            return null;
        }

        // If no custom size requested, return original optimized image
        if (!$width && !$height && !$quality) {
            return $imageUrl;
        }

        // Use ImageKitService to generate custom size
        return app(ImageKitService::class)->getUrlWithTransformations(
            $this->extractBaseFilePath($imageUrl),
            $width,
            $height,
            $quality
        );
    }

    /**
     * Extract base file path from ImageKit URL
     */
    protected function extractBaseFilePath($imageKitUrl): ?string
    {
        try {
            $parsedUrl = parse_url($imageKitUrl);

            if (!isset($parsedUrl['path'])) {
                return null;
            }

            $path = $parsedUrl['path'];

            // Remove transformation part to get base path
            if (str_contains($path, '/tr:')) {
                $parts = explode('/tr:', $path, 2);
                if (isset($parts[1])) {
                    $transformationAndPath = $parts[1];
                    $firstSlash = strpos($transformationAndPath, '/');
                    if ($firstSlash !== false) {
                        return substr($transformationAndPath, $firstSlash);
                    }
                }
            }

            return $path;

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Delete profile image from ImageKit
     */
    public function deleteProfileImage(): bool
    {
        $fileId = $this->profile_image_file_id;

        if ($fileId) {
            try {
                $imageKitService = app(ImageKitService::class);
                $imageKitService->deleteFile($fileId);
            } catch (\Exception $e) {
                // Log error but don't fail the operation
                Log::error('Failed to delete ImageKit file: ' . $e->getMessage());
            }
        }

        // Clear image fields
        $this->update([
            'profile_image' => null,
            'profile_image_file_id' => null,
            'avatar' => null,
            'avatar_file_id' => null,
        ]);

        return true;
    }

    /**
     * Update profile image with ImageKit file ID
     */
    public function updateProfileImage($imageUrl, $fileId): bool
    {
        // Delete old image first
        $this->deleteProfileImage();

        return $this->update([
            'profile_image' => $imageUrl,
            'profile_image_file_id' => $fileId,
        ]);
    }

    // ===================== SCOPES ========================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeTeamMembers($query)
    {
        return $query->where('is_team_member', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeEditors($query)
    {
        return $query->where('role', 'editor');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeAuthors($query)
    {
        return $query->where('role', 'author');
    }

    // ================== RELATIONSHIPS ======================

    public function createdPeople()
    {
        return $this->hasMany(People::class, 'created_by');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'user_id');
    }

    // ============== CUSTOM METHODS =====================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    public function isAuthor(): bool
    {
        return $this->role === 'author';
    }

    public function canManageContent(): bool
    {
        return in_array($this->role, ['admin', 'editor']);
    }

    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'editor' => 'Editor',
            'author' => 'Author',
            default => ucfirst($this->role)
        };
    }




}
