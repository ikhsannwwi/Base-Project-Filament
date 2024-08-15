<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UserGroup;
use App\Helpers\LogSystemHelpers;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_group_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getAuth(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'user_group_id' => $this->user_group_id,
            'status' => $this->status,
        ];

        return $data;
    }

    protected static function booted()
    {
        static::created(function ($model) {
            $user = Auth::user();

            if ($user) {
                LogSystemHelpers::createLog(
                    'User',
                    'create',
                    $model->id,
                    $model->toArray(),
                    $user
                );
            } else {
                \Log::warning('No authenticated user while creating model.');
            }
        });

        static::updated(function ($model) {
            $user = Auth::user();

            if ($user) {
                LogSystemHelpers::createLog(
                    'User',
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
                    'User',
                    'delete',
                    $model->id,
                    $model->toArray(),
                    $user
                );
            } else {
                \Log::warning('No authenticated user while deleting model.');
            }
        });
    }

    public function usergroup() {
        return $this->belongsTo(UserGroup::class, 'user_group_id', 'id');
    }
}
