<?php

namespace App\Actions;

class ATPScore
{
    public static function get($status, $type, $total) {
        if ($status === '*') {
            $diff = '*';
        } else {
            if ($status) {
                $status = explode('/', $status);
                $diff = intval($status[1]) - intval($status[0]);
            } else {
                $diff = $total;
            }
        }
        switch ($type) {
            case 1000:
                return match ($diff) {
                    '*' => 1000,
                    0 => 600,
                    1 => 360,
                    2 => 180,
                    3 => 90,
                    4 => 45,
                    5 => 25,
                    6 => 10,
                    default => 0
                };
            case 500:
                return match ($diff) {
                    '*' => 500,
                    0 => 300,
                    1 => 180,
                    2 => 90,
                    3 => 45,
                    4 => 20,
                    5 => 10,
                    6 => 5,
                    default => 0
                };
            case 250:
                return match ($diff) {
                    '*' => 250,
                    0 => 150,
                    1 => 90,
                    2 => 45,
                    3 => 20,
                    4 => 10,
                    5 => 5,
                    6 => 2,
                    default => 0
                };
            case 125:
                return match ($diff) {
                    '*' => 125,
                    0 => 75,
                    1 => 45,
                    2 => 25,
                    3 => 10,
                    4 => 5,
                    5 => 2,
                    6 => 1,
                    default => 0
                };
        }

    }
}
