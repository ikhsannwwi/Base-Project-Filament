<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Helpers\LogSystemHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminMenu extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->identifier = Str::of($model->name)->lower()->snake();
        });

        static::created(function ($model) {
            $user = Auth::user();

            if ($user) {
                LogSystemHelpers::createLog(
                    'Admin Menu',
                    'store',
                    $model->id,
                    $model->toArray(),
                    $user
                );
            } else {
                \Log::warning('No authenticated user while creating model.');
            }
        });

        static::updating(function ($model) {
            $model->identifier = Str::of($model->name)->lower()->snake();
        });

        static::updated(function ($model) {
            $user = Auth::user();

            if ($user) {
                LogSystemHelpers::createLog(
                    'Admin Menu',
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
            $user = Auth::user();

            if ($user) {
                LogSystemHelpers::createLog(
                    'Admin Menu',
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
