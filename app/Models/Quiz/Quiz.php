<?php

namespace App\Models\Quiz;

// ===================================================>> Core Library
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// ===================================================>> Custom Library
use App\Models\Question\Question;
use App\Models\Quiz\UserQuizResult;

class Quiz extends Model
{
    use HasFactory;
    protected $table = 'quizzes';


    public function questions(): HasMany //1:M
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }

    public function userQuizResults(): HasMany //1:M
    {
        return $this->hasMany(UserQuizResult::class, 'quiz_id');
    }
}
