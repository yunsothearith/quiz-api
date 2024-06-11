<?php

namespace App\Http\Controllers\Admin;

// ============================================================================>> Core Library
use App\Models\Answer\Answer;
use Illuminate\Http\Request; // For Getting requested Payload from Client
use Illuminate\Http\Response; // For Responsing data back to Client
use Illuminate\Pagination\Paginator;


// ============================================================================>> Third Party Library កខគឃង
use Carbon\Carbon; // Data Time format & Calculation

// ============================================================================>> Custome Library
// Controller
use App\Http\Controllers\MainController;

// Service
use App\Services\FileUpload; // Upload Image/File to File Micro Serivce

// Model
use App\Models\Question\Question;

class AnswerController extends MainController
{
    public function create(Request $req, $quiz_id = 0)
    {
        try {
            // Validate the incoming request
            $this->validate(
                $req,
                [
                    'text' => 'required|string|max:200',
                    'is_correct' => 'required|boolean'
                ]
            );

            // Find the Question by quiz_id
            $question = Question::find($quiz_id);
            if (!$question) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Question not found.'
                ], Response::HTTP_NOT_FOUND);
            }

            // Find or create the Answer
            if (isset($req->answer_id)) {
                $answer = Answer::where('question_id', $quiz_id)->find($req->answer_id);
                if ($answer) {
                    $answer->updated_at = Carbon::now();
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Answer not found.'
                    ], Response::HTTP_NOT_FOUND);
                }
            } else {
                $answer = new Answer;
                $answer->created_at = Carbon::now();
            }
            $answer->text = $req->text;
            $answer->is_correct = $req->is_correct;
            $answer->question_id = $quiz_id;
            $answer->save();

            $message = $answer->is_correct ? "is correct answer" : "is wrong answer";

            return response()->json([
                'file_id' => $answer->id,
                'message' => $req->text . ' has been saved.',
                'answer' => $message
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during the question creation process.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
