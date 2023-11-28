<?php

use Illuminate\Database\Seeder;

class ResultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('results')->insert([
            ['sets' => json_encode([[7, 5], [4, 6], [6, 3]]), 'court_id' => 1],
            ['sets' => json_encode([[7, 5], [5, 6], [4, 6], [6, 3], [6, 2]]), 'court_id' => 1],
            ['sets' => json_encode([[7, 5], [5, 6], [4, 6], [6, 3], [6, 2]]), 'court_id' => 2],
            ['sets' => json_encode([[4, 6], [6, 3], [6, 2]]), 'court_id' => 2],
            ['sets' => json_encode([[4, 6], [6, 3], [6, 2], [7, 5], [6, 3]]), 'court_id' => 2],
        ]);
        DB::table('results_users')->insert([
            ['user_id' => 3, 'result_id' => 1, 'verified' => true],
            ['user_id' => 4, 'result_id' => 1, 'verified' => true],
            ['user_id' => 5, 'result_id' => 2, 'verified' => true],
            ['user_id' => 6, 'result_id' => 2, 'verified' => true],
            ['user_id' => 7, 'result_id' => 3, 'verified' => true],
            ['user_id' => 8, 'result_id' => 3, 'verified' => true],
            ['user_id' => 6, 'result_id' => 4, 'verified' => false],
            ['user_id' => 3, 'result_id' => 4, 'verified' => true],
            ['user_id' => 8, 'result_id' => 5, 'verified' => true],
            ['user_id' => 6, 'result_id' => 5, 'verified' => false],
        ]);
    }
}
