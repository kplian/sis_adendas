CREATE OR REPLACE FUNCTION ads.f_update_adenda_total_monto () RETURNS trigger AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Adendas
 FUNCION: 		ads.f_update_adenda_total_pago
 DESCRIPCION:   Función ejecutada desde un trigger al momento de actualizar el detalle de la adenda
 AUTOR: 		valvarado
 FECHA:	        14-08-2019
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
Issue			Fecha        Author				Descripcion
***************************************************************************/

DECLARE
    v_monto_total DECIMAL;
    v_id_adenda INTEGER;
    v_resp  VARCHAR;
BEGIN

    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
        v_id_adenda = NEW.id_adenda;
    ELSEIF  TG_OP = 'DELETE' THEN
        v_id_adenda = OLD.id_adenda;
    end if;

    SELECT SUM(adst.monto_pago_mo)
    INTO v_monto_total
    FROM ads.tadenda_det adst
    WHERE adst.id_adenda = v_id_adenda;

    UPDATE ads.tadendas ad SET total_pago = v_monto_total where ad.id_adenda = v_id_adenda;

    return NEW;
EXCEPTION

  WHEN OTHERS
    THEN
      v_resp = '';
      v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
      v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
      RAISE EXCEPTION '%', v_resp;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;
alter function ads.f_update_adenda_total_monto() owner to postgres;