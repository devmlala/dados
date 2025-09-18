@extends('laravel-usp-theme::master')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="flex-grow-1 me-3">
                <h4 class="fw-bold text-primary mb-2">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Lattes: Resumo Docentes Lattes Completo
                </h4>
                <p class="lead text-muted mb-0">
                    Este <span class="fw-semibold text-dark">dashboard</span> apresenta um panorama completo dos
                    docentes,
                    incluindo <span class="text-primary">produções acadêmicas</span>,
                    <span class="text-success">orientações</span> e
                    <span class="text-info">projetos de pesquisa</span>.
                    Use o filtro ao lado para buscar informações específicas por <span class="fw-semibold">nome do
                        docente</span>.
                </p>
            </div>
        </div>

        <form method="get" class="form-inline mb-3">
            <div class="input-group">
                <input type="text" name="busca" value="{{ request('busca') }}" class="form-control form-control-sm"
                    placeholder="Nome do docente">
                <button class="btn btn-primary btn-sm">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </div>
        </form>



        <div class="card-body">
                <form method="GET" action="">
                    @php
                        $departamentos = \App\Utils\Util::getDepartamentos();
                    @endphp
                    <div class="form-group">
                        <label for="departamento">Departamento:</label>
                        <select name="departamento" id="departamento" class="form-control" required>
                            <option value="" disabled selected>Selecione um departamento</option>
                            @foreach($departamentos as $sigla => $dados)
                                <option value="{{ $sigla }}">{{ $dados[1] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>




        <div class="card shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4">Docente</th>
                                <th class="text-center">Departamento</th>
                                <th class="text-center"><i class="fas fa-file-alt text-primary"></i> Artigos</th>
                                <th class="text-center"><i class="fas fa-book text-success"></i> Livros</th>
                                <th class="text-center"><i class="fas fa-book-open text-info"></i> Capítulos</th>
                                <th class="text-center"><i class="fas fa-project-diagram text-info"></i> Projetos</th>
                                <th class="text-center"><i class="fas fa-calendar-alt text-muted"></i> Atualização</th>
                                <th class="text-center"><i class="fas fa-file-export text-success"></i> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($docentes as $docente)
                                <tr class="border-bottom">
                                    <td class="pl-4">
                                        <strong>{{ $docente['docente']['nompes'] }}</strong>
                                        @if (!empty($docente['orcid']))
                                            <div class="small text-muted">
                                                <i class="fab fa-orcid text-success"></i> {{ $docente['orcid'] }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ implode(', ', $docente['departamentos']) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $docente['contagem']['artigos'] ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ $docente['contagem']['livros'] ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ $docente['contagem']['capitulos-livros'] ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ $docente['contagem']['projetos'] ?? 0 }}
                                    </td>
                                    <td class="text-muted text-center small">
                                        {{ !empty($docente['ultimaAtualizacao']) ? \Carbon\Carbon::createFromFormat('dmY', $docente['ultimaAtualizacao'])->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-success" data-toggle="modal"
                                            data-target="#modalResumo{{ $docente['docente']['codpes'] }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="alert alert-warning mb-0">Nenhum docente encontrado ou dados indisponíveis.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            {{ $docentes->links() }}
        </div>
    </div>
@endsection

@section('javascripts_bottom')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection