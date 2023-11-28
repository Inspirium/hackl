<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\ClubSocial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClubSocialController extends Controller
{
    public function index(Request $request, $service) {
        $code = $request->input('code');
        $state = json_decode($request->input('state'), true);
        $club = Club::findOrFail($state['club']);
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://api.vimeo.com/oauth/access_token', [
            'headers' => [
                'Authorization' => 'basic ' . base64_encode(env('VIMEO_CLIENT_ID') . ':' . env('VIMEO_CLIENT_SECRET')),
                'Content-Type' => 'application/json',
                'Accept' => 'application/vnd.vimeo.*+json;version=3.4'
            ],
            'json' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => 'https://api.tenis.plus/v2/club/social/vimeo'
            ]
        ]);

        $response = json_decode($response->getBody(), true);
        $social = ClubSocial::firstOrNew([
            'club_id' => $club->id,
            'service' => $service,
        ]);
        $social->data = $response;
        $social->save();
        return redirect('https://' . $club->domain . '/admin/club/edit');
    }
}
