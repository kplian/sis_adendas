create or replace function ads.f_clasificar_adenda_det()
    returns TABLE
            (
                id_adenda          integer,
                id_obligacion_pago integer,
                id_obligacion_det  integer,
                id_centro_costo    integer,
                id_partida         integer,
                centro_costo       character varying,
                nombre_partida     character varying,
                monto_anterior     numeric,
                nuevo_monto        numeric,
                monto_operacion    numeric,
                estado             character varying,
                descripcion             character varying
            )
    language plpgsql
as
$body$
declare
    v_nombre_funcion varchar = 'ads.f_clasificar_adenda_det';
    v_resp           varchar;
    v_registros      record;
    v_monto_anterior numeric;
    v_monto          numeric;
    v_estado         varchar;
begin
    FOR v_registros in (select adt.id_adenda,
                               adt.id_obligacion_det,
                               adt.id_centro_costo,
                               adt.id_partida,
                               p.codigo_cc as centro_costo,
                               par.nombre_partida,
                               adt.monto_pago_mo,
                               adt.monto_descomprometer,
                               adt.monto_comprometer,
                               op.id_gestion,
                               op.id_obligacion_pago,
                               op.id_moneda,
                               op.fecha,
                               op.num_tramite,
                               op.tipo_cambio_conv,
                               p.id_presupuesto,
                               p.codigo_cc,
                               par.codigo,
                               par.nombre_partida,
                               adt.descripcion
                        from ads.tadenda_det adt
                                 join ads.tadendas ad on ad.id_adenda = adt.id_adenda
                                 join tes.tobligacion_pago op on op.id_obligacion_pago = ad.id_obligacion_pago
                                 join pre.tpartida par on par.id_partida = adt.id_partida
                                 join pre.vpresupuesto_cc p on p.id_centro_costo = adt.id_centro_costo
                            and adt.estado_reg = 'activo'
                            and adt.monto_pago_mo > 0)
        LOOP

            v_monto_anterior = 0;

            select det.monto_pago_mo
            into v_monto_anterior
            from tes.tobligacion_det det
            where det.id_obligacion_det = v_registros.id_obligacion_det;

            if v_registros.id_obligacion_det is null then
                v_estado = 'nuevo';
            elsif v_registros.monto_comprometer > 0 then
                v_estado = 'comprometer';
            elsif v_registros.monto_descomprometer > 0 then
                v_estado = 'descomprometer';
            else
                v_estado = null;
            end if;

            if v_estado = 'nuevo' then
                v_monto = v_registros.monto_pago_mo;
            else
                v_monto = v_registros.monto_comprometer - v_registros.monto_descomprometer;
            end if;

            id_adenda = v_registros.id_adenda;
            id_obligacion_pago = v_registros.id_obligacion_pago;
            id_obligacion_det = v_registros.id_obligacion_det;
            id_centro_costo = v_registros.id_centro_costo;
            id_partida = v_registros.id_partida;
            centro_costo = v_registros.centro_costo;
            nombre_partida = v_registros.nombre_partida;
            monto_anterior = v_monto_anterior;
            nuevo_monto = v_registros.monto_pago_mo;
            monto_operacion = v_monto;
            estado = v_estado;
            descripcion = v_registros.descripcion;
            return next;
        end loop;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimiento', v_nombre_funcion);
        raise exception '%',v_resp;
end ;
$body$;
end;
alter function ads.f_clasificar_adenda_det() owner to postgres;