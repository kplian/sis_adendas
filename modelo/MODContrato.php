<?php
/**
 * @package pXP
 * @file (valvarado)
 * @date 24-06-2019 15:15:06
 * @description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODContrato extends MODbase
{

    function __construct(CTParametro $pParam)
    {
        parent::__construct($pParam);
    }

    function listar()
    {

        $this->procedimiento = 'ads.f_contrato';
        $this->transaccion = 'CONTRATO_SEL';
        $this->tipo_procedimiento = 'SEL';

        $this->captura('id_contrato', 'int4');
        $this->captura('numero', 'varchar');
        $this->captura('id_contrato_fk', 'int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

}

?>