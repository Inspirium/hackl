<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\OtherExpenseCollection;
use App\Http\Resources\OtherExpenseResource;
use App\Models\Club;
use App\Models\OtherExpense;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class OtherExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Club $club)
    {
        $expenses = QueryBuilder::for(OtherExpense::class)
            ->where('club_id', $club->id)
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return OtherExpenseCollection::make($expenses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Club $club)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);
        $validated['club_id'] = $club->id;
        $expense = OtherExpense::create($validated);

        return OtherExpenseResource::make($expense);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OtherExpense  $otherExpense
     * @return \Illuminate\Http\Response
     */
    public function show(Club $club, OtherExpense $otherExpense)
    {
        return OtherExpenseResource::make($otherExpense);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OtherExpense  $otherExpense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Club $club, OtherExpense $otherExpense)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        $otherExpense->update($validated);

        return OtherExpenseResource::make($otherExpense);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OtherExpense  $otherExpense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Club $club, OtherExpense $otherExpense)
    {
        $otherExpense->delete();

        return response()->noContent();
    }
}
