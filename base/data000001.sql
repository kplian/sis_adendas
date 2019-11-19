/********************************************I-DAT-VAN-ADS-0-17/10/2019********************************************/
INSERT INTO segu.tsubsistema ("codigo", "nombre", "prefijo", "estado_reg", "nombre_carpeta", "id_subsis_orig")
VALUES (E'ADS', E'Modificatorios', E'ADS', E'activo', E'sis_adendas', NULL);

select pxp.f_insert_tgui ('<i class="fa fa-file-text-o fa-2x"></i> MODIFICATORIOS', '', 'ADS', 'si', 1, '', 1, '', '', 'ADS');
select pxp.f_insert_tgui ('Modificatorios', 'Módulo Adendas', 'AD', 'si', 1, 'sis_adendas/vista/adendas/Adendas.php', 2, '', 'Adendas', 'ADS');

select wf.f_import_tproceso_macro ('insert','WF-AD', 'ADS', 'Sistema de Adendas','si');
select wf.f_import_tcategoria_documento ('insert','legales', 'Legales');
select wf.f_import_tcategoria_documento ('insert','proceso', 'Proceso');
select wf.f_import_ttipo_proceso ('insert','SEG-AD',NULL,NULL,'WF-AD','Seguimiento Adendas','ads.vadendas','id_adenda','si','','obligatorio','','SEG-AD',NULL);

select wf.f_import_ttipo_estado ('insert','borrador','SEG-AD','borrador','si','no','no','ninguno','','ninguno','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','',NULL);
select wf.f_import_ttipo_estado ('insert','pendiente','SEG-AD','pendiene','no','no','no','funcion_listado','ads.f_lista_funcionario_aprobador','anterior','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','',NULL);
select wf.f_import_ttipo_estado ('insert','aprobado','SEG-AD','aprobado','no','no','si','funcion_listado','ads.f_funcionario_wf_sel','ninguno','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','',NULL);
select wf.f_import_ttipo_estado ('insert','anulado','SEG-AD','anulado','no','no','si','anterior','','anterior','','','no','no',NULL,'<font color="99CC00" size="5"><font size="4">{TIPO_PROCESO}</font></font><br><br><b>&nbsp;</b>Tramite:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <b>{NUM_TRAMITE}</b><br><b>&nbsp;</b>Usuario :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {USUARIO_PREVIO} </b>en estado<b>&nbsp; {ESTADO_ANTERIOR}<br></b>&nbsp;<b>Responsable:&nbsp;&nbsp; &nbsp;&nbsp; </b><b>{FUNCIONARIO_PREVIO}&nbsp; {DEPTO_PREVIO}<br>&nbsp;</b>Estado Actual<b>: &nbsp; &nbsp;&nbsp; {ESTADO_ACTUAL}</b><br><br><br>&nbsp;{OBS} <br>','Aviso WF ,  {PROCESO_MACRO}  ({NUM_TRAMITE})','','no','','','','','','','',NULL);

select wf.f_import_ttipo_documento ('insert','DOCAD','SEG-AD','Documento respaldo','','','escaneado',1.00,'{}');
select wf.f_import_ttipo_documento ('insert','DOCADOC','SEG-AD','Adenda - Orden de compra ','','sis_adendas/control/ReportesOrden/reporteOrden/','generado',1.00,'{proceso}');
select wf.f_import_testructura_estado ('insert','borrador','pendiente','SEG-AD',1,'');
select wf.f_import_testructura_estado ('insert','pendiente','aprobado','SEG-AD',1,'');
/********************************************F-DAT-VAN-ADS-0-27/10/2019**********************************************/
/********************************************I-DAT-VAN-ADS-1-31/10/2019********************************************/
select pxp.f_insert_tgui ('Obligaciones de Pago', 'Obligaciones de Pago', 'ADOP', 'si', 1, 'sis_adendas/vista/obligacion_pago/ObligacionPagoAd.php', 2, '', 'ObligacionPagoAd', 'ADS');
/********************************************F-DAT-VAN-ADS-1-31/10/2019********************************************/
/********************************************I-DAT-VAN-ADS-2-13/11/2019********************************************/
select param.f_import_tdocumento ('insert','C_ADS','Correlativo adendas','ADS','depto','periodo','',NULL);
/********************************************F-DAT-VAN-ADS-2-13/11/2019********************************************/
/********************************************I-DAT-VAN-ADS-3-14/11/2019********************************************/
select pxp.f_insert_tgui('Parámetros', 'Parámetros', 'ADPARAM', 'si', 1, '', 2, '', '', 'ADS');
select pxp.f_insert_tgui('Tipos', 'Tipos de Modificatorios', 'ADTIPOS', 'si', 1, 'sis_adendas/vista/tipos/Tipos.php', 3,
                         '', 'Tipos', 'ADS');
insert into ads.ttipos(id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_usuario_ai, usuario_ai,
                       codigo, descripcion)
values (1, 1, now(), null, 'activo', 1, NULL, 'AD', 'Adenda');
insert into ads.ttipos(id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_usuario_ai, usuario_ai,
                       codigo, descripcion)
values (1, 1, now(), null, 'activo', 1, NULL, 'ADCORRECCION', 'Corrección');
insert into ads.ttipos(id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_usuario_ai, usuario_ai,
                       codigo, descripcion)
values (1, 1, now(), null, 'activo', 1, NULL, 'ADORDEN', 'Orden Modificatorio');
/********************************************F-DAT-VAN-ADS-3-14/11/2019********************************************/
/********************************************I-DAT-VAN-ADS-4-18/11/2019********************************************/
select pxp.f_insert_tgui ('Procesos', 'Procesos', 'ADPRO', 'si', 1, '', 2, '', '', 'ADS');
select pxp.f_insert_tgui ('Consultas', 'Consultas', 'ADCON', 'si', 1, '', 2, '', '', 'ADS');
/********************************************F-DAT-VAN-ADS-4-18/11/2019********************************************/
/********************************************I-DAT-VAN-ADS-5-18/11/2019********************************************/
select pxp.f_insert_tgui ('Visto Bueno Modificatorios', 'Visto Bueno Modificatorios', 'ADVOBO', 'si', 2, 'sis_adendas/vista/adendas/AdendasVoBo.php', 3, '', 'AdendasVoBo', 'ADS');
/********************************************F-DAT-VAN-ADS-5-18/11/2019********************************************/