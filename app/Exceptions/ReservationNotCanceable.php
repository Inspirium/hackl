<?php

namespace App\Exceptions;

use Exception;

class ReservationNotCanceable extends Exception
{


    public function render()
    {
        return response()->json([
            'message' => $this->message,
        ], 403);
    }
}
