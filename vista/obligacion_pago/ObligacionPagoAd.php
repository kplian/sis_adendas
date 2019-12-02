<?php
/**
 * @package pXP
 * @file ObligacionPagoAd.php
 * @author  (valvaarado)
 * @date 30/10/2019
 * @description Archivo con la interfaz de usuario que permite dar el visto a solicitudes de compra
 * Issue            Fecha        Author                Descripcion
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ObligacionPagoAd = {
        bedit: true,
        bnew: false,
        bsave: false,
        bdel: true,
        require: '../../../sis_tesoreria/vista/obligacion_pago/ObligacionPagoAdq.php',
        requireclase: 'Phx.vista.ObligacionPagoAdq',
        title: 'Obligacion de Pago(Adquisiciones)',
        nombreVista: 'obligacionPagoAdq',
        tabsouth: [
            {
                url: '../../../sis_adendas/vista/obligacion_det/ObligacionDetAd.php',
                title: 'Detalle',
                height: '50%',
                cls: 'ObligacionDetAd'
            },
            {
                url: '../../../sis_adendas/vista/plan_pago/PlanPagoAd.php',
                title: 'Plan de Pagos',
                height: '50%',
                cls: 'PlanPagoAd'
            }

        ],
        xeast: {
            url: '../../../sis_adendas/vista/adendas/AdendasPanel.php',
            cls: 'AdendasPanel',
            width: '30%',
            height: '100%',
            title: "Modificatorios",
            layout: 'accordion',
            floating: true,
            collapsed: true,
            animCollapse: true,
            collapsible: true
        },
        constructor: function (config) {
            var self = this;
            this.maestro = config;
            Phx.vista.ObligacionPagoAd.superclass.constructor.call(this, config);
            this.init();
            this.hideToolbar();
            this.sm.grid.getSelectionModel().on('rowselect', function (sm, rowIdx, r) {
                self.actualizarAdendas()
            });
        },

        preparaMenu: function (n) {
            var data = this.getSelectedData();
            var tb = this.tbar;
            Phx.vista.ObligacionPago.superclass.preparaMenu.call(this, n);
            this.hideToolbar();
            this.getBoton('diagrama_gantt').enable();
            this.getBoton('btnChequeoDocumentosWf').enable();
            this.getBoton('btnObs').enable();
            if (data['estado'] === 'borrador') {
                this.disableTabPagos();
                this.disableTabConsulta();
            } else {
                if (data['estado'] === 'registrado') {
                    this.enableTabPagos();
                }

                if (data['estado'] === 'en_pago') {
                    this.enableTabPagos();
                }

                if (data['estado'] === 'anulado') {
                    this.disableTabPagos();
                    this.enableTabConsulta();
                }
                if (data['estado'] === 'finalizado') {
                    this.enableTabConsulta();
                }
            }
        },
        hideToolbar: function () {
            if (this.getBoton('new'))
                this.getBoton('new').hide();
            if (this.getBoton('edit'))
                this.getBoton('edit').hide();
            if (this.getBoton('del'))
                this.getBoton('del').hide();

            this.getBoton('fin_registro').hide();
            this.getBoton('ant_estado').hide();
            this.getBoton('reporte_com_ejec_pag').hide();
            this.getBoton('reporte_plan_pago').hide();
            this.getBoton('ajustes').hide();
            this.getBoton('est_anticipo').hide();
            this.getBoton('ant_estado').hide();
            this.getBoton('extenderop').hide();
            this.getBoton('chkpresupuesto').hide();
            this.getBoton('btnVerifPresup').hide();
            this.menuAdq.hide();
        },
        actualizarAdendas: function () {
            var contenedor = Phx.CP.getPagina(this.idContenedor + '-xeast');
            contenedor.actualizarTabla(this.getSelectedData());
        }
    };
</script>
