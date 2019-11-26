<?php
/**
 * @package pXP
 * @file (valvarado)
 * @date 14-08-2019
 * @description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */

class ACTContratos extends ACTbase
{

    function listar()
    {
        $this->objParam->defecto('ordenacion', 'numero');
        $this->objParam->defecto('dir_ordenacion', 'asc');
        if ($this->objParam->getParametro("id_contrato_fk") != "") {
            $this->objParam->addFiltro('id_contrato_fk =' . $this->objParam->getParametro("id_contrato_fk"));
        }
        $this->objFunc = $this->create('MODContrato');
        $this->res = $this->objFunc->listar($this->objParam);

        $this->res->imprimirRespuesta($this->res->generarJson());
    }
}

?>