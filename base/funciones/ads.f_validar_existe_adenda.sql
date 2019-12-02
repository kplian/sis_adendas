CREATE OR REPLACE FUNCTION ads.f_validar_existe_adenda (
  p_id_obligacion_pago integer
)
RETURNS varchar AS
$body$
declare
    v_resp        varchar;
    v_num_tramite varchar;
begin

    select obpg.num_tramite
    into v_num_tramite
    FROM ads.tadendas tad
             inner join tes.tobligacion_pago obpg on obpg.id_obligacion_pago = tad.id_obligacion_pago
    where tad.id_obligacion_pago = p_id_obligacion_pago
      and tad.estado in ('borrador', 'pendiente') and tad.estado_reg = 'activo';

    if v_num_tramite is not null then
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'La obligación de pago (tramite: ' || v_num_tramite ||
                                                       ') ya posee un modificatorio en proceso!');
        raise exception '%',v_resp;
    end if;

    return v_num_tramite;
exception

    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        raise exception '%',v_resp;

end;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION ads.f_validar_existe_adenda (p_id_obligacion_pago integer)
  OWNER TO postgres;