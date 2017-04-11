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
    Phx.vista.SolicitudArchivado = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'SolArchivado',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleNoEdit.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleNoEdit'
            }
        ],

        constructor: function (config) {
            Phx.vista.SolicitudArchivado.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'archivado_ing';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.getBoton('new').setVisible(false);
            this.getBoton('del').setVisible(false);
            this.getBoton('edit').setVisible(false);
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('btnObs').setVisible(false);
            this.getBoton('Report').setVisible(false);
            this.getBoton('ant_estado').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
            this.getBoton('Consulta_desaduanizacion').setVisible(false);
            this.getBoton('Control_aLmacene').setVisible(false);

        },
        gruposBarraTareas:[
            {name:'archivado_ing',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Operaciones</h1>',grupo:6,height:0, width: 100},
            {name:'archivado_man',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Mantenimiento</h1>',grupo:6,height:0, width: 100},
            {name:'archivado_alm',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Abastecimientos</h1>',grupo:6,height:0, width: 150}

        ],
        tam_pag:50,
        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0,6],
        btestGroups: [0],
        bexcelGroups: [0,6],

        enableTabDetalle:function(){
            if(this.TabPanelSouth.get(0)){
                this.TabPanelSouth.get(0).enable();
                this.TabPanelSouth.setActiveTab(0);
            }
        },
        disableTabDetalle:function(){
            if(this.TabPanelSouth.get(0)){
                //this.TabPanelSouth.get(0).disable();
                this.TabPanelSouth.setActiveTab(0);
                //this.TabPanelSouth.bdel.getVisible(false);
            }
        },
        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.SolicitudArchivado.superclass.preparaMenu.call(this,n);

            if(data['estado'] ==  'almacen'){
                this.getBoton('sig_estado').enable();
                //this.getBoton('ant_estado').enable();
                this.getBoton('ini_estado').enable();
                this. enableTabDetalle();


            }else if(data['estado'] !=  'almacen'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('ini_estado').enable();

                this.disableTabDetalle();
            }
            else {
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
                this.getBoton('ini_estado').disable();
                this.disableTabDetalle();
            }

            return tb;
        },

        liberaMenu:function(){
            var tb = Phx.vista.SolicitudArchivado.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                this.getBoton('sig_estado').disable();
                this.getBoton('ini_estado').disable();

            }
            return tb;
        }





    }

</script>
