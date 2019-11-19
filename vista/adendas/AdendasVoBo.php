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

    Phx.vista.AdendasVoBo = {
        require: '../../../sis_adendas/vista/adendas/Adendas.php',
        requireclase: 'Phx.vista.Adendas',
        xeast: false,
        bnew: true,
        title: 'Visto Bueno Modificatorios',
        nombreVista: 'AdendasVoBo',
        constructor: function (config) {
            this.maestro = config.maestro;
            Phx.vista.AdendasVoBo.superclass.constructor.call(this, config);
            this.store.baseParams.id_funcionario = Phx.CP.config_ini.id_usuario;
            this.load({params: {start: 0, limit: this.tam_pag, nombreVista: this.nombreVista}});
            this.getBoton('edit').hide();
            this.getBoton('del').hide();
            this.getBoton('new').hide();
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
                        break;
                    }
                    case 'pendiente': {
                        this.getBoton('sig_estado').enable();
                        this.getBoton('ant_estado').enable();
                        this.getBoton('edit').enable();
                        break;
                    }
                    case 'borrador': {
                        this.getBoton('sig_estado').enable();
                        this.getBoton('ant_estado').disable();
                        this.getBoton('edit').enable();
                        break;
                    }
                    default: {
                        this.getBoton('sig_estado').disable();
                        this.getBoton('diagrama_gantt').disable();
                        this.getBoton('ant_estado').disable();
                        this.getBoton('btnChequeoDocumentosWf').disable();
                    }
                }

            }
            return tb
        },
    }

</script>