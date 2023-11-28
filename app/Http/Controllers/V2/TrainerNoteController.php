<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\TrainerNote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TrainerNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = QueryBuilder::for(TrainerNote::class)
            ->allowedFilters([
                'is_public',
                AllowedFilter::exact('team', 'team_id'),
                AllowedFilter::exact('club', 'club_id'),
                AllowedFilter::exact('trainer', 'trainer_id'),
                AllowedFilter::callback('school_group', function ($query, $schoolGroup) {
                    $query->whereHas('team', function ($q) use ($schoolGroup) {
                        $q->whereHas('school_group', $schoolGroup);
                    });
                }),
                AllowedFilter::callback('display_name', function ($query, $display_name) {
                    $query->whereHas('team', function ($query) use ($display_name) {
                        $query
                            ->where(function($query) use ($display_name) {
                                $query->where('display_name', 'LIKE', "%$display_name")
                                    ->orWhereHas('players', function($query) use ($display_name) {
                                        $query->where('last_name', 'LIKE', "%$display_name%");
                                        $query->orWhere('first_name', 'LIKE', "%$display_name%");
                                    });
                            });
                    });
                }),
            ])
            ->allowedIncludes(['team', 'club', 'trainer'])
            ->allowedSorts(['title', 'created_at'])->defaultSort('-created_at')
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return JsonResource::collection($notes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team.id' => 'required|exists:teams,id',
            'title' => 'required',
            'content' => 'nullable',
            'club.id' => 'sometimes|exists:clubs,id',
            'trainer.id' => 'required|exists:users,id',
            'is_public' => 'sometimes|boolean',
            'videos' => 'sometimes|array',
        ]);

        $note = TrainerNote::create([
            'team_id' => $validated['team']['id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'club_id' => $validated['club']['id'],
            'trainer_id' => $validated['trainer']['id'],
            'is_public' => $validated['is_public'] ?? false,
            'videos' => $validated['videos'] ?? '[]',
        ]);
        return JsonResource::make($note);
    }

    /**
     * Display the specified resource.
     */
    public function show($trainerNote)
    {
        $trainerNote = QueryBuilder::for(TrainerNote::class)
            ->allowedIncludes(['team', 'club', 'trainer'])
            ->find($trainerNote);
        return JsonResource::make($trainerNote);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainerNote $trainerNote)
    {
        $validated = $request->validate([
            'title' => 'sometimes',
            'content' => 'nullable',
            'is_public' => 'sometimes|boolean',
            'videos' => 'sometimes|array',
        ]);

        $trainerNote->update([
            'title' => $validated['title'] ?? $trainerNote->title,
            'content' => $validated['content'] ?? $trainerNote->content,
            'is_public' => $validated['is_public'] ?? $trainerNote->is_public,
            'videos' => $validated['videos'] ?? $trainerNote->videos,
        ]);

        return JsonResource::make($trainerNote);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainerNote $trainerNote)
    {
        $trainerNote->delete();
        return response()->noContent();
    }
}
