<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletTransactionCollection;
use App\Http\Resources\WalletTransactionResource;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class WalletTransactionController extends Controller
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
    public function index(Request $request)
    {
        $transactions = QueryBuilder::for(WalletTransaction::class)
            ->allowedIncludes(['wallet', 'user', 'wallet.owner'])
            ->allowedFilters([
                AllowedFilter::exact('wallet', 'wallet_id'),
                AllowedFilter::scope('amount_greater_than'),
                AllowedFilter::scope('amount_less_than'),
                AllowedFilter::scope('created_between'),
                AllowedFilter::exact('club', 'wallet.club_id'),
            ])
            ->allowedSorts([
                AllowedSort::field('created_at'),
            ])->defaultSort('-created_at')
            ->paginate(request()->input('limit'))
            ->appends($request->query());

        return WalletTransactionCollection::make($transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, \App\Actions\Wallet\WalletTransaction $walletTransaction)
    {
        $club = $request->get('club');
        if (!\Auth::user()->is_admin->contains($club->id)) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'note' => 'sometimes',
            'player.id' => 'required|exists:users,id',
            'wallet.id' => 'sometimes'
        ]);

        $wallet = null;
        if (isset($validated['wallet'])) {
            $wallet = Wallet::find($validated['wallet']['id']);
        }
        $player = User::find($validated['player']['id']);
        $transaction = $walletTransaction->handle($wallet, $player, $validated['amount'], $validated['note']);

        return WalletTransactionResource::make($transaction);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(WalletTransaction $walletTransaction)
    {
        return WalletTransactionResource::make($walletTransaction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(WalletTransaction $walletTransaction)
    {
        //
    }
}
