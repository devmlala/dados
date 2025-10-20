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
        if (!$lattes = $lattes_array ?? self::obterArray($codpes)) {
            return false;
        }

        $eventTypes = [
            'PARTICIPACAO-EM-CONGRESSO' => ['name' => 'Congresso', 'prefix' => 'DADOS-BASICOS-DA-PARTICIPACAO-EM-CONGRESSO', 'detail_prefix' => 'DETALHAMENTO-DA-PARTICIPACAO-EM-CONGRESSO'],
            'PARTICIPACAO-EM-SEMINARIO' => ['name' => 'Seminário', 'prefix' => 'DADOS-BASICOS-DA-PARTICIPACAO-EM-SEMINARIO', 'detail_prefix' => 'DETALHAMENTO-DA-PARTICIPACAO-EM-SEMINARIO'],
            'PARTICIPACAO-EM-SIMPOSIO' => ['name' => 'Simpósio', 'prefix' => 'DADOS-BASICOS-DA-PARTICIPACAO-EM-SIMPOSIO', 'detail_prefix' => 'DETALHAMENTO-DA-PARTICIPACAO-EM-SIMPOSIO'],
            'PARTICIPACAO-EM-OFICINA' => ['name' => 'Oficina', 'prefix' => 'DADOS-BASICOS-DA-PARTICIPACAO-EM-OFICINA', 'detail_prefix' => 'DETALHAMENTO-DA-PARTICIPACAO-EM-OFICINA'],
            'PARTICIPACAO-EM-ENCONTRO' => ['name' => 'Encontro', 'prefix' => 'DADOS-BASICOS-DA-PARTICIPACAO-EM-ENCONTRO', 'detail_prefix' => 'DETALHAMENTO-DA-PARTICIPACAO-EM-ENCONTRO'],
            'OUTRAS-PARTICIPACOES-EM-EVENTOS-E-CONGRESSOS' => ['name' => 'Outro', 'prefix' => 'DADOS-BASICOS-DE-OUTRAS-PARTICIPACOES-EM-EVENTOS-CONGRESSOS', 'detail_prefix' => 'DETALHAMENTO-DE-OUTRAS-PARTICIPACOES-EM-EVENTOS-CONGRESSOS'],
        ];

        $allEvents = collect($eventTypes)->flatMap(function ($typeInfo, $lattesKey) use ($lattes) {
            $records = self::listarRegistrosPorChaveOrdenado(
                $lattes,
                "DADOS-COMPLEMENTARES.PARTICIPACAO-EM-EVENTOS-CONGRESSOS.{$lattesKey}",
                "{$typeInfo['prefix']}.@attributes.ANO"
            );

            return collect($records)->map(function ($participation) use ($typeInfo) {
                $basicData = Arr::get($participation, "{$typeInfo['prefix']}.@attributes", []);
                $details = Arr::get($participation, "{$typeInfo['detail_prefix']}.@attributes", []);

                return [
                    'TIPO_EVENTO'        => $typeInfo['name'],
                    'NOME_EVENTO'        => Arr::get($basicData, 'NOME-DO-EVENTO', ''), // Correct: Name is in basic data
                    'ANO'                => (string) Arr::get($basicData, 'ANO', ''),
                    'FORMA_PARTICIPACAO' => Arr::get($basicData, 'FORMA-PARTICIPACAO', ''),
                    'LOCAL_EVENTO'       => Arr::get($details, 'LOCAL-DO-EVENTO', ''), // New: Location is in details
                ];
            });
        });

        return $allEvents
            ->sortByDesc('ANO')
            ->values() // Reset keys to be 0-indexed
            ->filter(function ($event, $index) use ($tipo, $limit_ini, $limit_fim) {
                // The parent's protected method `verificarFiltro` is accessible here.
                return self::verificarFiltro($tipo, $event['ANO'], $limit_ini, $limit_fim, $index + 1);
            })
            ->values() // Reset keys again after filtering
            ->all();
    }

    /**
     * Retrieves a detailed list of Master's examination boards a person participated in.
     *
     * @param int $codpes
     * @param array|null $lattes_array
     * @return array|bool
     */
    public static function listarBancasMestrado($codpes, $lattes_array = null)
    {
        if (!$lattes = $lattes_array ?? self::obterArray($codpes)) {
            return false;
        }

        $records = self::listarRegistrosPorChaveOrdenado(
            $lattes,
            'DADOS-COMPLEMENTARES.PARTICIPACAO-EM-BANCA-TRABALHOS-CONCLUSAO.PARTICIPACAO-EM-BANCA-DE-MESTRADO',
            'DADOS-BASICOS-DA-PARTICIPACAO-EM-BANCA-DE-MESTRADO.@attributes.ANO'
        );

        return collect($records)->map(function ($banca) {
            $basicData = Arr::get($banca, 'DADOS-BASICOS-DA-PARTICIPACAO-EM-BANCA-DE-MESTRADO.@attributes', []);
            $details = Arr::get($banca, 'DETALHAMENTO-DA-PARTICIPACAO-EM-BANCA-DE-MESTRADO.@attributes', []);

            return [
                'ANO'            => Arr::get($basicData, 'ANO', ''),
                'TITULO'         => Arr::get($basicData, 'TITULO', ''),
                'NOME_CANDIDATO' => Arr::get($details, 'NOME-DO-CANDIDATO', ''),
                'TIPO'           => Arr::get($basicData, 'TIPO', ''),
                'NOME_INSTITUICAO' => Arr::get($details, 'NOME-INSTITUICAO', ''),
            ];
        })->all();
    }

    /**
     * Retrieves a detailed list of Doctorate examination boards a person participated in.
     *
     * @param int $codpes
     * @param array|null $lattes_array
     * @return array|bool
     */
    public static function listarBancasDoutorado($codpes, $lattes_array = null)
    {
        if (!$lattes = $lattes_array ?? self::obterArray($codpes)) {
            return false;
        }

        $records = self::listarRegistrosPorChaveOrdenado(
            $lattes,
            'DADOS-COMPLEMENTARES.PARTICIPACAO-EM-BANCA-TRABALHOS-CONCLUSAO.PARTICIPACAO-EM-BANCA-DE-DOUTORADO',
            'DADOS-BASICOS-DA-PARTICIPACAO-EM-BANCA-DE-DOUTORADO.@attributes.ANO'
        );

        return collect($records)->map(function ($banca) {
            $basicData = Arr::get($banca, 'DADOS-BASICOS-DA-PARTICIPACAO-EM-BANCA-DE-DOUTORADO.@attributes', []);
            $details = Arr::get($banca, 'DETALHAMENTO-DA-PARTICIPACAO-EM-BANCA-DE-DOUTORADO.@attributes', []);

            return [
                'ANO'            => Arr::get($basicData, 'ANO', ''),
                'TITULO'         => Arr::get($basicData, 'TITULO', ''),
                'NOME_CANDIDATO' => Arr::get($details, 'NOME-DO-CANDIDATO', ''),
                'TIPO'           => Arr::get($basicData, 'TIPO', ''),
                'NOME_INSTITUICAO' => Arr::get($details, 'NOME-INSTITUICAO', ''),
            ];
        })->all();
    }
}