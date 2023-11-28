<?php

namespace App\Http\Controllers\V2;

use App\Actions\Imports\UserImportAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function users(Request $request, UserImportAction $userImportAction) {
        $file = $request->file('file');
        $club = $request->get('club');
        $tournament = $request->get('tournament');
        $userImportAction->handle($file, $club, $tournament);
        return response()->json(['message' => 'Imported successfully']);
    }
}
