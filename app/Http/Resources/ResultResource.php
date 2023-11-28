<?php

namespace App\Http\Resources;

use App\Models\Result;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ResultResource
 *
 * @mixin Result
 */
class ResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $out = parent::toArray($request);
        $out['tie_break'] = $out['sets']['tie_break'] ?? [];
        if (isset($out['sets']['tie_break'])) {
            unset($out['sets']['tie_break']);
        }
        if ((!isset($out['players']) || !$out['players']) && isset($out['teams'])) {
            $out['players'] = $out['teams'];
        }

        return $out;
    }
}
