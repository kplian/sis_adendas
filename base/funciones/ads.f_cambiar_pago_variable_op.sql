create or replace function ads.f_cambiar_pago_variable_op(p_id_obligacion_pago integer, p_pago_variable character varying DEFAULT 'no') returns boolean
    language plpgsql
as
$body$
declare
    v_nombre_funcion varchar = 'ads.f_bloquear_obligacion_pago';
    v_resp           varchar;
begin
    update tes.tobligacion_pago set pago_variable = p_pago_variable where id_obligacion_pago = p_id_obligacion_pago;
    return true;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', v_nombre_funcion);
        raise exception '%', v_resp;
end ;
$body$;

alter function ads.f_cambiar_pago_variable_op(integer, character varying) owner to postgres;

