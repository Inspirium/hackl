<?php

namespace App\Http\Proxies;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Application;

class LoginProxy
{
    const REFRESH_TOKEN = 'refreshToken';

    private $auth;

    private $cookie;

    private $db;

    private $request;

    private $user;

    public function __construct(Application $app)
    {
        $this->auth = $app->make('auth');
        $this->cookie = $app->make('cookie');
        $this->db = $app->make('db');
        $this->request = $app->make('request');
    }

    /**
     * Attempt to create an access token using user credentials
     *
     * @param  string  $email
     * @param  string  $password
     */
    public function attemptLogin($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (! is_null($user)) {
            $user->makeVisible(['club_member']);
            $this->user = $user;

            return $this->proxy('password', [
                'username' => $email,
                'password' => $password,
            ]);
        }

        //throw new InvalidCredentialsException();
    }

    /**
     * Attempt to refresh the access token used a refresh token that
     * has been saved in a cookie
     */
    public function attemptRefresh($refreshToken)
    {
        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken,
        ]);
    }

    /**
     * Proxy a request to the OAuth server.
     *
     * @param  string  $grantType what type of grant type should be proxied
     * @param  array  $data the data to send to the server
     */
    public function proxy($grantType, array $data = [])
    {
        $data = array_merge($data, [
            'client_id' => env('PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET'),
            'grant_type' => $grantType,
            'scope' => '*',
        ]);
        $http = new Client();

        $response = $http->post(url('/oauth/token'), [
            'form_params' => $data,
        ]);

        $data = json_decode($response->getBody());

        return [
            'user' => $this->user,
            'access_token' => $data->access_token,
            'expires_in' => $data->expires_in,
            'refresh_token' => $data->refresh_token,
        ];
    }

    /**
     * Logs out the user. We revoke access token and refresh token.
     * Also instruct the client to forget the refresh cookie.
     */
    public function logout()
    {
        $accessToken = $this->auth->user()->token();

        $refreshToken = $this->db
            ->table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true,
            ]);

        $accessToken->revoke();

        $this->cookie->queue($this->cookie->forget(self::REFRESH_TOKEN));
    }
}
