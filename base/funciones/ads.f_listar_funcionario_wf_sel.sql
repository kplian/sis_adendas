create or replace function ads.f_lista_funcionario_wf_sel(p_id_usuario integer, p_id_tipo_estado integer, p_fecha date DEFAULT now(),
                                           p_id_estado_wf integer DEFAULT NULL::integer, p_count boolean DEFAULT false,
                                           p_limit integer DEFAULT 1, p_start integer DEFAULT 0,
                                           p_filtro character varying DEFAULT '0=0'::character varying) returns SETOF record
    language plpgsql
as
$body$
/**************************************************************************
 SISTEMA:		Sistema Adendas
 FUNCION: 		ads.f_lista_funcionario_wf_sel
 DESCRIPCION:   Función que obtiene lista de funcionarios para el WF
 AUTOR: 		(valvarado)
 FECHA:	        14-08-2018
 COMENTARIOS:
***************************************************************************/

DECLARE
    g_registros               record;
    v_consulta                varchar;
    v_nombre_funcion          varchar;
    v_resp                    varchar;
    v_id_funcionario          integer;
    v_id_funcionario_superior integer;

BEGIN
    v_nombre_funcion = 'ads.f_lista_funcionario_wf_sel';

    select id_funcionario
    into
        v_id_funcionario
    from ads.tadendas
    where id_estado_wf = p_id_estado_wf;

    select ads.f_obtener_jefe_superior(v_id_funcionario) into v_id_funcionario_superior;

    IF not p_count then

        v_consulta := 'SELECT
                            fun.id_funcionario,
                            fun.desc_funcionario1 as desc_funcionario,
                            ''Gerente''::text  as desc_funcionario_cargo,
                            1 as prioridad
                         FROM orga.vfuncionario fun WHERE fun.id_funcionario = ' || v_id_funcionario_superior || '
                         and ' || p_filtro || '
                         limit ' || p_limit::varchar || ' offset ' || p_start::varchar;


        FOR g_registros in execute (v_consulta)
            LOOP
                RETURN NEXT g_registros;
            END LOOP;

    ELSE
        v_consulta = 'select
                                  COUNT(fun.id_funcionario) as total
                                 FROM orga.vfuncionario fun  WHERE fun.id_funcionario = ' || v_id_funcionario_superior || '
                                 and ' || p_filtro;

        FOR g_registros in execute (v_consulta)
            LOOP
                RETURN NEXT g_registros;
            END LOOP;


    END IF;

EXCEPTION

    WHEN OTHERS THEN
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
        raise exception '%',v_resp;


END ;
$body$;

alter function ads.f_lista_funcionario_wf_sel(integer, integer, date, integer, boolean, integer, integer, varchar) owner to dbavalvarado;

