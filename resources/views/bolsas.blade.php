@extends('main')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/programas.css') }}">
@endsection

@section('content')

    @if(count($bolsa) > 0)
        @foreach($bolsa as $bolsas)
            <p>{{ print_r($bolsas, true) }}</p>
        @endforeach
    @else
        <p>Nenhuma bolsa encontrada.</p>
    @endif

@endsection