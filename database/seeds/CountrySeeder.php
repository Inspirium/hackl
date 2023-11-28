<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'name' => [
                    'hr' => 'Hrvatska',
                    'en' => 'Croatia',
                ],
                'code' => 'HR',
                'locale' => 'hr_HR',
                'currency' => 'EUR',
                'flag' => '🇭🇷',
                'latitude' => 45.1,
                'longitude' => 15.2,
            ],
            [
                'name' => [
                    'hr' => 'Slovenija',
                    'en' => 'Slovenia',
                ],
                'code' => 'SI',
                'locale' => 'sl_SI',
                'currency' => 'EUR',
                'flag' => '🇸🇮',
                'latitude' => 46.0,
                'longitude' => 15.0,
            ],
            [
                'name' => [
                    'hr' => 'Austrija',
                    'en' => 'Austria',
                ],
                'code' => 'AT',
                'locale' => 'de_AT',
                'currency' => 'EUR',
                'flag' => '🇦🇹',
                'latitude' => 47.6,
                'longitude' => 14.6,
            ],
            [
                'name' => [
                    'hr' => 'Italija',
                    'en' => 'Italy',
                ],
                'code' => 'IT',
                'locale' => 'it_IT',
                'currency' => 'EUR',
                'flag' => '🇮🇹',
                'latitude' => 42.8,
                'longitude' => 12.8,
            ],
            [
                'name' => [
                    'hr' => 'Francuska',
                    'en' => 'France',
                ],
                'code' => 'FR',
                'locale' => 'fr_FR',
                'currency' => 'EUR',
                'flag' => '🇫🇷',
                'latitude' => 46.0,
                'longitude' => 2.0,
            ],
            [
                'name' => [
                    'hr' => 'Španjolska',
                    'en' => 'Spain',
                ],
                'code' => 'ES',
                'locale' => 'es_ES',
                'currency' => 'EUR',
                'flag' => '🇪🇸',
                'latitude' => 40.0,
                'longitude' => -4.0,
            ],
            [
                'name' => [
                    'hr' => 'Portugal',
                    'en' => 'Portugal',
                ],
                'code' => 'PT',
                'locale' => 'pt_PT',
                'currency' => 'EUR',
                'flag' => '🇵🇹',
                'latitude' => 39.5,
                'longitude' => -8.0,
            ],
            [
                'name' => [
                    'hr' => 'Nizozemska',
                    'en' => 'Netherlands',
                ],
                'code' => 'NL',
                'locale' => 'nl_NL',
                'currency' => 'EUR',
                'flag' => '🇳🇱',
                'latitude' => 52.5,
                'longitude' => 5.75,
            ],
            [
                'name' => [
                    'hr' => 'Belgija',
                    'en' => 'Belgium',
                ],
                'code' => 'BE',
                'locale' => 'nl_BE',
                'currency' => 'EUR',
                'flag' => '🇧🇪',
                'latitude' => 50.8,
                'longitude' => 4.0,
            ],
            [
                'name' => [
                    'hr' => 'Luksemburg',
                    'en' => 'Luxembourg',
                ],
                'code' => 'LU',
                'locale' => 'fr_LU',
                'currency' => 'EUR',
                'flag' => '🇱🇺',
                'latitude' => 49.8,
                'longitude' => 6.0,
            ],
            [
                'name' => [
                    'hr' => 'Švicarska',
                    'en' => 'Switzerland',
                ],
                'code' => 'CH',
                'locale' => 'de_CH',
                'currency' => 'CHF',
                'flag' => '🇨🇭',
                'latitude' => 47.0,
                'longitude' => 8.0,
            ],
            [
                'name' => [
                    'hr' => 'Njemačka',
                    'en' => 'Germany',
                ],
                'code' => 'DE',
                'locale' => 'de_DE',
                'currency' => 'EUR',
                'flag' => '🇩🇪',
                'latitude' => 51.0,
                'longitude' => 9.0,
            ],
            [
                'name' => [
                    'hr' => 'Ujedinjeno Kraljevstvo',
                    'en' => 'United Kingdom',
                ],
                'code' => 'GB',
                'locale' => 'en_GB',
                'currency' => 'GBP',
                'flag' => '🇬🇧',
                'latitude' => 54.0,
                'longitude' => -2.0,
            ],
            [
                'name' => [
                    'hr' => 'Srbija',
                    'en' => 'Serbia',
                ],
                'code' => 'RS',
                'locale' => 'sr_RS',
                'currency' => 'RSD',
                'flag' => '🇷🇸',
                'latitude' => 44.0,
                'longitude' => 21.0,
            ],
            [
                'name' => [
                    'hr' => 'Bosna i Hercegovina',
                    'en' => 'Bosnia and Herzegovina',
                ],
                'code' => 'BA',
                'locale' => 'bs_BA',
                'currency' => 'BAM',
                'flag' => '🇧🇦',
                'latitude' => 44.0,
                'longitude' => 18.0,
            ],
            [
                'name' => [
                    'hr' => 'Crna Gora',
                    'en' => 'Montenegro',
                ],
                'code' => 'ME',
                'locale' => 'sr_ME',
                'currency' => 'EUR',
                'flag' => '🇲🇪',
                'latitude' => 42.0,
                'longitude' => 19.0,
            ],
            [
                'name' => [
                    'hr' => 'Makedonija',
                    'en' => 'North Macedonia',
                ],
                'code' => 'MK',
                'locale' => 'mk_MK',
                'currency' => 'MKD',
                'flag' => '🇲🇰',
                'latitude' => 41.6,
                'longitude' => 21.7,
            ],
            [
                'name' => [
                    'hr' => 'Mađarska',
                    'en' => 'Hungary',
                ],
                'code' => 'HU',
                'locale' => 'hu_HU',
                'currency' => 'HUF',
                'flag' => '🇭🇺',
                'latitude' => 47.2,
                'longitude' => 19.5,
            ],
            [
                'name' => [
                    'hr' => 'Slovačka',
                    'en' => 'Slovakia',
                ],
                'code' => 'SK',
                'locale' => 'sk_SK',
                'currency' => 'EUR',
                'flag' => '🇸🇰',
                'latitude' => 48.7,
                'longitude' => 19.5,
            ],
            [
                'name' => [
                    'hr' => 'Češka',
                    'en' => 'Czechia',
                ],
                'code' => 'CZ',
                'locale' => 'cs_CZ',
                'currency' => 'CZK',
                'flag' => '🇨🇿',
                'latitude' => 49.8,
                'longitude' => 15.5,
            ],
            [
                'name' => [
                    'hr' => 'Poljska',
                    'en' => 'Poland',
                ],
                'code' => 'PL',
                'locale' => 'pl_PL',
                'currency' => 'PLN',
                'flag' => '🇵🇱',
                'latitude' => 52.0,
                'longitude' => 20.0,
            ],
            [
                'name' => [
                    'hr' => 'Ukrajina',
                    'en' => 'Ukraine',
                ],
                'code' => 'UA',
                'locale' => 'uk_UA',
                'currency' => 'UAH',
                'flag' => '🇺🇦',
                'latitude' => 49.0,
                'longitude' => 32.0,
            ],
            [
                'name' => [
                    'hr' => 'Rumunjska',
                    'en' => 'Romania',
                ],
                'code' => 'RO',
                'locale' => 'ro_RO',
                'currency' => 'RON',
                'flag' => '🇷🇴',
                'latitude' => 46.0,
                'longitude' => 25.0,
            ],
            [
                'name' => [
                    'hr' => 'Bugarska',
                    'en' => 'Bulgaria',
                ],
                'code' => 'BG',
                'locale' => 'bg_BG',
                'currency' => 'BGN',
                'flag' => '🇧🇬',
                'latitude' => 43.0,
                'longitude' => 25.0,
            ],
            [
                'name' => [
                    'hr' => 'Grčka',
                    'en' => 'Greece',
                ],
                'code' => 'GR',
                'locale' => 'el_GR',
                'currency' => 'EUR',
                'flag' => '🇬🇷',
                'latitude' => 39.0,
                'longitude' => 22.0,
            ],
            [
                'name' => [
                    'hr' => 'Turska',
                    'en' => 'Turkey',
                ],
                'code' => 'TR',
                'locale' => 'tr_TR',
                'currency' => 'TRY',
                'flag' => '🇹🇷',
                'latitude' => 39.0,
                'longitude' => 35.0,
            ],
            [
                'name' => [
                    'hr' => 'Francuska',
                    'en' => 'France',
                ],
                'code' => 'FR',
                'locale' => 'fr_FR',
                'currency' => 'EUR',
                'flag' => '🇫🇷',
                'latitude' => 46.0,
                'longitude' => 2.0,
            ],
            [
                'name' => [
                    'hr' => 'Španjolska',
                    'en' => 'Spain',
                ],
                'code' => 'ES',
                'locale' => 'es_ES',
                'currency' => 'EUR',
                'flag' => '🇪🇸',
                'latitude' => 40.0,
                'longitude' => -4.0,
            ],
            [
                'name' => [
                    'hr' => 'Portugal',
                    'en' => 'Portugal',
                ],
                'code' => 'PT',
                'locale' => 'pt_PT',
                'currency' => 'EUR',
                'flag' => '🇵🇹',
                'latitude' => 39.5,
                'longitude' => -8.0,
            ],
            [
                'name' => [
                    'hr' => 'Nizozemska',
                    'en' => 'Netherlands',
                ],
                'code' => 'NL',
                'locale' => 'nl_NL',
                'currency' => 'EUR',
                'flag' => '🇳🇱',
                'latitude' => 52.5,
                'longitude' => 5.75,
            ],
            [
                'name' => [
                    'hr' => 'Belgija',
                    'en' => 'Belgium',
                ],
                'code' => 'BE',
                'locale' => 'nl_BE',
                'currency' => 'EUR',
                'flag' => '🇧🇪',
                'latitude' => 50.8,
                'longitude' => 4.0,
            ],
            [
                'name' => [
                    'hr' => 'Luksemburg',
                    'en' => 'Luxembourg',
                ],
                'code' => 'LU',
                'locale' => 'fr_LU',
                'currency' => 'EUR',
                'flag' => '🇱🇺',
                'latitude' => 49.8,
                'longitude' => 6.0,
            ],
            [
                'name' => [
                    'hr' => 'Švicarska',
                    'en' => 'Switzerland',
                ],
                'code' => 'CH',
                'locale' => 'de_CH',
                'currency' => 'CHF',
                'flag' => '🇨🇭',
                'latitude' => 47.0,
                'longitude' => 8.0,
            ],
            [
                'name' => [
                    'hr' => 'Njemačka',
                    'en' => 'Germany',
                ],
                'code' => 'DE',
                'locale' => 'de_DE',
                'currency' => 'EUR',
                'flag' => '🇩🇪',
                'latitude' => 51.0,
                'longitude' => 9.0,
            ],
            [
                'name' => [
                    'hr' => 'Sjedinjene Američke Države',
                    'en' => 'United States of America',
                ],
                'code' => 'US',
                'locale' => 'en_US',
                'currency' => 'USD',
                'flag' => '🇺🇸',
                'latitude' => 38.0,
                'longitude' => -97.0,
            ]

        ];

        foreach ($countries as $country) {
            $country = Country::create($country);
        }
    }
}
