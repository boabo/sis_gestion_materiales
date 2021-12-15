<?php
/**
 *@package pXP
 *@file    FormCotizacion.php
 *@author  Ismael Valdivia
 *@date    19/03/2020
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.FormCotizacion=Ext.extend(Phx.frmInterfaz,{

        ActSave:'../../sis_gestion_materiales/control/Cotizacion/insertarCotizacionCompleta',
        tam_pag: 10,
        layout: 'fit',
        autoScroll: false,
        breset: false,
        constructor:function(config)
        {
            this.addEvents('beforesave');
            this.addEvents('successsave');
            this.buildComponentesDetalle();
            this.buildDetailGrid();
            this.buildGrupos();
            Phx.vista.FormCotizacion.superclass.constructor.call(this,config);
            this.init();
            this.iniciarEventos();
            this.recuperarDetalleCotizacion();
        },
        buildComponentesDetalle: function () {

            this.detCmp =
                     {
                    'nro_parte_cot': new Ext.form.TextField({
                        name: 'nro_parte_cot',
                        msgTarget: 'title',
                        fieldLabel: 'Nro. Parte',
                        allowBlank: false,
                        width: 400,
                        disabled:true,
                        maxLength:50
                    }),
                    'nro_parte_alterno_cot': new Ext.form.TextField({
                        name: 'nro_parte_alterno_cot',
                        msgTarget: 'title',
                        fieldLabel: 'Nro. Parte Alterno',
                        allowBlank: true,
                        width: 400,
                        disabled:true,
                        maxLength:50
                    }),

                    'referencia_cot': new Ext.form.TextField({
                        name: 'referencia_cot',
                        msgTarget: 'title',
                        fieldLabel: 'Referencia',
                        allowBlank: true,
                        width: 400,
                        maxLength:500
                    }),

                    'serial': new Ext.form.TextField({
                        name: 'referencia_cot',
                        msgTarget: 'title',
                        fieldLabel: 'Serial',
                        allowBlank: true,
                        width: 400,
                        maxLength:500
                    }),

                    'descripcion_cot': new Ext.form.TextArea({
                        name: 'descripcion_cot',
                        msgTarget: 'title',
                        fieldLabel: 'Descripcion',
                        allowBlank: false,
                        width: 400,
                        maxLength:5000
                    }),

                     'explicacion_detallada_part_cot': new Ext.form.TextField({
                         name: 'explicacion_detallada_part_cot',
                         msgTarget: 'title',
                         fieldLabel: 'P/N Cotización',
                         allowBlank: false,
                         width: 400,
                         maxLength:5000
                     }),

                    // 'tipo_cot': new Ext.form.ComboBox({
                    //
                    //     name:'tipo_cot',
                    //     fieldLabel:'Tipo ',
                    //     allowBlank:false,
                    //     emptyText:'Elija una opción...',
                    //     typeAhead: true,
                    //     triggerAction: 'all',
                    //     lazyRender: true,
                    //     lazyRender:true,
                    //     mode: 'local',
                    //     width: 400,
                    //     store:['Consumible','Rotable','Herramienta','Otros Cargos'],
                    //     maxLength: 100
                    // }),

                    'tipo_cot': new Ext.form.ComboBox({
                         name: 'tipo_cot',
                         fieldLabel: 'Tipo',
                         allowBlank: true,
                         emptyText: 'Elija una opción...',
                         store : new Ext.data.JsonStore({
            							 url : '../../sis_parametros/control/Catalogo/listarCatalogoCombo',
            							 id : 'id_catalogo',
            							 root : 'datos',
            							 sortInfo : {
            								 field : 'codigo',
            								 direction : 'ASC'
            							 },
            							 totalProperty : 'total',
            							 fields: ['codigo','descripcion'],
            							 remoteSort : true,
            							 baseParams:{
                             par_filtro: 'cat.descripcion',
            								cod_subsistema:'MAT',
            								catalogo_tipo:'tdetalle_sol'
            							},
            						 }),
                         valueField : 'descripcion',
            						 displayField : 'descripcion',
            						 gdisplayField : 'descripcion',
            						 hiddenName : 'tipo',
            						 forceSelection : true,
            						 typeAhead : false,
            						 tpl: new Ext.XTemplate([
            								 '<tpl for=".">',
            								 '<div class="x-combo-list-item">',
            								 '<p><b><span style="color: black; height:15px;">{descripcion}</span></b></p>',
            								 '</div></tpl>'
            						 ]),
                         triggerAction: 'all',
                         listWidth:'450',
                         lazyRender: true,
                         resizable:true,
                         mode: 'remote',
                         pageSize: 100,
                         queryDelay: 100,
                         width: 200,
                         gwidth: 200,
                         minChars: 2,
                         renderer : function(value, p, record) {
                             return String.format('{0}', record.data['codigo']);
                         }
                     }),

                    'codigo': new Ext.form.TextField({
                        name: 'codigo',
                        msgTarget: 'title',
                        fieldLabel: 'Unidad Medida',
                        allowBlank: false,
                        width: 400,
                        maxLength:5000
                    }),

                    'cantidad_det': new Ext.form.NumberField({
                        name: 'cantidad_det',
                        msgTarget: 'title',
                        fieldLabel: 'Cantidad Total',
                        allowBlank: false,
                        allowDecimals: false,
                        enableKeyEvents: true,
                        maxLength:1000
                    }),
                    // 'cd': new Ext.form.ComboBox({
                    //     name: 'cd',
                    //     fieldLabel: 'CD',
                    //     allowBlank: false,
                    //     emptyText: 'U/M..',
                    //     store:new Ext.data.ArrayStore({
                    //         fields: ['ID', 'valor'],
                    //         data :	[
                    //             ['1','AR'],
                    //             ['2','OH'],
                    //             ['3','EXC'],
                    //             ['4','NEW'],
                    //             ['5','NE'],
                    //             ['6','NSV'],
                    //             ['7','SVC'],
                    //             ['8','SCR'],
                    //             ['9','BER'],
                    //             ['10','RP'],
                    //             ['11','FN'],
                    //             ['12','RTC'],
                    //             ['13','SV'],
                    //             ['14','INSP'],
                    //             ['15','AS IS'],
                    //             ['16','NS'],
                    //             ['17','I/T']
                    //         ]
                    //     }),
                    //     valueField: 'valor',
                    //     displayField: 'valor',
                    //     typeAhead: true,
                    //     triggerAction: 'all',
                    //     mode: 'local',
                    //     width: 400,
                    //     gwidth: 100,
                    //     minChars: 2,
                    // }),
                    'cd': new Ext.form.ComboBox({
                         name: 'cd',
                         fieldLabel: 'CD',
                         allowBlank: true,
                         emptyText: 'U/M..',
                         store : new Ext.data.JsonStore({
                   				 url : '../../sis_parametros/control/Catalogo/listarCatalogoCombo',
                   				 id : 'id_catalogo',
                   				 root : 'datos',
                   				 sortInfo : {
                   					 field : 'codigo',
                   					 direction : 'ASC'
                   				 },
                   				 totalProperty : 'total',
                   				 fields: ['codigo','descripcion'],
                   				 remoteSort : true,
                           baseParams:{
                             par_filtro: 'cat.descripcion',
                   					cod_subsistema:'MAT',
                   					catalogo_tipo:'tsolicitud'
                   				},
                   			 }),
                         valueField: 'codigo',
                         displayField: 'codigo',
                         gdisplayField: 'codigo',
                         tpl: new Ext.XTemplate([
                             '<tpl for=".">',
                             '<div class="x-combo-list-item">',
                             '<p><b>Codigo: <span style="color: red;">{codigo}</span></b></p>',
                             '<p><b>Descripción:</b> <span style="color: green;">{descripcion}</span></p>',
                             '</div></tpl>'
                         ]),
                         hiddenName: 'codigo',
                         forceSelection: true,
                         typeAhead: false,
                         triggerAction: 'all',
                         listWidth:'450',
                         lazyRender: true,
                         resizable:true,
                         mode: 'remote',
                         pageSize: 100,
                         queryDelay: 100,
                         width: 200,
                         gwidth: 200,
                         minChars: 2,
                         renderer : function(value, p, record) {
                             return String.format('{0}', record.data['codigo']);
                         }
                     }),
                    'precio_unitario': new Ext.form.NumberField({
                        name: 'precio_unitario',
                        msgTarget: 'title',
                        fieldLabel: 'Precio Unitario',
                        allowBlank: false,
                        allowDecimals: true,
                        enableKeyEvents: true,
                        decimalPrecision : 2,
                        maxLength:1245186
                    }),
                    'precio_unitario_mb': new Ext.form.NumberField({
                        name: 'precio_unitario_mb',
                        msgTarget: 'title',
                        fieldLabel: 'Precio Total',
                        disabled:true,
                        allowBlank: true,
                        allowDecimals: true,
                        decimalPrecision : 2,
                        maxLength:1245186
                    }),
                   'id_unidad_medida': new Ext.form.ComboBox({
                        name: 'id_unidad_medida',
                        fieldLabel: 'Unidad de medida',
                        allowBlank: true,
                        emptyText: 'U/M..',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_gestion_materiales/control/DetalleSol/unidadMedia',
                            id: 'id_unidad_medida',
                            root: 'datos',
                            sortInfo: {
                                field: 'codigo',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_unidad_medida','codigo','descripcion','tipo_unidad_medida'],
                            remoteSort: true,
                            baseParams: {par_filtro: 'un.codigo# un.descripcion'}
                        }),
                        valueField: 'id_unidad_medida',
                        displayField: 'codigo',
                        gdisplayField: 'codigo',
                        tpl:'<tpl for="."><div class="x-combo-list-item"><p>{codigo}</p><p style="color: blue">{descripcion}</p></div></tpl>',
                        hiddenName: 'id_unidad_medida',
                        forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        listWidth:95,
                        lazyRender: true,
                        resizable:true,
                        mode: 'remote',
                        pageSize: 100,
                        queryDelay: 100,
                        width: 200,
                        gwidth: 200,
                        minChars: 2

                    }),
                    'id_day_week': new Ext.form.ComboBox({
                        name: 'id_day_week',
                        fieldLabel: 'Tiempo Entrega',
                        allowBlank: true,
                        emptyText: 'Elija una opción...',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_gestion_materiales/control/CotizacionDetalle/listarDay_week',
                            id: 'id_day',
                            root: 'datos',
                            sortInfo: {
                                field: 'id_day',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_day','codigo_tipo'],
                            remoteSort: true,
                            baseParams: {par_filtro: 'da.codigo_tipo'}
                        }),
                        valueField: 'id_day',
                        displayField: 'codigo_tipo',
                        hiddenName: 'id_day_week',
                        forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        listWidth:95,
                        lazyRender: true,
                        resizable:true,
                        mode: 'remote',
                        pageSize: 50,
                        queryDelay: 100,
                        width: 400,
                        gwidth: 10,
                        minChars: 2
                    })
                }
        },


        HazmatPredefinido : function () {
          if (this.data.objPadre.tipoTramite != 'GR') {
            this.detCmp.tipo_cot.store.load({params:{start:0,limit:50},
                   callback : function (r) {
                      /*Cuando se Agrege el detalle de la cotizacion poner Hazmat*/
                      for (var i = 0; i < r.length; i++) {
                        if (r[i].data.codigo == 39) {
                              this.detCmp.tipo_cot.setValue(r[i].data.descripcion);
                              this.detCmp.tipo_cot.fireEvent('select', this.detCmp.tipo_cot,r[i]);
                            }
                      }
                      /***********************************************************/
                    }, scope : this
                });
                /*Aqui aumentamos para agregar HAZMAT en Cotización*/
                      this.detCmp.id_day_week.allowBlank = true;
                      this.detCmp.id_unidad_medida.allowBlank = true;
                      this.detCmp.cd.allowBlank = true;
                      this.detCmp.nro_parte_cot.setValue('HAZMAT');
                      this.detCmp.nro_parte_alterno_cot.setValue('HAZMAT');
                      this.detCmp.referencia_cot.setValue('HAZMAT');
                      this.detCmp.descripcion_cot.setValue('HAZMAT');
                      this.detCmp.explicacion_detallada_part_cot.setValue('HAZMAT');
                      this.detCmp.cantidad_det.setValue(1);

                      this.detCmp.nro_parte_cot.setDisabled(true);
                      this.detCmp.nro_parte_alterno_cot.setDisabled(true);
                      this.detCmp.referencia_cot.setDisabled(true);
                      this.detCmp.descripcion_cot.setDisabled(false);
                      this.detCmp.explicacion_detallada_part_cot.setDisabled(true);
                      this.detCmp.id_unidad_medida.setDisabled(true);
                      this.detCmp.cd.setDisabled(true);
                      this.detCmp.id_day_week.setDisabled(true);
                /***************************************************/
          } else {
            this.detCmp.nro_parte_cot.setDisabled(false);
            this.detCmp.nro_parte_alterno_cot.setDisabled(false);
          }

        },

        iniciarEventos : function () {


          /*Aqui obtenemos la fecha referencial para todo el detalle (Ismael Valdivia 29/04/2020)*/
            //var dateTo = moment(new Date()).format('YYYY-MM-DD');--aqui recuperamos la fecha actual
            //var dateFrom = moment().subtract(7,'d').format('YYYY-MM-DD'); --Aqui sutraemos los dias
            var fechaAumentada = moment().add(30,'d').format('YYYY-MM-DD'); //Aumentamos los 30 dias
            //this.Cmp.fecha_entrega.setValue(fechaAumentada);

          /**********************************************************/

          /*Aumentando para que el campo sea dinamico el campo serial y referencia (Ismael Valdivia 16/03/2020)*/
          var tipo_tramite = Phx.CP.getPagina(this.idContenedorPadre).maestro.tipoTramite;
          if (tipo_tramite == 'GR') {

            //var fecha_po = new Date(Phx.CP.getPagina(this.idContenedorPadre).maestro.fecha_po);
            //fecha_po.setDate(fecha_po.getDate() + 30);
            //var fecha_formateada = fecha_po.format('d/m/Y');
            //console.log("aqui llega data para enviar",Phx.CP.getPagina(this.idContenedorPadre).maestro);
            var formaPago = Phx.CP.getPagina(this.idContenedorPadre).maestro.id_forma_pago;
            var condiconEntrega = Phx.CP.getPagina(this.idContenedorPadre).maestro.id_condicion_entrega;
            var codigoFormaPago = Phx.CP.getPagina(this.idContenedorPadre).maestro.codigo_forma_pago;
            var codigoCondiconEntrega = Phx.CP.getPagina(this.idContenedorPadre).maestro.codigo_condicion_entrega;
            var tipoEvalucion = Phx.CP.getPagina(this.idContenedorPadre).maestro.tipo_evaluacion;

            this.megrid.colModel.setHidden(3,true);
            this.megrid.colModel.setHidden(4,false);
            //this.megrid.colModel.setHidden(13,true);
            //this.detCmp.id_day_week.allowBlank = true;
            this.ocultarComponente(this.Cmp.recomendacion);
            this.ocultarComponente(this.Cmp.pie_pag);
            this.ocultarComponente(this.Cmp.tipo_evaluacion);
            this.ocultarComponente(this.Cmp.id_modo_envio);
            this.ocultarComponente(this.Cmp.id_puntos_entrega);
            this.ocultarComponente(this.Cmp.id_tipo_transaccion);
            this.ocultarComponente(this.Cmp.id_orden_destino);
            this.Cmp.recomendacion.allowBlank = true;
            this.Cmp.pie_pag.allowBlank = true;

            this.ocultarComponente(this.Cmp.nro_cotizacion);
            this.Cmp.nro_cotizacion.allowBlank = true;
            /*Aqui Recuperamos los datos*/
            this.Cmp.id_condicion_entrega.store.load({params:{start:0,limit:50},
               callback : function (r) {
                     this.Cmp.id_condicion_entrega.setValue(condiconEntrega);
                     this.Cmp.id_condicion_entrega.fireEvent('select',this.Cmp.id_condicion_entrega,condiconEntrega);

                }, scope : this
            });
            this.Cmp.id_forma_pago.store.load({params:{start:0,limit:50},
               callback : function (r) {
                     this.Cmp.id_forma_pago.setValue(formaPago);
                     this.Cmp.id_forma_pago.fireEvent('select',this.Cmp.id_forma_pago,formaPago);

                }, scope : this
            });

            this.Cmp.codigo_condicion_entrega.setValue(codigoCondiconEntrega);
            this.Cmp.codigo_forma_pago.setValue(codigoFormaPago);
            this.Cmp.tipo_evaluacion.setValue(tipoEvalucion);
            /****************************/


            this.Cmp.adjudicado.setValue('si');
            this.Cmp.adjudicado.fireEvent('select', this.Cmp.adjudicado,'si',0);
                if (this.Cmp.adjudicado.getValue() == 'si') {
                  this.Cmp.id_condicion_entrega.allowBlank = false;
                  this.Cmp.id_forma_pago.allowBlank = false;
                  this.Cmp.id_modo_envio.allowBlank = false;
                  this.Cmp.id_puntos_entrega.allowBlank = false;
                  this.Cmp.id_tipo_transaccion.allowBlank = false;
                  this.Cmp.id_orden_destino.allowBlank = false;
                  this.Cmp.id_proveedor_contacto.allowBlank = false;
                  Ext.getCmp('grupo_alkym').show();

                  //this.Cmp.fecha_entrega.allowBlank = false;
                  //this.Cmp.tipo_evaluacion.allowBlank = false;
                  //this.Cmp.fecha_entrega.setValue(fecha_formateada);
                  //this.mostrarComponente(this.Cmp.fecha_entrega);
                  this.mostrarComponente(this.Cmp.tipo_evaluacion);
                  this.mostrarComponente(this.Cmp.id_proveedor_contacto);
                } else {
                  this.ocultarComponente(this.Cmp.tipo_evaluacion);
                  this.ocultarComponente(this.Cmp.id_proveedor_contacto);
                  this.Cmp.id_proveedor_contacto.allowBlank = true;
                }
                this.Cmp.id_condicion_entrega.setDisabled(true);
                this.Cmp.id_forma_pago.setDisabled(true);
                this.Cmp.tipo_evaluacion.setDisabled(true);
                this.ocultarComponente(this.Cmp.id_proveedor_contacto);
                this.Cmp.id_proveedor_contacto.allowBlank = true;

          } else {
            this.megrid.colModel.setHidden(3,false);
            this.megrid.colModel.setHidden(13,false);
            this.detCmp.id_day_week.allowBlank = false;
            this.megrid.colModel.setHidden(4,true);
            //this.ocultarComponente(this.Cmp.fecha_entrega);
            //this.Cmp.fecha_entrega.allowBlank = true;
          }
          /*Aqui para el listado de los contactos*/
          this.Cmp.id_proveedor.on('select', function (c,r,i) {
            this.Cmp.id_proveedor_contacto.store.baseParams.id_proveedor = this.Cmp.id_proveedor.getValue();
          },this);
          /***************************************/




          /*Aqui ponemos la condicion para que se esconda (Ismael Valdivia 05/04/2020)*/
          this.Cmp.adjudicado.on('select', function (c,r,i) {
            if (c.getValue() == 'si') {

              if (tipo_tramite == 'GR') {
                /*Aqui Recuperamos los datos*/
                this.Cmp.id_condicion_entrega.store.load({params:{start:0,limit:50},
                   callback : function (r) {
                         this.Cmp.id_condicion_entrega.setValue(condiconEntrega);
                         this.Cmp.id_condicion_entrega.fireEvent('select',this.Cmp.id_condicion_entrega,condiconEntrega);

                    }, scope : this
                });
                this.Cmp.id_forma_pago.store.load({params:{start:0,limit:50},
                   callback : function (r) {
                         this.Cmp.id_forma_pago.setValue(formaPago);
                         this.Cmp.id_forma_pago.fireEvent('select',this.Cmp.id_forma_pago,formaPago);

                    }, scope : this
                });

                this.Cmp.codigo_condicion_entrega.setValue(codigoCondiconEntrega);
                this.Cmp.codigo_forma_pago.setValue(codigoFormaPago);
                this.Cmp.tipo_evaluacion.setValue(tipoEvalucion);

                this.Cmp.id_condicion_entrega.allowBlank = false;
                this.Cmp.id_forma_pago.allowBlank = false;
                this.Cmp.id_modo_envio.allowBlank = false;
                this.Cmp.id_puntos_entrega.allowBlank = false;
                this.Cmp.id_tipo_transaccion.allowBlank = false;
                this.Cmp.id_orden_destino.allowBlank = false;
                Ext.getCmp('grupo_alkym').show();

                //this.Cmp.fecha_entrega.allowBlank = false;
                //this.Cmp.tipo_evaluacion.allowBlank = false;
                //this.Cmp.fecha_entrega.setValue(fecha_formateada);
                //this.mostrarComponente(this.Cmp.fecha_entrega);
                this.mostrarComponente(this.Cmp.tipo_evaluacion);

                this.Cmp.id_condicion_entrega.setDisabled(true);
                this.Cmp.id_forma_pago.setDisabled(true);
                this.Cmp.tipo_evaluacion.setDisabled(true);
                this.ocultarComponente(this.Cmp.id_proveedor_contacto);
                this.Cmp.id_proveedor_contacto.allowBlank = true;
                /****************************/
              } else {

                /*Aqui condicionando para los manuales*/
                if (tipo_tramite != 'GR' /*&& Phx.CP.getPagina(this.idContenedorPadre).maestro.origen_solicitud == 'control_mantenimiento'*/) {
                  this.Cmp.id_condicion_entrega.allowBlank = false;
                  this.Cmp.id_forma_pago.allowBlank = false;
                  this.Cmp.id_modo_envio.allowBlank = false;
                  this.Cmp.id_puntos_entrega.allowBlank = false;
                  this.Cmp.id_tipo_transaccion.allowBlank = false;
                  this.Cmp.id_orden_destino.allowBlank = false;
                  Ext.getCmp('grupo_alkym').show();

                  Ext.Ajax.request({
                      url:'../../sis_gestion_materiales/control/Solicitud/getDatosAlkym',
                      params:{id_solicitud:this.data.objPadre.id_solicitud},
                      success: function(resp){
                          var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                          this.Cmp.codigo_condicion_entrega.setValue(reg.ROOT.datos.codigo_condicion_entrega_alkym);
                          this.Cmp.codigo_forma_pago.setValue(reg.ROOT.datos.codigo_forma_pago_alkym);
                          this.Cmp.codigo_modo_envio.setValue(reg.ROOT.datos.codigo_modo_envio_alkym);
                          this.Cmp.codigo_puntos_entrega.setValue(reg.ROOT.datos.codigo_puntos_entrega_alkym);
                          this.Cmp.codigo_tipo_transaccion.setValue(reg.ROOT.datos.codigo_tipo_transaccion_alkym);
                          this.Cmp.codigo_orden_destino.setValue(reg.ROOT.datos.codigo_orden_destino_alkym);

                          this.Cmp.id_condicion_entrega.store.load({params:{start:0,limit:50},
                             callback : function (r) {
                               if (reg.ROOT.datos.id_condicion_entrega_alkym != '' && reg.ROOT.datos.id_condicion_entrega_alkym != null) {
                                 this.Cmp.id_condicion_entrega.setValue(reg.ROOT.datos.id_condicion_entrega_alkym);
                                 this.Cmp.id_condicion_entrega.fireEvent('select',this.Cmp.id_condicion_entrega,this.Cmp.id_condicion_entrega.getValue());
                               }
                              }, scope : this
                          });
                          this.Cmp.id_forma_pago.store.load({params:{start:0,limit:50},
                             callback : function (r) {
                               if (reg.ROOT.datos.id_forma_pago_alkym != '' && reg.ROOT.datos.id_forma_pago_alkym != null) {
                                   this.Cmp.id_forma_pago.setValue(reg.ROOT.datos.id_forma_pago_alkym);
                                   this.Cmp.id_forma_pago.fireEvent('select',this.Cmp.id_forma_pago,this.Cmp.id_forma_pago.getValue());
                                }
                              }, scope : this
                          });
                          this.Cmp.id_modo_envio.store.load({params:{start:0,limit:50},
                             callback : function (r) {
                               if (reg.ROOT.datos.id_modo_envio_alkym != '' && reg.ROOT.datos.id_modo_envio_alkym != null) {
                                   this.Cmp.id_modo_envio.setValue(reg.ROOT.datos.id_modo_envio_alkym);
                                   this.Cmp.id_modo_envio.fireEvent('select',this.Cmp.id_modo_envio,this.Cmp.id_modo_envio.getValue());
                                }
                              }, scope : this
                          });
                          this.Cmp.id_puntos_entrega.store.load({params:{start:0,limit:50},
                             callback : function (r) {
                               if (reg.ROOT.datos.id_puntos_entrega_alkym != '' && reg.ROOT.datos.id_puntos_entrega_alkym != null) {
                                   this.Cmp.id_puntos_entrega.setValue(reg.ROOT.datos.id_puntos_entrega_alkym);
                                   this.Cmp.id_puntos_entrega.fireEvent('select',this.Cmp.id_puntos_entrega,this.Cmp.id_puntos_entrega.getValue());
                               }
                              }, scope : this
                          });
                          this.Cmp.id_tipo_transaccion.store.load({params:{start:0,limit:50},
                             callback : function (r) {
                               if (reg.ROOT.datos.id_tipo_transaccion_alkym != '' && reg.ROOT.datos.id_tipo_transaccion_alkym != null) {
                                   this.Cmp.id_tipo_transaccion.setValue(reg.ROOT.datos.id_tipo_transaccion_alkym);
                                   this.Cmp.id_tipo_transaccion.fireEvent('select',this.Cmp.id_tipo_transaccion,this.Cmp.id_tipo_transaccion.getValue());
                                } else {
                                  //this.Cmp.id_tipo_transaccion.store.load({params:{start:0,limit:50},
                                     //callback : function (r) {
                                          this.Cmp.id_tipo_transaccion.setValue('1');
                                          this.Cmp.id_tipo_transaccion.fireEvent('select',this.Cmp.id_tipo_transaccion, this.Cmp.id_tipo_transaccion.store.getById('1'));
                                      //}, scope : this
                                  //});
                                }
                              }, scope : this
                          });
                          this.Cmp.id_orden_destino.store.load({params:{start:0,limit:50},
                             callback : function (r) {
                               if (reg.ROOT.datos.id_orden_destino_alkym != '' && reg.ROOT.datos.id_orden_destino_alkym != null) {
                                   this.Cmp.id_orden_destino.setValue(reg.ROOT.datos.id_orden_destino_alkym);
                                   this.Cmp.id_orden_destino.fireEvent('select',this.Cmp.id_orden_destino,this.Cmp.id_orden_destino.getValue());
                                }
                              }, scope : this
                          });
                      },
                      failure: this.conexionFailure,
                      timeout:this.timeout,
                      scope:this
                  });

                  //this.Cmp.fecha_entrega.allowBlank = false;
                  this.Cmp.tipo_evaluacion.allowBlank = false;
                  //this.Cmp.fecha_entrega.setValue(fecha_formateada);
                  //this.ocultarComponente(this.Cmp.fecha_entrega);
                  this.mostrarComponente(this.Cmp.tipo_evaluacion);

                  this.Cmp.id_condicion_entrega.on('select', function (c,r,i) {
                    if (r.data != undefined) {
                      this.Cmp.codigo_condicion_entrega.setValue(r.data.nombre);
                    }
                  },this);

                  this.Cmp.id_forma_pago.on('select', function (c,r,i) {
                    if (r.data != undefined) {
                      this.Cmp.codigo_forma_pago.setValue(r.data.nombre);
                    }
                  },this);

                  this.Cmp.id_modo_envio.on('select', function (c,r,i) {
                    if (r.data != undefined) {
                      this.Cmp.codigo_modo_envio.setValue(r.data.nombre);
                    }
                  },this);

                  this.Cmp.id_puntos_entrega.on('select', function (c,r,i) {
                    if (r.data != undefined) {
                      this.Cmp.codigo_puntos_entrega.setValue(r.data.nombre);
                      this.Cmp.direccion_punto_entrega.setValue(r.data.direccion);
                    }
                  },this);

                  this.Cmp.id_tipo_transaccion.on('select', function (c,r,i) {
                    if (r.data != undefined) {
                      this.Cmp.codigo_tipo_transaccion.setValue(r.data.nombre);
                    }
                  },this);

                  this.Cmp.id_orden_destino.on('select', function (c,r,i) {
                    if (r.data != undefined) {
                      this.Cmp.codigo_orden_destino.setValue(r.data.nombre);
                    }
                  },this);
                } else {
                  this.Cmp.id_condicion_entrega.allowBlank = true;
                  this.Cmp.id_forma_pago.allowBlank = true;
                  this.Cmp.id_modo_envio.allowBlank = true;
                  this.Cmp.id_puntos_entrega.allowBlank = true;
                  this.Cmp.id_tipo_transaccion.allowBlank = true;
                  this.Cmp.id_orden_destino.allowBlank = true;
                  Ext.getCmp('grupo_alkym').hide();
                  this.Cmp.tipo_evaluacion.allowBlank = true;
                  this.mostrarComponente(this.Cmp.tipo_evaluacion);
                }
                /**************************************/
              }
              this.mostrarComponente(this.Cmp.id_proveedor_contacto);
              this.Cmp.id_proveedor_contacto.allowBlank = false;
            } else {

              if (tipo_tramite == 'GR') {
                /*Aqui Recuperamos los datos*/
                this.Cmp.id_condicion_entrega.store.load({params:{start:0,limit:50},
                   callback : function (r) {
                         this.Cmp.id_condicion_entrega.setValue(condiconEntrega);
                         this.Cmp.id_condicion_entrega.fireEvent('select',this.Cmp.id_condicion_entrega,condiconEntrega);

                    }, scope : this
                });
                this.Cmp.id_forma_pago.store.load({params:{start:0,limit:50},
                   callback : function (r) {
                         this.Cmp.id_forma_pago.setValue(formaPago);
                         this.Cmp.id_forma_pago.fireEvent('select',this.Cmp.id_forma_pago,formaPago);

                    }, scope : this
                });

                this.Cmp.codigo_condicion_entrega.setValue(codigoCondiconEntrega);
                this.Cmp.codigo_forma_pago.setValue(codigoFormaPago);
                this.Cmp.tipo_evaluacion.setValue(tipoEvalucion);

                this.Cmp.id_condicion_entrega.allowBlank = false;
                this.Cmp.id_forma_pago.allowBlank = false;
                this.Cmp.id_modo_envio.allowBlank = false;
                this.Cmp.id_puntos_entrega.allowBlank = false;
                this.Cmp.id_tipo_transaccion.allowBlank = false;
                this.Cmp.id_orden_destino.allowBlank = false;
                Ext.getCmp('grupo_alkym').hide();

                //this.Cmp.fecha_entrega.allowBlank = false;
                //this.Cmp.tipo_evaluacion.allowBlank = false;
                //this.Cmp.fecha_entrega.setValue(fecha_formateada);
                //this.mostrarComponente(this.Cmp.fecha_entrega);
                this.mostrarComponente(this.Cmp.tipo_evaluacion);
                this.ocultarComponente(this.Cmp.id_proveedor_contacto);
                this.Cmp.id_proveedor_contacto.allowBlank = true;

                this.Cmp.id_condicion_entrega.setDisabled(true);
                this.Cmp.id_forma_pago.setDisabled(true);
                this.Cmp.tipo_evaluacion.setDisabled(true);
              } else {
                this.Cmp.id_condicion_entrega.allowBlank = true;
                this.Cmp.id_forma_pago.allowBlank = true;
                this.Cmp.id_modo_envio.allowBlank = true;
                this.Cmp.id_puntos_entrega.allowBlank = true;
                this.Cmp.id_tipo_transaccion.allowBlank = true;
                this.Cmp.id_orden_destino.allowBlank = true;

                this.Cmp.id_condicion_entrega.reset();
                this.Cmp.id_forma_pago.reset();
                this.Cmp.id_modo_envio.reset();
                this.Cmp.id_puntos_entrega.reset();
                this.Cmp.id_tipo_transaccion.reset();
                this.Cmp.id_orden_destino.reset();

                this.Cmp.fecha_entrega.allowBlank = true;
                this.Cmp.tipo_evaluacion.allowBlank = true;
                this.Cmp.fecha_entrega.reset();
                this.Cmp.tipo_evaluacion.reset();
                this.ocultarComponente(this.Cmp.fecha_entrega);
                this.ocultarComponente(this.Cmp.tipo_evaluacion);
                Ext.getCmp('grupo_alkym').hide();
              }
              this.Cmp.id_proveedor_contacto.reset();
              this.ocultarComponente(this.Cmp.id_proveedor_contacto);
              this.Cmp.id_proveedor_contacto.allowBlank = true;
            }
          },this);
          /*****************************************************************************************************/

          /*Datos Referencial*/
          // Ext.Ajax.request({
    			// 		url:'../../sis_gestion_materiales/control/DetalleSol/getCentroCosto',
    			// 		params:{id_gestion:this.data.objPadre.maestro.id_gestion,
          //             id_solicitud:this.data.objPadre.id_solicitud},
    			// 		success:function(resp){
    			// 				var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
          //         this.data.objPadre.id_centro_costo_rec = reg.ROOT.datos.id_centro_costo;
          //         this.data.objPadre.id_cc_rec = reg.ROOT.datos.id_cc_rec;
          //         this.data.objPadre.id_concepto_ingas_rec = reg.ROOT.datos.id_concepto_ingas_rec;
    			// 				this.data.objPadre.id_orden_trabajo_rec = reg.ROOT.datos.id_orden_trabajo_rec;
          //     },
    			// 		failure: this.conexionFailure,
    			// 		timeout:this.timeout,
    			// 		scope:this
    			// });
          //
          // this.Cmp.id_centro_costo.store.baseParams.id_depto = this.data.objPadre.maestro.id_depto;
    			// this.Cmp.id_centro_costo.store.baseParams.id_gestion = this.data.objPadre.maestro.id_gestion;
          //
          // this.Cmp.id_centro_costo.store.baseParams.codigo_subsistema = 'ADQ';
          //
    			// this.Cmp.id_concepto_ingas.store.baseParams.id_gestion = this.data.objPadre.maestro.id_gestion;
    			// this.Cmp.id_concepto_ingas.modificado = true;
          //
          // this.Cmp.id_orden_trabajo.store.baseParams.fecha_solicitud = this.data.objPadre.maestro.fecha_solicitud.dateFormat('d/m/Y');
          // this.Cmp.id_orden_trabajo.modificado = true;
          //
          // this.Cmp.id_centro_costo.store.load({params:{start:0,limit:50},
    			// 	 callback : function (r) {
          //      if (this.data.objPadre.id_cc_rec == '') {
          //          this.Cmp.id_centro_costo.setValue(this.data.objPadre.id_centro_costo_rec);
          //          this.Cmp.id_centro_costo.fireEvent('select',this.Cmp.id_centro_costo, this.Cmp.id_centro_costo.store.getById(this.data.objPadre.id_centro_costo_rec));
          //      } else {
          //          this.Cmp.id_centro_costo.setValue(this.data.objPadre.id_cc_rec);
          //          this.Cmp.id_centro_costo.fireEvent('select',this.Cmp.id_centro_costo, this.Cmp.id_centro_costo.store.getById(this.data.objPadre.id_cc_rec));
          //      }
    			// 		}, scope : this
    			// });
          //
          // if (this.data.objPadre.id_cc_rec == '') {
          //     this.Cmp.id_centro_costo.on('select', function (cmp, rec) {
        	// 	      this.Cmp.id_concepto_ingas.el.dom.style.background='#FFCD8C';
        	// 				this.Cmp.id_orden_trabajo.reset();
          //         this.Cmp.id_concepto_ingas.reset();
          //         this.Cmp.id_orden_trabajo.store.baseParams.id_centro_costo = rec.data.id_centro_costo;
        	// 		}, this);
          // } else {
          //   this.Cmp.id_centro_costo.on('select', function (cmp, rec) {
          //       this.Cmp.id_concepto_ingas.el.dom.style.background='#FFCD8C';
          //       this.Cmp.id_orden_trabajo.reset();
          //       this.Cmp.id_concepto_ingas.reset();
          //       this.Cmp.id_orden_trabajo.store.baseParams.id_centro_costo = rec.data.id_centro_costo;
          //   }, this);
          // }
          //
          // /*Aqui ponemos la condicion para q interactue con el usuario*/
          // if (this.Cmp.id_centro_costo.getValue() != '') {
          //     this.Cmp.id_concepto_ingas.setDisabled(false);
          //     this.Cmp.id_concepto_ingas.allowBlank = false;
          // } else {
          //     this.Cmp.id_concepto_ingas.setDisabled(true);
          //     this.Cmp.id_concepto_ingas.allowBlank = true;
          // }
          //
          //
          //   this.Cmp.id_concepto_ingas.on('select', function (cmp, rec) {
          //     this.Cmp.id_orden_trabajo.el.dom.style.background='#FFCD8C';
          //     this.Cmp.id_orden_trabajo.store.baseParams = Ext.apply(this.Cmp.id_orden_trabajo.store.baseParams, {
          //                                                  filtro_ot:rec.data.filtro_ot,
          //                              requiere_ot:rec.data.requiere_ot,
          //                              id_grupo_ots:rec.data.id_grupo_ots
          //                           });
          //     this.Cmp.id_orden_trabajo.modificado = true;
          //     this.Cmp.id_orden_trabajo.reset();
          //     this.Cmp.id_orden_trabajo.store.load({params:{start:0,limit:50},
          //       callback : function (r) {
          //         if (this.Cmp.id_centro_costo.getValue() != this.data.objPadre.id_centro_costo_rec && r.length == 1) {
          //           this.Cmp.id_orden_trabajo.setValue(r[0].data.id_orden_trabajo);
          //           this.Cmp.id_orden_trabajo.fireEvent('select',this.Cmp.id_orden_trabajo, this.Cmp.id_orden_trabajo.store.getById(r[0].data.id_orden_trabajo));
          //         } else if (this.Cmp.id_centro_costo.getValue() != this.data.objPadre.id_centro_costo_rec && r.length > 1) {
          //           this.Cmp.id_orden_trabajo.setValue('');
          //         }
          //       }, scope : this
          //     });
          //   }, this);
          //
          //   if (this.Cmp.id_concepto_ingas.getValue() != '') {
          //     this.Cmp.id_orden_trabajo.setDisabled(false);
          //     this.Cmp.id_orden_trabajo.allowBlank = false;
          //   } else {
          //     this.Cmp.id_orden_trabajo.setDisabled(true);
          //     this.Cmp.id_orden_trabajo.allowBlank = true;
          //   }



           this.Cmp.id_solicitud.setValue(this.data.objPadre.id_solicitud);
           this.Cmp.id_gestion.setValue(this.data.objPadre.maestro.id_gestion);
           this.Cmp.id_proveedor.store.baseParams ={id_solicitud:this.data.objPadre.id_solicitud ,par_filtro: 'desc_proveedor'};
           this.Cmp.pie_pag.setValue('La entrega del producto es en Forwarder Miami, de acuerdo a oferta del proveedor, después de la notificación del Purchase Order, sin embargo el ingreso al almacén Cochabamba será hasta la fecha requerida de llegada.');


            /*Cambiando fondos*/
            //Donde se encuntran los botones agregar etc
            this.megrid.topToolbar.items.items[0].container.dom.style.width="100px";
            this.megrid.topToolbar.items.items[0].container.dom.style.height="35px";
            this.megrid.topToolbar.items.items[0].btnEl.dom.style.height="35px";
            this.megrid.topToolbar.items.items[0].btnEl.dom.style.width="150px";

            this.megrid.topToolbar.items.items[1].container.dom.style.width="100px";
            this.megrid.topToolbar.items.items[1].container.dom.style.height="35px";
            this.megrid.topToolbar.items.items[1].btnEl.dom.style.height="35px";
            this.megrid.topToolbar.items.items[1].btnEl.dom.style.width="150px";

            Ext.getCmp('botonAgregar').el.dom.onmouseover = function () {
              Ext.getCmp('botonAgregar').btnEl.dom.style.background = '#5CE100';
            };

            Ext.getCmp('botonAgregar').el.dom.onmouseout = function () {
              Ext.getCmp('botonAgregar').btnEl.dom.style.background = '';
            };

            Ext.getCmp('botonEliminar').el.dom.onmouseover = function () {
              Ext.getCmp('botonEliminar').btnEl.dom.style.background = 'rgba(255, 0, 0, 0.5)';
            };

            Ext.getCmp('botonEliminar').el.dom.onmouseout = function () {
              Ext.getCmp('botonEliminar').btnEl.dom.style.background = '';
            };

            this.megrid.topToolbar.el.dom.style.background="#548DCA";
            //cabezera grid
            this.megrid.body.dom.childNodes[0].firstChild.children[0].firstChild.style.background='#A7E7FC';
            this.megrid.body.dom.childNodes[0].firstChild.children[1].offsetParent.style.background='#DCF6FF';

            /*Aumentando para calcular el total en el detalle (Ismael Valdivia 20/03/2020)*/
            this.detCmp.precio_unitario.on('keyup',function(field,newValue,oldValue){
              this.detCmp.precio_unitario_mb.setValue(this.detCmp.cantidad_det.getValue() * this.detCmp.precio_unitario.getValue());
            },this);

            this.detCmp.cantidad_det.on('keyup',function(field,newValue,oldValue){
              this.detCmp.precio_unitario_mb.setValue(this.detCmp.cantidad_det.getValue() * this.detCmp.precio_unitario.getValue());
            },this);

            /******************************************************************************/
        },

        evaluaRequistos: function(){
            //valida que todos los requistosprevios esten completos y habilita la adicion en el grid
            var i = 0;
            sw = true;
            while( i < this.Componentes.length) {
                if(!this.Componentes[i].isValid()){
                    sw = false;
                }
                i++;
            }
            return sw
        },

        recuperarDetalleCotizacion:function(){
          Ext.Ajax.request({
              url:'../../sis_gestion_materiales/control/Cotizacion/recuperarDetalleCotizacion',
              params:{id_solicitud:this.data.objPadre.id_solicitud},
              success: function(resp){
                  var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                  this.nro_parte_rec = reg.ROOT.datos.nro_parte;
                  this.nro_parte_alterno_rec = reg.ROOT.datos.nro_parte_alterno;
                  this.referencia_rec = reg.ROOT.datos.referencia;
                  this.descripcion_rec = reg.ROOT.datos.descripcion;
                  this.explicacion_detallada_part_rec = reg.ROOT.datos.explicacion_detallada_part;
                  this.tipo_rec = reg.ROOT.datos.tipo;
                  this.cantidad_sol_rec = reg.ROOT.datos.cantidad_sol;
                  this.id_unidad_medida_rec = reg.ROOT.datos.id_unidad_medida;
                  this.cod_medida_rec = reg.ROOT.datos.cod_medida;
                  this.id_detalle_rec = reg.ROOT.datos.id_detalle;

                  this.nro_parte_json = this.nro_parte_rec.split(",");
                  this.nro_parte_alterno_json = this.nro_parte_alterno_rec.split(",");
                  this.referencia_json = this.referencia_rec.split(",");
                  this.descripcion_json = this.descripcion_rec.split(",");
                  this.explicacion_detallada_part_json = this.explicacion_detallada_part_rec.split(",");
                  this.tipo_json = this.tipo_rec.split(",");
                  this.cantidad_sol_json = this.cantidad_sol_rec.split(",");
                  this.id_unidad_medida_json = this.id_unidad_medida_rec.split(",");
                  this.cod_medida_json = this.cod_medida_rec.split(",");
                  this.id_detalle_json = this.id_detalle_rec.split(",");

                  var grillaRecord =  Ext.data.Record.create([
                      {name:'nro_parte_cot', type: 'varchar'},
                      {name:'nro_parte_alterno_cot', type: 'varchar'},
                      {name:'referencia_cot', type: 'varchar'},
                      {name:'descripcion_cot', type: 'varchar'},
                      {name:'explicacion_detallada_part_cot', type: 'varchar'},
                      {name:'tipo_cot', type: 'varchar'},
                      {name:'codigo', type: 'varchar'},
                      {name:'cantidad_det', type: 'integer'},
                      {name:'cd', type: 'varchar'},
                      {name:'precio_unitario', type: 'numeric'},
                      {name:'precio_unitario_mb', type: 'numeric'},
                      {name:'id_unidad_medida', type: 'numeric'},
                      {name:'cod_medida', type: 'varchar'},
                      {name:'id_detalle', type: 'numeric'},
                    ]);

                for (var i = 0; i < this.nro_parte_json.length; i++) {
                  var myNewRecord = new grillaRecord({
                      nro_parte_cot : this.nro_parte_json[i],
                      nro_parte_alterno_cot: this.nro_parte_alterno_json[i],
                      referencia_cot: this.referencia_json[i],
                      descripcion_cot : this.descripcion_json[i],
                      explicacion_detallada_part_cot : this.nro_parte_json[i],
                      tipo_cot:this.tipo_json[i],
                      id_unidad_medida: this.id_unidad_medida_json[i],
                      cantidad_det: this.cantidad_sol_json[i],
                      cod_medida: this.cod_medida_json[i],
                      id_detalle: this.id_detalle_json[i],
                    });

                     this.mestore.add(myNewRecord);
                 }
              },
              failure: this.conexionFailure,
              timeout:this.timeout,
              scope:this
          });

        },
        obtenerGestion:function(x){
            var fecha = x.getValue().dateFormat(x.format);
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_parametros/control/Gestion/obtenerGestionByFecha',
                params:{fecha:fecha},
                success:this.successGestion,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },
        successGestion:function(resp){
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            if(!reg.ROOT.error){

                this.Cmp.id_gestion.setValue(reg.ROOT.datos.id_gestion);


            }else{

                alert('ocurrio al obtener la gestion')
            }
        },

        onInitAdd: function(re, o, rec, num){

        },
        onCancelAdd: function(re,save){
            if(this.sw_init_add){
                this.mestore.remove(this.mestore.getAt(0));
            }
            this.sw_init_add = false;

            this.detCmp.referencia_cot.setDisabled(false);
            this.detCmp.descripcion_cot.setDisabled(false);
            this.detCmp.explicacion_detallada_part_cot.setDisabled(false);
            this.detCmp.id_unidad_medida.setDisabled(false);
            this.detCmp.cd.setDisabled(false);
            this.detCmp.id_day_week.setDisabled(false);
                       //this.evaluaGrilla();

        },
        onUpdateRegister: function(){
            this.sw_init_add = false;
        },

        onAfterEdit:function(re, o, rec, num){

          var cmb_rec = this.detCmp['id_unidad_medida'].store.getById(rec.get('id_unidad_medida'));

            if(cmb_rec) {
                rec.set('cod_medida', cmb_rec.get('codigo'));
            }

            var cmb_rec = this.detCmp['id_day_week'].store.getById(rec.get('id_day_week'));
              if(cmb_rec) {
                  rec.set('codigo_descripcion_irva', cmb_rec.get('codigo_tipo'));
              }

              this.detCmp.referencia_cot.setDisabled(false);
              this.detCmp.descripcion_cot.setDisabled(false);
              this.detCmp.explicacion_detallada_part_cot.setDisabled(false);
              this.detCmp.id_unidad_medida.setDisabled(false);
              this.detCmp.cd.setDisabled(false);
              this.detCmp.id_day_week.setDisabled(false);

        },
        buildDetailGrid:function () {
            var Items = Ext.data.Record.create([{
                name: 'cantidad_sol',
                type: 'int'
            }
            ]);
            this.mestore = new Ext.data.JsonStore({
                url: '../../sis_gestion_materiales/control/CotizacionDetalle/listarCotizacionDetalle',
                id: 'id_cotizacion_det',
                root: 'datos',
                totalProperty: 'total',
                fields: [
                    {name:'id_cotizacion_det', type: 'numeric'},
                    {name:'total_precio_unitario', type: 'numeric'},
                    {name:'total_precio', type: 'numeric'},
                    {name:'id_day_week', type: 'numeric'},
                    {name:'id_cotizacion_det', type: 'numeric'},
                    {name:'id_cotizacion', type: 'numeric'},
                    {name:'id_detalle', type: 'numeric'},
                    {name:'id_solicitud', type: 'numeric'},
                    {name:'nro_parte_cot', type: 'varchar'},
                    {name:'nro_parte_alterno_cot', type: 'varchar'},
                    {name:'referencia_cot', type: 'varchar'},
                    {name:'descripcion_cot', type: 'varchar'},
                    {name:'explicacion_detallada_part_cot', type: 'varchar'},
                    {name:'tipo_cot', type: 'varchar'},
                    {name:'cantidad_det', type: 'numeric'},
                    {name:'precio_unitario', type: 'numeric'},
                    {name:'precio_unitario_mb', type: 'numeric'},
                    {name:'cd', type: 'varchar'},
                    {name:'codigo', type: 'varchar'},
                    {name:'revisado', type: 'varchar'},
                    {name:'desc_codigo_tipo', type: 'varchar'},
                ],
                remoteSort: true,
                baseParams: {dir:'ASC',sort:'id_cotizacion_det',limit:'100',start:'0'}
            });

            this.editorDetaille = new Ext.ux.grid.RowEditor({
                saveText: 'Aceptar',
                name: 'btn_editor'

            });
            // al iniciar la edicion
            this.editorDetaille.on('beforeedit', this.onInitAdd , this);
            //al cancelar la edicion
            this.editorDetaille.on('canceledit', this.onCancelAdd , this);
            //al cancelar la edicion
            this.editorDetaille.on('validateedit', this.onUpdateRegister, this);
            this.editorDetaille.on('afteredit', this.onAfterEdit, this);

            this.summary = new Ext.ux.grid.GridSummary();

            this.megrid = new Ext.grid.GridPanel({
                layout: 'fit',
                store:  this.mestore,
                region: 'center',
                split: true,
                border: false,
                plain: true,
                plugins: [this.editorDetaille],
                stripeRows: true,
                tbar: [{
                    text: '<div style="font-weight:bold; font-size:15px;"><img src="../../../lib/imagenes/facturacion/anadir.png" style="width:30px; vertical-align: middle;"> Agregar Detalle</div>',
                    scope: this,
                    id:'botonAgregar',
                    width: '100',
                    handler: function(){
                      if(this.evaluaRequistos() === true) {
                            var e = new Items({
                                cantidad_sol: 1
                            });
                            this.editorDetaille.stopEditing();
                            this.mestore.insert(0, e);
                            this.megrid.getView().refresh();
                            this.megrid.getSelectionModel().selectRow(0);
                            this.editorDetaille.startEditing(0);
                            this.sw_init_add = true;

                            this.HazmatPredefinido();
                            //this.bloqueaRequisitos(true);
                        }
                      else{
                          alert('Verifique los requisitos');
                      }
                    }
                },{
                    ref: '../removeBtn',
                    text: '<div style="font-weight:bold; font-size:15px;"><img src="../../../lib/imagenes/facturacion/eliminar.png" style="width:30px; vertical-align: middle;"> Eliminar</div>',
                    scope:this,
                    id:'botonEliminar',
                    handler: function(){
                        this.editorDetaille.stopEditing();
                        var s = this.megrid.getSelectionModel().getSelections();
                        for(var i = 0, r; r = s[i]; i++){
                            this.mestore.remove(r);
                        }
                        //this.evaluaGrilla();
                    }
                }],

                columns: [
                    new Ext.grid.RowNumberer(),
                    {
                        header: 'Nro. Parte',
                        dataIndex: 'nro_parte_cot',
                        align: 'center',
                        width: 165,
                        editor: this.detCmp.nro_parte_cot
                    },
                    {
                        header: 'Nro. Parte Alterno',
                        dataIndex: 'nro_parte_alterno_cot',
                        align: 'center',
                        width: 165,
                        editor: this.detCmp.nro_parte_alterno_cot
                    },
                    {
                        header: 'Referencia',
                        dataIndex: 'referencia_cot',
                        align: 'center',
                        allowBlank:true,
                        width: 165,
                        editor: this.detCmp.referencia_cot
                    },
                    {
                        header: 'Serial',
                        dataIndex: 'referencia_cot',
                        align: 'center',
                        allowBlank:true,
                        //hidden:true,
                        width: 165,
                        editor: this.detCmp.serial
                    },
                    {
                        header: 'Descripcion',
                        dataIndex: 'descripcion_cot',
                        align: 'center',
                        width: 180,
                        editor: this.detCmp.descripcion_cot
                    },
                    {
                        header: 'P/N Cotizacion',
                        dataIndex: 'explicacion_detallada_part_cot',
                        align: 'center',
                        width: 210,
                        editor: this.detCmp.explicacion_detallada_part_cot
                    },
                    {
                        header: 'Tipo',
                        dataIndex: 'tipo_cot',
                        align: 'center',
                        width: 125,
                        editor: this.detCmp.tipo_cot
                    },
                    {
                        header: 'Unidad Medida',
                        dataIndex: 'id_unidad_medida',
                        align: 'center',
                        width: 125,
                        renderer:function(value, p, record){
                          return String.format('{0}', record.data['cod_medida']);
                          //return String.format('{0}', record.data['codigo_recuperando']);
                        },
                        editor: this.detCmp.id_unidad_medida
                    },
                    {
                        header: 'Cantidad Total',
                        dataIndex: 'cantidad_det',
                        align: 'center',
                        width: 125,
                        editor: this.detCmp.cantidad_det
                    },
                    {
                         header: 'Precio Unitario',
                         dataIndex: 'precio_unitario',
                         align: 'center',
                         width: 125,
                         editor: this.detCmp.precio_unitario
                     },
                     {
                          header: 'Precio Total',
                          dataIndex: 'precio_unitario_mb',
                          align: 'center',
                          width: 125,
                          disabled:true,
                          editor: this.detCmp.precio_unitario_mb
                      },
                      {
                           header: 'CD',
                           dataIndex: 'cd',
                           align: 'center',
                           width: 125,
                           editor: this.detCmp.cd
                      },
                      {
                           header: 'Tiempo Entrega',
                           dataIndex: 'id_day_week',
                           align: 'center',
                           width: 125,
                           sortable: false,
                           renderer: function (value, p, record) {
                            if(record.data['codigo_descripcion_irva'] != undefined) {
                              return String.format('{0}', record.data['codigo_descripcion_irva']);
                            } else {
                              return String.format('{0}', '');
                            }
                           },
                           editor: this.detCmp.id_day_week
                       }
                ]
            });

        },
        buildGrupos: function(){
            this.Grupos = [{
                layout: 'border',
                border: false,
                frame:true,

                items:[
                    {
                        xtype: 'fieldset',
                        border: false,
                        split: true,
                        layout: 'column',
                        region: 'north',
                        autoScroll: true,
                        autoHeight: true,
                        collapseFirst : false,
                        collapsible: true,
                        width: '100%',
                        //autoHeight: true,
                        padding: '0 0 0 10',
                        style: {

                               background: '#548DCA',
                               //border:'2px solid green'
                            },
                        items:[
                            {
                                bodyStyle: 'padding-right:5px;',
                                style: {
                                       background: '#9FE1FF',
                                       height:'260px',
                                       border:'1px solid white',
                                       borderRadius:'2px'
                                    },

                                border: false,
                                autoHeight: true,
                                items: [{
                                    xtype: 'fieldset',
                                    //frame: true,aa
                                    layout: 'form',
                                    title: '<b style="color:#0062CA; font-size:15px;"><img src="../../../lib/imagenes/facturacion/Registro.svg" style="width:27px; vertical-align: middle;"> <span style="vertical-align: middle; font-size:20px; text-shadow: 3px 0px 0px #000000;"> DATOS COTIZACIÓN</span></b>',
                                    width: '350px',
                                    border: false,
                                    //border: false,
                                    //margins: '0 0 0 5',
                                    padding: '0 0 0 10',
                                    bodyStyle: 'padding-left:5px;',
                                    id_grupo: 11,
                                    items: [{
                                        xtype: 'fieldset',
                                        //frame: true,
                                        layout: 'form',
                                        border: false,
                                        //border: false,
                                        //margins: '0 0 0 5',
                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 0,
                                        items: []
                                    },
                                    ]
                                }]
                            },
                            {
                              style:{
                                    background:'#9FE1FF',
                                    height:'260px',
                                    border:'1px solid white',
                                    borderRadius:'2px',
                                    marginLeft:'60px'
                                   },
                                id:'grupo_segundo_cotizacion',
                                autoHeight: true,
                                border: false,
                                items:[
                                    {
                                        xtype: 'fieldset',
                                        /*frame: true,
                                         border: false,*/
                                        layout: 'form',
                                        border: false,
                                        //title: '<b style="color:#FF8700; font-size:15px"><i class="fa fa-pencil" aria-hidden="true"></i> Datos Referencial</b>',
                                        width: '350px',


                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 2,
                                        items: []
                                    }]
                            },
                            {
                              style:{
                                    background:'#ECFFEA',
                                    height:'260px',
                                    border:'1px solid white',
                                    borderRadius:'2px',
                                    marginLeft:'60px'
                                   },
                                autoHeight: true,
                                border: false,
                                hidden:true,
                                id:'grupo_alkym',
                                items:[
                                    {
                                        xtype: 'fieldset',
                                        /*frame: true,
                                         border: false,*/
                                        layout: 'form',
                                        border: false,
                                        title: '<b style="color:#54B200; font-size:15px;"><img src="../../../lib/imagenes/facturacion/conexion.svg" style="width:27px; vertical-align: middle;"> <span style="vertical-align: middle; font-size:20px; text-shadow: 3px 0px 0px #000000;"> DATOS ALKYM</span></b>',
                                        width: '350px',


                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 3,
                                        items: []
                                    }]
                            }
                        ]
                    },
                    this.megrid
                ]
            }];


        },
        successSave:function(resp)
        {
            Phx.CP.loadingHide();
            Phx.CP.getPagina(this.idContenedorPadre).reload();
            this.panel.close();
        },
        Atributos:[
          {
              //configuracion del componente
              config:{
                  labelSeparator:'',
                  inputType:'hidden',
                  name: 'id_cotizacion'
              },
              type:'Field',
              form:true
          },
          {
              //configuracion del componente
              config:{
                  labelSeparator:'',
                  inputType:'hidden',
                  name: 'id_solicitud'
              },
              type:'Field',
              form:true
          },
          {
              //configuracion del componente
              config:{
                  labelSeparator:'',
                  inputType:'hidden',
                  name: 'id_gestion'
              },
              type:'Field',
              form:true
          },
          /*Aqui recuperamos los codigos de los datos del Alkym*/
          {
              //configuracion del componente
              config:{
                  labelSeparator:'',
                  inputType:'hidden',
                  name: 'codigo_condicion_entrega'
              },
              type:'Field',
              form:true
          },
          {
              //configuracion del componente
              config:{
                  labelSeparator:'',
                  inputType:'hidden',
                  name: 'codigo_forma_pago'
              },
              type:'Field',
              form:true
          },
          {
              //configuracion del componente
              config:{
                  labelSeparator:'',
                  inputType:'hidden',
                  name: 'codigo_modo_envio'
              },
              type:'Field',
              form:true
          },
          {
              //configuracion del componente
              config:{
                  labelSeparator:'',
                  inputType:'hidden',
                  name: 'codigo_puntos_entrega'
              },
              type:'Field',
              form:true
          },
          {
              //configuracion del componente
              config:{
                  labelSeparator:'',
                  inputType:'hidden',
                  name: 'codigo_tipo_transaccion'
              },
              type:'Field',
              form:true
          },
          {
              //configuracion del componente
              config:{
                  labelSeparator:'',
                  inputType:'hidden',
                  name: 'codigo_orden_destino'
              },
              type:'Field',
              form:true
          },
          {
              //configuracion del componente
              config:{
                  labelSeparator:'',
                  inputType:'hidden',
                  name: 'direccion_punto_entrega'
              },
              type:'Field',
              form:true
          },
          /*****************************************************/
          {
              config: {
                  name: 'id_proveedor',
                  fieldLabel: 'Proveedor',
                  allowBlank: false,
                  style:{
                    background:'#FFCD8C',
                  },
                  emptyText: 'Elija una opción...',
                  store: new Ext.data.JsonStore({
                      url: '../../sis_gestion_materiales/control/Cotizacion/listarProvedor',
                      id: 'id_prov',
                      root: 'datos',
                      sortInfo: {
                          field: 'desc_proveedor',
                          direction: 'ASC'
                      },
                      totalProperty: 'total',
                      fields: ['id_solicitud', 'id_gestion_proveedores','id_prov','desc_proveedor'],
                      remoteSort: true,
                      baseParams: {par_filtro: 'desc_proveedor'}
                  }),
                  valueField: 'id_prov',
                  displayField: 'desc_proveedor',
                  gdisplayField: 'desc_proveedor',
                  hiddenName: 'id_proveedor',
                  forceSelection: true,
                  typeAhead: false,
                  triggerAction: 'all',
                  lazyRender: true,
                  mode: 'remote',
                  pageSize: 15,
                  queryDelay: 1000,
                  width: 200,
                  gwidth: 300,
                  minChars: 2,
                  renderer : function(value, p, record) {
                      return String.format('{0}', record.data['desc_proveedor']);
                  }
              },
              type: 'ComboBox',
              id_grupo: 1,
              filters: {pfiltro: 'p.desc_proveedor',type: 'string'},
              grid: true,
              form: true
          },
          {
              config:{
                  name: 'nro_cotizacion',
                  style:{
                    background:'#FFCD8C',
                  },
                  fieldLabel: 'Nro.Cotización',
                  allowBlank: false,
                  width: 200,
                  gwidth: 170,
                  maxLength:500
              },
              type:'TextField',
              filters:{pfiltro:'cts.nro_cotizacion',type:'string'},
              id_grupo:0,
              grid:true,
              form:true
          },
          {
              config:{
                  name: 'fecha_cotizacion',
                  style:{
                    background:'#FFCD8C',
                  },
                  fieldLabel: 'Fecha Cotización',
                  allowBlank: false,
                  qtip: 'Para BoA Rep esta información se refleja en el reporte de Informe de Justificacion y Recomendacion (Fecha)',
                  width: 200,
                  gwidth: 100,
                  format: 'd/m/Y',
                  renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
              },
              type:'DateField',
              filters:{pfiltro:'cts.fecha_cotizacion',type:'date'},
              id_grupo:0,
              grid:true,
              form:true
          },
          {
              config:{
                  name: 'recomendacion',
                  style:{
                    background:'#FFCD8C',
                  },
                  fieldLabel: 'Recomendación',
                  allowBlank: true,
                  width: 200,
                  gwidth: 200,
                  maxLength:10000
              },
              type:'TextArea',
              filters:{pfiltro:'sol.obs',type:'string'},
              id_grupo:0,
              grid:true,
              form:true
          },
          {
              config:{
                  name: 'obs',
                  fieldLabel: 'Observaciones',
                  style:{
                    background:'#FFCD8C',
                  },
                  allowBlank: true,
                  qtip: 'Para BoA Rep esta información se refleja en el reporte de Comite de Evaluación (Observaciones)<br>Para los trámites del tipo GO-GA-GM esta información se refleja en el reporte comité de evaluación(Observaciones)',
                  width: 200,
                  gwidth: 200,
                  maxLength:10000
              },
              type:'TextArea',
              filters:{pfiltro:'cts.obs',type:'string'},
              id_grupo:0,
              grid:true,
              form:true
          },
          {
              config:{
                  name: 'pie_pag',
                  fieldLabel: 'Pie de Pagina',
                  style:{
                    background:'#FFCD8C',
                  },
                  allowBlank: true,
                  width: 200,
                  gwidth: 200,
                  maxLength:10000
              },
              type:'TextArea',
              filters:{pfiltro:'cts.pie_pag',type:'string'},
              id_grupo:2,
              grid:true,
              form:true
          },
          // {
  				// 		config:{
  				// 				name:'id_centro_costo',
  				// 				origen:'CENTROCOSTO',
  				// 				allowBlank: true,
  				// 				gdisplayField:'desc_centro_costo',
  				// 				disabled: false,
  				// 				width: 200,
  				// 				gwidth: 200,
  				// 				style: {
  				// 									background:'#FFCD8C'
  				// 								},
  				// 				fieldLabel: 'Centro de Costos',
  				// 				url: '../../sis_parametros/control/CentroCosto/listarCentroCostoFiltradoXDepto',
  				// 				baseParams: {filtrar: 'grupo_ep'},
  				// 		},
  				// 		type: 'ComboRec',
  				// 		id_grupo: 2,
  				// 		form: true,
  				// 		grid:true
  				// },
  				// {
  				// 		config: {
  				// 				name: 'id_concepto_ingas',
  				// 				fieldLabel: 'Concepto',
          //         style:{
          //           background:'#FFCD8C',
          //         },
  				// 				allowBlank: true,
  				// 				width: 200,
  				// 				emptyText: 'Concepto...',
  				// 				store: new Ext.data.JsonStore({
  				// 						url: '../../sis_parametros/control/ConceptoIngas/listarConceptoIngasMasPartida',
  				// 						id: 'id_concepto_ingas',
  				// 						root: 'datos',
  				// 						sortInfo: {
  				// 								field: 'desc_ingas',
  				// 								direction: 'ASC'
  				// 						},
  				// 						totalProperty: 'total',
  				// 						fields: ['id_concepto_ingas', 'tipo', 'desc_ingas', 'movimiento', 'desc_partida', 'id_grupo_ots', 'filtro_ot', 'requiere_ot'],
  				// 						remoteSort: true,
  				// 						baseParams: {
  				// 								par_filtro: 'desc_ingas#par.codigo',
  				// 								movimiento: 'gasto',
  				// 								autorizacion: 'adquisiciones',
  				// 								gestion_materiales: 'si'
  				// 						}
  				// 				}),
  				// 				valueField: 'id_concepto_ingas',
  				// 				gdisplayField : 'desc_concepto_ingas',
  				// 				displayField: 'desc_ingas',
  				// 				hiddenName: 'id_concepto_ingas',
  				// 				forceSelection: true,
  				// 				typeAhead: false,
  				// 				triggerAction: 'all',
  				// 				lazyRender: true,
  				// 				mode: 'remote',
  				// 				pageSize: 10,
  				// 				queryDelay: 1000,
  				// 				minChars: 2,
  				// 				gwidth: 350,
  				// 				listWidth:'500',
  				// 				listeners: {
  				// 					  beforequery: function(qe){
  				// 						delete qe.combo.lastQuery;
  				// 					}
  				// 				},
  				// 				qtip: 'Si el concepto de gasto que necesita no existe por favor  comuniquese con el área de presupuestos para solictar la creación',
  				// 				tpl: '<tpl for="."><div class="x-combo-list-item"><p><b>{desc_ingas}</b></p><strong>{tipo}</strong><p>PARTIDA: {desc_partida}</p></div></tpl>',
          //
  				// 		},
  				// 		type: 'ComboBox',
  				// 		id_grupo: 2,
  				// 		form: true,
  				// 		grid:true
  				// },
  				// {
  				// 		config:{
  				// 				name:'id_orden_trabajo',
  				// 				sysorigen: 'sis_contabilidad',
          //         style:{
          //           background:'#FFCD8C',
          //         },
  				// 				origen: 'OT' ,
  				// 				width: 200,
  				// 				fieldLabel: 'Orden Trabajo',
  				// 				allowBlank: true,
  				// 				renderer:function(value, p, record){return String.format('{0}', record.data['desc_orden_trabajo']);},
  				// 				baseParams:{par_filtro:'desc_orden#motivo_orden'},
  				// 				gdisplayField: 'desc_orden_trabajo',
  				// 				gwidth: 200,
  				// 				listeners: {
  				// 				  beforequery: function(qe){
  				// 					delete qe.combo.lastQuery;
  				// 				}
  				// 			},
  				// 		},
  				// 		type: 'ComboRec',
  				// 		id_grupo: 2,
  				// 		form: true,
  				// 		grid:true
  				// },
          // {
          //     config:{
          //         name:'referencial',
          //         fieldLabel:'Referencial',
          //         style:{
          //           background:'#F9FF73',
          //         },
          //         typeAhead: true,
          //         allowBlank:true,
          //         triggerAction: 'all',
          //         emptyText:'Tipo...',
          //         selectOnFocus:true,
          //         mode:'local',
          //         store:new Ext.data.ArrayStore({
          //             fields: ['ID', 'valor'],
          //             data :	[
          //                 ['1','Si'],
          //                 ['2','No'],
          //             ]
          //         }),
          //         valueField:'valor',
          //         displayField:'valor',
          //         width: 200,
          //         gwidth:100
          //
          //     },
          //     type:'ComboBox',
          //     id_grupo:2,
          //     grid:false,
          //     egrid: true,
          //     form:true
          // },
          {
              config:{
                  name:'adjudicado',
                  fieldLabel:'Adjudicado',
                  style:{
                    background:'#73FF73',
                  },
                  typeAhead: true,
                  allowBlank:true,
                  triggerAction: 'all',
                  emptyText:'Tipo...',
                  selectOnFocus:true,
                  mode:'local',
                  store:new Ext.data.ArrayStore({
                      fields: ['ID', 'valor'],
                      data :	[
                          ['si','Si'],
                          ['no','No'],
                      ]
                  }),
                  valueField:'ID',
                  displayField:'valor',
                  width: 200,
                  gwidth:100

              },
              type:'ComboBox',
              id_grupo:2,
              grid:false,
              egrid: true,
              form:true
          },
          {
              config:{
                  name: 'fecha_entrega',
                  style:{
                    background:'#FFCD8C',
                  },
                  fieldLabel: 'Fecha Entrega',
                  allowBlank: true,
                  qtip: 'Para BoA Rep esta información se refleja en el reporte de Order Date (Delivery date)',
                  hidden:true,
                  width: 200,
                  gwidth: 100,
                  format: 'd/m/Y',
                  renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
              },
              type:'DateField',
              //filters:{pfiltro:'sol.fecha_reg',type:'date'},
              id_grupo:2,
              grid:true,
              form:true
          },
          {
              config:{
                  name:'tipo_evaluacion',
                  style:{
                    background:'#FFCD8C',
                  },
                  fieldLabel:'Tipo de Adquisición',
                  typeAhead: true,
                  width: 200,
                  allowBlank:true,//esta parte comentado
                  hidden:true,//esta parte aumentado
                  triggerAction: 'all',
                  emptyText:'Tipo...',
                  selectOnFocus:true,
                  mode:'local',
                  store:new Ext.data.ArrayStore({
                      fields: ['ID', 'valor'],
                      data :	[
                          ['1','Compra'],
                          ['2','Reparacion'],
                          ['3','Exchange'],
                          ['4','Flat Exchange']
                      ]
                  }),
                  valueField:'valor',
                  displayField:'valor',
                  gwidth:100

              },
              type:'ComboBox',
              id_grupo:2,
              grid:false,
              form:true
          },
          /*Listado para seleccionar el contacto si es adjudicado*/
          {
              config: {
                  name: 'id_proveedor_contacto',
                  fieldLabel: 'Contacto Proveedor',
                  allowBlank: true,
                  style:{
                    background:'#FFCD8C',
                  },
                  emptyText: 'Elija una opción...',
                  store: new Ext.data.JsonStore({
                      url: '../../sis_gestion_materiales/control/Cotizacion/listarContactos',
                      id: 'id_proveedor_contacto',
                      root: 'datos',
                      sortInfo: {
                          field: 'id_proveedor_contacto',
                          direction: 'ASC'
                      },
                      totalProperty: 'total',
                      fields: ['id_proveedor_contacto', 'id_proveedor_contacto_alkym','nombre_contacto'],
                      remoteSort: true,
                      baseParams: {par_filtro: 'nombre_contacto'}
                  }),
                  valueField: 'id_proveedor_contacto',
                  displayField: 'nombre_contacto',
                  gdisplayField: 'nombre_contacto',
                  hiddenName: 'id_proveedor_contacto',
                  forceSelection: true,
                  typeAhead: false,
                  triggerAction: 'all',
                  lazyRender: true,
                  mode: 'remote',
                  pageSize: 15,
                  queryDelay: 1000,
                  width: 200,
                  gwidth: 300,
                  hidden:true,
                  minChars: 2,
                  renderer : function(value, p, record) {
                      return String.format('{0}', record.data['nombre_contacto']);
                  },
                  listeners: {
  									  beforequery: function(qe){
  										delete qe.combo.lastQuery;
  									}
  								},
              },
              type: 'ComboBox',
              id_grupo: 2,
              filters: {pfiltro: 'nombre_contacto',type: 'string'},
              grid: true,
              form: true
          },
          /*Aumentando para recuperar los datos del Alkym*/
          {
  						config: {
  								name: 'id_condicion_entrega',
  								fieldLabel: 'Condiciones de Entrega',
                  style:{
                    background:'#96DCEB',
                  },
  								allowBlank: true,
  								width: 200,
  								emptyText: 'Condiciones de Entrega...',
  								store: new Ext.data.JsonStore({
  										url: '../../sis_gestion_materiales/control/Solicitud/obtenerCombosAlkym',
  										id: 'id',
  										root: 'datos',
  										sortInfo: {
  												field: 'id',
  												direction: 'ASC'
  										},
  										totalProperty: 'total',
  										fields: ['id', 'nombre'],
  										remoteSort: true,
                      baseParams: {par_filtro: 'alk.nombre#alk.id',tipo_combo: 'condicion_entrega'}
  								}),
  								valueField: 'id',
  								gdisplayField : 'nombre',
  								displayField: 'nombre',
  								hiddenName: 'id',
  								forceSelection: true,
  								typeAhead: false,
  								triggerAction: 'all',
  								lazyRender: true,
  								mode: 'remote',
  								pageSize: 10,
  								queryDelay: 1000,
  								minChars: 2,
  								gwidth: 350,
  								listWidth:'200',
  								listeners: {
  									  beforequery: function(qe){
  										delete qe.combo.lastQuery;
  									}
  								},
  						},
  						type: 'ComboBox',
              filters: {pfiltro:'alk.nombre', type:'string'},
  						id_grupo: 3,
  						form: true,
  						grid:true
  				},
          {
  						config: {
  								name: 'id_forma_pago',
  								fieldLabel: 'Forma de pago',
                  style:{
                    background:'#96DCEB',
                  },
  								allowBlank: true,
  								width: 200,
  								emptyText: 'Formas de pago...',
  								store: new Ext.data.JsonStore({
  										url: '../../sis_gestion_materiales/control/Solicitud/obtenerCombosAlkym',
  										id: 'id',
  										root: 'datos',
  										sortInfo: {
  												field: 'id',
  												direction: 'ASC'
  										},
  										totalProperty: 'total',
  										fields: ['id', 'nombre'],
  										remoteSort: true,
                      baseParams: {par_filtro: 'alk.nombre#alk.id',tipo_combo: 'formas_pago'}
  								}),
  								valueField: 'id',
  								gdisplayField : 'nombre',
  								displayField: 'nombre',
  								hiddenName: 'id',
  								forceSelection: true,
  								typeAhead: false,
  								triggerAction: 'all',
  								lazyRender: true,
  								mode: 'remote',
  								pageSize: 10,
  								queryDelay: 1000,
  								minChars: 2,
  								gwidth: 350,
  								listWidth:'200',
  								listeners: {
  									  beforequery: function(qe){
  										delete qe.combo.lastQuery;
  									}
  								},
  						},
  						type: 'ComboBox',
  						id_grupo: 3,
  						form: true,
  						grid:true
  				},
          {
  						config: {
  								name: 'id_modo_envio',
  								fieldLabel: 'Modos de Envio',
                  style:{
                    background:'#96DCEB',
                  },
  								allowBlank: true,
  								width: 200,
  								emptyText: 'Modos de Envio...',
  								store: new Ext.data.JsonStore({
  										url: '../../sis_gestion_materiales/control/Solicitud/obtenerCombosAlkym',
  										id: 'id',
  										root: 'datos',
  										sortInfo: {
  												field: 'id',
  												direction: 'ASC'
  										},
  										totalProperty: 'total',
  										fields: ['id', 'nombre'],
  										remoteSort: true,
                      baseParams: {par_filtro: 'alk.nombre#alk.id',tipo_combo: 'modos_envio'}

  								}),
  								valueField: 'id',
  								gdisplayField : 'nombre',
  								displayField: 'nombre',
  								hiddenName: 'id',
  								forceSelection: true,
  								typeAhead: false,
  								triggerAction: 'all',
  								lazyRender: true,
  								mode: 'remote',
  								pageSize: 10,
  								queryDelay: 1000,
  								minChars: 2,
  								gwidth: 350,
  								listWidth:'200',
  								listeners: {
  									  beforequery: function(qe){
  										delete qe.combo.lastQuery;
  									}
  								},
  						},
  						type: 'ComboBox',
  						id_grupo: 3,
  						form: true,
  						grid:true
  				},
          {
  						config: {
  								name: 'id_puntos_entrega',
  								fieldLabel: 'Puntos de entrega',
                  style:{
                    background:'#96DCEB',
                  },
  								allowBlank: true,
  								width: 200,
  								emptyText: 'Puntos de Entrega...',
  								store: new Ext.data.JsonStore({
  										url: '../../sis_gestion_materiales/control/Solicitud/obtenerCombosAlkym',
  										id: 'id',
  										root: 'datos',
  										sortInfo: {
  												field: 'id',
  												direction: 'ASC'
  										},
  										totalProperty: 'total',
  										fields: ['id', 'nombre','direccion'],
  										remoteSort: true,
                      baseParams: {par_filtro: 'alk.nombre#alk.id',tipo_combo: 'puntos_entrega'}
  								}),
  								valueField: 'id',
  								gdisplayField : 'nombre',
  								displayField: 'nombre',
  								hiddenName: 'id',
  								forceSelection: true,
  								typeAhead: false,
                  tpl: new Ext.XTemplate([
     								 '<tpl for=".">',
     								 '<div class="x-combo-list-item">',
     								 '<div>',
     								 '<p><b>Punto de Entrega: <span style="color: red;">{nombre}</span></b></p>',
     								 '</div><p><b>Direccion:</b> <span style="color: green;">{direccion}</span></p>',
     								 '</div></tpl>'
     						 ]),
  								triggerAction: 'all',
  								lazyRender: true,
  								mode: 'remote',
  								pageSize: 10,
  								queryDelay: 1000,
  								minChars: 2,
  								gwidth: 350,
  								listWidth:'450',
  								listeners: {
  									  beforequery: function(qe){
  										delete qe.combo.lastQuery;
  									}
  								},
  						},
  						type: 'ComboBox',
  						id_grupo: 3,
  						form: true,
  						grid:true
  				},
          {
  						config: {
  								name: 'id_tipo_transaccion',
  								fieldLabel: 'Tipo Transacciones',
                  style:{
                    background:'#96DCEB',
                  },
  								allowBlank: true,
  								width: 200,
  								emptyText: 'Tipo Transacciones...',
  								store: new Ext.data.JsonStore({
  										url: '../../sis_gestion_materiales/control/Solicitud/obtenerCombosAlkym',
  										id: 'id',
  										root: 'datos',
  										sortInfo: {
  												field: 'id',
  												direction: 'ASC'
  										},
  										totalProperty: 'total',
  										fields: ['id', 'nombre'],
  										remoteSort: true,
                      baseParams: {par_filtro: 'alk.nombre#alk.id',tipo_combo: 'tipo_transaccion'}
  								}),
  								valueField: 'id',
  								gdisplayField : 'nombre',
  								displayField: 'nombre',
  								hiddenName: 'id',
  								forceSelection: true,
  								typeAhead: false,
  								triggerAction: 'all',
  								lazyRender: true,
  								mode: 'remote',
  								pageSize: 10,
  								queryDelay: 1000,
  								minChars: 2,
  								gwidth: 350,
  								listWidth:'200',
  								listeners: {
  									  beforequery: function(qe){
  										delete qe.combo.lastQuery;
  									}
  								},
  						},
  						type: 'ComboBox',
  						id_grupo: 3,
  						form: true,
  						grid:true
  				},
          {
  						config: {
  								name: 'id_orden_destino',
  								fieldLabel: 'Orden de Destino',
                  style:{
                    background:'#96DCEB',
                  },
  								allowBlank: true,
  								width: 200,
  								emptyText: 'Orden de destino...',
  								store: new Ext.data.JsonStore({
  										url: '../../sis_gestion_materiales/control/Solicitud/obtenerCombosAlkym',
  										id: 'id',
  										root: 'datos',
  										sortInfo: {
  												field: 'id',
  												direction: 'ASC'
  										},
  										totalProperty: 'total',
  										fields: ['id', 'nombre'],
  										remoteSort: true,
                      baseParams: {par_filtro: 'alk.nombre#alk.id',tipo_combo: 'orden_destino'}

  								}),
  								valueField: 'id',
  								gdisplayField : 'nombre',
  								displayField: 'nombre',
  								hiddenName: 'id',
  								forceSelection: true,
  								typeAhead: false,
  								triggerAction: 'all',
  								lazyRender: true,
  								mode: 'remote',
  								pageSize: 10,
  								queryDelay: 1000,
  								minChars: 2,
  								gwidth: 350,
  								listWidth:'200',
  								listeners: {
  									  beforequery: function(qe){
  										delete qe.combo.lastQuery;
  									}
  								},
  						},
  						type: 'ComboBox',
  						id_grupo: 3,
  						form: true,
  						grid:true
  				},
          /***********************************************/

        ],
        title: 'Frm Materiales',

        onSubmit: function(o) {
            //  validar formularios
            var arra = [], k, me = this;
            for (k = 0; k < me.megrid.store.getCount(); k++) {
                record = me.megrid.store.getAt(k);
                arra[k] = record.data;
            }
            me.argumentExtraSubmit = { 'json_new_records': JSON.stringify(arra, function replacer(key, value) {
                return value;
            }) };
            /*Aumentando para que se registre cuando los datos de la cabezera esten llenados (Ismael Valdivia 23/03/2020)*/

                      if( k > 0 &&  !this.editorDetaille.isVisible()){
                          Phx.vista.FormCotizacion.superclass.onSubmit.call(this,o);
                      }
                      else{
                          alert("No tiene datos en el detalle")
                      }

            /******************************************************************************************/

        }
    })
</script>
