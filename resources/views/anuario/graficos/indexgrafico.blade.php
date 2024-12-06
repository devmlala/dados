<!-- resources/views/anuario/graficoindex.blade.php -->
@extends('layouts.app')

@section('title', 'Gráficos do Anuário')

@section('content')
<div class="container mt-4">
    <h1>Gráficos do Anuário - Ano: {{ $anoSelecionado }}</h1>

    @if ($graficos)
        <div class="row">
            @foreach ($graficos as $grafico)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $grafico }}</h5>
                            <p class="card-text">{{ $grafico }}</p>
                            <a href="#" class="btn btn-primary">Ver Gráfico</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center">Nenhum gráfico encontrado para o ano selecionado.</p>
    @endif
</div>
@endsection
