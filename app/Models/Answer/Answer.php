<?php

namespace App\Models\Answer;

// ===================================================>> Core Library
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// ===================================================>> Custom Library
use App\Models\Question\Question;

class Answer extends Model
{
    use HasFactory;
    protected $table = 'answer';


    public function question(): BelongsTo //M:1
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
