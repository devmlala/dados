<?php

namespace App\Exports;

use App\Services\LattesMetricsService;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

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

        $sheets = [];

        // Exporta apenas as seções qualitativas com conteúdo
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

        foreach ($map as $key => $nome) {
            if (!empty($dados[$key])) {
                $sheets[] = new \App\Exports\ArraySheetExport($dados[$key], $nome);
            }
        }

        return $sheets;
    }
}
