<?php

namespace App\Actions\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Export
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [];
    }

    public function map($item): array
    {
        return [];
    }

    public function title(): string
    {
        return '';
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($this->headings(), null, 'A1');
        $sheet->fromArray($this->collection()->map(function ($item) {
            return $this->map($item);
        })->toArray(), null, 'A2');
        $writer = new Xlsx($spreadsheet);
        $filename = storage_path($this->title() . '.xlsx');
        $writer->save($filename);
        return $this->download($filename);
    }

    public function download($filename)
    {
        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
