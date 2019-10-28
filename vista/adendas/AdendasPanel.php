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
        '/etr/sis_adendas/assets/js/utils.js'], true);

    Phx.vista.AdendasPanel = {
        require: '../../../sis_adendas/vista/adendas/Adendas.php',
        requireclase: 'Phx.vista.Adendas',
        xeast: false,
        bnew: true,
        title: 'Modificatorios',
        nombreVista: 'AdendasPanel',
        constructor: function (config) {
            this.maestro = config.maestro;
            Phx.vista.AdendasPanel.superclass.constructor.call(this, config);
            this.store.baseParams = {id_obligacion_pago: '0'};
            this.load({params: {start: 0, limit: 50}});
            this.panel.on('collapse',function(p){
                if(!p.col){
                    var id = p.getEl().id,
                        parent = p.getEl().parent(),
                        buscador = '#'+id+'-xcollapsed',
                        col = parent.down(buscador);
                    col.insertHtml('beforeEnd','<div style="writing-mode: vertical-lr; transform: rotate(180deg); text-align: center; height: 100%;"><span class="x-panel-header-text"><b>'+p.title+'</b></span></div>');
                    p.col = col;
                };
            }, this);
        },
        onButtonNew: function () {
            var self = this;
            var data = self.getSelectedData();
            self.winNuevaAdenda = Phx.CP.loadWindows('../../../sis_adendas/vista/adendas/NuevaAdenda.php',
                'Nueva Moficiaci&oacute;n',
                {
                    modal: true,
                    width: 500,
                    closeAction: "close"
                },
                {obligacion_pago: {id_obligacion_pago: self.store.baseParams.id_obligacion_pago}},
                self.idContenedor,
                'NuevaAdenda',
                {
                    config: [{
                        event: 'successsave',
                        delegate: self.guardarNuevaAdenda,

                    }],

                    scope: self
                });
        },
        actualizarTabla: function (sm) {
            this.store.baseParams = {id_obligacion_pago: sm.id_obligacion_pago};
            this.load({params: {start: 0, limit: 50}})
        },
        tieneAdendas: function () {
            return this.store.data.items.length > 0;
        }
    }
</script>