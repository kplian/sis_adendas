<?php
/**
 * @package pXP
 * @file (valvarado)
 * @date 14-08-2019
 * @description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */

class ACTTipos extends ACTbase
{

    function listar()
    {
        $this->objParam->defecto('ordenacion', 'id_adenda');
        $this->objParam->defecto('dir_ordenacion', 'asc');

        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODTipo', 'listar');
        } else {
            $this->objFunc = $this->create('MODTipo');
            $this->res = $this->objFunc->listar($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function insertar()
    {
        $this->objFunc = $this->create('MODTipo');
        if ($this->objParam->insertar('id_tipo')) {
            $this->res = $this->objFunc->insertar($this->objParam);
        } else {
            $this->res = $this->objFunc->modificar($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminar()
    {
        $this->objFunc = $this->create('MODTipo');
        $this->res = $this->objFunc->eliminar($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
}

?>