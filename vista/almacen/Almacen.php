<?php
/**
 *@package pXP
 *@file Almacen.php
 *@author  MAM
 *@date 27-12-2016 14:45
 *@Interface para el inicio de solicitudes de materiales
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Almacen = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'Almacen',
        constructor: function (config) {


            Phx.vista.Almacen.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'almacen';
            this.load({params:{start:0, limit:this.tam_pag}});


            this.getBoton('new').setVisible(false);
            this.getBoton('del').setVisible(false);
            this.getBoton('edit').setVisible(false);
            this.getBoton('ini_estado').setVisible(false);

        },

        tam_pag:50
        /*actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        }*/



    }

</script>
