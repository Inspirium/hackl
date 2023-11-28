<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Proxies\LoginProxy;
use App\Models\User;
use App\Notifications\NewMember;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    private $loginProxy;

    public function __construct(LoginProxy $loginProxy)
    {
        $this->loginProxy = $loginProxy;
    }

    public function login(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        return response()->json($this->loginProxy->attemptLogin($email, $password));
    }

    public function refresh(Request $request)
    {
        return response()->json($this->loginProxy->attemptRefresh($request->input('refresh_token')));
    }

    public function logout()
    {
        $this->loginProxy->logout();

        return response()->json(null, 204);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'birthyear' => 'required|date_format:Y',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'member' => 'required|boolean',
            'gender' => 'required',
        ]);
        $member = $validatedData['member'];
        unset($validatedData['member']);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['rating'] = 1500;
        try {
            $user = User::create($validatedData);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Neuspjela registracija, pokušajte ponovo'], 500);
        }

        if ($request->hasFile('image') && $request->file('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $path = $file->store(sprintf('%s/%d/%d', 'avatars', date('Y'), date('m')), 'public');
                $user->image = $path;
                $user->save();
            }
        }
        if ($member && $request->get('club')) {
            if ($request->get('club')->validate_user) {
                $status = 'applicant';
            } else {
                $status = 'member';
            }
            $request->get('club')->players()->attach($user, ['status' => $status]);
            $user->primary_club()->associate($request->get('club'));
            $user->save();
            //Notification::send($request->get('club')->all_admins, new NewMember($user));
            foreach ($request->get('club')->all_admins as $admin) {
                $admin->notify((new NewMember($user))->locale($admin->lang));
            }
        }

        return response()->json($this->loginProxy->attemptLogin($validatedData['email'], $request->input('password')));
    }

    public function passwordEmail(Request $request)
    {
        //TODO: validate email
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return response()->json();
    }

    public function passwordReset(Request $request)
    {
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
            ? response()->json('success', 200)
            : response()->json('error', 401);
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $user->save();

        event(new PasswordReset($user));
    }

    public function broker()
    {
        return Password::broker();
    }
}
