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
        $busca = $request->input('busca'); // Captures the search text
        $page = $request->input('page', 1);

        // List all docentes
        $todosDocentes = Pessoa::listarDocentes();

        // Filter by name, if a search query exists
        if (!empty($busca)) {
            $todosDocentes = array_filter($todosDocentes, function ($docente) use ($busca) {
                return stripos($docente['nompes'], $busca) !== false;
            });
            $todosDocentes = array_values($todosDocentes); // Reindex the array
        }

        // Paginate the results
        $offset = ($page - 1) * $limit;
        $docentesPagina = array_slice($todosDocentes, $offset, $limit);

        // Fetch metrics and department for each docente
        $docentesComMetricas = $this->metricsService->getDocentesComMetricasParaLista($docentesPagina);

        foreach ($docentesComMetricas as &$docente) {
            $codpes = $docente['docente']['codpes'];
            $departamentos = \App\Utils\ReplicadoTemp::obterVinculo($codpes);

            // Ensure $departamentos is an array
            $docente['departamentos'] = is_array($departamentos) ? $departamentos : [$departamentos];
        }

        // Create a manual paginator
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
        $limit = 5; // docentes por página
        $busca = $request->input('busca');
        $page = $request->input('page', 1);

        // Lista todos os docentes
        $todosDocentes = Pessoa::listarDocentes();

        // Filtra por nome, se houver busca
        if (!empty($busca)) {
            $todosDocentes = array_filter($todosDocentes, function ($docente) use ($busca) {
                return stripos($docente['nompes'], $busca) !== false;
            });
            $todosDocentes = array_values($todosDocentes); // reindexa
        }

        // Pagina docentes
        $offset = ($page - 1) * $limit;
        $docentesPagina = array_slice($todosDocentes, $offset, $limit);

        // Busca artigos de cada docente da página atual
        $artigos = [];
        foreach ($docentesPagina as $docente) {
            $codpes = $docente['codpes'];
            $lattesArray = Lattes::obterArray($codpes);

            if ($lattesArray) {
                $artigos[$codpes] = Lattes::listarArtigos($codpes, $lattesArray, 'registros', -1);
            } else {
                $artigos[$codpes] = [];
            }
        }

        // Cria paginator
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $docentesPagina,
            count($todosDocentes),
            $limit,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return view('lattes.docentes.artigos_docentes', [
            'docentes' => $paginator,
            'artigos' => $artigos,
            'busca' => $busca,
            'limit' => $limit,
        ]);
    }

    public function livrosPublicados(Request $request)
    {
        $limit = $request->input('limit', 5); // docentes por página
        $busca = $request->input('busca');
        $page = $request->input('page', 1);

        // Lista todos os docentes
        $todosDocentes = Pessoa::listarDocentes();

        // Filtra por nome, se houver busca
        if (!empty($busca)) {
            $todosDocentes = array_filter($todosDocentes, function ($docente) use ($busca) {
                return stripos($docente['nompes'], $busca) !== false;
            });
            $todosDocentes = array_values($todosDocentes); // reindexa
        }

        // Pagina docentes
        $offset = ($page - 1) * $limit;
        $docentesPagina = array_slice($todosDocentes, $offset, $limit);

        // Busca livros para os docentes da página atual
        $livrosPublicados = [];
        foreach ($docentesPagina as $docente) {
            $codpes = $docente['codpes'];
            $lattesArray = Lattes::obterArray($codpes);

            if ($lattesArray) {
                $livrosPublicados[$codpes] = Lattes::listarLivrosPublicados($codpes, $lattesArray, 'registros', -1);
            } else {
                $livrosPublicados[$codpes] = [];
            }
        }

        // Cria paginator
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $docentesPagina,
            count($todosDocentes),
            $limit,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return view('lattes.docentes.livros_publicados_docentes', [
            'docentes' => $paginator,
            'livrosPublicados' => $livrosPublicados,
            'busca' => $busca,
            'limit' => $limit,
        ]);
    }



    public function projetosPesquisa(Request $request)
    {
        $limit = $request->input('limit', 5); // docentes por página (padronizado com livros)
        $busca = $request->input('busca');
        $page = $request->input('page', 1);

        // Lista todos os docentes
        $todosDocentes = Pessoa::listarDocentes();

        // Filtra por nome, se houver busca
        if (!empty($busca)) {
            $todosDocentes = array_filter($todosDocentes, function ($docente) use ($busca) {
                return stripos($docente['nompes'], $busca) !== false;
            });
            $todosDocentes = array_values($todosDocentes); // reindexa
        }

        // Pagina docentes
        $offset = ($page - 1) * $limit;
        $docentesPagina = array_slice($todosDocentes, $offset, $limit);

        // Busca projetos para os docentes da página atual
        $projetosPesquisa = [];
        foreach ($docentesPagina as $docente) {
            $codpes = $docente['codpes'];
            $lattesArray = Lattes::obterArray($codpes);

            if ($lattesArray) {
                try {
                    $lista = Lattes::listarProjetosPesquisa($codpes, $lattesArray);
                    $projetosPesquisa[$codpes] = is_array($lista) ? $lista : [];
                } catch (\Throwable $e) {
                    \Log::warning("Erro ao listar projetos para {$codpes}: " . $e->getMessage());
                    $projetosPesquisa[$codpes] = [];
                }
            } else {
                $projetosPesquisa[$codpes] = [];
            }
        }

        // Cria paginator
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $docentesPagina,
            count($todosDocentes),
            $limit,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return view('lattes.docentes.projetos_pesquisa_docentes', [
            'docentes' => $paginator,
            'projetosPesquisa' => $projetosPesquisa,
            'busca' => $busca,
            'limit' => $limit,
        ]);
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
