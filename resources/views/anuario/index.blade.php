@extends('main') {{-- Substitua pelo layout base do seu projeto --}}

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Menu lateral -->
        <div class="col-md-3">
                <div class="list-group">
                    <a href="{{ route('anuario.infogeral', ['codigo' => '1']) }}"
                        class="list-group-item list-group-item-action active">1 - Informações Gerais</a>
                    <a href="{{ route('anuario.infodemo', ['codigo' => '2']) }}"
                        class="list-group-item list-group-item-action">2 - Informações Demográficas</a>
                    <a href="{{ route('anuario.infodemo', ['codigo' => '3']) }}"
                        class="list-group-item list-group-item-action">2 - Alunos estrangeiros</a>
                    <a href="{{ route('anuario.infodemo', ['codigo' => '4']) }}"
                        class="list-group-item list-group-item-action">3 - Evolução dos Alunos</a>
            </div>

        </div>

        <!-- Conteúdo principal -->
        <div class="col-md-9">
            <h2 class="mb-4">Seção 2: Informações Demográficas</h2>

            <!-- Abas -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-tabelas" data-bs-toggle="tab" href="#tabelas">Tabelas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-graficos" data-bs-toggle="tab" href="#graficos">Gráficos</a>
                </li>
            </ul>

            <!-- Conteúdo das abas -->
            <div class="tab-content mt-3">
                <!-- Tabelas -->
                <div class="tab-pane fade show active" id="tabelas">
                    <ul class="list-group">
                        <li class="list-group-item"><a href="#">2.01 - Alunos de Graduação distribuídos pelas Unidades e
                                Cursos</a></li>
                        <li class="list-group-item"><a href="#">2.02 - Alunos estrangeiros matriculados em Cursos de
                                Graduação</a></li>
                        <li class="list-group-item"><a href="#">2.03 - Evolução do número de Alunos matriculados de
                                Graduação</a></li>
                        <li class="list-group-item"><a href="#">2.04 - Alunos de Pós-Graduação distribuídos pelas Áreas
                                de Concentração</a></li>
                        <li class="list-group-item"><a href="#">2.05 - Alunos de Pós-Graduação distribuídos pelas
                                Unidades</a></li>
                        <!-- Adicione outros links aqui -->
                    </ul>
                </div>

                <!-- Gráficos -->
                <div class="tab-pane fade" id="graficos">
                    <p>Conteúdo relacionado aos gráficos ainda será implementado.</p>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="tab-pane fade" id="graficos">
            <p>Conteúdo relacionado aos gráficos ainda será implementado.</p>
        </div>
    </div>
</div>
</div>
</div>
@endsection