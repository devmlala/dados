<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anuario;
use PhpParser\Node\Expr\Cast\Object_;

class AnuarioController extends Controller
{
    /**
     * Exibe a página principal do anuário com a seleção de ano.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Recuperar todos os anos distintos disponíveis
        $anosDisponiveis = Anuario::distinct()->pluck('ano');

        // Defina um valor padrão para $anoSelecionado, se necessário.
        $anoSelecionado = request()->input('ano', $anosDisponiveis->first()); // Seleciona o primeiro ano, caso não tenha um ano específico na requisição

        // Buscar os itens da tabela para o ano selecionado
        $itensTabelas = Anuario::where('ano', $anoSelecionado)->get();

        return view('anuario.index', compact('anosDisponiveis', 'anoSelecionado', 'itensTabelas'));
    }

    /**
     * Exibe a página de tabelas para o ano especificado.
     *
     * @param int $ano
     * @return \Illuminate\View\View
     */
    /**
 * Exibe a página de tabelas para o ano especificado.
 *
 * @param int $ano
 * @return \Illuminate\View\View
 */
public function listarTabelas($ano)
{
    // Recupera todos os anos distintos disponíveis para o filtro no formulário
    $anosDisponiveis = Anuario::distinct()->pluck('ano');

    // Recupera os dados das tabelas com base no ano fornecido
    $itensTabelas = Anuario::where('ano', $ano)->get();

    // Define o ano selecionado como o ano passado como parâmetro
    $anoSelecionado = $ano;

    // Retorna a view com as variáveis necessárias
    return view('anuario.index', compact('anosDisponiveis', 'anoSelecionado', 'itensTabelas'));
}


    /**
     * Exibe a página de gráficos para o ano especificado.
     *
     * @param int $ano
     * @return \Illuminate\View\View
     */

    public function listarGraficos($ano)
    {
        // Definindo o ano selecionado
        $anoSelecionado = 2024;

        // Criando um objeto anônimo e adicionando atributos
        $graficos = new \stdClass();
        $graficos->titulo = "Título do Gráfico";
        $graficos->descricao = "Descrição do Gráfico";

        // Retorna a view com o objeto gráfico e o ano selecionado
        return view('anuario.graficos.indexgrafico', compact('anoSelecionado', 'graficos'));
    }



    /**
     * Exibe os dados de uma seção específica do anuário em PDF.
     *
     * @param  int  $ano
     * @return \Illuminate\View\View
     */
    public function showAnuario($ano)
    {
        // Busca todos os registros do anuário para o ano fornecido
        $itensTabelas = Anuario::where('ano', $ano)->get();

        // Verifica se há registros para o ano
        if ($itensTabelas->isEmpty()) {
            abort(404, 'Nenhum registro encontrado para o ano especificado.');
        }

        return view('anuario.show', compact('ano', 'itensTabelas'));
    }

    /**
     * Lista as seções para um ano específico.
     *
     * @param  int  $ano
     * @return \Illuminate\View\View
     */
    public function listarSecoes($ano)
    {
        // Recupera todas as seções para o ano selecionado
        $secoes = Anuario::where('ano', $ano)->get();

        return view('anuario.secoes', compact('ano', 'secoes'));
    }

    /**
     * Exibe o conteúdo de uma seção específica do anuário.
     *
     * @param  int  $ano
     * @param  string  $secao
     * @return \Illuminate\View\View
     */
    public function mostrarConteudo($ano, $secao)
    {
        // Recupera o conteúdo específico da seção
        $conteudo = Anuario::where('ano', $ano)
            ->where('secao', $secao)
            ->firstOrFail();

        return view('anuario.conteudo', compact('conteudo'));
    }
}
