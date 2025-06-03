@extends('laravel-usp-theme::master')

@section('content')
<div class="container">
    <h4>Currículos Lattes dos Docentes</h4>

    <table class="table table-bordered table-striped table-sm">
        <thead class="thead-light">
            <tr>
                <th>Nome</th>
                <th>Download por Seção</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($docentes as $docente)
                <tr>
                    <td>{{ $docente['nompes'] }} ({{ $docente['codpes'] }})</td>
                    <td>
                        @foreach ([
                            'atributos' => 'Atributos',
                            'resumo_cv' => 'Resumo',
                            'outras_informacoes' => 'Outras Informações',
                            'producao_bibliografica' => 'Bibliografia',
                            'atuacao_profissional' => 'Atuação Profissional',
                            'formacao' => 'Formação Acadêmica',
                            'atuacoes_projetos' => 'Projetos de Pesquisa',
                            'bancas' => 'Participação em Bancas',
                            'idiomas' => 'Idiomas',
                        ] as $secao => $label)
                            <a href="{{ route('lattes.download', [$docente['codpes'], $secao]) }}"
                               class="btn btn-sm btn-outline-primary mb-1">
                                {{ $label }}
                            </a>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Paginação --}}
    <div class="d-flex justify-content-center">
        {{ $docentes->links() }}
    </div>
</div>
@endsection
