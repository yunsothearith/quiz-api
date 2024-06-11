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
use App\Http\Controllers\MainController;

// Service
use App\Services\FileUpload; // Upload Image/File to File Micro Serivce

// Model
use App\Models\Question\Question;

class QuestionController extends MainController
{
    public function getData(Request $req){

        // Declare Variable
        $data = Question::select('*') ;// Include the count of questions

        // ===>> Filter Data
        // By Key compared with Code or Name
        if ($req->key && $req->key != '') {

            $data = $data->where('name', 'LIKE', '%' . $req->key . '%');
        }

        $data = $data->orderBy('id', 'desc') // Order Data by Giggest ID to small
        ->paginate($req->limit ? $req->limit : 10,'per_page'); // Paginate Data

        // ===> Success Response Back to Client
        return response()->json($data, Response::HTTP_OK);

    }
    

}
