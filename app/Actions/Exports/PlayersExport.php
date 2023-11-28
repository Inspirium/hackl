<?php

namespace App\Actions\Exports;

class PlayersExport extends Export
{
    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Ime',
            'Prezime',
            'Email',
            'Telefon',
            'Datum rođenja',
            'Adresa',
            'Grad',
            'Poštanski broj',
            'Država',
        ];
    }

    public function map($player): array
    {
        return [
            $player->id,
            $player->first_name,
            $player->last_name,
            $player->email,
            $player->phone,
            $player->date_of_birth,
            $player->address,
            $player->city,
            $player->zip,
            $player->country,
        ];
    }

    public function title(): string
    {
        return 'Igrači';
    }

}
