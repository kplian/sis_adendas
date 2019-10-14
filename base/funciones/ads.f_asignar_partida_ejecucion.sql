create or replace function ads.f_asignar_partida_ejecucion(p_administrador integer, p_id_usuario integer, p_tabla character varying,
                                            p_transaccion character varying, p_id_adenda integer) returns boolean
    language plpgsql
as
$body$
/**************************************************************************
 SISTEMA:		Sistema Adendas
 FUNCION: 		ads.ft_adendas_ime
 DESCRIPCION:   Función que permite la asignación de la partidad ejecucion a nuevas lineas en el detalle de la Adenda
 AUTOR: 		(valvarado)
 FECHA:	        14-08-2018
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
#
 ***************************************************************************/

DECLARE
    v_registros              RECORD;
    v_i                      INTEGER;
    v_fecha_aux              DATE;
    v_ano_1                  INTEGER;
    v_ano_2                  INTEGER;
    v_verif_pres_for         VARCHAR[];
    v_id_moneda_base         INTEGER;
    v_saldo_vigente          NUMERIC;
    v_verif_pres_comp        VARCHAR[];
    v_saldo_comp             NUMERIC;
    va_fecha                 DATE[];
    v_monto_a_comprometer    NUMERIC;
    v_monto_a_comprometer_mb NUMERIC;
    va_resp_ges              NUMERIC[];
    v_reg_sol                RECORD;
    v_desc_pre               VARCHAR;
