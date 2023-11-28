<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class SaveImageAction
{
    public function execute($imageData, $dirName)
    {
        $fileName = Carbon::now()->timestamp.'_'.uniqid().'.'.explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
        $dir = storage_path('/app/public/'.sprintf('%s/%d/%d/', $dirName, date('Y'), date('m')));

        File::isDirectory($dir) or File::makeDirectory($dir, 0777, true, true);

        $path = sprintf('%s/%d/%d/', $dirName, date('Y'), date('m')).$fileName;

        Image::make($imageData)->save(storage_path('/app/public/'.$path));

        return $path;
    }
}
