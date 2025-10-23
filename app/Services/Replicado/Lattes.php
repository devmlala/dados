<?php

namespace App\Services\Replicado;

use Uspdev\Replicado\Lattes as LattesBase;
use Illuminate\Support\Arr;

class Lattes extends LattesBase
{
    /**
     * Recebe o número USP e devolve array com as participações em eventos e congressos.
     *
     * Os campos $tipo, $limit_ini e $limit_fim são usado em diversos métodos e o signifcado e valores default são os mesmos
     * Default: tipo = registro, limit_ini = 5
     *
     * Dependendo de $tipo, o resultado é modificado:
     * $tipo == 'anual': retorna os eventos dos últimos $limit_ini anos
     * $tipo == 'registros': retorna os $limit_ini eventos mais recentes
     * $tipo == 'periodo': retorna todos os eventos dos anos entre $limit_ini e $limit_fim
     *
     * @param Integer $codpes = Número USP
     * @param Array $lattes_array (opcional) Lattes convertido para array
     * @param String $tipo (opcional) Valores possíveis para determinar o limite: 'anual' e 'registros', 'periodo'. Default: últimos 5 registros.
     * @param Integer $limit_ini (opcional) Limite de retorno conforme o tipo.
     * @param Integer $limit_fim (opcional) Se o tipo for periodo, irá pegar os registros do ano entre limit_ini e limit_fim
     * @return Array|Bool
     */
    public static function listarParticipacaoEventos($codpes, $lattes_array = null, $tipo = 'registros', $limit_ini = 5, $limit_fim = null)
    {
        // A chamada a `self::obterArray` funcionará porque nossa classe herda de LattesBase
        if (!$lattes = self::obterArray($codpes, $lattes_array)) {
            return false;
        }

        $eventos = Arr::get($lattes, 'DADOS-COMPLEMENTARES.PARTICIPACAO-EM-EVENTOS-CONGRESSOS', false);
        if (!$eventos) {
            return false;
        }

        $tipos_evento = [
            'PARTICIPACAO-EM-CONGRESSO' => 'Congresso',
            'PARTICIPACAO-EM-SEMINARIO' => 'Seminário',
            'PARTICIPACAO-EM-SIMPOSIO' => 'Simpósio',
            'PARTICIPACAO-EM-OFICINA' => 'Oficina',
            'PARTICIPACAO-EM-ENCONTRO' => 'Encontro',
            'OUTRAS-PARTICIPACOES-EM-EVENTOS-CONGRESSOS' => 'Outro',
        ];

        $todos_eventos = [];
        foreach ($tipos_evento as $chave_lattes => $nome_tipo) {
            $participacoes = Arr::get($eventos, $chave_lattes, []);

            // Normaliza a estrutura: se for um único evento (array associativo), coloca-o dentro de um array numérico.
            if (!empty($participacoes) && !is_numeric(key($participacoes))) {
                $participacoes = [$participacoes];
            }

            foreach ($participacoes as $participacao) {
                // As chaves são dinâmicas, baseadas no tipo de evento (ex: ...-EM-CONGRESSO)
                $dados_basicos_key = str_replace('PARTICIPACAO-EM-', 'DADOS-BASICOS-DA-PARTICIPACAO-EM-', $chave_lattes);
                $detalhamento_key = str_replace('PARTICIPACAO-EM-', 'DETALHAMENTO-DA-PARTICIPACAO-EM-', $chave_lattes);

                $dados_basicos = Arr::get($participacao, "{$dados_basicos_key}.@attributes", []);
                $detalhamento = Arr::get($participacao, "{$detalhamento_key}.@attributes", []);
                $todos_eventos[] = [
                    'TIPO_EVENTO'        => $nome_tipo,
                    'NOME_EVENTO'        => Arr::get($detalhamento, 'NOME-DO-EVENTO', ''),
                    
                    // O campo de ano pode variar, então verificamos as duas possibilidades.
                    'ANO'                => Arr::get($dados_basicos, 'ANO', Arr::get($dados_basicos, 'ANO-DE-REALIZACAO', '')),
                    
                    // A forma de participação está nos dados básicos, não no detalhamento.
                    'FORMA_PARTICIPACAO' => Arr::get($dados_basicos, 'FORMA-PARTICIPACAO', ''),
                    'LOCAL_EVENTO'       => Arr::get($detalhamento, 'LOCAL-DO-EVENTO', ''),
                ];
            }
        }

        // Ordena todos os eventos por ano, do mais recente para o mais antigo
        usort($todos_eventos, function ($a, $b) {
            return (int) $b['ANO'] - (int) $a['ANO'];
        });

        $eventos_filtrados = [];
        $i = 0;
        foreach ($todos_eventos as $evento) {
            $i++;
            // A chamada a `self::verificarFiltro` também funcionará, pois o método é `protected` na classe pai.
            if (self::verificarFiltro($tipo, $evento['ANO'], $limit_ini, $limit_fim, $i)) {
                $eventos_filtrados[] = $evento;
            }
        }

        return $eventos_filtrados;
    }
}