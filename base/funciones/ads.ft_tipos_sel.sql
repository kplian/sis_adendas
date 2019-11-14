CREATE OR REPLACE FUNCTION ads.ft_tipos_sel(p_administrador INTEGER,
                                            p_id_usuario INTEGER,
                                            p_tabla VARCHAR,
                                            p_transaccion VARCHAR)
    RETURNS VARCHAR AS
$BODY$
DECLARE

    v_consulta       varchar;
    v_parametros     record;
    v_nombre_funcion text;
    v_resp           varchar;

BEGIN

    v_nombre_funcion = 'ads.ft_tipos_sel';
    v_parametros = pxp.f_get_record(p_tabla);
    IF (p_transaccion = 'ADS_TIPOS_SEL') THEN

        BEGIN
            v_consulta := 'select tipos.id_tipo,
                            tipos.codigo,
                            tipos.descripcion,
                            tipos.estado_reg,
                            tipos.fecha_reg,
                            tipos.fecha_mod
                        from ads.ttipos tipos
                            inner join segu.tusuario usu1 on usu1.id_usuario = tipos.id_usuario_reg
                            left join segu.tusuario usu2 on usu2.id_usuario = tipos.id_usuario_mod
                        where tipos.estado_reg = ''activo'' and ';

            v_consulta := v_consulta || v_parametros.filtro;
            v_consulta := v_consulta || ' order by ' || v_parametros.ordenacion || ' ' ||
                          v_parametros.dir_ordenacion || ' limit ' ||
                          v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            RETURN v_consulta;

        END;
    ELSIF (p_transaccion = 'ADS_TIPOS_CONT') THEN

        BEGIN

            v_consulta := 'select count(tipos.id_tipo)
                        from ads.ttipos tipos
                            left join segu.tusuario usu1 on usu1.id_usuario = tipos.id_usuario_reg
                            left join segu.tusuario usu2 on usu2.id_usuario = tipos.id_usuario_mod
                        where  tipos.estado_reg = ''activo'' and ';

            v_consulta := v_consulta || v_parametros.filtro;

            RETURN v_consulta;

        END;

    ELSE

        RAISE EXCEPTION 'Transaccion inexistente';

    END IF;

EXCEPTION

    WHEN OTHERS
        THEN
            v_resp = '';
            v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
            v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
            v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
            RAISE EXCEPTION '%', v_resp;
END;
$BODY$
    LANGUAGE 'plpgsql'
    VOLATILE
    COST 100;

ALTER FUNCTION ads.ft_tipos_sel( INTEGER, INTEGER, CHARACTER VARYING, CHARACTER VARYING )
    OWNER TO postgres;