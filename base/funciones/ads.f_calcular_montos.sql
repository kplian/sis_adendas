CREATE OR REPLACE FUNCTION ads.f_calcular_montos (
  p_id_obligacion_det integer,
  p_nuevo_monto numeric,
  out p_monto_comprometer numeric,
  out p_monto_descomprometer numeric
)
RETURNS record AS
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
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION ads.f_calcular_montos (p_id_obligacion_det integer, p_nuevo_monto numeric, out p_monto_comprometer numeric, out p_monto_descomprometer numeric)
  OWNER TO postgres;