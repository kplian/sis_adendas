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
        $this->transaccion = 'ADS_AD_SEL';
        $this->tipo_procedimiento = 'SEL';

        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('nombreVista', 'nombreVista', 'varchar');

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
        $this->captura('fecha_entrega', 'date');
        $this->captura('observacion', 'varchar');
        $this->captura('numero', 'varchar');
        $this->captura('numero_modificatorio', 'varchar');
        $this->captura('fecha_informe', 'date');
        $this->captura('lugar_entrega', 'varchar');
        $this->captura('forma_pago', 'varchar');
        $this->captura('glosa', 'varchar');
        $this->captura('nombre_depto', 'varchar');
        $this->captura('numero_contrato', 'varchar');
        $this->captura('desc_funcionario1', 'text');
        $this->captura('tipo', 'varchar');
        $this->captura('numero_adenda', 'varchar');
        $this->captura('id_contrato_adenda', 'int4');
        $this->captura('id_tipo', 'int4');
        $this->captura('descripcion', 'varchar');
        $this->captura('codigo', 'varchar');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function insertar()
    {

        $this->procedimiento = 'ads.ft_adendas_ime';
        $this->transaccion = 'ADS_AD_INS';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('id_gestion', 'id_gestion', 'int4');
        $this->setParametro('id_contrato', 'id_contrato', 'int4');
        $this->setParametro('numero', 'numero', 'varchar');
        $this->setParametro('observacion', 'observacion', 'varchar');
        $this->setParametro('fecha_entrega', 'fecha_entrega', 'date');
        $this->setParametro('numero', 'numero', 'varchar');
        $this->setParametro('observacion', 'observacion', 'varchar');
        $this->setParametro('id_contrato_adenda', 'id_contrato_adenda', 'int4');
        $this->setParametro('id_tipo', 'id_tipo', 'int4');
        $this->setParametro('fecha_informe', 'fecha_informe', 'date');
        $this->setParametro('numero_modificatorio', 'numero_modificatorio', 'varchar');
        $this->setParametro('lugar_entrega', 'lugar_entrega', 'varchar');
        $this->setParametro('forma_pago', 'forma_pago', 'varchar');
        $this->setParametro('glosa', 'glosa', 'varchar');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function modificar()
    {

        $this->procedimiento = 'ads.ft_adendas_ime';
        $this->transaccion = 'ADS_AD_MOD';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('id_adenda', 'id_adenda', 'int4');
        $this->setParametro('fecha_entrega', 'fecha_entrega', 'date');
        $this->setParametro('numero', 'numero', 'varchar');
        $this->setParametro('observacion', 'observacion', 'varchar');
        $this->setParametro('id_tipo', 'id_tipo', 'int4');
        $this->setParametro('fecha_informe', 'fecha_informe', 'date');
        $this->setParametro('numero_modificatorio', 'numero_modificatorio', 'varchar');
        $this->setParametro('lugar_entrega', 'lugar_entrega', 'varchar');
        $this->setParametro('forma_pago', 'forma_pago', 'varchar');
        $this->setParametro('glosa', 'glosa', 'varchar');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function eliminar()
    {

        $this->procedimiento = 'ads.ft_adendas_ime';
        $this->transaccion = 'ADS_AD_ELI';
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
        $this->transaccion = 'ADS_AD_CLONAR';
        $this->tipo_procedimiento = 'IME';
        $this->setParametro('id_obligacion_pago', 'id_obligacion_pago', 'int4');
        $this->setParametro('id_funcionario', 'id_funcionario', 'int4');
        $this->setParametro('fecha_entrega', 'fecha_entrega', 'date');
        $this->setParametro('numero', 'numero', 'varchar');
        $this->setParametro('observacion', 'observacion', 'varchar');
        $this->setParametro('id_contrato_adenda', 'id_contrato_adenda', 'int4');
        $this->setParametro('id_tipo', 'id_tipo', 'int4');
        $this->setParametro('fecha_informe', 'fecha_informe', 'date');
        $this->setParametro('numero_modificatorio', 'numero_modificatorio', 'varchar');
        $this->setParametro('lugar_entrega', 'lugar_entrega', 'varchar');
        $this->setParametro('forma_pago', 'forma_pago', 'varchar');
        $this->setParametro('glosa', 'glosa', 'varchar');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function siguienteEstado()
    {

        $this->procedimiento = 'ads.ft_adendas_ime';
        $this->transaccion = 'ADS_AD_SiGEST';
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

    function anteriorEstado()
    {

        $this->procedimiento = 'ads.ft_adendas_ime';
        $this->transaccion = 'ADS_AD_ANTEST';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_adenda', 'id_adenda', 'int4');
        $this->setParametro('id_proceso_wf_act', 'id_proceso_wf_act', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
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
        $this->transaccion = 'ADS_AD_SEL';
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
        $this->captura('fecha_entrega', 'date');
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
        $this->captura('fecha_entrega', 'date');
        $this->captura('observacion', 'varchar');
        $this->captura('numero', 'varchar');
        $this->captura('numero_modificatorio', 'varchar');
        $this->captura('fecha_informe', 'date');
        $this->captura('lugar_entrega', 'varchar');
        $this->captura('forma_pago', 'varchar');
        $this->captura('glosa', 'varchar');
        $this->captura('nombre_depto', 'varchar');
        $this->captura('numero_contrato', 'varchar');
        $this->captura('desc_funcionario1', 'text');
        $this->captura('tipo', 'varchar');
        $this->captura('numero_adenda', 'varchar');
        $this->captura('id_contrato_adenda', 'int4');
        $this->captura('id_tipo', 'int4');
        $this->captura('descripcion', 'varchar');
        $this->captura('codigo', 'varchar');
        $this->captura('rotulo_comercial', 'varchar');
        $this->captura('direccion', 'varchar');
        $this->captura('correo_contacto', 'varchar');
        $this->captura('funcionario_contacto', 'varchar');

        $this->armarConsulta();
        $this->ejecutarConsulta();;
        return $this->respuesta;
    }
}

?>