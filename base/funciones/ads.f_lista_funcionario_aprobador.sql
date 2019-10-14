create or replace function ads.f_lista_funcionario_aprobador(p_id_usuario integer, p_id_tipo_estado integer,
                                                             p_fecha date DEFAULT now(),
                                                             p_id_estado_wf integer DEFAULT NULL::integer,
                                                             p_count boolean DEFAULT false, p_limit integer DEFAULT 1,
                                                             p_start integer DEFAULT 0,
                                                             p_filtro character varying DEFAULT '0=0'::character varying) returns SETOF record
    language plpgsql
as
$body$

DECLARE
    g_registros                 record;
    v_consulta                  varchar;
    v_nombre_funcion            varchar;
    v_resp                      varchar;
    v_i                         integer;
    v_reg_op                    record;
    va_id_uo                    integer[];
    v_id_subsistema             integer;
    v_tam                       integer;
    v_id_func_list              varchar;
    v_bandera_GG                varchar;
    v_bandera_GAF               varchar;
    v_record_gg                 varchar[];
    v_record_gaf                varchar[];
    v_fecha                     date;
    v_record_id_funcionario_gg  varchar;
    v_record_id_funcionario_gaf varchar;
    v_integer_gg                integer;

BEGIN
    v_nombre_funcion = 'adq.f_lista_funcionario_aprobador';

    select ad.nueva_fecha_fin,
           ad.id_funcionario,
           ad.total_pago as monto_pago_mb
    into
        v_reg_op
    from ads.tadendas ad
    where ad.id_estado_wf = p_id_estado_wf;

    WITH RECURSIVE path(id_funcionario, id_uo, presupuesta, gerencia, numero_nivel) AS (
        SELECT uofun.id_funcionario, uo.id_uo, uo.presupuesta, uo.gerencia, no.numero_nivel
        from orga.tuo_funcionario uofun
                 inner join orga.tuo uo
                            on uo.id_uo = uofun.id_uo
                 inner join orga.tnivel_organizacional no
                            on no.id_nivel_organizacional = uo.id_nivel_organizacional
        where uofun.fecha_asignacion <= v_reg_op.nueva_fecha_fin
          and (uofun.fecha_finalizacion is null or uofun.fecha_finalizacion >= v_reg_op.nueva_fecha_fin)
          and uofun.estado_reg = 'activo'
          and uofun.id_funcionario = v_reg_op.id_funcionario
        UNION

        SELECT uofun.id_funcionario, euo.id_uo_padre, uo.presupuesta, uo.gerencia, no.numero_nivel
        from orga.testructura_uo euo
                 inner join orga.tuo uo
                            on uo.id_uo = euo.id_uo_padre
                 inner join orga.tnivel_organizacional no
                            on no.id_nivel_organizacional = uo.id_nivel_organizacional
                 inner join path hijo
                            on hijo.id_uo = euo.id_uo_hijo
                 left join orga.tuo_funcionario uofun
                           on uo.id_uo = uofun.id_uo and uofun.estado_reg = 'activo' and
                              uofun.fecha_asignacion <= v_reg_op.nueva_fecha_fin
                               and
                              (uofun.fecha_finalizacion is null or uofun.fecha_finalizacion >= v_reg_op.nueva_fecha_fin)
    )
    SELECT pxp.aggarray(id_uo)
    into
        va_id_uo
    FROM path
    WHERE id_funcionario is not null
      and presupuesta = 'si';


    select s.id_subsistema
    into
        v_id_subsistema
    from segu.tsubsistema s
    WHERE s.codigo = 'ADQ'
      and s.estado_reg = 'activo';

    v_tam = array_upper(va_id_uo, 1);
    v_i = 1;
    v_id_func_list = '0';

    WHILE v_i <= v_tam
        LOOP

            select pxp.list(id_funcionario::varchar)
            into
                v_id_func_list
            from param.f_obtener_listado_aprobadores(va_id_uo[v_i],
                                                     null,
                                                     null,
                                                     v_id_subsistema,
                                                     v_reg_op.nueva_fecha_fin,
                                                     v_reg_op.monto_pago_mb,
                                                     p_id_usuario,
                                                     null
                     ) as
                     (id_aprobador integer,
                      id_funcionario integer,
                      fecha_ini date,
                      fecha_fin date,
                      desc_funcionario text,
                      monto_min numeric,
                      monto_max numeric,
                      prioridad integer);

            IF v_id_func_list != '0' and v_id_func_list is not null THEN
                v_i = v_tam + 1;
            END IF;
            v_i = v_i + 1;


        END LOOP;

    IF v_id_func_list is null THEN
        raise exception 'No se encontro un funcionario aprobador  para la unidad solictante y el monto (% MB) ',v_reg_op.monto_pago_mb;
    END IF;

    v_id_func_list = split_part(v_id_func_list, ',', 1);

    v_bandera_GG = pxp.f_get_variable_global('orga_codigo_gerencia_general');
    v_bandera_GAF = pxp.f_get_variable_global('orga_codigo_gerencia_financiera');

    v_fecha = now();
    v_record_gg = orga.f_obtener_gerente_x_codigo_uo(v_bandera_GG, v_fecha);
    v_record_id_funcionario_gg = v_record_gg[1];
    v_integer_gg = v_record_id_funcionario_gg::integer;

    v_record_gaf = orga.f_obtener_gerente_x_codigo_uo(v_bandera_GAF, v_fecha);
    v_record_id_funcionario_gaf = v_record_gaf[1];

    if ((v_reg_op.id_funcionario = v_id_func_list::integer) AND (v_reg_op.id_funcionario != v_integer_gg)) then
        v_i = 2;

        WHILE v_i <= v_tam
            LOOP

                select pxp.list(id_funcionario::varchar)
                into
                    v_id_func_list
                from param.f_obtener_listado_aprobadores(va_id_uo[v_i],
                                                         null,
                                                         null,
                                                         v_id_subsistema,
                                                         v_reg_op.nueva_fecha_fin,
                                                         v_reg_op.monto_pago_mb,
                                                         p_id_usuario,
                                                         null
                         ) as
                         (id_aprobador integer,
                          id_funcionario integer,
                          fecha_ini date,
                          fecha_fin date,
                          desc_funcionario text,
                          monto_min numeric,
                          monto_max numeric,
                          prioridad integer);

                IF v_id_func_list != '0' and v_id_func_list is not null THEN
                    v_i = v_tam + 1;
                END IF;
                v_i = v_i + 1;

            END LOOP;
    elseif (v_reg_op.id_funcionario = v_integer_gg) then
        v_id_func_list = v_record_id_funcionario_gaf;

    end if;

    IF not p_count then

        v_consulta := 'SELECT
                            fun.id_funcionario,
                            fun.desc_funcionario1 as desc_funcionario,
                            ''Gerente''::text  as desc_funcionario_cargo,
                            1 as prioridad
                         FROM orga.vfuncionario fun WHERE fun.id_funcionario in(' || v_id_func_list || ')
                         and ' || p_filtro || '
                         limit ' || p_limit::varchar || ' offset ' || p_start::varchar;


        FOR g_registros in execute (v_consulta)
            LOOP
                RETURN NEXT g_registros;
            END LOOP;

    ELSE
        v_consulta = 'select
                                  COUNT(fun.id_funcionario) as total
                                 FROM orga.vfuncionario fun  WHERE fun.id_funcionario in(' || v_id_func_list || ')
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

END;
$body$;

alter function ads.f_lista_funcionario_aprobador(integer, integer, date, integer, boolean, integer, integer, varchar) owner to postgres;