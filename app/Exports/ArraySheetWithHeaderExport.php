<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ArraySheetWithHeaderExport extends ArraySheetExport implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        if (empty($this->data)) {
            return [];
        }

        // Pega as chaves do primeiro item para usar como cabeçalho
        $firstItem = reset($this->data);
        return is_array($firstItem) ? array_keys($firstItem) : ['Descrição'];
    }
}