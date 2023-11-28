<?php

use Illuminate\Database\Seeder;

class ClubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clubs')->insert([
            'name' => 'TK Zaprešić',
            'subdomain' => 'tkzapresic',
            'address' => 'Mirka Bogovića 53a',
            'postal_code' => '10290',
            'city' => 'Zaprešić',
            'county' => 'Zagrebačka županija',
            'region' => 'Središnja',
            'phone' => json_encode(['013390010', '098221795']),
            'fax' => json_encode(['013310573']),
            'email' => json_encode(['petarlacy@gmail.com']),
            'has_players' => true,
            'has_courts' => true,
            'is_active' => true,
        ]);
    }
}
