@extends('laravel-usp-theme::master')

@section('content')
    <div class="container">
        <h4 class="mb-4">Docentes e seus Livros Publicados</h4>

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
                    <th>Livros Publicados</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($docentes as $docente)
                    @php
                        $codpes = $docente['codpes'];
                        $livrosDocente = $livrosPublicados[$codpes] ?? [];
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $docente['nompes'] }}</strong><br>
                            <small>{{ $codpes }}</small>
                        </td>
                        <td>
                            @if (!empty($livrosDocente))
                                <h5 class="text-success fw-bold mb-3">
                                    <i class="fas fa-layer-group me-1"></i>
                                    Total de Livros: {{ count($livrosDocente) }}
                                </h5>
                                <ul>
                                    @foreach ($livrosDocente as $livro)
                                        <div class="card mb-2 p-2 shadow-sm border-start border-3 border-success">
                                            <h6 class="mb-1">
                                                <i class="fas fa-book text-success me-1"></i>
                                                @php
                                                    echo is_array($livro['TITULO-DO-LIVRO'] ?? null)
                                                        ? implode_recursive(', ', $livro['TITULO-DO-LIVRO'])
                                                        : ($livro['TITULO-DO-LIVRO'] ?? 'Sem título');
                                                @endphp
                                            </h6>
                                            <div class="small text-muted">
                                                <strong>Ano:</strong>
                                                @php
                                                    echo is_array($livro['ANO'] ?? null)
                                                        ? implode_recursive(', ', $livro['ANO'])
                                                        : ($livro['ANO'] ?? 'Sem ano');
                                                @endphp
                                                |
                                                <strong>Editora:</strong>
                                                @php
                                                    echo is_array($livro['NOME-DA-EDITORA'] ?? null)
                                                        ? implode_recursive(', ', $livro['NOME-DA-EDITORA'])
                                                        : ($livro['NOME-DA-EDITORA'] ?? 'Editora desconhecida');
                                                @endphp
                                                |
                                                <strong>Autores:</strong>
                                                @php
                                                    echo is_array($livro['AUTORES'] ?? null)
                                                        ? implode_recursive(', ', $livro['AUTORES'])
                                                        : ($livro['AUTORES'] ?? 'Autores não informados');
                                                @endphp
                                            </div>
                                        </div>
                                    @endforeach
                                </ul>
                            @else
                                <em>Nenhum livro publicado encontrado.</em>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@php
    // Função helper para lidar com arrays multidimensionais
    function implode_recursive($glue, $array)
    {
        $result = '';
        foreach ($array as $item) {
            if (is_array($item)) {
                $result .= implode_recursive($glue, $item) . $glue;
            } else {
                $result .= $item . $glue;
            }
        }
        return rtrim($result, $glue);
    }
@endphp