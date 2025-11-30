<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user can access editor features.
     */
    public function editorAccess(User $user): bool
    {
        return $user->isEditor() || $user->isAdmin();
    }

    /**
     * Determine if the user can manage people entries.
     */
    public function managePeople(User $user): bool
    {
        return $user->canManageContent();
    }

    /**
     * Determine if the user can edit the person entry.
     */
    public function editPerson(User $user, $person): bool
    {
        // Admin can edit all, editors can only edit their own
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isEditor()) {
            return $person->created_by === $user->id;
        }

        return false;
    }
}
