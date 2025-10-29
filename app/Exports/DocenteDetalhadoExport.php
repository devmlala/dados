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
                // Verifica se existe um manipulador específico para esta chave de dados
                $handlerMethod = 'processar' . ucfirst($key);
                $processedData = method_exists($this, $handlerMethod)
                    ? $this->{$handlerMethod}($dados[$key])
                    : $this->processarDadosGenericos($dados[$key]);

                $sheets[] = new ArraySheetWithHeaderExport($processedData, $nome);
            }
        }

        return $sheets;
    }

    /**
     * Processador de dados genérico. Formata a coluna de autores.
     */
    private function processarDadosGenericos(array $data): array
    {
        foreach ($data as &$item) {
            if (isset($item['AUTORES']) && is_array($item['AUTORES'])) {
                $autoresNomes = array_map(fn($autor) => $autor['NOME-COMPLETO-DO-AUTOR'] ?? 'N/A', $item['AUTORES']);
                $item['AUTORES'] = implode('; ', $autoresNomes);
            }
        }
        return $data;
    }

    /**
     * Manipulador específico para 'bancasMestrado'.
     */
    private function processarBancasMestrado(array $data): array
    {
        $bancasFormatadas = [];
        foreach ($data as $banca) {
            if (is_array($banca) && isset($banca['DADOS-BASICOS-DA-PARTICIPACAO-EM-BANCA-DE-MESTRADO']['@attributes'])) {
                $dadosBasicos = $banca['DADOS-BASICOS-DA-PARTICIPACAO-EM-BANCA-DE-MESTRADO']['@attributes'];
                $detalhamento = $banca['DETALHAMENTO-DA-PARTICIPACAO-EM-BANCA-DE-MESTRADO']['@attributes'] ?? [];
                $bancasFormatadas[] = [
                    'TITULO' => $dadosBasicos['TITULO'] ?? 'N/A',
                    'ANO' => $dadosBasicos['ANO'] ?? 'N/A',
                    'CANDIDATO' => $detalhamento['NOME-DO-CANDIDATO'] ?? 'N/A',
                ];
            } elseif (is_string($banca)) {
                $bancasFormatadas[] = ['TITULO' => $banca, 'ANO' => 'N/A', 'CANDIDATO' => 'N/A'];
            }
        }
        return $bancasFormatadas;
    }

    /**
     * Manipulador específico para 'bancasDoutorado'.
     */
    private function processarBancasDoutorado(array $data): array
    {
        $bancasFormatadas = [];
        foreach ($data as $banca) {
            if (is_array($banca) && isset($banca['DADOS-BASICOS-DA-PARTICIPACAO-EM-BANCA-DE-DOUTORADO']['@attributes'])) {
                $dadosBasicos = $banca['DADOS-BASICOS-DA-PARTICIPACAO-EM-BANCA-DE-DOUTORADO']['@attributes'];
                $detalhamento = $banca['DETALHAMENTO-DA-PARTICIPACAO-EM-BANCA-DE-DOUTORADO']['@attributes'] ?? [];
                $bancasFormatadas[] = [
                    'TITULO' => $dadosBasicos['TITULO'] ?? 'N/A',
                    'ANO' => $dadosBasicos['ANO'] ?? 'N/A',
                    'CANDIDATO' => $detalhamento['NOME-DO-CANDIDATO'] ?? 'N/A',
                ];
            } elseif (is_string($banca)) {
                $bancasFormatadas[] = ['TITULO' => $banca, 'ANO' => 'N/A', 'CANDIDATO' => 'N/A'];
            }
        }
        return $bancasFormatadas;
    }
}
