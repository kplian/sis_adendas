create or replace function ads.f_bloquear_obligacion_pago(p_id_obligacion_pago integer, p_bloquear integer DEFAULT 0) returns boolean
    language plpgsql
as
$body$
declare
    v_nombre_funcion  varchar = 'ads.f_calcular_montos';
    v_resp            varchar;
    v_monto_actual    numeric;
    v_monto_prorrateo numeric;
    v_monto_pendiente numeric;

begin

    select opd.monto_pago_mo
    into v_monto_actual
    from tes.tobligacion_det opd
    where opd.id_obligacion_det = p_id_obligacion_det;

    p_monto_descomprometer = 0;
    p_monto_comprometer = 0;
    v_monto_prorrateo = ads.f_obtener_total_monto_prorrateo(p_id_obligacion_det);
    v_monto_pendiente = (v_monto_actual - v_monto_prorrateo);

    if v_monto_actual > p_nuevo_monto then
        p_monto_comprometer = 0;
        p_monto_descomprometer = v_monto_actual - p_nuevo_monto;
    elsif v_monto_actual < p_nuevo_monto then
        p_monto_comprometer = p_nuevo_monto - v_monto_actual;
        p_monto_descomprometer = 0;
    end if;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
end;
$body$;

alter function ads.f_bloquear_obligacion_pago(integer, integer) owner to postgres;

