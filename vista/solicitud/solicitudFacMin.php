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
    Phx.vista.solicitudFacMin={

        require: '../../../sis_gestion_materiales/vista/solicitud/SolicitudFec.php',
        requireclase: 'Phx.vista.SolicitudFec',
        title: 'SolicitudFec',
        nombreVista: 'solicitudFacMin',
        ActList:'../../sis_gestion_materiales/control/Solicitud/listarSolicitud',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleNoEdit.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleNoEdit'
            }
        ],

        constructor: function(config) {
            Phx.vista.solicitudFacMin.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'ab_origen_ing';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.getBoton('new').setVisible(false);
            this.getBoton('del').setVisible(false);
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Report').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
            this.getBoton('Consulta_desaduanizacion').setVisible(false);
            this.getBoton('sig_estado').setVisible(false);
            this.getBoton('edit').setVisible(false);
            this.getBoton('ant_estado').setVisible(false);
            this.getBoton('btnObs').setVisible(false);
            this.getBoton('Control_aLmacene').setVisible(false);
        },
        gruposBarraTareas:[
            {name:'ab_origen_ing',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Operaciones</h1>',grupo:7,height:0},
            {name:'ab_origen_man',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Mantenimiento</h1>',grupo:7,height:0},
            {name:'ab_origen_alm',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Abastecimientos</h1>',grupo:7,height:0}

        ],
        tam_pag:50,
        beditGroups: [2,4],
        bdelGroups:  [2],
        bactGroups:  [7],
        btestGroups: [2],
        bexcelGroups: [7],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },

    }
</script>

