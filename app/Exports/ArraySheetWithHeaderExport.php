<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ArraySheetWithHeaderExport extends ArraySheetExport implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        // Mapa de tradução de chaves para nomes de colunas legíveis
        $headerMap = [
            'TIPO_EVENTO' => 'Tipo',
            'NOME_EVENTO' => 'Nome do Evento',
            'ANO' => 'Ano',
            'FORMA_PARTICIPACAO' => 'Forma de Participação',
            'LOCAL_EVENTO' => 'Local',
            'TITULO' => 'Título',
            'TIPO' => 'Tipo',
            'NOME_CANDIDATO' => 'Candidato(a)',
            'NOME_INSTITUICAO' => 'Instituição',
            'Descrição' => 'Título', // For simple lists like Bancas
        ];

        if (empty($this->data)) {
            return [];
        }

        // Pega as chaves do primeiro item para usar como cabeçalho
        $firstItem = reset($this->data);
        $keys = is_array($firstItem) ? array_keys($firstItem) : ['Descrição'];

        // Traduz as chaves para os nomes do mapa, se existirem
        return array_map(function ($key) use ($headerMap) {
            return $headerMap[$key] ?? $key;
        }, $keys);
    }
}