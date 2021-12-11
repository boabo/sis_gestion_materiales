<?php
/**
 *@package pXP
 *@file RegistroSolicitud.php
 *@author  MAM
 *@date 27-12-2016 14:45
 *@Interface para el inicio de solicitudes de materiales
 */
include_once ('../../media/styles.php');
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.RegistroSolicitud = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        fwidth: 750,
        fheight: 420,

        title: 'Solicitud',
        nombreVista: 'RegistroSolicitud',
        constructor: function (config) {
            this.maestro = config.maestro;
            Phx.vista.RegistroSolicitud.superclass.constructor.call(this, config);
            this.store.baseParams = {tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'borrador_reg';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;

            /*Aqui ponemos la vista (Ismael Valdivia 20/02/2020)*/
            this.nombre_vista = 'RegistroSolicitud';
            /****************************************************/


            this.getBoton('ant_estado').setVisible(false);
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
           // this.getBoton('Consulta_desaduanizacion').setVisible(false);
            //this.getBoton('Control_aLmacene').setVisible(false);
            /*Comentando el proveedor (Ismael Valdivia 23/03/2020)*/
              //this.getBoton('btnproveedor').setVisible(false);
            /*****************************************************/
            this.getBoton('Cotizacion').setVisible(false);
            this.getBoton('btnObs').setVisible(false);
            /*Comentando el proveedor (Ismael Valdivia 23/03/2020)*/
              //this.getBoton('btnproveedor').setVisible(false);
            /*****************************************************/
            this.getBoton('clonar_solicitud').setVisible(false);

            this.ocultarComponente(this.Cmp.fecha_po);
            this.ocultarComponente(this.Cmp.tipo_evaluacion);
            this.ocultarComponente(this.Cmp.taller_asignado);
            this.ocultarComponente(this.Cmp.observacion_nota);
            this.ocultarComponente(this.Cmp.mensaje_correo);
            this.ocultarComponente(this.Cmp.condicion);
            this.ocultarComponente(this.Cmp.lugar_entrega);


        },
        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'g_registro',
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
            {name:'borrador_reg',title:'<H1 align="center" style="font-size:12px; color:red;"><i style="font-size:15px; font-weight: bold;" class="fa fa-pencil-square-o"></i> Borrador</h1>',grupo:0,height:0},
            {name:'vobo_area_reg',title:'<H1 "center" style="font-size:12px; color:#F2711C;"><i style="font-size:15px; font-weight: bold;" class="fa fa-thumbs-o-up"></i> Visto Bueno</h1>',grupo:1,height:0},
            {name:'revision_reg',title:'<H1 align="center" style="font-size:12px; color:blue;"><i style="font-size:15px; font-weight: bold;" class="fa fa-retweet"></i> Proceso</h1>',grupo:1,height:0},
            {name:'finalizado_reg',title:'<H1 align="center" style="font-size:12px; color:green;"><i style="font-size:15px; font-weight: bold;" class="fa fa-sign-in"></i> Finalizado</h1>',grupo:1,height:0}
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

        onButtonNew:function(){
            //abrir formulario de solicitud
            var me = this;
            me.objSolForm = Phx.CP.loadWindows('../../../sis_gestion_materiales/vista/solicitud/FromFormula.php',
                'Formulario Requerimiento de Materiales',
                {
                    modal:true,
                    width:'95%',
                    height:'95%'
                }, {data:{objPadre: me}
                },
                this.idContenedor,
                'FromFormula',
                {
                    config:[{
                        event:'successsave',
                        delegate: this.onSaveForm,
                    }],

                    scope:this
                });

        },

        onSaveForm:function(form,  objRes){


            var me = this;
            //muestra la ventana de documentos para este proceso wf
            Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
                'Chequear documento del WF',
                {
                    width:'90%',
                    height:500
                },
                {
                    id_solicitud: objRes.ROOT.datos.id_solicitud,
                    id_proceso_wf: objRes.ROOT.datos.id_proceso_wf,
                    num_tramite: objRes.ROOT.datos.num_tramite,
                    estao: objRes.ROOT.datos.estado,
                    nombreVista: 'Formulario de solicitud de compra',
                    tipo: 'solcom'  //para crear un boton de guardar directamente en la ventana de documentos

                },
                this.idContenedor,
                'DocumentoWf',
                {
                    config:[{
                        event:'finalizarsol',
                        delegate: this.onCloseDocuments,

                    }],

                    scope:this
                }
            )

            form.panel.destroy()
            me.reload();
        },


        onCloseDocuments: function(paneldoc, data, directo){
            var newrec = this.store.getById(data.id_solicitud);
            if(newrec){
                this.sm.selectRecords([newrec]);
                if(directo === true){
                    this.sigEstado(paneldoc);
                }
                else{
                    if(confirm("¿Desea mandar la solictud para aprobación?")){
                        this.sigEstado(paneldoc);
                    }
                }

            }


        },


        sigEstado:function(paneldoc)
        {
            var d= this.sm.getSelected().data;
            Phx.CP.loadingShow();
            console.log("aqui llega el dato para enciar",d);
            Ext.Ajax.request({
                // form:this.form.getForm().getEl(),
                url:'../../sis_gestion_materiales/control/Solicitud/siguienteEstadoSolicitudBorrador',
                params: { id_solicitud: d.id_solicitud,
                          id_estado_wf: d.id_estado_wf,
                          id_proceso_wf: d.id_proceso_wf,
                          estado: d.estado,
                         },
                argument: { paneldoc: paneldoc},
                success: this.successSinc,
                failure: this.conexionFail,
                timeout: this.timeout,
                scope: this
            });
        },


        conexionFail:function(resp,data){
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            console.log('error=> ',reg);

            Ext.Msg.show({
                title:'<h1 style="font-size:15px;">Aviso!</h1>',
                msg: '<p style="font-weight:bold; font-size:12px;">'+reg.ROOT.detalle.mensaje+'</p>',
                buttons: Ext.Msg.OK,
                width:450,
                height:200,
                icon: Ext.MessageBox.WARNING,
                scope:this
            });
            this.reload();
        },

        successSinc:function(resp){
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            console.log('succ=> ',reg);
            if(!reg.ROOT.error){
                if(resp.argument.paneldoc.panel){
                    resp.argument.paneldoc.panel.destroy();
                }
                this.reload();
            }else{

                alert('ocurrio un error durante el proceso')
            }


        },

        // sigEstado: function(){
        //     var rec = this.sm.getSelected();
        //     this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
        //         'Estado de Wf',
        //         {
        //             modal: true,
        //             width: 700,
        //             height: 450
        //         },
        //         {
        //             data: {
        //                 id_estado_wf: rec.data.id_estado_wf,
        //                 id_proceso_wf: rec.data.id_proceso_wf,
        //                 /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
        //                 id_solicitud: rec.data.id_solicitud
        //                 /********************************************************************************************/
        //             }
        //         }, this.idContenedor, 'FormEstadoWf',
        //
        //         {
        //             config: [{
        //                 event: 'beforesave',
        //                 delegate: this.onSaveWizard
        //             }],
        //             scope: this
        //         }
        //     );
        //
        //
        // },
        // onSaveWizard:function(wizard,resp){
        //     var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
        //
        //     Phx.CP.loadingShow();
        //     Ext.Ajax.request({
        //         url:'../../sis_gestion_materiales/control/Solicitud/siguienteEstadoSolicitud',
        //         params:{
        //             id_proceso_wf_act:  resp.id_proceso_wf_act,
        //             id_estado_wf_act:   resp.id_estado_wf_act,
        //             id_tipo_estado:     resp.id_tipo_estado,
        //             id_funcionario_wf:  resp.id_funcionario_wf,
        //             id_depto_wf:        resp.id_depto_wf,
        //             obs:                resp.obs,
        //             json_procesos:      Ext.util.JSON.encode(resp.procesos),
        //             /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
        //             id_solicitud:       wizard.data.id_solicitud,
        //             /********************************************************************************************/
        //         },
        //         success:this.successWizard,
        //         failure: this.conexionFailure,
        //         argument:{wizard:wizard},
        //         timeout:this.timeout,
        //         scope:this
        //     });
        //
        //     /*var rec = this.sm.getSelected();
        //     Phx.CP.loadingShow();
        //     Ext.Ajax.request({
        //         url: '../../sis_gestion_materiales/control/Solicitud/iniciarDisparo',
        //         params: {
        //             id_estado_wf: rec.data.id_estado_wf,
        //             id_proceso_wf: rec.data.id_proceso_wf
        //         },
        //         success:this.successWizard,
        //         failure: this.conexionFailure,
        //         argument:{wizard:wizard},
        //         timeout: this.timeout,
        //         scope: this
        //     });*/
        //
        //
        // },
        // successWizard:function(resp){
        //     Phx.CP.loadingHide();
        //     resp.argument.wizard.panel.destroy();
        //     this.reload();
        // },

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
        onButtonEdit: function() {
            var data = this.getSelectedData();
            Phx.vista.Solicitud.superclass.onButtonEdit.call(this);
            this.window.items.items[0].body.dom.style.background = '#548DCA';
      			this.window.mask.dom.style.background = '#7E7E7E';
      			this.window.mask.dom.style.opacity = '0.8';
            /*Aumentando para recuperar el Id_Moneda (Ismael Valdivia 11/02/2020)*/
            this.Cmp.id_moneda.store.load({params:{start:0,limit:50},
               callback : function (r) {
                     this.Cmp.id_moneda.setValue(this.Cmp.id_moneda.getValue());
                     this.Cmp.id_moneda.fireEvent('select',this.Cmp.id_moneda,this.Cmp.id_moneda.getValue());

                }, scope : this
            });
            /*********************************************************************/
            if(this.Cmp.origen_pedido.getValue() == 'Gerencia de Operaciones'){
                this.mostrarComponente(this.Cmp.mel);
                this.ocultarComponente(this.Cmp.tipo_reporte);
                this.ocultarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.ocultarComponente(this.Cmp.id_proveedor);
                this.ocultarComponente(this.Cmp.lista_correos);
                this.ocultarComponente(this.Cmp.nro_po);
                this.mostrarComponente(this.Cmp.justificacion);
                this.mostrarComponente(this.Cmp.nro_justificacion);
                this.ocultarComponente(this.Cmp.obs_pac);
                this.ocultarComponente(this.Cmp.monto_pac);
                this.ocultarComponente(this.Cmp.nro_lote);
                this.ocultarComponente(this.Cmp.id_forma_pago);
                this.ocultarComponente(this.Cmp.id_condicion_entrega);

                /*Aqui poner condicion para mostrar el mel observacion*/
                if(this.Cmp.justificacion.getValue() == '"0" Existencia en Almacén' || this.Cmp.justificacion.getValue() == 'Otros'){
                  this.ocultarComponente(this.Cmp.nro_justificacion);
                } else {
                  this.mostrarComponente(this.Cmp.nro_justificacion);
                }

                this.Cmp.justificacion.on('select', function (c,r,i) {
                  if(this.Cmp.justificacion.getValue() == '"0" Existencia en Almacén' || this.Cmp.justificacion.getValue() == 'Otros'){
                    this.ocultarComponente(this.Cmp.nro_justificacion);
                  } else {
                    this.Cmp.nro_justificacion.reset();
                    this.mostrarComponente(this.Cmp.nro_justificacion);
                  }
                },this);
                /******************************************************/

                /*Aqui poner condicion para mostrar el mel observacion*/
                if(this.Cmp.mel.getValue() == 'A' || this.Cmp.mel.getValue() == 'Otro' || this.Cmp.mel.getValue() == 'otro' || this.Cmp.mel.getValue() == 'OTRO'){
                  this.mostrarComponente(this.Cmp.mel_observacion);
                } else {
                  this.Cmp.mel_observacion.reset();
                  this.ocultarComponente(this.Cmp.mel_observacion);
                }

                this.Cmp.mel.on('select', function (c,r,i) {
                  if(this.Cmp.mel.getValue() == 'A' || this.Cmp.mel.getValue() == 'Otro' || this.Cmp.mel.getValue() == 'otro' || this.Cmp.mel.getValue() == 'OTRO'){
                    this.mostrarComponente(this.Cmp.mel_observacion);
                  } else {
                    this.Cmp.mel_observacion.reset();
                    this.ocultarComponente(this.Cmp.mel_observacion);
                  }
                },this);
                /******************************************************/

                this.Cmp.mel.allowBlank = true;
                this.Cmp.tipo_reporte.allowBlank = true;
                this.Cmp.tipo_falla.allowBlank = true;
                this.Cmp.justificacion.allowBlank = true;
                this.Cmp.id_matricula.allowBlank = true;
                this.Cmp.nro_justificacion.allowBlank = true;
                this.Cmp.fecha_arribado_bolivia.allowBlank = true;
                this.Cmp.fecha_desaduanizacion.allowBlank = true;
                this.Cmp.fecha_en_almacen.allowBlank = true;
                this.Cmp.fecha_cotizacion.allowBlank = true;
                this.Cmp.id_proveedor.allowBlank = true;
                this.Cmp.lista_correos.allowBlank = true;
                this.Cmp.nro_po.allowBlank = true;
                this.Cmp.obs_pac.allowBlank = true;
                this.Cmp.monto_pac.allowBlank = true;
                this.Cmp.nro_lote.allowBlank = true;
                this.Cmp.id_forma_pago.allowBlank = true;
                this.Cmp.id_condicion_entrega.allowBlank = true;

            }   else if (this.Cmp.origen_pedido.getValue() == 'Gerencia de Mantenimiento'){
                this.mostrarComponente(this.Cmp.mel);
                this.mostrarComponente(this.Cmp.tipo_reporte);
                this.mostrarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.ocultarComponente(this.Cmp.id_proveedor);
                this.ocultarComponente(this.Cmp.lista_correos);
                this.ocultarComponente(this.Cmp.nro_po);
                this.mostrarComponente(this.Cmp.justificacion);
                this.mostrarComponente(this.Cmp.nro_justificacion);
                this.ocultarComponente(this.Cmp.obs_pac);
                this.ocultarComponente(this.Cmp.monto_pac);
                this.ocultarComponente(this.Cmp.nro_lote);
                this.ocultarComponente(this.Cmp.id_forma_pago);
                this.ocultarComponente(this.Cmp.id_condicion_entrega);

                /*Aqui poner condicion para mostrar el mel observacion*/
                if(this.Cmp.justificacion.getValue() == '"0" Existencia en Almacén' || this.Cmp.justificacion.getValue() == 'Otros'){
                  this.ocultarComponente(this.Cmp.nro_justificacion);
                } else {
                  this.mostrarComponente(this.Cmp.nro_justificacion);
                }

                this.Cmp.justificacion.on('select', function (c,r,i) {
                  if(this.Cmp.justificacion.getValue() == '"0" Existencia en Almacén' || this.Cmp.justificacion.getValue() == 'Otros'){
                    this.ocultarComponente(this.Cmp.nro_justificacion);
                  } else {
                    this.Cmp.nro_justificacion.reset();
                    this.mostrarComponente(this.Cmp.nro_justificacion);
                  }
                },this);
                /******************************************************/

                if(this.Cmp.mel.getValue() == 'A' || this.Cmp.mel.getValue() == 'Otro' || this.Cmp.mel.getValue() == 'otro' || this.Cmp.mel.getValue() == 'OTRO'){
                  this.mostrarComponente(this.Cmp.mel_observacion);
                } else {
                  this.Cmp.mel_observacion.reset();
                  this.ocultarComponente(this.Cmp.mel_observacion);
                }

                this.Cmp.mel.on('select', function (c,r,i) {
                  if(this.Cmp.mel.getValue() == 'A' || this.Cmp.mel.getValue() == 'Otro' || this.Cmp.mel.getValue() == 'otro' || this.Cmp.mel.getValue() == 'OTRO'){
                    this.mostrarComponente(this.Cmp.mel_observacion);
                  } else {
                    this.Cmp.mel_observacion.reset();
                    this.ocultarComponente(this.Cmp.mel_observacion);
                  }
                },this);



                this.Cmp.mel.allowBlank = true;
                this.Cmp.tipo_reporte.allowBlank = true;
                this.Cmp.tipo_falla.allowBlank = true;
                this.Cmp.justificacion.allowBlank = true;
                this.Cmp.id_matricula.allowBlank = true;
                this.Cmp.nro_justificacion.allowBlank = true;
                this.Cmp.fecha_arribado_bolivia.allowBlank = true;
                this.Cmp.fecha_desaduanizacion.allowBlank = true;
                this.Cmp.fecha_en_almacen.allowBlank = true;
                this.Cmp.fecha_cotizacion.allowBlank = true;
                this.Cmp.id_proveedor.allowBlank = true;
                this.Cmp.lista_correos.allowBlank = true;
                this.Cmp.nro_po.allowBlank = true;
                this.Cmp.obs_pac.allowBlank = true;
                this.Cmp.monto_pac.allowBlank = true;
                this.Cmp.nro_lote.allowBlank = true;
                this.Cmp.id_forma_pago.allowBlank = true;
                this.Cmp.id_condicion_entrega.allowBlank = true;

            }   else if (this.Cmp.origen_pedido.getValue() == 'Almacenes Consumibles o Rotables'){
                this.mostrarComponente(this.Cmp.mel);
                this.ocultarComponente(this.Cmp.tipo_reporte);
                this.ocultarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.justificacion);
                this.ocultarComponente(this.Cmp.id_matricula);
                this.ocultarComponente(this.Cmp.nro_justificacion);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.ocultarComponente(this.Cmp.id_proveedor);
                this.ocultarComponente(this.Cmp.lista_correos);
                this.ocultarComponente(this.Cmp.nro_po);
                this.ocultarComponente(this.Cmp.obs_pac);
                this.ocultarComponente(this.Cmp.monto_pac);
                this.ocultarComponente(this.Cmp.nro_lote);
                this.ocultarComponente(this.Cmp.id_forma_pago);
                this.ocultarComponente(this.Cmp.id_condicion_entrega);
                this.ocultarComponente(this.Cmp.tipo_solicitud);
                this.ocultarComponente(this.Cmp.nro_no_rutina);


                if(this.Cmp.mel.getValue() == 'A' || this.Cmp.mel.getValue() == 'Otro' || this.Cmp.mel.getValue() == 'otro' || this.Cmp.mel.getValue() == 'OTRO'){
                  this.mostrarComponente(this.Cmp.mel_observacion);
                } else {
                  this.Cmp.mel_observacion.reset();
                  this.ocultarComponente(this.Cmp.mel_observacion);
                }

                this.Cmp.mel.on('select', function (c,r,i) {
                  if(this.Cmp.mel.getValue() == 'A' || this.Cmp.mel.getValue() == 'Otro' || this.Cmp.mel.getValue() == 'otro' || this.Cmp.mel.getValue() == 'OTRO'){
                    this.mostrarComponente(this.Cmp.mel_observacion);
                  } else {
                    this.Cmp.mel_observacion.reset();
                    this.ocultarComponente(this.Cmp.mel_observacion);
                  }
                },this);

                this.Cmp.mel.allowBlank = true;
                this.Cmp.tipo_reporte.allowBlank = true;
                this.Cmp.tipo_falla.allowBlank = true;
                this.Cmp.justificacion.allowBlank = true;
                this.Cmp.id_matricula.allowBlank = true;
                this.Cmp.nro_justificacion.allowBlank = true;
                this.Cmp.fecha_arribado_bolivia.allowBlank = true;
                this.Cmp.fecha_desaduanizacion.allowBlank = true;
                this.Cmp.fecha_en_almacen.allowBlank = true;
                this.Cmp.fecha_cotizacion.allowBlank = true;
                this.Cmp.id_proveedor.allowBlank = true;
                this.Cmp.lista_correos.allowBlank = true;
                this.Cmp.nro_po.allowBlank = true;
                this.Cmp.obs_pac.allowBlank = true;
                this.Cmp.monto_pac.allowBlank = true;
                this.Cmp.nro_lote.allowBlank = true;
                this.Cmp.id_forma_pago.allowBlank = true;
                this.Cmp.id_condicion_entrega.allowBlank = true;
                this.Cmp.tipo_solicitud.allowBlank = true;
                this.Cmp.nro_no_rutina.allowBlank = true;

            } else if (this.Cmp.origen_pedido.getValue() == 'Centro de Entrenamiento Aeronautico Civil'){
                this.ocultarComponente(this.Cmp.mel);
                this.ocultarComponente(this.Cmp.tipo_reporte);
                this.ocultarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.justificacion);
                this.ocultarComponente(this.Cmp.id_matricula);
                this.ocultarComponente(this.Cmp.nro_justificacion);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.ocultarComponente(this.Cmp.id_proveedor);
                this.ocultarComponente(this.Cmp.lista_correos);
                this.ocultarComponente(this.Cmp.nro_po);
                this.ocultarComponente(this.Cmp.obs_pac);
                this.ocultarComponente(this.Cmp.monto_pac);
                this.ocultarComponente(this.Cmp.nro_lote);
                this.ocultarComponente(this.Cmp.id_forma_pago);
                this.ocultarComponente(this.Cmp.id_condicion_entrega);

                this.Cmp.mel.allowBlank = true;
                this.Cmp.tipo_reporte.allowBlank = true;
                this.Cmp.tipo_falla.allowBlank = true;
                this.Cmp.justificacion.allowBlank = true;
                this.Cmp.id_matricula.allowBlank = true;
                this.Cmp.nro_justificacion.allowBlank = true;
                this.Cmp.fecha_arribado_bolivia.allowBlank = true;
                this.Cmp.fecha_desaduanizacion.allowBlank = true;
                this.Cmp.fecha_en_almacen.allowBlank = true;
                this.Cmp.fecha_cotizacion.allowBlank = true;
                this.Cmp.id_proveedor.allowBlank = true;
                this.Cmp.lista_correos.allowBlank = true;
                this.Cmp.nro_po.allowBlank = true;
                this.Cmp.obs_pac.allowBlank = true;
                this.Cmp.monto_pac.allowBlank = true;
                this.Cmp.nro_lote.allowBlank = true;
                this.Cmp.id_forma_pago.allowBlank = true;
                this.Cmp.id_condicion_entrega.allowBlank = true;

            } else if (this.Cmp.origen_pedido.getValue() == 'Reparación de Repuestos' || this.Cmp.origen_pedido.getValue() == 'Gerencia de Repuestos'){
                this.ocultarComponente(this.Cmp.mel);
                this.ocultarComponente(this.Cmp.tipo_reporte);
                this.ocultarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.justificacion);
                this.ocultarComponente(this.Cmp.id_matricula);
                this.ocultarComponente(this.Cmp.nro_justificacion);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.ocultarComponente(this.Cmp.id_proveedor);
                this.ocultarComponente(this.Cmp.lista_correos);
                this.ocultarComponente(this.Cmp.nro_no_rutina);
                this.ocultarComponente(this.Cmp.fecha_requerida);
                this.ocultarComponente(this.Cmp.obs_pac);
                this.ocultarComponente(this.Cmp.monto_pac);
                this.ocultarComponente(this.Cmp.tipo_solicitud);

                this.mostrarComponente(this.Cmp.nro_po);
                this.mostrarComponente(this.Cmp.nro_lote);
                this.mostrarComponente(this.Cmp.id_forma_pago);
                this.mostrarComponente(this.Cmp.id_condicion_entrega);


                this.Cmp.nro_lote.allowBlank = false;
                this.Cmp.id_forma_pago.allowBlank = false;
                this.Cmp.id_condicion_entrega.allowBlank = false;

                this.Cmp.mel.allowBlank = true;
                this.Cmp.tipo_reporte.allowBlank = true;
                this.Cmp.tipo_falla.allowBlank = true;
                this.Cmp.justificacion.allowBlank = true;
                this.Cmp.id_matricula.allowBlank = true;
                this.Cmp.nro_justificacion.allowBlank = true;
                this.Cmp.fecha_arribado_bolivia.allowBlank = true;
                this.Cmp.fecha_desaduanizacion.allowBlank = true;
                this.Cmp.fecha_en_almacen.allowBlank = true;
                this.Cmp.fecha_cotizacion.allowBlank = true;
                this.Cmp.id_proveedor.allowBlank = true;
                this.Cmp.lista_correos.allowBlank = true;
                this.Cmp.nro_no_rutina.allowBlank = true;
                this.Cmp.fecha_requerida.allowBlank = true;
                this.Cmp.obs_pac.allowBlank = true;
                this.Cmp.monto_pac.allowBlank = true;
                this.Cmp.tipo_solicitud.allowBlank = true;

                this.Cmp.id_condicion_entrega.on('select', function (c,r,i) {
                  this.Cmp.codigo_condicion_entrega.setValue(r.data.nombre);
                },this);

                this.Cmp.id_forma_pago.on('select', function (c,r,i) {
                  this.Cmp.codigo_forma_pago.setValue(r.data.nombre);
                },this);


            }
            //Ext.getCmp('datos_generales').el.dom.style.height='289px';
            //Ext.getCmp('justificacion_necesidad').hide();


        },

        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.RegistroSolicitud.superclass.preparaMenu.call(this,n);

            if(data['estado'] ==  'borrador'){
                this.getBoton('sig_estado').enable();
                this. enableTabDetalle();


            }else if(data['estado'] !=  'vobo_area') {
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();


                this.disableTabDetalle();

            } else {
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
                this.disableTabDetalle();

            }

            return tb;
        },
        liberaMenu:function(){
            var tb = Phx.vista.RegistroSolicitud.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').setVisible(false);
                this.getBoton('ini_estado').setVisible(false);
            }
            return tb;
        }


    };
</script>
