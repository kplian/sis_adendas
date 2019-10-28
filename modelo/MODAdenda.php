<?php
/**
 * @package pXP
 * @file (valvarado)
 * @date 24-06-2019 15:15:06
 * @description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODAdenda extends MODbase
{

    function __construct(CTParametro $pParam)
    {
        parent::__construct($pParam);
    }

    function listar()
    {

        $this->procedimiento = 'ads.ft_adendas_sel';
        $this->transaccion = 'ADS_ADENDA_SEL';
        $this->tipo_procedimiento = 'SEL';

        $this->captura('id_adenda', 'int4');
        $this->captura('id_obligacion_pago', 'int4');
        $this->captura('id_estado_wf', 'int4');
        $this->captura('id_proceso_wf', 'int4');
        $this->captura('id_funcionario', 'int4');
        $this->captura('id_depto', 'int4');
        $this->captura('num_tramite', 'varchar');
        $this->captura('total_pago', 'numeric');
        $this->captura('estado_reg', 'varchar');
        $this->captura('estado', 'varchar');
        $this->captura('nueva_fecha_fin', 'date');
        $this->captura('observacion', 'varchar');
        $this->captura('numero', 'varchar');
        $this->captura('nombre_depto', 'varchar');
        $this->captura('numero_contrato', 'varchar');
        $this->captura('desc_funcionario1', 'text');
        $this->captura('tipo', 'varchar');
        $this->captura('numero_adenda', 'varchar');
        $this->captura('id_contrato_adenda', 'int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function insertar()
    {

        $this->procedimiento = 'ads.ft_adendas_ime';
        $this->transaccion = 'ADS_ADENDA_INS';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('id_gestion', 'id_gestion', 'int4');
        $this->setParametro('id_contrato', 'id_contrato', 'int4');
        $this->setParametro('numero', 'numero', 'varchar');
        $this->setParametro('observacion', 'observacion', 'varchar');
        $this->setParametro('nueva_fecha_fin', 'nueva_fecha_fin', 'date');
        $this->setParametro('numero', 'numero', 'varchar');
        $this->setParametro('observacion', 'observacion', 'varchar');
        $this->setParametro('id_contrato_adenda', 'id_contrato_adenda', 'int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function modificar()
    {

        $this->procedimiento = 'ads.ft_adendas_ime';
        $this->transaccion = 'ADS_ADENDA_MOD';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('id_adenda', 'id_adenda', 'int4');
        $this->setParametro('nueva_fecha_fin', 'nueva_fecha_fin', 'date');
        $this->setParametro('numero', 'numero', 'varchar');
        $this->setParametro('observacion', 'observacion', 'varchar');
        $this->setParametro('id_contrato_adenda', 'id_contrato_adenda', 'int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function eliminar()
    {

        $this->procedimiento = 'ads.ft_adendas_ime';
        $this->transaccion = 'ADS_ADENDA_ELI';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_adenda', 'id_adenda', 'int4');
        $this->setParametro('id_obligacion_pago', 'id_obligacion_pago', 'int4');
        $this->setParametro('id_proceso_wf_act', 'id_proceso_wf_act', 'int4');
        $this->setParametro('id_estado_wf_act', 'id_estado_wf_act', 'int4');
        $this->setParametro('id_funcionario_usu', 'id_funcionario_usu', 'int4');
        $this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');
        $this->setParametro('id_funcionario_wf', 'id_funcionario_wf', 'int4');
        $this->setParametro('id_depto_wf', 'id_depto_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');
        $this->setParametro('json_procesos', 'json_procesos', 'text');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function clonarObligacion()
    {
        $this->procedimiento = 'ads.ft_adendas_ime';
        $this->transaccion = 'ADS_ADENDA_CLONAR';
        $this->tipo_procedimiento = 'IME';
        $this->setParametro('id_obligacion_pago', 'id_obligacion_pago', 'int4');
        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('nueva_fecha_fin', 'nueva_fecha_fin', 'date');
        $this->setParametro('numero', 'numero', 'varchar');
        $this->setParametro('observacion', 'observacion', 'varchar');
        $this->setParametro('id_contrato_adenda', 'id_contrato_adenda', 'int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function siguienteEstado()
    {

        $this->procedimiento = 'ads.ft_adendas_ime';
        $this->transaccion = 'ADS_ADENDA_SiGEST';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_adenda', 'id_adenda', 'int4');
        $this->setParametro('id_proceso_wf_act', 'id_proceso_wf_act', 'int4');
        $this->setParametro('id_estado_wf_act', 'id_estado_wf_act', 'int4');
        $this->setParametro('id_funcionario_usu', 'id_funcionario_usu', 'int4');
        $this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');
        $this->setParametro('id_funcionario_wf', 'id_funcionario_wf', 'int4');
        $this->setParametro('id_depto_wf', 'id_depto_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');
        $this->setParametro('json_procesos', 'json_procesos', 'text');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function encontraPorId()
    {

        $this->procedimiento = 'ads.ft_adendas_sel';
        $this->transaccion = 'ADS_ADENDA_SEL';
        $this->tipo_procedimiento = 'SEL';

        $this->setCount(false);

        $this->captura('id_adenda', 'int4');
        $this->captura('id_obligacion_pago', 'int4');
        $this->captura('id_estado_wf', 'int4');
        $this->captura('id_proceso_wf', 'int4');
        $this->captura('id_funcionario', 'int4');
        $this->captura('id_depto', 'int4');
        $this->captura('num_tramite', 'varchar');
        $this->captura('total_pago', 'numeric');
        $this->captura('estado_reg', 'varchar');
        $this->captura('estado', 'varchar');
        $this->captura('nueva_fecha_fin', 'date');
        $this->captura('observacion', 'varchar');
        $this->captura('numero', 'varchar');
        $this->captura('nombre_depto', 'varchar');
        $this->captura('numero_contrato', 'varchar');
        $this->captura('desc_funcionario1', 'text');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function obtener()
    {
        $this->procedimiento = 'ads.f_reporte_adendas';
        $this->transaccion = 'ADS_RPT_AD';
        $this->tipo_procedimiento = 'SEL';

        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');

        $this->captura('numero', 'varchar');
        $this->captura('num_tramite', 'varchar');
        $this->captura('numero_contrato', 'varchar');
        $this->captura('estado_reg', 'varchar');
        $this->captura('nueva_fecha_fin', 'date');
        $this->captura('observacion', 'varchar');
        $this->captura('descripcion', 'varchar');
        $this->captura('fecha_mod', 'timestamp');
        $this->captura('estado', 'varchar');
        $this->captura('nombre_depto', 'varchar');
        $this->captura('total_pago', 'numeric');
        $this->captura('desc_funcionario1', 'text');

        $this->armarConsulta();
        $this->ejecutarConsulta();;
        return $this->respuesta;
    }
}

?>