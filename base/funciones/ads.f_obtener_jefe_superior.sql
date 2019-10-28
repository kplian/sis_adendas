create or replace function ads.f_obtener_jefe_superior(param_id_funcionario integer) returns integer
    stable
    language plpgsql
as
$body$
/**************************************************************************
 SISTEMA:		Sistema Adendas
 FUNCION: 		ads.f_obtener_jefe_superior
 DESCRIPCION:   Función que retorna el id del jefe superior de un funcionario
 AUTOR: 		(valvarado)
 FECHA:	        13-08-2019 18:30:00
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 ***************************************************************************/

DECLARE
    v_resp           varchar;
    v_nombre_funcion text;
    v_record         record;
    v_id_funcionario integer;
    v_monto_min      numeric;
    v_monto_max      numeric;
BEGIN
    v_nombre_funcion = 'asis.f_get_funcionario_boss';

    for v_record in (with recursive funcionario_apro(id_funcionario,
                                                     id_uo,
                                                     presupuesta,
                                                     gerencia,
                                                     numero_nivel, nombre_nivel) AS (
        select uofun.id_funcionario,
               uo.id_uo,
               uo.presupuesta,
               uo.gerencia,
               no.numero_nivel,
               no.nombre_nivel
        from orga.tuo_funcionario uofun
                 inner join orga.tuo uo on uo.id_uo = uofun.id_uo
                 inner join orga.tnivel_organizacional no on no.id_nivel_organizacional = uo.id_nivel_organizacional
        where uofun.fecha_asignacion <= now()::date
          and (uofun.fecha_finalizacion is null or uofun.fecha_finalizacion >= now()::date)
          and uofun.estado_reg = 'activo'
          and uofun.id_funcionario = 525
        union
        select uofun.id_funcionario,
               euo.id_uo_padre,
               uo.presupuesta,
               uo.gerencia,
               no.numero_nivel,
               no.nombre_nivel
        from orga.testructura_uo euo
                 inner join orga.tuo uo on uo.id_uo = euo.id_uo_padre
                 inner join orga.tnivel_organizacional no on no.id_nivel_organizacional = uo.id_nivel_organizacional
                 inner join funcionario_apro hijo on hijo.id_uo = euo.id_uo_hijo
                 left join orga.tuo_funcionario uofun on uo.id_uo = uofun.id_uo and uofun.estado_reg = 'activo'
                 left join param.taprobador apro on apro.id_funcionario = uofun.id_funcionario
            and uofun.fecha_asignacion <= now()::date
            and (uofun.fecha_finalizacion is null or uofun.fecha_finalizacion >= now()::date)
            and hijo.presupuesta = 'si')
                     select f.id_funcionario,
                            f.presupuesta,
                            f.numero_nivel
                     from funcionario_apro f
                     where f.numero_nivel <= 6
                       and f.numero_nivel != 0
                     order by numero_nivel desc)
        loop


            select monto_min, monto_max
            into v_monto_min, v_monto_max
            from param.taprobador apro
            where apro.id_funcionario = v_record.id_funcionario;
            raise exception 'MENSAJE ENTRO LOOP %,  % , %',v_record.id_funcionario, v_monto_max, v_monto_min;
            if v_record.presupuesta = 'si' and v_monto_min < 50008 and v_monto_max > 50008 then
                raise exception 'MENSAJE ENTRO';
                if v_record.id_funcionario is not null and v_record.numero_nivel = 2 then
                    v_id_funcionario = v_record.id_funcionario;
                    RETURN v_id_funcionario;
                elsif v_record.id_funcionario is not null and v_record.numero_nivel = 4 then
                    v_id_funcionario = v_record.id_funcionario;
                    RETURN v_id_funcionario;
                elsif v_record.id_funcionario is not null and v_record.numero_nivel = 5 then
                    v_id_funcionario = v_record.id_funcionario;
                    RETURN v_id_funcionario;
                elsif v_record.id_funcionario is not null and v_record.numero_nivel = 6 then
                    v_id_funcionario = v_record.id_funcionario;
                    RETURN v_id_funcionario;
                end if;
            end if;

        end loop;
    RETURN v_id_funcionario;

EXCEPTION

    WHEN OTHERS THEN
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimientos', v_nombre_funcion);
        raise exception '%',v_resp;

END;
$body$;

alter function ads.f_obtener_jefe_superior(integer) owner to postgres;

