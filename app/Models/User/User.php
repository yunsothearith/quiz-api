<?php

namespace App\Models\User;

// ===================================================>> Core Library
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// ===================================================>> Thirdd Party Library
use Tymon\JWTAuth\Contracts\JWTSubject;

// ===================================================>> Custom Library
use App\Models\User\Type;
use App\Models\Quiz\UserQuizResult;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';
    // Rest omitted for brevity

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function type(): BelongsTo //M:1
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function userQuizResults(): HasMany //1:M
    {
        return $this->hasMany(UserQuizResult::class, 'user_id');
    }

}
