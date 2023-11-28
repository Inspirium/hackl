<?php

namespace App\Exceptions;

use Exception;

class PlayerHasTooManyReservations extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Player has too many reservations',
        ], 403);
    }
}
