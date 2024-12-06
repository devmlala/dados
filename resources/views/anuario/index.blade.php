@extends('main') {{-- Substitua pelo layout base do seu projeto --}}

@section('content')
<div class="container mt-4">
    <!-- Formulário de seleção de ano -->
    <div class="form-group">
        <form method="GET" action="{{ route('anuario.index') }}" class="mb-4">
            <label for="ano"><b>Selecionar ano do Anuário</b></label>
            <select class="form-control" id="ano" name="ano" onchange="this.form.submit()">
                @foreach($anosDisponiveis as $ano)
                    <option value="{{ $ano }}" {{ $ano == ($anoSelecionado ?? '2024') ? 'selected' : '' }}>{{ $ano }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="row mt-4">
        <!-- Menu lateral -->
        <div class="col-md-3">
            <button class="btn btn-primary d-md-none mb-3" type="button" data-bs-toggle="collapse"
                data-bs-target="#menuLateral" aria-expanded="false" aria-controls="menuLateral">
                Menu
            </button>
            <div class="collapse d-md-block" id="menuLateral">
                <div class="list-group">
                    <!-- Itens do menu com marcação de item ativo -->
                    @foreach([['codigo' => '1', 'label' => '1 - Informações Gerais'], ['codigo' => '2', 'label' => '2 - Informações Demográficas'], ['codigo' => '3', 'label' => '3 - Informações Acadêmicas'], ['codigo' => '4', 'label' => '4 - Informações Departamentais']] as $menuItem)
                        <a href="{{ route('anuario.infodemo', ['codigo' => $menuItem['codigo']]) }}"
                            class="list-group-item list-group-item-action {{ request()->codigo == $menuItem['codigo'] ? 'active' : '' }}">
                            {{ $menuItem['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Conteúdo principal -->
        <div class="col-md-9">
            <h2 class="mb-4">Seção: {{ request()->codigo ?? 'Informações Gerais' }} (Ano: {{ $anoSelecionado }})</h2>

            <!-- Abas de navegação -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-tabelas"
                        href="{{ route('anuario.tabelas', ['ano' => $anoSelecionado]) }}">Tabelas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-graficos"
                        href="{{ route('anuario.graficos', ['ano' => $anoSelecionado]) }}">Gráficos</a>
                </li>

            </ul>

            <!-- Conteúdo das abas -->
            <!-- Conteúdo das abas -->
            <div class="tab-content mt-3">
                <!-- Conteúdo da aba Tabelas -->
                <div class="tab-pane fade show active" id="tabelas">
                    <ul class="list-group">
                        @foreach ($itensTabelas as $itensTabela)
                            <li class="list-group-item">{{ $itensTabela->titulo }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Conteúdo da aba Gráficos -->
                <div class="tab-pane fade" id="graficos">
                    <p>Conteúdo relacionado aos gráficos ainda será implementado.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection