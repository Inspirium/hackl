<?php

namespace App\Actions;

use App\Models\Document;
use Carbon\Carbon;

class SaveDocument
{
    public static function save($document, $data = [])
    {
        $fileName = Carbon::now()->timestamp.'_'.$document->getClientOriginalName();

        $path = $document->storeAs('documents', $fileName, 'public');

        $d = Document::create([
            'title' => $data['title'] ?? $document->getClientOriginalName(),
            'path' => $path,
            'user_id' => \Auth::id(),
        ]);

        return $d;
    }
}
