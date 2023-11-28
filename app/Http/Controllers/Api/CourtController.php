<?php
/**
 * Filename: CourtController.php
 *
 * User: mbanusic
 * Date: 03/04/2018
 * Time: 14:32
 *
 * Contact: http://mbanusic.com
 * License: GPL-2.0+
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Court;

class CourtController extends Controller
{
    public function getCourt(Court $court, $date = null)
    {
        if (! $date) {
            $date = date('Y-m-d');
        }
        $court->load([
            'reservations' => function ($query) use ($date) {
                $query->whereDate('from', $date);
            },
            'working_hours' => function ($query) use ($date) {
                $query->whereDate('active_from', '>', $date);
                $query->whereDate('active_to', '<', $date);
            },
        ]);
        $hours = $court->getParsedReservations($date);

        return response()->json(['court' => $court, 'reservations' => $hours]);
    }
}
