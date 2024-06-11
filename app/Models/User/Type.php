<?php

namespace App\Models\User;

// ===================================================>> Core Library
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// ===================================================>> Custom Library
use App\Models\User\User;

class Type extends Model
{
    use HasFactory;
    protected $table = 'users_type';


    public function users(): HasMany //1:M
    {
        return $this->hasMany(User::class, 'tyep_id');
    }
}
