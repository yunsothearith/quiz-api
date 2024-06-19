<?php

namespace App\Models\Answer;

// ===================================================>> Core Library
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// ===================================================>> Custom Library
use App\Models\Question\Question;

class Option extends Model
{
    use HasFactory;
    protected $table = 'option';


    public function question(): BelongsTo //M:1
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
