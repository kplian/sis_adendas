<?php
/**
*@package pXP
*@file gen-ObligacionDetAd.php
*@author  (valvarado)
*@date 30/10/2019
*@description Archivo con la interfaz de usuario que permite 
*dar el visto a solicitudes de compra
*
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.ObligacionDetAd = {
    bedit:false,
    bnew:false,
    bsave:false,
    bdel:false,
	require:'../../../sis_tesoreria/vista/obligacion_det/ObligacionDetConsulta.php',
	requireclase:'Phx.vista.ObligacionDetConsulta',
	title:'Cambio de Apropiación',
	nombreVista: 'ObligacionDetConsulta'
};
</script>
