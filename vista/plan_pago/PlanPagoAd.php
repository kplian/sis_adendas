<?php
/**
 *@package pXP
 *@file gen-PlanPagoAd.php
 *@author  (valvaarado)
 *@date 30/10/2019
 *@description consulta de planes de pago
Issue			Fecha        Author				Descripcion
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.PlanPagoAd= {

        bdel:false,
        bedit:false,
        bsave:false,
        bnew: false,
        require:'../../../sis_tesoreria/vista/plan_pago/PlanPagoConsulta.php',
        requireclase:'Phx.vista.PlanPagoConsulta',
        title:'Consulta de Planes de Pago',
        nombreVista: 'PlanPagoAd',
        constructor: function (config) {
            var self = this;
            this.maestro = config;
            Phx.vista.PlanPagoAd.superclass.constructor.call(this, config);
            this.init();
            this.hideToolBar();
        },
        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.PlanPagoAd.superclass.preparaMenu.call(this,n);
            this.hideToolBar();
            this.getBoton('btnChequeoDocumentosWf').enable();
            this.getBoton('btnObs').enable();

        },
        hideToolBar: function () {
            this.getBoton('ant_estado').hide();
            this.getBoton('sig_estado').hide();
            this.getBoton('SincPresu').hide();
            this.getBoton('SolPlanPago').hide();
            this.getBoton('ini_estado').hide();
            this.getBoton('ant_estado').hide();
            this.getBoton('sig_estado').hide();
            this.getBoton('btnPagoRel').hide();
        }
    };
</script>
