<?php

namespace App\Actions;

class CalculateELO {

    /**
     * @param float $rating1
     * @param float $rating2
     * @param bool $winner
     * @return array
     */
    public function execute($rating1, $rating2, $winner) {
        $r1 = 10 ** ($rating1 / 400);
        $r2 = 10 ** ($rating2 / 400);
        $e1 = $r1 / ($r1 + $r2);
        $e2 = $r2 / ($r1 + $r2);
        $s1 = intval(!$winner);
        $s2 = intval($winner);
        $r1 = $rating1 + 32 * ($s1 - $e1);
        $r2 = $rating2 + 32 * ($s2 - $e2);

        return [$r1, $r2, abs(32 * ($s1 - $e1))];
    }
}
