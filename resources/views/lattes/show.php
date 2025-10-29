@extends('layouts.app')

@section('content')
    <h1>Currículo de {{ $nome }} ({{ $codpes }})</h1>

    <h2>Artigos Publicados</h2>
    <ul>
        @forelse($artigos as $artigo)
            <li>{{ $artigo['TITULO-DO-ARTIGO'] ?? '[Sem título]' }} ({{ $artigo['ANO'] ?? 'Ano não informado' }})</li>
        @empty
            <li>Nenhum artigo encontrado.</li>
        @endforelse
    </ul>

    <h2>Livros Publicados</h2>
    <ul>
        @forelse($livros as $livro)
            <li>{{ $livro['TITULO-DO-LIVRO'] ?? '[Sem título]' }} ({{ $livro['ANO'] ?? 'Ano não informado' }})</li>
        @empty
            <li>Nenhum livro encontrado.</li>
        @endforelse
    </ul>

    <h2>Linhas de Pesquisa</h2>
    <ul>
        @forelse($linhas as $linha)
            <li>{{ $linha }}</li>
        @empty
            <li>Nenhuma linha de pesquisa encontrada.</li>
        @endforelse
    </ul>

    <a href="{{ route('lattes.export.artigos', $codpes) }}" class="btn btn-success">Exportar Artigos</a>
    <a href="{{ route('lattes.export.livros', $codpes) }}" class="btn btn-success">Exportar Livros</a>
    <a href="{{ route('lattes.export.linhas', $codpes) }}" class="btn btn-success">Exportar Linhas de Pesquisa</a>
@endsection
