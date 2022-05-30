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
    Phx.vista.SolicitudVistoBueno = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'VistoBueno',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleSol.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleSol'
            }
        ],
        constructor: function (config) {


          this.Grupos.push( {
              layout: 'column',
              border: false,
              xtype: 'fieldset',
              autoScroll: false,
              defaults: {
                  border: false
              },
              style:{
                    background:'#548DCA',
                    // height:'245px',
                    autoHeight: true,
                    // border:'2px solid red',
                    marginTop:'-26px',
                    // marginLeft: '50%'
                   },
              items: [
                  {
                    xtype: 'fieldset',
                    autoScroll: false,
                    style:{
                          background:'#FFB09C',
                          width:'330px',
                          height:'330px',
                          border:'1px solid black',
                          borderRadius:'2px',
                          marginTop:'5px'
                         },
                      items: [
                          {
                              xtype: 'fieldset',
                              border: false,
                              autoScroll: false,
                              title: ' Datos Adquisiciones ',
                              autoHeight: true,
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
                    style:{
                          background:'#81D3FF',
                          width:'330px',
                          height:'330px',
                          marginLeft:'2px',
                          border:'1px solid black',
                          borderRadius:'2px',
                          marginTop:'5px'
                         },
                      items: [
                          {
                              xtype: 'fieldset',
                              title: ' Datos Comité de Evaluación ',
                              autoHeight: true,
                              border: false,
                              autoScroll: false,
                              style:{
                                    background:'#81D3FF',
                                    //border:'2px solid green',
                                    //width : '100%',
                                   },
                              items: [],
                              id_grupo: 5
                          }


                      ]
                  },
              ]

          });

            //this.Atributos[this.getIndAtributo('nombre_estado')].grid=false;
            this.Atributos[this.getIndAtributo('tipo_evaluacion')].grid=false;
            this.Atributos[this.getIndAtributo('taller_asignado')].grid=false;
            this.Atributos[this.getIndAtributo('observacion_nota')].grid=false;
            this.Atributos[this.getIndAtributo('lugar_entrega')].grid=false;
            this.Atributos[this.getIndAtributo('condicion')].grid=false;

            Phx.vista.SolicitudVistoBueno.superclass.constructor.call(this, config);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'pedido_revision';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.getBoton('Report').setVisible(false);
            //this.getBoton('edit').setVisible(false);
            //this.getBoton('ini_estado').setVisible(true);
            this.getBoton('ant_estado').setVisible(true);
            this.getBoton('sig_estado').setVisible(true);
            this.getBoton('Archivado_concluido').setVisible(false);
            this.getBoton('clonar_solicitud').setVisible(false);

           // this.getBoton('Consulta_desaduanizacion').setVisible(false);
            //.getBoton('Control_aLmacene').setVisible(false);
            //this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);
        },

        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'g_vistoBueno',
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
          {name:'pedido_revision',title:'<H1 align="center" style="color:#E85C00; font-size:12px;"><i style="font-size:15px;" class="fa fa-pencil-square"></i> Revisión</h1>',grupo:50,height:0},
          {name:'pedido_iniciado',title:'<H1 align="center" style="color:#0023FF; font-size:12px;"><i style="font-size:15px;" class="fa fa-location-arrow"></i> Iniciados</h1>',grupo:51,height:0},
          {name:'pedido_tiene_po',title:'<H1 align="center" style="color:#31A200; font-size:12px;"><i style="font-size:15px;" class="fa fa-check-square"></i> Tienen PO</h1>',grupo:52,height:0},
        ],


        bactGroups:  [50,51,52],
        beditGroups: [50,51],
        bganttGroups: [50,51,52],

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
            Phx.vista.SolicitudVistoBueno.superclass.preparaMenu.call(this,n);

            if(data['estado'] ==  'revision_tecnico_abastecimientos'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();


                if (this.store.baseParams.pes_estado == 'pedido_revision') {
                  this.getBoton('ini_estado').enable();
                  this.getBoton('ini_estado').setVisible(true);
                }else{
                  this.getBoton('ini_estado').disable();
                  this.getBoton('ini_estado').setVisible(false);
                }


                //this.getBoton('autorizar').enable();
                this. enableTabDetalle();
            }




            if(data['estado'] ==  'cotizacion_solicitada'){
              this.getBoton('Cotizacion').setVisible(true);
            } else {
              this.getBoton('Cotizacion').setVisible(false);
            }


            return tb;
        },
        liberaMenu:function(){
            var tb = Phx.vista.SolicitudVistoBueno.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                //this.getBoton('edit').setVisible(false);
                this.getBoton('Report').setVisible(false);
                this.getBoton('Archivado_concluido').enable();
                this.getBoton('Archivado_concluido').setVisible(true);
                //this.getBoton('ini_estado').setVisible(true);
               // this.getBoton('del').disable();
            }
            return tb;
        },


        onButtonEdit: function() {
            this.iniciarEvento();
            var data = this.getSelectedData();
            Phx.vista.SolicitudVistoBueno.superclass.onButtonEdit.call(this);

            /*Aumentando para resetear la condicion y el proveedor*/
            if (data.condicion == '') {
              this.Cmp.condicion.reset();
            }

            if (data.lista_correos == '') {
              this.Cmp.lista_correos.reset();
            }
            /******************************************************/


            if (data.remark == '') {
              this.Cmp.remark.setValue(data.motivo_solicitud);
            } else {
              this.Cmp.remark.setValue(data.remark);
            }

            //console.log("aqui llega el mensaje del correo",this);
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

            /*Aqui ocultamos el campo (Ismael valdivia 08/04/2020)*/
            this.ocultarComponente(this.Cmp.nro_justificacion);
            this.mostrarComponente(this.Cmp.justificacion);
            if (data.estado == 'cotizacion') {
                this.Cmp.lista_correos.reset();
                this.Cmp.condicion.reset();
            }
            /******************************************************/
            /*Aumentamos la condicion para mostrar el nuevo Campo MELOBSERVACION Ismael Valdivia 01/10/2020*/
            if(data.mel == 'A' || data.mel == 'Otro' || data.mel == 'otro' || data.mel == 'OTRO'){
              this.mostrarComponente(this.Cmp.mel_observacion);
            } else {
              this.ocultarComponente(this.Cmp.mel_observacion);
            }
            /***********************************************************************************************/


            if (data['origen_pedido'] == 'Gerencia de Mantenimiento' && this.historico == 'si') {
                this.mostrarComponente(this.Cmp.mel);
                this.mostrarComponente(this.Cmp.tipo_reporte);
                this.mostrarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.mostrarComponente(this.Cmp.fecha_cotizacion);
                this.mostrarComponente(this.Cmp.id_proveedor);
                this.ocultarComponente(this.Cmp.nro_po);
                this.mostrarComponente(this.Cmp.fecha_po);
                this.mostrarComponente(this.Cmp.fecha_entrega);
                this.Cmp.nro_po.allowBlank = true;
                this.CampoBloqueado(true);
            }else{
                var data = this.getSelectedData();
                this.mostrarComponente(this.Cmp.mel);
                this.mostrarComponente(this.Cmp.tipo_reporte);
                this.mostrarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.ocultarComponente(this.Cmp.id_proveedor);
                this.ocultarComponente(this.Cmp.nro_po);
                this.ocultarComponente(this.Cmp.fecha_po);
                this.ocultarComponente(this.Cmp.fecha_entrega);
                this.Cmp.fecha_po.allowBlank = true;
                this.Cmp.nro_po.allowBlank = true;
                this.CampoBloqueado(true);

                this.mostrarComponente(this.Cmp.tiempo_entrega);
                this.Cmp.tiempo_entrega.allowBlank=false;

                /*Aqui poner condcion para ocultar el campo de dias de entrega para el reporte de invitacion (Ismael Valdivia)*/
                if (data['estado'] ==  'cotizacion' || data['estado'] ==  'cotizacion_solicitada') {
                  /*Comentando y ocultar el campo a pedido de Paty (Ismael Valdivia 29/10/2021)*/

                  //this.ocultarComponente(this.Cmp.tiempo_entrega);
                  //this.Cmp.tiempo_entrega.allowBlank=true;

                  this.mostrarComponente(this.Cmp.metodo_de_adjudicación);
                  this.Cmp.metodo_de_adjudicación.allowBlank=false;

                  this.mostrarComponente(this.Cmp.tipo_de_adjudicacion);
                  this.Cmp.tipo_de_adjudicacion.allowBlank=false;

                }else {
                //  this.ocultarComponente(this.Cmp.tiempo_entrega);
                //  this.Cmp.tiempo_entrega.allowBlank=true;

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



                    if (data.origen_solicitud == 'ERP' || data.origen_solicitud == '' ) {
                      //this.mostrarComponente(this.Cmp.nro_po);
                    }
                    //this.mostrarComponente(this.Cmp.nro_po);
                    this.mostrarComponente(this.Cmp.fecha_po);
                    this.mostrarComponente(this.Cmp.fecha_entrega);


                    this.Cmp.fecha_po.on('select', function (c,r,i) {
                      /*Aumentamos la fecha 30 dias mas*/
                      var fecha_po = new Date(this.Cmp.fecha_po.getValue());
                      fecha_po.setDate(fecha_po.getDate() + 15);
                      var fecha_formateada = fecha_po.format('d/m/Y');
                      /*********************************/
                      this.Cmp.fecha_entrega.setValue(fecha_formateada);
                    },this);


                    this.Cmp.id_proveedor.setValue(data['id_proveedor']);
                    this.Cmp.id_proveedor.setRawValue(data['desc_proveedor']);
                    this.Cmp.fecha_cotizacion.setValue(data['fecha_cotizacion'] );
                } else {
                  this.ocultarComponente(this.Cmp.fecha_entrega);
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
            this.Cmp.mel.setDisabled(sw);
            this.Cmp.tipo_reporte.setDisabled(sw);
            this.Cmp.tipo_falla.setDisabled(sw);
            this.Cmp.tipo_solicitud.setDisabled(sw);
            this.Cmp.mel_observacion.setDisabled(sw);

            /*Aumentnado para que los campos esten bloqueados*/
            //this.Cmp.fecha_requerida.setDisabled(sw);
            //this.Cmp.fecha_solicitud.setDisabled(sw);
            this.Cmp.motivo_solicitud.setDisabled(sw);
            /*************************************************/




        },
        /*Comentando esta parte para que directamente cambiemos el estado de las Solicitudes
        vb_rpcd y vb_dpto_abastecimientos Ismael Valdivia (03/02/2020)*/
        //Comentando esta parte para pasar los estados siguientes de vobo vb_dpto_administrativo y vobo_rpcd
        sigEstado: function(){
            var rec = this.sm.getSelected();
            var tramite = rec.data['nro_tramite'];
            var tipo_tramite = tramite.substring(0,2);

            this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
                'Estado de Wf',
                {
                    modal: true,
                    width: 700,
                    height: 450
                },
                {
                    data: {
                        id_estado_wf: rec.data.id_estado_wf,
                        id_proceso_wf: rec.data.id_proceso_wf,
                        /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
                        id_solicitud: rec.data.id_solicitud
                        /********************************************************************************************/
                    }
                }, this.idContenedor, 'FormEstadoWf',

                {
                    config: [{
                        event: 'beforesave',
                        delegate: this.onSaveWizard
                    }],
                    scope: this
                }
            );

            //this.onSaveWizard(rec);
        },

        onSaveWizard:function(wizard,resp){
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/siguienteEstadoSolicitud',
                params:{

                    id_proceso_wf_act:  resp.id_proceso_wf_act,
                    id_estado_wf_act:   resp.id_estado_wf_act,
                    id_tipo_estado:     resp.id_tipo_estado,
                    id_funcionario_wf:  resp.id_funcionario_wf,
                    id_depto_wf:        resp.id_depto_wf,
                    obs:                resp.obs,
                    json_procesos:      Ext.util.JSON.encode(resp.procesos),
                    /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
                    id_solicitud:       wizard.data.id_solicitud,
                    /********************************************************************************************/
                },
                success:this.successWizard,
                failure: this.conexionFailure,
                argument:{wizard:wizard},
                timeout:this.timeout,
                scope:this
            });

        },


        successWizard:function(resp){
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
        },


        antEstado:function(res){

            var rec=this.sm.getSelected();
              Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
                  'Estado de Wf',
                  {
                      modal:true,
                      width:450,
                      height:250
                  }, { data:rec.data, estado_destino: res.argument.estado}, this.idContenedor,'AntFormEstadoWf',
                  {
                      config:[{
                          event:'beforesave',
                          delegate: this.onAntEstadoProceso
                      }
                      ],
                      scope:this
                  })


        },

        onAntEstadoProceso: function(wizard,resp){
            Phx.CP.loadingShow();
            var rec = this.sm.getSelected();
            Ext.Ajax.request({
                //url:'../../sis_gestion_materiales/control/Solicitud/anteriorDisparo',
                url:'../../sis_gestion_materiales/control/Solicitud/anteriorEstadoSolicitud',
                params:{
                    //id_proceso_wf_firma: rec.data.id_proceso_wf_firma,
                    //id_estado_wf_firma:  rec.data.id_estado_wf_firma,
                     id_proceso_wf: resp.id_proceso_wf,
                     id_estado_wf:  resp.id_estado_wf,
                    obs: resp.obs,
                    estado_destino: resp.estado_destino
                },
                argument:{wizard:wizard},
                success:this.successEstadoSinc,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },
        successEstadoSinc:function(resp){
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
        },



        onOpenObs:function() {
            var rec=this.sm.getSelected();
            console.log("aqui llega seleccionado",rec);
                var data = {
                    id_proceso_wf: rec.data.id_proceso_wf,
                    id_estado_wf: rec.data.id_estado_wf,
                    num_tramite: rec.data.nro_tramite
                };
            Phx.CP.loadWindows('../../../sis_workflow/vista/obs/Obs.php',
                'Observaciones del WF',
                {
                    width:'80%',
                    height:'70%'
                },
                data,
                this.idContenedor,
                'Obs'
            )
        },
        iniEstado:function(res){
            var rec=this.sm.getSelected();
            Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
                'Estado de Wf',
                {
                    modal:true,
                    width:450,
                    height:250
                }, { data:rec.data, estado_destino: res.argument.estado}, this.idContenedor,'AntFormEstadoWf',
                {
                    config:[{
                        event:'beforesave',
                        delegate: this.inAntEstado
                    }
                    ],
                    scope:this
                })
        },
        inAntEstado: function(wizard,resp){
            Phx.CP.loadingShow();
            var operacion = 'cambiar';
            operacion =  resp.estado_destino == 'inicio'?'inicio':operacion;
            Ext.Ajax.request({
              //  url:'../../sis_gestion_materiales/control/Solicitud/inicioEstadoSolicitudDisparo',
                url:'../../sis_gestion_materiales/control/Solicitud/inicioEstadoSolicitud',
                params:{
                    id_proceso_wf: resp.id_proceso_wf,
                    id_estado_wf:  resp.id_estado_wf,
                    operacion: operacion,
                    obs: resp.obs
                },
                argument:{wizard:wizard},
                success:this.succeEstadoSinc,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },
        succeEstadoSinc:function(resp){
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
        },


        bdel:false,
        bsave:false,
        bnew:false
    }

</script>
