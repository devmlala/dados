<?php

namespace App\Exports;

use App\Services\LattesMetricsService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DocenteDetalhadoExport implements WithMultipleSheets
{
    protected $codpes;

    public function __construct($codpes)
    {
        $this->codpes = $codpes;
    }

    public function sheets(): array
    {
        $service = new LattesMetricsService();
        $dados = $service->getMetricasDetalhadas($this->codpes);

        $map = [
            'artigos' => 'Artigos',
            'livros' => 'Livros',
            'capitulosLivros' => 'Capítulos',
            'projetos' => 'Projetos',
            'orientacoesIC' => 'Orientações IC',
            'orientacoesConcluidasDoc' => 'Doutorado',
            'orientacoesMestrado' => 'Mestrado',
            'premios' => 'Prêmios',
        ];

        $linhas = [];

        foreach ($map as $key => $nome) {
            foreach ($dados[$key] ?? [] as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $linha = ['Tipo' => $nome];

                foreach ($item as $chave => $valor) {
                    if (is_array($valor)) {
                        $linha[$chave] = implode('; ', array_map(function ($v) {
                            return is_array($v) ? implode(' ', $v) : $v;
                        }, $valor));
                    } else {
                        $linha[$chave] = $valor;
                    }
                }

                $linhas[] = $linha;
            }
        }

        // ✅ Remove linhas duplicadas
        $linhas = array_values(array_unique($linhas, SORT_REGULAR));

        return [
            new class($linhas) implements FromArray, WithHeadings, WithTitle {
                private $linhas;

                public function __construct(array $linhas)
                {
                    $this->linhas = $linhas;
                }

                public function array(): array
                {
                    return $this->linhas;
                }

                public function headings(): array
                {
                    return array_keys($this->linhas[0] ?? []);
                }

                public function title(): string
                {
                    return 'Produção Docente';
                }
            }
        ];
    }
}
