@extends('laravel-usp-theme::master')

@section('content')
    <div class="container">
        <h4 class="mb-4">Docentes e seus Artigos</h4>

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
                    <th>Artigos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($docentes as $docente)
                    @php
                        $codpes = $docente['codpes'];
                        $artigosDocente = $artigos[$codpes] ?? [];
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $docente['nompes'] }}</strong><br>
                            <small>{{ $codpes }}</small>
                        </td>
                        <td>
                            @if (!empty($artigosDocente))
                                <h5 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-file-alt me-1"></i>
                                    Total de Artigos: {{ count($artigos) }}
                                </h5>
                                <ul>
                                    @foreach ($artigosDocente as $artigo)
                                        <div class="card mb-2 p-2 shadow-sm border-start border-3 border-primary">
                                            <h6 class="mb-1">
                                                <i class="fas fa-file-alt text-primary me-1"></i>
                                                {{ $artigo['TITULO-DO-ARTIGO'] ?? 'Sem título' }}
                                            </h6>
                                            <div class="small text-muted">
                                                <strong>Ano:</strong> {{ $artigo['ANO'] ?? 'Sem ano' }} |
                                                <strong>Periódico/Revista:</strong>
                                                {{ $artigo['TITULO-DO-PERIODICO-OU-REVISTA'] ?? 'Periódico desconhecido' }}
                                            </div>
                                        </div>

                                    @endforeach
                                </ul>
                            @else
                                <em>Nenhum artigo encontrado.</em>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection