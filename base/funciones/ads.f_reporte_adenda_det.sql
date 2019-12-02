CREATE OR REPLACE FUNCTION ads.f_reporte_adenda_det (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
declare
    v_nombre_funcion varchar = 'ads.f_reporte_adenda_det';
    v_resp           varchar;
    v_consulta       varchar;
    v_parametros     record;
    v_id_adenda      integer;
begin
    v_parametros = pxp.f_get_record(p_tabla);

    select id_adenda into v_id_adenda from ads.tadendas where id_proceso_wf = v_parametros.id_proceso_wf;

    if (p_transaccion = 'ADS_RPT_DETALLES') then

        v_consulta = 'select centro_costo, nombre_partida, monto_anterior, nuevo_monto, monto_operacion, estado, descripcion
                        from ads.f_clasificar_adenda_det()
                        where id_adenda =' || v_id_adenda;
        return v_consulta;

    elsif (p_transaccion = 'ADS_RPT_PRESUS') then

        v_consulta =
                    'select techo, centro_costo, nombre_partida, monto_operacion, disponible from ads.f_verificar_presupuesto() where id_adenda = ' ||
                    v_id_adenda;
        return v_consulta;

    end if;
exception
    when others then
        v_resp = '';
        v_resp = pxp.f_agrega_clave(v_resp, 'mensaje', SQLERRM);
        v_resp = pxp.f_agrega_clave(v_resp, 'codigo_error', SQLSTATE);
        v_resp = pxp.f_agrega_clave(v_resp, 'procedimiento', v_nombre_funcion);
        raise exception '%', v_resp;
end;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION ads.f_reporte_adenda_det (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;