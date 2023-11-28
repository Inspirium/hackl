<?php

namespace App\Http\Controllers\V2;

use App\Actions\Query\UserQuery;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Models\WorkOrder;
use App\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $notifications = QueryBuilder::for(DatabaseNotification::class)
            ->allowedFilters([
                AllowedFilter::callback('read', function ($query, $value) {
                    switch ($value) {
                        case 'read':
                            $query->whereNotNull('read_at');
                            break;
                        case 'unread':
                            $query->whereNull('read_at');
                            break;
                    }
                }),
                AllowedFilter::callback('type', function ($query, $value) {
                    $query->where('type', '=', $value);
                }),
                AllowedFilter::callback('-type', function ($query, $value) {
                    $query->where('type', '<>', $value);
                }),
            ])
            ->where('notifiable_id', Auth::id())->where('notifiable_type', 'App\Models\User')
            ->orderBy('created_at', 'desc')
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return NotificationResource::collection($notifications);
    }

    public function mark_all()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'success']);
    }

    public function update($notification)
    {
        $notification = DatabaseNotification::findOrFail($notification);
        $notification->markAsRead();

        return response()->json(['message' => 'success']);
    }

    public function delete($notification)
    {
        $notification = DatabaseNotification::findOrFail($notification);
        $notification->delete();

        return response()->noContent();
    }

    public function number()
    {
        if (\request('v') == 2) {
            $data = Cache::get('notifications_' . Auth::id());
            if (!$data) {
                $reservations = Auth::user()->reservations()->whereDate('from', Carbon::today())->count();
                $orders = Auth::user()->orders()->count();
                $workOrders = WorkOrder::query()->where('assignee_id', Auth::id())->count();
                $data = [
                    'number' => Auth::user()->unreadNotifications()->count(),
                    'unreads' => 0,
                    'reservations' => $reservations,
                    'orders' => $orders,
                    'workOrders' => $workOrders,
                ];
                Cache::put('notifications_' . Auth::id(), $data, 600);
            }
            return $data;
        }
        return Auth::user()->unreadNotifications()->count();
    }

    public function checkToken(Request $request)
    {
        $token = $request->input('token');
        /** @var User $user */
        $user = Auth::user();
        $sub = $user->fireBaseSubscriptions()->where('token', $token)->first();
        if ($sub) {
            return response()->json(['has' => true]);
        }

        return response()->json(['has' => false]);
    }

    public function subscribe(Request $request)
    {
        $token = $request->input('token');
        /** @var User $user */
        $user = Auth::user();
        $sub = $user->fireBaseSubscriptions()->where('token', $token)->first();
        if ($sub) {
            // $sub->delete();
        } else {
            $user->fireBaseSubscriptions()->create([
                'token' => $token,
                'club_id' => $request->get('club')?$request->get('club')->id:null,
                'platform' => 'web',
            ]);
        }

        return response()->noContent();
    }

    public function send(Request $request, UserQuery $userQuery) {
        $title = $request->input('title');
        $body = $request->input('body');
        $link = $request->input('link');
        $user = Auth::user();
        $users = $userQuery->get($request, -1);

        if (strpos($link, 'http') === false) {
            $link = 'https://' . $link;
        }
        if (strpos($link, 'http:') > -1) {
            $link = str_replace('http:', 'https:', $link);
        }
        if (strpos($link, 'tenis.plus') > -1) {
            $link = preg_replace('/https:\/\/(.*)\.tenis\.plus/', '', $link);
        }

        //\Notification::send($users, new Notification($user, $title, $body, $link));
        foreach($users as $user) {
            $user->notify((new Notification($user, $title, $body, $link))->locale($user->lang));
        }
        return response()->json(['users' => $users]);
    }
}
