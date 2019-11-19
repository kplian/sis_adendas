<?php
/**
 * @package pXP
 * @file AdendaDet.php
 * @author (valvarado)
 * @date 24-06-2019 15:15:06
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema adendas
 * Issue            Fecha            Author          Descripcion
 * */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.AdendaDet = Ext.extend(Phx.gridInterfaz, {
        sortInfo: {
            field: 'id_adenda_det',
            direction: 'ASC'
        },
        nombreVista: 'AdendaDet',
        tam_pag: 50,
        bdel: true,
        bsave: false,
        title: 'Detalle',
        ActSave: '../../sis_adendas/control/AdendaDet/insertar',
        ActDel: '../../sis_adendas/control/AdendaDet/eliminar',
        ActList: '../../sis_adendas/control/AdendaDet/listar',
        id_store: 'id_adenda_det',
        Atributos: [
            {
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_adenda_det'
                },
                type: 'Field',
                grid: false,
                form: true
            },
            {

                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_adenda'
                },
                type: 'Field',
                grid: false,
                form: true
            },
            {
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_obligacion_det'
                },
                type: 'Field',
                form: true
            },
            {
                config: {
                    labelSeparator: '',
                    name: 'id_obligacion_pago',
                    fieldLabel: 'id_obligacion_pago',
                    inputType: 'hidden'
                },
                type: 'TextField',
                grid: false,
                form: true
            },
            {
                config: {
                    name: 'id_centro_costo',
                    origen: 'CENTROCOSTO',
                    fieldLabel: 'Centro de Costos',
                    url: '../../sis_parametros/control/CentroCosto/listarCentroCostoFiltradoXDepto',
                    emptyText: 'Centro Costo...',
                    allowBlank: false,
                    gdisplayField: 'codigo_cc',
                    gwidth: 200,
                    baseParams: {tipo: 'tipo'},
                    renderer: function (value, p, record, rowIndex, colIndex, ds) {
                        var color = '#FF0000';
                        if (!Boolean(record.data.id_obligacion_det)) {
                            color = '#006400';
                        } else if (Boolean(record.data.id_obligacion_det) && (Boolean(record.data.monto_comprometer) && record.data.monto_comprometer > 0) || (Boolean(record.data.monto_descomprometer) && record.data.monto_descomprometer > 0)) {
                            color = "#ffae42";
                        }
                        p.attr = "style='border-left:2px " + color + " solid'";
                        return value;
                    }
                },
                type: 'ComboRec',
                id_grupo: 0,
                filters: {pfiltro: 'cc.codigo_cc', type: 'string'},
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_concepto_ingas',
                    fieldLabel: 'Concepto Ingreso Gasto',
                    allowBlank: true,
                    emptyText: 'Concepto Ingreso Gasto...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_parametros/control/ConceptoIngas/listarConceptoIngasMasPartida',
                        id: 'id_concepto_ingas',
                        root: 'datos',
                        sortInfo: {
                            field: 'desc_ingas',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_concepto_ingas', 'tipo', 'desc_ingas', 'movimiento', 'desc_partida', 'id_grupo_ots', 'filtro_ot', 'requiere_ot'],
                        remoteSort: true,
                        baseParams: {
                            par_filtro: 'desc_ingas#par.codigo#par.nombre_partida',
                            movimiento: 'gasto',
                            autorizacion: 'pago_directo',
                            autorizacion_nulos: 'no'
                        }
                    }),
                    valueField: 'id_concepto_ingas',
                    displayField: 'desc_ingas',
                    gdisplayField: 'nombre_ingas',
                    tpl: '<tpl for="."><div class="x-combo-list-item"><p><b>{desc_ingas}</b></p><p>TIPO:{tipo}</p><p>MOVIMIENTO:{movimiento}</p> <p>PARTIDA:{desc_partida}</p></div></tpl>',
                    hiddenName: 'id_concepto_ingas',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 10,
                    queryDelay: 1000,
                    listWidth: 600,
                    resizable: true,
                    anchor: '80%',
                    gwidth: 200,
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['nombre_ingas']);
                    }
                },
                type: 'ComboBox',
                id_grupo: 0,
                filters: {
                    pfiltro: 'cig.movimiento#cig.desc_ingas',
                    type: 'string'
                },
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'descripcion',
                    fieldLabel: 'Descripión',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 200,
                    maxLength: 1245184
                },
                type: 'TextArea',
                filters: {pfiltro: 'adt.descripcion', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_orden_trabajo',
                    fieldLabel: 'Orden Trabajo',
                    sysorigen: 'sis_contabilidad',
                    origen: 'OT',
                    allowBlank: true,
                    gwidth: 200,
                    baseParams: {par_filtro: 'desc_orden#motivo_orden'},
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_orden']);
                    }

                },
                type: 'ComboRec',
                id_grupo: 0,
                filters: {pfiltro: 'ot.motivo_orden#ot.desc_orden', type: 'string'},
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_partida',
                    fieldLabel: 'Partida',
                    allowBlank: true,
                    emptyText: 'Partida...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_presupuestos/control/Partida/listarPartida',
                        id: 'id_partida',
                        root: 'datos',
                        sortInfo: {
                            field: 'codigo',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_partida', 'codigo', 'nombre_partida'],

                        remoteSort: true,
                        baseParams: {par_filtro: 'codigo#nombre_partida', sw_transaccional: 'movimiento'}
                    }),
                    valueField: 'id_partida',
                    displayField: 'nombre_partida',
                    tpl: '<tpl for="."><div class="x-combo-list-item"><p>CODIGO:{codigo}</p><p>{nombre_partida}</p></div></tpl>',
                    hiddenName: 'id_partida',
                    forceSelection: true,
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 10,
                    listWidth: 350,
                    resizable: true,
                    queryDelay: 1000,
                    anchor: '80%',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['nombre_partida']);
                    }
                },
                type: 'ComboBox',
                id_grupo: 0,
                filters: {
                    pfiltro: 'codigo#nombre_partida',
                    type: 'string'
                },
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'monto_pago_mo',
                    currencyChar: ' ',
                    fieldLabel: 'Monto Total Pago',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 1245184,
                    renderer: function (value, p, record, rowIndex, colIndex, ds) {
                        var color = '#FF0000';
                        if (!Boolean(record.data.id_obligacion_det)) {
                            color = '#006400';
                        } else if (Boolean(record.data.id_obligacion_det) && (Boolean(record.data.monto_comprometer) && record.data.monto_comprometer > 0) || (Boolean(record.data.monto_descomprometer) && record.data.monto_descomprometer > 0)) {
                            color = "#ffae42";
                        }
                        return String.format('<b><font  color="' + color + '">{0}</font><b>', value ? Ext.util.Format.number(value, '0,000.00') : '');
                    }
                },
                type: 'MoneyField',
                filters: {pfiltro: 'adt.monto_pago_mo', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'monto_comprometer',
                    currencyChar: ' ',
                    fieldLabel: 'Monto A Comprometer',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 1245184
                },
                type: 'MoneyField',
                filters: {pfiltro: 'adt.monto_comprometer', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'monto_descomprometer',
                    currencyChar: ' ',
                    fieldLabel: 'Monto A Descomprometer',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 1245184
                },
                type: 'MoneyField',
                filters: {pfiltro: 'adt.monto_descomprometer', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'precio_ga',
                    fieldLabel: 'Monto Act. Ges.',
                    currencyChar: ' ',
                    allowBlank: true,
                    disabled: true,
                    width: 100,
                    gwidth: 120,
                    maxLength: 1245186
                },
                type: 'MoneyField',
                id_grupo: 1,
                grid: false,
                form: true
            },
            {
                config: {
                    name: 'monto_pago_sg_mo',
                    fieldLabel: 'Monto Sig. Ges. Mo',
                    currencyChar: ' ',
                    allowBlank: true,
                    value: '0',
                    width: 100,
                    gwidth: 120,
                    maxLength: 1245186
                },
                type: 'MoneyField',
                filters: {pfiltro: 'adt.monto_pago_sg_mo', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'monto_pago_sg_mb',
                    fieldLabel: 'Monto Sig. Ges. Mb',
                    currencyChar: ' ',
                    allowBlank: true,
                    initialValue: 0,
                    width: 100,
                    gwidth: 120,
                    maxLength: 1245186
                },
                type: 'MoneyField',
                filters: {pfiltro: 'adt.monto_pago_sg_mb', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'monto_pago_mb',
                    fieldLabel: 'Monto Pago Bs.',
                    currencyChar: 'Bs. ',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 123456778
                },
                type: 'MoneyField',
                filters: {pfiltro: 'adt.monto_pago_mb', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'factor_porcentual',
                    fieldLabel: 'Factor Porcentual',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 1245184
                },
                type: 'NumberField',
                filters: {pfiltro: 'adt.factor_porcentual', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'id_partida_ejecucion_com',
                    fieldLabel: 'Partida Ejecucion Com',
                    allowBlank: true,
                    inputType: 'hidden',
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'NumberField',
                filters: {pfiltro: 'adt.id_partida_ejecucion_com', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'estado_reg',
                    fieldLabel: 'Estado Reg.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 10
                },
                type: 'TextField',
                filters: {pfiltro: 'adt.estado_reg', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_reg',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat(conf.format_datetime) : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'adt.fecha_reg', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'usr_reg',
                    fieldLabel: 'Creado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'NumberField',
                filters: {pfiltro: 'usu1.cuenta', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_mod',
                    fieldLabel: 'Fecha Modif.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat(conf.format_datetime) : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'adt.fecha_mod', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'usr_mod',
                    fieldLabel: 'Modificado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'NumberField',
                filters: {pfiltro: 'usu2.cuenta', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            }
        ],
        fields: [
            {name: 'id_adenda_det', type: 'numeric'},
            {name: 'id_adenda', type: 'numeric'},
            {name: 'id_obligacion_det', type: 'numeric'},
            {name: 'estado_reg', type: 'string'},
            {name: 'id_partida', type: 'numeric'},
            {name: 'nombre_partida', type: 'string'},
            {name: 'id_concepto_ingas', type: 'numeric'},
            {name: 'nombre_ingas', type: 'string'},
            {name: 'monto_pago_mo', type: 'numeric'},
            {name: 'monto_comprometer', type: 'numeric'},
            {name: 'monto_descomprometer', type: 'numeric'},
            {name: 'id_obligacion_pago', type: 'numeric'},
            {name: 'id_centro_costo', type: 'numeric'},
            {name: 'codigo_cc', type: 'string'},
            {name: 'monto_pago_mb', type: 'numeric'},
            {name: 'factor_porcentual', type: 'numeric'},
            {name: 'id_partida_ejecucion_com', type: 'numeric'},
            {name: 'fecha_reg', type: 'date'},
            {name: 'id_usuario_reg', type: 'numeric'},
            {name: 'fecha_mod', type: 'date'},
            {name: 'id_usuario_mod', type: 'numeric'},
            {name: 'usr_reg', type: 'string'},
            {name: 'usr_mod', type: 'string'},
            'desc_ingas',
            'nombre_ingas',
            'descripcion',
            'id_orden_trabajo',
            'desc_orden',
            'monto_pago_sg_mo',
            'monto_pago_sg_mb'
        ],
        constructor: function (config) {
            this.maestro = config.maestro;
            Phx.vista.AdendaDet.superclass.constructor.call(this, config);
            this.init();
            this.iniciarEventos();
            this.addBotones();
        },
        addBotones: function () {
            this.addButton('lbl-color', {
                xtype: 'label',
                disabled: false,
                style: {
                    position: 'absolute',
                    top: '5px',
                    right: 0,
                    width: '90px',
                    'margin-right': '10px',
                    float: 'right'
                },
                html: '<div style="display: inline-flex"><div style="background-color:#006400;width:10px;height:10px;"></div>&nbsp;<div>Nuevos</div></div><br/>' +
                    '<div style="display: inline-flex"><div style="background-color:#ffae42;width:10px;height:10px;"></div>&nbsp;<div>Modificados</div></div><br/>' +
                    '<div style="display: inline-flex"><div style="background-color:#FF0000;width:10px;height:10px;"></div>&nbsp;<div>No Modificados</div></div>'
            });
        },
        iniciarEventos: function () {

        },
        onReloadPage: function (m) {
            this.maestro = m;
            this.store.baseParams = {id_adenda: this.maestro.id_adenda};
            this.Cmp.id_centro_costo.store.baseParams.id_depto = this.maestro.id_depto;
            this.Cmp.id_centro_costo.modificado = true;
            this.Cmp.id_concepto_ingas.store.baseParams.tipo = this.maestro.tipo;
            this.obtenerGestion();
            this.load({params: {start: 0, limit: 50}});

        },
        onButtonNew: function () {
            Phx.vista.AdendaDet.superclass.onButtonNew.call(this);
            this.Cmp.id_adenda.setValue(this.maestro.id_adenda);
            this.Cmp.id_obligacion_pago.setValue(this.maestro.id_obligacion_pago);
            this.Cmp.monto_pago_sg_mo.setValue(0);
            this.Cmp.id_concepto_ingas.store.baseParams.tipo = this.maestro.tipo;
            this.Cmp.id_centro_costo.enable();
            this.Cmp.id_concepto_ingas.enable();
        },
        preparaMenu: function (n) {
            var detalle = this.getSelectedData();
            var tb = this.tbar;
            Phx.vista.AdendaDet.superclass.preparaMenu.call(this, n);
            var adenda = this.maestro;
            switch (adenda.estado) {
                case 'aprobado': {
                    this.getBoton('new').disable();
                    this.getBoton('edit').disable();
                    this.getBoton('del').disable();
                    break;
                }
                case 'anulado': {
                    this.getBoton('new').disable();
                    this.getBoton('edit').disable();
                    this.getBoton('del').disable();
                    break;
                }
                case 'pendiente': {
                    this.getBoton('new').disable();
                    this.getBoton('edit').disable();
                    if (Boolean(detalle.id_obligacion_det) || adenda.estado === 'pendiente')
                        this.getBoton('del').disable();
                    break;
                }
                case 'borrador': {
                    this.getBoton('edit').enable();
                    this.getBoton('new').enable();
                    if (Boolean(detalle.id_obligacion_det))
                        this.getBoton('del').disable();
                    break;
                }
                default: {
                    this.getBoton('edit').disable();
                    this.getBoton('new').enable();
                    this.getBoton('del').disable();
                }

            }
            return tb;
        },
        liberaMenu: function () {
            var tb = Phx.vista.AdendaDet.superclass.liberaMenu.call(this);
            this.getBoton('edit').disable();
            this.getBoton('del').disable();
            this.getBoton('new').disable();
            if (this.maestro.estado == 'borrador')
                this.getBoton('new').enable();
            return tb
        },
        onButtonEdit: function () {
            Phx.vista.AdendaDet.superclass.onButtonEdit.call(this);
            var data = this.getSelectedData();
            if (Boolean(data.id_obligacion_det)) {
                this.Cmp.id_centro_costo.disable();
                this.Cmp.id_concepto_ingas.disable();
            } else {
                this.Cmp.id_centro_costo.enable();
                this.Cmp.id_concepto_ingas.enable();
            }
        },
        obtenerGestion: function () {
            var fecha = new Date();
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_parametros/control/Gestion/obtenerGestionByFecha',
                params: {fecha: fecha},
                success: this.successGestion,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        successGestion: function (resp) {
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            if (!reg.ROOT.error) {
                this.Cmp.id_centro_costo.store.baseParams.id_gestion = reg.ROOT.datos.id_gestion;
            } else {
                alert('Lo sentimos, no fue posible completar la obtenci&oacute;n de la gesti&oacute;n')
            }
        },
    });
</script>