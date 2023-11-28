<?php

namespace App\Actions\Exports;

class PaymentExport extends Export
{
    public function collection()
    {
        return collect($this->query);
    }

    public function headings(): array
    {
        return [
            'Način plaćanja',
            'Iznos bruto',
        ];
    }

    public function map($item): array
    {
        return [
            $item['method'],
            $item['total_amount'],
        ];
    }

    public function title(): string
    {
        return 'Promet';
    }

}
