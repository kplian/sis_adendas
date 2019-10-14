create or replace function ads.f_validar_obligacion_pago_no_bloqueado(p_id_obligacion_pago integer) returns boolean
    language plpgsql
as
$body$
DECLARE
    v_resp varchar;
begin

    if exists(SELECT ob.id_obligacion_pago
              FROM tes.tobligacion_pago ob
                       JOIN tes.tobligacion_det obd ON obd.id_obligacion_pago = ob.id_obligacion_pago
              WHERE ob.id_obligacion_pago = p_id_obligacion_pago
                AND ob.bloqueado = 1) then
        raise exception 'No es posible crear una adenda a una obligacioón de pago bloqueada';
    end if;

    return true;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        raise exception '%', v_resp;
end;
$body$;

alter function ads.f_validar_obligacion_pago_no_bloqueado(integer) owner to postgres;

