<?php

namespace App\ImageTemplates;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Classified implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->orientate()->fit(768, 576)->sharpen(20);
    }
}
