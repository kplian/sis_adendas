CREATE OR REPLACE FUNCTION ads.f_trig_verificar_bloqueado (
)
RETURNS trigger AS
$body$
declare
    v_id_obligacion_det integer;
BEGIN
    IF TG_OP = 'INSERT' THEN
        v_id_obligacion_det = new.id_obligacion_det;
    else
        v_id_obligacion_det = old.id_obligacion_det;
    END IF;

    IF EXISTS(SELECT op.id_obligacion_pago
              from tes.tprorrateo pro
                       join tes.tobligacion_det opd on opd.id_obligacion_det = pro.id_obligacion_det
                       join tes.tobligacion_pago op on op.id_obligacion_pago = opd.id_obligacion_pago
              where opd.id_obligacion_det = v_id_obligacion_det
                and op.bloqueado = 1
              limit 1) THEN
        RAISE EXCEPTION 'No es posible realizar cambios por que tiene un proceso modificatorio en curso';
    ELSE
        RETURN NEW;
    END IF;
END
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION ads.f_trig_verificar_bloqueado ()
  OWNER TO postgres;