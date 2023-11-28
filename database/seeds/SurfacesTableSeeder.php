<?php

use Illuminate\Database\Seeder;

class SurfacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('surfaces')->insert([
            0 => ['designation' => 'A', 'title' => 'Akril', 'badge' => 'acrylic', 'fill' => '', 'reserved' => ''],
            1 => ['designation' => 'B', 'title' => 'Umjetna zemlja', 'badge' => 'artifical-clay', 'fill' => '', 'reserved' => ''],
            2 => ['designation' => 'C', 'title' => 'Umjetna trava', 'badge' => 'artificial-grass', 'fill' => '', 'reserved' => ''],
            3 => ['designation' => 'D', 'title' => 'Asfalt', 'badge' => 'asphalt', 'fill' => '', 'reserved' => ''],
            4 => ['designation' => 'E', 'title' => 'Tepih', 'badge' => 'carpet', 'fill' => '', 'reserved' => ''],
            5 => ['designation' => 'F', 'title' => 'Zemlja', 'badge' => 'clay', 'fill' => '#B25900', 'reserved' => 'clay-reserve'],
            6 => ['designation' => 'G', 'title' => 'Beton', 'badge' => 'concrete', 'fill' => '#0085B2', 'reserved' => 'concrete-reserve'],
            7 => ['designation' => 'H', 'title' => 'Trava', 'badge' => 'grass', 'fill' => '', 'reserved' => ''],
            8 => ['designation' => 'J', 'title' => 'Drugo', 'badge' => 'other', 'fill' => '', 'reserved' => ''],
        ]);
    }
}
