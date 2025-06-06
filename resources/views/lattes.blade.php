@extends('laravel-usp-theme::master')

@section('content')
<div class="container">
    <h4 class="mb-4">Currículos Lattes dos Docentes</h4>

    <div class="alert alert-info">
        <strong>Debug:</strong><br>
        Número de docentes exibidos: {{ $limit }}<br>
        Tempo de execução: {{ $tempoExecucao }} segundos<br>

        <form method="get" class="mt-2 d-inline-block">
            <label for="limit">Alterar quantidade:</label>
            <input type="number" name="limit" value="{{ $limit }}" min="1" max="100" class="form-control d-inline w-auto">
            <button class="btn btn-sm btn-primary">Aplicar</button>
        </form>
    </div>

    <table class="table table-bordered table-hover table-sm align-middle">
        <thead class="thead-light">
            <tr>
                <th class="w-25">Nome</th>
                <th>Download por Seção/Subseção</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($docentes as $docente)
                <tr>
                    <td>
                        <strong>{{ $docente['nompes'] ?? 'Nome não disponível' }}</strong><br>
                        <small class="text-muted">({{ $docente['codpes'] }})</small>
                    </td>
                    <td>
                        @if (!empty($docente['subsecoes']))
                            @foreach ($docente['subsecoes'] as $secao => $subsecoes)
                                <div class="mb-2">
                                    <strong class="text-uppercase">{{ str_replace('_', ' ', $secao) }}</strong><br>
                                    @foreach ($subsecoes as $subsecao)
                                        <a href="{{ route('lattes.download', [$docente['codpes'], $secao, $subsecao]) }}"
                                           class="btn btn-sm btn-outline-primary mt-1 me-1"
                                           title="Exportar {{ $subsecao }} para Excel">
                                            {{ ucwords(str_replace(['-', '_'], ' ', strtolower($subsecao))) }}
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach
                        @else
                            <span class="text-muted">Nenhuma subseção disponível</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center text-muted">Nenhum docente encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4 alert alert-warning">
        <strong>Aviso:</strong><br>
        Algumas seções como <code>ATRIBUTOS</code> ou <code>RESUMO-CV</code> podem gerar arquivos com apenas uma linha ou dados genéricos. Isso ocorre porque o formato dessas seções é diferente das seções com listas, como <code>PRODUCAO-BIBLIOGRAFICA</code>.<br>
        Se necessário, os dados brutos dessas subseções serão exportados como texto JSON.
    </div>
</div>
@endsection
