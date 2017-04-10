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
    Phx.vista.PedidosAlmacen = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'PerdidoAlmacen',
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
            this.Atributos[this.getIndAtributo('tipo_falla')].grid=false;
            this.Atributos[this.getIndAtributo('tipo_reporte')].grid=false;
            this.Atributos[this.getIndAtributo('mel')].grid=false;
            this.Atributos[this.getIndAtributo('nro_no_rutina')].grid=false;
            this.Atributos[this.getIndAtributo('id_matricula')].grid=false;
            this.Atributos[this.getIndAtributo('nro_po')].grid=true;
            this.Atributos[this.getIndAtributo('id_proveedor')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_cotizacion')].grid=true;
            
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
                    }
                ]
            });

            Phx.vista.PedidosAlmacen.superclass.constructor.call(this, config);
            this.store.baseParams = {tipo_interfaz: this.nombreVista};
            this.store.baseParams.pes_estado = 'pedido_al_pendiente';
            this.load({params: {start: 0, limit: this.tam_pag}});
            this.finCons = true;
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Report').setVisible(false);
            this.getBoton('ant_estado').setVisible(true);
         

        },
        gruposBarraTareas:[
            {name:'pedido_al_pendiente',title:'<H1 align="center"><i class="fa fa-folder-open"></i> Pendientes</h1>',grupo:3,height:0},
            {name:'pedido_al_solicitada',title:'<H1 align="center"><i class="fa fa-file"></i> Solicitadas</h1>',grupo:3,height:0},
            {name:'pedido_al_sin_resp',title:'<H1 align="center"><i class="fa fa-minus-circle"></i> Sin Respuestas</h1>',grupo:3,height:0},
            {name:'pedido_al_compra',title:'<H1 align="center"><i class="fa fa-money"></i> Compra</h1>',grupo:3,height:0},
            {name:'pedido_al_concluido',title:'<H1 align="center"><i class="fa fa-folder"></i> Concluido</h1>',grupo:3,height:0}
        ],

        actualizarSegunTab: function(name, indice){

            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        tam_pag:50,
        beditGroups: [2,3],
        bdelGroups:  [0],
        bactGroups:  [0,1,2,3],
        btestGroups: [0],
        bexcelGroups: [0,1,2,3]
    }

</script>
