<?php

namespace App\Exceptions;

use Exception;

class CourtTimeUnavailable extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Court time unavailable',
        ], 403);
    }
}
