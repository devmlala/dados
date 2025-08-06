<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LattesMetricsService;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\Lattes;

use App\Exports\DocenteExport;
use App\Exports\DocenteDetalhadoExport;
use App\Exports\ArraySheetExport;
use Maatwebsite\Excel\Facades\Excel;

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
        $limit = 5;
        $busca = $request->input('busca'); // captura o texto da busca
        $page = $request->input('page', 1);

        // Lista todos os docentes
        $todosDocentes = Pessoa::listarDocentes();

        // Filtra por nome, se houver busca
        if (!empty($busca)) {
            $todosDocentes = array_filter($todosDocentes, function ($docente) use ($busca) {
                return stripos($docente['nompes'], $busca) !== false;
            });
            $todosDocentes = array_values($todosDocentes); // reindexa o array
        }

        // Pagina os resultados
        $offset = ($page - 1) * $limit;
        $docentesPagina = array_slice($todosDocentes, $offset, $limit);

        // Obtém as métricas dos docentes da página atual
        $docentesComMetricas = $this->metricsService->getDocentesComMetricasParaLista($docentesPagina);

        // Cria um paginator manual
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $docentesComMetricas,
            count($todosDocentes),
            $limit,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return view('lattes.docentes.dashboard', [
            'docentes' => $paginator,
            'limit' => $limit,
            'busca' => $busca,
        ]);
    }



    //exports
    public function exportarDocente($codpes)
    {
        return Excel::download(new DocenteExport($codpes), "docente_$codpes.xlsx");
    }
    public function exportarDetalhado($codpes)
    {
        return Excel::download(new DocenteDetalhadoExport($codpes), "docente_{$codpes}_detalhado.xlsx");
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
                // Pass -1 as the limit to remove internal article limit
                $artigos[$codpes] = Lattes::listarArtigos($codpes, $lattesArray, 'registros', -1);
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
                $livrosPublicados[$codpes] = Lattes::listarLivrosPublicados($codpes, $lattesArray, 'registros', -1);
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
