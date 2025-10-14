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

        // Mapeia as chaves de dados para nomes de planilhas legíveis
        $map = [
            'artigos' => 'Artigos',
            'livros' => 'Livros',
            'capitulosLivros' => 'Capítulos',
            'eventos' => 'Participação em Eventos',
            'organizacaoEventos' => 'Organização de Eventos',
            'projetos' => 'Projetos',
            'premios' => 'Prêmios e Títulos',
            'orientacoesConcluidasDoc' => 'Orientações de Doutorado',
            'orientacoesMestrado' => 'Orientações de Mestrado',
            'orientacoesIC' => 'Orientações IC',
            'orientacoesPosDoc' => 'Orientações de Pós-Doc',
            'bancasDoutorado' => 'Bancas de Doutorado',
            'bancasMestrado' => 'Bancas de Mestrado',
            'trabAnais' => 'Trabalhos em Anais',
            'trabTecnicos' => 'Trabalhos Técnicos',
            'apresTrab' => 'Apresentações de Trabalho',
            'textosJornaisRevistas' => 'Textos em Jornais/Revistas',
            'relatoriosPesquisa' => 'Relatórios de Pesquisa',
            'materialDidatico' => 'Material Didático',
            'formacaoAcademica' => 'Formação Acadêmica',
        ];

        $sheets = [];

        foreach ($map as $key => $nome) {
            if (!empty($dados[$key]) && is_array($dados[$key])) {
                $sheets[] = new ArraySheetWithHeaderExport($dados[$key], $nome);
            }
        }

        return $sheets;
    }
}
