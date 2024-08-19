<?php

namespace App\Models;

use App\Helpers\LogSystemHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserGroupPermission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::created(function ($model) {
            $user = Auth::user();

            if ($user) {
                LogSystemHelpers::createLog(
                    'User Group Permission',
                    'store',
                    $model->id,
                    $model->toArray(),
                    $user
                );
            } else {
                \Log::warning('No authenticated user while creating model.');
            }
        });

        static::deleted(function ($model) {
            $user = Auth::user();

            if ($user) {
                LogSystemHelpers::createLog(
                    'User Group Permission',
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
}
