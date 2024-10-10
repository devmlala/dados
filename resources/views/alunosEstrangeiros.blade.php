@extends('main')

@section('content')

<!-- Identificar: alunos estrangeiros por ano. Curso se necessário (há poucos registros) -->

@foreach($alunosEstrangeiro as $alunosEstrangeiros)
    <p>{{ print_r($alunosEstrangeiros, true) }}</p>

@endforeach


<div class="content-options">
    <label for="ano" class="form-label">Filtrar por ano:</label>
        <select id="ano" class="form-select" onchange="location = this.value;">
            @foreach($anos as $a)
                <option 
                    @if($a == $ano)
                        selected="selected"
                    @endif    
                value="/alunosEstrangeiros/{{$a}}">
                    {{ $a }}
                </option>
            @endforeach
        </select>

<a href="/alunosEstrangeiros/export/excel/{{$ano}}">
    <i class="fas fa-file-excel"></i> Download Excel</a>

</div>

<div id="chart-div"></div>

{!! $lava->render('PieChart', 'Alunos Estrangeiros', 'chart-div') !!}

@endsection













@endsection
