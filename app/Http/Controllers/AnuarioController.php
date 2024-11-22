<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnuarioController extends Controller
{
    public function index()
    {
        return view('anuario.index');
    }

    /**
     * Método genérico para renderizar as views das seções
     * 
     * @param string $secao
     * @param string|null $codigo
     * @return \Illuminate\View\View
     */
    public function renderSecao($secao, $codigo = null)
{
    $view = $codigo 
        ? "anuario.{$secao}.{$codigo}" 
        : "anuario.secoes.{$secao}";

    if (!view()->exists($view)) {
        abort(404, "View '{$view}' não encontrada.");
    }

    return view($view);
}



}

