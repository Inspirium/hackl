<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PlayerController extends Controller
{
    public function getPlayers(Request $request)
    {
        $power = [0, 100];
        $age = [0, 120];
        $offset = intval($request->input('offset'));
        $ranked = (bool) $request->input('ranked');
        $club = (bool) $request->input('is_club');
        $term = $request->input('term');
        if ($request->input('power') && $request->input('power') !== 'Sve') {
            $power = substr($request->input('power'), 0, -1);
            $power = explode('-', $power);
        }
        if ($request->input('age') && $request->input('age') !== 'Sve') {
            $age = explode('-', $request->input('age'));
        }
        if ($club) {
            $year = date('Y');
            $players = $request->get('club')->players()
                                      ->whereBetween('birthyear', [
                                          $year - $age[1],
                                          $year - $age[0] - 1,
                                      ])
                                      ->wherePivot('power', '>=', $power[0])
                                      ->wherePivot('power', '<=', $power[1]);
            if ($term) {
                $players->where(function ($query) use ($term) {
                    $query->where('first_name', 'LIKE', '%'.$term.'%');
                    $query->orWhere('last_name', 'LIKE', '%'.$term.'%');
                });
            }
            if ($ranked) {
                $players->wherePivot('rank', '<>', 0);
            }
            $players->orderBy('rating', 'desc');
            $total = $players->count();
            $players = $players->limit(20)->offset($offset)->get();
        } else {
            $year = date('Y');
            $players = User::query();
            $players->orderBy('rating', 'desc')->limit(20)->offset($offset);
            $players->whereBetween('birthyear', [
                $year - $age[1],
                $year - $age[0] - 1,
            ])->where('power_global', '>=', $power[0])->where('power_global', '<=', $power[1]);
            if ($term) {
                $players->where(function ($query) use ($term) {
                    $query->where('first_name', 'LIKE', '%'.$term.'%');
                    $query->orWhere('last_name', 'LIKE', '%'.$term.'%');
                });
            }
            if ($ranked) {
                $players->where('rank_global', '<>', 0);
            }
            $total = $players->count();
            $players = $players->get();
        }

        return response()->json(['players' => $players, 'total' => $total]);
    }

    public function getPlayer(User $player)
    {
        $this->authorize('view', $player);
        $player->load('results.players');
        $player->winslosses = $player->getWinsLosses();

        return response()->json($player);
    }

    public function postPlayer(Request $request)
    {
        $this->authorize('create', User::class);
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'username' => 'required|unique:users',
            'birthyear' => 'required|date_format:Y',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'member' => 'required|boolean',
        ]);
        $member = $validatedData['member'];
        unset($validatedData['member']);
        $validatedData['password'] = Hash::make($validatedData['password']);
        try {
            $user = User::create($validatedData);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Neuspjela registracija, pokušajte ponovo'], 500);
        }
        if ($member && $request->get('club')) {
            $request->get('club')->players()->attach($user);
        }

        return response()->json(['location' => $user->link]);
    }

    public function putPlayer(Request $request, User $player)
    {
        $this->authorize('update', $player);

        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($player->id)],
            'birthyear' => 'required|date_format:Y',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'gender' => 'required',
        ]);
        $member = $request->input('club_member');
        /*if ($validatedData['password']) {
            $validatedData['password'] = Hash::make( $validatedData['password'] );
        }*/
        if ($request->hasFile('image') && $request->file('image')) {
            $file = $request->file('image');
            if (! $file->isValid()) {
                return response()->json(['result' => 'error', 'message' => 'Invalid file upload'], 400);
            }
            $path = $file->store(sprintf('%s/%d/%d', 'avatars', date('Y'), date('m')), 'public');
            $validatedData['image'] = $path;
        }
        try {
            $player->update($validatedData);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Neuspjelo ažuriranje, pokušajte ponovo'], 500);
        }

        /*if ($member && $request->get('club')) {
            $request->get('club')->players()->attach($player);
        }*/
        return response()->json(['location' => $player->link, 'user' => $player]);
    }

    public function getReservations(User $player)
    {
        $this->authorize('view', $player);
        $player->load(['reservations' => function ($query) {
            $query->with(['court', 'players']);
            $query->whereDate('from', '>=', date('Y-m-d'));
            $query->orderBy('from');
        }]);

        return response()->json(['reservations' => $player->reservations]);
    }

    public function getPlayerVS(User $player)
    {
        $this->authorize('view', $player);
        $player->load(['results' => function ($query) {
            $query->whereHas('players', function ($query) {
                $query->where('user_id', Auth::id());
            });
        }]);

        return response()->json(['results' => $player->results]);
    }

    public function removeImage(User $player)
    {
        $this->authorize('update', $player);
        $player->image = '';
        $player->save();

        return response()->json($player->image);
    }

    public function saveImage(Request $request, User $player)
    {
        $this->authorize('update', $player);
        if ($request->hasFile('image') && $request->file('image')) {
            $file = $request->file('image');
            if (! $file->isValid()) {
                return response()->json(['result' => 'error', 'message' => 'Invalid file upload'], 400);
            }
            $path = $file->store(sprintf('%s/%d/%d', 'avatars', date('Y'), date('m')), 'public');
            $player->image = $path;
            $player->save();

            return response()->json($player->image);
        }

        return response()->json();
    }

    public function savePassword(Request $request, User $player)
    {
        $this->authorize('update', $player);
        $old = $request->input('old_password');
        $new = $request->input('new_password');
        $new_conf = $request->input('new_password_confirmation');

        $player->password = Hash::make($new);
        $player->save();

        return response()->json();
    }
}
