<?php
/**
 * @package pXP
 * @file AdendaDet.php
 * @author (valvarado)
 * @date 24-06-2019 15:15:06
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Ext.Loader.load(['/etr/sis_adendas/assets/conf.js',
        '/etr/sis_adendas/assets/js/dialog.messages.js',
        '/etr/sis_adendas/assets/js/utils.js'], false);

    Phx.vista.Adendas = Ext.extend(Phx.gridInterfaz, {
        nombreVista: 'Adendas',
        fheight: '50%',
        cls: 'Adendas',
        fwidth: '30%',
        tam_pag: 50,
        title: 'Modificatorios',
        ActSave: '../../sis_adendas/control/Adendas/insertar',
        ActDel: '../../sis_adendas/control/Adendas/eliminar',
        ActList: '../../sis_adendas/control/Adendas/listar',
        id_store: 'id_adenda',
        bdel: true,
        bsave: false,
        bedit: true,
        bnew: true,
        bexcel: false,
        Atributos: [
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_adenda'
                },
                type: 'Field',
                form: true
            },
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_obligacion_pago'
                },
                type: 'Field',
                form: true
            },
            {
                config: {
                    name: 'num_tramite',
                    fieldLabel: 'Num. Tramite',
                    allowBlank: true,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 200
                },
                type: 'TextField',
                filters: {pfiltro: 'ad.num_tramite', type: 'string'},
                id_grupo: 1,
                bottom_filter: true,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'numero',
                    fieldLabel: 'Número',
                    allowBlank: true,
                    readOnly: true,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 200
                },
                type: 'TextField',
                filters: {pfiltro: 'ad.numero', type: 'string'},
                id_grupo: 1,
                bottom_filter: true,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_tipo',
                    fieldLabel: 'Tipo',
                    emptyText: 'Tipo..',
                    typeAhead: true,
                    lazyRender: true,
                    allowBlank: false,
                    mode: 'remote',
                    gwidth: 180,
                    anchor: '100%',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_adendas/control/Tipos/listar',
                        id: 'id_tipo',
                        root: 'datos',
                        sortInfo: {
                            field: 'id_tipo',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_tipo', 'codigo', 'descripcion'],
                        // turn on remote sorting
                        remoteSort: true,
                        baseParams: {par_filtro: 'tipos.id_tipo#tipos.codigo#tipos.descripcion'}
                    }),
                    valueField: 'id_tipo',
                    displayField: 'descripcion',
                    gdisplayField: 'descripcion',
                    hiddenName: 'id_tipo',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 10,
                    queryDelay: 1000,
                    resizable: true,
                    minChars: 1,
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['descripcion']);
                    }
                },
                type: 'ComboBox',
                id_grupo: 2,
                filters: {pfiltro: 't.descripcion', type: 'string'},
                grid: true,
                form: true,
                bottom_filter: true
            },
            {
                config: {
                    name: 'id_contrato_adenda',
                    fieldLabel: 'N&uacute;mero Adenda',
                    allowBlank: false,
                    emptyText: 'Numero Adenda...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_adendas/control/Contratos/listar',
                        id: 'id_contrato',
                        root: 'datos',
                        sortInfo: {
                            field: 'numero',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_contrato', 'numero', 'id_contrato_fk'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'id_contrato#numero'}
                    }),
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['numero']);
                    },
                    tpl: '<tpl for=".">\
		                       <div class="x-combo-list-item"><p><b>Contrato: </b>{numero}</p></div>\		                       \
		                       </tpl>',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    listWidth: 500,
                    resizable: true,
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 10,
                    queryDelay: 1000,
                    width: 250,
                    gwidth: 250,
                    minChars: 2,
                    anchor: '100%',
                    qtip: 'N&uacute;mero de la adenda originada en el departamento legal'
                },
                type: 'ComboBox',
                bottom_filter: false,
                filters: {pfiltro: 'cont.numero', type: 'string'},
                id_grupo: 2,
                grid: false,
                form: false
            },
            {
                config: {
                    name: 'id_funcionario',
                    hiddenName: 'id_funcionario',
                    origen: 'FUNCIONARIOCAR',
                    fieldLabel: 'Funcionario Solicitante',
                    allowBlank: false,
                    width: '100%',
                    anchor: '100%',
                    valueField: 'id_funcionario',
                    gdisplayField: 'desc_funcionario1',
                    baseParams: {par_filtro: 'id_funcionario#desc_funcionario1'},
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_funcionario']);
                    }
                },
                type: 'ComboRec',
                valueField: 'id_funcionario',
                gdisplayField: 'fun.desc_funcionario1',
                typeAhead: false,
                triggerAction: 'all',
                listWidth: 500,
                resizable: true,
                lazyRender: false,
                lazyInit: false,
                mode: 'remote',
                pageSize: 10,
                queryDelay: 1000,
                minChars: 3,
                anchor: '100%',
                id_grupo: 2,
                filters: {pfiltro: 'fun.desc_funcionario1', type: 'string'},
                bottom_filter: false,
                grid: false,
                form: true
            },
            {
                config: {
                    name: 'fecha_entrega',
                    fieldLabel: 'Fecha Entrega',
                    allowBlank: false,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 1000
                },
                type: 'Datefield',
                filters: {pfiltro: 'fecha_entrega', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'estado',
                    fieldLabel: 'estado',
                    allowBlank: true,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 50
                },
                type: 'TextField',
                filters: {pfiltro: 'estado', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'total_pago',
                    currencyChar: ' ',
                    fieldLabel: 'Total a Pagar',
                    allowBlank: false,
                    width: '100%',
                    maxLength: 1245184
                },
                type: 'MoneyField',
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'observacion',
                    fieldLabel: 'Desc',
                    allowBlank: false,
                    qtip: 'Descripcion del objetivo del pago, o Si el proveedor es PASAJEROS PERJUDICADOS aqui va el nombre del pasajero',
                    anchor: '100%',
                    width: '100%',
                    maxLength: 1000
                },
                type: 'TextArea',
                filters: {pfiltro: 'observacion', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'desc_funcionario1',
                    fieldLabel: 'Funcionario',
                    allowBlank: true,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 1000
                },
                type: 'field',
                filters: {pfiltro: 'desc_funcionario1', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'numero_modificatorio',
                    fieldLabel: 'N&uacute;mero Modificatorio',
                    allowBlank: true,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 1000
                },
                type: 'field',
                filters: {pfiltro: 'numero_modificatorio', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_informe',
                    fieldLabel: 'Fecha Informe',
                    allowBlank: true,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 1000
                },
                type: 'Datefield',
                filters: {pfiltro: 'fecha_informe', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'lugar_entrega',
                    fieldLabel: 'Lugar Entrega',
                    allowBlank: true,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 1000
                },
                type: 'field',
                filters: {pfiltro: 'numero_modificatorio', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'forma_pago',
                    fieldLabel: 'Forma Pago',
                    allowBlank: true,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 1000
                },
                type: 'field',
                filters: {pfiltro: 'forma_pago', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'glosa',
                    fieldLabel: 'Glosa',
                    allowBlank: true,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 1000
                },
                type: 'field',
                filters: {pfiltro: 'glosa', type: 'string'},
                id_grupo: 1,
                grid: false,
                form: false
            },
        ],
        fields: [
            {name: 'id_adenda', type: 'numeric'},
            {name: 'id_obligacion_pago', type: 'numeric'},
            {name: 'id_estado_wf', type: 'numeric'},
            {name: 'id_proceso_wf', type: 'numeric'},
            {name: 'id_funcionario', type: 'numeric'},
            {name: 'id_depto', type: 'numeric'},
            {name: 'estado', type: 'string'},
            {name: 'estado_reg', type: 'string'},
            {name: 'num_tramite', type: 'string'},
            {name: 'total_pago', type: 'mumeric'},
            {name: 'fecha_entrega', type: 'string'},
            {name: 'observacion', type: 'string'},
            {name: 'numero', type: 'string'},
            {name: 'numero_modificatorio', type: 'string'},
            {name: 'fecha_informe', type: 'string'},
            {name: 'lugar_entrega', type: 'string'},
            {name: 'forma_pago', type: 'string'},
            {name: 'glosa', type: 'string'},
            {name: 'nombre_depto', type: 'string'},
            {name: 'numero_contrato', type: 'string'},
            {name: 'desc_funcionario1', type: 'string'},
            {name: 'tipo', type: 'string'},
            {name: 'numero_adenda', type: 'string'},
            {name: 'id_contrato_adenda', type: 'numeric'},
            {name: 'id_tipo', type: 'numeric'},
            {name: 'descripcion', type: 'string'},
            {name: 'codigo', type: 'string'}
        ],
        arrayDefaultColumHidden: [],
        rowExpander: new Ext.ux.grid.RowExpander({
            tpl: new Ext.Template('<br>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Obligación de pago:&nbsp;&nbsp;</b> {numero}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Monto Total:&nbsp;&nbsp;</b> {total_pago:number("0,000.00")}   {moneda}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Contrato:&nbsp;&nbsp;</b> {numero_contrato}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Depto:&nbsp;&nbsp;</b> {nombre_depto}</p>',
            )
        }),
        sortInfo: {
            field: 'id_adenda',
            direction: 'DESC'
        },
        tabsouth: [
            {
                url: '../../../sis_adendas/vista/adenda_det/AdendaDet.php',
                title: 'Detalle',
                height: '50%',
                cls: 'AdendaDet'
            },
            {
                url: '../../../sis_adendas/vista/plan_pago/PlanPagoAd.php',
                title: 'Plan de Pagos',
                height: '50%',
                cls: 'PlanPagoAd'
            }
        ],
        constructor: function (config) {
            this.maestro = config;
            //llama al constructor de la clase padre
            Phx.vista.Adendas.superclass.constructor.call(this, config);
            this.init();
            this.store.baseParams = {
                nombreVista: this.nombreVista,
                id_adenda: this.maestro.id_adenda
            };

            if (config.filtro_directo) {
                this.store.baseParams.filtro_valor = config.filtro_directo.valor;
                this.store.baseParams.filtro_campo = config.filtro_directo.campo;
            }
            this.load({params: {start: 0, limit: this.tam_pag, nombreVista: this.nombreVista}});
            this.addBotones();
            this.iniciarEventos();
            this.getBoton('diagrama_gantt').disable();
            this.getBoton('sig_estado').disable();
            this.getBoton('ant_estado').disable();
        },
        liberaMenu: function () {
            var tb = Phx.vista.Adendas.superclass.liberaMenu.call(this);
            if (tb) {
                this.getBoton('btnChequeoDocumentosWf').disable();
                this.getBoton('diagrama_gantt').disable();
                this.getBoton('chkpresupuesto').disable();
                this.getBoton('btnSolicitud').disable();
                this.getBoton('btnOrden').disable();
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
            }
            return tb
        },
        preparaMenu: function (n) {
            var data = this.getSelectedData();
            var tb = this.tbar;
            Phx.vista.Adendas.superclass.preparaMenu.call(this, n);
            if (data) {
                this.getBoton('btnChequeoDocumentosWf').enable();
                this.getBoton('diagrama_gantt').enable();
                this.getBoton('chkpresupuesto').enable();
                this.getBoton('btnSolicitud').enable();
                this.getBoton('btnOrden').enable();
                switch (data.estado) {
                    case 'aprobado': {
                        this.getBoton('sig_estado').disable();
                        this.getBoton('edit').disable();
                        this.getBoton('del').disable();
                        this.getBoton('btnChequeoDocumentosWf').disable();
                        break;
                    }
                    case 'anulado': {
                        this.getBoton('btnChequeoDocumentosWf').disable();
                        this.getBoton('sig_estado').disable();
                        this.getBoton('ant_estado').disable();
                        this.getBoton('edit').disable();
                        this.getBoton('del').disable();
                        this.getBoton('btnChequeoDocumentosWf').disable();
                        break;
                    }
                    case 'pendiente': {
                        this.getBoton('sig_estado').disable();
                        this.getBoton('ant_estado').disable();
                        this.getBoton('edit').disable();
                        this.getBoton('del').disable();
                        this.getBoton('btnChequeoDocumentosWf').disable();
                        break;
                    }
                    case 'borrador': {
                        this.getBoton('sig_estado').enable();
                        this.getBoton('ant_estado').disable();
                        this.getBoton('edit').enable();
                        break;
                    }
                    default: {
                        this.getBoton('btnChequeoDocumentosWf').disable();
                        this.getBoton('sig_estado').disable();
                        this.getBoton('ant_estado').disable();
                        this.getBoton('edit').disable();
                        this.getBoton('del').disable();
                        this.getBoton('btnChequeoDocumentosWf').disable();
                    }
                }

            }
            return tb
        },
        addBotones: function () {
            this.addButton('btnReporte',
                {
                    id: 'b-btnSolicitud-' + this.idContenedor,
                    text: 'Reporte',
                    iconCls: 'bpdf32',
                    tooltip: '<b>Reporte de Solicitud de Compra</b>',
                    handler: this.onButtonSolicitud,
                    scope: this
                }
            );
            this.addButton('btnReporte',
                {
                    id: 'b-btnOrden-' + this.idContenedor,
                    text: 'Reporte OC',
                    iconCls: 'bpdf32',
                    tooltip: '<b>Reporte de Orden</b>',
                    handler: this.onButtonOrden,
                    scope: this
                }
            );
            this.addButton('chkpresupuesto', {
                grupo: [0],
                text: 'Presupuesto',
                iconCls: 'blist',
                tooltip: '<b>Revisar Presupuesto</b><p>Revisar estado de ejecución presupeustaria para este  trámite</p>',
                handler: this.wndowsCheckPresupuesto,
                scope: this
            });
            this.addButton('btnChequeoDocumentosWf',
                {
                    text: 'Documentos',
                    grupo: [0],
                    iconCls: 'bchecklist',
                    disabled: true,
                    handler: this.loadCheckDocumentosSolWf,
                    tooltip: '<b>Documentos de la Solicitud</b><br/>Subir los documetos requeridos en la solicitud seleccionada.'
                }
            );
            this.addButton('ant_estado', {
                argument: {estado: 'anterior'},
                text: 'Anterior',
                grupo: [0],
                iconCls: 'batras',
                disabled: true,
                handler: this.antEstado,
                tooltip: '<b>Pasar al Anterior Estado</b>'
            });
            this.addButton('sig_estado', {
                grupo: [0],
                text: 'Siguiente',
                iconCls: 'badelante',
                disabled: true,
                handler: this.sigEstado,
                tooltip: '<b>Pasar al Siguiente Estado</b>'
            });
            this.addBotonesGantt();
        },

        onButtonSolicitud: function () {
            var rec = this.sm.getSelected();
            Ext.Ajax.request({
                url: '../../sis_adendas/control/ReportesAdenda/reporteAdendas',
                params: {'id_proceso_wf': rec.data.id_proceso_wf, 'estado': rec.data.estado},
                success: this.successExport,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        onButtonOrden: function () {
            var rec = this.sm.getSelected();
            Ext.Ajax.request({
                url: '../../sis_adendas/control/ReportesOrden/reporteOrden',
                params: {
                    id_adenda: rec.data.id_adenda,
                    'id_proceso_wf': rec.data.id_proceso_wf,
                    'estado': rec.data.estado
                },
                success: this.successExport,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        sigEstado: function () {
            var rec = this.sm.getSelected();
            this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
                'Estado de Wf',
                {
                    modal: true,
                    width: 700,
                    height: 450
                }, {
                    data: {
                        id_estado_wf: rec.data.id_estado_wf,
                        id_proceso_wf: rec.data.id_proceso_wf,
                        id_adenda: rec.data.id_adenda
                    }
                }, this.idContenedor, 'FormEstadoWf',
                {
                    config: [{
                        event: 'beforesave',
                        delegate: this.onSaveWizard
                    }],
                    scope: this
                });
        },
        onSaveWizard: function (wizard, resp) {
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_adendas/control/Adendas/siguienteEstadoAdenda',
                params: {
                    id_adenda: wizard.data.id_adenda,
                    id_proceso_wf_act: resp.id_proceso_wf_act,
                    id_estado_wf_act: resp.id_estado_wf_act,
                    id_tipo_estado: resp.id_tipo_estado,
                    id_funcionario_wf: resp.id_funcionario_wf,
                    id_depto_wf: resp.id_depto_wf,
                    obs: resp.obs,
                    json_procesos: Ext.util.JSON.encode(resp.procesos)
                },
                success: this.successWizard,
                failure: this.failureWizard,
                argument: {wizard: wizard},
                timeout: this.timeout,
                scope: this
            });
        },
        successWizard: function (wizard, resp) {
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
        },
        failureWizard: function (res1, res2, res3, res4, res5) {
            var reg = utils.serialize(res1);
            Phx.CP.loadingHide();
            adendas.dialog.error(reg.ROOT.detalle.mensaje);
        },
        onButtonNew: function () {
            var self = this;
            self.winObligacionPago = Phx.CP.loadWindows('../../../sis_adendas/vista/obligacion_pago/ObligacionPago.php',
                'Listado Obligaciones de Pago',
                {
                    modal: true,
                    width: '70%',
                    height: '50%',
                    closeAction: "close"
                },
                {parent: self},
                this.idContenedor,
                'ObligacionPago');

        },
        onButtonEdit: function (n) {
            var self = this;
            var record = this.getSelectedData();
            self.fheight = self.calTamPor(self.fheight, Ext.getBody());
            self.winNuevaAdenda = Phx.CP.loadWindows('../../../sis_adendas/vista/adendas/NuevaAdenda.php',
                'Editar Modificatorio',
                {
                    modal: true,
                    width: "60%",
                    height: "70%",
                    closeAction: "close"
                },
                {adenda: record},
                self.idContenedor,
                'NuevaAdenda',
                {
                    config: [{
                        event: 'successsave',
                        delegate: self.guardarNuevaAdenda,

                    }],

                    scope: self
                });
        },
        refreshGrid: function () {
            this.reload()
        },
        addBotonesGantt: function () {
            this.menuAdqGantt = new Ext.Toolbar.SplitButton({
                id: 'b-diagrama_gantt-' + this.idContenedor,
                text: 'Gantt',
                disabled: false,
                grupo: [0, 1, 2],
                iconCls: 'bgantt',
                handler: this.diagramGanttDinamico,
                scope: this,
                menu: {
                    items: [{
                        id: 'b-gantti-' + this.idContenedor,
                        text: 'Gantt Imagen',
                        tooltip: '<b>Mues un reporte gantt en formato de imagen</b>',
                        handler: this.diagramGantt,
                        scope: this
                    }, {
                        id: 'b-ganttd-' + this.idContenedor,
                        text: 'Gantt Dinámico',
                        tooltip: '<b>Muestra el reporte gantt facil de entender</b>',
                        handler: this.diagramGanttDinamico,
                        scope: this
                    }
                    ]
                }
            });
            this.tbar.add(this.menuAdqGantt);
        },
        iniciarEventos: function () {

        },
        diagramGantt: function () {
            var data = this.sm.getSelected().data.id_proceso_wf;
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_workflow/control/ProcesoWf/diagramaGanttTramite',
                params: {'id_proceso_wf': data},
                success: this.successExport,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        diagramGanttDinamico: function () {
            var data = this.sm.getSelected().data.id_proceso_wf;
            window.open('../../../sis_workflow/reportes/gantt/gantt_dinamico.html?id_proceso_wf=' + data)
        },
        loadCheckDocumentosSolWf: function () {
            var rec = this.sm.getSelected();
            rec.data.nombreVista = this.nombreVista;
            Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
                'Documentos del Proceso',
                {
                    width: '90%',
                    height: 500
                },
                rec.data,
                this.idContenedor,
                'DocumentoWf'
            )
        },
        wndowsCheckPresupuesto: function () {
            var rec = this.sm.getSelected();
            var configExtra = [];
            this.objChkPres = Phx.CP.loadWindows('../../../sis_presupuestos/vista/presup_partida/ChkPresupuesto.php',
                'Estado del Presupuesto',
                {
                    modal: true,
                    width: 700,
                    height: 450
                }, {
                    data: {
                        nro_tramite: rec.data.num_tramite
                    }
                }, this.idContenedor, 'ChkPresupuesto');

        },
        antEstado: function (res) {
            var data = this.getSelectedData();
            Phx.CP.loadingHide();
            Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
                'Estado de Wf',
                {
                    modal: true,
                    width: 450,
                    height: 250
                },
                {
                    data: data,
                    estado_destino: res.argument.estado
                },
                this.idContenedor, 'AntFormEstadoWf',
                {
                    config: [{
                        event: 'beforesave',
                        delegate: this.onAntEstado,
                    }],
                    scope: this
                });

        },
        onAntEstado: function (wizard, resp) {
            Phx.CP.loadingShow();
            var operacion = 'cambiar';

            Ext.Ajax.request({
                url: '../../sis_adendas/control/Adendas/anteriorEstadoAdenda',
                params: {
                    id_adenda: wizard.data.id_adenda,
                    id_proceso_wf: resp.id_proceso_wf,
                    id_estado_wf: resp.id_estado_wf,
                    obs: resp.obs,
                    operacion: operacion
                },
                argument: {wizard: wizard},
                success: this.successAntEstado,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },

        successAntEstado: function (resp) {
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();

        },
    });

</script>