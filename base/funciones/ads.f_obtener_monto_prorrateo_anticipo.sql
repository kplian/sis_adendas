create or replace function ads.f_obtener_monto_prorrateo_anticipo(p_id_obligacion_pago integer, p_id_obligacion_det integer) returns numeric
    language plpgsql
as
$$
declare
    v_nombre_funcion                  varchar;
    v_resp                            varchar;
    v_monto_anticipo                  numeric;
    v_importe_total                   numeric;
    v_num_tramite                     varchar;
    v_monto_factor_prorrateo_anticipo numeric;
    v_monto_detalle                   numeric;
begin
    v_nombre_funcion = 'ads.f_obtener_monto_prorrateo_anticipo';

    select obp.total_pago, obp.num_tramite
    into v_importe_total, v_num_tramite
    from tes.tobligacion_pago obp
    where obp.id_obligacion_pago = p_id_obligacion_pago;

    select sum(monto_anticipo) as monto_anticipo
    into v_monto_anticipo
    from pre.tpartida_ejecucion pe
    where pe.nro_tramite = v_num_tramite;

    select monto_pago_mo into v_monto_detalle from tes.tobligacion_det where id_obligacion_det = p_id_obligacion_det;

    v_monto_factor_prorrateo_anticipo = v_monto_anticipo / v_importe_total;

    return v_monto_detalle * v_monto_factor_prorrateo_anticipo;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
        raise exception '%',v_resp;
end
$$;

alter function ads.f_obtener_monto_prorrateo_anticipo(integer, integer) owner to postgres;

