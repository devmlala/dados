@extends('laravel-usp-theme::master')

@section('content')
<div class="container">
    <h4>Portal Lattes dos Docentes</h4>

    <ul>
        <li><a href="{{ route('lattes.docentes.artigos') }}">Ver Artigos Publicados</a></li>
        {{-- <li><a href="{{ route('lattes.docentes.resumos') }}">Ver Resumos</a></li> --}}
    </ul>
</div>
@endsection
