<?php

namespace App\Http\Controllers\V2;

use App\Actions\NewPaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Club;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PaymentController extends Controller
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
        $payments = QueryBuilder::for(Payment::class)
            ->allowedFilters([
                AllowedFilter::exact('user', 'user_id'),
                AllowedFilter::partial('type', 'type_type'),
                AllowedFilter::callback('date', function ($query, $value) {
                    $query->whereDate('created_at', '=', $value);
                }),
            ])
            ->where('club_id', $club->id)
            ->allowedIncludes(['user', 'receiver', 'club', 'type'])
            ->allowedSorts(['created_at'])
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return PaymentCollection::make($payments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Club $club, NewPaymentAction $newPaymentAction)
    {
        $players = $request->input('playerList');
        $type = $request->input('type', null);
        if ($type) {
            $type = app($type['class'])->find($type['id']);
        }
        foreach ($players as $player) {
            $user = User::find($player['id']);
            if ($user) {
                $newPaymentAction->execute($club, $user, Auth::user(), $type);
            }
        }

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($payment, Club $club = null)
    {
        $payment = QueryBuilder::for(Payment::where('id', $payment))
            ->allowedIncludes(['user', 'receiver', 'club', 'type'])
            ->first();

        return PaymentResource::make($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Club $club, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Club $club, Payment $payment)
    {
        $payment->delete();

        return response()->noContent();
    }
}
