<?php

namespace App\Http\Controllers\Api;

use App\Events\ResultCommentAdded;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Result;
use App\Models\User;
use App\Notifications\NewResultComment;
use App\Notifications\RequestResultVerificationNotification;
use App\Notifications\ResultDisputed;
use App\Notifications\ResultVerified;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class ResultsController extends Controller
{
    public function getResults(Request $request, $type = 'all', $offset = 0)
    {
        $club = $request->get('club')->id;
        $r = Cache::tags(['results-'.$club])->get('results_'.$type.$offset);
        if (! $r) {
            if ($type === 'all') {
                $all = Result::orderBy('id', 'desc')->whereNull('non_member')->limit(10)->offset($offset)->whereHas('players', function ($query) use ($club) {
                    $query->whereHas('clubs', function ($q) use ($club) {
                        $q->where('id', $club);
                    });
                })->get();
                $total = Result::whereNull('non_member')->whereHas('players', function ($query) use ($club) {
                    $query->whereHas('clubs', function ($q) use ($club) {
                        $q->where('id', $club);
                    });
                })->count();
            }
            if ($type === 'official') {
                $all = Result::orderBy('id', 'desc')->where('official', true)->whereNull('non_member')->limit(10)->offset($offset)->whereHas('players', function ($query) use ($club) {
                    $query->whereHas('clubs', function ($q) use ($club) {
                        $q->where('id', $club);
                    });
                })->get();
                $total = Result::where('official', true)->whereNull('non_member')->whereHas('players', function ($query) use ($club) {
                    $query->whereHas('clubs', function ($q) use ($club) {
                        $q->where('id', $club);
                    });
                })->count();
            }
            if ($type === 'friendly') {
                $all = Result::orderBy('id', 'desc')->where('official', false)->whereNull('non_member')->limit(10)->offset($offset)->whereHas('players', function ($query) use ($club) {
                    $query->whereHas('clubs', function ($q) use ($club) {
                        $q->where('id', $club);
                    });
                })->get();
                $total = Result::where('official', false)->whereNull('non_member')->whereHas('players', function ($query) use ($club) {
                    $query->whereHas('clubs', function ($q) use ($club) {
                        $q->where('id', $club);
                    });
                })->count();
            }
            $r = ['results' => $all, 'total' => $total];
            Cache::tags(['results-'.$club])->put('results_'.$type.$offset, $r, 10);
        }

        return response()->json($r);
    }

    public function postResult(Request $request)
    {
        $this->authorize('create', Result::class);
        $p1 = $request->input('player_1.id');
        $court = $request->input('court.id');
        $sets = $request->input('sets');
        $result = new Result();
        $result->court_id = $court;
        $result->sets = $sets;
        $result->date = Carbon::now();
        $result->official = (bool) $request->input('official');
        $result->rated = (bool) $request->input('official');
        $result->save();
        $result->players()->attach($p1, ['verified' => true]);
        $p1 = User::find($p1);
        if ($request->has('player_2.id')) {
            $p2 = $request->input('player_2.id');
            $result->players()->attach($p2);
            $p2 = User::find($p2);
            //notify p2
            $p2->notify((new RequestResultVerificationNotification($result, $p1))->locale($p2->lang));
        } else {
            $result->non_member = $request->input('player_2.display_name');
            $result->official = false;
            $result->rated = false;
            $result->verified = false;
            $result->save();
        }
        Cache::tags(['results-'.$request->get('club')->id])->flush();

        return response()->json(['result' => $result]);
    }

    public function getResult(Result $result)
    {
        $result->load(['court', 'players', 'comments']);

        return response()->json(['result' => $result]);
    }

    public function verifyResult(Request $request, Result $result)
    {
        $this->authorize('update', $result);
        $result->players()->updateExistingPivot(Auth::id(), ['verified' => true]);
        $result->verified_at = Carbon::now();
        $result->save();
        $result->load('players');
        $result->players[0]->notify((new ResultVerified($result, Auth::user()))->locale($result->players[0]->lang));
        Cache::tags(['results-'.$request->get('club')->id])->flush();

        return response()->json(['result' => $result]);
    }

    public function deleteResult(Request $request, Result $result)
    {
        $this->authorize('delete', $result);

        if (! $result->verified && (Auth::user()->is_admin || $result->players[0]->id === Auth::id())) {
            $result->delete();
        }
        Cache::tags(['results-'.$request->get('club')->id])->flush();

        return response()->json();
    }

    public function disputeResult(Request $request, Result $result)
    {
        $this->authorize('delete', $result);

        if (! $result->verified && (Auth::user()->is_admin || $result->players[1]->id === Auth::id())) {
            $result->delete();
        }
        $result->players[0]->notify((new ResultDisputed($result, Auth::user()))->locale($result->players[0]->lang));
        Cache::tags(['results-'.$request->get('club')->id])->flush();

        return response()->json();
    }

    public function postComment(Request $request, Result $result)
    {
        $comment = new Comment();
        $comment->message = $request->input('message');
        $comment->commentable()->associate($result);
        $comment->player()->associate(Auth::user());
        $comment->save();
        $result->load('comments');
        $users = $result->comments->mapWithKeys(function ($item) {
            return [$item->player_id => $item->player];
        });
        $users->merge($result->players->mapWithKeys(function ($item) {
            return [$item->id => $item];
        }));
        $users->forget(Auth::id());
        foreach ($users as $user) {
            $user->notify((new NewResultComment($comment))->locale($user->lang));
        }
        //Notification::send($users, new NewResultComment($comment));
        broadcast(new ResultCommentAdded($comment))->toOthers();

        return response()->json($comment);
    }
}
