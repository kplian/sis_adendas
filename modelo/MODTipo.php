<?php
/**
 * @package pXP
 * @file (valvarado)
 * @date 24-06-2019 15:15:06
 * @description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODTipo extends MODbase
{

    function __construct(CTParametro $pParam)
    {
        parent::__construct($pParam);
    }

    function listar()
    {

        $this->procedimiento = 'ads.ft_tipos_sel';
        $this->transaccion = 'ADS_TIPOS_SEL';
        $this->tipo_procedimiento = 'SEL';

        $this->captura('id_tipo', 'int4');
        $this->captura('codigo', 'varchar');
        $this->captura('descripcion', 'varchar');
        $this->captura('estado_reg', 'varchar');
        $this->captura('fecha_reg', 'timestamp');
        $this->captura('fecha_mod', 'timestamp');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function insertar()
    {

        $this->procedimiento = 'ads.ft_tipos_ime';
        $this->transaccion = 'ADS_TIPOS_INS';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_tipo', 'id_tipo', 'int4');
        $this->setParametro('codigo', 'codigo', 'varchar');
        $this->setParametro('descripcion', 'descripcion', 'varchar');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function modificar()
    {

        $this->procedimiento = 'ads.ft_tipos_ime';
        $this->transaccion = 'ADS_TIPOS_MOD';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_tipo', 'id_tipo', 'int4');
        $this->setParametro('codigo', 'codigo', 'varchar');
        $this->setParametro('descripcion', 'descripcion', 'varchar');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }

    function eliminar()
    {

        $this->procedimiento = 'ads.ft_tipos_ime';
        $this->transaccion = 'ADS_TIPOS_ELI';
        $this->tipo_procedimiento = 'IME';

        $this->setParametro('id_tipo', 'id_tipo', 'int4');

        $this->armarConsulta();
        $this->ejecutarConsulta();

        return $this->respuesta;
    }
}

?>