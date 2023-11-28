<?php

namespace App\ImageTemplates;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Logo implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->orientate()->fit(390, 345);
    }
}
