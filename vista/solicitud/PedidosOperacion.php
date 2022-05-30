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
    Phx.vista.PedidosOperacion = {
      fheight:650,
      fwidth:1410,
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'PedidoOperacion',
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
            this.Grupos[0].items.push( {
              xtype: 'fieldset',
              id:'datos_pac_op',
              hidden:true,
              style:{
                    background:'#E1EC00',
                    width:'330px',
                    minHeight:'520px',
                    marginLeft:'2px',
                    marginTop:'0px',
                    border:'1px solid black',
                    borderRadius:'2px',

                   },
                items: [
                    {
                        xtype: 'fieldset',
                        title: ' Datos PAC',
                        autoHeight: true,
                        border: false,
                        autoScroll: false,
                        collapsible: false,
                        style:{
                              background:'#E1EC00',
                             },
                        items: [],
                        id_grupo: 10
                    }


                ]
            },{
                      xtype: 'fieldset',
                      autoScroll: false,
                      id:'datos_adquisiciones_op',
                      style:{
                            background:'#FFB09C',
                            width:'330px',
                            minHeight:'520px',
                            border:'1px solid black',
                            marginLeft:'2px',
                            borderRadius:'2px',
                           },
                        items: [
                            {
                                xtype: 'fieldset',
                                border: false,
                                autoScroll: false,
                                title: ' Datos Adquisiciones ',
                                autoHeight: true,
                                collapsible: false,
                                style:{
                                      background:'#FFB09C'
                                     },
                                items: [],
                                id_grupo: 2
                            }

                        ]
                    },
                    {
                              xtype: 'fieldset',
                              id:'datos_comite_op',
                              style:{
                                    background:'#81D3FF',
                                    width:'330px',
                                    minHeight:'520px',
                                    marginLeft:'2px',
                                    marginTop:'2px',
                                    border:'1px solid black',
                                    borderRadius:'2px'
                                   },
                                items: [
                                    {
                                        xtype: 'fieldset',
                                        title: ' Datos Comité de Evaluación ',
                                        autoHeight: true,
                                        border: false,
                                        autoScroll: false,
                                        collapsible: false,
                                        style:{
                                              background:'#81D3FF',
                                              //border:'2px solid green',
                                              //width : '100%',
                                             },
                                        items: [],
                                        id_grupo: 5
                                    }


                                ]
                            }

                  );
            // this.Grupos.push( {
            //     layout: 'column',
            //     border: false,
            //     xtype: 'fieldset',
            //     autoScroll: false,
            //     defaults: {
            //         border: false
            //     },
            //     style:{
            //           background:'#548DCA',
            //           // height:'245px',
            //           autoHeight: true,
            //           // border:'2px solid red',
            //           marginTop:'-26px',
            //           // marginLeft: '50%'
            //          },
            //
            //     items: [
            //
            //       {
            //         xtype: 'fieldset',
            //         autoScroll: false,
            //         style:{
            //               background:'#FFB09C',
            //               width:'330px',
            //               height:'330px',
            //               border:'1px solid black',
            //               borderRadius:'2px',
            //              },
            //           items: [
            //               {
            //                   xtype: 'fieldset',
            //                   border: false,
            //                   autoScroll: false,
            //                   title: ' Datos Adquisiciones ',
            //                   autoHeight: true,
            //                   style:{
            //                         background:'#FFB09C'
            //                        },
            //                   items: [],
            //                   id_grupo: 2
            //               }
            //
            //           ]
            //       },
            //       {
            //         xtype: 'fieldset',
            //         style:{
            //               background:'#81D3FF',
            //               width:'330px',
            //               height:'330px',
            //               marginLeft:'2px',
            //               border:'1px solid black',
            //               borderRadius:'2px'
            //              },
            //           items: [
            //               {
            //                   xtype: 'fieldset',
            //                   title: ' Datos Comité de Evaluación ',
            //                   autoHeight: true,
            //                   border: false,
            //                   autoScroll: false,
            //                   style:{
            //                         background:'#81D3FF',
            //                         //border:'2px solid green',
            //                         //width : '100%',
            //                        },
            //                   items: [],
            //                   id_grupo: 5
            //               }
            //
            //
            //           ]
            //       }
            //     ]
            //
            // });
            this.historico = 'no';
            this.tbarItems = ['-',{
                    text: 'Histórico',
                    enableToggle: true,
                    pressed: false,
                    toggleHandler: function(btn, pressed) {

                        if(pressed){
                            this.historico = 'si';
                            //this.desBotoneshistorico();
                        }
                        else{
                            this.historico = 'no'
                        }

                        this.store.baseParams.historico = this.historico;
                        this.reload();
                    },
                    scope: this
                }
            ];
            Phx.vista.PedidosOperacion.superclass.constructor.call(this, config);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'pedido_op_pendiente';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.getBoton('Report').setVisible(false);
            this.addButton('btnpac',
                {
                    iconCls: 'bemail',
                    text: 'Generar PAC Referencial',
                    grupo:[3],
                    disabled: true,
                    handler: this.correoPac,
                    tooltip: '<b>Envia Correo PAC</b>'
                }
            );



        },
        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'g_operacion',
            fieldLabel: 'Gestion',
            allowBlank: true,
            emptyText:'Gestion...',
            blankText: 'Año',
            store:new Ext.data.JsonStore(
                {
                    url: '../../sis_parametros/control/Gestion/listarGestion',
                    id: 'id_gestion',
                    root: 'datos',
                    sortInfo:{
                        field: 'gestion',
                        direction: 'DESC'
                    },
                    totalProperty: 'total',
                    fields: ['id_gestion','gestion'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams:{par_filtro:'gestion'}
                }),
            valueField: 'id_gestion',
            triggerAction: 'all',
            displayField: 'gestion',
            hiddenName: 'id_gestion',
            mode:'remote',
            pageSize:50,
            queryDelay:500,
            listWidth:'280',
            hidden:false,
            width:80
        }),

        gruposBarraTareas:[
            {name:'pedido_op_pendiente',title:'<H1 align="center" style="color:#E85C00; font-size:12px;"><i style="font-size:15px;" class="fa fa-pencil-square"></i> Pendientes</h1>',grupo:3,height:0},
            {name:'pedido_op_solicitada',title:'<H1 align="center" style="color:#00AD1F; font-size:12px;"><i style="font-size:15px;" class="fa fa-check-circle"></i> Solicitadas</h1>',grupo:3,height:0},
            {name:'pedido_op_sin_resp',title:'<H1 align="center" style="color:red; font-size:12px;"><i style="font-size:15px;" class="fa fa-times-circle"></i> Sin Respuestas</h1>',grupo:3,height:0},
            {name:'pedido_op_comite',title:'<H1 align="center" style="color:#007615; font-size:12px;"><i style="font-size:15px;" class="fa fa-thumbs-up"></i> Vobo Comité</h1>',grupo:5,height:0},
            {name:'pedido_op_compra',title:'<H1 align="center" style="color:#0038D8; font-size:12px;"><i style="font-size:15px;" class="fa fa-shopping-cart"></i> Compra</h1>',grupo:3,height:0},
            {name:'pedido_op_concluido',title:'<H1 align="center" style="color:#00B377; font-size:12px;"><i style="font-size:15px;" class="fa fa fa-th-list"></i> Concluido</h1>',grupo:5,height:0}
        ],

        actualizarSegunTab: function(name, indice){

            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                if(name == 'pedido_op_solicitada'){
                    //this.getBoton('btnproveedor').setVisible(true);
                    this.getBoton('Cotizacion').setVisible(true);
                    this.getBoton('btnpac').setVisible(true);
                    this.getBoton('ant_estado').setVisible(true);
                }else if (name == 'pedido_op_pendiente' || name == 'pedido_op_compra'){
                    //this.getBoton('btnproveedor').setVisible(true);
                    this.getBoton('Cotizacion').setVisible(true);
                    //this.getBoton('ant_estado').setVisible(false);
                } else{
                    //this.getBoton('btnproveedor').setVisible(false);
                    this.getBoton('btnpac').setVisible(false);
                    this.getBoton('Cotizacion').setVisible(false);
                    this.getBoton('btnpac').setVisible(false);
                    this.getBoton('ant_estado').setVisible(true);
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
        bganttGroups: [0,1,2,3,5],

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
            Phx.vista.PedidosOperacion.superclass.preparaMenu.call(this,n);
            //this.getBoton('btnproveedor').enable();
            this.getBoton('Cotizacion').enable();
            this.getBoton('btnpac').enable();

            /*Aqui pondremos para verificar los docuementos*/
            if (this.store.baseParams.pes_estado == 'pedido_op_solicitada') {
              Ext.Ajax.request({
                  url:'../../sis_gestion_materiales/control/Solicitud/getVerificarDocumentos',
                  params:{id_proceso_wf: data.id_proceso_wf,
                          estado_sig: 'comite_unidad_abastecimientos'},
                  success:function(resp){
                      var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                      if (reg.ROOT.datos.v_chekeado == 'no') {
                          this.getBoton('sig_estado').disable();
                          this.noti_documentos.setText('Adjuntar doc: '+reg.ROOT.datos.nombre_documento);
                      } else {
                        this.getBoton('sig_estado').enable();
                        this.noti_documentos.setText('');
                        //this.reload();
                      }
                    /************************************************************************************/

                  },
                  failure: this.conexionFailure,
                  timeout:this.timeout,
                  scope:this
              });
            }
            if (this.store.baseParams.pes_estado == 'pedido_op_compra') {

              /*Recuperamos el monto del Adjudicado*/
              Ext.Ajax.request({
                  url:'../../sis_gestion_materiales/control/Solicitud/getVerificarMontoAdjudicado',
                  params:{id_proceso_wf: data.id_proceso_wf},
                  success:function(resp){
                      var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                      console.log("aqui llega el monto recuperado",reg);
                      //this.monto_convertido_bs = reg.ROOT.datos.monto_adjudicado;

                      if (reg.ROOT.datos.monto_adjudicado > 20000) {

                        Ext.Ajax.request({
                              url:'../../sis_gestion_materiales/control/Solicitud/getVerificarDocumentos',
                              params:{id_proceso_wf: data.id_proceso_wf,
                                      estado_sig: 'despachado'},
                              success:function(resp){
                                  var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                                  if (reg.ROOT.datos.v_chekeado == 'no') {
                                      this.getBoton('sig_estado').disable();
                                      this.noti_documentos.setText('Adjuntar doc: '+reg.ROOT.datos.nombre_documento);
                                  } else {
                                    this.getBoton('sig_estado').enable();
                                    this.noti_documentos.setText('');
                                    //this.reload();
                                  }
                                /************************************************************************************/

                              },
                              failure: this.conexionFailure,
                              timeout:this.timeout,
                              scope:this
                          });
                      } else {
                        this.getBoton('sig_estado').enable();
                        this.noti_documentos.setText('');
                        /*Recuperando la fecha cuando autoriza Jaime Lazarte para compra*/
                        Ext.Ajax.request({
                              url:'../../sis_gestion_materiales/control/Solicitud/getVerificarDocumentos',
                              params:{id_proceso_wf: data.id_proceso_wf,
                                      estado_sig: 'compra'},
                              success:function(resp){
                                  var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                                  this.store.baseParams.fecha_po_automatico = reg.ROOT.datos.fecha_po_automatico;
                                /************************************************************************************/

                              },
                              failure: this.conexionFailure,
                              timeout:this.timeout,
                              scope:this
                          });
                          /****************************************************************/

                        //this.reload();
                      }




                    /************************************************************************************/

                  },
                  failure: this.conexionFailure,
                  timeout:this.timeout,
                  scope:this
              });
              /*************************************/


              // if (this.store.baseParams.monto_pac > 20000) {
              //
              //   Ext.Ajax.request({
              //         url:'../../sis_gestion_materiales/control/Solicitud/getVerificarDocumentos',
              //         params:{id_proceso_wf: data.id_proceso_wf,
              //                 estado_sig: 'despachado'},
              //         success:function(resp){
              //             var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
              //             if (reg.ROOT.datos.v_chekeado == 'no') {
              //                 this.getBoton('sig_estado').disable();
              //                 this.noti_documentos.setText('Adjuntar doc: '+reg.ROOT.datos.nombre_documento);
              //             } else {
              //               this.getBoton('sig_estado').enable();
              //               this.noti_documentos.setText('');
              //               //this.reload();
              //             }
              //           /************************************************************************************/
              //
              //         },
              //         failure: this.conexionFailure,
              //         timeout:this.timeout,
              //         scope:this
              //     });
              // } else {
              //   this.getBoton('sig_estado').enable();
              //   this.noti_documentos.setText('');
              //   /*Recuperando la fecha cuando autoriza Jaime Lazarte para compra*/
              //   Ext.Ajax.request({
              //         url:'../../sis_gestion_materiales/control/Solicitud/getVerificarDocumentos',
              //         params:{id_proceso_wf: data.id_proceso_wf,
              //                 estado_sig: 'compra'},
              //         success:function(resp){
              //             var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
              //             this.store.baseParams.fecha_po_automatico = reg.ROOT.datos.fecha_po_automatico;
              //           /************************************************************************************/
              //
              //         },
              //         failure: this.conexionFailure,
              //         timeout:this.timeout,
              //         scope:this
              //     });
              //     /****************************************************************/
              //
              //   //this.reload();
              // }
            }
            /***********************************************/



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
            var tb = Phx.vista.PedidosOperacion.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('sig_estado').disable();
                //this.getBoton('btnproveedor').disable();
                this.getBoton('btnpac').disable();

                this.getBoton('Archivado_concluido').enable();
                this.getBoton('Cotizacion').disable();
                this.getBoton('ini_estado').disable();
                this.getBoton('Report').setVisible(false);

            }
            return tb;
        },
        bnew: false,
        fheight:'100%',
      	fwidth: 750,
        onButtonEdit: function() {
            this.iniciarEvento();
            var data = this.getSelectedData();
            Phx.vista.PedidosOperacion.superclass.onButtonEdit.call(this);

            /*Aumentando para resetear la condicion y el proveedor*/
            if (data.condicion == '') {
              this.Cmp.condicion.reset();
            }

            if (data.lista_correos == '') {
              this.Cmp.lista_correos.reset();
            }
            /******************************************************/

            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/getVerificarMontoAdjudicado',
                params:{id_proceso_wf: data.id_proceso_wf},
                success:function(resp){
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                    console.log("aqui llega el monto recuperado",reg);
                    //if (reg.ROOT.datos.monto_adjudicado > 20000 && (data.estado != 'borrador' && data.estado != 'revision' && data.estado != 'cotizacion' && data.estado != 'revision_tecnico_abastecimiento')) {
                      if (reg.ROOT.datos.monto_adjudicado > 20000 && (data.estado != 'borrador' && data.estado != 'revision' && data.estado != 'cotizacion' && data.estado != 'revision_tecnico_abastecimiento')) {
                        Ext.getCmp('datos_adquisiciones_op').el.dom.style.minHeight = '265px';
                        Ext.getCmp('datos_comite_op').el.dom.style.minHeight = '250px';

                        Ext.getCmp('datos_pac_op').show();
                        this.Cmp.nro_pac.allowBlank = false;
                        this.Cmp.fecha_pac.allowBlank = false;
                        this.Cmp.objeto_contratacion.allowBlank = false;

                        this.mostrarComponente(this.Cmp.nro_pac);
                        this.mostrarComponente(this.Cmp.fecha_pac);
                        this.mostrarComponente(this.Cmp.objeto_contratacion);


                      }else{
                        Ext.getCmp('datos_adquisiciones_op').el.dom.style.minHeight = '520px';
                        Ext.getCmp('datos_comite_op').el.dom.style.minHeight = '520px';
                        Ext.getCmp('datos_pac_op').hide();
                        this.Cmp.nro_pac.allowBlank = true;
                        this.Cmp.fecha_pac.allowBlank = true;
                        this.Cmp.objeto_contratacion.allowBlank = true;
                        this.Cmp.nro_pac.reset();
                        this.Cmp.fecha_pac.reset();
                        this.Cmp.objeto_contratacion.reset();

                        this.ocultarComponente(this.Cmp.nro_pac);
                        this.ocultarComponente(this.Cmp.fecha_pac);
                        this.ocultarComponente(this.Cmp.objeto_contratacion);

                      }

                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });


            // if (this.store.baseParams.monto_pac > 20000 && (data.estado != 'borrador' && data.estado != 'revision' && data.estado != 'cotizacion' && data.estado != 'revision_tecnico_abastecimiento')) {
            //   Ext.getCmp('datos_adquisiciones_op').el.dom.style.minHeight = '265px';
            //   Ext.getCmp('datos_comite_op').el.dom.style.minHeight = '250px';
            //
            //   Ext.getCmp('datos_pac_op').show();
            //   this.Cmp.nro_pac.allowBlank = false;
            //   this.Cmp.fecha_pac.allowBlank = false;
            //   this.Cmp.objeto_contratacion.allowBlank = false;
            //
            //   this.mostrarComponente(this.Cmp.nro_pac);
            //   this.mostrarComponente(this.Cmp.fecha_pac);
            //   this.mostrarComponente(this.Cmp.objeto_contratacion);
            //
            //
            // }else{
            //   Ext.getCmp('datos_adquisiciones_op').el.dom.style.minHeight = '520px';
            //   Ext.getCmp('datos_comite_op').el.dom.style.minHeight = '520px';
            //   Ext.getCmp('datos_pac_op').hide();
            //   this.Cmp.nro_pac.allowBlank = true;
            //   this.Cmp.fecha_pac.allowBlank = true;
            //   this.Cmp.objeto_contratacion.allowBlank = true;
            //   this.Cmp.nro_pac.reset();
            //   this.Cmp.fecha_pac.reset();
            //   this.Cmp.objeto_contratacion.reset();
            //
            //   this.ocultarComponente(this.Cmp.nro_pac);
            //   this.ocultarComponente(this.Cmp.fecha_pac);
            //   this.ocultarComponente(this.Cmp.objeto_contratacion);
            //
            // }


            if (data.remark == '') {
              this.Cmp.remark.setValue(data.motivo_solicitud);
            } else {
              this.Cmp.remark.setValue(data.remark);
            }

            this.Cmp.mensaje_correo.setValue('Favor cotizar según documento Adjunto. Cuando se traten de componentes Rotables por favor detallar el tiempo de garantía del componente ofertado en cada cotización y en caso de ser adjudicado también detallar en la factura.');

            if (this.Cmp.tipo_de_adjudicacion.getValue() == '') {
              this.Cmp.tipo_de_adjudicacion.setValue('Ninguno');
            }

            var fecha_salida = '2021-12-13';

            var diaActual = new Date(fecha_salida).getDate() + 1;
            var mesActual = new Date(fecha_salida).getMonth() + 1;
            var añoActual = new Date(fecha_salida).getFullYear();

            if (diaActual < 10) {
              diaActual = "0"+diaActual;
            }

            if (mesActual < 10) {
              mesActual = "0"+mesActual;
            }

            var fechaFormateada = diaActual + "/" + mesActual + "/" + añoActual;


            var fecha_recuperado = this.Cmp.fecha_solicitud.getValue();

            var diaActual = new Date(fecha_recuperado).getDate();
            var mesActual = new Date(fecha_recuperado).getMonth() + 1;
            var añoActual = new Date(fecha_recuperado).getFullYear();

            if (diaActual < 10) {
              diaActual = "0"+diaActual;
            }

            if (mesActual < 10) {
              mesActual = "0"+mesActual;
            }

            var fechaFormateadaRecu = diaActual + "/" + mesActual + "/" + añoActual;



            // if ((fechaFormateadaRecu <= fechaFormateada) && (data['estado'] ==  'cotizacion' || data['estado'] ==  'cotizacion_solicitada')) {
            //   console.log("entra reseteo 1");
            //   this.Cmp.condicion.reset();
            //   this.Cmp.condicion.setValue('');
            // }


            if (this.Cmp.nro_po.getValue()) {
              this.Cmp.fecha_po.setDisabled(true);
              this.Cmp.fecha_entrega.setDisabled(true);
            } else {
              this.Cmp.fecha_po.setDisabled(false);
              this.Cmp.fecha_entrega.setDisabled(false);
            }
            this.ocultarComponente(this.Cmp.taller_asignado);
            this.ocultarComponente(this.Cmp.observacion_nota);
            this.Cmp.tipo_evaluacion.on('select',function(combo, record, index){
                if (record.data.ID == 1 ){
                    this.ocultarComponente(this.Cmp.taller_asignado);
                    this.ocultarComponente(this.Cmp.observacion_nota);
                }if (record.data.ID == 2){
                    this.mostrarComponente(this.Cmp.taller_asignado);
                    this.mostrarComponente(this.Cmp.observacion_nota);
                }if (record.data.ID == 3){
                    this.ocultarComponente(this.Cmp.taller_asignado);
                    this.mostrarComponente(this.Cmp.observacion_nota);
                }
                this.Cmp.taller_asignado.reset();
                this.Cmp.observacion_nota.reset();
            },this);
            this.reload();
            },

        iniciarEvento:function () {
          var data = this.getSelectedData();
          if (data.estado == 'cotizacion') {
              this.Cmp.lista_correos.reset();
              this.Cmp.condicion.reset();
          }
          /*Aqui ocultamos el campo (Ismael valdivia 08/04/2020)*/
          this.ocultarComponente(this.Cmp.nro_no_rutina);
          this.ocultarComponente(this.Cmp.nro_justificacion);
          /******************************************************/
          /*Aumentamos la condicion para mostrar el nuevo Campo MELOBSERVACION Ismael Valdivia 01/10/2020*/
          if(data.mel == 'A' || data.mel == 'Otro' || data.mel == 'otro' || data.mel == 'OTRO'){
            this.mostrarComponente(this.Cmp.mel_observacion);
          } else {
            this.ocultarComponente(this.Cmp.mel_observacion);
          }
          /***********************************************************************************************/


            if (this.Cmp.origen_pedido.getValue() == 'Gerencia de Operaciones' && this.historico == 'si') {
                        this.ocultarComponente(this.Cmp.mel);
                        this.ocultarComponente(this.Cmp.tipo_reporte);
                        this.ocultarComponente(this.Cmp.tipo_falla);
                        this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                        this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                        this.ocultarComponente(this.Cmp.fecha_en_almacen);
                        this.mostrarComponente(this.Cmp.fecha_cotizacion);
                        this.mostrarComponente(this.Cmp.id_proveedor);
                        this.ocultarComponente(this.Cmp.nro_po);
                        this.Cmp.nro_po.allowBlank = true;
                        this.mostrarComponente(this.Cmp.fecha_po);
                        this.mostrarComponente(this.Cmp.fecha_entrega);
                        this.CampoBloqueado(true);
            }else{
                var data = this.getSelectedData();
                    this.ocultarComponente(this.Cmp.mel);
                    this.ocultarComponente(this.Cmp.tipo_reporte);
                    this.ocultarComponente(this.Cmp.tipo_falla);
                    this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                    this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                    this.ocultarComponente(this.Cmp.fecha_en_almacen);
                    this.ocultarComponente(this.Cmp.fecha_cotizacion);
                    this.ocultarComponente(this.Cmp.id_proveedor);
                    this.ocultarComponente(this.Cmp.nro_po);
                    this.ocultarComponente(this.Cmp.fecha_po);
                    this.Cmp.nro_po.allowBlank = true;
                    this.Cmp.fecha_po.allowBlank = true;
                    this.Cmp.mel.allowBlank = true;
                    this.Cmp.tipo_reporte.allowBlank = true;
                    this.Cmp.tipo_falla.allowBlank = true;
                    this.Cmp.fecha_arribado_bolivia.allowBlank = true;
                    this.CampoBloqueado(true);

                    this.mostrarComponente(this.Cmp.tiempo_entrega);
                    this.Cmp.tiempo_entrega.allowBlank=false;

                    /*Aqui poner condcion para ocultar el campo de dias de entrega para el reporte de invitacion (Ismael Valdivia)*/
                    if (data['estado'] ==  'cotizacion' || data['estado'] ==  'cotizacion_solicitada' || data['estado'] ==  'compra') {

                      //this.ocultarComponente(this.Cmp.tiempo_entrega);
                      //this.Cmp.tiempo_entrega.allowBlank=true;

                      this.mostrarComponente(this.Cmp.tipo_evaluacion);

                      this.mostrarComponente(this.Cmp.metodo_de_adjudicación);
                      this.Cmp.metodo_de_adjudicación.allowBlank=false;

                      this.mostrarComponente(this.Cmp.tipo_de_adjudicacion);
                      this.Cmp.tipo_de_adjudicacion.allowBlank=false;
                    }else {
                      //this.ocultarComponente(this.Cmp.tiempo_entrega);
                      //this.Cmp.tiempo_entrega.allowBlank=true;
                      this.ocultarComponente(this.Cmp.tipo_evaluacion);

                      this.ocultarComponente(this.Cmp.metodo_de_adjudicación);
                      this.Cmp.metodo_de_adjudicación.allowBlank=true;

                      this.ocultarComponente(this.Cmp.tipo_de_adjudicacion);
                      this.Cmp.tipo_de_adjudicacion.allowBlank=true;
                    }
                    /*************************************************************************************************************/

                    if(data['estado'] ==  'despachado' || data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado' )  {
                      this.mostrarComponente(this.Cmp.nro_po);
                    }

                    if(data['estado'] ==  'compra' || data['estado'] ==  'despachado' || data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado' )  {
                        this.mostrarComponente(this.Cmp.fecha_cotizacion);
                        this.mostrarComponente(this.Cmp.id_proveedor);
                        //this.mostrarComponente(this.Cmp.nro_po);
                        this.mostrarComponente(this.Cmp.fecha_po);
                        this.mostrarComponente(this.Cmp.fecha_entrega);


                        this.Cmp.fecha_po.on('select', function (c,r,i) {
                          /*Aumentamos la fecha 15 dias mas*/
                          var fecha_po = new Date(this.Cmp.fecha_po.getValue());
                          fecha_po.setDate(fecha_po.getDate() + 15);
                          var fecha_formateada = fecha_po.format('d/m/Y');
                          /*********************************/
                          this.Cmp.fecha_entrega.setValue(fecha_formateada);
                        },this);

                        this.Cmp.id_proveedor.setValue(data['id_proveedor']);
                        this.Cmp.id_proveedor.setRawValue(data['desc_proveedor']);
                        this.Cmp.fecha_cotizacion.setValue(data['fecha_cotizacion'] );




                    }

            }




        },
        CampoBloqueado : function (sw) {
            this.Cmp.id_funcionario_sol.setDisabled(sw);
            this.Cmp.origen_pedido.setDisabled(sw);
            this.Cmp.id_matricula.setDisabled(sw);
            this.Cmp.justificacion.setDisabled(sw);
            this.Cmp.nro_justificacion.setDisabled(sw);
            // this.Cmp.tipo_solicitud.setDisabled(sw);
            this.Cmp.nro_no_rutina.setDisabled(sw);
            this.Cmp.tipo_solicitud.setDisabled(sw);
        },



        font:function () {
            this.Atributos[this.getIndAtributo('tipo_falla')].grid=false;
            this.Atributos[this.getIndAtributo('tipo_reporte')].grid=false;
            this.Atributos[this.getIndAtributo('mel')].grid=true;
            this.Atributos[this.getIndAtributo('nro_po')].grid=true;
            this.Atributos[this.getIndAtributo('id_proveedor')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_cotizacion')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_po')].grid=true;
            this.Atributos[this.getIndAtributo('nombre_estado_firma')].grid=true;
            this.Atributos[this.getIndAtributo('mensaje_correo')].grid = true;
            this.Atributos[this.getIndAtributo('tipo_evaluacion')].grid=true;
            this.Atributos[this.getIndAtributo('taller_asignado')].grid=true;
            this.Atributos[this.getIndAtributo('observacion_nota')].grid=true;
            this.Atributos[this.getIndAtributo('lugar_entrega')].grid=true;
            this.Atributos[this.getIndAtributo('condicion')].grid=true;
        },

        successSave:function(resp){
          Phx.vista.PedidosOperacion.superclass.successSave.call(this,resp);
          if (this.historico == 'no') {
            this.confirmarEstado();
          }
        },

    }

</script>
