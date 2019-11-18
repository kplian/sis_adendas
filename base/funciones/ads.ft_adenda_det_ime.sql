CREATE OR REPLACE FUNCTION "ads"."ft_adenda_det_ime"(p_administrador INTEGER, p_id_usuario INTEGER,
                                                     p_tabla character varying, p_transaccion character varying)
    RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Adendas
 FUNCION: 		ads.ft_adenda_det_ime
 DESCRIPCION:   Funcion que gestiona las aderaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'ads.tadenda_det'
 AUTOR: 		 (valvarado)
 FECHA:	        24-06-2019 15:15:06
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #
 ***************************************************************************/

DECLARE
    v_id_adenda_det      INTEGER;
    v_parametros         RECORD;
    v_resp               VARCHAR;
    v_nombre_funcion     text;
    v_id_obligacion_det  INTEGER;
    v_tipo_cambio_conv   NUMERIC;
    v_monto_mb           NUMERIC;
    v_tipo_obligacion    VARCHAR;
    v_id_partida         INTEGER;
    v_id_gestion         INTEGER;
    v_registros_cig      RECORD;
    v_relacion           VARCHAR;
    v_id_moneda_sg       INTEGER;
    v_monto_pago_sg_mb   NUMERIC;
    v_fecha_ob           DATE;
    v_id_obligacion_pago INTEGER;

