<?php
/**
 * @package pXP
 * @file (valvarado)
 * @date 14-08-2019
 * @description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */

class ACTAdendas extends ACTbase
{

    function listar()
    {
        $this->objParam->defecto('ordenacion', 'id_adenda');
        $this->objParam->defecto('dir_ordenacion', 'asc');

        if ($this->objParam->getParametro('estado') != '') {
            $this->objParam->addFiltro("ad.estado = ''" . $this->objParam->getParametro('estado') . "''");
        }
        if ($this->objParam->getParametro('id_obligacion_pago') != '') {
            $this->objParam->addFiltro("ad.id_obligacion_pago = ''" . $this->objParam->getParametro('id_obligacion_pago') . "''");
        }

        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODAdenda', 'listar');
        } else {
            $this->objFunc = $this->create('MODAdenda');
            $this->res = $this->objFunc->listar($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function insertar()
    {
        $this->objFunc = $this->create('MODAdenda');
        if ($this->objParam->insertar('id_adenda')) {
            $this->res = $this->objFunc->insertar($this->objParam);
        } else {
            $this->res = $this->objFunc->modificar($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminar()
    {
        $this->objFunc = $this->create('MODAdenda');
        $this->res = $this->objFunc->eliminar($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function clonarObligacion()
    {
        $this->objParam->addParametro('id_funcionario', $_SESSION["ss_id_funcionario"]);
        $this->objParam->addParametro('estado_reg', 'activo');
        $this->objFunc = $this->create('MODAdenda');
        $this->res = $this->objFunc->clonarObligacion($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function siguienteEstadoAdenda()
    {
        $this->objFunc = $this->create('MODAdenda');

        $this->objParam->addParametro('id_funcionario_usu', $_SESSION["ss_id_funcionario"]);

        $this->res = $this->objFunc->siguienteEstado($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
}

?>