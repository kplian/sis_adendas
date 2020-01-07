CREATE OR REPLACE FUNCTION ads.ft_adenda_det_sel(p_administrador INTEGER,
                                                 p_id_usuario INTEGER,
                                                 p_tabla VARCHAR,
                                                 p_transaccion VARCHAR)
    RETURNS VARCHAR AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Adendas
 FUNCION: 		tes.ft_adenda_det_sel
 DESCRIPCION:   Función que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ads.adenda_det'
 AUTOR: 		valvarado
 FECHA:	        02-04-2013 20:27:35
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
Issue			Fecha        Author				Descripcion
***************************************************************************/

DECLARE

    v_consulta       VARCHAR;
    v_parametros     RECORD;
    v_nombre_funcion TEXT;
    v_resp           VARCHAR;

BEGIN

    v_nombre_funcion = 'tes.ft_adenda_det_sel';
    v_parametros = pxp.f_get_record(p_tabla);

    IF (p_transaccion = 'ADS_AD_DET_SEL')
    THEN

        BEGIN

            v_consulta := 'select adt.id_adenda_det,
                           adt.id_adenda,
                           adt.id_obligacion_det,
                           adt.estado_reg,
                           adt.id_partida,
                           par.nombre_partida || ''-('' || par.codigo || '')''           as nombre_partida,
                           adt.id_concepto_ingas,
                           cig.desc_ingas || ''-('' || cig.movimiento || '')''           as nombre_ingas,
                           adt.monto_pago_mo,
                           adt.monto_comprometer,
                           adt.monto_descomprometer,
                           adt.id_obligacion_pago,
                           adt.id_centro_costo,
                           cc.codigo_cc,
                           adt.monto_pago_mb,
                           adt.factor_porcentual,
                           (case when adt.id_partida_ejecucion_com is not null then adt.id_partida_ejecucion_com else adt.id_partida_ejecucion_com_ad end) as id_partida_ejecucion_com,
                           adt.fecha_reg,
                           adt.id_usuario_reg,
                           adt.fecha_mod,
                           adt.id_usuario_mod,
                           usu1.cuenta                                               as usr_reg,
                           usu2.cuenta                                               as usr_mod,
                           adt.descripcion,
                           ot.id_orden_trabajo,
                           ot.desc_orden,
                           adt.monto_pago_sg_mo,
                           adt.monto_pago_sg_mb,
                           adt.precio_unitario,
                           adt.cantidad_adjudicada
                    from ads.tadenda_det adt
                             inner join segu.tusuario usu1 on usu1.id_usuario = adt.id_usuario_reg
                             left join segu.tusuario usu2 on usu2.id_usuario = adt.id_usuario_mod
                             left join pre.tpartida par on par.id_partida = adt.id_partida
                             left join param.tconcepto_ingas cig on cig.id_concepto_ingas = adt.id_concepto_ingas
                             left join param.vcentro_costo cc on cc.id_centro_costo = adt.id_centro_costo
                             left join conta.torden_trabajo ot on ot.id_orden_trabajo = adt.id_orden_trabajo
                            where adt.estado_reg = ''activo'' and adt.id_adenda=' || v_parametros.id_adenda || ' and ';

            v_consulta := v_consulta || v_parametros.filtro;
            v_consulta :=
                                        v_consulta || ' order by ' || v_parametros.ordenacion || ' ' ||
                                        v_parametros.dir_ordenacion || ' limit ' ||
                                        v_parametros.cantidad || ' offset ' || v_parametros.puntero;

            RETURN v_consulta;

        END;

    ELSIF (p_transaccion = 'ADS_AD_DET_CONT')
    THEN

        BEGIN
            v_consulta := 'select count(id_obligacion_det)
                                            from ads.tadenda_det obdet
                                            inner join segu.tusuario usu1 on usu1.id_usuario = obdet.id_usuario_reg
                                              left join segu.tusuario usu2 on usu2.id_usuario = obdet.id_usuario_mod
                                  left join pre.tpartida par on par.id_partida=obdet.id_partida
                                  left join param.tconcepto_ingas cig on cig.id_concepto_ingas=obdet.id_concepto_ingas
                                  left join param.vcentro_costo cc on cc.id_centro_costo=obdet.id_centro_costo
                                  left join conta.torden_trabajo ot on ot.id_orden_trabajo = obdet.id_orden_trabajo
                                  where obdet.estado_reg = ''activo'' and  obdet.id_obligacion_pago=' ||
                          v_parametros.id_adenda || ' and ';

            v_consulta := v_consulta || v_parametros.filtro;

            RETURN v_consulta;

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