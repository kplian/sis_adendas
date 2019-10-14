create or replace function ads.f_obtener_total_monto_prorrateo(p_id_obligacion_pago_det INTEGER)
    RETURNS numeric AS
$BODY$
declare
    v_nombre_funcion  varchar;
    v_resp            varchar;
    v_monto_prorrateo numeric;
begin
    v_nombre_funcion = 'ads.f_obtener_total_monto_prorrateo';

    select coalesce(sum(pr.monto_ejecutar_mo), 0)
    into v_monto_prorrateo
    from tes.tprorrateo pr
    where id_prorrateo_fk is null
      and pr.id_obligacion_det = p_id_obligacion_pago_det;

    return v_monto_prorrateo;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
        raise exception '%',v_resp;
end
$BODY$
    LANGUAGE 'plpgsql'
    VOLATILE
    COST 100;

alter function ads.f_obtener_total_monto_prorrateo( integer ) owner to postgres;