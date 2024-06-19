<?php
use Illuminate\Support\Facades\Route;

// ============================================================================>> Custom Library
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\AnswerController;
use App\Http\Controllers\Admin\QuestionController;

// ===========================================================================>> Product
Route::group(['prefix' => 'quizs'], function () {
    // ===>> Product
    Route::get('/', [QuizController::class, 'getData']); // Read Multi Records
    Route::get('/{id}', [QuizController::class, 'view']); // View a Record
    Route::post('/', [QuizController::class, 'create']); // Create New Record
    Route::post('/{id}', [QuizController::class, 'update']); // Update
    Route::delete('/{id}', [QuizController::class, 'delete']); // Delete a Record

});


Route::group(['prefix' => 'question'], function () {
    // ===>> Product
    Route::get('/', [QuestionController::class, 'getData']); // Read Multi Records
    Route::post('/', [QuestionController::class, 'create']);
    Route::post('/{id}', [QuestionController::class, 'update']);
    Route::delete('/{id}', [QuestionController::class, 'delete']);
    // create answer

    Route::post('{quiz_id}/answer', [AnswerController::class, 'create']);
    Route::post('/{id}/answer/{id}', [QuestionController::class, 'update']);
    Route::delete('/{id}/answer/{id}', [QuestionController::class, 'delete']);

});





