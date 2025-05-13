<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PesertaTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'name',
            'email',
            'address',
            'no_hp',
        ];
    }

    public function array(): array
    {
        // Kosong, hanya untuk template
        return [];
    }
}