BEGIN

    v_nombre_funcion = 'ads.ft_adenda_det_ime';
    v_parametros = pxp.f_get_record(p_tabla);

    if (p_transaccion = 'ADS_AD_DET_INS') then

        begin

            select obpg.tipo_cambio_conv,
                   obpg.tipo_obligacion,
                   obpg.id_gestion,
                   obpg.id_obligacion_pago
            into
                v_tipo_cambio_conv,
                v_tipo_obligacion,
                v_id_gestion,
                v_id_obligacion_pago
            from ads.tadendas ad
                     INNER JOIN tes.tobligacion_pago obpg ON obpg.id_obligacion_pago = ad.id_obligacion_pago
            where ad.id_adenda = v_parametros.id_adenda;

            v_monto_mb = v_parametros.monto_pago_mo * v_tipo_cambio_conv;

            v_id_partida = NULL;


            raise notice '(''CUECOMP'', %, %, %)', v_id_gestion, v_parametros.id_concepto_ingas, v_parametros.id_centro_costo;

            select cig.desc_ingas
            into
                v_registros_cig
            from param.tconcepto_ingas cig
            where cig.id_concepto_ingas = v_parametros.id_concepto_ingas;


            IF v_tipo_obligacion = 'pago_especial' THEN
                v_relacion = 'PAGOES';
            ELSE
                v_relacion = 'CUECOMP';
            END IF;

            SELECT ps_id_partida
            into
                v_id_partida
            FROM conta.f_get_config_relacion_contable(v_relacion, v_id_gestion, v_parametros.id_concepto_ingas,
                                                      v_parametros.id_centro_costo,
                                                      'No se encontro relación contable para el conceto de gasto: ' ||
                                                      v_registros_cig.desc_ingas || '. <br> Mensaje: ');


            IF v_id_partida is NULL THEN
                raise exception 'no se tiene una parametrizacion de partida  para este concepto de gasto en la relacion contable de código  (%,%,%,%)','CUECOMP', v_relacion, v_parametros.id_concepto_ingas, v_parametros.id_centro_costo;
            END IF;

            select obpg.id_moneda,
                   ad.fecha_reg
            into
                v_id_moneda_sg,
                v_fecha_ob
            from ads.tadendas ad
                     INNER JOIN tes.tobligacion_pago obpg ON obpg.id_obligacion_pago = ad.id_obligacion_pago
            where ad.id_adenda = v_parametros.id_adenda;

            v_monto_pago_sg_mb = 0;
            IF pxp.f_existe_parametro(p_tabla, 'monto_pago_sg_mo') THEN

                v_monto_pago_sg_mb = param.f_convertir_moneda(v_id_moneda_sg,
                                                              NULL,
                                                              v_parametros.monto_pago_sg_mo,
                                                              v_fecha_ob,
                                                              'O',
                                                              NULL);
            END IF;


            if (v_parametros.monto_pago_sg_mo < 0) then
                raise exception 'El Monto Sig. Ges. Mo no puedes ser menor al Monto Total';
            elsif (v_parametros.monto_pago_sg_mo > v_parametros.monto_pago_mo) then
                raise exception 'El Monto Sig. Ges. Mo no puedes ser mayor al Monto Total';
            end if;

            insert into ads.tadenda_det(estado_reg,
                                        id_partida,
                                        id_concepto_ingas,
                                        monto_pago_mo,
                                        monto_comprometer,
                                        id_obligacion_pago,
                                        id_centro_costo,
                                        monto_pago_mb,
                                        descripcion,
                                        fecha_reg,
                                        id_usuario_reg,
                                        id_orden_trabajo,
                                        monto_pago_sg_mo,
                                        monto_pago_sg_mb,
                                        id_adenda)
            values ('activo',
                    v_id_partida,
                    v_parametros.id_concepto_ingas,
                    v_parametros.monto_pago_mo,
                    v_parametros.monto_pago_mo,
                    v_id_obligacion_pago,
                    v_parametros.id_centro_costo,
                    v_monto_mb,
                    v_parametros.descripcion,
                    now(),
                    p_id_usuario,
                    v_parametros.id_orden_trabajo,
                    v_parametros.monto_pago_sg_mo,
                    v_monto_pago_sg_mb,
                    v_parametros.id_adenda)
            RETURNING id_adenda_det into v_id_adenda_det;

            v_resp = pxp.f_agrega_clave(v_resp, 'mensaje',
                                        'Detalle almacenado(a) con exito (id_obligacion_det' || v_id_obligacion_det ||
                                        ')');
            v_resp = pxp.f_agrega_clave(v_resp, 'id_obligacion_det', v_id_obligacion_det::VARCHAR);

            return v_resp;

        end;
    elseif (p_transaccion = 'ADS_AD_DET_MOD') then
        declare
            v_monto_comprometer    numeric;
            v_monto_descomprometer numeric;
            v_validar_monto        varchar;
        begin

            v_validar_monto = ads.f_validar_nuevo_monto(v_parametros.id_obligacion_pago, v_parametros.id_obligacion_det,
                                                        v_parametros.monto_pago_mo);

            select obpg.tipo_cambio_conv,
                   obpg.tipo_obligacion,
                   obpg.id_gestion,
                   obpg.id_obligacion_pago
            into
                v_tipo_cambio_conv,
                v_tipo_obligacion,
                v_id_gestion,
                v_id_obligacion_pago
            from ads.tadendas ad
                     INNER JOIN tes.tobligacion_pago obpg ON obpg.id_obligacion_pago = ad.id_obligacion_pago
            where ad.id_adenda = v_parametros.id_adenda;

            v_monto_mb = v_parametros.monto_pago_mo * v_tipo_cambio_conv;

            v_id_partida = NULL;


            select cig.desc_ingas
            into
                v_registros_cig
            from param.tconcepto_ingas cig
            where cig.id_concepto_ingas = v_parametros.id_concepto_ingas;

            SELECT ps_id_partida
            into
                v_id_partida
            FROM conta.f_get_config_relacion_contable('CUECOMP', v_id_gestion, v_parametros.id_concepto_ingas,
                                                      v_parametros.id_centro_costo,
                                                      'No se encontro relación contable para el conceto de gasto: ' ||
                                                      v_registros_cig.desc_ingas || '. <br> Mensaje: ');


            IF v_id_partida is NULL THEN

                raise exception 'no se tiene una parametrizacionde partida  para este concepto de gasto en la relacion contable de código CUECOMP  (%,%,%,%)','CUECOMP', v_id_gestion, v_parametros.id_concepto_ingas, v_parametros.id_centro_costo;

            END IF;

            select obpg.id_moneda,
                   ad.fecha_reg
            into
                v_id_moneda_sg,
                v_fecha_ob
            from ads.tadendas ad
                     INNER JOIN tes.tobligacion_pago obpg ON obpg.id_obligacion_pago = ad.id_obligacion_pago
            where ad.id_adenda = v_parametros.id_adenda;

            if (v_parametros.monto_pago_sg_mo < 0) then
                raise exception 'El Monto Sig. Ges. Mo no puedes ser menor al Monto Total';
            elsif (v_parametros.monto_pago_sg_mo > v_parametros.monto_pago_mo) then
                raise exception 'El Monto Sig. Ges. Mo no puedes ser mayor al Monto Total';
            end if;

            v_monto_pago_sg_mb = param.f_convertir_moneda(v_id_moneda_sg,
                                                          NULL,
                                                          v_parametros.monto_pago_sg_mo,
                                                          v_fecha_ob,
                                                          'O',
                                                          NULL);

            SELECT p_monto_comprometer, p_monto_descomprometer
            into v_monto_comprometer, v_monto_descomprometer
            FROM ads.f_calcular_montos(v_parametros.id_obligacion_det, v_parametros.monto_pago_mo);

            update ads.tadenda_det
            set id_partida           = v_id_partida,
                id_concepto_ingas    = v_parametros.id_concepto_ingas,
                monto_pago_mo        = v_parametros.monto_pago_mo,
                monto_comprometer    = v_monto_comprometer,
                monto_descomprometer = v_monto_descomprometer,
                id_obligacion_pago   = v_id_obligacion_pago,
                id_centro_costo      = v_parametros.id_centro_costo,
                monto_pago_mb        = v_monto_mb,
                fecha_mod            = now(),
                descripcion          = v_parametros.descripcion,
                id_usuario_mod       = p_id_usuario,
                id_orden_trabajo     = v_parametros.id_orden_trabajo,
                monto_pago_sg_mo     = v_parametros.monto_pago_sg_mo,
                monto_pago_sg_mb     = v_monto_pago_sg_mb
            where id_adenda_det = v_parametros.id_adenda_det;

            v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Detalle modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp, 'id_adenda_det', v_parametros.id_adenda_det::VARCHAR);

            return v_resp;

        end;

    elsif (p_transaccion = 'ADS_AD_DET_ELI') then

        begin
            delete
            from ads.tadenda_det
            where id_adenda_det = v_parametros.id_adenda_det;

            v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Sis Adenda eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp, 'id_adenda_det', v_parametros.id_adenda_det::VARCHAR);

            return v_resp;

        end;

    else

        raise exception 'Transaccion inexistente: %',p_transaccion;

    end if;

EXCEPTION

    WHEN OTHERS THEN
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
        raise exception '%',v_resp;

END;
$BODY$
    LANGUAGE 'plpgsql' VOLATILE
                       COST 100;
ALTER FUNCTION "ads"."ft_adenda_det_ime"(INTEGER, INTEGER, character varying, character varying) OWNER TO postgres;
