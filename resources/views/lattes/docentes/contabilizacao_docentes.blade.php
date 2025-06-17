@extends('laravel-usp-theme::master')

@section('content')
    <div class="container">
        <h4 class="mb-4">Contabilização de Docentes</h4>

        <form method="get" class="mb-3">
            <label for="limit">Quantidade:</label>
            <input type="number" name="limit" value="{{ $limit }}" min="1" max="5"
                class="form-control d-inline-block w-auto ms-2 me-2">
            <button class="btn btn-primary btn-sm">Aplicar</button>
        </form>

        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Docente</th>
                    <th>Artigos</th>
                    <th>Livros</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($docentes as $docente)
                    @php
                        $codpes = $docente['codpes'];
                        $artigosDocente = $artigos[$codpes] ?? [];
                        $livrosDocente = $livros[$codpes] ?? [];
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $docente['nompes'] }}</strong><br>
                            <small>{{ $codpes }}</small>
                        </td>
                        <td>
                            @if (!empty($artigosDocente))
                                <h5>TOTAL: {{ count($artigosDocente) }}</h5>
                            @else
                                <em>Nenhum artigo</em>
                            @endif
                        </td>
                        <td>
                            @if (!empty($livrosDocente))
                                <h5>TOTAL: {{ count($livrosDocente) }}</h5>
                            @else
                                <em>Nenhum livro</em>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection