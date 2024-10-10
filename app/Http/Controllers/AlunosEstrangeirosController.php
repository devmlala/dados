<?php

namespace App\Http\Controllers;

use App\Console\Commands\ReplicadoDailySyncCommand;
use App\Utils\ReplicadoTemp;
use Illuminate\Http\Request;
use Uspdev\Replicado\DB;


class AlunosEstrangeirosController extends Controller
{
    //mostrar alunos estrangeiros
    public function show(Request $request){
        //de acordo com ano
        $ano = $request->ano ?? Date(format:'Y');

        $alunosEstrangeiro = ReplicadoTemp::listarAlunoEstrangeiro($ano);


        return view('alunosEstrangeiros', ['alunosEstrangeiro' => $alunosEstrangeiro]);

    }
}
