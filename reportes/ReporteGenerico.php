<?php
require_once dirname(__FILE__) . '/Estrategia.php';

class ReporteGenerico
{
    private $estrategia;
    private $datos;
    private $params;

    function __construct()
    {
    }

    function usarEstrategia(Estrategia $estrategia)
    {
        $this->estrategia = $estrategia;
    }

    function ejecutar()
    {
        $this->estrategia->generarReporte();
        $this->estrategia->output($this->estrategia->url_archivo, 'F');
    }
}