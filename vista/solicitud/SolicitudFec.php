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
    Phx.vista.SolicitudFec = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'SolicitudFec',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/ControlDetalle.php',
                title:'Detalle',
                height:'50%',
                cls:'ControlDetalle'
            }
        ],


        constructor: function (config) {
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
                                title: ' Registro Fechas',
                                autoHeight: true,
                                items: [],
                                id_grupo: 8
                            }


                        ]
                    }
                ]

            });
            this.font();
            Phx.vista.SolicitudFec.superclass.constructor.call(this, config);
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
            this.getBoton('Control_aLmacene').setVisible(false);
            this.getBoton('btnproveedor').setVisible(false);

            this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);

        },
        gruposBarraTareas:[
         {name:'ab_origen_ing',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Operaciones</h1>',grupo:4,height:0, width: 100},
         {name:'ab_origen_man',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Mantenimiento</h1>',grupo:4,height:0, width: 100},
         {name:'ab_origen_alm',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Abastecimientos</h1>',grupo:4,height:0, width: 150}

         ],
        tam_pag:50,
        beditGroups: [2,4],
        bdelGroups:  [0],
        bactGroups:  [0,4],
        btestGroups: [0],
        bexcelGroups: [0,4],
        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
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
            Phx.vista.SolicitudFec.superclass.preparaMenu.call(this,n);

            if(data['estado'] ==  'cotizacion_sin_respuesta'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();

                this. enableTabDetalle();


            }else if(data['estado'] !=  'cotizacion_sin_respuesta'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();


                this.disableTabDetalle();
            }
            else {
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();

                this.disableTabDetalle();
            }

            return tb;
        },

        liberaMenu:function(){
            var tb = Phx.vista.SolicitudFec.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();


            }
            return tb;
        },
        font : function () {
            this.Atributos[this.getIndAtributo('nombre_estado_firma')].grid=true;
            this.Atributos[this.getIndAtributo('nro_po')].grid=true;
            this.Atributos[this.getIndAtributo('id_proveedor')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_cotizacion')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_arribado_bolivia')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_desaduanizacion')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_en_almacen')].grid=true;
            this.Atributos[this.getIndAtributo('tipo_evaluacion')].grid=true;
            this.Atributos[this.getIndAtributo('taller_asignado')].grid=true;
            this.Atributos[this.getIndAtributo('observacion_nota')].grid=true;
            this.Atributos[this.getIndAtributo('lugar_entrega')].grid=true;
            this.Atributos[this.getIndAtributo('condicion')].grid=true;
        }


    }

</script>

