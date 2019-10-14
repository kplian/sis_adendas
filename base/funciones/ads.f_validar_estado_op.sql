create or replace function ads.f_validar_estado_op(id_op integer) returns character varying
    language plpgsql
as
$body$
/**************************************************************************
 SISTEMA:		Sistema Adendas
 FUNCION: 		ads.f_validar_estado_op
 DESCRIPCION:   Función que valida el estado de la obligación de pago necesaria para la creación de adendas
 AUTOR: 		(valvarado)
 FECHA:	        26-06-2019 18:30:00
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 ***************************************************************************/
DECLARE
    v_resp           VARCHAR;
    v_nombre_funcion TEXT;
    v_numero_tramite VARCHAR;
BEGIN
    v_nombre_funcion = 'ads.ft_adendas_ime';

    SELECT obpg.num_tramite
    INTO v_numero_tramite
    FROM tes.tobligacion_pago obpg
    WHERE obpg.id_obligacion_pago = id_op
      AND obpg.estado in ('en_pago');

    IF v_numero_tramite IS NULL THEN
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje',
                                    'Debe seleccionar una obligación de pago en el estado:  en_pago !');
        raise exception '%',v_resp;
    END IF;

    RETURN v_numero_tramite;

EXCEPTION

    WHEN OTHERS THEN
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
        raise exception '%',v_resp;

END
$body$;

alter function  ads.f_validar_estado_op(integer) owner to postgres;

