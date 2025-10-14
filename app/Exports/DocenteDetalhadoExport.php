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
                $processedData = $this->processarDadosParaExportacao($dados[$key]);
                $sheets[] = new ArraySheetWithHeaderExport($processedData, $nome);
            }
        }

        return $sheets;
    }

    /**
     * Processa os dados para garantir que os campos de array sejam convertidos em strings.
     *
     * @param array $data
     * @return array
     */
    private function processarDadosParaExportacao(array $data): array
    {
        $processed = [];
        foreach ($data as $row) {
            if (!is_array($row)) continue;

            $newRow = [];
            foreach ($row as $key => $value) {
                if (is_array($value)) {
                    // Converte arrays em uma string legível
                    $flattened = array_map(function ($item) {
                        if (is_array($item) && isset($item['NOME-COMPLETO-DO-AUTOR'])) {
                            return $item['NOME-COMPLETO-DO-AUTOR'];
                        }
                        return is_array($item) ? json_encode($item) : (string)$item;
                    }, $value);
                    $newRow[$key] = implode('; ', $flattened);
                } else {
                    $newRow[$key] = $value;
                }
            }
            $processed[] = $newRow;
        }
        return $processed;
    }
}
