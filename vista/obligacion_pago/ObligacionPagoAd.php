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
        tabsouth:[
            {
                url:'../../../sis_adendas/vista/obligacion_det/ObligacionDetAd.php',
                title:'Detalle',
                height:'50%',
                cls:'ObligacionDetAd'
            },
            {
                url:'../../../sis_adendas/vista/plan_pago/PlanPagoAd.php',
                title:'Plan de Pagos',
                height:'50%',
                cls:'PlanPagoAd'
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
        preparaMenu: function (n) {
            var data = this.getSelectedData();
            var tb = this.tbar;
            Phx.vista.ObligacionPago.superclass.preparaMenu.call(this, n);

            if (this.getBoton('new'))
                this.getBoton('new').disable();
            if (this.getBoton('edit'))
                this.getBoton('edit').disable();
            if (this.getBoton('del'))
                this.getBoton('del').disable();

            this.getBoton('fin_registro').disable();
            this.getBoton('ant_estado').disable();
            this.getBoton('reporte_com_ejec_pag').disable();
            this.getBoton('reporte_plan_pago').disable();
            this.getBoton('ajustes').disable();
            this.getBoton('est_anticipo').disable();
            this.getBoton('ant_estado').disable();
            this.getBoton('extenderop').disable();
            this.getBoton('chkpresupuesto').disable();
            this.getBoton('btnVerifPresup').disable();
            this.getBoton('anti_ret').disable();
            this.getBoton('diagrama_gantt').disable();
            this.getBoton('btnChequeoDocumentosWf').disable();
            this.getBoton('btnObs').disable();
            this.menuAdq.disable();
            if (data['estado'] == 'borrador') {
                this.disableTabPagos();
                this.disableTabConsulta();
            } else {
                if (data['estado'] == 'registrado') {
                    this.enableTabPagos();
                }

                if (data['estado'] == 'en_pago') {
                    this.enableTabPagos();
                }

                if (data['estado'] == 'anulado') {
                    this.disableTabPagos();
                    this.enableTabConsulta();
                }
                if (data['estado'] == 'finalizado') {
                    this.enableTabConsulta();
                }
             }
        }
    };
</script>
