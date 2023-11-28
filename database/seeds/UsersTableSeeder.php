<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'email' => 'marko@inspirium.hr',
                'password' => bcrypt('secret123'),
                'first_name' => 'Marko',
                'last_name' => 'Banušić',
                'phone' => '0915268779',
                'rating' => 1500,
                'birthyear' => 1986,
            ],
            [
                'email' => 'stjepan@inspirium.hr',
                'password' => bcrypt('secret123'),
                'first_name' => 'Stjepan',
                'last_name' => 'Drmić',
                'phone' => '',
                'rating' => 1500,
                'birthyear' => 1978,
            ],
        ]);
        DB::table('club_user')->insert([
            ['club_id' => 1, 'player_id' => 1],
            ['club_id' => 1, 'player_id' => 2],
        ]);
    }
}
