<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Lattes;

class LattesController extends Controller
{
    public function index()
    {
        return view('lattes.index'); // visão geral
    }

    public function artigos(Request $request)
    {
        $limit = $request->input('limit', 3);

        $todosDocentes = Pessoa::listarDocentes();
        $docentes = array_slice($todosDocentes, 0, $limit);

        $artigos = [];

        foreach ($docentes as $docente) {
            $codpes = $docente['codpes'];
            $lattesArray = Lattes::obterArray($codpes);

            if ($lattesArray) {
                $artigos[$codpes] = Lattes::listarArtigos($codpes, $lattesArray);
            } else {
                $artigos[$codpes] = [];
            }
        }


        return view('lattes.docentes.artigos_docentes', compact('docentes', 'artigos', 'limit'));
    }
}

