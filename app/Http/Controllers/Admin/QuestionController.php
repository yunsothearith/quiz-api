<?php

namespace App\Http\Controllers\Admin;

// ============================================================================>> Core Library
use Illuminate\Http\Request; // For Getting requested Payload from Client
use Illuminate\Http\Response; // For Responsing data back to Client
use Illuminate\Pagination\Paginator;


// ============================================================================>> Third Party Library កខគឃង
use Carbon\Carbon; // Data Time format & Calculation

// ============================================================================>> Custome Library
// Controller
use App\Http\Controllers\Controller;

// Service
use App\Services\FileUpload; // Upload Image/File to File Micro Serivce

// Model
use App\Models\Question\Question;

class QuestionController extends Controller
{
    public function getData(Request $req)
    {

        // Declare Variable
        $data = Question::select('id', 'name', 'answer_type')
        ->with([
            'answers:id,question_id,text,is_correct'
        ]);
        if ($req->key && $req->key != '') {

            $data = $data->where('name', 'LIKE', '%' . $req->key . '%');
        }

        $data = $data->orderBy('id', 'desc') // Order Data by Giggest ID to small
            ->paginate($req->limit ? $req->limit : 10, 'per_page'); // Paginate Data

        // ===> Success Response Back to Client
        return response()->json($data, Response::HTTP_OK);

    }

    public function create(Request $req)
    {
        $validation = $req->validate([
            'quiz_id' => 'required| integer',
            'name' => 'required | string '
        ]);


        $data = new Question();
        $data->quiz_id = $req->quiz_id;
        $data->name = $req->name;
        $data->answer_type = $req->answer_type;

        $data->save();

        $data = Question::select('id', 'quiz_id', 'name', 'answer_type')
            ->with([
                'quiz' => function ($query) {
                    $query->select('id', 'name');
                }
            ])->find($data->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Question has been create successfully',
            'data' => $data
        ], 200);
    }
    public function update(Request $req, $id)
    {

        $validation = $req->validate([
            'quiz_id' => 'required|integer',
            'name' => 'required|string'
        ]);

        $data = Question::find($id);
        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Question not found'
            ], 404);
        }

        $data->quiz_id = $req->quiz_id;
        $data->name = $req->name;
        $data->answer_type = $req->answer_type;

        $data->save();

        $data = Question::select('id', 'quiz_id', 'name', 'answer_type')
            ->with([
                'quiz' => function ($query) {
                    $query->select('id', 'name');
                }
            ])->find($data->id);

        // Return response
        return response()->json([
            'status' => 'success',
            'message' => 'Question has been updated successfully',
            'data' => $data
        ], 200);
    }

    public function delete($id)
    {

        $data = Question::find($id);
        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Question not found'
            ], 404);
        }

        $data->delete();

        // Return response
        return response()->json([
            'status' => 'success',
            'message' => 'Question has been deleted successfully'
        ], 200);
    }

}
