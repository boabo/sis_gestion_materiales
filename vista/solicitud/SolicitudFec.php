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
                xtype: 'fieldset',
                style:{
                      background:'#548DCA',
                      marginTop:'-26px',
                     },

                items: [

                    {
                      xtype: 'fieldset',
                      style:{
                            background:'#FFB09C',
                            width:'330px',
                            //height:'330px',
                            border:'1px solid black',
                            borderRadius:'2px'
                           },
                        items: [
                            {
                                xtype: 'fieldset',
                                border: false,
                                title: ' Datos Adquisiciones ',
                                autoHeight: true,
                                items: [],
                                id_grupo: 2
                            }

                        ]
                    }
                ]

            });
            this.font();
            this.historico = 'no';
            this.tbarItems = ['-',{
                text: 'Histórico',
                enableToggle: true,
                pressed: false,
                toggleHandler: function(btn, pressed) {

                    if(pressed){
                        this.historico = 'si';
                    }
                    else{
                        this.historico = 'no'
                    }

                    this.store.baseParams.historico = this.historico;
                    this.reload();
                },
                scope: this
            }];
            Phx.vista.SolicitudFec.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'ab_origen_ing_n';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.getBoton('new').setVisible(false);
            this.getBoton('del').setVisible(false);
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Report').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
            // this.getBoton('btnproveedor').setVisible(false);
            // this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('clonar_solicitud').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);
        },
        gruposBarraTareas:[
             {name:'ab_origen_ing_n',title:'<H1 align="center" style="color:#E78800; font-size:11px;"><i style="font-size:12px;" class="fa fa-truck" aria-hidden="true"></i> Operaciones</h1>',grupo:4,height:0, width: 100},
             {name:'ab_origen_man_n',title:'<H1 align="center" style="color:#007AD9; font-size:11px;"><i style="font-size:12px;" class="fa fa-wrench" aria-hidden="true"></i> Mantenimiento</h1>',grupo:4,height:0, width: 100},
             {name:'ab_origen_alm_n',title:'<H1 align="center" style="color:#00A530; font-size:11px;"><i style="font-size:12px;" class="fa fa-retweet" aria-hidden="true"></i> Abastecimientos</h1>',grupo:4,height:0, width: 150},
             {name:'ab_origen_alm_ceac',title:'<H1 align="center" style="color:#FF0000; font-size:11px;"><i style="font-size:12px;" class="fa fa-plane" aria-hidden="true"></i> Ope. CEAC</h1>',grupo:4,height:0, width: 150},
             {name:'ab_origen_rep_n',title:'<H1 align="center" style="color:#7100BB; font-size:11px;"><i style="font-size:12px;" class="fa fa-cogs" aria-hidden="true"></i> Repuestos</h1>',grupo:4,height:0, width: 150}

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

        onButtonEdit: function() {
            this.iniciarEvento();
            Phx.vista.SolicitudFec.superclass.onButtonEdit.call(this);
            this.reload();
        },
        iniciarEvento : function () {
            var data = this.getSelectedData();
            if (this.store.baseParams.pes_estado == 'ab_origen_ing_n') {
                if (this.historico == 'si') {
                    this.ocultarComponente(this.Cmp.mel);
                    this.ocultarComponente(this.Cmp.tipo_reporte);
                    this.ocultarComponente(this.Cmp.tipo_falla);
                    this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                    this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                    this.ocultarComponente(this.Cmp.fecha_en_almacen);
                    this.ocultarComponente(this.Cmp.mensaje_correo);
                    this.ocultarComponente(this.Cmp.observacion_nota);
                    this.ocultarComponente(this.Cmp.taller_asignado);
                    this.ocultarComponente(this.Cmp.tipo_evaluacion);
                    this.ocultarComponente(this.Cmp.condicion);
                    this.ocultarComponente(this.Cmp.lugar_entrega);
                    this.mostrarComponente(this.Cmp.fecha_cotizacion);
                    this.mostrarComponente(this.Cmp.id_proveedor);
                    this.mostrarComponente(this.Cmp.nro_po);
                    this.mostrarComponente(this.Cmp.fecha_po);
                    this.mostrarComponente(this.Cmp.fecha_arribado_bolivia);
                    this.mostrarComponente(this.Cmp.fecha_desaduanizacion);
                    this.mostrarComponente(this.Cmp.fecha_en_almacen);
                    this.CampoBloqueado(true);
                } else {
                    this.ocultarComponente(this.Cmp.mel);
                    this.ocultarComponente(this.Cmp.tipo_reporte);
                    this.ocultarComponente(this.Cmp.tipo_falla);
                    this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                    this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                    this.ocultarComponente(this.Cmp.fecha_en_almacen);
                    this.ocultarComponente(this.Cmp.mensaje_correo);
                    this.ocultarComponente(this.Cmp.observacion_nota);
                    this.ocultarComponente(this.Cmp.taller_asignado);
                    this.ocultarComponente(this.Cmp.tipo_evaluacion);
                    this.ocultarComponente(this.Cmp.condicion);
                    this.ocultarComponente(this.Cmp.lugar_entrega);
                    this.mostrarComponente(this.Cmp.fecha_cotizacion);
                    this.mostrarComponente(this.Cmp.id_proveedor);
                    this.mostrarComponente(this.Cmp.nro_po);
                    this.mostrarComponente(this.Cmp.fecha_po);
                    this.CampoBloqueado(true);

                    if (data['estado'] == 'despachado') {
                        this.mostrarComponente(this.Cmp.fecha_arribado_bolivia);
                    }
                    if (data['estado'] == 'arribo') {
                        this.mostrarComponente(this.Cmp.fecha_desaduanizacion);
                    }
                    if (data['estado'] == 'desaduanizado') {
                        this.mostrarComponente(this.Cmp.fecha_en_almacen);
                    }

                }
            } else if (this.store.baseParams.pes_estado == 'ab_origen_man_n') {

                  if( this.historico == 'si' ){

                       this.mostrarComponente(this.Cmp.mel);
                       this.mostrarComponente(this.Cmp.tipo_reporte);
                       this.mostrarComponente(this.Cmp.tipo_falla);

                       this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                       this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                       this.ocultarComponente(this.Cmp.fecha_en_almacen);
                       this.ocultarComponente(this.Cmp.mensaje_correo);
                       this.ocultarComponente(this.Cmp.observacion_nota);
                       this.ocultarComponente(this.Cmp.taller_asignado);
                       this.ocultarComponente(this.Cmp.tipo_evaluacion);
                       this.ocultarComponente(this.Cmp.condicion);
                       this.ocultarComponente(this.Cmp.lugar_entrega);
                       this.mostrarComponente(this.Cmp.fecha_cotizacion);
                       this.mostrarComponente(this.Cmp.id_proveedor);
                       this.mostrarComponente(this.Cmp.nro_po);
                       this.mostrarComponente(this.Cmp.fecha_po);
                       this.mostrarComponente(this.Cmp.fecha_arribado_bolivia);
                       this.mostrarComponente(this.Cmp.fecha_desaduanizacion);
                       this.mostrarComponente(this.Cmp.fecha_en_almacen);
                       this.CampoBloqueado(true);
                   }else{
                       this.mostrarComponente(this.Cmp.mel);
                       this.mostrarComponente(this.Cmp.tipo_reporte);
                       this.mostrarComponente(this.Cmp.tipo_falla);

                       this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                       this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                       this.ocultarComponente(this.Cmp.fecha_en_almacen);
                       this.ocultarComponente(this.Cmp.mensaje_correo);
                       this.ocultarComponente(this.Cmp.observacion_nota);
                       this.ocultarComponente(this.Cmp.taller_asignado);
                       this.ocultarComponente(this.Cmp.tipo_evaluacion);
                       this.ocultarComponente(this.Cmp.condicion);
                       this.ocultarComponente(this.Cmp.lugar_entrega);
                       this.mostrarComponente(this.Cmp.fecha_cotizacion);
                       this.mostrarComponente(this.Cmp.id_proveedor);
                       this.mostrarComponente(this.Cmp.nro_po);
                       this.mostrarComponente(this.Cmp.fecha_po);
                       this.CampoBloqueado(true);

                       if(data['estado'] ==  'despachado'){
                           this.mostrarComponente(this.Cmp.fecha_arribado_bolivia);
                       }
                       if(data['estado'] ==  'arribo'){
                           this.mostrarComponente(this.Cmp.fecha_desaduanizacion);
                       }
                       if(data['estado'] ==  'desaduanizado') {
                           this.mostrarComponente(this.Cmp.fecha_en_almacen);
                       }

                   }
            }else{

                if( this.historico == 'si' ){

                    /*Aqui ponemos la condicion para que en rep y ceac se muestre la justificacion*/
                    this.mostrarComponente(this.Cmp.justificacion);
                    this.mostrarComponente(this.Cmp.nro_justificacion);
                    this.ocultarComponente(this.Cmp.nro_no_rutina);
                    /******************************************************************************/

                    this.ocultarComponente(this.Cmp.mel);
                    this.ocultarComponente(this.Cmp.tipo_reporte);
                    this.ocultarComponente(this.Cmp.tipo_falla);

                    //this.ocultarComponente(this.Cmp.justificacion);
                    this.ocultarComponente(this.Cmp.id_matricula);
                    //this.ocultarComponente(this.Cmp.nro_justificacion);

                    this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                    this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                    this.ocultarComponente(this.Cmp.fecha_en_almacen);
                    this.ocultarComponente(this.Cmp.mensaje_correo);
                    this.ocultarComponente(this.Cmp.observacion_nota);
                    this.ocultarComponente(this.Cmp.taller_asignado);
                    this.ocultarComponente(this.Cmp.tipo_evaluacion);
                    this.ocultarComponente(this.Cmp.condicion);
                    this.ocultarComponente(this.Cmp.lugar_entrega);
                    this.mostrarComponente(this.Cmp.fecha_cotizacion);
                    this.mostrarComponente(this.Cmp.id_proveedor);
                    this.mostrarComponente(this.Cmp.nro_po);
                    this.mostrarComponente(this.Cmp.fecha_po);
                    this.mostrarComponente(this.Cmp.fecha_arribado_bolivia);
                    this.mostrarComponente(this.Cmp.fecha_desaduanizacion);
                    this.mostrarComponente(this.Cmp.fecha_en_almacen);
                    this.CampoBloqueado(true);
                }else{

                  /*Aqui ponemos la condicion para que en rep y ceac se muestre la justificacion*/
                  this.mostrarComponente(this.Cmp.justificacion);
                  this.mostrarComponente(this.Cmp.nro_justificacion);
                  this.ocultarComponente(this.Cmp.nro_no_rutina);
                  /******************************************************************************/

                    this.ocultarComponente(this.Cmp.mel);
                    this.ocultarComponente(this.Cmp.tipo_reporte);
                    this.ocultarComponente(this.Cmp.tipo_falla);

                    //this.ocultarComponente(this.Cmp.justificacion);
                    this.ocultarComponente(this.Cmp.id_matricula);
                    //this.ocultarComponente(this.Cmp.nro_justificacion);

                    this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                    this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                    this.ocultarComponente(this.Cmp.fecha_en_almacen);
                    this.ocultarComponente(this.Cmp.mensaje_correo);
                    this.ocultarComponente(this.Cmp.observacion_nota);
                    this.ocultarComponente(this.Cmp.taller_asignado);
                    this.ocultarComponente(this.Cmp.tipo_evaluacion);
                    this.ocultarComponente(this.Cmp.condicion);
                    this.ocultarComponente(this.Cmp.lugar_entrega);
                    this.mostrarComponente(this.Cmp.fecha_cotizacion);
                    this.mostrarComponente(this.Cmp.id_proveedor);
                    this.mostrarComponente(this.Cmp.nro_po);
                    this.mostrarComponente(this.Cmp.fecha_po);
                    this.CampoBloqueado(true);

                    if(data['estado'] ==  'despachado'){
                        this.mostrarComponente(this.Cmp.fecha_arribado_bolivia);
                    }
                    if(data['estado'] ==  'arribo'){
                        this.mostrarComponente(this.Cmp.fecha_desaduanizacion);
                    }
                    if(data['estado'] ==  'desaduanizado') {
                        this.mostrarComponente(this.Cmp.fecha_en_almacen);
                    }

                }

            }

        },

        CampoBloqueado : function (sw) {
            this.Cmp.id_funcionario_sol.setDisabled(sw);
            this.Cmp.origen_pedido.setDisabled(sw);
            this.Cmp.id_matricula.setDisabled(sw);
            this.Cmp.motivo_solicitud.setDisabled(sw);
            this.Cmp.observaciones_sol.setDisabled(sw);
            this.Cmp.justificacion.setDisabled(sw);
            this.Cmp.nro_justificacion.setDisabled(sw);
            this.Cmp.tipo_solicitud.setDisabled(sw);
            this.Cmp.nro_no_rutina.setDisabled(sw);
            this.Cmp.mel.setDisabled(sw);
            this.Cmp.tipo_reporte.setDisabled(sw);
            this.Cmp.tipo_falla.setDisabled(sw);
            this.Cmp.fecha_cotizacion.setDisabled(sw);
            this.Cmp.id_proveedor.setDisabled(sw);
            this.Cmp.nro_po.setDisabled(sw);
            this.Cmp.fecha_po.setDisabled(sw);

            this.Cmp.fecha_requerida.setDisabled(sw);
            this.Cmp.fecha_solicitud.setDisabled(sw);
            this.Cmp.tiempo_entrega.setDisabled(sw);
            this.Cmp.mel_observacion.setDisabled(sw);
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
