<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ClubsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SurfacesTableSeeder::class);
        $this->call(CourtsTableSeeder::class);
        $this->call(ResultsSeeder::class);
    }
}
