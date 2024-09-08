<?php

namespace App\Models;

use App\Helpers\LogSystemHelpers;
use App\Models\UserGroupPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserGroup extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (UserGroup $userGroup) {
            $userGroup->UserGroupPermission()->delete();
        });
    }

    protected static function booted()
    {
        static::created(function ($model) {
            $model->load('UserGroupPermission');
            $user = Auth::user();

            if ($user) {
                LogSystemHelpers::createLog(
                    'User Group',
                    'store',
                    $model->id,
                    $model->toArray(),
                    $user
                );
            } else {
                \Log::warning('No authenticated user while creating model.');
            }
        });

        static::updated(function ($model) {
            $model->load('UserGroupPermission');
            $user = Auth::user();

            if ($user) {
                LogSystemHelpers::createLog(
                    'User Group',
                    'update',
                    $model->id,
                    $model->toArray(),
                    $user
                );
            } else {
                \Log::warning('No authenticated user while updating model.');
            }
        });

        static::deleted(function ($model) {
            $model->load('UserGroupPermission');
            $user = Auth::user();

            if ($user) {
                LogSystemHelpers::createLog(
                    'User Group',
                    'destroy',
                    $model->id,
                    $model->toArray(),
                    $user
                );
            } else {
                \Log::warning('No authenticated user while deleting model.');
            }
        });
    }

    public function UserGroupPermission()
    {
        return $this->hasMany(UserGroupPermission::class);
    }
}
