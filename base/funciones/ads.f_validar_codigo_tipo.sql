CREATE OR REPLACE FUNCTION ads.f_validar_codigo_tipo(
    codigo_tipo varchar
)
    RETURNS character varying AS
$body$
DECLARE
    v_nombre_funcion TEXT;
BEGIN
    v_nombre_funcion = 'ads.f_validar_codigo_tipo';

    IF exists(SELECT t.codigo
              FROM ads.ttipos t
              WHERE t.codigo = codigo_tipo
              limit 1) THEN

        raise exception 'El c&oacute;digo % ya se encuentra en uso.', codigo_tipo;
    END IF;
    return codigo_tipo;
END
$body$
    LANGUAGE 'plpgsql'
    VOLATILE
    CALLED ON NULL INPUT
    SECURITY INVOKER
    COST 100;

ALTER FUNCTION ads.f_validar_codigo_tipo (codigo_tipo varchar)
    OWNER TO postgres;