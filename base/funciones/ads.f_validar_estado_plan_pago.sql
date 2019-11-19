CREATE OR REPLACE FUNCTION ads.f_validar_estado_plan_pago (
  p_id_obligacion integer
)
RETURNS boolean AS
$body$
declare
    v_resp           varchar;
    v_nombre_funcion varchar;
begin
    v_nombre_funcion = 'ads.f_validar_estado_plan_pago';
    if exists(select 1
                  from tes.tplan_pago pp
                  where pp.estado not in ('anticipado', 'devengado')
                    and pp.id_obligacion_pago = p_id_obligacion
                    AND id_plan_pago_fk is null) then
        raise exception 'No es posible crear una adenda para Obligaciones de pago con pagos en curso';
    end if;

    return true;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
        RAISE EXCEPTION '%', v_resp;
end;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION ads.f_validar_estado_plan_pago (p_id_obligacion integer)
  OWNER TO dbavalvarado;