create or replace function ads.f_contrato(p_administrador integer, p_id_usuario integer, p_tabla character varying,
                                          p_transaccion character varying) returns character varying
    language plpgsql
as
$$
declare
    v_resp           varchar;
    v_nombre_funcion varchar = 'ads.f_contrato';
    v_consulta       varchar;
    v_parametros     record;
begin
    v_parametros = pxp.f_get_record(p_tabla);
    if (p_transaccion = 'CONTRATO_SEL') then
        v_consulta =
                'select con.id_contrato, con.numero, con.id_contrato_fk from leg.vcontrato con where numero is not null and';

        v_consulta = v_consulta || v_parametros.filtro;
        v_consulta = v_consulta || ' order by ' || v_parametros.ordenacion || ' ' ||
                     v_parametros.dir_ordenacion || ' limit ' ||
                     v_parametros.cantidad || ' offset ' || v_parametros.puntero;

        return v_consulta;
    elsif (p_transaccion = 'CONTRATO_CONT') then
        v_consulta = ' select count(con.id_contrato) from leg.vcontrato con where ';
        v_consulta := v_consulta || v_parametros.filtro;
        RETURN v_consulta;

    end if;

exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimiento', v_nombre_funcion);
        raise exception '%',v_resp;
end;
$$;

alter function ads.f_contrato(integer, integer, varchar, varchar) owner to postgres;

