create or replace function ads.f_update_adenda_total_pago() returns trigger
    language plpgsql
as
$$

DECLARE
    v_monto_total DECIMAL;
    v_id_adenda   INTEGER;
    v_resp        VARCHAR;
BEGIN

    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
        v_id_adenda = NEW.id_adenda;
    ELSEIF TG_OP = 'DELETE' THEN
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
$$;

alter function ads.f_update_adenda_total_pago() owner to postgres;

