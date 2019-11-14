CREATE OR REPLACE FUNCTION ads.ft_tipos_ime(p_administrador integer,
                                            p_id_usuario integer,
                                            p_tabla varchar,
                                            p_transaccion varchar)
    RETURNS varchar AS
$body$
DECLARE
    v_parametros     record;
    v_resp           varchar;
    v_nombre_funcion varchar;
    v_id_tipo        decimal;
    v_codigo         varchar;

BEGIN

    v_nombre_funcion = 'ads.ft_tipos_ime';
    v_parametros = pxp.f_get_record(p_tabla);
    if (p_transaccion = 'ADS_TIPOS_INS') then
        begin

            v_codigo = ads.f_validar_codigo_tipo(v_parametros.codigo);

            insert into ADS.ttipoS(codigo,
                                   descripcion,
                                   estado_reg,
                                   fecha_reg,
                                   id_usuario_reg,
                                   id_usuario_ai,
                                   usuario_ai)
            values (v_parametros.codigo,
                    v_parametros.descripcion,
                    'activo',
                    now(),
                    p_id_usuario,
                    v_parametros._id_usuario_ai,
                    v_parametros._nombre_usuario_ai)
            RETURNING id_tipo into v_id_tipo;

            v_resp = pxp.f_agrega_clave(v_resp, 'mensaje',
                                        'Tipo Modificatorio registrado con exito !!!');
            v_resp = pxp.f_agrega_clave(v_resp, 'id_tipo', v_id_tipo::varchar);

            return v_resp;
        end;
    elsif (p_transaccion = 'ADS_TIPOS_MOD') then

        begin

            select t.codigo into v_codigo from ads.ttipos t where t.id_tipo = v_parametros.id_tipo;

            if v_codigo != v_parametros.codigo then
                v_codigo = ads.f_validar_codigo_tipo(v_parametros.codigo);
            end if;

            update ads.ttipos
            set codigo         = v_parametros.codigo,
                descripcion    = v_parametros.descripcion,
                fecha_mod      = now(),
                id_usuario_ai  = v_parametros._id_usuario_ai,
                id_usuario_mod = p_id_usuario,
                usuario_ai     = v_parametros._nombre_usuario_ai
            where id_tipo = v_parametros.id_tipo;

            v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Tipo Modificatorio modificado con &eacute;xito');
            v_resp = pxp.f_agrega_clave(v_resp, 'id_tipo', v_parametros.id_tipo::varchar);

            return v_resp;
        end;

    elsif (p_transaccion = 'ADS_TIPOS_ELI') then

        begin

            update ads.ttipos
            set estado_reg = 'inactivo'
            where id_tipo = v_parametros.id_tipo;

            v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', 'Tipo Modificatorio eliminado con &eacute;xito !!!');
            v_resp = pxp.f_agrega_clave(v_resp, 'id_tipo', v_parametros.id_tipo::varchar);

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
$body$
    LANGUAGE 'plpgsql'
    VOLATILE
    CALLED ON NULL INPUT
    SECURITY INVOKER
    COST 100;

ALTER FUNCTION ads.ft_tipos_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
    OWNER TO postgres;