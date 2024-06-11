<?php

namespace App\Models\Question;

// ===================================================>> Core Library
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// ===================================================>> Custom Library
use App\Models\Quiz\Quiz;
use App\Models\Answer\Answer;
use App\Models\Answer\Option;

class Question extends Model
{
    use HasFactory;
    protected $table = 'question';


    public function quiz(): BelongsTo //M:1
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function answers(): HasMany //1:M
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function options(): HasMany //1:M
    {
        return $this->hasMany(Option::class, 'question_id');
    }

}
