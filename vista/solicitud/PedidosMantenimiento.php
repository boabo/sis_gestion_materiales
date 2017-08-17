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
    Phx.vista.PedidosMantenimiento = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'PedidoMantenimiento',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleSol.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleSol'
            }
        ],

        constructor: function (config) {

            this.font();

            this.Grupos.push( {
                layout: 'column',
                border: false,
                defaults: {
                    border: false
                },

                items: [

                    {
                        bodyStyle: 'padding-right:10px;',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: ' Datos Adquisiciones ',
                                autoHeight: true,
                                items: [],
                                id_grupo: 2
                            }

                        ]
                    },
                    {
                        bodyStyle: 'padding-left:10px;',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: ' Datos Comité de Evaluación ',
                                autoHeight: true,
                                items: [],
                                id_grupo: 5
                            }


                        ]
                    }
                ]

            });

            Phx.vista.PedidosMantenimiento.superclass.constructor.call(this, config);
            this.store.baseParams = {tipo_interfaz: this.nombreVista};
            this.store.baseParams.pes_estado = 'pedido_ma_pendiente';
            this.load({params: {start: 0, limit: this.tam_pag}});
            this.getBoton('Report').setVisible(false);
            this.finCons = true;

        },
        gruposBarraTareas:[
            {name:'pedido_ma_pendiente',title:'<H1 align="center"><i class="fa fa-folder-open"></i> Pendientes</h1>',grupo:3,height:0},
            {name:'pedido_ma_solicitada',title:'<H1 align="center"><i class="fa fa-file"></i> Solicitadas</h1>',grupo:3,height:0},
            {name:'pedido_ma_sin_resp',title:'<H1 align="center"><i class="fa fa-minus-circle"></i> Sin Respuestas</h1>',grupo:3,height:0},
            {name:'pedido_ma_comite',title:'<H1 align="center"><i class="fa fa-minus-circle"></i> Vobo Comite</h1>',grupo:5,height:0},
            {name:'pedido_ma_compra',title:'<H1 align="center"><i class="fa fa-money"></i> Compra</h1>',grupo:3,height:0},
            {name:'pedido_ma_concluido',title:'<H1 align="center"><i class="fa fa-folder"></i> Concluido</h1>',grupo:5,height:0}
        ],
        
        actualizarSegunTab: function(name, indice){

            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                if(name == 'pedido_ma_pendiente' || name == 'pedido_ma_solicitada' ){
                    this.getBoton('btnproveedor').setVisible(true);
                    this.getBoton('Cotizacion').setVisible(true);
                }else{
                    this.getBoton('btnproveedor').setVisible(false);
                    this.getBoton('Cotizacion').setVisible(false);
                }

                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        tam_pag:50,
        beditGroups: [2,3],
        bdelGroups:  [0],
        bactGroups:  [0,1,2,3,5],
        btestGroups: [0],
        bexcelGroups: [0,1,2,3,5],
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
            Phx.vista.PedidosMantenimiento.superclass.preparaMenu.call(this,n);

            this.getBoton('btnproveedor').enable();
            this.getBoton('Cotizacion').enable();


            if( data['estado'] ==  'cotizacion'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ini_estado').enable();
                this.getBoton('ant_estado').enable();
                this. enableTabDetalle();


            }else if( data['estado'] !=  'finalizado'){
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
            var tb = Phx.vista.PedidosMantenimiento.superclass.liberaMenu.call(this);
            if(tb){


                this.getBoton('sig_estado').disable();
                this.getBoton('btnproveedor').disable();
                this.getBoton('Consulta_desaduanizacion').enable();
                this.getBoton('Control_aLmacene').enable();
                this.getBoton('Archivado_concluido').enable();
                this.getBoton('Cotizacion').disable();
                this.getBoton('ini_estado').disable();
                this.getBoton('Report').setVisible(false);
            }
            return tb;
        },
        font :function () {
            this.Atributos[this.getIndAtributo('nombre_estado_firma')].grid=true;
            this.Atributos[this.getIndAtributo('nro_po')].grid=true;
            this.Atributos[this.getIndAtributo('id_proveedor')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_cotizacion')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_po')].grid=true;

            this.Atributos[this.getIndAtributo('mensaje_correo')].grid = true;
            this.Atributos[this.getIndAtributo('tipo_evaluacion')].grid=true;
            this.Atributos[this.getIndAtributo('taller_asignado')].grid=true;
            this.Atributos[this.getIndAtributo('observacion_nota')].grid=true;
            this.Atributos[this.getIndAtributo('lugar_entrega')].grid=true;
            this.Atributos[this.getIndAtributo('condicion')].grid=true;

        }



    }
</script>
