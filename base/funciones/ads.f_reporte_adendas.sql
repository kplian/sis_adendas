CREATE OR REPLACE FUNCTION ads.f_reporte_adendas (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
declare
    v_nombre_funcion varchar = 'ads.f_reporte_adendas';
    v_resp           varchar;
    v_consulta       varchar;
    v_parametros     record;
    v_id_adenda      integer;
begin
    v_parametros = pxp.f_get_record(p_tabla);

    select id_adenda into v_id_adenda from ads.tadendas where id_proceso_wf = v_parametros.id_proceso_wf;

    if (p_transaccion = 'ADS_RPT_DETALLE') then
        v_consulta = 'select adt.centro_costos::varchar, adt.nombre_partida::varchar, adt.precio_total::numeric
                        from ads.detalle_adenda adt
                        where adt.id_adenda = ' || v_id_adenda;
        return v_consulta;
    elsif (p_transaccion = 'ADS_RPT_PRESU') then
        v_consulta = 'select centro_costo, nombre_partida, SUM(monto_anterior) monto_anterior ,sum(monto_operacion) monto_operacion, disponible, estado
                        from ads.f_verificar_presupuesto(' || v_id_adenda || ')
                            GROUP BY centro_costo, nombre_partida, disponible,estado
                            HAVING estado is not null';
        return v_consulta;
    elsif (p_transaccion = 'ADS_RPT_AD') then
        v_consulta = 'select ad.id_adenda,
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
                           fun.codigo,
                           pv.rotulo_comercial,
                           inst.direccion,
                           cot.correo_contacto,
                           cot.funcionario_contacto,
                            mon.moneda
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
                             left join param.tinstitucion inst on inst.id_institucion = pv.id_institucion
                             left join leg.tcontrato con on con.id_contrato = obpg.id_contrato
                             left join param.tplantilla pla on pla.id_plantilla = obpg.id_plantilla
                             left join orga.vfuncionario fun on fun.id_funcionario = wf.id_funcionario
                             join adq.tcotizacion cot on cot.id_obligacion_pago = obpg.id_obligacion_pago
                             join adq.tproceso_compra pc on pc.id_proceso_compra = cot.id_proceso_compra
                             join adq.tsolicitud sol on sol.id_solicitud = pc.id_solicitud
                             left join leg.vcontrato vcon on vcon.id_contrato = ad.id_contrato_adenda
                             inner join param.tmoneda mon on mon.id_moneda = sol.id_moneda
                     WHERE ad.id_adenda=' || v_id_adenda;
        return v_consulta;
    elsif (p_transaccion = 'ADS_RPT_AD_DET') then
        v_consulta = ' ';
        return v_consulta;
    end if;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
        raise exception '%', v_resp;
end;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION ads.f_reporte_adendas (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;