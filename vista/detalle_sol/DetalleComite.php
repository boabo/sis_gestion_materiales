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
    Phx.vista.DetalleComite = {
        require: '../../../sis_gestion_materiales/vista/cotizacion_detalle/CotizacionDetalle.php',
        requireclase: 'Phx.vista.CotizacionDetalle',
        title: 'Solicitud',
        nombreVista: 'DetalleComite',
        constructor: function (config) {
            this.Atributos[this.getIndAtributo('revisado')].grid=false;
            Phx.vista.DetalleComite.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
        },
    /*arrayDefaultColumHidden:['nro_parte_cot','tipo_cot','cantidad_det','precio_unitario','precio_unitario_mb'],
        rowExpander: new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Nro. Parte:&nbsp;&nbsp;</b> {nro_parte_cot}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Tipo:&nbsp;&nbsp;</b> {tipo_cot}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Cantidad:&nbsp;&nbsp;</b> {cantidad_det}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Precio Unitario:&nbsp;&nbsp;</b> {precio_unitario}</p>',
                '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Precio Total:&nbsp;&nbsp;</b> {precio_unitario_mb}</p>'
            )
        }),*/

        bnew:false,
        bedit:false,
        bsave:false,
        bdel:false
    }
</script>