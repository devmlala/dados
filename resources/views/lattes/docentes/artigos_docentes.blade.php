@extends('laravel-usp-theme::master')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Lattes: Artigos Docentes</h4>

            <!-- Filtro -->
            <form method="get" class="form-inline">
                <div class="input-group mr-2">
                    <input type="text" name="busca" value="{{ request('busca') }}" class="form-control form-control-sm"
                        placeholder="Nome do docente">
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
                                <th class="text-center"><i class="fas fa-file-alt text-primary"></i> Artigos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($docentes as $docente)
                                @php
                                    $codpes = $docente['codpes'];
                                    $todosArtigos = $artigos[$codpes] ?? [];
                                    $artigosLimitados = array_slice($todosArtigos, 0, 5); // mostra só 5
                                @endphp
                                <tr class="border-bottom">
                                    <td class="pl-4">
                                        <strong>{{ $docente['nompes'] }}</strong>
                                    </td>
                                    <td>
                                        @if (!empty($todosArtigos))
                                            <h6 class="fw-bold text-primary mb-2">
                                                <i class="fas fa-file-alt me-1"></i>
                                                Total: {{ count($todosArtigos) }} artigos
                                            </h6>

                                            <ul class="list-unstyled" id="artigos-{{ $codpes }}">
                                                @foreach ($artigosLimitados as $artigo)
                                                    <li class="card mb-2 p-2 shadow-sm border-start border-3 border-primary">
                                                        <h6 class="mb-1">
                                                            <i class="fas fa-file-alt text-primary me-1"></i>
                                                            {{ $artigo['TITULO-DO-ARTIGO'] ?? 'Sem título' }}
                                                        </h6>
                                                        <div class="small text-muted">
                                                            <strong>Ano:</strong> {{ $artigo['ANO'] ?? 'Sem ano' }} |
                                                            <strong>Periódico:</strong>
                                                            {{ $artigo['TITULO-DO-PERIODICO-OU-REVISTA'] ?? 'Desconhecido' }}
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            @if (count($todosArtigos) > 5)
                                                <button class="btn btn-link btn-sm p-0" 
                                                    onclick="document.getElementById('artigos-{{ $codpes }}').innerHTML = `{!! collect($todosArtigos)->map(function($artigo){ 
                                                        return '<li class=\'card mb-2 p-2 shadow-sm border-start border-3 border-primary\'><h6 class=\'mb-1\'><i class=\'fas fa-file-alt text-primary me-1\'></i> '.e($artigo['TITULO-DO-ARTIGO'] ?? 'Sem título').'</h6><div class=\'small text-muted\'><strong>Ano:</strong> '.e($artigo['ANO'] ?? 'Sem ano').' | <strong>Periódico:</strong> '.e($artigo['TITULO-DO-PERIODICO-OU-REVISTA'] ?? 'Desconhecido').'</div></li>'; 
                                                    })->implode('') !!}`; this.remove();">
                                                    Ver todos os artigos
                                                </button>
                                            @endif
                                        @else
                                            <em class="text-muted">Nenhum artigo encontrado.</em>
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
