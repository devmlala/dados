@extends('laravel-usp-theme::master')

@section('content')
<div class="container">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">
                <i class="fas fa-book-open text-primary mr-2"></i>
                Portal Lattes dos Docentes
            </h5>
            
            <div class="list-group">
                <a href="{{ route('lattes.docentes.artigos') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-file-alt text-primary mr-2"></i>
                    Artigos Publicados
                </a>
                
                <a href="#" class="list-group-item list-group-item-action disabled">
                    <i class="fas fa-file-contract text-secondary mr-2"></i>
                    Resumos (em breve)
                </a>
                
                <!-- Exemplo de novo item -->
                <a href="#" class="list-group-item list-group-item-action disabled">
                    <i class="fas fa-book text-secondary mr-2"></i>
                    Livros Publicados (em breve)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection