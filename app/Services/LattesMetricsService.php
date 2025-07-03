<?php

namespace App\Services;

use Uspdev\Replicado\Lattes;
use Uspdev\Replicado\Pessoa;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LattesMetricsService
{
    /**
     * Retorna os docentes com suas métricas detalhadas
     */
    public function getDocentesComMetricas(int $limit = 10): array
    {
        $todosDocentes = Pessoa::listarDocentes();
        $docentes = array_slice($todosDocentes, 0, $limit);

        $resultado = [];

        foreach ($docentes as $docente) {
            $codpes = $docente['codpes'];
            $metricas = $this->getMetricasDetalhadas($codpes);

            $resultado[] = [
                'docente' => $docente,
                'artigos' => $metricas['artigos'],
                'livros' => $metricas['livros'],
                'capitulosLivros' => $metricas['capitulosLivros'],
                'projetos' => $metricas['projetos'],
                'orientacoesIC' => $metricas['orientacoesIC'],
                'orientacoesConcluidasDoc' => $metricas['orientacoesConcluidasDoc'],
                'orientacoesMestrado' => $metricas['orientacoesMestrado'],
                'premios' => $metricas['premios'] ?? [],
                'contagem' => $metricas['contagem'],
                'ultimaAtualizacao' => $metricas['ultimaAtualizacao'],
                'resumoCV' => $metricas['resumoCV'] ?? '',
            ];
            
        }

        return $resultado;
    }

    /**
     * Retorna os dados brutos e contagens de um docente
     */
    public function getMetricasDetalhadas(int $codpes): array
    {
                $lattesArray = Lattes::obterArray($codpes);

                $artigos = Lattes::listarArtigos($codpes, $lattesArray, 'registros', -1);
                //$artigos = is_array($artigos) ? $artigos : [];

                $livros = Lattes::listarLivrosPublicados($codpes, $lattesArray, 'registros', -1);
                $livros = is_array($livros) ? $livros : [];

                $projetos = Lattes::listarProjetosPesquisa($codpes, $lattesArray);
                $projetos = is_array($projetos) ? $projetos : [];
              
                $linhasDePesquisa = Lattes::listarLinhasPesquisa($codpes, $lattesArray);
                $linhasDePesquisa = is_array($linhasDePesquisa) ? $linhasDePesquisa : [];

                $textosJornaisRevistas = Lattes::listarTextosJornaisRevistas($codpes, $lattesArray, 'registros', -1);
                $textosJornaisRevistas = is_array($textosJornaisRevistas) ? $textosJornaisRevistas : [];

                $trabAnais = Lattes::listarTrabalhosAnais($codpes, $lattesArray, 'registros', -1);
                $trabAnais = is_array($trabAnais) ? $trabAnais : [];

                $trabTecnicos = Lattes::listarTrabalhosTecnicos($codpes, $lattesArray, 'registros', -1);
                $trabTecnicos = is_array($trabTecnicos) ? $trabTecnicos : [];

                $apresTrab = Lattes::listarApresentacaoTrabalho($codpes, $lattesArray, 'registros', -1);
                $apresTrab = is_array($apresTrab) ? $apresTrab : [];

                $capitulosLivros = Lattes::listarCapitulosLivros($codpes, $lattesArray, 'registros', -1);
                $capitulosLivros = is_array($capitulosLivros) ? $capitulosLivros : [];

                $bancasMestrado = Lattes::retornarBancaMestrado($codpes, $lattesArray);
                $bancasMestrado = is_array($bancasMestrado) ? $bancasMestrado : [];

                $bancasDoutorado = Lattes::retornarBancaDoutorado($codpes, $lattesArray);
                $bancasDoutorado = is_array($bancasDoutorado) ? $bancasDoutorado : [];

                $relatoriosPesquisa = Lattes::listarRelatorioPesquisa($codpes, $lattesArray, 'registros', -1);
                $relatoriosPesquisa = is_array($relatoriosPesquisa) ? $relatoriosPesquisa : [];

                $formacaoAcademica = Lattes::retornarFormacaoAcademica($codpes, $lattesArray);
                $formacaoAcademica = is_array($formacaoAcademica) ? $formacaoAcademica : [];

                $formacaoProfissional = Lattes::listarFormacaoProfissional($codpes, $lattesArray, 'periodo', -1);
                $formacaoProfissional = is_array($formacaoProfissional) ? $formacaoProfissional : [];

                $premios = Lattes::listarPremios($codpes, $lattesArray);
                $premios = is_array($premios) ? $premios : [];

                $organizacaoEventos = Lattes::listarOrganizacaoEvento($codpes, $lattesArray, 'registros', -1);
                $organizacaoEventos = is_array($organizacaoEventos) ? $organizacaoEventos : [];

                $materialDidatico = Lattes::listarMaterialDidaticoInstrucional($codpes, $lattesArray, 'registros', -1);
                $materialDidatico = is_array($materialDidatico) ? $materialDidatico : [];

                $resumoCV = Lattes::retornarResumoCV($codpes, 'pt', $lattesArray);
                $resumoCV = is_string($resumoCV) ? $resumoCV : [];

                $ultimaAtualizacao = Lattes::retornarUltimaAtualizacao($codpes, $lattesArray);
                $ultimaAtualizacao = is_array($ultimaAtualizacao) ? $ultimaAtualizacao : [];

                $orcid = Lattes::retornarOrcidID($codpes, $lattesArray);
                $orcid = is_array($orcid) ? $orcid : [];

                $orientacoesConcluidasDoc = Lattes::listarOrientacoesConcluidasDoutorado($codpes, $lattesArray, 'registros', -1);
                $orientacoesConcluidasDoc = is_array($orientacoesConcluidasDoc) ? $orientacoesConcluidasDoc : [];

                $orientacoesMestrado = Lattes::listarOrientacoesConcluidasMestrado($codpes, $lattesArray, 'registros', -1);
                $orientacoesMestrado = is_array($orientacoesMestrado) ? $orientacoesMestrado : [];

                $orientacoesPosDoc = Lattes::listarOrientacoesConcluidasPosDoutorado($codpes, $lattesArray, 'registros', -1);
                $orientacoesPosDoc = is_array($orientacoesPosDoc) ? $orientacoesPosDoc : [];

                $orientacoesIC = Lattes::listarOrientacoesConcluidasIC($codpes, $lattesArray, 'registros', -1);
                $orientacoesIC = is_array($orientacoesIC) ? $orientacoesIC : [];

                $premios = Lattes::listarPremios($codpes, $lattesArray);
                $premios = is_array($premios) ? $premios : [];

                $ultimaAtualizacao = Lattes::retornarUltimaAtualizacao($codpes, $lattesArray);


                $contagem = [
                    'artigos' => count($artigos),
                    'livros' => count($livros),
                    'projetos' => count($projetos),
                    'linhas-de-pesquisa' => count($linhasDePesquisa),
                    'textos-jornais-revistas' => count($textosJornaisRevistas),
                    'trabalhos-anais' => count($trabAnais),
                    'trabalhos-tecnicos' => count($trabTecnicos),
                    'apresentacao-de-trabalho' => count($apresTrab),
                    'capitulos-livros' => count($capitulosLivros),
                    'bancas-mestrado' => count($bancasMestrado),
                    'bancas-doutorado' => count($bancasDoutorado),
                    'relatorios-pesquisa' => count($relatoriosPesquisa),
                    'formacao-academica' => count($formacaoAcademica),
                    'formacao-profissional' => count($formacaoProfissional),
                    'organizacao-eventos' => count($organizacaoEventos),
                    'material-didatico' => count($materialDidatico),
                    //'resumo-cv' => count($resumoCV),
                    //'orcid' => count($orcid),
                    'orientacoes-concluidas-doutorado' => count($orientacoesConcluidasDoc),
                    'orientacoes-concluidas-mestrado' => count($orientacoesMestrado),
                    'orientacoes-concluidas-pos-doutorado' => count($orientacoesPosDoc),
                    'orientacoes-concluidas-ic' => count($orientacoesIC),
                    'premios' => count($premios),
                    //'ultima-atualizacao' => $ultimaAtualizacao,
                ];


                return compact(
                    'artigos',
                    'livros',
                    'capitulosLivros',
                    'projetos',
                    'orientacoesIC',
                    'orientacoesConcluidasDoc',
                    'orientacoesMestrado',
                    'orientacoesPosDoc',
                    'linhasDePesquisa',
                    'textosJornaisRevistas',
                    'trabAnais',
                    'trabTecnicos',
                    'apresTrab',
                    'bancasMestrado',
                    'bancasDoutorado',
                    'relatoriosPesquisa',
                    'formacaoAcademica',
                    'formacaoProfissional',
                    'premios',
                    'organizacaoEventos',
                    'materialDidatico',
                    'resumoCV',
                    'ultimaAtualizacao',
                    'orcid',
                    'contagem'
                );
                
    }

    private function metricasVazias(): array
    {
        return [
            'artigos' => [],
            'livros' => [],
            'projetos' => [],
            'orientacoesIC' => [],
            'contagem' => [
                'artigos' => 0,
                'livros' => 0,
                'projetos' => 0,
                'orientacoes' => 0,
            ],
            'ultimaAtualizacao' => null,
        ];
    }
}
