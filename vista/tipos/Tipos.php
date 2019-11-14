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

    Phx.vista.Tipos = Ext.extend(Phx.gridInterfaz, {
        nombreVista: 'Tipos',
        fheight: '50%',
        cls: 'Tipos',
        fwidth: '30%',
        tam_pag: 50,
        title: 'Tipos de Modificatorios',
        ActSave: '../../sis_adendas/control/Tipos/insertar',
        ActDel: '../../sis_adendas/control/Tipos/eliminar',
        ActList: '../../sis_adendas/control/Tipos/listar',
        id_store: 'id_tipo',
        bdel: true,
        bsave: false,
        bedit: true,
        bnew: true,
        bexcel: false,
        Atributos: [
            {
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_tipo'
                },
                type: 'Field',
                form: true
            },
            {
                config: {
                    name: 'codigo',
                    fieldLabel: 'C&oacute;digo',
                    allowBlank: false,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 200
                },
                type: 'TextField',
                filters: {pfiltro: 'tipos.codigo', type: 'string'},
                id_grupo: 1,
                bottom_filter: true,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'descripcion',
                    fieldLabel: 'Descripci&oacute;n',
                    allowBlank: false,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 1000
                },
                type: 'TextArea',
                filters: {pfiltro: 'tipos.descripcion', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'estado_reg',
                    fieldLabel: 'Estado',
                    allowBlank: true,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 50
                },
                type: 'TextField',
                filters: {pfiltro: 'tipos.estado_reg', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_mod',
                    currencyChar: ' ',
                    fieldLabel: 'Fecha Modificaci&oacute;n',
                    allowBlank: false,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 1245184,
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat(conf.format_date) : ''
                    }
                },
                type: 'DateField',
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_reg',
                    currencyChar: ' ',
                    fieldLabel: 'Fecha Registro',
                    allowBlank: false,
                    anchor: '100%',
                    width: '100%',
                    maxLength: 1245184,
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat(conf.format_date) : ''
                    }
                },
                type: 'DateField',
                id_grupo: 1,
                grid: true,
                form: false
            },
        ],
        fields: [
            {name: 'id_tipo', type: 'numeric'},
            {name: 'codigo', type: 'string'},
            {name: 'descripcion', type: 'string'},
            {name: 'estado_reg', type: 'string'},
            {name: 'fecha_mod', type: 'date'},
            {name: 'fecha_reg', type: 'date'},
        ],
        sortInfo: {
            field: 'fecha_mod',
            direction: 'DESC'
        },
        constructor: function (config) {
            this.maestro = config;
            Phx.vista.Tipos.superclass.constructor.call(this, config);
            this.load({params: {start: 0, limit: this.tam_pag}});
            this.init();
        },
        liberaMenu: function () {
            var tb = Phx.vista.Tipos.superclass.liberaMenu.call(this);
            return tb
        },
        preparaMenu: function (n) {
            var data = this.getSelectedData();
            var tb = this.tbar;
            if (data) {
                this.getBoton('edit').enable();
                this.getBoton('new').enable();
                this.getBoton('del').enable();
            }
            return tb
        },
    });

</script>