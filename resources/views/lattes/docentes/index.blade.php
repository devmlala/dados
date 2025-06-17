@extends('laravel-usp-theme::master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Módulo Lattes - Docentes</h2>
            <p class="lead">Acesso às informações curriculares dos docentes</p>
        </div>
    </div>

    <!-- Cards de Navegação -->
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="fas fa-file-alt fa-lg mr-2"></i>Artigos
                    </h5>
                    <p class="card-text">Visualize os artigos científicos publicados pelos docentes</p>
                    <a href="{{ route('lattes.artigos') }}" class="btn btn-outline-primary">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        <i class="fas fa-book fa-lg mr-2"></i>Livros
                    </h5>
                    <p class="card-text">Lista de livros publicados pelos docentes</p>
                    <a href="{{ route('lattes.livros') }}" class="btn btn-outline-success">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-info">
                <div class="card-body">
                    <h5 class="card-title text-info">
                        <i class="fas fa-graduation-cap fa-lg mr-2"></i>Orientações
                    </h5>
                    <p class="card-text">Orientações de mestrado, doutorado e iniciação científica</p>
                    <a href="{{ route('lattes.orientacoes') }}" class="btn btn-outline-info">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning">
                        <i class="fas fa-chart-line fa-lg mr-2"></i>Projetos
                    </h5>
                    <p class="card-text">Projetos de pesquisa em andamento ou concluídos</p>
                    <a href="{{ route('lattes.projetos') }}" class="btn btn-outline-warning">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-danger">
                <div class="card-body">
                    <h5 class="card-title text-danger">
                        <i class="fas fa-trophy fa-lg mr-2"></i>Prêmios
                    </h5>
                    <p class="card-text">Prêmios e distinções recebidos pelos docentes</p>
                    <a href="{{ route('lattes.premios') }}" class="btn btn-outline-danger">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-secondary">
                <div class="card-body">
                    <h5 class="card-title text-secondary">
                        <i class="fas fa-file-contract fa-lg mr-2"></i>Currículo Completo
                    </h5>
                    <p class="card-text">Visão consolidada de todas as informações curriculares</p>
                    <a href="{{ route('lattes.curriculo') }}" class="btn btn-outline-secondary">Acessar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumo Curricular dos Docentes -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Resumo Curricular por Docente</h4>
                </div>
                <div class="card-body">
                    <form method="get" class="mb-4">
                        <div class="form-row align-items-center">
                            <div class="col-md-4 mb-2">
                                <label for="limit" class="sr-only">Docentes por página</label>
                                <select class="form-control" name="limit" id="limit">
                                    <option value="5" {{ request('limit') == 5 ? 'selected' : '' }}>5 docentes</option>
                                    <option value="10" {{ request('limit') == 10 || !request('limit') ? 'selected' : '' }}>10 docentes</option>
                                    <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25 docentes</option>
                                    <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50 docentes</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="search" class="sr-only">Pesquisar</label>
                                <input type="text" class="form-control" name="search" id="search" 
                                       placeholder="Pesquisar docente..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2 mb-2">
                                <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                            </div>
                        </div>
                    </form>

                    <div class="list-group">
                        @foreach ($docentes as $docente)
                        <div class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $docente['nompes'] }}</h5>
                                <small>COD: {{ $docente['codpes'] }}</small>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <p class="mb-1"><i class="fas fa-file-alt text-primary mr-2"></i> 
                                        <strong>Artigos:</strong> {{ $docente['total_artigos'] ?? '0' }}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><i class="fas fa-book text-success mr-2"></i> 
                                        <strong>Livros:</strong> {{ $docente['total_livros'] ?? '0' }}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><i class="fas fa-graduation-cap text-info mr-2"></i> 
                                        <strong>Orientações:</strong> {{ $docente['total_orientacoes'] ?? '0' }}
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <p class="mb-1"><i class="fas fa-chart-line text-warning mr-2"></i> 
                                        <strong>Projetos:</strong> {{ $docente['total_projetos'] ?? '0' }}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><i class="fas fa-trophy text-danger mr-2"></i> 
                                        <strong>Prêmios:</strong> {{ $docente['total_premios'] ?? '0' }}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('lattes.detalhes', $docente['codpes']) }}" 
                                       class="btn btn-sm btn-outline-primary float-right">
                                        Ver detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($docentes->hasPages())
                    <div class="mt-4">
                        {{ $docentes->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection