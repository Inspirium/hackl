<?php

namespace App\Http\Controllers\V2;

use App\Models\Club;
use App\Models\User;
use App\Models\UserSocial;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    protected $state;

    protected $club;

    public function __construct()
    {
        $this->middleware(['social']);
        $this->middleware('auth:api')->only('connect');
    }

    public function redirect($service)
    {
        return Socialite::driver($service)
            ->scopes(['public_profile', 'email'])
            ->with(['state' => json_encode(['club' => request()->input('club') ? request()->input('club') : 0])])
            ->redirect();
    }

    private function url($action, $params = [])
    {
        if (! $this->club && $this->state) {
            $this->club = Club::find($this->state['club']);
        }
        $domain = 'https://www.tenis.plus';
        if ($this->club) {
            $domain = $this->club->domain;
        }
        if ($action) {
            $action .= '/';
        }
        if (env('APP_ENV') === 'local' || env('APP_ENV') === 'testing') {
            //$domain = 'http://tennis.inspirium.hr';
        }

        return $domain.'/login/social/'.$action.'?'.http_build_query($params);
    }

    public function callback($service)
    {
        try {
            $serviceUser = Socialite::driver($service)->stateless()->user();
            $this->state = json_decode(request()->input('state'), true);
        } catch (\Exception $e) {
            return redirect($this->url('error', ['message' => 'error while loading driver']));
        }
        $email = $serviceUser->getEmail();

        $user = $this->getExistingUser($serviceUser, $email, $service);
        if (! $user) {
            return redirect($this->url('new', ['id' => $serviceUser->getId(), 'email' => $serviceUser->getEmail(), 'service' => $service, 'name' => $serviceUser->getName(), 'token' => $serviceUser->token]));
        }

        if ($this->needsToCreateSocial($user, $service)) {
            UserSocial::create([
                'user_id' => $user->id,
                'social_id' => $serviceUser->getId(),
                'social_email' => $serviceUser->getEmail(),
                'token' => $serviceUser->token,
                'service' => $service,
            ]);
        }

        return redirect($this->url('', ['token' => $user->createToken('')->accessToken]));
    }

    public function connect(Request $request)
    {
        UserSocial::create([
            'user_id' => \Auth::id(),
            'social_id' => $request->input('id'),
            'social_email' => $request->input('email'),
            'service' => $request->input('service'),
            'token' => $request->input('token'),
        ]);

        return response()->json(['message' => 'success']);
    }

    public function needsToCreateSocial(User $user, $service)
    {
        return ! $user->hasSocialLinked($service);
    }

    public function getExistingUser($serviceUser, $email, $service)
    {
        return User::where('email', $email)->orWhereHas('social', function ($q) use ($serviceUser, $service) {
            $q->where('social_email', $serviceUser->getEmail())->where('service', $service);
        })->first();
    }

    public function resetPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return response()->json(['message' => $status]);
    }

    public function resetNewPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        return response()->json(['message' => $status]);
    }
}
