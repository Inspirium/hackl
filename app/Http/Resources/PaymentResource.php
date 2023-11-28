<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PaymentResource
 *
 * @mixin Payment
 */
class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
