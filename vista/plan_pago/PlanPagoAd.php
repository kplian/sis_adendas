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

        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.PlanPagoAd.superclass.preparaMenu.call(this,n);
            this.getBoton('ant_estado').disable();
            this.getBoton('sig_estado').disable();
            this.getBoton('SincPresu').disable();
            this.getBoton('SolPlanPago').disable();
            this.getBoton('ini_estado').disable();
            this.getBoton('ant_estado').disable();
            this.getBoton('sig_estado').disable();
            this.getBoton('btnChequeoDocumentosWf').disable();
            this.getBoton('btnPagoRel').disable();
            this.getBoton('btnObs').disable();
        },
    };
</script>
