<?php

namespace App\Http\Resources;

use App\Models\WalletTransaction;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class WalletTransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function with($request)
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
            ->sum('amount');
        return [
            'meta' => [
                'sum' => $transactions,
            ]
        ];
    }
}
