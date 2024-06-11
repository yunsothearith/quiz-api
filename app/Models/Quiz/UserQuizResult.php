<?php

namespace App\Models\Quiz;

// ===================================================>> Core Library
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// ===================================================>> Custom Library
use App\Models\Quiz\Quiz;
use App\Models\User\User;

class UserQuizResult extends Model
{
    use HasFactory;
    protected $table = 'user_quiz_results';


    public function quiz(): BelongsTo //M:1
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function user(): BelongsTo //M:1
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
