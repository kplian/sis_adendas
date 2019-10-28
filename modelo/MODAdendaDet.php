<?php
/**
 * @package pXP
 * @file MODAdenda@author  (valvarado)
 * @date 24-06-2019 15:15:06
 * @description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODAdendaDet extends MODbase
{

    function __construct(CTParametro $pParam)
    {
        parent::__construct($pParam);
    }

    function listar()
    {

        $this->procedimiento = 'ads.ft_adenda_det_sel';
        $this->transaccion = 'ADS_ADENDA_DET_SEL';
        $this->tipo_procedimiento = 'SEL';

        $this->setParametro('id_adenda', 'id_adenda', 'int4');

        $this->captura('id_adenda_det', 'int4');
        $this->captura('id_adenda', 'int4');
        $this->captura('id_obligacion_det', 'int4');
        $this->captura('estado_reg', 'varchar');
        $this->captura('id_partida', 'int4');
        $this->captura('nombre_partida', 'text');
        $this->captura('id_concepto_ingas', 'int4');
        $this->captura('nombre_ingas', 'text');
        $this->captura('monto_pago_mo', 'numeric');
        $this->captura('monto_comprometer', 'numeric');
        $this->captura('monto_descomprometer', 'numeric');
        $this->captura('id_obligacion_pago', 'int4');
        $this->captura('id_centro_costo', 'int4');
        $this->captura('codigo_cc', 'text');
        $this->captura('monto_pago_mb', 'numeric');
        $this->captura('factor_porcentual', 'numeric');
        $this->captura('id_partida_ejecucion_com', 'int4');
        $this->captura('fecha_reg', 'timestamp');
        $this->captura('id_usuario_reg', 'int4');
        $this->captura('fecha_mod', 'timestamp');
        $this->captura('id_usuario_mod', 'int4');
        $this->captura('usr_reg', 'varchar');
        $this->captura('usr_mod', 'varchar');
        $this->captura('descripcion', 'text');
        $this->captura('id_orden_trabajo', 'int4');
        $this->captura('desc_orden', 'varchar');
        $this->captura('monto_pago_sg_mo', 'numeric');
        $this->captura('monto_pago_sg_mb', 'numeric');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function insertar()
    {

        $this->procedimiento = 'ads.ft_adenda_det_ime';
        $this->transaccion = 'ADS_ADENDA_DET_INS';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_adenda', 'id_adenda', 'int4');
        $this->setParametro('estado_reg', 'estado_reg', 'varchar');
        $this->setParametro('id_cuenta', 'id_cuenta', 'int4');
        $this->setParametro('id_partida', 'id_partida', 'int4');
        $this->setParametro('id_auxiliar', 'id_auxiliar', 'int4');
        $this->setParametro('id_concepto_ingas', 'id_concepto_ingas', 'int4');
        $this->setParametro('monto_pago_mo', 'monto_pago_mo', 'numeric');
        $this->setParametro('id_obligacion_pago', 'id_obligacion_pago', 'int4');
        $this->setParametro('id_centro_costo', 'id_centro_costo', 'int4');
        $this->setParametro('factor_porcentual', 'factor_porcentual', 'numeric');
        $this->setParametro('id_partida_ejecucion_com', 'id_partida_ejecucion_com', 'int4');
        $this->setParametro('descripcion', 'descripcion', 'text');
        $this->setParametro('id_orden_trabajo', 'id_orden_trabajo', 'int4');
        $this->setParametro('monto_pago_sg_mo', 'monto_pago_sg_mo', 'numeric');
        $this->setParametro('id_obligacion_det', 'id_obligacion_det', 'int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function modificar()
    {

        $this->procedimiento = 'ads.ft_adenda_det_ime';
        $this->transaccion = 'ADS_ADENDA_DET_MOD';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_adenda_det', 'id_adenda_det', 'int4');
        $this->setParametro('id_adenda', 'id_adenda', 'int4');
        $this->setParametro('estado_reg', 'estado_reg', 'varchar');
        $this->setParametro('id_cuenta', 'id_cuenta', 'int4');
        $this->setParametro('id_partida', 'id_partida', 'int4');
        $this->setParametro('id_auxiliar', 'id_auxiliar', 'int4');
        $this->setParametro('id_concepto_ingas', 'id_concepto_ingas', 'int4');
        $this->setParametro('monto_pago_mo', 'monto_pago_mo', 'numeric');
        $this->setParametro('id_obligacion_pago', 'id_obligacion_pago', 'int4');
        $this->setParametro('id_centro_costo', 'id_centro_costo', 'int4');
        $this->setParametro('factor_porcentual', 'factor_porcentual', 'numeric');
        $this->setParametro('id_partida_ejecucion_com', 'id_partida_ejecucion_com', 'int4');
        $this->setParametro('descripcion', 'descripcion', 'text');
        $this->setParametro('id_orden_trabajo', 'id_orden_trabajo', 'int4');
        $this->setParametro('monto_pago_sg_mo', 'monto_pago_sg_mo', 'numeric'); //#19
        $this->setParametro('id_obligacion_det', 'id_obligacion_det', 'int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function eliminar()
    {

        $this->procedimiento = 'ads.ft_adenda_det_ime';
        $this->transaccion = 'ADS_ADENDA_DET_ELI';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_adenda_det', 'id_adenda_det', 'int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function adendaDetalleRpt()
    {
        $this->procedimiento = 'ads.f_reporte_adenda_det';
        $this->transaccion = 'ADS_RPT_DETALLE';
        $this->tipo_procedimiento = 'SEL';

        $this->setCount(false);

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('centro_costos', 'varchar');
        $this->captura('nombre_partida', 'varchar');
        $this->captura('monto_anterior', 'numeric');
        $this->captura('nuevo_monto', 'numeric');
        $this->captura('monto_operacion', 'numeric');
        $this->captura('estado', 'varchar');
        $this->captura('descripcion', 'varchar');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function presupuestoDetalleRpt()
    {
        $this->procedimiento = 'ads.f_reporte_adenda_det';
        $this->transaccion = 'ADS_RPT_PRESU';
        $this->tipo_procedimiento = 'SEL';

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('techo', 'varchar');
        $this->captura('centro_costo', 'varchar');
        $this->captura('nombre_partida', 'varchar');
        $this->captura('monto_operacion', 'numeric');
        $this->captura('disponible', 'varchar');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

}

?>