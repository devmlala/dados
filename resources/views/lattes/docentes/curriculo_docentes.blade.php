@extends('laravel-usp-theme::master')

@section('content')
<div class="container">
    <h4 class="mb-4">Currículo dos Docentes</h4>

    <form method="get" class="mb-3">
        <label for="limit">Quantidade de docentes:</label>
        <input type="number" name="limit" value="{{ $limit }}" min="1" max="50"
            class="form-control d-inline-block w-auto ms-2 me-2">
        <button class="btn btn-primary btn-sm">Aplicar</button>
    </form>

    @foreach ($docentes as $docente)
        @php
            $codpes = $docente['codpes'];
            $resumo = $curriculo[$codpes] ?? [];
            
            // Verificação adicional para garantir que os dados são arrays
            $artigos = is_array($resumo['artigos'] ?? null) ? $resumo['artigos'] : [];
            $livros = is_array($resumo['livros_publicados'] ?? null) ? $resumo['livros_publicados'] : [];
        @endphp

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    {{ $docente['nompes'] }}
                    <small class="text-muted">({{ $codpes }})</small>
                </h5>
            </div>
            <div class="card-body">
                <!-- Seção de Artigos -->
                @if (!empty($artigos))
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-file-alt"></i> Artigos Publicados ({{ count($artigos) }})
                    </h6>
                    <div class="pl-3">
                        @foreach ($artigos as $artigo)
                            <div class="mb-3 p-2 border-left border-primary">
                                <strong>{{ $artigo['TITULO-DO-ARTIGO'] ?? 'Título não disponível' }}</strong>
                                <div class="small text-muted">
                                    {{ $artigo['TITULO-DO-PERIODICO-OU-REVISTA'] ?? '' }}
                                    @isset($artigo['ANO'])
                                        ({{ $artigo['ANO'] }})
                                    @endisset
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Seção de Livros -->
                @if (!empty($livros))
                    <h6 class="text-success mt-4 mb-3">
                        <i class="fas fa-book"></i> Livros Publicados ({{ count($livros) }})
                    </h6>
                    <div class="pl-3">
                        @foreach ($livros as $livro)
                            <div class="mb-3 p-2 border-left border-success">
                                <strong>{{ $livro['TITULO-DO-LIVRO'] ?? 'Título não disponível' }}</strong>
                                <div class="small text-muted">
                                    @isset($livro['NOME-DA-EDITORA'])
                                        {{ $livro['NOME-DA-EDITORA'] }}
                                    @endisset
                                    @isset($livro['ANO'])
                                        ({{ $livro['ANO'] }})
                                    @endisset
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (empty($artigos) && empty($livros))
                    <div class="alert alert-warning">
                        Nenhuma informação curricular disponível.
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection