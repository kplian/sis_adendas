CREATE OR REPLACE FUNCTION ads.f_obtener_total_monto_desc_anticipo (
  p_id_obligacion_pago integer,
  p_id_obligacion_det integer
)
RETURNS numeric AS
$body$
declare
    v_nombre_funcion             varchar= 'ads.f_obtener_total_monto_desc_anticipo';
    v_resp                       varchar;
    v_total_descuento            numeric;
    v_importe_total              numeric;
    v_factor_prorrateo_descuento numeric;
    v_monto_detalle              numeric;
begin
    select sum(descuento_anticipo)
    into v_total_descuento
    from tes.tplan_pago pp
    where pp.id_obligacion_pago = p_id_obligacion_pago
      and pp.id_plan_pago_fk is null;

    select obp.total_pago
    into v_importe_total
    from tes.tobligacion_pago obp
    where obp.id_obligacion_pago = p_id_obligacion_pago;

    select monto_pago_mo into v_monto_detalle from tes.tobligacion_det where id_obligacion_det = p_id_obligacion_det;

    v_factor_prorrateo_descuento = v_total_descuento / v_importe_total;

    return v_monto_detalle * v_factor_prorrateo_descuento;
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

ALTER FUNCTION ads.f_obtener_total_monto_desc_anticipo (p_id_obligacion_pago integer, p_id_obligacion_det integer)
  OWNER TO postgres;