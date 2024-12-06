<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anuario extends Model
{
    protected $table = 'anuarios'; // Nome da tabela no banco de dados
    protected $fillable = ['ano', 'secao', 'titulo', 'conteudo'];

    /**
     * Retorna os anos disponíveis no anuário
     *
     * @return array
     */
    public static function anosDisponiveis()
    {
        return Anuario::distinct()->pluck('ano')->sortDesc()->toArray();
    }

    /**
     * Retorna as seções de um determinado ano
     *
     * @param int $ano
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function secoesPorAno($ano)
    {
        return Anuario::where('ano', $ano)->get();
    }

    public static function anos()
    {
        // Aqui, supondo que você tenha uma coluna 'ano' na tabela 'anuarios'
        return self::select('ano')
                    ->distinct()
                    ->orderBy('ano', 'desc')
                    ->pluck('ano')
                    ->toArray();
    }
}
