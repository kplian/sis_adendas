CREATE OR REPLACE FUNCTION ads.ft_obligacion_pago_sel(p_administrador INTEGER,
                                                      p_id_usuario INTEGER,
                                                      p_tabla VARCHAR,
                                                      p_transaccion VARCHAR)
    RETURNS VARCHAR AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Adendas
 FUNCION: 		tes.ft_obligacion_pago_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ads.adenda_det'
 AUTOR: 		valvarado
 FECHA:	        02-04-2013 20:27:35
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
Issue			Fecha        Author				Descripcion
***************************************************************************/

DECLARE

    v_consulta                VARCHAR;
    v_parametros              RECORD;
    v_nombre_funcion          TEXT;
    v_resp                    VARCHAR;
    v_adenda_existente        VARCHAR;
    v_obligacion_estado       VARCHAR;
    v_obligacion_no_bloqueada boolean;
BEGIN

    v_nombre_funcion = 'ads.ft_obligacion_pago_sel';
    v_parametros = pxp.f_get_record(p_tabla);

    IF (p_transaccion = 'OBTENER_POR_ID') THEN

        BEGIN
            v_adenda_existente = ads.f_validar_existe_adenda(v_parametros.id_obligacion_pago);
            v_obligacion_estado = ads.f_validar_estado_op(v_parametros.id_obligacion_pago);
            v_obligacion_no_bloqueada = ads.f_validar_obligacion_pago_no_bloqueado(v_parametros.id_obligacion_pago);
            v_obligacion_no_bloqueada = ads.f_validar_estado_plan_pago(v_parametros.id_obligacion_pago);

            v_consulta := 'select obpg.id_obligacion_pago,
                           obpg.id_gestion,
                           obpg.id_contrato,
                           obpg.estado,
                           dep.nombre                 as nombre_depto,
                           obpg.num_tramite,
                           fun.id_funcionario,
                           fun.desc_funcionario1,
                           pv.desc_proveedor          as proveedor,
                           obpg.total_pago,
                           COALESCE(con.numero, '''') as numero_contrato,
                           obpg.numero                as numero_orden,
                           con.fecha_fin,
                           sol.fecha_soli,
                           fun.codigo                 as codigo_fun,
                           cot.lugar_entrega,
                           cot.fecha_entrega,
                           cot.forma_pago,
                           cot.obs
                    from tes.tobligacion_pago obpg
                             inner join segu.tusuario usu1 on usu1.id_usuario = obpg.id_usuario_reg
                             left join segu.tusuario usu2 on usu2.id_usuario = obpg.id_usuario_mod
                             inner join param.tmoneda mn on mn.id_moneda = obpg.id_moneda
                             inner join segu.tsubsistema ss on ss.id_subsistema = obpg.id_subsistema
                             inner join param.tdepto dep on dep.id_depto = obpg.id_depto
                             left join param.vproveedor pv on pv.id_proveedor = obpg.id_proveedor
                             left join leg.tcontrato con on con.id_contrato = obpg.id_contrato
                             left join param.tplantilla pla on pla.id_plantilla = obpg.id_plantilla
                             left join orga.vfuncionario fun on fun.id_funcionario = obpg.id_funcionario
                             left join orga.vfuncionario fresp ON fresp.id_funcionario = obpg.id_funcionario_responsable
                             inner join adq.tcotizacion cot on cot.id_obligacion_pago = obpg.id_obligacion_pago
                             inner join adq.tproceso_compra pc on pc.id_proceso_compra = cot.id_proceso_compra
                             inner join adq.tsolicitud sol on sol.id_solicitud = pc.id_solicitud
                              where  obpg.id_obligacion_pago=' || v_parametros.id_obligacion_pago;

            return v_consulta;
        END;
    ELSE

        RAISE EXCEPTION 'Transaccion inexistente';

    END IF;

EXCEPTION

    WHEN OTHERS
        THEN
            v_resp = '';
            v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
            v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
            v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
            RAISE EXCEPTION '%', v_resp;
END;
$body$
    LANGUAGE 'plpgsql'
    VOLATILE
    CALLED ON NULL INPUT
    SECURITY INVOKER
    COST 100;