<?php
/**
 *@package pXP
 *@file gen-Cotizacion.php
 *@author  (miguel.mamani)
 *@date 04-07-2017 14:03:30
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Cotizacion=Ext.extend(Phx.gridInterfaz,{
            fwidth: 600,
            fheight: 650,
            constructor:function(config){
                this.idContenedor = config.idContenedor;
                this.maestro=config;
                //llama al constructor de la clase padre
                this.id_solicitud=this.maestro.id_solicitud;
                this.tipo_tramite=this.maestro.tipoTramite;
                //console.log('hola',this.maestro);
                //console.log('heee',this.idContenedor);
                Phx.vista.Cotizacion.superclass.constructor.call(this,config);
                this.init();
                this.store.baseParams={id_solicitud:this.id_solicitud,tipo_interfaz: 'Solicitud', interfaz_origen: Phx.CP.getPagina(this.idContenedorPadre).store.baseParams.tipo_interfaz};
                this.load({params:{start:0, limit:this.tam_pag}});
                this.grid.addListener('cellclick', this.oncellclick,this);
                this.bbar.el.dom.style.background='#80D7FF';
        				this.tbar.el.dom.style.background='#80D7FF';
        				this.grid.body.dom.firstChild.firstChild.firstChild.firstChild.style.background='#B7E8FF';
        				this.grid.body.dom.firstChild.firstChild.lastChild.style.background='#D8F9FF';
                //this.iniciarEvento();
            },

            Grupos: [
                {
                    layout: 'column',
                    border: false,
                    xtype: 'fieldset',
                    defaults: {
                        border: false
                    },
                    style:{
                          background:'#548DCA',
                         },

                    items: [
                        {
                          xtype: 'fieldset',
                          style:{
                                background:'#F2FF88',
                                width:'500px',
                                height:'580px',
                                border:'1px solid black',
                                borderRadius:'2px'
                               },
                           id:'data_alkym',
                            items: [

                                {
                                    xtype: 'fieldset',
                                    title: 'Datos Cotización',
                                    // autoHeight: true,
                                    border: false,
                                    items: [/*this.compositeFields()*/],
                                    id_grupo: 0
                                }

                            ]
                        }

                    ]
                }],

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
                    config:{
                        name: 'adjudicado',
                        fieldLabel: 'Adjudicado',
                        allowBlank: true,
                        width: 300,
                        gwidth: 80,
                        maxLength:3,
                        renderer: function (value){
                            //check or un check row
                            var checked = '',
                                momento = 'no';
                            if(value == 'si'){
                                checked = 'checked';;
                            }
                            return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:35px;width:35px;" type="checkbox"  {0}></div>',checked);

                        }
                    },
                    type: 'TextField',
                    filters: { pfiltro:'cts.adjudicado',type:'string'},
                    id_grupo: 0,
                    grid: true,
                    form: false
                },
                {
                    config:{
                        name: 'nro_tramite',
                        fieldLabel: 'Nro.Tramite',
                        allowBlank: true,
                        width: 300,
                        gwidth: 170,
                        maxLength:255
                    },
                    type:'TextField',
                    filters:{pfiltro:'cts.nro_tramite',type:'string'},
                    id_grupo:0,
                    grid:true,
                    form:false
                },
                /*Aumentando para filtrar los proveedores (Ismael Valdivia 18/02/2020)*/
                {
                    config: {
                        name: 'id_proveedor',
                        fieldLabel: 'Proveedor',
                        allowBlank: false,
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
                        width: 300,
                        gwidth: 300,
                        minChars: 2,
                        renderer : function(value, p, record) {
                            return String.format('{0}', record.data['desc_proveedor']);
                        }
                    },
                    type: 'ComboBox',
                    id_grupo: 0,
                    filters: {pfiltro: 'p.desc_proveedor',type: 'string'},
                    grid: true,
                    form: true
                },

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
                        width: 300,
                        gwidth: 300,
                        //hidden:true,
                        minChars: 2,
                        renderer : function(value, p, record) {
                            return String.format('{0}', record.data['desc_contacto']);
                        },
                        listeners: {
        									  beforequery: function(qe){
        										delete qe.combo.lastQuery;
        									}
        								},
                    },
                    type: 'ComboBox',
                    id_grupo: 0,
                    filters: {pfiltro: 'nombre_contacto',type: 'string'},
                    grid: true,
                    form: true
                },
                /************************************************************************/
                // {
                //     config: {
                //         name: 'id_proveedor',
                //         fieldLabel: 'Proveedor',
                //         anchor: '80%',
                //         tinit: false,
                //         allowBlank: false,
                //         origen: 'PROVEEDOR',
                //         gdisplayField: 'desc_proveedor',
                //         anchor: '100%',
                //         gwidth: 300,
                //         listWidth: '300',
                //         resizable: true
                //     },
                //     type: 'ComboRec',
                //     filters:{pfiltro:'pro.desc_proveedor',type:'string'},
                //     id_grupo:0,
                //     grid: true,
                //     form: true,
                //     bottom_filter:true
                // },

                {
                    config:{
                        name: 'nro_cotizacion',
                        fieldLabel: 'Nro.Cotización',
                        allowBlank: false,
                        width: 300,
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
                        fieldLabel: 'Fecha Cotización',
                        allowBlank: false,
                        width: 300,
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
                    config: {
                        name: 'monto_total',
                        fieldLabel: 'Monto Total',
                        currencyChar: ' ',
                        allowBlank: true,
                        width: 100,
                        gwidth: 120,
                        disabled: true,
                        maxLength: 1245186
                    },
                    type: 'MoneyField',
                    filters: {pfiltro: 'cts.monto_total', type: 'numeric'},
                    id_grupo: 1,
                    grid: true,
                    form: false
                },
                {
                    config:{
                        name: 'recomendacion',
                        fieldLabel: 'Recomendación',
                        allowBlank: true,
                        width: 300,
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
                        allowBlank: true,
                        width: 300,
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
                        allowBlank: true,
                        width: 300,
                        gwidth: 200,
                        maxLength:10000
                    },
                    type:'TextArea',
                    filters:{pfiltro:'cts.pie_pag',type:'string'},
                    id_grupo:0,
                    grid:true,
                    form:true
                },
                {
                    config:{
                        name:'id_moneda',
                        origen:'MONEDA',
                        allowBlank:false,
                        fieldLabel:'Moneda',
                        gdisplayField:'desc_moneda',//mapea al store del grid
                        width: 300,
                        gwidth: 100,
                        renderer : function(value, p, record) {
                            return String.format('{0}', record.data['desc_moneda']);
                        }
                    },
                    type:'ComboRec',
                    id_grupo:0,
                    filters:{
                        pfiltro:'incbte.desc_moneda',
                        type:'string'
                    },
                    grid:false,
                    egrid:false,
                    form:true
                },
                {
                    config:{
                        name:'tipo_evaluacion',
                        fieldLabel:'Tipo de Adquisición',
                        style:{
                          background:'#FFCD8C',
                        },
                        typeAhead: true,
                        width: 300,
                        allowBlank:true,//esta parte comentado
                        //hidden:true,//esta parte aumentado
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
                    id_grupo:3,
                    grid:false,
                    form:true
                },
                {
        						config: {
        								name: 'id_condicion_entrega',
        								fieldLabel: 'Condiciones de Entrega',
                        style:{
                          background:'#8AED74',
                        },
        								allowBlank: true,
        								width: 300,
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
        						grid:false
        				},
                {
        						config: {
        								name: 'id_forma_pago',
        								fieldLabel: 'Forma de pago',
                        style:{
                          background:'#8AED74',
                        },
        								allowBlank: true,
        								width: 300,
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
        						grid:false
        				},
                {
        						config: {
        								name: 'id_modo_envio',
        								fieldLabel: 'Modos de Envio',
                        style:{
                          background:'#8AED74',
                        },
        								allowBlank: true,
        								width: 300,
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
        										baseParams: { tipo_combo: 'modos_envio' }
        								}),
        								valueField: 'id',
        								gdisplayField : 'codigo_modo_envio',
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
        						grid:false
        				},
                {
        						config: {
        								name: 'id_puntos_entrega',
        								fieldLabel: 'Puntos de entrega',
                        style:{
                          background:'#8AED74',
                        },
        								allowBlank: true,
        								width: 300,
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
        										fields: ['id', 'nombre'],
        										remoteSort: true,
        										baseParams: { tipo_combo: 'puntos_entrega' }
        								}),
        								valueField: 'id',
        								gdisplayField : 'codigo_puntos_entrega',
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
        								listWidth:'300',
        								listeners: {
        									  beforequery: function(qe){
        										delete qe.combo.lastQuery;
        									}
        								},
        						},
        						type: 'ComboBox',
        						id_grupo: 3,
        						form: true,
        						grid:false
        				},
                {
        						config: {
        								name: 'id_tipo_transaccion',
        								fieldLabel: 'Tipo Transacciones',
                        style:{
                          background:'#8AED74',
                        },
        								allowBlank: true,
        								width: 300,
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
        										baseParams: { tipo_combo: 'tipo_transaccion' }
        								}),
        								valueField: 'id',
        								gdisplayField : 'codigo_tipo_transaccion',
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
        						grid:false
        				},
                {
        						config: {
        								name: 'id_orden_destino',
        								fieldLabel: 'Orden de Destino',
                        style:{
                          background:'#8AED74',
                        },
        								allowBlank: true,
        								width: 300,
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
        										baseParams: { tipo_combo: 'orden_destino' }
        								}),
        								valueField: 'id',
        								gdisplayField : 'codigo_orden_destino',
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
        						grid:false
        				},
                /***********************************************/
                {
                    config:{
                        name: 'estado_reg',
                        fieldLabel: 'Estado Reg.',
                        allowBlank: true,
                        width: 300,
                        gwidth: 100,
                        maxLength:10
                    },
                    type:'TextField',
                    filters:{pfiltro:'cts.estado_reg',type:'string'},
                    id_grupo:1,
                    grid:true,
                    form:false
                },
                {
                    config:{
                        name: 'id_usuario_ai',
                        fieldLabel: '',
                        allowBlank: true,
                        width: 300,
                        gwidth: 100,
                        maxLength:4
                    },
                    type:'Field',
                    filters:{pfiltro:'cts.id_usuario_ai',type:'numeric'},
                    id_grupo:1,
                    grid:false,
                    form:false
                },
                {
                    config:{
                        name: 'usr_reg',
                        fieldLabel: 'Creado por',
                        allowBlank: true,
                        width: 300,
                        gwidth: 100,
                        maxLength:4
                    },
                    type:'Field',
                    filters:{pfiltro:'usu1.cuenta',type:'string'},
                    id_grupo:1,
                    grid:true,
                    form:false
                },
                {
                    config:{
                        name: 'fecha_reg',
                        fieldLabel: 'Fecha creación',
                        allowBlank: true,
                        width: 300,
                        gwidth: 100,
                        format: 'd/m/Y',
                        renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
                    },
                    type:'DateField',
                    filters:{pfiltro:'cts.fecha_reg',type:'date'},
                    id_grupo:1,
                    grid:true,
                    form:false
                },
                {
                    config:{
                        name: 'usuario_ai',
                        fieldLabel: 'Funcionaro AI',
                        allowBlank: true,
                        width: 300,
                        gwidth: 100,
                        maxLength:300
                    },
                    type:'TextField',
                    filters:{pfiltro:'cts.usuario_ai',type:'string'},
                    id_grupo:1,
                    grid:true,
                    form:false
                },
                {
                    config:{
                        name: 'usr_mod',
                        fieldLabel: 'Modificado por',
                        allowBlank: true,
                        width: 300,
                        gwidth: 100,
                        maxLength:4
                    },
                    type:'Field',
                    filters:{pfiltro:'usu2.cuenta',type:'string'},
                    id_grupo:1,
                    grid:true,
                    form:false
                },
                {
                    config:{
                        name: 'fecha_mod',
                        fieldLabel: 'Fecha Modif.',
                        allowBlank: true,
                        width: 300,
                        gwidth: 100,
                        format: 'd/m/Y',
                        renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
                    },
                    type:'DateField',
                    filters:{pfiltro:'cts.fecha_mod',type:'date'},
                    id_grupo:1,
                    grid:true,
                    form:false
                }
            ],
            tam_pag:50,
            title:'Cotización ',
            ActSave:'../../sis_gestion_materiales/control/Cotizacion/insertarCotizacion',
            ActDel:'../../sis_gestion_materiales/control/Cotizacion/eliminarCotizacion',
            ActList:'../../sis_gestion_materiales/control/Cotizacion/listarCotizacion',
            id_store:'id_cotizacion',
            fields: [
                {name:'id_cotizacion', type: 'numeric'},
                {name:'id_solicitud', type: 'numeric'},
                {name:'id_moneda', type: 'numeric'},
                {name:'nro_tramite', type: 'string'},
                {name:'fecha_cotizacion', type: 'date',dateFormat:'Y-m-d'},
                {name:'adjudicado', type: 'string'},
                {name:'estado_reg', type: 'string'},
                {name:'id_proveedor', type: 'numeric'},
                {name:'monto_total', type: 'numeric'},
                {name:'id_usuario_ai', type: 'numeric'},
                {name:'id_usuario_reg', type: 'numeric'},
                {name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
                {name:'usuario_ai', type: 'string'},
                {name:'id_usuario_mod', type: 'numeric'},
                {name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
                {name:'usr_reg', type: 'string'},
                {name:'usr_mod', type: 'string'},
                {name:'desc_moneda', type: 'string'},
                {name:'desc_proveedor', type: 'string'},
                {name:'nro_cotizacion', type: 'string'},
                {name:'recomendacion', type: 'string'},
                {name:'obs', type: 'string'},
                {name:'pie_pag', type: 'string'},

                {name:'id_condicion_entrega', type: 'numeric'},
                {name:'id_forma_pago', type: 'numeric'},
                {name:'id_modo_envio', type: 'numeric'},
                {name:'id_puntos_entrega', type: 'numeric'},
                {name:'id_tipo_transaccion', type: 'numeric'},
                {name:'id_orden_destino', type: 'numeric'},

                {name:'codigo_condicion_entrega', type: 'string'},
                {name:'codigo_forma_pago', type: 'string'},
                {name:'codigo_modo_envio', type: 'string'},
                {name:'codigo_puntos_entrega', type: 'string'},
                {name:'codigo_tipo_transaccion', type: 'string'},
                {name:'codigo_orden_destino', type: 'string'},
                {name:'tipo_evaluacion', type: 'string'},
                {name:'id_proveedor_contacto', type: 'numeric'},
                {name:'desc_contacto', type: 'string'}
            ],
            sortInfo:{
                field: 'id_cotizacion',
                direction: 'ASC'
            },
            bdel:true,
            bsave:false,
            south:
                {
                    url:'../../../sis_gestion_materiales/vista/cotizacion_detalle/CotizacionDetalle.php',
                    title:'Detalle',
                    height:'50%',
                    cls:'CotizacionDetalle'
                },
            onButtonNew:function(){
                // console.log('id:',this.getComponente('id_solicitud'));
                // Phx.vista.Cotizacion.superclass.onButtonNew.call(this);
                // this.window.items.items[0].body.dom.style.background = '#548DCA';
                // this.window.mask.dom.style.background = '#000000';
          			// this.window.mask.dom.style.opacity = '0.9';
                // this.getComponente('id_solicitud').setValue(this.id_solicitud);
                // this.Cmp.id_proveedor.store.baseParams ={id_solicitud:this.maestro.id_solicitud ,par_filtro: 'desc_proveedor'};
                // this.Cmp.pie_pag.setValue('La entrega del producto es en Miami, de acuerdo a oferta del proveedor; sin embargo la recepción del mismo en almacen Cochabamba hasta en 30 dias. ');
                // this.Cmp.id_moneda.setValue(2);
                // this.Cmp.id_moneda.setRawValue('Dolares Americanos');
                    //abrir formulario de solicitud
                    var me = this;
                    me.objSolForm = Phx.CP.loadWindows('../../../sis_gestion_materiales/vista/solicitud/FormCotizacion.php',
                        '<center><img src="../../../lib/imagenes/facturacion/Formulario.png" style="width:35px; vertical-align: middle;"> <span style="vertical-align: middle; font-size:30px; text-shadow: 3px 0px 0px #000000;"> FORMULARIO DE COTIZACIÓN</span></center>',
                        {
                            modal:true,
                            width:'100%',
                            height:'100%'
                        }, {data:{objPadre: me}
                        },
                        this.idContenedor,
                        'FormCotizacion',
                        {
                            config:[{
                                event:'successsave'
                            }],

                            scope:this
                        });


            },
            onButtonEdit:function () {
                var rec = this.getSelectedData();

                Phx.vista.Cotizacion.superclass.onButtonEdit.call(this);


                /*Aumentando para filtrar el contacto del proveedor*/
                this.Cmp.id_proveedor_contacto.store.baseParams.id_proveedor = rec.id_proveedor;
                /***************************************************/

                this.Cmp.id_proveedor_contacto.store.load({params:{start:0,limit:50},
                   callback : function (r) {
                         this.Cmp.id_proveedor_contacto.setValue(rec.id_proveedor_contacto);
                         this.Cmp.id_proveedor_contacto.fireEvent('select',this.Cmp.id_proveedor_contacto,this.Cmp.id_proveedor_contacto.getValue());
                    }, scope : this
                });


                this.getComponente('id_solicitud').setValue(this.id_solicitud);
                this.Cmp.id_proveedor.store.baseParams ={id_solicitud:this.maestro.id_solicitud ,par_filtro: 'desc_proveedor'};
                this.window.items.items[0].body.dom.style.background = '#548DCA';
                this.window.mask.dom.style.background = '#000000';
          			this.window.mask.dom.style.opacity = '0.9';

                Ext.Ajax.request({
                    url:'../../sis_gestion_materiales/control/Solicitud/getDatosAlkym',
                    params:{id_solicitud:this.maestro.id_solicitud},
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
                                 this.Cmp.id_condicion_entrega.setValue(reg.ROOT.datos.id_condicion_entrega_alkym);
                                 this.Cmp.id_condicion_entrega.fireEvent('select',this.Cmp.id_condicion_entrega,this.Cmp.id_condicion_entrega.getValue());
                            }, scope : this
                        });
                        this.Cmp.id_forma_pago.store.load({params:{start:0,limit:50},
                           callback : function (r) {
                                 this.Cmp.id_forma_pago.setValue(reg.ROOT.datos.id_forma_pago_alkym);
                                 this.Cmp.id_forma_pago.fireEvent('select',this.Cmp.id_forma_pago,this.Cmp.id_forma_pago.getValue());
                            }, scope : this
                        });
                        this.Cmp.id_modo_envio.store.load({params:{start:0,limit:50},
                           callback : function (r) {
                                 this.Cmp.id_modo_envio.setValue(reg.ROOT.datos.id_modo_envio_alkym);
                                 this.Cmp.id_modo_envio.fireEvent('select',this.Cmp.id_modo_envio,this.Cmp.id_modo_envio.getValue());
                            }, scope : this
                        });
                        this.Cmp.id_puntos_entrega.store.load({params:{start:0,limit:50},
                           callback : function (r) {
                                 this.Cmp.id_puntos_entrega.setValue(reg.ROOT.datos.id_puntos_entrega_alkym);
                                 this.Cmp.id_puntos_entrega.fireEvent('select',this.Cmp.id_puntos_entrega,this.Cmp.id_puntos_entrega.getValue());
                            }, scope : this
                        });
                        this.Cmp.id_tipo_transaccion.store.load({params:{start:0,limit:50},
                           callback : function (r) {
                                 this.Cmp.id_tipo_transaccion.setValue(reg.ROOT.datos.id_tipo_transaccion_alkym);
                                 this.Cmp.id_tipo_transaccion.fireEvent('select',this.Cmp.id_tipo_transaccion,this.Cmp.id_tipo_transaccion.getValue());
                            }, scope : this
                        });
                        this.Cmp.id_orden_destino.store.load({params:{start:0,limit:50},
                           callback : function (r) {
                                 this.Cmp.id_orden_destino.setValue(reg.ROOT.datos.id_orden_destino_alkym);
                                 this.Cmp.id_orden_destino.fireEvent('select',this.Cmp.id_orden_destino,this.Cmp.id_orden_destino.getValue());
                            }, scope : this
                        });
                    },
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });

                this.Seleccion(rec);
                console.log("aqui llega el dato",rec);







                if (rec.adjudicado == 'si') {
                  this.mostrarComponente(this.Cmp.id_condicion_entrega);
                  this.mostrarComponente(this.Cmp.id_forma_pago);
                  this.mostrarComponente(this.Cmp.id_modo_envio);
                  this.mostrarComponente(this.Cmp.id_puntos_entrega);
                  this.mostrarComponente(this.Cmp.id_tipo_transaccion);
                  this.mostrarComponente(this.Cmp.id_orden_destino);
                  this.mostrarComponente(this.Cmp.tipo_evaluacion);

                  this.Cmp.id_condicion_entrega.setDisabled(true);
                  this.Cmp.id_forma_pago.setDisabled(true);
                  this.Cmp.id_modo_envio.setDisabled(true);
                  this.Cmp.id_puntos_entrega.setDisabled(true);
                  this.Cmp.id_tipo_transaccion.setDisabled(true);
                  this.Cmp.id_orden_destino.setDisabled(true);
                  this.Cmp.tipo_evaluacion.setDisabled(true);

                  if (this.tipoTramite == 'GR') {
                    this.ocultarComponente(this.Cmp.id_condicion_entrega);
                    this.ocultarComponente(this.Cmp.id_forma_pago);
                    this.ocultarComponente(this.Cmp.tipo_evaluacion);

                    this.ocultarComponente(this.Cmp.id_modo_envio);
                    this.ocultarComponente(this.Cmp.id_puntos_entrega);
                    this.ocultarComponente(this.Cmp.id_tipo_transaccion);
                    this.ocultarComponente(this.Cmp.id_orden_destino);
                    this.Cmp.id_proveedor_contacto.allowBlank = true;
                  } else {

                    /*Aqui ponemos la condicion para saber de que sistema es el tramite*/
                    //if (this.origen_solicitud == 'control_mantenimiento') {
                      this.mostrarComponente(this.Cmp.id_condicion_entrega);
                      this.mostrarComponente(this.Cmp.id_forma_pago);
                      this.mostrarComponente(this.Cmp.tipo_evaluacion);
                      this.mostrarComponente(this.Cmp.id_modo_envio);
                      this.mostrarComponente(this.Cmp.id_puntos_entrega);
                      this.mostrarComponente(this.Cmp.id_tipo_transaccion);
                      this.mostrarComponente(this.Cmp.id_orden_destino);
                      this.mostrarComponente(this.Cmp.id_proveedor_contacto);
                      this.Cmp.id_proveedor_contacto.allowBlank = false;
                      console.log("aqui llega el grupo",Ext.getCmp('data_alkym'));
                      Ext.getCmp('data_alkym').el.dom.style.height = '580px';
                    //} else {
                    //   this.ocultarComponente(this.Cmp.id_condicion_entrega);
                    //   this.ocultarComponente(this.Cmp.id_forma_pago);
                    //   this.ocultarComponente(this.Cmp.tipo_evaluacion);
                    //   this.ocultarComponente(this.Cmp.id_modo_envio);
                    //   this.ocultarComponente(this.Cmp.id_puntos_entrega);
                    //   this.ocultarComponente(this.Cmp.id_tipo_transaccion);
                    //   this.ocultarComponente(this.Cmp.id_orden_destino);
                    //   Ext.getCmp('data_alkym').el.dom.style.height = '400px';
                    //   //Ext.getCmp('data_alkym').show();
                    // }
                    /******************************************************************/
                  }


                } else {
                  this.ocultarComponente(this.Cmp.id_condicion_entrega);
                  this.ocultarComponente(this.Cmp.id_forma_pago);
                  this.ocultarComponente(this.Cmp.id_modo_envio);
                  this.ocultarComponente(this.Cmp.id_puntos_entrega);
                  this.ocultarComponente(this.Cmp.id_tipo_transaccion);
                  this.ocultarComponente(this.Cmp.id_orden_destino);
                  this.ocultarComponente(this.Cmp.tipo_evaluacion);
                }

                /*Aqui ponemos para mandar el codigo de los datos Alkym Ismael Valdivia (22/04/2020)*/

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
                /******************************************************************/


            },

            Seleccion : function(){

            },

            oncellclick : function(grid, rowIndex, columnIndex, e) {
                var record = this.store.getAt(rowIndex),
                    fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name

                if(fieldName == 'adjudicado') {
                    this.cambiarRevision(record);
                    this.evento();
                    //this.onButtonEdit();
                }
            },
            cambiarRevision: function(record){
                Phx.CP.loadingShow();
                var d = record.data;
                Ext.Ajax.request({
                    url:'../../sis_gestion_materiales/control/Cotizacion/controlAdjudicado',
                    params:{id_cotizacion: d.id_cotizacion, adjudicado: d.adjudicado},
                    success: this.successRevision,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
                this.reload();
            },
            successRevision: function(resp){
                Phx.CP.loadingHide();
                var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            },
            evento:function () {
                Phx.CP.getPagina(this.idContenedorPadre).reload();
            },

            onDestroy: function() {
              Phx.CP.getPagina(this.idContenedorPadre).reload();
            }
        }
    )
</script>
