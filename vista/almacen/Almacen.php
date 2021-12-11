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
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleNoEdit.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleNoEdit' 
            }
        ],

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
            this.getBoton('Report').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
            //this.getBoton('Consulta_desaduanizacion').setVisible(false);
            //this.getBoton('Control_aLmacene').setVisible(false);
            //this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);

        },
        gruposBarraTareas:[
            {name:'almacen',title:'<H1 align="center" style="color:#E78800; font-size:11px;"><i style="font-size:12px;" class="fa fa-truck" aria-hidden="true"></i> Operaciones</h1>',grupo:4,height:0, width: 100},
            {name:'origen_al_man',title:'<H1 align="center" style="color:#007AD9; font-size:11px;"><i style="font-size:12px;" class="fa fa-wrench" aria-hidden="true"></i> Mantenimiento</h1>',grupo:4,height:0, width: 100},
            {name:'origen_al_ab',title:'<H1 align="center" style="color:#00A530; font-size:11px;"><i style="font-size:12px;" class="fa fa-retweet" aria-hidden="true"></i> Abastecimientos</h1>',grupo:4,height:0, width: 150},
            {name:'origen_al_ceac',title:'<H1 align="center" style="color:#FF0000; font-size:11px;"><i style="font-size:12px;" class="fa fa-plane" aria-hidden="true"></i> Ope. CEAC</h1>',grupo:4,height:0, width: 200},
            {name:'origen_al_repu',title:'<H1 align="center" style="color:#7100BB; font-size:11px;"><i style="font-size:12px;" class="fa fa-cogs" aria-hidden="true"></i> Repuestos</h1>',grupo:2,height:0, width: 200}
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
        bactGroups:  [0,1,4],
        btestGroups: [0],
        bexcelGroups: [0,1,4],

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
            Phx.vista.Almacen.superclass.preparaMenu.call(this,n);

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
            var tb = Phx.vista.Almacen.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();

            }
            return tb;
        }





    }

</script>
