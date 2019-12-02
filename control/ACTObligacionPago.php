<?php
/**
 * @package pXP
 * @file (valvarado)
 * @date 14-08-2019
 * @description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */

class ACTObligacionPago extends ACTbase
{

    function obtenerObligacionPorId()
    {
        $this->objFunc = $this->create('MODObligacionPago');
        $this->res = $this->objFunc->obtenerObligacionPorId($this->objParam);

        $this->res->imprimirRespuesta($this->res->generarJson());
    }
}

?>