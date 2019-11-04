/********************************************I-DEP-VAN-ADS-0-17/01/2019*************************************/
select pxp.f_insert_testructura_gui ('ADS', 'SISTEMA');
select pxp.f_insert_testructura_gui ('AD', 'ADS');

create trigger trig_adenda_det_total_pago
	after insert or update or delete
	on ads.tadenda_det
	for each row
	execute procedure ads.f_update_adenda_total_pago();
--SQL
CREATE OR REPLACE VIEW ads.detalle_adenda(
    id_adenda,
    centro_costos,
    nombre_partida,
    precio_total)
AS
  SELECT obdet.id_adenda,
         (cec.descripcion::text || ' '::text) || cec.gestion AS centro_costos,
         ((par.nombre_partida::text || '-('::text) || par.codigo::text) || ')'::
           text AS nombre_partida,
         sum(obdet.monto_pago_mo) AS precio_total
  FROM ads.tadenda_det obdet
       JOIN segu.tusuario usu1 ON usu1.id_usuario = obdet.id_usuario_reg
       LEFT JOIN segu.tusuario usu2 ON usu2.id_usuario = obdet.id_usuario_mod
       LEFT JOIN pre.tpartida par ON par.id_partida = obdet.id_partida
       LEFT JOIN param.tconcepto_ingas cig ON cig.id_concepto_ingas =
         obdet.id_concepto_ingas
       LEFT JOIN param.vcentro_costo cc ON cc.id_centro_costo =
         obdet.id_centro_costo
       LEFT JOIN conta.torden_trabajo ot ON ot.id_orden_trabajo =
         obdet.id_orden_trabajo
       LEFT JOIN pre.vpresupuesto_cc cec ON cec.id_centro_costo =
         obdet.id_centro_costo
  WHERE obdet.estado_reg::text = 'activo'::text
  GROUP BY obdet.id_adenda,
           cec.descripcion,
           cec.gestion,
           par.nombre_partida,
           par.codigo;
--SQL
select wf.f_import_ttipo_documento_estado ('insert','DOCAD','SEG-AD','borrador','SEG-AD','crear','superior','');
select wf.f_import_ttipo_documento_estado ('insert','DOCAD','SEG-AD','aprobado','SEG-AD','exigir','superior','');
select wf.f_import_ttipo_documento_estado ('insert','DOCADOC','SEG-AD','borrador','SEG-AD','insertar','superior','"{$tabla.es_contrato}"= "no"');
select wf.f_import_ttipo_documento_estado ('insert','DOCADOC','SEG-AD','aprobado','SEG-AD','crear','superior','"{$tabla.es_contrato}"= "no"');
select wf.f_import_ttipo_proceso_origen ('insert','SEG-AD','WF-AD','TOPD','en_pago','manual','');
--SQL
create or replace VIEW ads.vadendas
            (id_adenda,
             id_usuario_reg,
             id_usuario_mod,
             fecha_reg,
             fecha_mod,
             estado_reg,
             id_usuario_ai,
             usuario_ai,
             id_obligacion_pago,
             id_funcionario,
             id_estado_wf,
             id_proceso_wf,
             estado,
             num_tramite,
             total_pago,
             nueva_fecha_fin,
             observacion,
             numero,
             numero_adenda,
             id_contrato_adenda,
             es_contrato)
as
select id_adenda,
       id_usuario_reg,
       id_usuario_mod,
       fecha_reg,
       fecha_mod,
       estado_reg,
       id_usuario_ai,
       usuario_ai,
       id_obligacion_pago,
       id_funcionario,
       id_estado_wf,
       id_proceso_wf,
       estado,
       num_tramite,
       total_pago,
       nueva_fecha_fin,
       observacion,
       numero,
       numero_adenda,
       id_contrato_adenda,
       (case when id_contrato_adenda is not null then 'si' else 'no' end) as es_contrato
from ads.tadendas ad
where ad.estado_reg::text = 'activo'::text;
/********************************************F-DEP-VAN-ADS-0-17/10/2019*************************************/
/********************************************I-DEP-VAN-ADS-1-31/10/2019*************************************/
select pxp.f_insert_testructura_gui ('ADOP', 'ADS');
/********************************************F-DEP-VAN-ADS-1-31/10/2019*************************************/
/********************************************I-DEP-VAN-ADS-2-04/11/2019*************************************/
alter table tes.tobligacion_pago add bloqueado integer default 0;
/********************************************F-DEP-VAN-ADS-2-04/11/2019*************************************/