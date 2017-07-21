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
    Phx.vista.SolicitudCotizacion = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'ProcesoCompra',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleNoEdit.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleNoEdit'
            }
        ],

        constructor: function (config) {
            this.Atributos[this.getIndAtributo('nombre_estado_firma')].grid=false;

            Phx.vista.SolicitudCotizacion.superclass.constructor.call(this, config);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'origen_ing';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Report').setVisible(false);
            this.getBoton('ant_estado').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);
            this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Consulta_desaduanizacion').setVisible(false);
            this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);

        },
        gruposBarraTareas:[
            {name:'origen_ing',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Operaciones</h1>',grupo:2,height:0, width: 100},
            {name:'origen_man',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Mantenimiento</h1>',grupo:2,height:0, width: 100},
            {name:'origen_alm',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Abastecimientos</h1>',grupo:2,height:0, width: 150}

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
        bactGroups:  [0,1,2],
        btestGroups: [0],
        bexcelGroups: [0,1,2],

        enableTabDetalle:function(){
            if(this.TabPanelSouth.get(0)){
                this.TabPanelSouth.get(0).enable();
                this.TabPanelSouth.setActiveTab(0);
            }
        },

        disableTabDetalle:function(){
            if(this.TabPanelSouth.get(0)){
                this.TabPanelSouth.setActiveTab(0);
            }
        },
        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.SolicitudCotizacion.superclass.preparaMenu.call(this,n);

            if(data['estado'] ==  'revision'){
                this.getBoton('sig_estado').enable();
                this.getBoton('Control_aLmacene').enable();
                this.getBoton('Archivado_concluido').enable();
                this. enableTabDetalle();
            } else {
                this.getBoton('sig_estado').disable();
                this.getBoton('Control_aLmacene').disable();
                this.getBoton('Archivado_concluido').disable();
                this.disableTabDetalle();
            }
            return tb;
        },
        liberaMenu:function(){
            var tb = Phx.vista.SolicitudCotizacion.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                this.getBoton('Control_aLmacene').disable();
                this.getBoton('Archivado_concluido').disable();

            }
            return tb;
        },

        bdel:false,
        bsave:false,
        bnew:false

   }

</script>