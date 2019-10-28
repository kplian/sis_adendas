CREATE OR REPLACE FUNCTION ads.f_bloquear_obligacion_pago (
  p_id_obligacion_pago integer,
  p_bloquear integer = 0
)
RETURNS boolean AS
$body$
declare
    v_nombre_funcion varchar = 'ads.f_bloquear_obligacion_pago';
    v_resp           varchar;
begin
    update tes.tobligacion_pago set bloqueado = p_bloquear where id_obligacion_pago = p_id_obligacion_pago;
    return true;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', v_nombre_funcion);
        raise exception '%', v_resp;
end ;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION ads.f_bloquear_obligacion_pago (p_id_obligacion_pago integer, p_bloquear integer)
  OWNER TO postgres;