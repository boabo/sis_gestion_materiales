<?php
/**
 *@package pXP
 *@file    FormFrumla.php
 *@author  MMV
 *@date    21/12/2016
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.FromFormula=Ext.extend(Phx.frmInterfaz,{

        ActSave:'../../sis_gestion_materiales/control/Solicitud/insertarSolicitudCompleta',
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
            Phx.vista.FromFormula.superclass.constructor.call(this,config);
            this.init();
            this.onNew();
            this.iniciarEventos();
            this.ocultar = false;
        },
        buildComponentesDetalle: function () {

            this.detCmp =
                     {
                    // 'nro_parte': new Ext.form.TextField({
                    //     name: 'nro_parte',
                    //     msgTarget: 'title',
                    //     fieldLabel: 'Nro. Partes',
                    //     allowBlank: false,
                    //     width: 400,
                    //     maxLength:50
                    // }),

                    'nro_parte': new Ext.form.ComboBox({
                         name: 'nro_parte',
                         fieldLabel: 'Nro. Parte',
                         allowBlank: true,
                         emptyText: 'Part Number...',
                         store : new Ext.data.JsonStore({
                          url: '../../sis_gestion_materiales/control/Solicitud/obtenerCombosPartNumber',
                          id : 'id',
                          root : 'datos',
                          sortInfo : {
                            field : 'PN',
                            direction : 'ASC'
                          },
                          totalProperty: 'total',
                          fields: ['idproducto', 'idproductopn', 'pn', 'descripcion','tipoproducto', 'idtipoproducto', 'codigo_unidad_medida', 'idunidadmedida', 'reparable'],
                          remoteSort : true,
                          baseParams: {auto_complete: 'si'}
                        }),
                         valueField : 'pn',
                         displayField : 'pn',
                         gdisplayField : 'pn',
                         hiddenName : 'id',
                         forceSelection : true,
                         typeAhead : false,
                         triggerAction: 'all',
                         listWidth:'450',
                         lazyRender: true,
                         resizable:true,
                         mode: 'remote',
                         pageSize: 10,
                         queryDelay: 100,
                         width: 200,
                         gwidth: 200,
                         minChars: 2,
                         enableKeyEvents:true,
                         tpl: new Ext.XTemplate([
            								 '<tpl for=".">',
            								 '<div class="x-combo-list-item">',
            								 '<div>',
            								 '<p><b>Codigo: <span style="color: red;">{pn}</span></b></p>',
            								 '</div><p><b>Descripción:</b> <span style="color: blue;">{descripcion}</span></p>',
                             '<p><b>Unidad de Medida:</b> <span style="color: green;">{codigo_unidad_medida}</span></p>',
                             '<p><b>Tipo:</b> <span style="color: #38A78C;">{tipoproducto}</span></p>',
                             '<p><b>Reparable:</b> <span style="color: #5938A7;">{reparable}</span></p>',
            								 '</div></tpl>'
            						 ]),


                     }),



                    // 'nro_parte_alterno': new Ext.form.TextField({
                    //     name: 'nro_parte_alterno',
                    //     msgTarget: 'title',
                    //     fieldLabel: 'Nro. Parte alterno',
                    //     allowBlank: false,
                    //     width: 400,
                    //     maxLength:50
                    // }),

                    'nro_parte_alterno': new Ext.form.ComboBox({
                         name: 'id_concepto_ingas',
                         fieldLabel: 'Concepto',
                         allowBlank: true,
                         emptyText: 'Part Number Alterno...',
                         store : new Ext.data.JsonStore({
                          url: '../../sis_gestion_materiales/control/Solicitud/obtenerCombosPartNumber',
                          id : 'id',
                          root : 'datos',
                          sortInfo : {
                            field : 'PN',
                            direction : 'ASC'
                          },
                          totalProperty: 'total',
                          fields: ['idproducto', 'idproductopn', 'pn', 'descripcion','tipoproducto', 'idtipoproducto', 'codigo_unidad_medida', 'idunidadmedida', 'reparable'],
                          remoteSort : true,
                          baseParams: {auto_complete: 'si'}
                        }),
                         valueField : 'pn',
                         displayField : 'pn',
                         gdisplayField : 'pn',
                         hiddenName : 'id',
                         forceSelection : true,
                         typeAhead : false,
                         triggerAction: 'all',
                         listWidth:'450',
                         lazyRender: true,
                         resizable:true,
                         mode: 'remote',
                         pageSize: 10,
                         queryDelay: 100,
                         width: 200,
                         gwidth: 200,
                         minChars: 2,
                         enableKeyEvents:true,
                         tpl: new Ext.XTemplate([
            								 '<tpl for=".">',
            								 '<div class="x-combo-list-item">',
            								 '<div>',
            								 '<p><b>Codigo: <span style="color: red;">{pn}</span></b></p>',
            								 '</div><p><b>Descripción:</b> <span style="color: blue;">{descripcion}</span></p>',
                             '<p><b>Unidad de Medida:</b> <span style="color: green;">{codigo_unidad_medida}</span></p>',
                             '<p><b>Tipo:</b> <span style="color: #38A78C;">{tipoproducto}</span></p>',
                             '<p><b>Reparable:</b> <span style="color: #5938A7;">{reparable}</span></p>',
            								 '</div></tpl>'
            						 ]),


                     }),


                    'referencia': new Ext.form.TextField({
                        name: 'referencia',
                        msgTarget: 'title',
                        fieldLabel: 'Referencia',
                        allowBlank: true,
                        width: 400,
                        maxLength:50
                    }),

                    'serial': new Ext.form.TextField({
                        name: 'referencia',
                        msgTarget: 'title',
                        fieldLabel: 'Serial',
                        allowBlank: true,
                        width: 400,
                        maxLength:50
                    }),

                    'condicion_det': new Ext.form.ComboBox({
                         name: 'condicion_det',
                         fieldLabel: 'Condicion',
                         allowBlank: true,
                         emptyText: 'Condicion...',
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
            								cod_subsistema:'MAT',
            								catalogo_tipo:'tsolicitud'
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
            								 '<div>',
            								 '<p><b>Codigo: <span style="color: red;">{codigo}</span></b></p>',
            								 '</div><p><b>Descripción:</b> <span style="color: green;">{descripcion}</span></p>',
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
                     }),
                     /*Aumentando esta parte para que se registre directamente en el formulario*/
                     'id_centro_costo': new Ext.form.ComboRec({
                       origen:'CENTROCOSTO',
              				 allowBlank: true,
              				 gdisplayField:'desc_centro_costo',
              				 disabled: false,
              				 width: 200,
              				 gwidth: 200,
              				 fieldLabel: 'Centro de Costos',
              				 url: '../../sis_parametros/control/CentroCosto/listarCentroCostoFiltradoXDepto',
              				 baseParams: {filtrar: 'grupo_ep'},
                     }),

                     'id_concepto_ingas': new Ext.form.ComboBox({
                          name: 'id_concepto_ingas',
                          fieldLabel: 'Concepto',
                          allowBlank: true,
                          emptyText: 'Concepto...',
                          store : new Ext.data.JsonStore({
             							 url: '../../sis_parametros/control/ConceptoIngas/listarConceptoIngasMasPartida',
             							 id : 'id_concepto_ingas',
             							 root : 'datos',
             							 sortInfo : {
             								 field : 'desc_ingas',
             								 direction : 'ASC'
             							 },
             							 totalProperty : 'total',
             							 fields: ['id_concepto_ingas', 'tipo', 'desc_ingas', 'movimiento', 'desc_partida', 'id_grupo_ots', 'filtro_ot', 'requiere_ot'],
             							 remoteSort : true,
                           baseParams: {
                   												par_filtro: 'desc_ingas#par.codigo',
                   												movimiento: 'gasto',
                   												autorizacion: 'adquisiciones',
                   												gestion_materiales: 'si'
                   										}
             						 }),
                          valueField : 'id_concepto_ingas',
               						displayField : 'desc_ingas',
               						gdisplayField : 'desc_ingas',
               						hiddenName : 'id_concepto_ingas',
               						forceSelection : true,
               						typeAhead : false,
                          listeners: {
                  									  beforequery: function(qe){
                  										delete qe.combo.lastQuery;
                  									}
                  								},
                          tpl: '<tpl for="."><div class="x-combo-list-item"><p><b>{desc_ingas}</b></p><strong>{tipo}</strong><p>PARTIDA: {desc_partida}</p></div></tpl>',
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
                      }),
                      'id_orden_trabajo': new Ext.form.ComboRec({
                       sysorigen: 'sis_contabilidad',
                       origen: 'OT',
                       fieldLabel: 'Orden Trabajo',
               				 allowBlank: true,
               				 gdisplayField:'desc_orden_trabajo',
               				 disabled: false,
               				 width: 200,
               				 gwidth: 200,
               				 fieldLabel: 'Centro de Costos',
               				 //url: '../../sis_parametros/control/CentroCosto/listarCentroCostoFiltradoXDepto',
               				 baseParams:{par_filtro:'desc_orden#motivo_orden'},
                       listeners: {
               									  beforequery: function(qe){
               										delete qe.combo.lastQuery;
               									}
               								},
                      }),
                     /**************************************************************************/
                    'descripcion': new Ext.form.TextField({
                        name: 'descripcion',
                        msgTarget: 'title',
                        fieldLabel: 'Descripcion',
                        allowBlank: false,
                        width: 400,
                        maxLength:5000
                    }),
                         'explicacion_detallada_part': new Ext.form.TextArea({
                             name: 'explicacion_detallada_part',
                             msgTarget: 'title',
                             fieldLabel: 'Explicacion Detallada P/N',
                             allowBlank: false,
                             width: 400,
                             maxLength:5000
                         }),

                    // 'tipo': new Ext.form.ComboBox({
                    //
                    //     name:'tipo',
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

                    'tipo': new Ext.form.ComboBox({
                         name: 'tipo',
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

                    'cantidad_sol': new Ext.form.NumberField({
                        name: 'cantidad_sol',
                        msgTarget: 'title',
                        fieldLabel: 'Cantidad',
                        allowBlank: false,
                        allowDecimals: false,
                        maxLength:10
                    }),
                    'id_unidad_medida': new Ext.form.ComboBox({
                        name: 'id_unidad_medida',
                        fieldLabel: 'Unidad de medida',
                        allowBlank: false,
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
                            baseParams: {par_filtro: ' un.codigo# un.descripcion'}
                        }),
                        valueField: 'id_unidad_medida',
                        displayField: 'codigo',
                        gdisplayField: 'codigo',
                        hiddenName: 'id_unidad_medida',
                        forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        listWidth:300,
                        lazyRender: true,
                        resizable:true,
                        mode: 'remote',
                        pageSize: 50,
                        queryDelay: 100,
                        width: 400,
                        gwidth: 10,
                        minChars: 2,
                        tpl:'<tpl for="."><div class="x-combo-list-item"><p>{codigo}</p><p style="color: blue">{descripcion}</p></div></tpl>'

                    }),
                    'precio_unitario': new Ext.form.NumberField({
                        name: 'precio_unitario',
                        msgTarget: 'title',
                        fieldLabel: 'Precio Unitario',
                        allowBlank: true,
                        allowDecimals: true,
                        decimalPrecision : 2,
                        maxLength:1245186
                    }),
                    'precio_total': new Ext.form.NumberField({
                        name: 'precio_total',
                        msgTarget: 'title',
                        fieldLabel: 'Precio Total',
                        allowBlank: true,
                        allowDecimals: true,
                        decimalPrecision : 2,
                        maxLength:1245186
                    }),

                    'id_producto_alkym': new Ext.form.TextField({
                        name: 'id_producto_alkym',
                        msgTarget: 'title',
                        fieldLabel: 'id_producto_alkym',
                        allowBlank: false,
                        width: 0,
                        disabled: true,
                        maxLength:5000
                    }),
                }
        },
        arrayStore :{
            'Gerencia de Operaciones':[
                ['Gerencia de Operaciones'],

            ],
            'Gerencia de Mantenimiento':[
                ['Gerencia de Mantenimiento'],
            ],
            'Centro de Entrenamiento Aeronautico Civil':[
                ['Centro de Entrenamiento Aeronautico Civil'],
            ]
        },

        iniciarEventos : function () {
            this.Cmp.fecha_solicitud.on('change',function(f){
                 Phx.CP.loadingShow();
                this.obtenerGestion(this.Cmp.fecha_solicitud);
                this.Cmp.id_funcionario_solicitante.reset();
                this.Cmp.id_funcionario_solicitante.enable();
                this.Cmp.id_funcionario_solicitante.store.baseParams.fecha = this.Cmp.fecha_solicitud.getValue().dateFormat(this.Cmp.fecha_solicitud.format);
            },this);

            /*Aumentando para que el campo Obsevaciones tenga una leyenda por defecto (Ismael Valdivia 26/10/2021)*/
            this.Cmp.observaciones_sol.setValue("La entrega del producto es en Forwarder Miami, de acuerdo a oferta del proveedor, después de la notificación del Purchase Order, sin embargo el ingreso al almacén Cochabamba será hasta la fecha requerida de llegada.");
            /******************************************************************************************************/

            /*Aumentando para que la moneda este por defecto llenado (Ismael Valdivia 18/02/2020)*/
            this.Cmp.id_moneda.store.load({params:{start:0,limit:50},
               callback : function (r) {
                     this.Cmp.id_moneda.setValue(r[0].data.id_moneda);
                     this.Cmp.id_moneda.fireEvent('select',this.Cmp.id_moneda,r[0].data.id_moneda);

                }, scope : this
            });
            /********************************************************/

            this.ocultarComponente(this.Cmp.id_funcionario_solicitante);
            this.Cmp.id_funcionario_solicitante.reset();

            /*Aumentando para que nos muestre el cmapo del MEL Observacion Ismael Valdivia 01/10/2020*/
            this.Cmp.mel.on('select', function (c,r,i) {
              if(this.Cmp.mel.getValue() == 'A' || this.Cmp.mel.getValue() == 'Otro' || this.Cmp.mel.getValue() == 'otro' || this.Cmp.mel.getValue() == 'OTRO'){
                this.mostrarComponente(this.Cmp.mel_observacion);
              } else {
                this.Cmp.mel_observacion.reset();
                this.ocultarComponente(this.Cmp.mel_observacion);
              }
            },this);
            /*****************************************************************************************/

            /*Aumentando para completar data*/
            this.detCmp.nro_parte.on('select', function (cmp, rec) {

              this.detCmp.id_unidad_medida.reset();
              this.detCmp.tipo.reset();
              this.detCmp.descripcion.reset();
              this.detCmp.id_producto_alkym.reset();

              this.detCmp.id_producto_alkym.setValue(rec.data.idproducto);

              this.detCmp.descripcion.setValue(rec.data.descripcion);
              console.log("aqui llega el rec seleccionado PN",rec);
              this.detCmp.id_unidad_medida.store.baseParams.id_unidad_medida = rec.data.idunidadmedida;

              this.detCmp.id_unidad_medida.store.load({params:{start:0,limit:50},
                     callback : function (r) {
                          if (r.length == 1 ) {
                                this.detCmp.id_unidad_medida.setValue(r[0].data.id_unidad_medida);
                                this.detCmp.id_unidad_medida.fireEvent('select', this.detCmp.id_unidad_medida,r[0],0);
                                this.detCmp.id_unidad_medida.store.baseParams.id_unidad_medida = '';
                            } else {
                              this.detCmp.id_unidad_medida.store.baseParams.id_unidad_medida = '';
                            }
                      }, scope : this
                  });

              this.detCmp.tipo.store.load({params:{start:0,limit:50},
                     callback : function (r) {
                           for (var i = 0; i < r.length; i++) {
                             if (r[i].data.descripcion == rec.data.tipoproducto) {
                               this.detCmp.tipo.setValue(r[i].data.descripcion);
                               this.detCmp.tipo.fireEvent('select', this.detCmp.tipo,r[i]);
                             }
                           }
                           console.log("aqui llega el tipo",r);
                          // if (r.length == 1 ) {
                          //       this.detCmp.tipo.setValue(r[0].data.tipo);
                          //       this.detCmp.tipo.fireEvent('select', this.detCmp.tipo,r[0],0);
                          //   }
                      }, scope : this
                  });

              this.detCmp.id_concepto_ingas.store.load({params:{start:0,limit:50},
                     callback : function (r) {
                       console.log("aqui para el concepto",r);
                       for (var i = 0; i < r.length; i++) {
                         if (r[i].data.desc_ingas.toLowerCase().includes(rec.data.tipoproducto.toLowerCase())) {
                           this.detCmp.id_concepto_ingas.setValue(r[i].data.id_concepto_ingas);
                           this.detCmp.id_concepto_ingas.fireEvent('select', this.detCmp.id_concepto_ingas,r[i]);
                         }
                       }
                      }, scope : this
                  });

            }, this);
            /********************************/


            this.Cmp.origen_pedido.on('select',function(cmp,rec){

                //identificamos si es un bien o un servicio
                if(this.isInArray(rec.json, this.arrayStore['Gerencia de Operaciones'])){
                    this.Cmp.origen_pedido.setValue('Gerencia de Operaciones');
                }
                if(this.Cmp.origen_pedido.getValue() == 'Gerencia de Operaciones'){
                  this.detCmp.id_unidad_medida.store.baseParams.repuestos = '';
                    /*Ocultando los nuevos campos para repuestos*/
                    this.ocultarComponente(this.Cmp.nro_po);
                    //this.ocultarComponente(this.Cmp.fecha_po);
                    this.ocultarComponente(this.Cmp.nro_lote);
                    this.Cmp.nro_po.allowBlank = true;
                    //this.Cmp.fecha_po.allowBlank = true;
                    this.Cmp.nro_lote.allowBlank = true;
                    /********************************************/
                    this.detCmp.id_centro_costo.store.baseParams.id_centro_costo = '';

                     this.mostrarComponente(this.Cmp.mel);
                     this.ocultarComponente(this.Cmp.tipo_reporte);
                     this.ocultarComponente(this.Cmp.tipo_falla);
                     //this.ocultarComponente(this.Cmp.nro_justificacion);
                     this.mostrarComponente(this.Cmp.id_matricula);
                     //this.mostrarComponente(this.Cmp.justificacion);
                     //this.mostrarComponente(this.Cmp.id_matricula);
                     this.ocultarComponente(this.Cmp.id_forma_pago);
                     this.ocultarComponente(this.Cmp.id_condicion_entrega);
                     /*Aqui mostramos el nro_justificacion y la justificacion y ocultamos el nro no.rutina*/
                     this.mostrarComponente(this.Cmp.nro_justificacion);
                     this.mostrarComponente(this.Cmp.justificacion);
                     this.mostrarComponente(this.Cmp.nro_no_rutina);

                     this.Cmp.nro_no_rutina.allowBlank = false;
                     this.Cmp.justificacion.allowBlank = false;
                     this.Cmp.nro_justificacion.allowBlank = false;

                     this.ocultarComponente(this.Cmp.dias_entrega_estimado);
                     this.Cmp.dias_entrega_estimado.allowBlank = true;
                     this.Cmp.dias_entrega_estimado.reset();
                     /************************************************************************************/

                    // this.Cmp.justificacion.allowBlank = false;
                    // this.Cmp.nro_justificacion.allowBlank = false;
                    this.Cmp.tipo_solicitud.allowBlank = false;
                    this.Cmp.mel.allowBlank = false;
                    this.Cmp.id_forma_pago.allowBlank = true;
                    this.Cmp.id_condicion_entrega.allowBlank = true;
                    //this.Cmp.mel.allowBlank = false;

                    /*Aumentando para que el campo sea dinamico el campo serial y referencia (Ismael Valdivia 16/03/2020)*/
                    this.megrid.colModel.setHidden(3,false);
                    this.megrid.colModel.setHidden(4,true);
                    this.megrid.colModel.setHidden(5,true);
                    this.megrid.colModel.setHidden(6,true);
                    this.megrid.colModel.setHidden(7,true);
                    this.megrid.colModel.setHidden(8,true);
                    this.megrid.colModel.setHidden(13,true);
                    this.megrid.colModel.setHidden(14,true);
                    /*****************************************************************************************************/
                    this.Cmp.nro_justificacion.reset();
                    this.Cmp.justificacion.reset();
                    this.Cmp.nro_no_rutina.reset();
                    this.Cmp.motivo_solicitud.reset();
                    this.Cmp.nro_po.reset();
                    //this.Cmp.fecha_po.reset();
                    this.Cmp.nro_lote.reset();
                    Ext.getCmp('justificacion_necesidad_form').show();
                 }

                 if(this.isInArray(rec.json, this.arrayStore['Gerencia de Mantenimiento']) || this.isInArray(rec.json, this.arrayStore['Gerencia de Operaciones DGAC'])){
                    this.Cmp.origen_pedido.setValue('Gerencia de Mantenimiento'); this.Cmp.origen_pedido.setValue('Gerencia de Operaciones DGAC');
                }
                if(this.Cmp.origen_pedido.getValue() == 'Gerencia de Mantenimiento'){
                  this.detCmp.id_unidad_medida.store.baseParams.repuestos = '';
                  /*Ocultando los nuevos campos para repuestos*/
                  this.ocultarComponente(this.Cmp.nro_po);
                  //this.ocultarComponente(this.Cmp.fecha_po);
                  this.ocultarComponente(this.Cmp.nro_lote);
                  this.Cmp.nro_po.allowBlank = true;
                  //this.Cmp.fecha_po.allowBlank = true;
                  this.Cmp.nro_lote.allowBlank = true;
                  /********************************************/
                  this.detCmp.id_centro_costo.store.baseParams.id_centro_costo = '';

                    this.mostrarComponente(this.Cmp.mel);
                    this.mostrarComponente(this.Cmp.tipo_reporte);
                    this.mostrarComponente(this.Cmp.tipo_falla);
                    //this.ocultarComponente(this.Cmp.nro_justificacion);
                    this.mostrarComponente(this.Cmp.justificacion);
                    this.mostrarComponente(this.Cmp.id_matricula);

                    /*Aqui mostramos el nro_justificacion y la justificacion y ocultamos el nro no.rutina*/
                    this.ocultarComponente(this.Cmp.nro_justificacion);
                    this.mostrarComponente(this.Cmp.justificacion);
                    this.mostrarComponente(this.Cmp.nro_no_rutina);

                    this.ocultarComponente(this.Cmp.id_forma_pago);
                    this.ocultarComponente(this.Cmp.id_condicion_entrega);

                    this.Cmp.nro_no_rutina.allowBlank = false;
                    this.Cmp.justificacion.allowBlank = true;
                    this.Cmp.nro_justificacion.allowBlank = true;
                    /************************************************************************************/

                    this.Cmp.justificacion.allowBlank = false;
                    //this.Cmp.nro_justificacion.allowBlank = false;
                    this.Cmp.tipo_solicitud.allowBlank = false;
                    this.Cmp.mel.allowBlank = false;
                    this.Cmp.id_forma_pago.allowBlank = true;
                    this.Cmp.id_condicion_entrega.allowBlank = true;

                    this.ocultarComponente(this.Cmp.dias_entrega_estimado);
                    this.Cmp.dias_entrega_estimado.allowBlank = true;
                    this.Cmp.dias_entrega_estimado.reset();
                    //this.Cmp.mel.allowBlank = false;
                    /*Aumentando para que el campo sea dinamico el campo serial y referencia (Ismael Valdivia 16/03/2020)*/
                    this.megrid.colModel.setHidden(3,false);
                    this.megrid.colModel.setHidden(4,true);
                    this.megrid.colModel.setHidden(5,true);
                    this.megrid.colModel.setHidden(6,true);
                    this.megrid.colModel.setHidden(7,true);
                    this.megrid.colModel.setHidden(8,true);
                    this.megrid.colModel.setHidden(13,true);
                    this.megrid.colModel.setHidden(14,true);
                    /*****************************************************************************************************/
                    this.Cmp.nro_justificacion.reset();
                    this.Cmp.justificacion.reset();
                    this.Cmp.nro_no_rutina.reset();
                    this.Cmp.motivo_solicitud.reset();
                    this.Cmp.nro_po.reset();
                    //this.Cmp.fecha_po.reset();
                    this.Cmp.nro_lote.reset();
                    Ext.getCmp('justificacion_necesidad_form').show();
                }

                if(this.isInArray(rec.json, this.arrayStore['Almacenes Consumibles o Rotables'])){
                    this.Cmp.origen_pedido.setValue('Almacenes Consumibles o Rotables');
                }
                if(this.Cmp.origen_pedido.getValue() == 'Almacenes Consumibles o Rotables'){
                  this.detCmp.id_unidad_medida.store.baseParams.repuestos = '';
                  /*Ocultando los nuevos campos para repuestos*/
                  this.ocultarComponente(this.Cmp.nro_po);
                  //this.ocultarComponente(this.Cmp.fecha_po);
                  this.ocultarComponente(this.Cmp.nro_lote);
                  this.Cmp.nro_po.allowBlank = true;
                  //this.Cmp.fecha_po.allowBlank = true;
                  this.Cmp.nro_lote.allowBlank = true;
                  /********************************************/
                  this.detCmp.id_centro_costo.store.baseParams.id_centro_costo = '';

                    this.mostrarComponente(this.Cmp.mel);
                    this.ocultarComponente(this.Cmp.tipo_reporte);
                    this.ocultarComponente(this.Cmp.tipo_falla);
                    // this.ocultarComponente(this.Cmp.nro_justificacion);
                    // this.ocultarComponente(this.Cmp.justificacion);
                    this.ocultarComponente(this.Cmp.id_matricula);
                    //this.mostrarComponente(this.Cmp.id_matricula);
                    //this.Cmp.id_matricula.allowBlank = false;

                    /*Aqui mostramos el nro_justificacion y la justificacion y ocultamos el nro no.rutina*/
                    //this.mostrarComponente(this.Cmp.nro_justificacion);
                    this.ocultarComponente(this.Cmp.justificacion);
                    this.ocultarComponente(this.Cmp.nro_no_rutina);
                    this.ocultarComponente(this.Cmp.id_forma_pago);
                    this.ocultarComponente(this.Cmp.id_condicion_entrega);

                    this.Cmp.nro_no_rutina.allowBlank = true;
                    /************************************************************************************/

                    this.Cmp.justificacion.allowBlank = true;
                    //this.Cmp.nro_justificacion.allowBlank = false;
                    this.Cmp.tipo_solicitud.allowBlank = false;
                    this.Cmp.mel.allowBlank = false;
                    this.Cmp.id_forma_pago.allowBlank = true;
                    this.Cmp.id_condicion_entrega.allowBlank = true;

                    this.ocultarComponente(this.Cmp.dias_entrega_estimado);
                    this.Cmp.dias_entrega_estimado.allowBlank = true;
                    this.Cmp.dias_entrega_estimado.reset();


                    /*Aumentando para que el campo sea dinamico el campo serial y referencia (Ismael Valdivia 16/03/2020)*/
                    this.megrid.colModel.setHidden(3,false);
                    this.megrid.colModel.setHidden(4,true);
                    this.megrid.colModel.setHidden(5,true);
                    this.megrid.colModel.setHidden(6,true);
                    this.megrid.colModel.setHidden(7,true);
                    this.megrid.colModel.setHidden(8,true);
                    this.megrid.colModel.setHidden(13,true);
                    this.megrid.colModel.setHidden(14,true);
                    /*****************************************************************************************************/
                    this.Cmp.id_matricula.setValue(null);
                    this.Cmp.nro_justificacion.reset();
                    this.Cmp.justificacion.reset();
                    this.Cmp.nro_no_rutina.reset();
                    this.Cmp.nro_po.reset();
                    //this.Cmp.fecha_po.reset();
                    this.Cmp.nro_lote.reset();
                    this.Cmp.motivo_solicitud.setValue('Compra a fin de proceder con la reposición de stock mínimo considerando los puntos mínimos según reporte de sistema ALKYM, a fin de atender requerimientos para el mantenimiento de la flota de aviones de BoA');
                    Ext.getCmp('justificacion_necesidad_form').show();
                }
                if( this.isInArray(rec.json, this.arrayStore['Centro de Entrenamiento Aeronautico Civil'])){
                    this.Cmp.origen_pedido.setValue('Centro de Entrenamiento Aeronautico Civil');
                }
                if(this.Cmp.origen_pedido.getValue() == 'Centro de Entrenamiento Aeronautico Civil'){
                    this.detCmp.id_unidad_medida.store.baseParams.repuestos = '';
                    /*Ocultando los nuevos campos para repuestos*/
                    this.ocultarComponente(this.Cmp.nro_po);
                  //  this.ocultarComponente(this.Cmp.fecha_po);
                    this.ocultarComponente(this.Cmp.nro_lote);
                    this.Cmp.nro_po.allowBlank = true;
                    //this.Cmp.fecha_po.allowBlank = true;
                    this.Cmp.nro_lote.allowBlank = true;
                    /********************************************/

                    this.detCmp.id_centro_costo.store.baseParams.id_centro_costo = '';

                    this.mostrarComponente(this.Cmp.mel);
                    this.ocultarComponente(this.Cmp.tipo_reporte);
                    this.ocultarComponente(this.Cmp.tipo_falla);
                    this.ocultarComponente(this.Cmp.nro_justificacion);
                  //  this.ocultarComponente(this.Cmp.justificacion);
                    this.ocultarComponente(this.Cmp.id_matricula);

                    /*Aqui mostramos el nro_justificacion y la justificacion y ocultamos el nro no.rutina*/
                    //this.mostrarComponente(this.Cmp.nro_justificacion);
                    this.mostrarComponente(this.Cmp.justificacion);
                    this.ocultarComponente(this.Cmp.nro_no_rutina);

                    this.ocultarComponente(this.Cmp.id_forma_pago);
                    this.ocultarComponente(this.Cmp.id_condicion_entrega);

                    this.Cmp.nro_no_rutina.allowBlank = true;
                    /************************************************************************************/

                    this.Cmp.justificacion.allowBlank = false;



                    this.Cmp.justificacion.allowBlank = false;
                    this.Cmp.nro_justificacion.allowBlank = false;
                    this.Cmp.tipo_solicitud.allowBlank = false;
                    this.Cmp.mel.allowBlank = false;
                    this.Cmp.id_forma_pago.allowBlank = true;
                    this.Cmp.id_condicion_entrega.allowBlank = true;

                    this.ocultarComponente(this.Cmp.dias_entrega_estimado);
                    this.Cmp.dias_entrega_estimado.allowBlank = true;
                    this.Cmp.dias_entrega_estimado.reset();

                    /*Aumentando para que el campo sea dinamico el campo serial y referencia (Ismael Valdivia 16/03/2020)*/
                    this.megrid.colModel.setHidden(3,false);
                    this.megrid.colModel.setHidden(4,true);
                    this.megrid.colModel.setHidden(5,true);
                    this.megrid.colModel.setHidden(6,true);
                    this.megrid.colModel.setHidden(7,true);
                    this.megrid.colModel.setHidden(8,true);
                    this.megrid.colModel.setHidden(13,true);
                    this.megrid.colModel.setHidden(14,true);
                    /*****************************************************************************************************/
                    this.Cmp.id_matricula.setValue(null);
                    this.Cmp.nro_justificacion.reset();
                    this.Cmp.justificacion.reset();
                    this.Cmp.nro_no_rutina.reset();
                    this.Cmp.motivo_solicitud.reset();
                    this.Cmp.nro_po.reset();
                    //this.Cmp.fecha_po.reset();
                    this.Cmp.nro_lote.reset();
                    Ext.getCmp('justificacion_necesidad_form').show();
                }
                /*Aumentando para repuestos (Ismael Valdivia 12/03/2020)*/
                if(this.Cmp.origen_pedido.getValue() == 'Reparación de Repuestos'){
                  /*Aqui iremos mostrando los componentes que se deben ocultar*/
                  //this.form.bwrap.dom.firstChild[16].labels[0].innerHTML = 'HOLA PRUEBA';
                  //this.form.bwrap.dom.firstChild[16].labels[0].innerText = 'HOLA PRUEBA';
                  this.detCmp.id_unidad_medida.store.baseParams.repuestos = 'filtro_boa_rep';

                  console.log("aqui llega la gestion para filtrar el OT",this);

                  Ext.Ajax.request({
                      url:'../../sis_gestion_materiales/control/Solicitud/getCentroCostoDefecto',
                      params:{id_gestion: this.data.objPadre.store.baseParams.id_gestion},
                      success:function(resp){
                          var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                          this.detCmp.id_centro_costo.store.baseParams.id_centro_costo = reg.ROOT.datos.id_centro_costo;
                          /************************************************************************************/

                      },
                      failure: this.conexionFailure,
                      timeout:this.timeout,
                      scope:this
                  });


                  this.ocultarComponente(this.Cmp.tipo_reporte);
                  this.ocultarComponente(this.Cmp.tipo_falla);
                  this.ocultarComponente(this.Cmp.nro_justificacion);
                  this.ocultarComponente(this.Cmp.justificacion);
                //  this.ocultarComponente(this.Cmp.id_matricula);
                  this.ocultarComponente(this.Cmp.nro_no_rutina);
                  this.ocultarComponente(this.Cmp.mel);
                  this.ocultarComponente(this.Cmp.fecha_requerida);
                  this.ocultarComponente(this.Cmp.motivo_solicitud);
                  /************************************************************/
                  /*Aqui permitimos que los campos ocultos permitan datos en blanco*/
                  this.Cmp.tipo_reporte.allowBlank = true;
                  this.Cmp.tipo_falla.allowBlank = true;
                  this.Cmp.nro_justificacion.allowBlank = true;
                  this.Cmp.justificacion.allowBlank = true;
                  this.Cmp.id_matricula.allowBlank = true;
                  this.Cmp.nro_no_rutina.allowBlank = true;
                  this.Cmp.mel.allowBlank = true;
                  this.Cmp.fecha_requerida.allowBlank = true;
                  this.Cmp.motivo_solicitud.allowBlank = true;
                  /*****************************************************************/
                  /*Aqui ponemos los datos que se mostraran para su respectivo registro*/
                  this.mostrarComponente(this.Cmp.nro_po);
                  //this.mostrarComponente(this.Cmp.fecha_po);
                  this.mostrarComponente(this.Cmp.nro_lote);
                  this.mostrarComponente(this.Cmp.id_forma_pago);
                  this.mostrarComponente(this.Cmp.id_condicion_entrega);
                  this.Cmp.nro_po.allowBlank = false;
                  //this.Cmp.fecha_po.allowBlank = false;
                  this.Cmp.nro_lote.allowBlank = false;
                  this.Cmp.id_forma_pago.allowBlank = false;
                  this.Cmp.id_condicion_entrega.allowBlank = false;

                  //this.mostrarComponente(this.Cmp.dias_entrega_estimado);
                  //this.Cmp.dias_entrega_estimado.allowBlank = false;

                  this.Cmp.id_condicion_entrega.on('select', function (c,r,i) {
                    this.Cmp.codigo_condicion_entrega.setValue(r.data.nombre);
                  },this);

                  this.Cmp.id_forma_pago.on('select', function (c,r,i) {
                    this.Cmp.codigo_forma_pago.setValue(r.data.nombre);
                  },this);
                  /*********************************************************************/

                  this.Cmp.id_matricula.on('select', function (c,r,i) {
                    this.detCmp.id_orden_trabajo.store.baseParams.id_orden_trabajo = this.Cmp.id_matricula.getValue();
                  },this);

                  /*Aumentando para que el campo sea dinamico el campo serial y referencia (Ismael Valdivia 16/03/2020)*/
                  this.megrid.colModel.setHidden(3,true);
                  this.megrid.colModel.setHidden(4,false);
                  this.megrid.colModel.setHidden(5,false);
                  this.megrid.colModel.setHidden(6,false);
                  this.megrid.colModel.setHidden(7,false);
                  this.megrid.colModel.setHidden(8,false);
                  this.megrid.colModel.setHidden(13,false);
                  this.megrid.colModel.setHidden(14,false);
                  /*****************************************************************************************************/
                  this.Cmp.id_matricula.setValue(null);
                  this.Cmp.tipo_reporte.reset();
                  this.Cmp.tipo_falla.reset();
                  this.Cmp.nro_justificacion.reset();
                  this.Cmp.justificacion.reset();
                  this.Cmp.id_matricula.reset();
                  this.Cmp.nro_no_rutina.reset();
                  this.Cmp.mel.reset();
                  this.Cmp.fecha_requerida.reset();



                  this.detCmp.id_centro_costo.on('select', function (cmp, rec) {
                    this.detCmp.id_orden_trabajo.store.baseParams.id_centro_costo = rec.data.id_centro_costo;
                    console.log("aqui hace el Select para filtro",rec.data.id_centro_costo);
                  }, this);

                  this.detCmp.id_orden_trabajo.on('select', function (cmp, rec) {
                    this.detCmp.id_concepto_ingas.store.baseParams.id_grupo_ots = rec.json.id_grupo_ots;
          					this.detCmp.id_concepto_ingas.reset();
                  }, this);

                  this.detCmp.precio_unitario.on('blur', function (cmp, rec) {
            				var cantidad = this.detCmp.cantidad_sol.getValue();
            				var precio_uni = this.detCmp.precio_unitario.getValue();
            				this.detCmp.precio_total.setValue(cantidad*precio_uni);
            			}, this);

            			this.detCmp.cantidad_sol.on('blur', function (cmp, rec) {
            				var cantidad = this.detCmp.cantidad_sol.getValue();
            				var precio_uni = this.detCmp.precio_unitario.getValue();
            				this.detCmp.precio_total.setValue(cantidad*precio_uni);
            			}, this);




                }
                /********************************************************/
            },this);


            this.mostrarComponente(this.Cmp.mel);
            this.ocultarComponente(this.Cmp.tipo_reporte);
            this.ocultarComponente(this.Cmp.tipo_falla);
            this.ocultarComponente(this.Cmp.nro_justificacion);

            this.Cmp.justificacion.on('select',function(cmp,rec){
                if(this.Cmp.justificacion.getValue() == 'Task Card'){
                    this.mostrarComponente(this.Cmp.nro_justificacion);
                }
               else if(this.Cmp.justificacion.getValue() == 'Directriz de Aeronavegabilidad'){
                    this.mostrarComponente(this.Cmp.nro_justificacion);
                }
               else if(this.Cmp.justificacion.getValue() == 'Boletín de Servicio'){
                    this.mostrarComponente(this.Cmp.nro_justificacion);
                }
                else{
                    this.ocultarComponente(this.Cmp.nro_justificacion);
                }
            },this);

            this.Cmp.id_funcionario_solicitante.on('select', function (combo, record, index) {

                if (!record.data.id_lugar) {
                    alert('El funcionario no tiene oficina definida');
                    return
                }
                this.Cmp.id_depto.reset();
                this.Cmp.id_depto.store.baseParams.id_lugar = record.data.id_lugar;
                //this.Cmp.id_depto.store.baseParams.id_funcionario_solicitante = reg.ROOT.datos.id_funcionario;

                this.Cmp.id_depto.modificado = true;
                this.Cmp.id_depto.enable();

                this.Cmp.id_depto.store.load({
                    params: {start: 0, limit: this.tam_pag},
                    callback: function (r) {
                        if (r.length == 1) {
                            this.Cmp.id_depto.setValue(r[0].data.id_depto);
                        }
                        if (r.length > 1) {
                            this.mostrarComponente(this.Cmp.id_funcionario_solicitante);
                            this.mostrarComponente(this.Cmp.id_depto);
                        }

                    }, scope: this
                });


            }, this);


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

            this.megrid.topToolbar.el.dom.style.background="#03A27C";
            //cabezera grid
            this.megrid.body.dom.childNodes[0].firstChild.children[0].firstChild.style.background='#E9E9E9';
            this.megrid.body.dom.childNodes[0].firstChild.children[1].offsetParent.style.background='#F6F6F6';

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
        bloqueaRequisitos: function(sw){
            /*Aumentando esto Ismael Valdivia (31/01/2020)*/
            this.Cmp.id_depto.setDisabled(sw);
            /******************/
            this.Cmp.id_funcionario_solicitante.setDisabled(sw);
        },

        evaluaGrilla: function(){
            //al eliminar si no quedan registros en la grilla desbloquea los requisitos en el maestro
            var  count = this.mestore.getCount();
            if(count == 0){
                this.bloqueaRequisitos(false);
            }
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
        onNew: function(){

            this.mostrarComponente(this.Cmp.id_funcionario_solicitante);
            this.Cmp.id_funcionario_solicitante.reset();
            this.Cmp.fecha_solicitud.enable();
            this.Cmp.id_funcionario_solicitante.disable();
            this.Cmp.fecha_solicitud.setValue(new Date());
            this.Cmp.fecha_solicitud.fireEvent('change');
            /*Aumentando esta parte para el funcionario solicitante*/
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/getDatos',
                params:{id_usuario: 0},
                success:function(resp){
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

                    this.Cmp.id_funcionario_solicitante.setValue(reg.ROOT.datos.id_funcionario);
                    this.Cmp.id_funcionario_solicitante.setRawValue(reg.ROOT.datos.nombre_completo1);

                    /*Aumentando esta parte para recuperar el departamento (Ismael Valdivia 31/01/2020)*/
                    this.Cmp.id_depto.store.baseParams.id_lugar = reg.ROOT.datos.id_lugar;
                    this.Cmp.id_depto.store.baseParams.id_funcionario_solicitante = reg.ROOT.datos.id_funcionario;


                    this.Cmp.id_depto.store.load({params:{start:0,limit:this.tam_pag},
                        callback : function (r) {
                            if (r.length == 1 ) {
                            this.Cmp.id_depto.setValue(r[0].data.id_depto);
                            /*Aumentando para el centro de costos para los BoA Rep*/
                            this.detCmp.id_centro_costo.store.baseParams.id_gestion = this.data.objPadre.store.baseParams.id_gestion;
                            this.detCmp.id_centro_costo.store.baseParams.id_funcionario_solicitante = reg.ROOT.datos.id_funcionario;
                            this.detCmp.id_concepto_ingas.store.baseParams.id_gestion = this.data.objPadre.store.baseParams.id_gestion;
                            this.detCmp.id_concepto_ingas.modificado = true;
                            this.detCmp.id_centro_costo.store.baseParams.id_depto = r[0].data.id_depto;
                            this.detCmp.id_orden_trabajo.store.baseParams.fecha_solicitud = this.Cmp.fecha_solicitud.getValue().dateFormat('d/m/Y');
                            this.detCmp.id_orden_trabajo.modificado = true;
                            /******************************************************/
                            }
                            if (r.length > 1) {
                              alert('El funcionario solicitante tiene mas de 1 departamento Porfavor seleccione un departamento');
                              this.mostrarComponente(this.Cmp.id_funcionario_solicitante);
                              this.mostrarComponente(this.Cmp.id_depto);
                            }

                          }, scope : this
                        });
                    this.Cmp.id_depto.enable();
                  /************************************************************************************/

                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
            /*****************************************************************************************/
            /*Aumentando esta parte para el tecnico solicitante*/
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/getDatosTecnico',
                params:{id_usuario: 0},
                success:function(resp){
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

                    this.Cmp.id_funcionario_sol.setValue(reg.ROOT.datos.id_funcionario);
                    this.Cmp.id_funcionario_sol.setRawValue(reg.ROOT.datos.nombre_completo1);
                  /************************************************************************************/

                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
            /*****************************************************************************************/

        },


        onInitAdd: function(){

        },
        onCancelAdd: function(re,save){
            if(this.sw_init_add){
                this.mestore.remove(this.mestore.getAt(0));
            }
            this.sw_init_add = false;
           this.evaluaGrilla();

        },
        onUpdateRegister: function(){
            this.sw_init_add = false;
        },

        onAfterEdit:function(re, o, rec, num){
            //set descriptins values ...  in combos boxs

            var cmb_rec = this.detCmp['id_unidad_medida'].store.getById(rec.get('id_unidad_medida'));
            if(cmb_rec) {

                rec.set('codigo', cmb_rec.get('codigo'));
            }

            var cmb_rec = this.detCmp['id_centro_costo'].store.getById(rec.get('id_centro_costo'));
            if(cmb_rec) {
                rec.set('desc_centro_costo', cmb_rec.get('codigo_cc'));
            }

            var cmb_rec = this.detCmp['id_concepto_ingas'].store.getById(rec.get('id_concepto_ingas'));
            if(cmb_rec) {
                rec.set('desc_concepto_ingas', cmb_rec.get('desc_ingas'));
            }

            var cmb_rec = this.detCmp['id_orden_trabajo'].store.getById(rec.get('id_orden_trabajo'));
            if(cmb_rec) {
                rec.set('desc_orden_trabajo', cmb_rec.get('desc_orden'));
            }


        },
        buildDetailGrid:function () {
            var Items = Ext.data.Record.create([{
                name: 'cantidad_sol',
                type: 'int'
            }
            ]);
            this.mestore = new Ext.data.JsonStore({
                url: '../../sis_gestion_materiales/control/DetalleSol/listarDetalleSol',
                id: 'id_detalle',
                root: 'datos',
                totalProperty: 'total',
                fields: ['id_detalle','id_solicitud','precio', 'cantidad_sol',
                    'id_unidad_medida','descripcion','nro_parte_alterno','moneda','referencia',
                    'nro_parte','codigo','desc_descripcion','tipo','explicacion_detallada_part,condicion_det,id_centro_costo,id_concepto_ingas,id_orden_trabajo,precio_unitario,precio_total'
                ],remoteSort: true,
                baseParams: {dir:'ASC',sort:'id_detalle',limit:'100',start:'0'}
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
            this.megrid = new Ext.grid.GridPanel({
                layout: 'fit',
                store:  this.mestore,
                region: 'center',
                split: true,
                border: false,
                plain: true,
                plugins: [ this.editorDetaille],
                stripeRows: true,
                tbar: [{
                    text: '<div style="font-weight:bold; font-size:15px; color:black;"><img src="../../../lib/imagenes/facturacion/anadir.png" style="width:30px; vertical-align: middle;"> Agregar Detalle</div>',
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
                            this.bloqueaRequisitos(true);

                            this.detCmp.id_centro_costo.store.load({params:{start:0,limit:50},
                               callback : function (r) {
                               		this.detCmp.id_centro_costo.setValue(this.detCmp.id_centro_costo.store.baseParams.id_centro_costo);
                                  this.detCmp.id_centro_costo.fireEvent('select',this.detCmp.id_centro_costo, this.detCmp.id_centro_costo.store.getById(this.detCmp.id_centro_costo.store.baseParams.id_centro_costo));
                                  // console.log("aqui llega el dato cabecera",this.Cmp.id_matricula.getValue());
                                  // this.detCmp.id_orden_trabajo.setValue(this.Cmp.id_matricula.getValue());
                               		// this.detCmp.id_orden_trabajo.fireEvent('select',this.detCmp.id_orden_trabajo, this.detCmp.id_centro_costo.store.getById(this.Cmp.id_matricula.getValue()));

                                  /*Aqui para el defecto*/

                                  if (this.Cmp.id_matricula.getValue() != '') {
                                    this.detCmp.id_orden_trabajo.store.baseParams.id_orden_trabajo = this.Cmp.id_matricula.getValue();
                                  }

                                  /*Aumentando para filtrar el CP de la matricula*/
                                    this.detCmp.id_orden_trabajo.store.load({params:{start:0,limit:100},
                                       callback : function (r) {
                                         if (r.length == 1 ) {
                                               this.detCmp.id_orden_trabajo.setValue(r[0].data.id_orden_trabajo);
                                               this.detCmp.id_orden_trabajo.fireEvent('select', this.detCmp.id_orden_trabajo,r[0],0);
                                               this.detCmp.id_orden_trabajo.store.baseParams.id_orden_trabajo = '';
                                           } else {
                                             this.detCmp.id_orden_trabajo.store.baseParams.id_orden_trabajo = '';

                                           }
                                         }, scope : this
                                    });
                                  /***********************************************/


                                }, scope : this
                            });


                        }
                      else{
                          //alert('Verifique los requisitos');
                      }
                    }
                },{
                    ref: '../removeBtn',
                    text: '<div style="font-weight:bold; font-size:15px; color:black;"><img src="../../../lib/imagenes/facturacion/eliminar.png" style="width:30px; vertical-align: middle;"> Eliminar</div>',
                    scope:this,
                    id:'botonEliminar',
                    handler: function(){
                        this.editorDetaille.stopEditing();
                        var s = this.megrid.getSelectionModel().getSelections();
                        for(var i = 0, r; r = s[i]; i++){
                            this.mestore.remove(r);
                        }
                        this.evaluaGrilla();
                    }
                }],

                columns: [
                    new Ext.grid.RowNumberer(),
                    {
                        header: 'Nro. Parte',
                        dataIndex: 'nro_parte',
                        align: 'center',
                        width: 165,
                        editor: this.detCmp.nro_parte
                    },
                    {
                        header: 'Nro. Parte Alterno',
                        dataIndex: 'nro_parte_alterno',
                        align: 'center',
                        width: 165,
                        editor: this.detCmp.nro_parte_alterno
                    },
                    {
                        header: 'Referencia',
                        dataIndex: 'referencia',
                        align: 'center',
                        allowBlank:true,
                        width: 165,
                        editor: this.detCmp.referencia
                    },
                    {
                        header: 'Serial',
                        dataIndex: 'referencia',
                        align: 'center',
                        allowBlank:true,
                        hidden:true,
                        width: 165,
                        editor: this.detCmp.serial
                    },
                    {
                        header: 'Condicion',
                        dataIndex: 'condicion_det',
                        align: 'center',
                        allowBlank:true,
                        hidden:true,
                        width: 165,
                        editor: this.detCmp.condicion_det
                    },
                    {
                        header: 'Centro de Costo',
                        dataIndex: 'id_centro_costo',
                        align: 'center',
                        allowBlank:true,
                        //hidden:true,
                        renderer:function(value, p, record){return String.format('{0}', record.data['desc_centro_costo']);},
                        width: 165,
                        editor: this.detCmp.id_centro_costo
                    },
                    {
                        header: 'Orden de trabajo',
                        dataIndex: 'id_orden_trabajo',
                        align: 'center',
                        allowBlank:true,
                        renderer:function(value, p, record){return String.format('{0}', record.data['desc_orden_trabajo']);},
                        //hidden:true,
                        width: 165,
                        editor: this.detCmp.id_orden_trabajo
                    },
                    {
                        header: 'Concepto',
                        dataIndex: 'id_concepto_ingas',
                        align: 'center',
                        allowBlank:true,
                        //hidden:true,
                        width: 165,
                        renderer:function(value, p, record){                          
                          return String.format('{0}', record.data['desc_concepto_ingas']);
                        },
                        editor: this.detCmp.id_concepto_ingas
                    },
                    {
                        header: 'Descripcion',
                        dataIndex: 'descripcion',
                        align: 'center',
                        width: 180,
                        editor: this.detCmp.descripcion
                    },
                    {
                        header: 'Explicacion Detallada P/N',
                        dataIndex: 'explicacion_detallada_part',
                        align: 'center',
                        width: 210,
                        editor: this.detCmp.explicacion_detallada_part
                    },
                    {
                        header: 'Tipo',
                        dataIndex: 'tipo',
                        align: 'center',
                        width: 125,
                        editor: this.detCmp.tipo
                    },
                    {
                        header: 'Cantidad',
                        dataIndex: 'cantidad_sol',
                        align: 'center',
                        width: 100,
                        summaryType: 'sum',
                        editor: this.detCmp.cantidad_sol
                    },
                    {
                        header: 'Precio Unitario',
                        dataIndex: 'precio_unitario',
                        align: 'center',
                        width: 130,
                        //summaryType: 'sum',
                        editor: this.detCmp.precio_unitario
                    },
                    {
                        header: 'Precio Total',
                        dataIndex: 'precio_total',
                        align: 'center',
                        width: 150,
                        //summaryType: 'sum',
                        editor: this.detCmp.precio_total
                    },
                   {
                        header: 'U/M',
                        dataIndex: 'id_unidad_medida',
                        align: 'center',
                        width: 95,
                        renderer:function(value, p, record){return String.format('{0}', record.data['codigo']);},
                        editor: this.detCmp.id_unidad_medida
                    },

                    {
                         header: 'Identificador Alkym',
                         dataIndex: 'id_producto_alkym',
                         align: 'center',
                         width: 150,
                         editor: this.detCmp.id_producto_alkym
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
                                       background: '#8CFFB1',
                                       height:'300px',
                                       border:'1px solid black',
                                       borderRadius:'2px'
                                    },
                                id:'datos_basicos',
                                border: false,
                                autoHeight: true,
                                items: [{
                                    xtype: 'fieldset',
                                    //frame: true,
                                    layout: 'form',
                                    title: ' DATOS BÁSICOS ',
                                    width: '33%',
                                    border: false,
                                    //border: false,
                                    //margins: '0 0 0 5',
                                    padding: '0 0 0 10',
                                    bodyStyle: 'padding-left:5px;',
                                    id_grupo: 0,
                                    items: []
                                }]
                            },
                            {
                              style:{
                                    background:'#EEDE5A',
                                    height:'300px',
                                    marginLeft:'25px',
                                    border:'1px solid black',
                                    borderRadius:'2px'
                                   },
                                id:'justificacion_necesidad_form',
                                autoHeight: true,
                                border: false,
                                items:[
                                    {
                                        xtype: 'fieldset',
                                        /*frame: true,
                                         border: false,*/
                                        layout: 'form',
                                        border: false,
                                        title: 'JUSTIFICACION DE NECESIDAD',
                                        width: '55%',


                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 1,
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
                config:{
                    name:'origen_pedido',
                    fieldLabel:'Origen Pedido',
                    allowBlank:false,
                    editable: false,
                    emptyText:'Elija una opción...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    width: 400,
                    store:['Gerencia de Operaciones','Gerencia de Mantenimiento','Reparación de Repuestos','Almacenes Consumibles o Rotables','Centro de Entrenamiento Aeronautico Civil']

                },
                type:'ComboBox',
                id_grupo:0,
                form:true

            },
            {
                config: {
                    name: 'id_funcionario_sol',
                    hiddenName: 'id_funcionario',
                    origen: 'FUNCIONARIOCAR',
                    fieldLabel: 'Técnico Solicitante',
                    allowBlank: false,
                    valueField: 'id_funcionario',
                    width: 400,
                    baseParams: {es_combo_solicitud: 'si'},
                    msgTarget: 'side'
                },
                type: 'ComboRec',//ComboRec
                id_grupo: 0,
                form: true
            },
            {
                config: {
                    name: 'id_funcionario_solicitante',
                    hiddenName: 'id_funcionario_solicitante',
                    origen: 'FUNCIONARIOCAR',
                    fieldLabel: 'Funcionario Solicitante',
                    allowBlank: false,
                    hidden:true,
                    valueField: 'id_funcionario',
                    width: 400,
                    baseParams: {es_combo_solicitud: 'si'},
                    msgTarget: 'side'
                },
                type: 'ComboRec',//ComboRec
                id_grupo: 0,
                form: true
            },
            {
                config: {
                    name: 'id_depto',
                    hiddenName: 'id_depto',
                    url: '../../sis_parametros/control/Depto/listarDeptoFiltradoXUsuario',
                    origen: 'DEPTO',
                    allowBlank: false,
                    fieldLabel: 'Depto',
                    disabled: true,
                    hidden:true,
                    width: 400,
                    baseParams: {estado: 'activo', codigo_subsistema: 'ADQ', desde_adquisiciones: 'si'},
                    msgTarget: 'side'
                },
                type: 'ComboRec',
                id_grupo: 0,
                form: true
            },
            {
                config:{
                    name: 'nro_lote',
                    fieldLabel: 'Nro. Lote',
                    allowBlank: true,
                    qtip: 'Para BoA Rep esta información se refleja en el reporte de Informe de Justificacion y Recomendacion',
                    hidden:true,
                    width: 400,
                    gwidth: 200,
                    maxLength:100
                },
                type:'NumberField',
                filters:{pfiltro:'sol.nro_lote',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'nro_po',
                    fieldLabel: 'Nro REP',
                    allowBlank: false,
                    qtip: 'Para BoA Rep esta información se refleja en el reporte de TECHNICAL SPECIFICATIONS, ORDEN DE REPARACION EXTERIOR, INFORME DE JUSTIFICACIÓN Y RECOMENDACIÓN',
                    width: 400,
                    hidden:true,
                    gwidth: 100,
                    maxLength:50
                },
                type:'TextField',
                filters:{pfiltro:'sol.nro_po',type:'string'},
                id_grupo:1,
                grid:false,
                form:true,
                bottom_filter:true
            },
            // {
            //     config:{
            //         name: 'fecha_po',
            //         fieldLabel: 'Fecha REP',
            //         allowBlank: true,
            //         qtip: 'Para BoA Rep esta información se refleja en el reporte de Orden de Reparación (order date)</br> </br> Para BoA Rep esta información se refleja en el reporte de Comité de Evaluación (fecha)</br>',
            //         width: 400,
            //         gwidth: 100,
            //         hidden:true,
            //         format: 'd/m/Y',
            //         renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            //     },
            //     type:'DateField',
            //     filters:{pfiltro:'sol.fecha_requerida',type:'date'},
            //     id_grupo:1,
            //     form:true
            // },
            {
                config: {
                    name: 'id_forma_pago',
                    fieldLabel: 'Forma de pago',
                    allowBlank: true,
                    hidden:true,
                    qtip: 'Para BoA Rep esta información se refleja en el reporte de TECHNICAL SPECIFICATIONS, ORDEN DE REPARACION EXTERIOR',
                    width: 400,
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
                        baseParams: { tipo_combo: 'formas_pago' }
                    }),
                    valueField: 'id',
                    gdisplayField : 'codigo_forma_pago',
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
                    listWidth:'450',
                    listeners: {
                        beforequery: function(qe){
                        delete qe.combo.lastQuery;
                      }
                    },
                },
                type: 'ComboBox',
                id_grupo: 1,
                form: true,
                grid:true
            },
            {
                config: {
                    name: 'id_condicion_entrega',
                    fieldLabel: 'Condiciones de Entrega',
                    allowBlank: true,
                    qtip: 'Para BoA Rep esta información se refleja en el reporte de TECHNICAL SPECIFICATIONS, ORDEN DE REPARACION EXTERIOR',
                    hidden:true,
                    width: 400,
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
                        baseParams: { tipo_combo: 'condicion_entrega' }
                    }),
                    valueField: 'id',
                    gdisplayField : 'codigo_condicion_entrega',
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
                    listWidth:'450',
                    listeners: {
                        beforequery: function(qe){
                        delete qe.combo.lastQuery;
                      }
                    },
                },
                type: 'ComboBox',
                id_grupo: 1,
                form: true,
                grid:true
            },
            {
                config:{
                    name: 'fecha_solicitud',
                    fieldLabel: 'Fecha Solicitud',
                    qtip: 'Según esta fecha se escoje el formulario de solicitud',
                    readOnly : true,
                    allowBlank: false,
                    gwidth: 100,
                    width: 400,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}

                },
                type: 'DateField',
                id_grupo: 0,
                form: true
            },

            /*Aumentando la moneda para el registro (Ismael Valdivia 10/02/2020)*/
            {
        				config:{
        					name: 'id_moneda',
        					fieldLabel: 'Moneda',
        					allowBlank: false,
        					emptyText:'Moneda...',
        					store:new Ext.data.JsonStore(
        					{
        						url: '../../sis_parametros/control/Moneda/listarMoneda',
        						id: 'id_moneda',
        						root: 'datos',
        						sortInfo:{
        							field: 'moneda',
        							direction: 'ASC'
        						},
        						totalProperty: 'total',
        						fields: ['id_moneda','moneda','codigo','codigo_internacional'],
        						// turn on remote sorting
        						remoteSort: true,
        						baseParams:{par_filtro:'moneda.codigo_internacional#moneda.moneda',filtrar_solo_dolar:'si'}
        					}),
        					valueField: 'id_moneda',
        					displayField: 'moneda',
        					gdisplayField:'moneda',
        					hiddenName: 'id_moneda',
        					tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>{moneda}</b></p><b><p>Codigo:<font color="green">{codigo_internacional}</font></b></p></div></tpl>',
        						triggerAction: 'all',
        						lazyRender:true,
        					mode:'remote',
        					pageSize:50,
        					queryDelay:500,
        					width: 400,
        					gwidth:150,
        					minChars:2,
                  hidden:true,
        					renderer:function (value, p, record){return String.format('{0}', record.data['id_moneda']);}
        				},
        				type:'ComboBox',
        				filters:{pfiltro:'moneda.codigo_internacional',type:'string'},
        				id_grupo:0,
        				grid:false,
        				form:true
        			},
            /********************************************************************/

            {
                config: {
                    name: 'id_matricula',
                    fieldLabel: 'Matricula',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_gestion_materiales/control/Solicitud/listarMatricula',
                        id: 'id_matricula',
                        root: 'datos',
                        sortInfo: {
                            field: 'matricula',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_orden_trabajo','desc_orden', 'matricula'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'ord.desc_orden',soloAlkym:'si'}
                    }),
                    valueField: 'id_orden_trabajo',
                    displayField: 'desc_orden',
                    hiddenName: 'id_matricula',
                    tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_orden}</p><p style="color: blue">{matricula}</p></div></tpl>',
                    istWidth:'280',
                    forceSelection:true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode:'remote',
                    pageSize:1000,
                    queryDelay:500,
                    minChars:2,
                    width: 400,
                    gwidth: 230
                },
                type: 'ComboBox',
                id_grupo: 0,
                form: true
            },
            {
                config:{
                    name: 'motivo_solicitud',
                    fieldLabel: 'Motivo Solicitud',
                    qtip: 'Este dato se refleja en los reportes Informe de Necesidad (Causas que originaron la solicitud), Especificación Técnica (Motivo de la Solicitud) para los Trámites del tipo GO-GA-GM.',
                    allowBlank: false,
                    width: 400,
                    maxLength:10000
                },
                type:'TextArea',
                filters:{pfiltro:'sol.motivo_solicitud',type:'string'},
                id_grupo:0,
                form:true
            },
            {
                config:{
                    name: 'observaciones_sol',
                    fieldLabel: 'Observaciones',
                    allowBlank: false,
                    qtip: 'Para BoA Rep esta información se refleja en el reporte de TechnicalSpecifications.<br> Para los tramites del tipo GO-GA-GM este campo se refleja en el reporte Especificación Técnica (Observaciones) tambien se reflejará en el cuadro comparativo.',
                    width: 400,
                    maxLength:10000,
                    hidden: true
                },
                type:'TextArea',
                filters:{pfiltro:'sol.observaciones_sol',type:'string'},
                id_grupo:0,
                form:true
            },
            {
                config:{
                    name:'justificacion',
                    fieldLabel:'Justificación ',
                    allowBlank:false,
                    editable: false,
                    emptyText:'Elija una opción...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    lazyRender:true,
                    mode: 'local',
                    width: 400,
                    store:['Directriz de Aeronavegabilidad','Boletín de Servicio','Task Card','"0" Existencia en Almacén','Otros']

                },
                type:'ComboBox',
                id_grupo:1,
                form:true

            },
            {
                config:{
                    name: 'nro_justificacion',
                    fieldLabel: 'Nro. Justificacion',
                    allowBlank: true,
                    width: 400,
                    gwidth: 100,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'sol.estado',type:'string'},
                id_grupo:1,
                form:true
            },

            {
                config:{
                    name:'tipo_solicitud',
                    fieldLabel:'Tipo Solicitud',
                    allowBlank:false,
                    editable: false,
                    emptyText:'Elija una opción...',

                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    width: 400,
                    store:['AOG','NORMAL','CRITICAL']

                },
                type:'ComboBox',
                id_grupo:1,
                form:true

            },
            {
                config:{
                    name:'tipo_falla',
                    fieldLabel:'Tipo de Falla',
                    allowBlank:true,
                    emptyText:'Elija una opción...',
                    typeAhead: true,
                    editable: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'local',
                    width: 400,
                    store:['Falla Confirmada','T/S en Progreso ','No Aplica']
                },
                type:'ComboBox',
                id_grupo:1,
                form:true

            },
            {
                config:{
                    name:'tipo_reporte',
                    fieldLabel:'Tipo de Reporte',
                    allowBlank:true,
                    emptyText:'Elija una opción...',
                    editable: false,
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    width: 400,
                    store:['PIREPS','MAREPS','No Aplica']

                },
                type:'ComboBox',
                id_grupo:1,
                form:true

            },
            {
                config:{
                    name:'mel',
                    fieldLabel:'Mel/Prioridad',
                    allowBlank:false,
                    emptyText:'Elija una opción...',
                    editable: false,
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    width: 400,
                    store:['A','B','C','D','NEF','OTRO']

                },
                type:'ComboBox',
                id_grupo:1,
                form:true

            },
            /*Aqui aumentando el nuevo campo de mel observacion*/
            {
                config:{
                    name: 'mel_observacion',
                    fieldLabel: 'Observación MEL',
                    allowBlank: true,
                    hidden: true,
                    width: 400,
                    gwidth: 150,
                    maxLength:10000
                },
                type:'TextArea',
                filters:{pfiltro:'sol.mel_observacion',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'fecha_requerida',
                    fieldLabel: 'Fecha Requerida / Due Date',
                    allowBlank: true,
                    width: 400,
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{pfiltro:'sol.fecha_requerida',type:'date'},
                id_grupo:1,
                form:true
            },
            {
                config:{
                    name: 'nro_no_rutina',
                    fieldLabel: 'Nro. No Rutina',
                    allowBlank: true,
                    width: 400,
                    gwidth: 200,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'sol.motivo_solicitud',type:'string'},
                id_grupo:1,
                form:true
            },
            /*Aqui aumentando para registrar los dias de entrega ya que esa fecha varia*/
            {
                config:{
                    name: 'dias_entrega_estimado',
                    fieldLabel: 'Días estimado de Entrega del Repuesto',
                    allowBlank: true,
                    qtip: 'Poner los dias estimados de entrega del repuesto<br><b>Este campo reparaciones se muestra en el reporte Technical specifications</b>',
                    width: 400,
                    gwidth: 200,
                    maxLength:100,
                    hidden:true
                },
                type:'NumberField',
                //filters:{pfiltro:'sol.dias_entrega_estimado',type:'string'},
                id_grupo:1,
                form:true
            }
            /****************************************************************************/


        ],
        title: 'Frm Materiales',

        /*inicarEventoDetalle: function () {
            this.detCmp.nro_parte.on('select', function(cmb,rec,i){
                    this.detCmp.nro_parte_alterno.setValue(rec.data.nro_parte_alterno);
                    this.detCmp.referencia.setValue(rec.data.referencia);
                    this.detCmp.descripcion.setValue(rec.data.descripcion);
                    this.detCmp.tipo.setValue(rec.data.tipo);
                    //this.detCmp.id_unidad_medida.setValue(rec.data.id_unidad_medida);
                    //this.detCmp.id_funcionario_sol.setRawValue(reg.ROOT.datos.nombre_completo1);
            } ,this);
            this.detCmp.nro_parte.on('change', function(cmb,newval,oldval){
                var rec = cmb.getStore().getById(newval);
                if(!rec){
                    //si el combo no tiene resultado
                    if(cmb.lastQuery){
                        //y se tiene una consulta anterior( cuando editemos no abra cnsulta anterior)
                        this.detCmp.nro_parte_alterno.reset();
                        this.detCmp.referencia.reset();
                        this.detCmp.descripcion.reset();
                        this.detCmp.tipo.reset();
                    }
                }
            } ,this);
        },*/
        onSubmit: function(o) {
            //  validar formularios
            var arra = [], k, me = this;
            console.log("aqui del megrid el formulario",me.megrid.store);
            for (k = 0; k < me.megrid.store.getCount(); k++) {
                record = me.megrid.store.getAt(k);
                arra[k] = record.data;
            }
            me.argumentExtraSubmit = { 'json_new_records': JSON.stringify(arra, function replacer(key, value) {
                return value;
            }) };
                if( k > 0 &&  !this.editorDetaille.isVisible()){
                    Ext.Ajax.request({
                        url:'../../sis_gestion_materiales/control/Solicitud/compararNroJustificacion',
                        params:{
                            justificacion: this.Cmp.nro_justificacion.getValue(),
                            nro_parte: this.detCmp.nro_parte.getValue(),
                            id_matricula: this.Cmp.id_matricula.getValue()
                        },
                        success:function(resp){
                            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                            var nro_justificacion =reg.ROOT.datos.justificacion;
                            var nro_parte =reg.ROOT.datos.parte;
                            var matricula=reg.ROOT.datos.matricula;
                            var mgs_control =reg.ROOT.datos.mgs_control_duplicidad;
                            var ma =this;
                            if(ma.Cmp.nro_justificacion.getValue() == nro_justificacion && ma.Cmp.nro_justificacion.getValue() != '' || ma.detCmp.nro_parte.getValue() == nro_parte && ma.Cmp.id_matricula.getValue() == matricula){
                                Ext.Msg.confirm('DUPLICIDAD', mgs_control + ' desea continuar con el registro ', function (btn) {
                                    if (btn === 'yes') {
                                        Phx.vista.FromFormula.superclass.onSubmit.call(this,o,undefined, true);
                                    } else {
                                    }
                                },this);
                                // Ext.Msg.alert('Alerta', mgs_control+ '</b> ya fue registrado por el Funcionario <b> ');
                            }else if(ma.Cmp.nro_justificacion.getValue() != nro_justificacion || ma.detCmp.nro_parte.getValue() != nro_parte && ma.Cmp.id_matricula.getValue()!=matricula){
                                Phx.vista.FromFormula.superclass.onSubmit.call(this,o,undefined, true);
                            }
                            else if(ma.Cmp.nro_justificacion.getValue() != nro_justificacion || ma.detCmp.nro_parte.getValue() != nro_parte && ma.Cmp.id_matricula.getValue()==''){
                                Phx.vista.FromFormula.superclass.onSubmit.call(this,o,undefined, true);
                            }
                        },
                        failure: this.conexionFailure,
                        timeout:this.timeout,
                        scope:this
                    });

                }
                else{
                    alert("No tiene datos en el detalle")
                }

        },


        successSave: function (resp) {

            Phx.CP.loadingHide();
            var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            this.fireEvent('successsave', this, objRes);

        },

        mensaje_: function (titulo, mensaje, icono) {

            var tipo = 'ext-mb-warning';
            Ext.MessageBox.show({
                title: titulo,
                msg: mensaje,
                buttons: Ext.MessageBox.OK,
                icon: tipo
            })

        }
    })
</script>
