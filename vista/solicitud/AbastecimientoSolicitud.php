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
    Phx.vista.AbastecimientoSolicitud = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'Consulta Requerimientos',
        constructor: function (config) {
            Phx.vista.AbastecimientoSolicitud.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'abastecimiento';
            this.load({params:{start:0, limit:this.tam_pag}});

            this.getBoton('sig_estado').setVisible(false);
            this.getBoton('ant_estado').setVisible(false);
            this.getBoton('new').setVisible(false);
            this.getBoton('del').setVisible(true);
            this.getBoton('edit').setVisible(true);
        },
        tam_pag:50,
        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        }


    }

</script>