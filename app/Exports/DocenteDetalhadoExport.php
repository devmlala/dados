<?php

namespace App\Exports;

use App\Services\LattesMetricsService;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DocenteDetalhadoExport implements WithMultipleSheets
{
    protected $codpes;
    protected $dados;

    public function __construct($codpes)
    {
        $this->codpes = $codpes;
        $service = new LattesMetricsService();
        $this->dados = $service->getMetricasDetalhadas($this->codpes);
    }

    public function sheets(): array
    {
        // Mapeia as chaves de dados para nomes de planilhas legíveis
        $map = [
            'artigos' => 'Artigos',
            'livros' => 'Livros',
            'capitulosLivros' => 'Capítulos',
            'eventos' => 'Participação em Eventos',
            'organizacaoEventos' => 'Organização de Eventos',
            'premios' => 'Prêmios e Títulos',
            'linhasDePesquisa' => 'Linhas de Pesquisa',
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
        
        // Add specific project sheets
        if (!empty($this->dados['projetosPesquisa'])) {
            $sheets[] = new ArraySheetWithHeaderExport($this->processarProjetos($this->dados['projetosPesquisa']), 'Projetos de Pesquisa');
        }

        if (!empty($this->dados['projetosExtensao'])) {
            $sheets[] = new ArraySheetWithHeaderExport($this->processarProjetos($this->dados['projetosExtensao']), 'Projetos de Extensão');
        }

        // Combine Desenvolvimento, Ensino, and Outros into a single "Projetos de Desenvolvimento" sheet
        $combinedDesenvolvimentoProjects = array_merge(
            $this->dados['projetosDesenvolvimento'] ?? [],
            $this->dados['projetosEnsino'] ?? [],
            $this->dados['outrosProjetos'] ?? []
        );
        if (!empty($combinedDesenvolvimentoProjects)) {
            $sheets[] = new ArraySheetWithHeaderExport($this->processarProjetos($combinedDesenvolvimentoProjects), 'Projetos de Desenvolvimento');
        }

        foreach ($map as $key => $nome) {
            // Skip project keys as they are handled above
            if (!in_array($key, ['projetosPesquisa', 'projetosExtensao', 'projetosDesenvolvimento', 'projetosEnsino', 'outrosProjetos'])) {
                if (!empty($this->dados[$key]) && is_array($this->dados[$key])) {
                    $handlerMethod = 'processar' . ucfirst($key);
                    $processedData = method_exists($this, $handlerMethod)
                        ? $this->{$handlerMethod}($this->dados[$key])
                        : $this->processarDadosGenericos($this->dados[$key]);

                    $sheets[] = new ArraySheetWithHeaderExport($processedData, $nome);
                }
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
     * Manipulador específico para projetos (Pesquisa, Extensão, Desenvolvimento).
     * Assumes the structure of project data from Lattes.
     */
    private function processarProjetos(array $data): array
    {
        $projetosFormatados = [];
        foreach ($data as $projeto) {
            $equipe = '';
            if (isset($projeto['EQUIPE-DO-PROJETO']) && is_array($projeto['EQUIPE-DO-PROJETO'])) {
                $nomesEquipe = array_map(fn($membro) => $membro['NOME-COMPLETO'] ?? 'N/A', $projeto['EQUIPE-DO-PROJETO']);
                $equipe = implode('; ', $nomesEquipe);
            }

            $projetosFormatados[] = [
                'NOME-DO-PROJETO' => $projeto['NOME-DO-PROJETO'] ?? 'N/A',
                'ANO-INICIO' => $projeto['ANO-INICIO'] ?? 'N/A',
                'ANO-FIM' => $projeto['ANO-FIM'] ?? 'N/A',
                'SITUACAO' => $projeto['SITUACAO'] ?? 'N/A',
                'NATUREZA' => $projeto['NATUREZA'] ?? 'N/A',
                'DESCRICAO-DO-PROJETO' => $projeto['DESCRICAO-DO-PROJETO'] ?? 'N/A',
                'EQUIPE-DO-PROJETO' => $equipe,
            ];
        }
        return $projetosFormatados;
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
