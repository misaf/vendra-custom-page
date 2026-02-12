<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Misaf\VendraCustomPage\Enums\CustomPageCategoryPolicyEnum;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraUser\Models\User;

final class CustomPageCategoryPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::CREATE);
    }

    public function delete(User $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::DELETE);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::DELETE_ANY);
    }

    public function forceDelete(User $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::FORCE_DELETE);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::FORCE_DELETE_ANY);
    }

    public function reorder(User $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::REORDER);
    }

    public function replicate(User $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::REPLICATE);
    }

    public function restore(User $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::RESTORE);
    }

    public function restoreAny(User $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::RESTORE_ANY);
    }

    public function update(User $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::UPDATE);
    }

    public function view(User $user, CustomPageCategory $customPageCategory): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::VIEW);
    }

    public function viewAny(User $user): bool
    {
        return $user->can(CustomPageCategoryPolicyEnum::VIEW_ANY);
    }
}
