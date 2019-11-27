<?php
/**
 * @package pXP
 * @file (valvarado)
 * @date 14-08-2019
 * @description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */
require_once(dirname(__FILE__) . '/../../pxp/pxpReport/DataSource.php');
require_once(dirname(__FILE__) . '/../reportes/ReporteGenerico.php');
require_once(dirname(__FILE__) . '/../reportes/ReporteOrden.php');

class ACTReportesOrden extends ACTbase
{
    function reporteOrden($create_file = false, $onlyData = false)
    {
        $dataSource = new DataSource();

        $firmar = 'no';
        $fecha_firma = '';
        $usuario_firma = '';

        $this->objParam->addParametroConsulta('ordenacion', 'id_adenda');
        $this->objParam->addParametroConsulta('dir_ordenacion', 'ASC');
        $this->objParam->addParametroConsulta('cantidad', 1000);
        $this->objParam->addParametroConsulta('puntero', 0);

        $this->objFunc = $this->create('MODAdenda');
        $this->res_adenda = $this->objFunc->obtener();


        $this->objFunc1 = $this->create('MODAdendaDet');
        $this->res_adenda_detalle = $this->objFunc1->listar();

        $this->objFunc2 = $this->create('MODAdendaDet');
        $this->res_presupuesto_detalle = $this->objFunc2->presupuestoDetalleRpt();

        $datosAdenda = $this->res_adenda->getDatos();
        $datosAdendaDet = $this->res_adenda_detalle->getDatos();
        $datosPresupuesto = $this->res_presupuesto_detalle->getDatos();
        $dataSource->putParameter('adenda', $datosAdenda);
        $dataSource->putParameter('adendaDet', $datosAdendaDet);
        $dataSource->putParameter('presupuestoDet', $datosPresupuesto);

        $nombreArchivo = uniqid(md5(session_id()) . 'Adenda') . '.pdf';
        $this->objParam->addParametro('orientacion', 'P');
        $this->objParam->addParametro('tamano', 'LETTER');
        $this->objParam->addParametro('titulo_archivo', 'Adenda');
        $this->objParam->addParametro('nombre_archivo', $nombreArchivo);
        $this->objParam->addParametro('firmar', $firmar);
        $this->objParam->addParametro('fecha_firma', $fecha_firma);
        $this->objParam->addParametro('usuario_firma', $usuario_firma);


        $reporteOrden = new ReporteOrden($this->objParam);
        $reporteOrden->setDataSource($dataSource);

        $reporte = new ReporteGenerico();
        $reporte->usarEstrategia($reporteOrden);
        $reporte->ejecutar();

        if (!$create_file) {
            $mensajeExito = new Mensaje();
            $mensajeExito->setMensaje('EXITO', 'Reporte.php', 'Reporte generado', 'Se gener&oacute; con &eoacute;xito el reporte: ' . $nombreArchivo, 'control');
            $mensajeExito->setArchivoGenerado($nombreArchivo);
            $this->res_adenda = $mensajeExito;
            $this->res_adenda->imprimirRespuesta($this->res_adenda->generarJson());
        } else {
            return dirname(__FILE__) . '/../../reportes_generados/' . $nombreArchivo;
        }
    }
}

?>