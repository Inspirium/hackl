<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $wallets = QueryBuilder::for(Wallet::class)
            ->allowedFilters([
                AllowedFilter::exact('owner', 'owner_id'),
                AllowedFilter::exact('club', 'club_id'),
                AllowedFilter::scope('amount_greater_than'),
                AllowedFilter::scope('amount_less_than'),
            ])
            ->allowedIncludes([
                'owner', 'club', 'transactions',
            ])
            ->allowedSorts('amount')
            ->paginate(request()->input('limit'))
            ->appends($request->query());

        return WalletResource::make($wallets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show($wallet)
    {
        $wallet = QueryBuilder::for(Wallet::where('id', $wallet))
            ->allowedIncludes(['owner', 'club', 'transactions'])
            ->first();

        return WalletResource::make($wallet);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        $validated = $request->validate([
            'name' => 'sometimes',
        ]);
        $wallet->update($validated);

        return WalletResource::make($wallet);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return response()->noContent();
    }
}
