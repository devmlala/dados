@extends('laravel-usp-theme::master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Lattes: Livros Publicados por Docentes</h4>

        <!-- Filtro -->
        <form method="get" class="form-inline">
            <div class="input-group mr-2">
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
                            <th class="text-center"><i class="fas fa-book text-success"></i> Livros Publicados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($docentes as $docente)
                            @php
                                $codpes = $docente['codpes'];
                                $todosLivros = $livrosPublicados[$codpes] ?? [];
                                $livrosLimitados = array_slice($todosLivros, 0, 5); // exibe só 5
                            @endphp
                            <tr class="border-bottom">
                                <td class="pl-4 align-middle">
                                    <strong>{{ $docente['nompes'] }}</strong>
                                </td>
                                <td>
                                    @if (!empty($todosLivros))
                                        <h6 class="fw-bold text-success mb-2">
                                            <i class="fas fa-book me-1"></i>
                                            Total de Livros: {{ count($todosLivros) }}
                                        </h6>

                                        <ul class="list-unstyled" id="livros-{{ $codpes }}">
                                            @foreach ($livrosLimitados as $livro)
                                                <li class="card mb-2 p-2 shadow-sm border-start border-3 border-success">
                                                    <h6 class="mb-1 text-success">
                                                        <i class="fas fa-book me-1"></i>
                                                        {{ implode_recursive(', ', $livro['TITULO-DO-LIVRO'] ?? 'Sem título') }}
                                                    </h6>
                                                    <div class="small text-muted">
                                                        <strong>Ano:</strong> {{ implode_recursive(', ', $livro['ANO'] ?? 'Sem ano') }} |
                                                        <strong>Editora:</strong> {{ implode_recursive(', ', $livro['NOME-DA-EDITORA'] ?? 'Desconhecida') }} |
                                                        <strong>Autores:</strong> {{ implode_recursive(', ', $livro['AUTORES'] ?? 'Não informados') }}
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>

                                        @if (count($todosLivros) > 5)
                                            <button class="btn btn-link btn-sm p-0"
                                                onclick="document.getElementById('livros-{{ $codpes }}').innerHTML = `{!! collect($todosLivros)->map(function($livro){ 
                                                    return '<li class=\'card mb-2 p-2 shadow-sm border-start border-3 border-success\'><h6 class=\'mb-1 text-success\'><i class=\'fas fa-book me-1\'></i> '.e(implode_recursive(', ', $livro['TITULO-DO-LIVRO'] ?? 'Sem título')).'</h6><div class=\'small text-muted\'><strong>Ano:</strong> '.e(implode_recursive(', ', $livro['ANO'] ?? 'Sem ano')).' | <strong>Editora:</strong> '.e(implode_recursive(', ', $livro['NOME-DA-EDITORA'] ?? 'Desconhecida')).' | <strong>Autores:</strong> '.e(implode_recursive(', ', $livro['AUTORES'] ?? 'Não informados')).'</div></li>'; 
                                                })->implode('') !!}`; this.remove();">
                                                Ver todos os livros
                                            </button>
                                        @endif
                                    @else
                                        <em class="text-muted">Nenhum livro encontrado.</em>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">
                                    <div class="alert alert-warning mb-0 text-center">
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
            return collect($value)->map(fn($item) => implode_recursive($glue, $item))->implode($glue);
        }
        return $value;
    }
@endphp
