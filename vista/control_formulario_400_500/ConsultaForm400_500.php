<?php
/**
 *@package pXP
 *@file gen-Informe.php
 *@author  (Ismael Valdivia)
 *@date 30-05-2012 11:31:07
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ConsultaForm400_500=Ext.extend(Phx.gridInterfaz,{

        bnew : false,
        bedit : false,
        bdel : false,
        ActList:'../../sis_gestion_materiales/control/Solicitud/listarForm400',
        gruposBarraTareas:[
            {name:'si-400',title:'<H1 align="center" style="font-size:10px; color:green;"><i class="fa fa-check"></i> CON FORMULARIO <BR/>400</H1>',grupo:0,height:0, width: 300},
            {name:'no-400',title:'<H1 align="center" style="font-size:10px; color:red;"><i class="fa fa-times"></i> SIN FORMULARIO <BR/>400</H1>',grupo:1,height:0, width: 300},
            {name:'si-500',title:'<H1 align="center" style="font-size:10px; color:green;"><i class="fa fa-check"></i> CON FORMULARIO <BR/>500</H1>',grupo:2,height:0, width: 300},
            {name:'no-500',title:'<H1 align="center" style="font-size:10px; color:red;"><i class="fa fa-times"></i> SIN FORMULARIO <BR/>500</H1>',grupo:3,height:0, width: 300},
            {name:'cantidad_tramites',title:'<H1 align="center" style="font-size:10px; color:blue;"><i class="fa fa-list-ol" aria-hidden="true"></i> TRÁMITES <BR/>ASIGNADOS</H1>',grupo:4,height:0, width: 700},


        ],
        bactGroups:  [0,1,2,3,4],
        bexcelGroups: [0,1,2,3,4],
        bganttGroups: [0,1,2,3],

        east:
              {
                  url:'../../../sis_gestion_materiales/vista/control_cantidad_procesos/CantidadTotalizado.php',
                  title:'Cantidad Procesos',
                  width:'37%',
                  collapsed: true,
                  cls:'CantidadTotalizado'
             },




        actualizarSegunTab: function(name, indice){
            this.store.baseParams.tipo_formulario = name;

            if (name == 'si-400' || name == 'no-400') {
              this.cm.setHidden(17,true);
              this.cm.setHidden(18,false);
              this.cm.setHidden(1,false);
              this.cm.setHidden(3,false);
              this.cm.setHidden(4,false);
              this.cm.setHidden(5,false);
              this.cm.setHidden(6,false);
              this.cm.setHidden(7,false);
              this.cm.setHidden(8,false);
              this.cm.setHidden(9,false);
              this.cm.setHidden(10,false);
              this.cm.setHidden(11,false);
              this.cm.setHidden(12,false);
              this.cm.setHidden(13,false);
              this.cm.setHidden(14,false);
              this.cm.setHidden(16,false);
              this.cm.setHidden(17,false);
              this.cm.setHidden(18,true);

              // if (Phx.CP.getPagina('docs-COTRFORM400-east-0') != undefined) {
              //   Phx.CP.getPagina('docs-COTRFORM400-east-0').cargarPagina(this.fecha_inicio.getValue(),this.fecha_fin.getValue(),this.cmbAux.getValue(),name);
              // }



            } else if (name == 'si-500' || name == 'no-500') {
              this.cm.setHidden(16,false);
              this.cm.setHidden(15,true);

              this.cm.setHidden(1,false);
              this.cm.setHidden(2,false);
              this.cm.setHidden(3,false);
              this.cm.setHidden(4,false);
              this.cm.setHidden(5,false);
              this.cm.setHidden(6,false);
              this.cm.setHidden(7,false);
              this.cm.setHidden(8,false);
              this.cm.setHidden(9,false);
              this.cm.setHidden(10,false);
              this.cm.setHidden(11,false);
              this.cm.setHidden(12,false);
              this.cm.setHidden(13,false);
              this.cm.setHidden(15,false);
              this.cm.setHidden(16,true);
              this.cm.setHidden(17,true);

              // if (Phx.CP.getPagina('docs-COTRFORM400-east-0') != undefined) {
              //   Phx.CP.getPagina('docs-COTRFORM400-east-0').cargarPagina(this.fecha_inicio.getValue(),this.fecha_fin.getValue(),this.cmbAux.getValue(),name);
              // }

            } else if (name == 'cantidad_tramites') {
                this.cm.setHidden(1,true);
                this.cm.setHidden(3,true);
                this.cm.setHidden(4,true);
                this.cm.setHidden(5,true);
                this.cm.setHidden(6,true);
                this.cm.setHidden(7,true);
                this.cm.setHidden(8,true);
                this.cm.setHidden(9,true);
                this.cm.setHidden(10,true);
                this.cm.setHidden(11,true);
                this.cm.setHidden(12,true);
                this.cm.setHidden(13,true);
                this.cm.setHidden(14,true);
                this.cm.setHidden(16,true);
                this.cm.setHidden(17,true);
                this.cm.setHidden(18,false);


            }


            if (this.fecha_fin.getValue() != '') {
              this.load({params:{start:0, limit:this.tam_pag}});


              if (Phx.CP.getPagina('docs-COTRFORM400-east-0') != undefined) {
                Phx.CP.getPagina('docs-COTRFORM400-east-0').cargarPagina(this.fecha_inicio.getValue(),this.fecha_fin.getValue(),this.cmbAux.getValue(),name);
              }

            }

        },

        constructor:function(config){
            // this.tbarItems = ['-',this.cmbGestion,'-',
            //     this.cmbAux,'-'
            //
            // ];

            this.maestro=config;
            // Ext.Ajax.request({
            //     url: '../../sis_adquisiciones/control/Reporte/getDatosUsuario',
            //     params: {id_usuario: 0},
            //     success: function (resp) {
            //         var reg = Ext.decode(Ext.util.Format.trim(resp.responseText));
            //         this.cmbAux.setValue(reg.ROOT.datos.id_usuario);
            //         this.cmbAux.setRawValue(reg.ROOT.datos.desc_usuario);
            //         this.store.baseParams.id_usuario = reg.ROOT.datos.id_usuario;
            //         this.load({params: {start: 0, limit: this.tam_pag}});
            //     },
            //     failure: this.conexionFailure,
            //     timeout: this.timeout,
            //     scope: this
            // });

            // Ext.Ajax.request({
            //     url:'../../sis_parametros/control/Gestion/obtenerGestionByFecha',
            //     params:{fecha:new Date()},
            //     success:function(resp){
            //         var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
            //         this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
            //         this.cmbGestion.setRawValue(reg.ROOT.datos.anho);
            //         this.store.baseParams.id_gestion=reg.ROOT.datos.id_gestion;
            //         this.load({params:{start:0, limit:this.tam_pag}});
            //     },
            //     failure: this.conexionFailure,
            //     timeout:this.timeout,
            //     scope:this
            // });

            //llama al constructor de la clase padre
            Phx.vista.ConsultaForm400_500.superclass.constructor.call(this,config);
            this.init();
            //this.store.baseParams.tipo_formulario = 'si-400';
            this.cm.setHidden(17,true);
            //this.cmbGestion.on('select',this.capturarEventos, this);
            //this.load({params:{start:0, limit: 50}});



            this.addButton('btnChequeoDocumentosWf',{
                text: 'Documentos',
                grupo: [0,1,2,3],
                iconCls: 'bchecklist',
                disabled: true,
                handler: this.loadCheckDocumentosRecWf,
                tooltip: '<b>Documentos del Reclamo</b><br/>Subir los documetos requeridos en el Reclamo seleccionado.'
            });

            // this.addButton('diagrama_gantt',{
            //     grupo:[0,1,2,3,4,5],
            //     text:'Gant',
            //     iconCls: 'bgantt',
            //     disabled:true,
            //     handler:this.diagramGantt,
            //     tooltip: '<b>Diagrama Gantt de proceso macro</b>'
            // });

            /*this.addButton('form400',{
                grupo:[0,1,2,3,4,5],
                text :'Form. 400',
                iconCls : 'bballot',
                disabled: false,
                handler : this.reporteForm400,
                tooltip : '<b>Procesos Pendientes</b><br/>Reporte que muestra los procesos pendientes del formulario 400.'
            });*/
            //this.cmbAux.on('select',this.capturarFiltros, this);

            this.tbar.addField(this.cmbAux);
            this.tbar.addField(this.fecha_inicio);
            this.tbar.addField(this.fecha_fin);


            this.addButton('bspacio1', {
                text : '                        ',
                grupo: [5],
                //iconCls: 'bengine',
                disabled: true,
                hidden:false,
                //handler: this.modPac,
                //tooltip: '<b>Modificar PAC</b><br/>Permite modificar el PAC de un trámite'
            });

            this.addButton('bmodPAC', {
                text: 'PAC',
                grupo: [0,1,2,3],
                iconCls: 'bengine',
                disabled: true,
                hidden:false,
                handler: this.modPac,
                tooltip: '<b>Modificar PAC</b><br/>Permite modificar el PAC de un trámite'
            });


            this.addButton('bmodCuce', {
                text: 'CUCE',
                grupo: [0,1,2,3],
                iconCls: 'bengine',
                disabled: true,
                hidden:false,
                handler: this.modCuce,
                tooltip: '<b>Modificar CUCE</b><br/>Permite modificar el CUCE de un trámite'
            });


            this.addButton('bfecha_impresion_Form3008', {
                text: 'Fecha 3008',
                grupo: [0,1,2,3],
                iconCls: 'bcalendar',
                disabled: true,
                hidden:false,
                handler: this.modFecha3008,
                tooltip: '<b>Modificar Fecha Impresión'
            });


            this.fecha_fin.on('select',function(value){
                this.capturarEventos();
            },this);



            this.cmbAux.on('select',function(value){
               if (this.fecha_fin.getValue()!='') {
                 this.capturarEventos();
               }
            },this);


            this.crearFormCuce();
            this.crearFormPac();
            this.crearFormFecha3008();

        },

        loadCheckDocumentosRecWf: function() {
            var rec=this.sm.getSelected();
            var that=this;
            Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
                'Chequear documento del WF',
                {
                    width:'90%',
                    height:500,
                    onDestroy: function() {
                        this.fireEvent('closepanel',this);

                  			if (this.window) {
                  					this.window.destroy();
                  			}
                  			if (this.form) {
                  					this.form.destroy();
                  			}
                  			Phx.CP.destroyPage(this.idContenedor);
                        that.reload();


                  	},
                },
                rec.data,
                this.idContenedor,
                'DocumentoWf'
            )
        },

        // diagramGantt: function() {
        //     var data=this.sm.getSelected().data.id_proceso_wf;
        //     //Phx.CP.loadingShow();
        //     Ext.Ajax.request({
        //         url:'../../sis_workflow/control/ProcesoWf/diagramaGanttTramite',
        //         params:{'id_proceso_wf':data},
        //         success:this.successExport,
        //         failure: this.conexionFailure,
        //         timeout:this.timeout,
        //         scope:this
        //     });
        // },

        // cmbTipo: new Ext.form.ComboBox({
        //     name: 'origen',
        //     //fieldLabel: 'Origen',
        //     allowBlank: true,
        //     anchor: '80%',
        //     gwidth: 100,
        //     maxLength: 25,
        //     typeAhead:true,
        //     forceSelection: true,
        //     triggerAction:'all',
        //     mode:'local',
        //     store:[ 'Todos','Proceso']
        // }),

        capturarEventos: function (record) {

            this.store.baseParams.fecha_inicio=this.fecha_inicio.getValue();
            this.store.baseParams.fecha_fin=this.fecha_fin.getValue();
            this.store.baseParams.id_funcionario=this.cmbAux.getValue();
            this.load({params:{start:0, limit:50}});

            console.log('record', record);

            if (Phx.CP.getPagina('docs-COTRFORM400-east-0') != undefined) {
              //Phx.CP.getPagina('docs-COTRFORM400-east-0').cargarPagina(this.fecha_inicio.getValue(),this.fecha_fin.getValue(),this.cmbAux.getValue(),this.store.baseParams.tipo_formulario);
            }


        },


        /*Aumentando para el CUCE Ismael Valdivia 16/03/2022*/
        crearFormCuce: function () {
            var me = this;
            me.formAjustes = new Ext.form.FormPanel({
                //id: me.idContenedor + '_AJUSTES',
                margins: ' 10 10 10 10',
                items: [
                  new Ext.form.TextField({
                      name: 'cuce',
                      xtype: 'field',
                      width: 150,
                      fieldLabel: 'CUCE',
                      minLength: 22,
                      maxLength: 22,
                  }),
                  new Ext.form.TextField({
                      name: 'nro_confirmacion',
                      xtype: 'field',
                      width: 150,
                      fieldLabel: 'Nro Confirmación',
                      minLength: 7,
                      maxLength: 8,
                  }),
                  new Ext.form.DateField({
                      name: 'fecha_publicacion_cuce',
                      xtype: 'datefield',
                      width: 150,
                      fieldLabel: 'Fecha publicación'

                  }),
                  {
                      xtype: 'field',
                      name: 'id_solicitud',
                      labelSeparator: '',
                      inputType: 'hidden'
                  }
                    ],
                autoScroll: false,
                autoDestroy: true
            });

            // Definicion de la ventana que contiene al formulario
            me.windowAjustes = new Ext.Window({
                // id:this.idContenedor+'_W',
                title: 'Registrar CUCE',
                margins: ' 10 10 10 10',
                modal: true,
                width: 400,
                height: 250,
                bodyStyle: 'padding:5px;',
                buttonAlign: 'center',
                layout: 'fit',
                plain: true,
                hidden: true,
                autoScroll: false,
                maximizable: true,
                buttons: [{
                    text: 'Guardar',
                    arrowAlign: 'bottom',
                    handler: me.saveAjustes,
                    argument: {
                        'news': false
                    },
                    scope: me

                },
                    {
                        text: 'Declinar',
                        handler: me.onDeclinarAjustes,
                        scope: me
                    }],
                items: me.formAjustes,
                autoDestroy: true,
                closeAction: 'hide'
            });


        },


        crearFormPac: function () {
            var me = this;
            me.formPac = new Ext.form.FormPanel({
                //id: me.idContenedor + '_AJUSTES',
                margins: ' 10 10 10 10',
                items: [
                    {
                        name: 'pac',
                        xtype: 'field',
                        width: 150,
                        fieldLabel: 'Nro Pac'
                    },
                    {
                        name: 'fecha_pac',
                        xtype: 'datefield',
                        width: 150,
                        fieldLabel: 'Fecha PAC'

                    },
                    {
                        name: 'objeto_contratacion',
                        xtype: 'textarea',
                        width: 150,
                        fieldLabel: 'Objeto de Contratacion'
                    },
                    {
                        xtype: 'field',
                        name: 'id_solicitud',
                        labelSeparator: '',
                        inputType: 'hidden'
                    }
                    ],
                autoScroll: false,
                autoDestroy: true
            });

            // Definicion de la ventana que contiene al formulario
            me.windowAjustesPac = new Ext.Window({
                // id:this.idContenedor+'_W',
                title: 'Registrar PAC',
                margins: ' 10 10 10 10',
                modal: true,
                width: 400,
                height: 250,
                bodyStyle: 'padding:5px;',
                buttonAlign: 'center',
                layout: 'fit',
                plain: true,
                hidden: true,
                autoScroll: false,
                maximizable: true,
                buttons: [{
                    text: 'Guardar',
                    arrowAlign: 'bottom',
                    handler: me.saveAjustesPac,
                    argument: {
                        'news': false
                    },
                    scope: me

                },
                    {
                        text: 'Declinar',
                        handler: me.onDeclinarAjustesPac,
                        scope: me
                    }],
                items: me.formPac,
                autoDestroy: true,
                closeAction: 'hide'
            });


        },


        saveAjustes: function () {
            var me = this,
                d = me.sm.getSelected().data;
            console.log('llega1',d)
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_gestion_materiales/control/Solicitud/insertarCuce',
                success: me.successAjustes,
                failure: me.failureAjustes,
                params: {
                    'id_solicitud': d.id_solicitud,
                    'cuce': me.formAjustes.getForm().findField('cuce').getValue(),
                    'nro_confirmacion': me.formAjustes.getForm().findField('nro_confirmacion').getValue(),
                    'fecha_publicacion_cuce': me.formAjustes.getForm().findField('fecha_publicacion_cuce').getValue()

                },
                timeout: me.timeout,
                scope: me
            });


        },



        successAjustes: function (resp) {
            Phx.CP.loadingHide();
            this.windowAjustes.hide();
            this.reload();

        },

        failureAjustes: function (resp) {
            Phx.CP.loadingHide();
            Phx.vista.SolicitudHistorico.superclass.conexionFailure.call(this, resp);

        },
        onDeclinarAjustes: function () {
            this.windowAjustes.hide();

        },

        onDeclinarAjustesPac: function () {
            this.windowAjustesPac.hide();

        },


        successAjustesPac: function (resp) {
            Phx.CP.loadingHide();
            this.windowAjustesPac.hide();
            this.reload();

        },

        failureAjustesPac: function (resp) {
            Phx.CP.loadingHide();
            Phx.vista.SolicitudHistorico.superclass.conexionFailure.call(this, resp);

        },

        saveAjustesPac: function () {
            var me = this,
                d = me.sm.getSelected().data;
            console.log('llega1',d)
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_gestion_materiales/control/Solicitud/insertarPac',
                success: me.successAjustesPac,
                failure: me.failureAjustesPac,
                params: {
                    'id_solicitud': d.id_solicitud,
                    'pac': me.formPac.getForm().findField('pac').getValue(),
                    'fecha_pac': me.formPac.getForm().findField('fecha_pac').getValue(),
                    'objeto_contratacion': me.formPac.getForm().findField('objeto_contratacion').getValue()

                },
                timeout: me.timeout,
                scope: me
            });


        },


        modCuce: function () {
            this.windowAjustes.show();
            this.formAjustes.getForm().reset();
            var d = this.sm.getSelected().data;


            this.formAjustes.getForm().findField('cuce').show();
            this.formAjustes.getForm().findField('cuce').setValue(d.cuce);
            this.formAjustes.getForm().findField('nro_confirmacion').show();
            this.formAjustes.getForm().findField('nro_confirmacion').setValue(d.nro_confirmacion);
            this.formAjustes.getForm().findField('fecha_publicacion_cuce').show();
            this.formAjustes.getForm().findField('fecha_publicacion_cuce').setValue(d.fecha_publicacion_cuce);


        },


        modFecha3008: function () {
            this.windowFecha3008.show();
            this.formFecha3008.getForm().reset();
            var d = this.sm.getSelected().data;


            this.formFecha3008.getForm().findField('fecha_form_3008').show();
            this.formFecha3008.getForm().findField('fecha_form_3008').setValue(d.fecha_3008);


        },


        crearFormFecha3008: function () {
            var me = this;
            me.formFecha3008 = new Ext.form.FormPanel({
                //id: me.idContenedor + '_AJUSTES',
                margins: ' 10 10 10 10',
                items: [
                    {
                        name: 'fecha_form_3008',
                        xtype: 'datefield',
                        width: 150,
                        fieldLabel: 'Fecha Formulario'
                    }
                    ],
                autoScroll: false,
                autoDestroy: true
            });

            // Definicion de la ventana que contiene al formulario
            me.windowFecha3008 = new Ext.Window({
                // id:this.idContenedor+'_W',
                title: 'Fecha del Formulario 3008',
                margins: ' 10 10 10 10',
                modal: true,
                width: 400,
                height: 250,
                bodyStyle: 'padding:5px;',
                buttonAlign: 'center',
                layout: 'fit',
                plain: true,
                hidden: true,
                autoScroll: false,
                maximizable: true,
                buttons: [{
                    text: 'Guardar',
                    arrowAlign: 'bottom',
                    handler: me.saveForm3008,
                    argument: {
                        'news': false
                    },
                    scope: me

                },
                    {
                        text: 'Declinar',
                        handler: me.onDeclinarForm3008,
                        scope: me
                    }],
                items: me.formFecha3008,
                autoDestroy: true,
                closeAction: 'hide'
            });


        },

        saveForm3008: function () {
            var me = this,
                d = me.sm.getSelected().data;
            tipo_formulario = this.store.baseParams.tipo_formulario;
            id_funcionario = this.store.baseParams.id_funcionario;
            fecha_inicio = this.store.baseParams.fecha_inicio;
            fecha_fin = this.store.baseParams.fecha_fin;

            //console.log('llega1',this.store.baseParams.tipo_formulario)
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url: '../../sis_gestion_materiales/control/Solicitud/insertarFecha3008',
                success: me.successform3008,
                failure: me.failureform3008,
                params: {
                    //'id_solicitud': d.id_solicitud,
                    'tipo_formulario': tipo_formulario,
                    'id_funcionario':id_funcionario,
                    'fecha_inicio':fecha_inicio,
                    'fecha_fin':fecha_fin,
                    'fecha_form_3008': me.formFecha3008.getForm().findField('fecha_form_3008').getValue()
                },
                timeout: me.timeout,
                scope: me
            });


        },

        onDeclinarForm3008: function () {
            this.windowFecha3008.hide();
        },


        successform3008: function (resp) {
            Phx.CP.loadingHide();
            this.windowFecha3008.hide();
            this.reload();

        },

        failureform3008: function (resp) {
            Phx.CP.loadingHide();
            Phx.vista.SolicitudHistorico.superclass.conexionFailure.call(this, resp);

        },

        modPac: function () {
            this.windowAjustesPac.show();
            this.formPac.getForm().reset();
            var d = this.sm.getSelected().data;


            this.formPac.getForm().findField('pac').show();
            this.formPac.getForm().findField('pac').setValue(d.nro_pac);
            this.formPac.getForm().findField('fecha_pac').show();
            this.formPac.getForm().findField('fecha_pac').setValue(d.fecha_pac);
            this.formPac.getForm().findField('objeto_contratacion').show();
            this.formPac.getForm().findField('objeto_contratacion').setValue(d.objeto_contratacion);


        },
        /****************************************************/

        Atributos:[
            {
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_proceso_wf'
                },
                type:'Field',
                form:false
            },

            /*Aumetnando el estado del plan de pago*/
            {
                config:{
                    name: 'estado',
                    fieldLabel: 'Ultima Cuota Plan de Pago',
                    allowBlank: true,
                    anchor: '50%',
                    gwidth: 150,
                    maxLength:20,
                    readOnly:true,
                    renderer: function(value,p,record) {
                        console.log("Aqui llega el estado de la couta",value);
                        if (value == '') {
                          return String.format('<b><font>SIN PLAN DE PAGOS</font></b>');
                        } else if (value == 'anulado') {
                          return String.format('<b><font color="red">ANULADO</font></b>');
                        } else {
                          return String.format('<b><font color="green">{0}</font></b>', value);
                        }

                        //;
                    }
                },
                type:'TextField',
                //bottom_filter: true,
                filters:{pfiltro:'dat.estado',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },
            /***************************************/


            {
                config:{
                    name: 'nro_tramite',
                    fieldLabel: 'Nro. Tramite',
                    allowBlank: true,
                    anchor: '50%',
                    gwidth: 150,
                    maxLength:20,
                    readOnly:true,
                    // renderer: function(value,p,record) {
                    //     return String.format('<b><font color="green">{0}</font></b>', value);
                    // }
                },
                type:'TextField',
                bottom_filter: true,
                filters:{pfiltro:'dat.nro_tramite',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },

            {
                config: {
                    name: 'fecha_po',
                    fieldLabel: 'Fecha PO/EO/REP',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'nro_po',
                    fieldLabel: 'Nro. PO/EO/REP',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'rotulo_comercial',
                    fieldLabel: 'Proveedor Adjudicado',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 250,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'monto_total_adjudicado',
                    fieldLabel: 'Total Adjudicado M/E',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    galign:'right',
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'fecha_publicacion_cuce',
                    fieldLabel: 'Fecha CUCE',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 80,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'cuce',
                    fieldLabel: 'Nro. CUCE',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'nro_confirmacion',
                    fieldLabel: 'Nro. Confirmación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'fecha_pac',
                    fieldLabel: 'Fecha PAC',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 80,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'nro_pac',
                    fieldLabel: 'Nro. PAC',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'objeto_contratacion',
                    fieldLabel: 'Objeto de Contratación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 250,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'fecha_3008',
                    fieldLabel: 'Fecha 3008',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 80,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'encargado_abastecimiento',
                    fieldLabel: 'Técnico Abastecimiento',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'encargado_adquicisiones',
                    fieldLabel: 'Técnico Administrativo',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config:{
                    name: 'chequeado',
                    fieldLabel: 'Formulario 400',
                    allowBlank: true,
                    anchor: '50%',
                    height: 80,
                    gwidth: 230,
                    maxLength:100,
                    renderer:function (value, p, record){
                        if(record.data.chequeado == 'si')
                            return String.format('{0}', '<div style="text-align:center;color: green; font-size:12px;"><b>TIENE FORMULARIO 400  <i style="font-size:20px;" class="fa fa-download" aria-hidden="true"></i></b></div>');
                        else
                            return String.format('{0}', '<div style="text-align:center;color: RED; font-size:12px;"><b>NO TIENE FORMULARIO 400  <i style="font-size:20px;" class="fa fa-upload" aria-hidden="true"></i></b></div>');
                    }
                },
                type:'TextField',
                filters:{pfiltro:'chequeado',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },

            {
                config:{
                    name: 'chequeado',
                    fieldLabel: 'Formulario 500',
                    allowBlank: true,
                    anchor: '50%',
                    height: 80,
                    gwidth: 230,
                    maxLength:100,
                    renderer:function (value, p, record){
                        if(record.data.chequeado == 'si')
                            return String.format('{0}', '<div style="text-align:center;color: green; font-size:12px;"><b>TIENE FORMULARIO 500  <i style="font-size:20px;" class="fa fa-download" aria-hidden="true"></i></b></div>');
                        else
                            return String.format('{0}', '<div style="text-align:center;color: RED; font-size:12px;"><b>NO TIENE FORMULARIO 500  <i style="font-size:20px;" class="fa fa-upload" aria-hidden="true"></i></b></div>');
                    }
                },
                type:'TextField',
                filters:{pfiltro:'chequeado',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },


            {
                config: {
                    name: 'fecha_asignado',
                    fieldLabel: 'Fecha Asignación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                },
                type: 'TextField',
                id_grupo: 0,
                grid: true,
                form: true
            },


        ],
        tam_pag:50,
        title:'ConsultaForm400_500',
        id_store:'id_proceso_compra',
        fields: [

            {name:'nro_tramite', type: 'string'},
            {name:'fecha_po', type: 'string'},
            {name:'nro_po', type: 'string'},
            {name:'chequeado', type: 'string'},
            {name:'id_solicitud', type: 'numeric'},
            {name:'id_proceso_wf', type: 'numeric'},
            {name:'id_encargado_abastecimiento', type: 'numeric'},
            {name:'encargado_abastecimiento', type: 'string'},
            {name:'id_encargado_adquicisiones', type: 'numeric'},
            {name:'encargado_adquicisiones', type: 'string'},
            {name:'rotulo_comercial', type: 'string'},
            {name:'monto_total_adjudicado', type: 'numeric'},

            {name:'fecha_publicacion_cuce', type: 'string'},
            {name:'cuce', type: 'string'},
            {name:'nro_confirmacion', type: 'string'},
            {name:'fecha_pac', type: 'string'},
            {name:'nro_pac', type: 'string'},
            {name:'objeto_contratacion', type: 'string'},
            {name:'fecha_3008', type: 'string'},
            {name:'fecha_asignado', type: 'string'},
            {name:'estado', type: 'string'},

        ],
        sortInfo:{
            field: 'fecha_po',
            direction: 'ASC'
        },
        bsave:false,
        btest: false,
        bgantt:true,
        preparaMenu: function(n)
        {	var rec = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.ConsultaForm400_500.superclass.preparaMenu.call(this,n);
            //this.getBoton('diagrama_gantt').enable();

            this.getBoton('btnChequeoDocumentosWf').setDisabled(false);
            this.getBoton('bmodPAC').setDisabled(false);
            this.getBoton('bmodCuce').setDisabled(false);
            this.getBoton('bfecha_impresion_Form3008').setDisabled(false);

        },

        liberaMenu:function(){
            var tb = Phx.vista.ConsultaForm400_500.superclass.liberaMenu.call(this);
            if(tb){
              this.getBoton('btnChequeoDocumentosWf').setDisabled(true);
              this.getBoton('bmodPAC').setDisabled(true);
              this.getBoton('bmodCuce').setDisabled(true);
              this.getBoton('bfecha_impresion_Form3008').setDisabled(true);
              //  this.getBoton('diagrama_gantt').disable();

            }
            return tb
        },

        fecha_inicio : new Ext.form.DateField({
            name: 'fecha_inicio',
            grupo: [0,1,2,3,4,5],
            fieldLabel: 'Fecha Inicio',
            emptyText:'Fecha Inicio',
            anchor: '60%',
            gwidth: 100,
            format: 'd/m/Y'
        }),

        fecha_fin : new Ext.form.DateField({
            name: 'fecha_fin',
            grupo: [0,1,2,3,4,5],
            fieldLabel: 'Fecha Final',
            emptyText:'Fecha Final',
            anchor: '60%',
            gwidth: 100,
            format: 'd/m/Y'
        }),



        cmbAux :new Ext.form.ComboBox({
            name: 'id_funcionario',
            grupo: [0,1,2,3,4,5],
            hiddenName: 'id_funcionario',
            fieldLabel: 'Encargado',
            listWidth:280,
            allowBlank: true,
            store:new Ext.data.JsonStore(
                {
                    url:    '../../sis_gestion_materiales/control/Solicitud/listarFuncionariosEncargados',
                    id: 'id_funcionario',
                    root:'datos',
                    sortInfo:{
                        field:'id_funcionario',
                        direction:'ASC'
                    },
                    totalProperty:'total',
                    fields: ['id_funcionario','desc_funcionario'],
                    // turn on remote sorting
                    remoteSort: true,
                    //baseParams:{par_filtro:'person.nombre_completo1', id_depto:2}
                }),
            valueField: 'id_funcionario',
            displayField: 'desc_funcionario',
            forceSelection:true,
            typeAhead: false,
            triggerAction: 'all',
            lazyRender:true,
            mode:'remote',
            pageSize:50,
            queryDelay:500,
            width:210,
            gwidth:220,
            minChars:2,
            tpl: '<tpl for="."><div class="x-combo-list-item"><p><b>{desc_funcionario}</b></p></div></tpl>'

        }),

    });
</script>
