<?php
/**
 *@package pXP
 *@file RegistroSolicitud.php
 *@author  MAM
 *@date 27-12-2016 14:45
 *@Interface para el inicio de solicitudes de materiales
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ControlDetalle = {
        require: '../../../sis_gestion_materiales/vista/detalle_sol/DetalleSol.php',
        requireclase: 'Phx.vista.DetalleSol',
        title: 'Solicitud',
        nombreVista: 'Control',
        constructor: function (config) {

            this.Atributos.unshift({
                    config: {
                        name: 'revisado',
                        fieldLabel: 'Parte Adquirida',
                        allowBlank: true,
                        anchor: '50%',
                        gwidth: 80,
                        maxLength: 3,
                        renderer: function (value, p, record, rowIndex, colIndex) {

                            //check or un check row
                            var checked = '',
                                momento = 'no';
                            if (value == 'si') {
                                checked = 'checked';
                            }
                            return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);

                        }
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'sol.revisado', type: 'string'},
                    id_grupo: 0,
                    grid: true,
                    form: false
                });
            Phx.vista.ControlDetalle.superclass.constructor.call(this, config);
            this.grid.addListener('cellclick', this.oncellclick,this);
            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
        },
        preparaMenu:function(n){
            var tb =this.tbar;
            Phx.vista.ControlDetalle.superclass.preparaMenu.call(this,n);
            return tb;
        },
        liberaMenu: function() {
            var tb = Phx.vista.ControlDetalle.superclass.liberaMenu.call(this);

            return tb;
        },
        oncellclick : function(grid, rowIndex, columnIndex, e) {

            var record = this.store.getAt(rowIndex),
                fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name

            if(fieldName == 'revisado') {
                this.cambiarRevision(record);
            }
        },
        cambiarRevision: function(record){
            Phx.CP.loadingShow();
            var d = record.data;
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/DetalleSol/cambiarRevision',
                params:{ id_detalle: d.id_detalle,revisado:d.revisado},
                success: this.successRevision,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
            this.reload();
        },
        successRevision: function(resp){
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
        },
        bnew: true,
        bdel: true,
        bedit: true,
        arrayDefaultColumHidden:['nro_parte'],
        rowExpander: new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                '<br>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Nro. Parte:&nbsp;&nbsp;</b> {nro_parte}</p>'
              )
        })
    }
</script>
