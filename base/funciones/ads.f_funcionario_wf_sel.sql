CREATE OR REPLACE FUNCTION ads.f_funcionario_wf_sel (
  p_id_usuario integer,
  p_id_tipo_estado integer,
  p_fecha date = now(),
  p_id_estado_wf integer = NULL::integer,
  p_count boolean = false,
  p_limit integer = 1,
  p_start integer = 0,
  p_filtro varchar = '0=0'::character varying
)
RETURNS SETOF record AS
$body$
declare
    v_nombre_function varchar = 'ads.f_funcionario_wf_sel';
    v_resp            varchar;
    v_consulta        varchar;
    v_id_funcionario  varchar;
    g_registros       record;
begin
    select id_funcionario
    into
        v_id_funcionario
    from ads.tadendas
    where id_estado_wf = p_id_estado_wf;


    if not p_count then

        v_consulta := 'SELECT fun.id_funcionario,
                            fun.desc_funcionario1 as desc_funcionario,
                            ''Gerente''::text  as desc_funcionario_cargo,
                            1 as prioridad
                         FROM orga.vfuncionario fun WHERE fun.id_funcionario = ' || v_id_funcionario || '
                         and ' || p_filtro || '
                         limit ' || p_limit::varchar || ' offset ' || p_start::varchar;


        for g_registros in execute (v_consulta)
            loop
                return next g_registros;
            end loop;
    else
        v_consulta = 'select COUNT(fun.id_funcionario) as total
                        FROM orga.vfuncionario fun  WHERE fun.id_funcionario = ' ||
                     v_id_funcionario || ' and ' || p_filtro;

        for g_registros in execute (v_consulta)
            loop
                return next g_registros;
            end loop;
    end if;
exception
    when others then
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimiento', v_nombre_function);
        raise exception '%',v_resp;
end;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100 ROWS 1000;

ALTER FUNCTION ads.f_funcionario_wf_sel (p_id_usuario integer, p_id_tipo_estado integer, p_fecha date, p_id_estado_wf integer, p_count boolean, p_limit integer, p_start integer, p_filtro varchar)
  OWNER TO postgres;