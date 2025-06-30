@extends('laravel-usp-theme::master')

@section('content')
    <div class="container">
        <h4 class="mb-4">Lattes: Docentes e seus Projetos de Pesquisa</h4>

        <form method="get" class="mb-3">
            <label for="limit">Quantidade:</label>
            <input type="number" name="limit" value="{{ $limit }}" min="1" max="50"
                class="form-control d-inline-block w-auto ms-2 me-2">
            <button class="btn btn-primary btn-sm">Aplicar</button>
        </form>

        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Docente</th>
                    <th>Projetos de Pesquisa</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($docentes as $docente)
                    @php
                        $codpes = $docente['codpes'];
                        $projetos = $projetosPesquisa[$codpes] ?? [];
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $docente['nompes'] }}</strong><br>
                            <small>{{ $codpes }}</small>
                        </td>
                        <td>
                            @if (!empty($projetos))
                                <h5 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-flask me-1"></i>
                                    Total de Projetos: {{ count($projetos) }}
                                </h5>
                                <ul class="list-unstyled">
                                @foreach ($projetos as $projeto)
    <div class="card mb-2 p-2 shadow-sm border-start border-3 border-primary">
        <h6 class="mb-1 text-primary">
            <i class="fas fa-flask me-1"></i>
            {{ $projeto['NOME-DO-PROJETO'] ?? 'Sem título' }}
        </h6>
        <div class="small text-muted">
            <strong>Ano de Início:</strong> {{ $projeto['ANO-INICIO'] ?? 'Desconhecido' }} |
            <strong>Situação:</strong> {{ str_replace('_', ' ', ucfirst(strtolower($projeto['SITUACAO'] ?? 'Desconhecida'))) }} |
            <strong>Natureza:</strong> {{ $projeto['NATUREZA'] ?? 'Não informada' }}
        </div>
        @if (!empty($projeto['DESCRICAO-DO-PROJETO']))
            <p class="small mt-1">
                {{ Str::limit(strip_tags($projeto['DESCRICAO-DO-PROJETO']), 200) }}
            </p>
        @endif
    </div>
@endforeach

                                </ul>
                            @else
                                <em>Nenhum projeto de pesquisa encontrado.</em>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
