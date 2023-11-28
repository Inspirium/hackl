<?php

namespace App\ImageTemplates;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Avatar implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->orientate()->fit(400, 400)->sharpen(20);
    }
}
