<?php

namespace App\Actions\Imports;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Support\Str;

class UserImportAction {

        public function handle($file, $club, $tournament = null)
        {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $rows = array_splice($rows, 8);
            $header = [
                'first_name',
                'last_name',
                'sex',
                'email',
                'birth_date',
                'city',
                'address',
                'country',
                'phone',
                'oib',
            ];
            $t = false;
            if ($tournament) {
                $t = Tournament::find($tournament);
            }
            foreach ($rows as $row) {
                $row = array_combine($header, $row);
                if (!$row['email']) continue;
                $user = User::where('email', $row['email'])->first();
                if (!$user) {
                    $row['password'] = \Hash::make(Str::random(8));
                    $row['rating'] = 1500;
                    $row['birth_date'] = date('Y-m-d', strtotime($row['birth_date']));
                    $row['birthyear'] = $row['birth_date'] ? date('Y', strtotime($row['birth_date'])) : null;
                    $user = User::create($row);
                    $team = Team::create(['number_of_players' => 1, 'primary_contact_id' => $user->id]);
                    $team->players()->attach($user);
                } else {
                    $team = $user->teams()->where('number_of_players', 1)->where('primary_contact_id', $user->id)->first();
                }
                $user->clubs()->attach($club->id, ['status' => 'member']);
                $team->clubs()->attach($club->id);
                if ($t) {
                    $t->players()->attach($team);
                }
            }
            if ($t) {
                $t->status = 2;
                $t->save();
            }
            return true;
        }
}
