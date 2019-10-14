create or replace function ads.f_verificar_presupuesto()
    returns TABLE
            (
                id_adenda       integer,
                id_centro_costo integer,
                id_partida      integer,
                id_presupuesto  integer,
                centro_costo    varchar,
                nombre_partida  varchar,
                monto_operacion numeric,
                disponible      varchar,
                adenda_det_ids  integer[],
                techo           varchar
            )
    language plpgsql
as
$$
declare
    v_procedimiento varchar;
    v_resp          varchar;
    v_registros     record;
    v_resp_pre      varchar[];
begin

    v_resp = '';


    FOR v_registros in (select adt.id_adenda,
                               ad.num_tramite,
                               adt.id_centro_costo,
                               adt.id_partida,
                               p.id_presupuesto,
                               p.codigo_cc                                                  as centro_costo,
                               par.id_partida,
                               par.nombre_partida,
                               (sum(adt.monto_comprometer) - sum(adt.monto_descomprometer)) as monto_total_operacion,
                               op.id_moneda,
                               tcc2.codigo,
                               array_agg(adt.id_adenda_det)                                 AS adenda_det_ids
                        from ads.tadenda_det adt
                                 join ads.tadendas ad on ad.id_adenda = adt.id_adenda
                                 join tes.tobligacion_pago op on op.id_obligacion_pago = ad.id_obligacion_pago
                                 left join tes.tobligacion_det opd on opd.id_obligacion_det = adt.id_obligacion_det
                                 join pre.tpartida par on par.id_partida = adt.id_partida
                                 join pre.vpresupuesto_cc p on p.id_centro_costo = adt.id_centro_costo
                                 join param.tcentro_costo cc on cc.id_centro_costo = p.id_centro_costo
                                 join param.vtipo_cc_techo tcc on tcc.id_tipo_cc = cc.id_tipo_cc
                                 join param.ttipo_cc tcc2 on tcc2.id_tipo_cc = tcc.id_tipo_cc_techo
                        where adt.estado_reg = 'activo'
                          and adt.monto_pago_mo > 0
                          and (adt.monto_pago_mo != opd.monto_pago_mo or adt.id_obligacion_det is null)
                        group by adt.id_adenda,
                                 ad.num_tramite,
                                 adt.id_centro_costo,
                                 adt.id_partida,
                                 p.id_presupuesto,
                                 p.codigo_cc,
                                 par.id_partida,
                                 par.nombre_partida,
                                 op.id_moneda,
                                 tcc2.codigo)
        LOOP
            v_resp_pre = pre.f_verificar_presupuesto_individual(v_registros.num_tramite, NULL,
                                                                v_registros.id_presupuesto,
                                                                v_registros.id_partida,
                                                                v_registros.monto_total_operacion,
                                                                v_registros.monto_total_operacion,
                                                                'comprometido', false);
            id_adenda = v_registros.id_adenda;
            techo = v_registros.codigo;
            id_centro_costo = v_registros.id_centro_costo;
            id_partida = v_registros.id_partida;
            id_presupuesto = v_registros.id_presupuesto;
            centro_costo = v_registros.centro_costo;
            nombre_partida = v_registros.nombre_partida;
            monto_operacion = v_registros.monto_total_operacion;
            disponible = v_resp_pre[1]::varchar;
            adenda_det_ids = v_registros.adenda_det_ids;

            return next;
        END LOOP;
exception
    when others then
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimiento', v_procedimiento);
        raise exception '%', v_resp;
end;
$$;

alter function ads.f_verificar_presupuesto() owner to postgres;

