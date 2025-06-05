<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Lattes;
use Maatwebsite\Excel\Excel;
use App\Exports\DadosExport;

class LattesController extends Controller
{
    public function index()
    {
        $docentes = Pessoa::listarDocentes();        
        foreach ($docentes as $docente) {
            $codpes = $docente['codpes'];
            $lattes = Lattes::obterArray($codpes);
            $docente['lattes'] = $lattes;
            $lattesProducao = array_values($lattes["PRODUCAO-BIBLIOGRAFICA"]);
            dd($lattesProducao);
        }
        return view('lattes', compact('docentes'));
    }


    public function baixarExcel(Excel $excel, Request $request, $codpes, $secao)
    {
        $lattes = Lattes::obterArray($codpes);
        $secoes = $this->extrairSecoesPrincipais($lattes);

        if (!isset($secoes[$secao])) {
            abort(404, 'Seção inválida');
        }

        $dadosBrutos = $secoes[$secao];
        $linhas = $this->normalizarArray($dadosBrutos);
        $cabecalhos = $this->gerarCabecalhos($linhas);

        $export = new DadosExport($linhas, $cabecalhos);
        return $excel->download($export, "{$secao}_{$codpes}.xlsx");
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

    private function normalizarArray($dados): array
    {
        $linhas = [];

        // Se for um array associativo direto (ex: ATTRIBUTES)
        if ($this->isAssociative($dados)) {
            $linha = [];
            array_walk_recursive($dados, function ($value, $key) use (&$linha) {
                $linha[$key] = $value;
            });
            $linhas[] = $linha;
            return $linhas;
        }

        // Se for uma lista de elementos
        foreach ($dados as $item) {
            if (is_array($item)) {
                $linha = [];
                array_walk_recursive($item, function ($value, $key) use (&$linha) {
                    if (!isset($linha[$key])) {
                        $linha[$key] = $value;
                    } else {
                        // Evita sobreposição de chave
                        $linha[$key . '_2'] = $value;
                    }
                });
                $linhas[] = $linha;
            } else {
                $linhas[] = [$item];
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
