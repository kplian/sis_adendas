CREATE OR REPLACE FUNCTION ads.ft_adendas_sel(p_administrador INTEGER,
                                              p_id_usuario INTEGER,
                                              p_tabla VARCHAR,
                                              p_transaccion VARCHAR)
    RETURNS VARCHAR AS
$BODY$
/**************************************************************************
 SISTEMA:		Adendas
 FUNCION: 		ads.ft_adendas_sel
 DESCRIPCION:   Función que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'ads.tadendas'
 AUTOR: 		 (valvarado)
 FECHA:	        24-06-2019 15:15:06
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
#
 ***************************************************************************/
DECLARE

    v_consulta       varchar;
    v_parametros     record;
    v_nombre_funcion text;
    v_resp           varchar;
    v_filtro         varchar;
BEGIN

    v_nombre_funcion = 'ads.ft_adendas_sel';
    v_parametros = pxp.f_get_record(p_tabla);

    v_filtro = '';
    IF v_parametros.nombreVista = 'AdendasVoBo' then
        v_filtro = ' ad.estado = ''pendiente'' and ';
    end if;

    if p_administrador != 1 then
        v_filtro = ' wf.id_funcionario = ' || v_parametros.id_funcionario || ' and ';
    end if;

    IF (p_transaccion = 'ADS_AD_SEL') THEN
        BEGIN
            v_consulta := 'select ad.id_adenda,
                           ad.id_obligacion_pago,
                           ad.id_estado_wf,
                           ad.id_proceso_wf,
                           ad.id_funcionario,
                           dep.id_depto,
                           ad.num_tramite,
                           COALESCE(ad.total_pago, 0) as total_pago,
                           ad.estado_reg,
                           ad.estado,
                           ad.fecha_entrega,
                           ad.observacion,
                           ad.numero,
                           ad.numero_modificatorio,
                           ad.fecha_informe,
                           ad.lugar_entrega,
                           ad.forma_pago,
                           ad.glosa,
                           dep.nombre                 as nombre_depto,
                           con.numero                 as numero_contrato,
                           fun.desc_funcionario1,
                           sol.tipo,
                           vcon.numero as numero_adenda,
                           ad.id_contrato_adenda,
                           t.id_tipo,
                           t.descripcion,
                           fun.codigo
                    from ads.tadendas ad
                             join wf.testado_wf wf on wf.id_estado_wf = ad.id_estado_wf
                             join ads.ttipos t on t.id_tipo = ad.id_tipo
                             left join tes.tobligacion_pago obpg on obpg.id_obligacion_pago = ad.id_obligacion_pago
                             join segu.tusuario usu1 on usu1.id_usuario = obpg.id_usuario_reg
                             left join segu.tusuario usu2 on usu2.id_usuario = obpg.id_usuario_mod
                             join param.tmoneda mn on mn.id_moneda = obpg.id_moneda
                             join segu.tsubsistema ss on ss.id_subsistema = obpg.id_subsistema
                             join param.tdepto dep on dep.id_depto = obpg.id_depto
                             left join param.vproveedor pv on pv.id_proveedor = obpg.id_proveedor
                             left join leg.tcontrato con on con.id_contrato = obpg.id_contrato
                             left join param.tplantilla pla on pla.id_plantilla = obpg.id_plantilla
                             left join orga.vfuncionario fun on fun.id_funcionario = wf.id_funcionario
                             join adq.tcotizacion cot on cot.id_obligacion_pago = obpg.id_obligacion_pago
                             join adq.tproceso_compra pc on pc.id_proceso_compra = cot.id_proceso_compra
                             join adq.tsolicitud sol on sol.id_solicitud = pc.id_solicitud
                             left join leg.vcontrato vcon on vcon.id_contrato = ad.id_contrato_adenda
                    where ' || v_filtro || ' ad.estado_reg = ''activo'' and ';

            v_consulta := v_consulta || v_parametros.filtro;
            v_consulta := v_consulta || ' order by ' || v_parametros.ordenacion || ' ' ||
                          v_parametros.dir_ordenacion || ' limit ' ||
                          v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            RETURN v_consulta;

        END;
    ELSIF (p_transaccion = 'ADS_AD_CONT') THEN

        BEGIN

            v_consulta := 'select count(id_adenda)
                                            from ads.tadendas ad
                                            join ads.ttipos t on t.id_tipo = ad.id_tipo
                                            left join tes.tobligacion_pago obpg on obpg.id_obligacion_pago = ad.id_obligacion_pago
                                            inner join segu.tusuario usu1 on usu1.id_usuario = obpg.id_usuario_reg
                                            left join segu.tusuario usu2 on usu2.id_usuario = obpg.id_usuario_mod
                                            inner join param.tmoneda mn on mn.id_moneda=obpg.id_moneda
                                            inner join segu.tsubsistema ss on ss.id_subsistema=obpg.id_subsistema
                                            inner join param.tdepto dep on dep.id_depto=obpg.id_depto
                                            left join param.vproveedor pv on pv.id_proveedor=obpg.id_proveedor
                                            left join leg.tcontrato con on con.id_contrato = obpg.id_contrato
                                            left join param.tplantilla pla on pla.id_plantilla = obpg.id_plantilla
                                            left join orga.vfuncionario fun on fun.id_funcionario=obpg.id_funcionario
                                            where ' || v_filtro || ' ad.estado_reg = ''activo'' and ';

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
$BODY$
    LANGUAGE 'plpgsql'
    VOLATILE
    COST 100;

ALTER FUNCTION ads.ft_adendas_sel( INTEGER, INTEGER, CHARACTER VARYING, CHARACTER VARYING )
    OWNER TO postgres;