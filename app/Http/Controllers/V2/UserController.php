<?php

namespace App\Http\Controllers\V2;

use App\Actions\Query\UserQuery;
use App\Actions\SaveImageAction;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Membership;
use App\Models\Team;
use App\Models\User;
use App\Models\UserMembership;
use App\Notifications\NewApplicant;
use App\Notifications\NewMember;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('store', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return UserCollection
     */
    public function index(Request $request, UserQuery $userQuery)
    {
        // TODO: pull frequent players first
        if ($request->has('filter.frequent')) {
            $users = $request->get('club')->players()->inRandomOrder()->get();

            return new UserCollection($users);
        }
        $users = $userQuery->get($request);
        return new UserCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return UserResource
     */
    public function store(Request $request, SaveImageAction $saveImageAction)
    {
        $validated = $request->validate([
            'first_name' => 'required|required',
            'last_name' => 'required|required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'birth_date' => 'required|date_format:Y-m-d',
            'address' => 'sometimes',
            'city' => 'sometimes',
            'phone' => 'sometimes',
            'gender' => 'sometimes|alpha',
            'county' => 'sometimes',
            'country' => 'sometimes',
            'image' => 'sometimes|nullable|image64:jpeg,jpg,png',
            //  'is_admin' => 'sometimes|boolean', TODO
            'primary_club_id' => 'sometimes|integer',
            'is_doubles' => 'sometimes|boolean',
        ]);
        $validated['password'] = \Hash::make($validated['password']);

        $image = isset($validated['image']) ? $validated['image'] : false;
        unset($validated['image']);
        if ($image) {
            $validated['image'] = $saveImageAction->execute($image, 'avatars');
        }
        $validated['birthyear'] = substr($validated['birth_date'], 0, 4);

        $validated['rating'] = 1500;
        if ($request->get('club')) {
            if (!isset($validated['primary_club_id'])) {
                $validated['primary_club_id'] = $request->get('club')->id;
            }
        }
        $validated['lang'] = $request->header('X-Language');
        $user = User::create($validated);
        //create Team
        $team = Team::create(['number_of_players' => 1, 'primary_contact_id' => $user->id]);
        $team->players()->attach($user);
        if ($request->input('parents')) {
            $user->parents()->sync(collect($request->input('parents'))->map(function ($parent) {
                return $parent['id'];
            }));
        }
        if ($request->input('children')) {
            $user->children()->sync(collect($request->input('children'))->map(function ($child) {
                return $child['id'];
            }));
        }
        if ($request->get('club')) {
            $user->clubs()->attach($request->get('club')->id, ['status' => $request->get('club')->validate_user ? 'applicant' : 'member']);
            if (!$request->get('club')->validate_user) {
                $team->clubs()->attach($request->get('club')->id);
            }
            foreach ($request->get('club')->all_admins as $admin) {
                if ($request->get('club')->validate_user) {
                    $admin->notify((new NewApplicant($user))->locale($admin->lang));
                } else {
                    $admin->notify((new NewMember($user))->locale($admin->lang));
                }
            }
            //Notification::send($request->get('club')->all_admins, $request->get('club')->validate_user ? new NewApplicant($user) : new NewMember($user));
        }

        return UserResource::make($user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $player
     * @return UserResource
     */
    public function show($player)
    {
        $user = QueryBuilder::for(User::where('id', $player))
            ->allowedIncludes(['trainer', 'memberships', 'wallets', 'clubs', 'parents', 'children'])
            ->first();

        return UserResource::make($user);
    }

    /**
     * Get logged in user resource.
     */
    public function me()
    {
        /** @var User $user */
        $user = auth()->user();
        $user->load(['wallets', 'teams']);
        $club = request()->get('club') ? request()->get('club')->id : $user->primary_club_id;
        $membership = UserMembership::where('user_id', $user->id)->whereHas('membership', function ($query) use ($club) {
            $query->where('club_id', $club);
        })->first();
        if ($membership) {
            $membership = $membership->membership;
        } else {
            $membership = Membership::where('club_id', $club)->where('basic', 1)->first();
        }

        return response()->json(['user' => UserResource::make($user, $membership)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $player
     * @return UserResource
     */
    public function update(Request $request, User $player, SaveImageAction $saveImageAction)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes',
            'last_name' => 'sometimes',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($player->id)],
            'new_password' => 'sometimes|nullable|min:8',
            'birth_date' => 'sometimes|date_format:Y-m-d',
            'address' => 'sometimes',
            'city' => 'sometimes',
            'phone' => 'sometimes',
            'gender' => 'sometimes|alpha',
            'county' => 'sometimes',
            'country' => 'sometimes',
            'new_image' => 'sometimes|nullable|image64:jpg,png,jpeg',
            'primary_club' => 'sometimes|integer',
            'is_doubles' => 'sometimes',
            'hidden_fields' => 'sometimes|array',
        ]);
        if (isset($validated['new_password'])) {
            $validated['password'] = \Hash::make($validated['new_password']);
        }
        unset($validated['new_password']);
        $image = isset($validated['new_image']) ? $validated['new_image'] : false;
        unset($validated['new_image']);
        if ($image) {
            $validated['image'] = $saveImageAction->execute($image, 'avatars');

        }
        if (isset($validated['birth_date'])) {
            $validated['birthyear'] = substr($validated['birth_date'], 0, 4);
        }
        $player->update($validated);
        $club = $request->get('club');
        if ($request->input('update_admin') && \Auth::user()->getClubAdminLevel($club) > 0) {
            $level = $player->getClubAdminLevel($request->get('club'));
            if ($level && $level < 2) { //protect owners
                $club->admins()->detach($player->id);
            }
            if (!$level) {
                $club->admins()->attach([$player->id => ['level' => 'admin']]);
            }
        }
        if ($request->has('parents')) {
            if ($request->input('parents')) {
                $player->parents()->sync(collect($request->input('parents'))->map(function ($parent) {
                    return $parent['id'];
                }));
            } else {
                $player->parents()->detach();
            }
        }
        if ($request->has('children')) {
            if ($request->input('children')) {
                $player->children()->sync(collect($request->input('children'))->map(function ($child) {
                    return $child['id'];
                }));
            } else {
                $player->children()->detach();
            }
        }
        if ($request->input('newMember')) {
            $club_id = $request->input('newMember');
            if (!$player->clubs()->where('clubs.id', $club_id)->first()) {
                $player->clubs()->attach($club_id, ['status' => $club->validate_user ? 'applicant' : 'member']);
                if (!$club->validate_user) {
                    $teams = Team::query()->where('primary_contact_id', $player->id)->get();
                    foreach ($teams as $team) {
                        if (!$team->clubs()->where('clubs.id', $club_id)->first()) {
                            $team->clubs()->attach($club_id);
                        }
                    }
                }
                foreach($club->all_admins as $admin) {
                    if ($club->validate_user) {
                        $admin->notify((new NewApplicant($player))->locale($admin->lang));
                    } else {
                        $admin->notify((new NewMember($player))->locale($admin->lang));
                    }
                }
                // Notification::send($request->get('club')->all_admins, $club->validate_user ? (new NewApplicant($player)) : (new NewMember($player)));
            }
        }
        $request->request->add(['wins' => true, 'stats' => true]);
        $player->clearCache();
        return UserResource::make($player);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $player
     * @return Response
     */
    public function destroy(User $player)
    {
        try {
            $player->email = $player->email . uniqid('deleted-');
            $player->save();
            $player->teams()->where('primary_contact_id', $player->id)->delete();
            $player->delete();
        } catch (Exception $e) {
            // TODO: handle errors
        }

        return response()->noContent();
    }

    public function setLocale(Request $request)
    {
        $user = auth()->user();
        $user->lang = $request->get('locale');
        $user->save();
        return response()->json(['success' => true]);
    }
}
