<?php
/**
 * @package pXP
 * @file NuevaAdenda.php
 * @author  (valvarado)
 * @date 24-06-2019 15:15:06
 * @description Permite la creacion de adendas
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Ext.Loader.load(['../../../sis_adendas/assets/conf.js',
        '../../../sis_adendas/assets/js/dialog.messages.js',
        '../../../sis_adendas/assets/js/utils.js'], true);
    Phx.vista.NuevaAdenda = Ext.extend(Phx.frmInterfaz, {
        nombreVista: 'NuevaAdenda',
        tam_pag: 50,
        title: 'Nueva Adenda',
        bsubmit: true,
        bcancel: true,
        breset: false,
        Atributos: [
            {
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_obligacion_pago',
                    width: '100%'
                },
                type: 'Field',
                id_group: 0,
                form: true
            },
            {
                config: {
                    name: 'num_tramite',
                    fieldLabel: 'Num. Tramite',
                    allowBlank: true,
                    readOnly: true,
                    width: '100%',
                    maxLength: 300
                },
                type: 'TextField',
                id_group: 0,
                form: true
            },
            {
                config: {
                    name: 'proveedor',
                    fieldLabel: 'Proveedor',
                    allowBlank: true,
                    readOnly: true,
                    width: '100%',
                    maxLength: 300
                },
                type: 'TextField',
                id_group: 0,
                form: true
            },
            {
                config: {
                    name: 'estado',
                    fieldLabel: 'estado',
                    readOnly: true,
                    width: '100%'
                },
                type: 'Field',
                id_group: 0,
                form: true
            },
            {
                config: {
                    name: 'total_pago',
                    currencyChar: ' ',
                    fieldLabel: 'Total a Pagar',
                    readOnly: true,
                    width: '100%'
                },
                type: 'MoneyField',
                id_group: 0,
                form: true
            },
            {
                config: {
                    name: 'numero_contrato',
                    fieldLabel: 'N&uacute;mero de orden/Contrato',
                    allowBlank: true,
                    width: '100%',
                    anchor: '100%',
                    readOnly: true,
                },
                type: 'Field',
                id_grupo: 1,
                form: true
            },
            {
                config: {
                    name: 'fecha_fin',
                    fieldLabel: 'Fecha Estimada Fin',
                    allowBlank: true,
                    width: '100%',
                    anchor: '100%',
                    readOnly: true,
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat(conf.format_date) : ''
                    }
                },
                type: 'Field',
                id_grupo: 1,
                grid: true,
                form: true
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
                        baseParams: {par_filtro: 'numero'}
                    }),
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['numero']);
                    },
                    tpl: '<tpl for=".">\
		                       <div class="x-combo-list-item"><p><b>Contrato: </b>{numero}</p></div>\		                       \
		                       </tpl>',
                    valueField: 'id_contrato',
                    displayField: 'numero',
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
                form: true
            },
            {
                config: {
                    name: 'fecha_soli',
                    inputType: 'hidden',
                    fieldLabel: 'Fecha Sol.',
                    allowBlank: false,
                    disabled: false,
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'fecha_soli', type: 'date'},
                id_grupo: 1,
                grid: false,
                form: true
            },
            {
                config: {
                    name: 'id_funcionario',
                    hiddenName: 'id_funcionario',
                    origen: 'FUNCIONARIO',
                    fieldLabel: 'Funcionario',
                    allowBlank: false,
                    width: '100%',
                    anchor: '100%',
                    valueField: 'id_funcionario',
                    gdisplayField: 'desc_funcionario',
                    baseParams: {es_combo_solicitud: 'si'},
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_funcionario']);
                    }
                },
                type: 'ComboRec',
                id_grupo: 2,
                filters: {pfiltro: 'fun.desc_funcionario1', type: 'string'},
                bottom_filter: true,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'nueva_fecha_fin',
                    fieldLabel: 'Nueva Fecha fin',
                    allowBlank: false,
                    width: '100%',
                    anchor: '100%',
                    format: conf.format_date,
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat(conf.format_date) : ''
                    }
                },
                type: 'DateField',
                id_grupo: 2,
                form: true
            },
            {
                config: {
                    name: 'observacion',
                    fieldLabel: 'Observaci&oacute;n',
                    allowBlank: false,
                    qtip: 'Observaciones y motivos de la generación de la adenda',
                    anchor: '100%',
                    maxLength: 1000
                },
                type: 'TextArea',
                filters: {pfiltro: 'obpg.obs', type: 'string'},
                id_grupo: 2,
                grid: true,
                form: true
            }
        ],
        fields: [
            {name: 'id_obligacion_pago', type: 'numeric'},
            {name: 'id_contrato', type: 'numeric'},
            {name: 'id_gestion', type: 'numeric'},
            {name: 'num_tramite', type: 'string'},
            {name: 'numero', type: 'string'},
            {name: 'estado', type: 'string'},
            {name: 'total_pago', type: 'numeric'},
            {name: 'numero_contrato', type: 'numeric'},
            {name: 'fecha_fin', type: 'date'},
            {name: 'fecha_soli', type: 'date'},
            {name: 'numero', type: 'string'},
            {name: 'nueva_fecha_fin', type: 'date'},
            {name: 'observacion', type: 'string'},
            {name: 'id_funcionario', type: 'numeric'},
            {name: 'id_contrato_adenda', type: 'numeric'}
        ],
        ActSave: '../../sis_adendas/control/Adendas/clonarObligacion',
        constructor: function (config) {
            this.maestro = config;
            this.construirGrupos();
            Phx.vista.NuevaAdenda.superclass.constructor.call(this, config);
            this.init();
            this.configurarForm(this.maestro.obligacion_pago);
        },
        configurarForm: function (obligacion_pago) {
            var self = this;
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_adendas/control/ObligacionPago/obtenerObligacionPorId',
                params:
                    {
                        'id_obligacion_pago': obligacion_pago.id_obligacion_pago
                    },
                success: self.resultadoObligacion,
                failure: self.errorObligacion,
                timeout: 3.6e+6,
                scope: self
            });
        },
        construirGrupos: function () {
            var me = this;
            this.panelResumen = new Ext.Panel({
                padding: '0 0 0 20',
                html: '',
                split: true,
                layout: 'fit'
            });

            me.Grupos = [
                {
                    layout: 'form',
                    border: false,
                    defaults: {
                        border: false
                    },
                    items: [{
                        bodyStyle: 'padding-right:5px;',
                        items: [{
                            xtype: 'fieldset',
                            title: 'Datos Obligacion de pago',
                            autoHeight: true,
                            items: [],
                            id_grupo: 0
                        }]
                    }, {
                        bodyStyle: 'padding-left:5px;',
                        items: [{
                            xtype: 'fieldset',
                            title: 'Datos Contrato',
                            autoHeight: true,
                            items: [],
                            id_grupo: 1
                        }]
                    }, {
                        bodyStyle: 'padding-left:5px;',
                        items: [{
                            xtype: 'fieldset',
                            title: 'Datos Andenda',
                            autoHeight: true,
                            items: [],
                            id_grupo: 2
                        }]
                    }
                    ]
                }
            ];

        },
        resultadoObligacion: function (data) {
            var data = utils.serialize(data);
            Phx.CP.loadingHide();
            this.llenarFormulario(data.datos)
        },
        errorObligacion: function (error) {
            var data = utils.serialize(error);
            Phx.CP.loadingHide();
            adendas.dialog.error(data.ROOT.detalle.mensaje)
            this.panel.close();
        },
        llenarFormulario: function (obligacion_pago) {
            var self = this;
            obligacion_pago = obligacion_pago[0];
            self.getComponente('id_obligacion_pago').setValue(obligacion_pago.id_obligacion_pago);
            self.getComponente('num_tramite').setValue(obligacion_pago.num_tramite);
            self.getComponente('proveedor').setValue(obligacion_pago.proveedor);
            self.getComponente('total_pago').setValue(obligacion_pago.total_pago);
            self.getComponente('fecha_fin').setValue(obligacion_pago.fecha_fin);
            self.getComponente('fecha_soli').setValue(obligacion_pago.fecha_soli);

            if (obligacion_pago.numero_contrato == '') {
                self.getComponente('numero_contrato').setValue(obligacion_pago.numero_orden);
                self.getComponente('id_contrato_adenda').setDisabled(true);
            } else {
                self.getComponente('numero_contrato').setValue(obligacion_pago.numero_contrato);
                self.getComponente('id_contrato_adenda').setDisabled(false);
            }

            this.Cmp.id_funcionario.store.baseParams.query = obligacion_pago.codigo_fun;
            this.Cmp.id_funcionario.store.load({
                params: {start: 0, limit: this.tam_pag},
                callback: function (r) {
                    if (r.length > 0) {
                        this.Cmp.id_funcionario.setValue(r[0].data.id_funcionario);
                        this.Cmp.id_funcionario.fireEvent('select', this.Cmp.id_funcionario, r[0].data.id_funcionario, 0)
                    }

                }, scope: this
            });
        },
        successSave: function (resp) {
            var data = utils.serialize(resp);
            Phx.CP.loadingHide();
            Phx.CP.getPagina(this.idContenedorPadre).reload();
            Phx.CP.getPagina(Phx.CP.getPagina(this.idContenedorPadre).idContenedorPadre).reload();
            if (this.idContenedorPadre !== 'docs-OBPAGOA-xeast')
                Phx.CP.getPagina(this.idContenedorPadre).close();
            adendas.dialog.info(data.ROOT.detalle.mensaje);
            this.panel.close();
        }
    })
</script>

