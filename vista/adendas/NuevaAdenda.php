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
        title: 'Nuevo Modificatorio',
        autoScroll: true,
        bsubmit: true,
        bcancel: true,
        breset: false,
        Atributos: [
            {
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_adenda',
                    width: '100%'
                },
                type: 'Field',
                id_group: 0,
                form: true
            },
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
                filters: {pfiltro: 'tipos.descripcion', type: 'string'},
                grid: true,
                form: true,
                bottom_filter: true
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
                type: 'DateField',
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
                        baseParams: {par_filtro: 'id_contrato#numero#id_contrato_fk'}
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
                    allowBlank: true,
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y') : ''
                    }
                },
                type: 'Field',
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
                    name: 'fecha_entrega',
                    fieldLabel: 'Fecha Entrega',
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
                filters: {pfiltro: 'observacion', type: 'string'},
                id_grupo: 2,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'numero_modificatorio',
                    fieldLabel: 'N&uacute;mero Modificatorio',
                    allowBlank: false,
                    width: '100%',
                    anchor: '100%'
                },
                type: 'Field',
                id_grupo: 3,
                form: true
            },
            {
                config: {
                    name: 'fecha_informe',
                    fieldLabel: 'Fecha Informe',
                    allowBlank: false,
                    width: '100%',
                    anchor: '100%',
                    format: conf.format_date,
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat(conf.format_date) : ''
                    }
                },
                type: 'DateField',
                id_grupo: 3,
                form: true
            },
            {
                config: {
                    name: 'lugar_entrega',
                    fieldLabel: 'Lugar Entrega',
                    allowBlank: false,
                    qtip: 'Lugar donde se realizará la entrega',
                    anchor: '100%',
                    maxLength: 499
                },
                type: 'TextArea',
                filters: {pfiltro: 'cot.lugar_entrega', type: 'string'},
                id_grupo: 3,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'forma_pago',
                    fieldLabel: 'Forma Pago',
                    allowBlank: false,
                    qtip: 'Breve descripci&oacute;n de la forma de pago',
                    anchor: '100%',
                    maxLength: 499
                },
                type: 'TextArea',
                filters: {pfiltro: 'cot.forma_pago', type: 'string'},
                id_grupo: 3,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'glosa',
                    fieldLabel: 'Glosa',
                    allowBlank: false,
                    qtip: 'Descripci&oacute;n breve de la glosa',
                    anchor: '100%',
                    maxLength: 5000
                },
                type: 'TextArea',
                filters: {pfiltro: 'glosa', type: 'string'},
                id_grupo: 3,
                grid: true,
                form: true
            },
        ],
        fields: [
            {name: 'id_adenda', type: 'numeric'},
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
            {name: 'observacion', type: 'string'},
            {name: 'id_funcionario', type: 'numeric'},
            {name: 'id_contrato_adenda', type: 'numeric'},
            {name: 'lugar_entrega', type: 'string'},
            {name: 'forma_pago', type: 'string'},
            {name: 'glosa', type: 'string'},
            {name: 'numero_modificatorio', type: 'string'},
            {name: 'fecha_informe', type: 'date', dateFormat: conf.format_datetime},
            {name: 'fecha_entrega', type: 'date', dateFormat: conf.format_datetime},
        ],
        ActSave: '../../sis_adendas/control/Adendas/clonarObligacion',
        constructor: function (config) {
            this.maestro = config;
            if (Boolean(this.maestro.obligacion_pago))
                this.band = false;
            else
                this.band = true;
            this.construirGrupos();
            Phx.vista.NuevaAdenda.superclass.constructor.call(this, config);
            this.init();
            this.configurarForm();
        },
        configurarForm: function () {
            var self = this;
            if (Boolean(this.maestro.obligacion_pago)) {
                var obligacion_pago = this.maestro.obligacion_pago;
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
            } else {
                var adenda = this.maestro.adenda;
                self.llenarEditFormulario(adenda);
            }
        },
        construirGrupos: function () {
            var me = this;
            me.Grupos = [
                {
                    layout: 'form',
                    border: false,
                    defaults: {
                        border: false
                    },
                    items: [{
                        layout: 'column',
                        border: false,
                        items: [{
                            columnWidth: '.5',
                            border: false,
                            hidden: me.band,
                            items: [{
                                xtype: 'fieldset',
                                title: 'Datos Obligaci&oacute;n',
                                autoHeight: true,
                                items: [],
                                id_grupo: 0
                            }]
                        },
                            {
                                columnWidth: '.5',
                                padding: '0px 5px 0px',
                                border: false,
                                hidden: me.band,
                                items: [{
                                    xtype: 'fieldset',
                                    title: 'Datos Contrato',
                                    autoHeight: true,
                                    items: [],
                                    id_grupo: 1
                                }]
                            }
                        ]
                    }, {
                        border: false,
                        width: '100%',
                        items: [{
                            xtype: 'fieldset',
                            title: 'Datos Modificatorio',
                            autoHeight: true,
                            items: [],
                            id_grupo: 2
                        }]
                    }, {
                        border: false,
                        width: '100%',
                        items: [{
                            xtype: 'fieldset',
                            title: 'Datos Informe',
                            autoHeight: true,
                            items: [],
                            id_grupo: 3
                        }]
                    }]
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
            self.getComponente('estado').setValue(obligacion_pago.estado);
            self.getComponente('glosa').setValue(obligacion_pago.obs);
            self.getComponente('lugar_entrega').setValue(obligacion_pago.lugar_entrega);
            self.getComponente('fecha_entrega').setValue(obligacion_pago.fecha_entrega);
            self.getComponente('forma_pago').setValue(obligacion_pago.forma_pago);
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
            this.Cmp.id_contrato_adenda.store.baseParams.id_contrato_fk = obligacion_pago.id_contrato;
        },
        llenarEditFormulario: function (adenda) {
            var self = this;
            self.getComponente('id_adenda').setValue(adenda.id_adenda);
            self.getComponente('fecha_entrega').setValue(moment(adenda.fecha_entrega).format('DD/MM/YYYY'));
            self.getComponente('numero_modificatorio').setValue(adenda.numero_modificatorio);
            self.getComponente('fecha_informe').setValue(moment(adenda.fecha_informe).format('DD/MM/YYYY'));
            self.getComponente('lugar_entrega').setValue(adenda.lugar_entrega);
            self.getComponente('forma_pago').setValue(adenda.forma_pago);
            self.getComponente('glosa').setValue(adenda.glosa);
            self.getComponente('observacion').setValue(adenda.observacion);
            if (Boolean(adenda.id_contrato_adenda) && adenda.id_contrato_adenda !=='') {
                self.getComponente('id_contrato_adenda').setDisabled(false);
                self.getComponente('id_contrato_adenda').setValue(adenda.id_contrato_adenda);
                this.Cmp.id_contrato_adenda.store.baseParams.query = adenda.numero_adenda;
                this.Cmp.id_contrato_adenda.store.load({
                    params: {start: 0, limit: this.tam_pag},
                    callback: function (r) {
                        if (r.length > 0) {
                            this.Cmp.id_contrato_adenda.setValue(r[0].data.id_contrato);
                            this.Cmp.id_contrato_adenda.fireEvent('select', this.Cmp.id_contrato, r[0].data.id_contrato, 0)
                        }

                    }, scope: this
                });
            } else {
                self.getComponente('id_contrato_adenda').setDisabled(true);
            }
            this.Cmp.id_funcionario.store.baseParams.query = adenda.codigo;
            this.Cmp.id_funcionario.store.load({
                params: {start: 0, limit: this.tam_pag},
                callback: function (r) {
                    if (r.length > 0) {
                        this.Cmp.id_funcionario.setValue(r[0].data.id_funcionario);
                        this.Cmp.id_funcionario.fireEvent('select', this.Cmp.id_funcionario, r[0].data.id_funcionario, 0)
                    }

                }, scope: this
            });
            this.Cmp.id_tipo.store.baseParams.query = adenda.id_tipo;
            this.Cmp.id_tipo.store.load({
                params: {start: 0, limit: this.tam_pag},
                callback: function (r) {
                    if (r.length > 0) {
                        this.Cmp.id_tipo.setValue(r[0].data.id_tipo);
                        this.Cmp.id_tipo.fireEvent('select', this.Cmp.id_tipo, r[0].data.id_tipo, 0)
                    }

                }, scope: this
            });

        },
        successSave: function (resp) {
            var data = utils.serialize(resp);
            Phx.CP.loadingHide();
            Phx.CP.getPagina(this.idContenedorPadre).reload();
            if (this.getComponente("id_adenda").getValue() !== '') {
                this.panel.close();
            } else if (this.idContenedorPadre === 'docs-ADOP-xeast') {
                this.panel.close();
                Phx.CP.getPagina(Phx.CP.getPagina(this.idContenedorPadre).idContenedorPadre).reload();
            }
            else{
                Phx.CP.getPagina(Phx.CP.getPagina(this.idContenedorPadre).idContenedorPadre).reload();
                Phx.CP.getPagina(this.idContenedorPadre).close();
                this.panel.close();
            }

            adendas.dialog.info(data.ROOT.detalle.mensaje);
        },
        onSubmit: function (o, x, force) {
            var me = this;
            if (me.form.getForm().isValid() || force === true) {
                Phx.CP.loadingShow();
                Ext.apply(me.argumentSave, o.argument);
                Ext.Ajax.request({
                    url: me.ActSave,
                    params: me.getValForm,
                    isUpload: me.fileUpload,
                    success: me.successSave,
                    argument: me.argumentSave,

                    failure: me.conexionFailure,
                    timeout: me.timeout,
                    scope: me
                });
            }
        },
    })
</script>

