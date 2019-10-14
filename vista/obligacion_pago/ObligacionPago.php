<?php
/***************************************************************************************************************************************************
 * @package pXP
 * @file ObligacionPago.php
 * @author (valvarado)
 * @date 24-06-2019 15:15:06
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 * Issue            Fecha        Author                Descripcion
 *********************************************************************************************************************************************/
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
    Ext.Loader.load(['/etr/sis_adendas/assets/conf.js', '/etr/sis_adendas/assets/js/dialog.messages.js'], true);
    Phx.vista.ObligacionPago = Ext.extend(Phx.gridInterfaz, {
        constructor: function (config) {
            this.maestro = config;
            Phx.vista.ObligacionPago.superclass.constructor.call(this, config);
            this.init();
            this.store.baseParams = {
                tipo_interfaz: this.nombreVista,
                id_obligacion_pago: this.maestro.id_obligacion_pago
            }
            if (config.filtro_directo) {
                this.store.baseParams.filtro_valor = config.filtro_directo.valor;
                this.store.baseParams.filtro_campo = config.filtro_directo.campo;
            }
            this.load({params: {start: 0, limit: this.tam_pag}});
            this.addBotones();
            this.iniciarEventos();
            this.getBoton('diagrama_gantt').disable();
            this.getBoton('seleccionar').disable();
        },
        nombreVista: 'obligacionPago',
        fheight: '50%',
        fwidth: '30%',
        tam_pag: 50,
        title: 'ObligacionPago de Pago',
        ActSave: '../../sis_tesoreria/control/ObligacionPago/insertarObligacionPago',
        ActDel: '../../sis_tesoreria/control/ObligacionPago/eliminarObligacionPago',
        ActList: '../../sis_tesoreria/control/ObligacionPago/listarObligacionPago',
        id_store: 'id_obligacion_pago',
        bdel: false,
        bsave: false,
        bedit: false,
        bnew: false,
        bexcel: false,
        sistema: 'ADQ',
        id_cotizacion: 0,
        id_proceso_compra: 0,
        id_solicitud: 0,
        auxFuncion: 'onBtnAdq',
        tabla_id: 'id_obligacion_pago',
        tabla: 'tes.tobligacion_pago',
        Atributos: [
            {
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
                    anchor: '80%',
                    gwidth: 150,
                    maxLength: 200,
                    renderer: function (value, p, record) {
                        if ((record.data.monto_estimado_sg > 0 || record.data.fin_forzado == 'si') && !record.data.id_obligacion_pago_extendida) {
                            if (record.data.monto_estimado_sg > 0) {
                                return String.format('<div ext:qtip="La extención de la obligación esta pendiente"><b><font color="red">{0}</font></b><br><b>Monto ampliado: </b>{1}</div>', value, record.data.monto_estimado_sg);
                            } else {
                                return String.format('<div ext:qtip="La extención de la obligación esta pendiente"><b><font color="red">{0}</font></b></div>', value);
                            }
                        } else {
                            if (record.data.monto_estimado_sg > 0 && record.data.id_obligacion_pago_extendida > 0) {
                                return String.format('<div ext:qtip="La obligación fue extendida"><b><font color="orange">{0}</font></b><br><b>Monto ampliado: </b>{1}</div>', value, record.data.monto_estimado_sg);
                            } else {
                                if (record.data.id_obligacion_pago_extendida > 0) {
                                    return String.format('<div ext:qtip="La obligación fue extendida"><b><font color="orange">{0}</font></b></div>', value, record.data.monto_estimado_sg);
                                } else {

                                }
                                return String.format('{0}', value);
                            }
                        }
                    }
                },
                type: 'TextField',
                filters: {pfiltro: 'obpg.num_tramite', type: 'string'},
                id_grupo: 1,
                bottom_filter: true,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'estado',
                    fieldLabel: 'estado',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 50
                },
                type: 'TextField',
                filters: {pfiltro: 'obpg.estado', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'numero',
                    fieldLabel: 'Numero',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 180,
                    renderer: function (value, p, record) {
                        if (record.data.comprometido == 'si') {
                            return String.format('<b><font color="green">{0}</font></b>', value);
                        } else {
                            return String.format('{0}', value);
                        }
                    },
                    maxLength: 50
                },
                type: 'Field',
                filters: {pfiltro: 'obpg.numero', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'ultima_cuota_pp',
                    fieldLabel: 'Ult PP',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 50
                },
                type: 'Field',
                filters: {pfiltro: 'obpg.ultima_cuota_pp', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'ultimo_estado_pp',
                    fieldLabel: 'Ult. Est. PP',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 50
                },
                type: 'Field',
                filters: {pfiltro: 'obpg.ultimo_estado_pp', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },

            {
                config: {
                    name: 'tipo_obligacion',
                    fieldLabel: 'Tipo Obligacion',
                    allowBlank: false,
                    anchor: '80%',
                    emptyText: 'Tipo Obligacion',
                    renderer: function (value, p, record) {
                        var dato = '';
                        dato = (dato == '' && value == 'pago_directo') ? 'Pago Directo' : dato;
                        dato = (dato == '' && value == 'aduisiciones') ? 'Adquisiciones' : dato;
                        return String.format('{0}', dato);
                    },

                    store: new Ext.data.ArrayStore({
                        fields: ['variable', 'valor'],
                        data: [
                            ['pago_directo', 'Pago Directo']
                        ]
                    }),
                    valueField: 'variable',
                    displayField: 'valor',
                    forceSelection: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'local',
                    wisth: 250
                },
                type: 'ComboBox',
                filters: {pfiltro: 'obpg.tipo_obligacion', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },


            {
                config: {
                    name: 'fecha',
                    fieldLabel: 'Fecha',
                    allowBlank: false,
                    readOnly: true,
                    gwidth: 100,
                    format: conf.format_date,
                    renderer: function (value, p, record) {
                        return String.format('<b><font size=3  color="#006400">{0}</font><b>', value ? value.dateFormat(conf.format_date) : '');
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'obpg.fecha', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_funcionario',
                    hiddenName: 'id_funcionario',
                    origen: 'FUNCIONARIOCAR',
                    fieldLabel: 'Funcionario',
                    allowBlank: false,
                    gwidth: 200,
                    valueField: 'id_funcionario',
                    gdisplayField: 'desc_funcionario1',
                    baseParams: {es_combo_solicitud: 'si'},
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_funcionario1']);
                    }
                },
                type: 'ComboRec',
                id_grupo: 1,
                filters: {pfiltro: 'fun.desc_funcionario1', type: 'string'},
                bottom_filter: true,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_depto',
                    fieldLabel: 'Depto',
                    allowBlank: false,
                    anchor: '80%',
                    origen: 'DEPTO',
                    tinit: false,
                    baseParams: {tipo_filtro: 'DEPTO_UO', estado: 'activo', codigo_subsistema: 'TES', modulo: 'OP'},
                    gdisplayField: 'nombre_depto',
                    gwidth: 100
                },
                type: 'ComboRec',
                filters: {pfiltro: 'dep.nombre', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_proveedor',
                    fieldLabel: 'Proveedor',
                    anchor: '80%',
                    tinit: false,
                    allowBlank: false,
                    origen: 'PROVEEDOR',
                    gdisplayField: 'desc_proveedor',
                    gwidth: 100,
                    listWidth: '280',
                    resizable: true
                },
                type: 'ComboRec',
                id_grupo: 1,
                filters: {pfiltro: 'pv.desc_proveedor', type: 'string'},
                bottom_filter: true,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_contrato',
                    hiddenName: 'id_contrato',
                    fieldLabel: 'Contrato',
                    typeAhead: false,
                    forceSelection: false,
                    allowBlank: false,
                    disabled: true,
                    emptyText: 'Contratos...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_workflow/control/Tabla/listarTablaCombo',
                        id: 'id_contrato',
                        root: 'datos',
                        sortInfo: {
                            field: 'id_contrato',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_contrato', 'numero', 'tipo', 'objeto', 'estado', 'desc_proveedor', 'monto', 'moneda', 'fecha_inicio', 'fecha_fin'],
                        remoteSort: true,
                        baseParams: {
                            par_filtro: 'con.numero#con.tipo#con.monto#prov.desc_proveedor#con.objeto#con.monto',
                            tipo_proceso: "CON",
                            tipo_estado: "finalizado"
                        }
                    }),
                    valueField: 'id_contrato',
                    displayField: 'numero',
                    gdisplayField: 'desc_contrato',
                    triggerAction: 'all',
                    lazyRender: true,
                    resizable: true,
                    mode: 'remote',
                    pageSize: 20,
                    queryDelay: 200,
                    listWidth: 380,
                    minChars: 2,
                    gwidth: 100,
                    anchor: '80%',
                    renderer: function (value, p, record) {
                        if (record.data['desc_contrato']) {
                            return String.format('{0}', record.data['desc_contrato']);
                        }
                        return '';

                    },
                    tpl: '<tpl for="."><div class="x-combo-list-item"><p>Nro: {numero} ({tipo})</p><p>Obj: <strong>{objeto}</strong></p><p>Prov : {desc_proveedor}</p> <p>Monto: {monto} {moneda}</p><p>Rango: {fecha_inicio} al {fecha_fin}</p></div></tpl>'
                },
                type: 'ComboBox',
                id_grupo: 0,
                filters: {
                    pfiltro: 'con.numero',
                    type: 'numeric'
                },
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'total_pago',
                    currencyChar: ' ',
                    fieldLabel: 'Total a Pagar',
                    allowBlank: false,
                    gwidth: 130,
                    maxLength: 1245184
                },
                type: 'MoneyField',
                filters: {pfiltro: 'obpg.total_pago', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'id_moneda',
                    fieldLabel: 'Moneda',
                    anchor: '80%',
                    tinit: false,
                    allowBlank: false,
                    origen: 'MONEDA',
                    gdisplayField: 'moneda',
                    gwidth: 100,
                },
                type: 'ComboRec',
                id_grupo: 1,
                filters: {pfiltro: 'mn.moneda', type: 'string'},
                grid: true,
                form: true
            }, {
                config: {
                    name: 'pago_variable',
                    fieldLabel: 'Pago Variable',
                    gwidth: 100,
                    maxLength: 30,
                    items: [
                        {
                            boxLabel: 'Si',
                            name: 'pg-var',
                            inputValue: 'si',
                            qtip: 'Los pagos variables se utilizan cuando NO se conocen los montos exactos que serán pagados (devengados o pagos presupuestariamente).<br> En el caso de anticipos se utiliza pagos variable cuando no sabemos si el total anticipado va ser el total gastado.<br> Ejemplo combustibles, si anticipamos 7000 $us no conocemos con exactitud si vamos a consumir este total puede sobrar o faltar'
                        },
                        {
                            boxLabel: 'No',
                            name: 'pg-var',
                            inputValue: 'no',
                            checked: true,
                            qtip: 'Los pagos no variable (fijos) se utilizan cuando se conocen los montos exactos que se pagaran.<br> Ejemplo los sueldos de los consultores de línea. Por lo general esta es la opcion mas utiliza (además permite que el sistema le ayude con el cálculo del prorrateo lo que no se puede hacer automáticamente cuando el pago es variable) '
                        }
                    ]
                },
                type: 'RadioGroupField',
                filters: {pfiltro: 'pago_variable', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'comprometer_iva',
                    fieldLabel: 'Comprometer al 100%',
                    gwidth: 100,
                    maxLength: 30,
                    items: [
                        {
                            boxLabel: 'Si',
                            name: 'pg-iva',
                            inputValue: 'si',
                            checked: true,
                            qtip: 'Si esta habilita le resta el 13% del iva al momento de comproemter la obligacion de pago'
                        },
                        {boxLabel: 'No', name: 'pg-iva', inputValue: 'no'}
                    ]
                },
                type: 'RadioGroupField',
                filters: {pfiltro: 'comprometer_iva', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'tipo_cambio_conv',
                    fieldLabel: 'Tipo Cambio',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 131074,
                    decimalPrecision: 10
                },
                type: 'NumberField',
                filters: {pfiltro: 'obpg.tipo_cambio_conv', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'funcionario_proveedor',
                    fieldLabel: 'Funcionario/<br/>Proveedor',
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 30,
                    items: [
                        {boxLabel: 'Funcionario', name: 'rg-auto', inputValue: 'funcionario', checked: true},
                        {boxLabel: 'Proveedor', name: 'rg-auto', inputValue: 'proveedor'}
                    ]
                },
                type: 'RadioGroup',
                id_grupo: 1,
                grid: false,
                form: true
            },
            {
                config: {
                    name: 'tipo_anticipo',
                    fieldLabel: 'Tiene Anticipo Parcial',
                    allowBlank: false,
                    qtip: 'Se habilita en SI,  solo para el caso de anticipos parcial, estos anticipos se tendran que descontar de los pagos sucesivos (Se descuenta del liquido  pagable). Los anticipos parciales no van contra factura u otro similar. <br>Para el caso de anticipo totales  escoger la opcion NO',
                    anchor: '80%',
                    emptyText: 'Tipo Obligacion',
                    store: new Ext.data.ArrayStore({
                        fields: ['variable', 'valor'],
                        data: [['si', 'si'],
                            ['no', 'no']]
                    }),
                    valueField: 'variable',
                    value: 'no',
                    displayField: 'valor',
                    forceSelection: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'local',
                    wisth: 250
                },
                type: 'ComboBox',
                valorInicial: 'no',
                filters: {pfiltro: 'obpg.tipo_anticipo', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'obs',
                    fieldLabel: 'Desc',
                    allowBlank: false,
                    qtip: 'Descripcion del objetivo del pago, o Si el proveedor es PASAJEROS PERJUDICADOS aqui va el nombre del pasajero',
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 1000
                },
                type: 'TextArea',
                filters: {pfiltro: 'obpg.obs', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'total_nro_cuota',
                    fieldLabel: 'Nro Cuotas',
                    allowBlank: false,
                    allowDecimals: false,
                    anchor: '80%',
                    gwidth: 50,
                    value: 0,
                    mimValue: 0,
                    maxLength: 131074,
                    maxValue: 24
                },
                type: 'NumberField',
                valorInicial: 0,
                filters: {pfiltro: 'obpg.total_nro_cuota', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: true
            }, {
                config: {
                    name: 'id_plantilla',
                    fieldLabel: 'Tipo Documento',
                    allowBlank: false,
                    emptyText: 'Elija una plantilla...',
                    store: new Ext.data.JsonStore(
                        {
                            url: '../../sis_parametros/control/Plantilla/listarPlantilla',
                            id: 'id_plantilla',
                            root: 'datos',
                            sortInfo: {
                                field: 'desc_plantilla',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_plantilla', 'nro_linea', 'desc_plantilla', 'tipo', 'sw_tesoro', 'sw_compro'],
                            remoteSort: true,
                            baseParams: {par_filtro: 'plt.desc_plantilla', sw_compro: 'si', sw_tesoro: 'si'}
                        }),
                    tpl: '<tpl for="."><div class="x-combo-list-item"><p>{desc_plantilla}</p></div></tpl>',
                    valueField: 'id_plantilla',
                    hiddenValue: 'id_plantilla',
                    displayField: 'desc_plantilla',
                    gdisplayField: 'desc_plantilla',
                    listWidth: '280',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 20,
                    queryDelay: 500,

                    gwidth: 250,
                    minChars: 2,
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['desc_plantilla']);
                    }
                },
                type: 'ComboBox',
                filters: {pfiltro: 'pla.desc_plantilla', type: 'string'},
                id_grupo: 0,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'fecha_pp_ini',
                    fieldLabel: 'Fecha Ini.',
                    allowBlank: false,
                    gwidth: 100,
                    format: conf.format_date,
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat(conf.format_date) : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'obpg.fecha_pp_ini', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'rotacion',
                    fieldLabel: 'Rotación (Meses)',
                    allowBlank: false,
                    allowDecimals: false,
                    anchor: '80%',
                    gwidth: 50,
                    value: 0,
                    maxLength: 131074,
                    mimValue: 1,
                    maxValue: 100
                },
                type: 'NumberField',
                filters: {pfiltro: 'obpg.rotacion', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'porc_anticipo',
                    fieldLabel: 'Porc. Anticipo',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 131074,
                    maxValue: 100
                },
                type: 'NumberField',
                filters: {pfiltro: 'obpg.porc_anticipo', type: 'numeric'},
                id_grupo: 1,
                grid: false,
                form: false
            },
            {
                config: {
                    name: 'porc_retgar',
                    fieldLabel: '%. Retgar',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 131074,
                    maxValue: 100
                },
                type: 'NumberField',
                filters: {pfiltro: 'obpg.porc_retgar', type: 'numeric'},
                id_grupo: 1,
                grid: false,
                form: false
            },
            {
                config: {
                    fieldLabel: 'Obs Presupuestos',
                    gwidth: 180,
                    name: 'obs_presupuestos'
                },
                type: 'Field',
                filters: {pfiltro: 'obpg.obs_presupuestos', type: 'string'},
                grid: true,
                form: false
            },
            {
                config: {
                    fieldLabel: 'Pedido SAP',
                    gwidth: 180,
                    name: 'pedido_sap'
                },
                type: 'Field',
                filters: {pfiltro: 'obpg.pedido_sap', type: 'string'},
                grid: true,
                form: false
            },
            {
                config: {
                    fieldLabel: 'Estado Reg.',
                    name: 'estado_reg'
                },
                type: 'Field',
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
                    format: conf.format_date,
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat(conf.format_datetime) : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'obpg.fecha_reg', type: 'date'},
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
                    format: conf.format_date,
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat(conf.format_datetime) : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'obpg.fecha_mod', type: 'date'},
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
            {name: 'id_obligacion_pago', type: 'numeric'},
            {name: 'id_proveedor', type: 'numeric'},
            {name: 'desc_proveedor', type: 'string'},
            {name: 'estado', type: 'string'},
            {name: 'tipo_obligacion', type: 'string'},
            {name: 'id_moneda', type: 'numeric'},
            {name: 'moneda', type: 'string'},
            {name: 'obs', type: 'string'},
            {name: 'porc_retgar', type: 'numeric'},
            {name: 'id_subsistema', type: 'numeric'},
            {name: 'nombre_subsistema', type: 'string'},
            {name: 'id_funcionario', type: 'numeric'},
            {name: 'desc_funcionario'},
            {name: 'estado_reg', type: 'string'},
            {name: 'porc_anticipo', type: 'numeric'},
            {name: 'id_estado_wf', type: 'numeric'},
            {name: 'id_depto', type: 'numeric'},
            {name: 'nombre_depto', type: 'string'},
            {name: 'uo_ex', type: 'string'},
            {name: 'num_tramite', type: 'string'},
            {name: 'id_proceso_wf', type: 'numeric'},
            {name: 'fecha_reg', type: 'date', dateFormat: conf.format_datetime},
            {name: 'fecha', type: 'date', dateFormat: 'Y-m-d'},
            {name: 'id_usuario_reg', type: 'numeric'},
            {name: 'fecha_mod', type: 'date', dateFormat: conf.format_datetime},
            {name: 'id_usuario_mod', type: 'numeric'},
            {name: 'usr_reg', type: 'string'},
            {name: 'usr_mod', type: 'string'},
            {name: 'tipo_cambio_conv', type: 'numeric'},
            {name: 'id_depto_conta', type: 'numeric'},
            'numero', 'pago_variable', 'total_pago',
            'id_gestion', 'comprometido', 'nro_cuota_vigente', 'tipo_moneda',
            'total_nro_cuota', 'id_plantilla', 'desc_plantilla',
            {name: 'fecha_pp_ini', type: 'date', dateFormat: 'Y-m-d'},
            'rotacion',
            'ultima_cuota_pp',
            'ultimo_estado_pp',
            'tipo_anticipo',
            'ajuste_anticipo', 'desc_funcionario1',
            'ajuste_aplicado', 'codigo_poa', 'obs_poa',
            'monto_estimado_sg',
            'id_obligacion_pago_extendida',
            'obs_presupuestos', 'id_contrato',
            'desc_contrato',
            'monto_ajuste_ret_garantia_ga',
            'monto_ajuste_ret_anticipo_par_ga',
            'monto_total_adjudicado',
            'total_anticipo',
            'pedido_sap',
            'fin_forzado',
            'monto_sg_mo',
            'comprometer_iva'
        ],
        arrayDefaultColumHidden: ['id_fecha_reg', 'id_fecha_mod', 'fecha_mod', 'usr_reg', 'estado_reg', 'fecha_reg', 'usr_mod',
            'numero', 'tipo_obligacion', 'id_depto', 'id_contrato', 'tipo_cambio_conv', 'tipo_anticipo', 'obs', 'total_nro_cuota', 'id_plantilla', 'fecha_pp_ini',
            'rotacion', 'porc_anticipo', 'obs_presupuestos'],
        rowExpander: new Ext.ux.grid.RowExpander({
            tpl: new Ext.Template('<br>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Obligación de pago:&nbsp;&nbsp;</b> {numero}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Monto Total:&nbsp;&nbsp;</b> {total_pago:number("0,000.00")}   {moneda}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Monto gestiones siguientes:&nbsp;&nbsp;</b> {monto_sg_mo:number("0,000.00")}  {moneda}   (No comprometido)</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Contrato:&nbsp;&nbsp;</b> {desc_contrato}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Depto:&nbsp;&nbsp;</b> {nombre_depto}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Justificación:&nbsp;&nbsp;</b> {obs}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Obs del área de presupeustos:&nbsp;&nbsp;</b> {obs_presupuestos}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Obs del área de POA:&nbsp;&nbsp;</b> {codigo_poa} - {obs_poa}</p><br>'
            )
        }),
        sortInfo: {
            field: 'obpg.fecha_reg',
            direction: 'DESC'
        },
        liberaMenu: function () {
            var tb = Phx.vista.ObligacionPago.superclass.liberaMenu.call(this);
            if (tb) {

            }
            return tb
        },
        preparaMenu: function (n) {
            var data = this.getSelectedData();
            var tb = this.tbar;
            Phx.vista.ObligacionPago.superclass.preparaMenu.call(this, n);
            if (data) {
                this.getBoton('diagrama_gantt').enable();
                this.getBoton('seleccionar').enable();
            }
            return tb
        },
        addBotones: function () {
            this.addBotonSeleccionar();
            this.addBotonesGantt();
        },
        addBotonSeleccionar: function () {
            this.menuAdq = new Ext.Toolbar.Button({
                id: 'b-seleccionar-' + this.idContenedor,
                text: 'Crear Adenda',
                grupo: [0, 1, 2],
                iconCls: 'brenew',
                disabled: false,
                scope: this
            });

            this.tbar.add(this.menuAdq);
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
            var self = this;
            this.menuAdq.on('click', function (event) {
                var data = self.getSelectedData();
                self.winNuevaAdenda = Phx.CP.loadWindows('../../../sis_adendas/vista/adendas/NuevaAdenda.php',
                    'Nueva Adenda',
                    {
                        modal: true,
                        width: 500,
                        closeAction: "close"
                    },
                    {obligacion_pago: data},
                    self.idContenedor,
                    'NuevaAdenda',
                    {
                        config: [{
                            event: 'successsave',
                            delegate: self.guardarNuevaAdenda,

                        }],

                        scope: self
                    });
            });
        },
        guardarNuevaAdenda: function () {
        },
    })
</script>