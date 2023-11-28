<?php

namespace App\Actions\Exports;

class InvoiceExport extends Export
{
    public function collection()
    {
        return collect($this->query);
    }

    public function headings(): array
    {
        return [
            'Način plaćanja',
            'Stopa poreza',
            'Iznos bruto',
            'Iznos neto',
            'Iznos PDV',
        ];
    }

    public function map($item): array
    {
        return [
            $item['method'],
            $item['tax_rate'],
            $item['total_amount'],
            $item['amount'],
            $item['tax_amount'],
        ];
    }

    public function title(): string
    {
        return 'Promet';
    }

}
