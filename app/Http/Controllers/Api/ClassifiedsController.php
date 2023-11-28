<?php

namespace App\Http\Controllers\Api;

use App\Events\ClassifiedCommentAdded;
use App\Http\Controllers\Controller;
use App\Models\Classified;
use App\Models\Comment;
use App\Notifications\NewClassifiedComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ClassifiedsController extends Controller
{
    public function getClassifieds(Request $request)
    {
        $category = $request->input('category');
        $all = Classified::orderBy('id', 'desc')->limit(20)->offset($request->input('offset'));
        if ($category !== 'all') {
            $all->where('category', $category);
        }
        $all = $all->get();
        $total = Classified::count();

        return response()->json(['classifieds' => $all, 'total' => $total]);
    }

    public function getClassified(Classified $classified)
    {
        $classified->load(['comments', 'user']);

        return response()->json($classified);
    }

    public function postClassified(Request $request)
    {
        $classified = new Classified();
        $classified->title = $request->input('title');
        $classified->description = $request->input('description');
        $classified->price = $request->input('price');
        $classified->category = $request->input('category');
        $classified->user()->associate(Auth::user());
        if ($request->hasFile('image') && $request->file('image')) {
            $file = $request->file('image');
            if (! $file->isValid()) {
                return response()->json(['result' => 'error',
                    'message' => 'Invalid file upload',
                ], 400);
            }
            $path = $file->store(sprintf('%s/%d/%d', 'classifieds', date('Y'), date('m')), 'public');
            $classified->image = $path;
        } else {
            $classified->image = ' ';
        }
        $classified->save();

        return response()->json(['link' => '/classified/'.$classified->id]);
    }

    public function putClassified(Request $request, Classified $classified)
    {
        $classified->title = $request->input('title');
        $classified->description = $request->input('description');
        $classified->price = $request->input('price');
        $classified->category = $request->input('category');
        if ($request->hasFile('image') && $request->file('image')) {
            $file = $request->file('image');
            if (! $file->isValid()) {
                return response()->json(['result' => 'error',
                    'message' => 'Invalid file upload',
                ], 400);
            }
            $path = $file->store(sprintf('%s/%d/%d', 'classifieds', date('Y'), date('m')), 'public');
            $classified->image = $path;
        }
        $classified->save();

        return response()->json(['link' => '/classified/'.$classified->id]);
    }

    public function postComment(Request $request, Classified $classified)
    {
        $comment = new Comment();
        $comment->message = $request->input('message');
        $comment->commentable()->associate($classified);
        $comment->player()->associate(Auth::user());
        $comment->save();
        $classified->load('comments');
        $users = $classified->comments->mapWithKeys(function ($item) {
            return [$item->player_id => $item->player];
        });
        $users[$classified->user->id] = $classified->user;
        $users->forget(Auth::id());
        //Notification::send($users, new NewClassifiedComment($comment));
        foreach ($users as $user) {
            $user->notify((new NewClassifiedComment($comment))->locale($user->lang));
        }
        broadcast(new ClassifiedCommentAdded($comment))->toOthers();

        return response()->json($comment);
    }

    public function deleteClassified(Classified $classified)
    {
        $classified->delete();

        return response()->json();
    }
}