BEGIN
    SELECT s.*
    INTO
        v_reg_sol
    FROM ads.tadenda_det adt
             INNER JOIN ads.tadendas ad ON ad.id_adenda = adt.id_adenda
             INNER JOIN pre.tpresupuesto p ON p.id_centro_costo = adt.id_centro_costo AND adt.estado_reg = 'activo'
             INNER JOIN tes.tobligacion_pago op ON op.id_obligacion_pago = ad.id_obligacion_pago
             INNER JOIN adq.tcotizacion cot ON cot.id_obligacion_pago = op.id_obligacion_pago
             INNER JOIN adq.tproceso_compra pc ON pc.id_proceso_compra = cot.id_proceso_compra
             INNER JOIN adq.tsolicitud s ON s.id_solicitud = pc.id_solicitud
    WHERE adt.id_adenda = p_id_adenda;

    v_i = 0;
    FOR v_registros in (
        SELECT adt.id_adenda_det,
               adt.id_centro_costo,
               s.id_gestion,
               s.id_solicitud,
               adt.id_partida,
               p.id_presupuesto,
               s.presu_comprometido,
               s.id_moneda,
               s.fecha_soli,
               ad.num_tramite as nro_tramite,
               s.comprometer_87

        FROM ads.tadenda_det adt
                 INNER JOIN ads.tadendas ad ON ad.id_adenda = adt.id_adenda
                 INNER JOIN pre.tpresupuesto p ON p.id_centro_costo = adt.id_centro_costo AND adt.estado_reg = 'activo'
                 INNER JOIN tes.tobligacion_pago op ON op.id_obligacion_pago = ad.id_obligacion_pago
                 INNER JOIN adq.tcotizacion cot ON cot.id_obligacion_pago = op.id_obligacion_pago
                 INNER JOIN adq.tproceso_compra pc ON pc.id_proceso_compra = cot.id_proceso_compra
                 INNER JOIN adq.tsolicitud s ON s.id_solicitud = pc.id_solicitud
        WHERE adt.id_adenda = p_id_adenda
          and adt.estado_reg = 'activo'
          AND adt.id_obligacion_det IS NULL)
        LOOP

            IF now() < v_registros.fecha_soli THEN
                v_fecha_aux = v_registros.fecha_soli::date;
            ELSE
                v_fecha_aux = now()::date;
                v_ano_1 = EXTRACT(YEAR FROM now()::date);
                v_ano_2 = EXTRACT(YEAR FROM v_registros.fecha_soli::date);

                IF v_ano_1 > v_ano_2 THEN
                    v_fecha_aux = ('31-12-' || v_ano_2::varchar)::date;
                END IF;

            END IF;

            v_verif_pres_for = pre.f_verificar_presupuesto_individual(
                    NULL,
                    NULL,
                    v_registros.id_presupuesto,
                    v_registros.id_partida,
                    0,
                    0,
                    'formulado');


            IF v_id_moneda_base != v_registros.id_moneda THEN
                v_saldo_vigente = param.f_convertir_moneda(
                        v_id_moneda_base,
                        v_registros.id_moneda,
                        v_verif_pres_for[2]::numeric,
                        v_fecha_aux,
                        'O'::varchar, 50);
            ELSE
                v_saldo_vigente = v_verif_pres_for[2]::numeric;
            END IF;

            v_verif_pres_comp = pre.f_verificar_presupuesto_individual(
                    NULL,
                    NULL,
                    v_registros.id_presupuesto,
                    v_registros.id_partida,
                    0,
                    0,
                    'comprometido');


            IF v_id_moneda_base != v_registros.id_moneda THEN
                v_saldo_comp = param.f_convertir_moneda(
                        v_id_moneda_base,
                        v_registros.id_moneda,
                        v_verif_pres_comp[2]::numeric,
                        v_fecha_aux,
                        'O'::varchar, 50);
            ELSE
                v_saldo_comp = v_verif_pres_comp[2]::numeric;
            END IF;

            update ads.tadenda_det adt
            set saldo_vigente    = v_saldo_vigente,
                saldo_vigente_mb = v_verif_pres_for[2]::numeric,
                saldo_comp       = v_saldo_comp,
                saldo_comp_mb    = v_verif_pres_comp[2]::numeric
            where adt.id_adenda_det = v_registros.id_adenda_det;

        END LOOP;

    FOR v_registros in (
        SELECT adt.id_adenda_det,
               adt.id_centro_costo,
               s.id_gestion,
               s.id_solicitud,
               adt.id_partida,
               adt.precio_ga_mb,
               p.id_presupuesto,
               s.presu_comprometido,
               s.id_moneda,
               adt.precio_ga,
               s.fecha_soli,
               ad.num_tramite as nro_tramite,
               s.comprometer_87

        FROM ads.tadenda_det adt
                 INNER JOIN ads.tadendas ad ON ad.id_adenda = adt.id_adenda
                 INNER JOIN pre.tpresupuesto p ON p.id_centro_costo = adt.id_centro_costo AND adt.estado_reg = 'activo'
                 INNER JOIN tes.tobligacion_pago op ON op.id_obligacion_pago = ad.id_obligacion_pago
                 INNER JOIN adq.tcotizacion cot ON cot.id_obligacion_pago = op.id_obligacion_pago
                 INNER JOIN adq.tproceso_compra pc ON pc.id_proceso_compra = cot.id_proceso_compra
                 INNER JOIN adq.tsolicitud s ON s.id_solicitud = pc.id_solicitud
        WHERE adt.id_adenda = p_id_adenda
          and adt.estado_reg = 'activo'
          AND adt.id_obligacion_det IS NULL)
        LOOP

            v_i = v_i + 1;

            IF now() < v_registros.fecha_soli THEN
                va_fecha[v_i] = v_registros.fecha_soli::date;
            ELSE
                va_fecha[v_i] = now()::date;
                v_ano_1 = EXTRACT(YEAR FROM now()::date);
                v_ano_2 = EXTRACT(YEAR FROM v_registros.fecha_soli::date);

                IF v_ano_1 > v_ano_2 THEN
                    va_fecha[v_i] = ('31-12-' || v_ano_2::varchar)::date;
                END IF;

            END IF;

            v_monto_a_comprometer = 0;
            v_monto_a_comprometer_mb = 0;
            IF v_registros.comprometer_87 = 'no' THEN
                v_monto_a_comprometer = v_registros.precio_ga::NUMERIC;
                v_monto_a_comprometer_mb = v_registros.precio_ga_mb::NUMERIC;
            ELSE
                v_monto_a_comprometer = v_registros.precio_ga::NUMERIC * 0.87;
                v_monto_a_comprometer_mb = v_registros.precio_ga_mb::NUMERIC * 0.87;
            END IF;


            va_resp_ges = pre.f_gestionar_presupuesto_individual(
                    p_id_usuario,
                    NULL::NUMERIC,
                    v_registros.id_presupuesto,
                    v_registros.id_partida,
                    v_registros.id_moneda,
                    v_monto_a_comprometer::numeric,
                    va_fecha[v_i],
                    'comprometido'::Varchar,
                    NULL::INTEGER,
                    'id_solicitud_compra'::VARCHAR,
                    v_registros.id_solicitud,
                    v_reg_sol.num_tramite::VARCHAR
                );


            IF va_resp_ges[1] = 0 THEN
                select pre.descripcion
                into
                    v_desc_pre
                from pre.tpresupuesto pre
                where pre.id_presupuesto = v_registros.id_presupuesto;


                IF va_resp_ges[4] is not null and va_resp_ges[4] = 1 THEN
                    raise exception '(%) el presupuesto no alcanza por diferencia cambiaria, en moneda base tenemos:  % ',v_desc_pre, va_resp_ges[3];
                ELSE
                    IF v_id_moneda_base = v_registros.id_moneda THEN
                        raise exception '(%) solo se tiene disponible un monto en moneda base de:  % , # % ,necesario: %', v_desc_pre, va_resp_ges[3], v_reg_sol.num_tramite , v_registros.precio_ga;
                    ELSE
                        IF va_resp_ges[5] is null THEN
                            raise exception 'BOB (%) solo se tiene disponible un monto en moneda base de:  % , # % ,necesario: %, le falta %',v_desc_pre, round(va_resp_ges[3], 2), v_reg_sol.num_tramite , round(v_monto_a_comprometer_mb, 2), round(v_monto_a_comprometer_mb - va_resp_ges[3], 2);
                        ELSE
                            raise exception '(%) solo se tiene disponible un monto de:  % , %',v_desc_pre, va_resp_ges[5], v_reg_sol.num_tramite;
                        END IF;

                    END IF;
                END IF;
            END IF;


            update ads.tadenda_det ad
            set id_partida_ejecucion_com = va_resp_ges[2],
                saldo_pre_mt             = va_resp_ges[5],
                saldo_pre_mb             = va_resp_ges[3],
                fecha_mod                = now(),
                fecha_comp               = now(),
                id_usuario_mod           = p_id_usuario,
                revertido_mb             = 0,
                revertido_mo             = 0,
                monto_cmp                = v_monto_a_comprometer,
                monto_cmp_mb             = v_monto_a_comprometer_mb
            where ad.id_adenda_det = v_registros.id_adenda_det;

        END LOOP;
    return TRUE;
end;
$body$;

alter function ads.f_asignar_partida_ejecucion(integer, integer, varchar, varchar, integer) owner to dbavalvarado;

