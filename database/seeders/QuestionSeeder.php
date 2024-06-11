<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class QuestionSeeder extends Seeder
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
        DB::table('question')->insert(
            [
                [
                    'name' => 'Which of the follwing is not a potential advantage of using good project management',
                    'quiz_id'=>1,
                ],
                [
                    'name' => 'Which of the follwing is not part of triple constrain of project management',
                    'quiz_id'=>1,
                ],
                [
                    'name' => 'Which of the follwing is not an attrbute of project',
                    'quiz_id'=>2,
                ],
                [
                    'name' => 'Which of the follwing is not true',
                    'quiz_id'=>2,
                ],
                [
                    'name' => 'Which of the follwing is not a potential advantage of using good project managerment',
                    'quiz_id'=>3,
                ],
            ]
        );
    }
}
