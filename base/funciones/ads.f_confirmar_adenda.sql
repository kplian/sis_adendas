CREATE OR REPLACE FUNCTION ads.f_confirmar_adenda (
  p_id_adenda integer
)
RETURNS varchar AS
$body$
declare
    v_nombre_funcion     varchar = 'ads.f_aprobar_adenda';
    v_resp               varchar;
    v_total_pago         numeric;
    v_nueva_fecha        date;
    v_registros          record;
    v_id_obligacion_pago integer;
begin

    select total_pago, fecha_entrega, id_obligacion_pago
    into v_total_pago, v_nueva_fecha , v_id_obligacion_pago
    from ads.tadendas
    where id_adenda = p_id_adenda;

    for v_registros in (select *
                        from ads.tadenda_det
                        where id_adenda = p_id_adenda)
        loop
            if v_registros.id_obligacion_det is null then

                insert into tes.tobligacion_det (id_usuario_reg,
                                                 id_usuario_mod,
                                                 fecha_reg,
                                                 fecha_mod,
                                                 estado_reg,
                                                 id_usuario_ai,
                                                 usuario_ai,
                                                 id_obligacion_pago,
                                                 id_concepto_ingas,
                                                 id_centro_costo,
                                                 id_partida,
                                                 id_partida_ejecucion_com,
                                                 descripcion,
                                                 monto_pago_mo,
                                                 monto_pago_mb,
                                                 factor_porcentual,
                                                 id_orden_trabajo,
                                                 monto_pago_sg_mo,
                                                 monto_pago_sg_mb)
                values (v_registros.id_usuario_reg,
                        v_registros.id_usuario_mod,
                        v_registros.fecha_reg,
                        v_registros.fecha_mod,
                        v_registros.estado_reg,
                        v_registros.id_usuario_ai,
                        v_registros.usuario_ai,
                        v_id_obligacion_pago,
                        v_registros.id_concepto_ingas,
                        v_registros.id_centro_costo,
                        v_registros.id_partida,
                        v_registros.id_partida_ejecucion_com_ad,
                        v_registros.descripcion,
                        v_registros.monto_pago_mo,
                        v_registros.monto_pago_mb,
                        v_registros.factor_porcentual,
                        v_registros.id_orden_trabajo,
                        COALESCE(v_registros.monto_pago_sg_mo, 0),
                        COALESCE(v_registros.monto_pago_sg_mb, 0));
            else

                update tes.tobligacion_det
                set id_usuario_reg           = v_registros.id_usuario_reg,
                    id_usuario_mod           = v_registros.id_usuario_mod,
                    fecha_reg                = v_registros.fecha_reg,
                    fecha_mod                = v_registros.fecha_mod,
                    estado_reg               = v_registros.estado_reg,
                    id_usuario_ai            = v_registros.id_usuario_ai,
                    usuario_ai               = v_registros.usuario_ai,
                    id_concepto_ingas        = v_registros.id_concepto_ingas,
                    id_centro_costo          = v_registros.id_centro_costo,
                    id_partida               = v_registros.id_partida,
                    id_partida_ejecucion_com = v_registros.id_partida_ejecucion_com,
                    descripcion              = v_registros.descripcion,
                    monto_pago_mo            = v_registros.monto_pago_mo,
                    monto_pago_mb            = v_registros.monto_pago_mb,
                    factor_porcentual        = v_registros.factor_porcentual,
                    id_orden_trabajo         = v_registros.id_orden_trabajo,
                    monto_pago_sg_mo         = v_registros.monto_pago_sg_mo,
                    monto_pago_sg_mb         = v_registros.monto_pago_sg_mb
                where id_obligacion_det = v_registros.id_obligacion_det;
            end if;
        end loop;

    update tes.tobligacion_pago set total_pago=v_total_pago where id_obligacion_pago = v_id_obligacion_pago;

    return v_resp;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimiento', v_nombre_funcion);
        raise exception '%', v_resp;
end ;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION ads.f_confirmar_adenda (p_id_adenda integer)
  OWNER TO postgres;