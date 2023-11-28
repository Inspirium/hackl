<?php

namespace App\Actions;

class GetMapData {
    public function handle($address) {
        $client = new \GuzzleHttp\Client();
        $r = $client->get('https://maps.googleapis.com/maps/api/geocode/json?components=locality&address=' . urlencode($address) . '&key=' . env('GOOGLE_MAPS_TOKEN'));
        $r = json_decode($r->getBody()->getContents(), true);
        if ($r['status'] == 'OK') {
            foreach ($r['results'] as $result) {
                if (in_array('locality', $result['types'])) {
                    $data = [
                        'latitude' => $result['geometry']['location']['lat'],
                        'longitude' => $result['geometry']['location']['lng'],
                    ];
                    return $data;
                }
            }
        }

        return null;
    }
}
