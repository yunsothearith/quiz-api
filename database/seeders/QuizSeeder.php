<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | Create User Type: Admin & Staff
        |--------------------------------------------------------------------------
        */
        DB::table('quizzes')->insert(
            [
                ['name' => 'Laravel'],
                ['name' => 'CSS'],
                ['name' => 'Python'],
                ['name' => 'JavaScript'],
                ['name' => 'SQL'],
            ]
        );
    }
}
