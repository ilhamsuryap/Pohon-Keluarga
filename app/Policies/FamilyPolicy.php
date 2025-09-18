<?php

namespace App\Policies;

use App\Models\Family;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FamilyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Family $family)
    {
        return $user->id === $family->user_id;
    }

    public function create(User $user)
    {
        // User can only create one family
        return !$user->families()->exists();
    }

    public function update(User $user, Family $family)
    {
        return $user->id === $family->user_id;
    }

    public function delete(User $user, Family $family)
    {
        return $user->id === $family->user_id;
    }
}
