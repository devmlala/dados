<?php

namespace App\Exports;

use App\Services\LattesMetricsService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocenteExport implements FromArray, WithHeadings
{
    protected $codpes;

    public function __construct($codpes)
    {
        $this->codpes = $codpes;
    }

    public function array(): array
    {
        $service = new LattesMetricsService();
        $dados = $service->getMetricasDetalhadas($this->codpes);

        // Mapeia as chaves para nomes legíveis, correspondendo ao dashboard
        $map = [
            'artigos' => 'Artigos',
            'livros' => 'Livros',
            'capitulos-livros' => 'Capítulos de Livros',
            'projetos' => 'Projetos',
            'eventos' => 'Participação em Eventos',
            'orientacoes-concluidas-ic' => 'Orientações de IC Concluídas',
            'orientacoes-concluidas-mestrado' => 'Orientações de Mestrado Concluídas',
            'orientacoes-concluidas-doutorado' => 'Orientações de Doutorado Concluídas',
            'orientacoes-concluidas-pos-doc' => 'Orientações de Pós-Doutorado Concluídas',
            'premios' => 'Prêmios e Títulos',
            'organizacao-eventos' => 'Organização de Eventos',
            'bancas-doutorado' => 'Bancas de Doutorado',
            'bancas-mestrado' => 'Bancas de Mestrado',
            'trabalhos-anais' => 'Trabalhos em Anais',
            'trabalhos-tecnicos' => 'Trabalhos Técnicos',
            'apresentacao-de-trabalho' => 'Apresentações de Trabalho',
            'textos-jornais-revistas' => 'Textos em Jornais/Revistas',
            'relatorios-pesquisa' => 'Relatórios de Pesquisa',
            'material-didatico' => 'Material Didático',
        ];

        $formatado = [];
        foreach ($map as $chave => $nome) {
            $formatado[] = [
                'Metrica' => $nome,
                'Total' => $dados['contagem'][$chave] ?? 0,
            ];
        }

        return $formatado;
    }

    public function headings(): array
    {
        return ['Métrica', 'Total'];
    }
}
