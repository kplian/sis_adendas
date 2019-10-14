create or replace function ads.f_validar_nuevo_monto(p_id_obligacion_pago integer, p_id_obligaciion_det integer,
                                                     nuevo_monto numeric) returns numeric
    language plpgsql
as
$body$
declare
    v_nombre_funcion           varchar;
    v_resp                     varchar;
    v_total_prorrateo          numeric;
    v_total_limite_monto       numeric;
    v_total_prorrateo_anticipo numeric;
begin
    v_nombre_funcion = 'ads.f_validar_nuevo_monto';

    v_total_prorrateo = ads.f_obtener_total_monto_prorrateo(p_id_obligaciion_det);
    v_total_prorrateo_anticipo = ads.f_obtener_monto_prorrateo_anticipo(p_id_obligacion_pago, p_id_obligaciion_det);

    v_total_limite_monto = abs(v_total_prorrateo - v_total_prorrateo_anticipo);

    if (nuevo_monto <= v_total_limite_monto) then
        raise exception 'El nuevo monto no puede ser menor al monto prorrateado: (Monto Prorrateado: %)', trim(to_char(v_total_limite_monto, '99999999999999999D99'));
    end if;
    return v_total_prorrateo;
exception
    when others then
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
        raise exception '%',v_resp;
end;
$body$;

alter function ads.f_validar_nuevo_monto(integer,integer, numeric) owner to postgres;