CREATE OR REPLACE FUNCTION ads.f_aprobar_adenda (
  p_id_adenda integer,
  p_id_usuario integer
)
RETURNS varchar AS
$body$
declare
    v_nombre_function varchar='ads.f_aprobar_adenda';
    v_resp            varchar;
    v_registros       record;
    v_fecha           date;
    v_anio_1          integer;
    v_anio_2          integer;
    va_resp_ges       numeric[];
    v_adenda_det      record;
begin

    for v_registros in (
        select *
        from ads.f_verificar_presupuesto() vp
        where vp.id_adenda = p_id_adenda)
        LOOP

            select *
            into v_adenda_det
            from ads.tadenda_det adt
                     inner join ads.tadendas ad on ad.id_adenda = adt.id_adenda
                     inner join pre.tpresupuesto p
                                on p.id_centro_costo = adt.id_centro_costo and adt.estado_reg = 'activo'
                     inner join tes.tobligacion_pago op on op.id_obligacion_pago = ad.id_obligacion_pago
                     inner join adq.tcotizacion cot on cot.id_obligacion_pago = op.id_obligacion_pago
                     inner join adq.tproceso_compra pc on pc.id_proceso_compra = cot.id_proceso_compra
                     inner join adq.tsolicitud s on s.id_solicitud = pc.id_solicitud
            where adt.estado_reg = 'activo'
              and adt.id_adenda_det = ANY (v_registros.adenda_det_ids ::integer[])
            limit 1;

            if now() < v_adenda_det.fecha_soli then
                v_fecha = v_adenda_det.fecha_soli::date;
            else
                v_fecha = now()::date;
                v_anio_1 = extract(year from now()::date);
                v_anio_2 = extract(year from v_adenda_det.fecha_soli::date);

                if v_anio_1 > v_anio_2 then
                    v_fecha = ('31-12-' || v_anio_2::varchar)::date;
                end if;

            end if;

            va_resp_ges = pre.f_gestionar_presupuesto_individual(
                    p_id_usuario,
                    null::numeric,
                    v_registros.id_presupuesto,
                    v_registros.id_partida,
                    v_adenda_det.id_moneda,
                    v_registros.monto_operacion::numeric,
                    v_fecha,
                    'comprometido'::varchar,
                    null::integer,
                    'id_solicitud_compra'::varchar,
                    v_adenda_det.id_solicitud,
                    v_adenda_det.num_tramite::varchar
                );

            update ads.tadenda_det ad
            set id_partida_ejecucion_com_ad = va_resp_ges[2],
                fecha_comp                  = now(),
                id_usuario_mod              = p_id_usuario,
                fecha_mod                   = now()
            where ad.id_adenda_det = ANY (v_registros.adenda_det_ids ::integer[]);

        end loop;
    v_resp = 'Modificatorio confirmado satisfactoriamente';
    return v_resp;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimiento', v_nombre_function);
        raise exception '%',v_resp;
end;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION ads.f_aprobar_adenda (p_id_adenda integer, p_id_usuario integer)
  OWNER TO postgres;