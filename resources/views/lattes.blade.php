<h1>Currículo Lattes</h1>

<ul>
    <li>Nome: {{ $dados['nome'] ?? 'Não disponível' }}</li>
    <li>Data de atualização: {{ \Carbon\Carbon::parse($dados['data_atualizacao'])->format('d/m/Y') }}</li>
    <li>Link: <a href="{{ $dados['url'] }}">{{ $dados['url'] }}</a></li>
</ul>
