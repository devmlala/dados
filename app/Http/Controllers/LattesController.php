<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Replicado\Lattes;
use Uspdev\Replicado\Docente;
use DB;

class LattesController extends Controller
{
    // Lista os currículos dos docentes da unidade configurada
    public function listarLattesDocentes()
    {
        $query = "
            SELECT DISTINCT p.nompesttd, v.codpes, v.dtainivin, v.dtafimvin
FROM VINCULOPESSOAUSP v
JOIN TIPOVINCULO t ON v.tipvin = t.tipvin
JOIN PESSOA p ON v.codpes = p.codpes
WHERE t.tipvin IN ('ALUNOICD', 'ALUNOCONVENIOINT')
AND codfusclgund = 8
ORDER BY dtainivin
        ";

        $resultados = DB::select($query);
        dd($resultados);
    }

    // Lista o resumo dos currículos de docentes especifícados
    public function listarResumoCurriculos()
    {
        $docentes = [5385361, 3544058, 3105829];
        $resumo = [];

        foreach ($docentes as $codpes) {
            $dados = Lattes::obterArray($codpes);
            $resumo[] = [
                'codpes' => $codpes,
                'nome' => $dados['nome'] ?? 'Não disponível',
                'data_atualizacao' => $dados['data_atualizacao'] ?? 'Não disponível',
                'url' => $dados['url'] ?? '',
            ];
        }

        return view('lattes.resumo', ['resumos' => $resumo]);
    }
}