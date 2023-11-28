<?php

namespace App\ImageTemplates;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Message implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->orientate()->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}
