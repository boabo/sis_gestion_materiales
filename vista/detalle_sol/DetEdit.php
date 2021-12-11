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
    Phx.vista.DetEdit = {
        require: '../../../sis_gestion_materiales/vista/detalle_sol/DetalleSol.php',
        requireclase: 'Phx.vista.DetalleSol',
        title: 'Solicitud',
        nombreVista: 'DetEdit',
        constructor: function (config) {
            Phx.vista.DetEdit.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
        },
        preparaMenu:function(n){
            var tb =this.tbar;
            Phx.vista.DetEdit.superclass.preparaMenu.call(this,n);

            if(this.maestro.estado == 'borrador'){
                this.getBoton('del').enable();
                //this.getBoton('new').enable();
                this.getBoton('edit').enable();
            }
            else{
                this.getBoton('del').disable();
                //this.getBoton('new').disable();
                this.getBoton('edit').disable();
            }
            return tb;
        },

        liberaMenu: function() {
            var tb = Phx.vista.DetEdit.superclass.liberaMenu.call(this);
            if(tb){
                if(this.maestro.estado == 'borrador'){
                    //this.getBoton('new').enable();
                }else{
                    //this.getBoton('new').disable();
                }
                this.getBoton('del').disable();
                this.getBoton('edit').disable();
            }
            return tb;
        }

    }
</script>
