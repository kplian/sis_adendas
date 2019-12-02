<?php
/**
 * @package pXP
 * @file (valvarado)
 * @date 24-06-2019 15:15:06
 * @description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */

class MODObligacionPago extends MODbase
{


    function __construct(CTParametro $pParam)
    {
        parent::__construct($pParam);
    }

    function obtenerObligacionPorId()
    {

        $this->procedimiento = 'ads.ft_obligacion_pago_sel';
        $this->transaccion = 'OBTENER_POR_ID';
        $this->tipo_procedimiento = 'SEL';
        $this->setCount(false);

        $this->setParametro('id_obligacion_pago', 'id_obligacion_pago', 'int4');

        $this->captura('id_obligacion_pago', 'int4');
        $this->captura('id_gestion', 'int4');
        $this->captura('id_contrato', 'int4');
        $this->captura('estado', 'varchar');
        $this->captura('nombre_depto', 'varchar');
        $this->captura('num_tramite', 'varchar');
        $this->captura('id_funcionario', 'int4');
        $this->captura('desc_funcionario2', 'text');
        $this->captura('proveedor', 'varchar');
        $this->captura('total_pago', 'numeric');
        $this->captura('numero_contrato', 'varchar');
        $this->captura('numero_orden', 'varchar');
        $this->captura('fecha_fin', 'date');
        $this->captura('fecha_soli', 'date');
        $this->captura('codigo_fun', 'varchar');
        $this->captura('lugar_entrega', 'varchar');
        $this->captura('fecha_entrega', 'date');
        $this->captura('forma_pago', 'varchar');
        $this->captura('obs', 'text');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }
}

?>

