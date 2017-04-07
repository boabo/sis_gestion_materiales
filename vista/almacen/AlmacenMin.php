<?php
/**
 *@package pXP
 *@file gen-PresupPartida.php
 *@author  (admin)
 *@date 29-02-2016 19:40:34
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.AlmacenMin={

        require: '../../../sis_gestion_materiales/vista/almacen/Almacen.php',
        requireclase: 'Phx.vista.Almacen',
        title: 'Almacen',
        nombreVista: 'Almacen',
        ActList:'../../sis_gestion_materiales/control/Solicitud/listarSolicitud',

        constructor: function(config) {
            Phx.vista.AlmacenMin.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'almacen';
            this.load({params:{start:0, limit:this.tam_pag}});
        },
        gruposBarraTareas:[
            {name:'almacen',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Operaciones</h1>',grupo:1,height:0, width: 100},
            {name:'origen_al_man',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Mantenimiento</h1>',grupo:1,height:0, width: 100},
            {name:'origen_al_ab',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Abastecimientos</h1>',grupo:1,height:0, width: 150}

        ],
        tam_pag:50,
        editGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0,1,2],
        btestGroups: [0],
        bexcelGroups: [0,1,2],
        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        }


    }
</script>

