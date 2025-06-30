<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LattesMetricsService;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Lattes;

class LattesController extends Controller
{
    protected $metricsService;

    public function __construct(LattesMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    public function index()
    {
        return view('lattes.index');
    }

    public function dashboard(Request $request)
    {
        $limit = $request->input('limit', 10);
        $docentesComMetricas = $this->metricsService->getDocentesComMetricas($limit);


        return view('lattes.docentes.dashboard', [
            'docentes' => $docentesComMetricas,
            'limit' => $limit
        ]);
    }

    public function apiMetricas(Request $request)
    {
        $limit = $request->input('limit', 10);
        return response()->json($this->metricsService->getDocentesComMetricas($limit));
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

    public function livrosPublicados(Request $request)
    {
        $limit = $request->input('limit', 3);

        $todosDocentes = Pessoa::listarDocentes();
        $docentes = array_slice($todosDocentes, 0, $limit);

        $livrosPublicados = [];

        foreach ($docentes as $docente) {
            $codpes = $docente['codpes'];
            $lattesArray = Lattes::obterArray($codpes);

            if ($lattesArray) {
                $livrosPublicados[$codpes] = Lattes::listarLivrosPublicados($codpes, $lattesArray);
            } else {
                $livrosPublicados[$codpes] = [];
            }
        }

        return view('lattes.docentes.livros_publicados_docentes', compact('docentes', 'livrosPublicados', 'limit'));
    }

    public function projetosPesquisa(Request $request)
    {
        $limit = $request->input('limit', 3);

        $todosDocentes = Pessoa::listarDocentes();
        $docentes = array_slice($todosDocentes, 0, $limit);

        $projetosPesquisa = [];

        foreach ($docentes as $docente) {
            $codpes = $docente['codpes'];
            $lattesArray = Lattes::obterArray($codpes);

            if ($lattesArray) {
                $projetosPesquisa[$codpes] = Lattes::listarProjetosPesquisa($codpes, $lattesArray);
            } else {
                $projetosPesquisa[$codpes] = [];
            }
        }

        //dd($projetosPesquisa);

        return view('lattes.docentes.projetos_pesquisa_docentes', compact('docentes', 'projetosPesquisa', 'limit'));
    }

    public function curriculo(Request $request)
    {
        $limit = $request->input('limit', 3);

        $todosDocentes = Pessoa::listarDocentes();
        $docentes = array_slice($todosDocentes, 0, $limit);

        $curriculo = [];

        foreach ($docentes as $docente) {
            $codpes = $docente['codpes'];
            $lattesArray = Lattes::obterArray($codpes);

            if ($lattesArray) {
                try {
                    $curriculo[$codpes] = Lattes::retornarResumoCV($codpes, $lattesArray);
                } catch (\TypeError $e) {
                    // Log do erro e continua com array vazio
                    \Log::error("Erro ao processar currículo do docente {$codpes}: " . $e->getMessage());
                    $curriculo[$codpes] = [];
                }
            } else {
                $curriculo[$codpes] = [];
            }
        }

        //dd($curriculo)[$codpes];

        return view('lattes.docentes.curriculo_docentes', [
            'docentes' => $docentes,
            'curriculo' => $curriculo,
            'limit' => $limit
        ]);
    }


}
