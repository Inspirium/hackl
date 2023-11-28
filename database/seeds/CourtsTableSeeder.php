<?php

use Illuminate\Database\Seeder;

class CourtsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courts')->insert([
            [
                'name' => 'Teren 1',
                'is_active' => true,
                'surface_id' => 6,
                'working_from' => '08:00',
                'working_to' => '22:00',
                'type' => 'open',
                'lights' => true,
                'reservation_confirmation' => true,
                'reservation_duration' => '30',
                'club_id' => 1,
            ],
            [
                'name' => 'Teren 2',
                'is_active' => true,
                'surface_id' => 6,
                'working_from' => '08:00',
                'working_to' => '22:00',
                'type' => 'open',
                'lights' => true,
                'reservation_confirmation' => true,
                'reservation_duration' => '30',
                'club_id' => 1,
            ],
            [
                'name' => 'Teren 3',
                'is_active' => true,
                'surface_id' => 6,
                'working_from' => '08:00',
                'working_to' => '22:00',
                'type' => 'open',
                'lights' => true,
                'reservation_confirmation' => true,
                'reservation_duration' => '30',
                'club_id' => 1,
            ],
            [
                'name' => 'Teren 4',
                'is_active' => true,
                'surface_id' => 6,
                'working_from' => '08:00',
                'working_to' => '22:00',
                'type' => 'open',
                'lights' => true,
                'reservation_confirmation' => true,
                'reservation_duration' => '30',
                'club_id' => 1,
            ],
            [
                'name' => 'Teren 5',
                'is_active' => true,
                'surface_id' => 7,
                'working_from' => '08:00',
                'working_to' => '22:00',
                'type' => 'open',
                'lights' => true,
                'reservation_confirmation' => true,
                'reservation_duration' => '30',
                'club_id' => 1,
            ],
        ]);
    }
}
