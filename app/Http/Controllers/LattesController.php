<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Lattes;
use Maatwebsite\Excel\Excel;
use App\Exports\DadosExport;

class LattesController extends Controller
{
    public function index(Request $request)
    {
        $inicio = microtime(true);

        $limit = intval($request->query('limit', 5));
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

    public function baixarExcel(Excel $excel, Request $request, $codpes, $secao, $subsecao)
    {
        $lattes = Lattes::obterArray($codpes);
        $secoes = $this->extrairSecoesPrincipais($lattes);
        if (!isset($secoes[$secao])) abort(404, 'Seção inválida');

        $subsecoes = $this->extrairSubsecoes($secoes[$secao]);
        if (!isset($subsecoes[$subsecao])) abort(404, 'Subseção inválida');

        $linhas = $this->normalizarArray($subsecoes[$subsecao]);
        $cabecalhos = $this->gerarCabecalhos($linhas);

        $export = new DadosExport($linhas, $cabecalhos);
        return $excel->download($export, "{$secao}_{$subsecao}_{$codpes}.xlsx");
    }

    private function extrairSecoesPrincipais(array $lattes): array
    {
        return [
            'atributos' => $lattes['ATTRIBUTES'] ?? [],
            'resumo_cv' => $lattes['RESUMO-CV'] ?? [],
            'outras_informacoes' => $lattes['OUTRAS-INFORMACOES-RELEVANTES'] ?? [],
            'producao_bibliografica' => $lattes['PRODUCAO-BIBLIOGRAFICA'] ?? [],
            'atuacao_profissional' => $lattes['ATUACAO-PROFISSIONAL'] ?? [],
            'formacao' => $lattes['FORMACAO-ACADEMICA-TITULACAO'] ?? [],
            'atuacoes_projetos' => $lattes['PROJETOS-DE-PESQUISA'] ?? [],
            'bancas' => $lattes['PARTICIPACAO-EM-BANCAS'] ?? [],
            'idiomas' => $lattes['IDIOMAS'] ?? [],
        ];
    }

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

    private function isAssociative(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    private function gerarCabecalhos(array $linhas): array
    {
        return isset($linhas[0]) ? array_keys($linhas[0]) : ['Dado'];
    }
}
