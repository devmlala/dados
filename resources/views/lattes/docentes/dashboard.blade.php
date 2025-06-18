@extends('laravel-usp-theme::master')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Docentes: Resumo Lattes Completo</h4>
            <form method="get" class="form-inline">
                <div class="input-group">
                    <label for="limit" class="input-group-text">Docentes:</label>
                    <input type="number" name="limit" value="{{ $limit }}" min="1" max="50"
                        class="form-control form-control-sm" style="width: 70px;">
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4">Docente</th>
                                <th class="text-center">
                                    <i class="fas fa-file-alt text-primary"></i> Artigos Publicados
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-book text-success"></i> Livros Publicados
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-book-open text-info"></i> Capítulos de Livros
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-project-diagram text-info"></i> Projetos de Pesquisa
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-graduation-cap text-danger"></i> Orientações Doutorado
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-user-graduate text-warning"></i> Orientações Mestrado
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-microscope text-primary"></i> Orientações IC
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-dna text-info"></i> Linhas de Pesquisa
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-newspaper text-secondary"></i> Publicação Jornais
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-calendar-alt text-muted"></i> Atualização
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-file-export text-success"></i> Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($docentes as $item)
                                <tr class="border-bottom">
                                    <td class="pl-4">
                                        <div class="font-weight-bold">{{ $item['docente']['nompes'] }}</div>
                                        <div class="text-muted small">{{ $item['docente']['codpes'] }}</div>
                                        @if(!empty($item['orcid']))
                                            <div class="small">
                                                <i class="fab fa-orcid text-success"></i> {{ $item['orcid'] }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-primary badge-pill">
                                            {{ count($item['artigos'] ?? []) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success badge-pill">
                                            {{ count($item['livros'] ?? []) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info badge-pill">
                                            {{ count($item['capitulosLivros'] ?? []) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info badge-pill">
                                            {{ count($item['projetos'] ?? []) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-danger badge-pill">
                                            {{ count($item['orientacoesConcluidasDoc'] ?? []) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-warning badge-pill">
                                            {{ count($item['orientacoesMestrado'] ?? []) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-primary badge-pill">
                                            {{ count($item['orientacoesIC'] ?? []) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info badge-pill">
                                            {{ count($item['linhasDePesquisa'] ?? []) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-secondary badge-pill">
                                            {{ count($item['textosJornaisRevistas'] ?? []) }}
                                        </span>
                                    </td>
                                    <td class="text-muted text-center small">
                                        {{ $item['ultimaAtualizacao'] ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalResumo{{ $item['docente']['codpes'] }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="#" class="btn btn-sm btn-primary">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13">
                                        <div class="alert alert-warning mb-0">Nenhum docente encontrado ou dados indisponíveis.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para resumo -->
    @foreach ($docentes as $item)
    <div class="modal fade" id="modalResumo{{ $item['docente']['codpes'] }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resumo Completo - {{ $item['docente']['nompes'] }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6><i class="fas fa-graduation-cap"></i> Formação Acadêmica</h6>
                        <ul class="list-group small">
                            @foreach($item['formacaoAcademica'] ?? [] as $formacao)
                                <li class="list-group-item">{{ $formacao['titulo'] }} - {{ $formacao['instituicao'] }} ({{ $formacao['anoConclusao'] }})</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Estatísticas</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><strong>Artigos:</strong> {{ count($item['artigos'] ?? []) }}</li>
                                        <li><strong>Livros:</strong> {{ count($item['livros'] ?? []) }}</li>
                                        <li><strong>Capítulos:</strong> {{ count($item['capitulosLivros'] ?? []) }}</li>
                                        <li><strong>Orientações Doutorado:</strong> {{ count($item['orientacoesConcluidasDoc'] ?? []) }}</li>
                                        <li><strong>Orientações Mestrado:</strong> {{ count($item['orientacoesMestrado'] ?? []) }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-trophy"></i> Prêmios e Distinções</h6>
                                </div>
                                <div class="card-body">
                                    @if(!empty($item['premios']))
                                        <ul class="list-unstyled">
                                            @foreach($item['premios'] as $premio)
                                                <li>{{ $premio['titulo'] }} ({{ $premio['ano'] }})</li>
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
                            {{ $item['resumoCV'] ?? 'Resumo não disponível' }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@section('javascripts_bottom')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection