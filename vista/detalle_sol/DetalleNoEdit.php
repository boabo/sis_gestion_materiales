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
    Phx.vista.DetalleNoEdit = {
        require: '../../../sis_gestion_materiales/vista/detalle_sol/DetalleSol.php',
        requireclase: 'Phx.vista.DetalleSol',
        title: 'Solicitud',
        nombreVista: 'Noedit',
        constructor: function (config) {

            Phx.vista.DetalleNoEdit.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'edit';
        },
        bnew: false,
        bdel: false,
        bedit: false
    }
</script>