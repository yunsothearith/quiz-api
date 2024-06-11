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
use App\Models\Quiz\Quiz;

class QuizController extends MainController
{
    public function getData(Request $req){

        // Declare Variable
        $data = Quiz::select('*') 
        ->withCount('questions as n_of_question') ;// Include the count of questions

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

    public function view($id = 0){

        // Find record from DB
        $data = Quiz::select('*')->find($id);

        // ===>> Check if data is valide
        if ($data) { // Yes

            // ===> Success Response Back to Client
            return response()->json($data, Response::HTTP_OK);

        } else { // No

            // ===> Failed Response Back to Client
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យមិនត្រឹមត្រូវ',
            ], Response::HTTP_BAD_REQUEST);

        }

    }

    public function create(Request $req){

        // ===>> Check validation
        $this->validate(
            $req,
            [
                'name'              => 'required|max:50',
            ],
            [
                'name.required'         => 'សូមបញ្ចូលឈ្មោះផលិតផល',

            ]
        );

        // ===>> Field Mapping Product
        // Map field of table in DB Vs. requested value from client
        $quiz                =   new Quiz;
        $quiz->name          =   $req->name;
        $quiz->is_active     = 1;

        // ===>> Save To DB
        $quiz->save();

        // ===> Success Response Back to Client
        return response()->json([
            'data'      =>  $quiz,
            'message'   => 'Quiz create'
        ], Response::HTTP_OK);

    }

    public function update(Request $req, $id = 0){

        // ===>> Check validation
        $this->validate(
            $req,
            [
                'name'              => 'required|max:20',
            ],
            [
                'name.required'         => 'សូមបញ្ចូលឈ្មោះផលិតផល',
            ]
        );

        // ===>> Update Product
        // Find record from DB
        $quiz                    = Quiz::find($id);

        // ===>> Check if data is valide
        if ($quiz) { //Yes

            // Map field of table in DB Vs. requested value from client
            $quiz->name                  = $req->name;
            $quiz->is_active         = 1;
            // ===>> Save to DB
            $quiz->save();

            // Prepare Data backt to Client
            $quiz = Quiz::select('*')
            ->find($quiz->id);

            // ===> Success Response Back to Client
            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => 'ផលិតផលត្រូវបានកែប្រែជោគជ័យ',
                'quiz'   => $quiz,
            ], Response::HTTP_OK);

        } else { // No

            // ===> Failed Response Back to Client
            return response()->json([

                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យមិនត្រឹមត្រូវ',

            ], Response::HTTP_BAD_REQUEST);

        }

    }

    public function delete($id = 0){

        // Find record from DB
        $data = Quiz::find($id);

        // ===>> Check if data is valide
        if ($data) { // Yes

            // ===>> Delete Data from DB
            $data->delete();

            // ===> Success Response Back to Client
            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => 'ទិន្នន័យត្រូវបានលុប',
            ], Response::HTTP_OK);

        } else { // No

            // ===> Failed Response Back to Client
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យមិនត្រឹមត្រូវ',
            ], Response::HTTP_BAD_REQUEST);

        }
    }
    

}
