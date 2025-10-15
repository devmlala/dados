@extends('laravel-usp-theme::master')

@section('content')
    <div class="container">
        <div class="card shadow-sm mb-4 bg-light border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chalkboard-teacher fa-3x text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="fw-bold text-primary mb-1">Dashboard de Métricas Lattes</h4>
                        <p class="text-muted mb-0">
                            Explore um panorama completo da produção acadêmica dos docentes. Utilize os filtros abaixo para
                            refinar sua busca por <strong>nome</strong> ou <strong>departamento</strong>. Cada linha da
                            tabela oferece um resumo das principais métricas, como <span
                                class="text-primary">artigos</span>, <span class="text-success">livros</span>, <span
                                class="text-info">orientações</span> e <span class="text-warning">prêmios</span>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('lattes.dashboard') }}" class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="busca"><b>Busca por Nome:</b></label>
                            <input type="text" name="busca" id="busca" value="{{ $busca }}" class="form-control"
                                placeholder="Digite o nome do docente">
                        </div>
                    </div>
                    <div class="col-md-6">
                        @php $departamentos = \App\Utils\Util::getDepartamentos(); @endphp
                        <div class="form-group">
                            <label for="departamento"><b>Filtro por Departamento:</b></label>
                            <select name="departamento" id="departamento" class="form-control">
                                <option value="">-- Todos os Departamentos --</option>
                                @foreach($departamentos as $sigla => $dados)
                                    <option value="{{ $dados[1] }}" {{ ($departamento_filtro == $dados[1]) ? 'selected' : '' }}>
                                        {{ $dados[1] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-filter"></i> Aplicar Filtros</button>
                <a href="{{ route('lattes.dashboard') }}" class="btn btn-outline-secondary mt-2">Limpar Filtros</a>
            </div>
        </form>

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
                                <th class="text-center"><i class="fas fa-calendar-check text-warning"></i> Eventos</th>
                                <th class="text-center"><i class="fas fa-user-graduate text-primary"></i> IC</th>
                                <th class="text-center"><i class="fas fa-user-graduate text-success"></i> Mestrado</th>
                                <th class="text-center"><i class="fas fa-user-graduate text-info"></i> Doutorado</th>
                                <th class="text-center"><i class="fas fa-chalkboard-teacher text-secondary"></i> Pós-Doc
                                </th>
                                <th class="text-center"><i class="fas fa-trophy text-warning"></i> Prêmios</th>
                                <th class="text-center"><i class="fas fa-plus-circle text-muted"></i> Mais</th>
                                <th class="text-center"><i class="fas fa-calendar-alt text-muted"></i> Atualização</th>
                                <th class="text-center"><i class="fas fa-file-export text-success"></i> Ações</th>
                                <th class="text-center"><i class="fas fa-file-export text-success"></i> Exportar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($docentes as $index => $docente)
                                <tr class="border-bottom">
                                    <td class="pl-4">
                                        <strong>{{ $docente['docente']['nompes'] }}</strong>
                                        @if (!empty($docente['docente']['orcid']))
                                            <div class="small text-muted">
                                                <i class="fab fa-orcid text-success"></i> {{ $docente['docente']['orcid'] }}
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
                                    <td class="text-center">
                                        {{ $docente['contagem']['eventos'] ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ $docente['contagem']['orientacoes-concluidas-ic'] ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ $docente['contagem']['orientacoes-concluidas-mestrado'] ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ $docente['contagem']['orientacoes-concluidas-doutorado'] ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ $docente['contagem']['premios'] ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ $docente['contagem']['orientacoes-concluidas-pos-doc'] ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                id="dropdownMais{{$index}}" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Ver
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMais{{$index}}">
                                                <h6 class="dropdown-header">Outras Produções</h6>
                                                <a class="dropdown-item" href="#">Org. Eventos: <span
                                                        class="badge badge-primary float-right">{{ $docente['contagem']['organizacao-eventos'] ?? 0 }}</span></a>
                                                <a class="dropdown-item" href="#">Bancas Doutorado: <span
                                                        class="badge badge-info float-right">{{ $docente['contagem']['bancas-doutorado'] ?? 0 }}</span></a>
                                                <a class="dropdown-item" href="#">Bancas Mestrado: <span
                                                        class="badge badge-success float-right">{{ $docente['contagem']['bancas-mestrado'] ?? 0 }}</span></a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Trab. em Anais: <span
                                                        class="badge badge-secondary float-right">{{ $docente['contagem']['trabalhos-anais'] ?? 0 }}</span></a>
                                                <a class="dropdown-item" href="#">Trab. Técnicos: <span
                                                        class="badge badge-secondary float-right">{{ $docente['contagem']['trabalhos-tecnicos'] ?? 0 }}</span></a>
                                                <a class="dropdown-item" href="#">Apres. Trabalho: <span
                                                        class="badge badge-secondary float-right">{{ $docente['contagem']['apresentacao-de-trabalho'] ?? 0 }}</span></a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Textos Jornais: <span
                                                        class="badge badge-light float-right">{{ $docente['contagem']['textos-jornais-revistas'] ?? 0 }}</span></a>
                                                <a class="dropdown-item" href="#">Rel. Pesquisa: <span
                                                        class="badge badge-light float-right">{{ $docente['contagem']['relatorios-pesquisa'] ?? 0 }}</span></a>
                                                <a class="dropdown-item" href="#">Material Didático: <span
                                                        class="badge badge-light float-right">{{ $docente['contagem']['material-didatico'] ?? 0 }}</span></a>
                                            </div>
                                        </div>
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
                                    <td class="text-center">
                                        <a href="{{ route('lattes.exportar', $docente['docente']['codpes']) }}"
                                            class="btn btn-sm btn-outline-primary" title="Exportar Excel">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="15">
                                        <div class="alert alert-warning mb-0">Nenhum docente encontrado ou dados indisponíveis.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            {{ $docentes->appends(request()->query())->links() }}
        </div>

        <!-- Modal para resumo -->
        @foreach ($docentes as $docente)
            <div class="modal fade" id="modalResumo{{ $docente['docente']['codpes'] }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Resumo Completo - {{ $docente['docente']['nompes'] }}</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Estatísticas</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled">
                                                <li><strong>Artigos:</strong> {{ count($docente['artigos'] ?? []) }}</li>
                                                <li><strong>Livros:</strong> {{ count($docente['livros'] ?? []) }}</li>
                                                <li><strong>Capítulos:</strong> {{ count($docente['capitulosLivros'] ?? []) }}
                                                </li>
                                                <li><strong>Doutorado:</strong>
                                                    {{ count($docente['orientacoesConcluidasDoc'] ?? []) }}</li>
                                                <li><strong>Eventos:</strong>
                                                    {{ count($docente['eventos'] ?? []) }}</li>
                                                <li><strong>Mestrado:</strong>
                                                    {{ count($docente['orientacoesMestrado'] ?? []) }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="fas fa-trophy"></i> Prêmios</h6>
                                        </div>
                                        <div class="card-body">
                                            @if(!empty($docente['premios']))
                                                <ul class="list-unstyled">
                                                    @foreach($docente['premios'] as $premio)
                                                        <ul>
                                                            <li>{{ $premio['nome'] ?? 'N/A' }} ({{ $premio['ano'] ?? 'N/A' }})</li>
                                                        </ul>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted">Nenhum prêmio registrado</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6><i class="fas fa-quote-left"></i> Resumo CV</h6>
                                <div class="card card-body bg-light">
                                    {{ $docente['resumoCV'] ?? 'Resumo não disponível' }}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('lattes.exportar_detalhado', $docente['docente']['codpes']) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-file-excel"></i> Exportar Dados Detalhados
                            </a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('javascripts_bottom')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection