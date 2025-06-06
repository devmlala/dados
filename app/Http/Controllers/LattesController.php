<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Lattes;
use Maatwebsite\Excel\Excel;
use App\Exports\DadosExport;

class LattesController extends Controller
{
    /**
     * Exibe a página inicial com os docentes e suas subseções do Lattes.
     */
    public function index(Request $request)
    {
        $inicio = microtime(true);

        // Número limite de docentes a serem processados
        $limit = intval($request->query('limit', 3));

        // Recupera todos os docentes e aplica o limite
        $todosDocentes = Pessoa::listarDocentes();
        $docentesSelecionados = array_slice($todosDocentes, 0, $limit);

        $docentes = [];

        foreach ($docentesSelecionados as $docente) {
            $codpes = $docente['codpes'];
            $lattes = Lattes::obterArray($codpes);
            $docente['subsecoes'] = [];

            if ($lattes) {
                foreach ($this->extrairSecoesPrincipais($lattes) as $secao => $conteudo) {
                    foreach ($this->extrairSubsecoes($conteudo) as $subsecao => $dados) {
                        $docente['subsecoes'][$secao][] = $subsecao;
                    }
                }
            }

            
            $docentes[] = $docente;
        }

        $tempoExecucao = round(microtime(true) - $inicio, 2);

        return view('lattes', compact('docentes', 'tempoExecucao', 'limit'));
    }

    /**
     * Gera o download de uma subseção em formato Excel.
     */
    public function baixarExcel(Excel $excel, Request $request, $codpes, $secao, $subsecao)
    {
        $lattes = Lattes::obterArray($codpes);
        $secoes = $this->extrairSecoesPrincipais($lattes);

        if (!isset($secoes[$secao])) {
            abort(404, 'Seção inválida');
        }

        $subsecoes = $this->extrairSubsecoes($secoes[$secao]);

        if (!isset($subsecoes[$subsecao])) {
            abort(404, 'Subseção inválida');
        }

        // Loga caso a subseção esteja vazia ou mal estruturada
        if (empty($subsecoes[$subsecao])) {
            \Log::warning("Subseção '{$subsecao}' da seção '{$secao}' para o docente '{$codpes}' está vazia ou mal formatada.");
        }

        $linhas = $this->normalizarArray($subsecoes[$subsecao]);
        $cabecalhos = $this->gerarCabecalhos($linhas);

        $export = new DadosExport($linhas, $cabecalhos);
        return $excel->download($export, "{$secao}_{$subsecao}_{$codpes}.xlsx");
    }

    /**
     * Extrai as seções principais do currículo Lattes.
     */
    private function extrairSecoesPrincipais(array $lattes): array
    {
        $dadosGerais = $lattes['DADOS-GERAIS'] ?? [];

        return [
            'atributos' => $lattes['@attributes'] ?? [],
            'resumo_cv' => $dadosGerais['RESUMO-CV']['@attributes'] ?? [],
            'outras_informacoes' => $dadosGerais['OUTRAS-INFORMACOES-RELEVANTES']['@attributes'] ?? [],
            'formacao' => $dadosGerais['FORMACAO-ACADEMICA-TITULACAO'] ?? [],
            'atuacao_profissional' => $dadosGerais['ATUACOES-PROFISSIONAIS'] ?? [],
            'idiomas' => $dadosGerais['IDIOMAS']['IDIOMA'] ?? [],
            'premios' => $dadosGerais['PREMIOS-TITULOS'] ?? [],
            'areas_atuacao' => $dadosGerais['AREAS-DE-ATUACAO'] ?? [],
            'producao_bibliografica' => $lattes['PRODUCAO-BIBLIOGRAFICA'] ?? [],
            'producao_tecnica' => $lattes['PRODUCAO-TECNICA'] ?? [],
            'outra_producao' => $lattes['OUTRA-PRODUCAO'] ?? [],
            'dados_complementares' => $lattes['DADOS-COMPLEMENTARES'] ?? [],
        ];
    }


    /**
     * Extrai as subseções internas de uma seção.
     */
    private function extrairSubsecoes($conteudo): array
    {
        $subsecoes = [];

        foreach ($conteudo as $item) {
            if (is_array($item)) {
                foreach ($item as $subsecao => $dados) {
                    $subsecoes[$subsecao] = array_merge($subsecoes[$subsecao] ?? [], $dados);
                }
            }
        }

        return $subsecoes;
    }

    /**
     * Normaliza um array aninhado para um array de linhas simples.
     */
    private function normalizarArray($dados): array
    {
        $linhas = [];

        if ($this->isAssociative($dados)) {
            $linha = [];
            array_walk_recursive($dados, function ($value, $key) use (&$linha) {
                $linha[$key] = $value;
            });
            $linhas[] = $linha;
        } else {
            foreach ($dados as $item) {
                $linha = [];
                array_walk_recursive($item, function ($value, $key) use (&$linha) {
                    $linha[$key] = $value;
                });
                $linhas[] = $linha;
            }
        }

        return $linhas;
    }

    /**
     * Verifica se um array é associativo.
     */
    private function isAssociative(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Gera os cabeçalhos a partir da primeira linha normalizada.
     */
    private function gerarCabecalhos(array $linhas): array
    {
        return isset($linhas[0]) ? array_keys($linhas[0]) : ['Dado'];
    }
}
