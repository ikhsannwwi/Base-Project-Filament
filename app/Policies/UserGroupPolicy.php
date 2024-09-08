<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AdminMenu;
use App\Models\UserGroup;
use Illuminate\Auth\Access\Response;

class UserGroupPolicy
{
    protected function userMenuId(){
        $menu = AdminMenu::where('name', 'User Group')->first();
        return $menu ? $menu->id : null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->user_group_id === 0) {
            return true;
        }

        $adminMenuId = $this->userMenuId();
        if ($adminMenuId === null) {
            return false;
        }

        $user->load('usergroup.UserGroupPermission');

        // $key_permission = 0;
        // foreach ($user->usergroup->UserGroupPermission as $key => $value) {
        //     if ($value->admin_menu_id === $adminMenuId) {
        //         $key_permission += $key;
        //         break;
        //     }
        // }
        // $hasPermission = $user->usergroup->UserGroupPermission[$key_permission]->view;

        $hasPermission = $user->usergroup->UserGroupPermission->contains(function ($permission) use ($adminMenuId) {
            return $permission->admin_menu_id === $adminMenuId && $permission->index;
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserGroup $model): bool
    {
        if ($user->user_group_id === 0) {
            return true;
        }

        $adminMenuId = $this->userMenuId();
        if ($adminMenuId === null) {
            return false;
        }

        $user->load('usergroup.UserGroupPermission');

        $hasPermission = $user->usergroup->UserGroupPermission->contains(function ($permission) use ($adminMenuId) {
            return $permission->admin_menu_id === $adminMenuId && $permission->view;
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->user_group_id === 0) {
            return true;
        }

        $adminMenuId = $this->userMenuId();
        if ($adminMenuId === null) {
            return false;
        }

        $user->load('usergroup.UserGroupPermission');

        $hasPermission = $user->usergroup->UserGroupPermission->contains(function ($permission) use ($adminMenuId) {
            return $permission->admin_menu_id === $adminMenuId && $permission->create;
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserGroup $model): bool
    {
        if ($user->user_group_id === 0) {
            return true;
        }

        $adminMenuId = $this->userMenuId();
        if ($adminMenuId === null) {
            return false;
        }

        $user->load('usergroup.UserGroupPermission');

        $hasPermission = $user->usergroup->UserGroupPermission->contains(function ($permission) use ($adminMenuId) {
            return $permission->admin_menu_id === $adminMenuId && $permission->edit;
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserGroup $model): bool
    {
        if ($user->user_group_id === 0) {
            return true;
        }

        $adminMenuId = $this->userMenuId();
        if ($adminMenuId === null) {
            return false;
        }

        $user->load('usergroup.UserGroupPermission');

        $hasPermission = $user->usergroup->UserGroupPermission->contains(function ($permission) use ($adminMenuId) {
            return $permission->admin_menu_id === $adminMenuId && $permission->destroy;
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserGroup $model): bool
    {
        if ($user->user_group_id === 0) {
            return true;
        }

        $adminMenuId = $this->userMenuId();
        if ($adminMenuId === null) {
            return false;
        }

        $user->load('usergroup.UserGroupPermission');

        $hasPermission = $user->usergroup->UserGroupPermission->contains(function ($permission) use ($adminMenuId) {
            return $permission->admin_menu_id === $adminMenuId && $permission->restore;
        });

        return $hasPermission;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserGroup $model): bool
    {
        if ($user->user_group_id === 0) {
            return true;
        }

        $adminMenuId = $this->userMenuId();
        if ($adminMenuId === null) {
            return false;
        }

        $user->load('usergroup.UserGroupPermission');

        $hasPermission = $user->usergroup->UserGroupPermission->contains(function ($permission) use ($adminMenuId) {
            return $permission->admin_menu_id === $adminMenuId && $permission->force_delete;
        });

        return $hasPermission;
    }
}
