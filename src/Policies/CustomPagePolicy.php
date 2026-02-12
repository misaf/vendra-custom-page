<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\VendraCustomPage\Enums\CustomPagePolicyEnum;
use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraUser\Models\User;

final class CustomPagePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can(CustomPagePolicyEnum::CREATE);
    }

    public function delete(User $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::DELETE);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can(CustomPagePolicyEnum::DELETE_ANY);
    }

    public function forceDelete(User $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::FORCE_DELETE);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can(CustomPagePolicyEnum::FORCE_DELETE_ANY);
    }

    public function reorder(User $user): bool
    {
        return $user->can(CustomPagePolicyEnum::REORDER);
    }

    public function replicate(User $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::REPLICATE);
    }

    public function restore(User $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::RESTORE);
    }

    public function restoreAny(User $user): bool
    {
        return $user->can(CustomPagePolicyEnum::RESTORE_ANY);
    }

    public function update(User $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::UPDATE);
    }

    public function view(User $user, CustomPage $customPage): bool
    {
        return $user->can(CustomPagePolicyEnum::VIEW);
    }

    public function viewAny(User $user): bool
    {
        return $user->can(CustomPagePolicyEnum::VIEW_ANY);
    }
}
