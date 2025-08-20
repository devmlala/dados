@extends('laravel-usp-theme::master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Lattes: Projetos de Pesquisa por Docentes</h4>

        <!-- Filtro -->
        <form method="get" class="form-inline">
            <div class="input-group me-2">
                <input type="text" name="busca" value="{{ request('busca') }}" 
                       class="form-control form-control-sm" placeholder="Nome do docente">
            </div>
            <button class="btn btn-primary btn-sm">
                <i class="fas fa-filter"></i> Filtrar
            </button>
        </form>
    </div>

    <!-- Lista de docentes -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">Docente</th>
                            <th class="text-center"><i class="fas fa-flask text-primary"></i> Projetos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($docentes as $docente)
                            @php
                                $codpes = $docente['codpes'];
                                $todosProjetos = $projetosPesquisa[$codpes] ?? [];
                                $projetosLimitados = array_slice($todosProjetos, 0, 5);
                            @endphp
                            <tr class="border-bottom">
                                <td class="pl-4">
                                    <strong>{{ $docente['nompes'] }}</strong>
                                    <div class="small text-muted">{{ $codpes }}</div>
                                </td>
                                <td>
                                    @if(!empty($todosProjetos))
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-flask me-1"></i>
                                            Total: {{ count($todosProjetos) }} projetos
                                        </h6>

                                        <ul class="list-unstyled" id="projetos-{{ $codpes }}">
                                            @foreach ($projetosLimitados as $projeto)
                                                <li class="card mb-2 p-2 shadow-sm border-start border-3 border-primary">
                                                    <h6 class="mb-1 text-primary">
                                                        <i class="fas fa-flask me-1"></i>
                                                        {!! e(implode_recursive(', ', $projeto['NOME-DO-PROJETO'] ?? 'Sem título')) !!}
                                                    </h6>
                                                    <div class="small text-muted">
                                                        <strong>Ano de Início:</strong> {!! e(implode_recursive(', ', $projeto['ANO-INICIO'] ?? 'Desconhecido')) !!} |
                                                        <strong>Situação:</strong> {!! e(implode_recursive(', ', str_replace('_',' ', ucfirst(strtolower($projeto['SITUACAO'] ?? 'Desconhecida'))))) !!} |
                                                        <strong>Natureza:</strong> {!! e(implode_recursive(', ', $projeto['NATUREZA'] ?? 'Não informada')) !!}
                                                    </div>
                                                    @if(!empty($projeto['DESCRICAO-DO-PROJETO']))
                                                        <p class="small mt-1">
                                                            {!! e(Str::limit(strip_tags($projeto['DESCRICAO-DO-PROJETO']),200)) !!}
                                                        </p>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>

                                        @if(count($todosProjetos) > 5)
                                            @php
                                                $todosProjetosHtml = collect($todosProjetos)->map(function($projeto){
                                                    $titulo = implode_recursive(', ', $projeto['NOME-DO-PROJETO'] ?? 'Sem título');
                                                    $ano = implode_recursive(', ', $projeto['ANO-INICIO'] ?? 'Desconhecido');
                                                    $situacao = implode_recursive(', ', str_replace('_', ' ', ucfirst(strtolower($projeto['SITUACAO'] ?? 'Desconhecida'))));
                                                    $natureza = implode_recursive(', ', $projeto['NATUREZA'] ?? 'Não informada');
                                                    $descricao = !empty($projeto['DESCRICAO-DO-PROJETO']) ? '<p class="small mt-1">'.e(Str::limit(strip_tags($projeto['DESCRICAO-DO-PROJETO']),200)).'</p>' : '';
                                                    return "<li class='card mb-2 p-2 shadow-sm border-start border-3 border-primary'>
                                                                <h6 class='mb-1 text-primary'><i class='fas fa-flask me-1'></i> {$titulo}</h6>
                                                                <div class='small text-muted'>
                                                                    <strong>Ano de Início:</strong> {$ano} |
                                                                    <strong>Situação:</strong> {$situacao} |
                                                                    <strong>Natureza:</strong> {$natureza}
                                                                </div>
                                                                {$descricao}
                                                            </li>";
                                                })->implode('');
                                            @endphp

                                            <button class="btn btn-link btn-sm p-0"
                                                onclick="document.getElementById('projetos-{{ $codpes }}').innerHTML = {!! json_encode($todosProjetosHtml) !!}; this.remove();">
                                                Ver todos os projetos
                                            </button>
                                        @endif
                                    @else
                                        <em class="text-muted">Nenhum projeto encontrado.</em>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">
                                    <div class="alert alert-warning mb-0">
                                        Nenhum docente encontrado.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center">
        {{ $docentes->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@php
    /**
     * Helper para transformar arrays (inclusive multidimensionais) em string
     */
    function implode_recursive($glue, $value) {
        if (is_array($value)) {
            $result = '';
            foreach ($value as $item) {
                $result .= implode_recursive($glue, $item) . $glue;
            }
            return rtrim($result, $glue);
        }
        return $value;
    }
@endphp
