<?php
/**
*@package pXP
*@file gen-DetalleSol.php
*@author  (admin)
*@date 23-12-2016 13:13:01
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.DetalleSol=Ext.extend(Phx.gridInterfaz,{
	constructor:function(config){
				this.maestro=config.maestro;
        Phx.vista.DetalleSol.superclass.constructor.call(this,config);
        this.init();
				this.bbar.el.dom.style.background='#03A27C';
				this.tbar.el.dom.style.background='#03A27C';
				this.grid.body.dom.firstChild.firstChild.firstChild.firstChild.style.background='#E9E9E9';
				this.grid.body.dom.firstChild.firstChild.lastChild.style.background='#F6F6F6';
				this.iniciarEventos();
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
												background:'#FFC686',
												width:'330px',
												height:'350px',
												border:'1px solid black',
												borderRadius:'2px'
											 },
										items: [

												{
														xtype: 'fieldset',
														//title: '  Datos Generales ',
														border: false,
														//autoHeight: true,
														style:{
																	background:'#FFC686',
																 },

														items: [/*this.compositeFields()*/],
														id_grupo: 0
												}

										]
								},
								{
									xtype: 'fieldset',
									style:{
												background:'#EEDE5A',
												width:'330px',
												height:'350px',
												marginLeft:'2px',
												border:'1px solid black',
												borderRadius:'2px'
											 },
										items: [
												{
														xtype: 'fieldset',
														//title: ' Justificacion de Necesidad ',
														//autoHeight: true,
														border: false,
														style:{
																	background:'#EEDE5A',
																	//border:'2px solid green',

																 },
														items: [],
														id_grupo: 1
												}


										]
								}
						]
				}
		],
		loadMask :false,
	Atributos:[
        {
            //configuracion del componente
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'id_detalle'
            },
            type:'Field',
            form:true
        },
				{
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'id_producto_alkym'
            },
            type:'Field',
            form:true
        },
        {
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'id_solicitud'
            },
            type:'Field',
            form:true
        },
				{
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'control_edicion'
            },
            type:'Field',
            form:true
        },
				{
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'id_gestion'
            },
            type:'Field',
            form:true
        },
				/*Separando por grupo para para ganar espacio
				Inicio grupo 1*/
			  {
            config:{
                name: 'nro_parte',
                //fieldLabel: 'Nro. Parte',
								fieldLabel: 'Nro. Parte',
                allowBlank: true,
                width: 200,
                gwidth: 250,
                maxLength:50,
								disabled:true,
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
                renderer: function(value, p, record) {
									var color = 'blue';
									var size = '12px';
									var colordes = '#009B13';
									var sizedes = '12px';
									var titulo = 'Referencia'
									if (record.store.baseParams.tipo_tramite == 'GR') {
										 titulo = 'Serial';
									}

									if(record.data.tipo_reg != 'summary'){
											return  '<div><p><b style="color:'+color+'; font-size:'+size+';">Nro.Parte: </b><b style="color:'+colordes+'; font-size:'+sizedes+';">'+record.data['nro_parte']+'</b></p>' +
															'<p><b style="color:'+color+'; font-size:'+size+';">Nro. Parte Alterna: </b><b style="color:'+colordes+'; font-size:'+sizedes+';">'+ record.data['nro_parte_alterno']+'</b></p>' +
															'<p><b style="color:'+color+'; font-size:'+size+';">'+titulo+': </b><b style="color:'+colordes+'; font-size:'+sizedes+';">'+ record.data['referencia']+'</b></p>' +
															'<p><b style="color:'+color+'; font-size:'+size+';">Descripción: </b><b style="color:'+colordes+'; font-size:'+sizedes+';">'+ record.data['descripcion'] +'</b></p>'+
															'<p><b style="color:'+color+'; font-size:'+size+';">Tipo: </b><b style="color:'+colordes+'; font-size:'+sizedes+';">'+ record.data['tipo'] +'</b></p>'+
															'<p><b style="color:'+color+'; font-size:'+size+';">Unidad de Medida: </b><b style="color:'+colordes+'; font-size:'+sizedes+';">'+ record.data['codigo'] +'</b></p></div>';
									} else {
											return String.format('<div><b><font>{0}</font></b><br></div>', '');
									}
								}
            },
            type:'ComboBox',
            filters:{pfiltro:'det.nro_parte',type:'string'},
            id_grupo:0,
            grid:true,
            form:true
        },
				/*Aumentando esta condicion a pedido de Patty y Veronica Aux Repuestos*/
					{
					 config : {
						 name : 'condicion_det',
						 fieldLabel : 'Condicion',
						 hidden:true,
						 width:200,
						 allowBlank : true,
						 emptyText : 'Condicion...',
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
						 valueField : 'descripcion',
						 displayField : 'descripcion',
						 gdisplayField : 'condicion_det',
						 hiddenName : 'condicion_det',
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
						 triggerAction : 'all',
						 lazyRender : true,
						 mode : 'remote',
						 pageSize : 25,
						 listWidth:'450',
						 maxHeight : 450,
						 queryDelay : 1000,
						 tasignacion : true,
						 tname : 'id_proveedor',
						 tdata:{},
						 tcls:'Proveedor',
						 gwidth : 170,
						 minChars : 2,
						 resizable:true,
						 enableMultiSelect: true
					 },
					 type : 'ComboBox',
					 id_grupo : 2,
					 grid: true,
					 form: true
				 },
			 /**************************************************************************/
        {
            config:{
                name: 'nro_parte_alterno',
                fieldLabel: 'Nro. Parte Alterno',
                allowBlank: true,
                width: 200,
                gwidth: 150,
								disabled:true,
                maxLength:150,
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
                renderer: function(value, p, record) {
                    if(record.data.revisado == 'no'&& record.data.estado == 'almacen'){
                        return String.format('<div ext:qtip="Optimo"><b><font color="red">{0}</font></b><br></div>', value);
                    }else{
                        return String.format('<div ext:qtip="Optimo"><b><font color="black">{0}</font></b><br></div>', value);

                    }

                }
            },
            type:'ComboBox',
            filters:{pfiltro:'det.nro_parte_alterno',type:'string'},
            id_grupo:0,
            grid:false,
            form:true
        },
        {
            config:{
                name: 'referencia',
                fieldLabel: 'Referencia',
                allowBlank: true,
                width: 200,
                gwidth: 200,
                maxLength:200,
                renderer: function(value, p, record) {
                    if(record.data.revisado == 'no'&& record.data.estado == 'almacen'){
                        return String.format('<div ext:qtip="Optimo"><b><font color="red">{0}</font></b><br></div>', value);
                    }else{
                        return String.format('<div ext:qtip="Optimo"><b><font color="black">{0}</font></b><br></div>', value);

                    }

                }
            },
            type:'TextField',
            filters:{pfiltro:'det.referencia',type:'string'},
            id_grupo:0,
            grid:false,
            form:true
        },
				/*Aumentando para Cabiar de acuerdo al estado Ismael Valdivia (24/03/2020)*/
				{
            config:{
                name: 'serial',
                fieldLabel: 'Serial',
                allowBlank: true,
                width: 200,
                gwidth: 200,
                maxLength:100,
                renderer: function(value, p, record) {
                    if(record.data.revisado == 'no'&& record.data.estado == 'almacen'){
                        return String.format('<div ext:qtip="Optimo"><b><font color="red">{0}</font></b><br></div>', record.data.referencia);
                    }else{
                        return String.format('<div ext:qtip="Optimo"><b><font color="black">{0}</font></b><br></div>', record.data.referencia);

                    }

                }
            },
            type:'TextField',
            filters:{pfiltro:'det.referencia',type:'string'},
            id_grupo:0,
            grid:false,
            form:true
        },
				/*********************************************************************************************************************/
        {
            config:{
                name: 'descripcion',
                fieldLabel: 'Descripcion',
                allowBlank: true,
                width: 200,
                gwidth: 200,
                maxLength:100,
                renderer: function(value, p, record) {
                    if(record.data.revisado == 'no'&& record.data.estado == 'almacen'){
                        return String.format('<div ext:qtip="Optimo"><b><font color="red">{0}</font></b><br></div>', value);
                    }else{
                        return String.format('<div ext:qtip="Optimo"><b><font color="black">{0}</font></b><br></div>', value);

                    }

                }
            },
            type:'TextArea',
            filters:{pfiltro:'det.descripcion',type:'string'},
            id_grupo:0,
            grid:false,
            form:true
        },
					{
					 config : {
						 name : 'tipo',
						 fieldLabel : 'Tipo',
						 anchor: '100%',
						 allowBlank : false,
						 emptyText : 'Tipo...',
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
						 triggerAction : 'all',
						 lazyRender : true,
						 mode : 'remote',
						 pageSize : 25,
						 listWidth:'450',
						 maxHeight : 450,
						 queryDelay : 1000,
						 tasignacion : true,
						 tdata:{},
						 tcls:'Proveedor',
						 gwidth : 170,
						 minChars : 2,
						 resizable:true,
						 renderer: function(value, p, record) {
								 if(record.data.revisado == 'no'&& record.data.estado == 'almacen'){
										 return String.format('<div ext:qtip="Optimo"><b><font color="red">{0}</font></b><br></div>', record.data.tipo);
								 }else{
										 return String.format('<div ext:qtip="Optimo"><b><font color="black">{0}</font></b><br></div>', record.data.tipo);
								 }

						 }
					 },
					 type : 'AwesomeCombo',
					 id_grupo : 0,
					 grid: true,
					 form: true
				 },
				// {
        //     config:{
        //         name:'tipo',
        //         fieldLabel:'Tipo',
        //         allowBlank:false,
        //         emptyText:'Elija una opción...',
        //         typeAhead: true,
        //         triggerAction: 'all',
        //         lazyRender:true,
        //         mode: 'local',
        //         width: 200,
        //         gwidth: 50,
        //         store:['Consumible','Rotable','Herramienta','Otros Cargos'],
        //         renderer: function(value, p, record) {
        //             if(record.data.revisado == 'no'&& record.data.estado == 'almacen'){
        //                 return String.format('<div ext:qtip="Optimo"><b><font color="red">{0}</font></b><br></div>', value);
        //             }else{
        //                 return String.format('<div ext:qtip="Optimo"><b><font color="black">{0}</font></b><br></div>', value);
				//
        //             }
				//
        //         }
				//
        //     },
        //     type:'ComboBox',
        //     id_grupo:0,
        //     grid:false,
        //     form:true
				//
        // },
        {
            config:{
                name: 'explicacion_detallada_part',
                fieldLabel: '<b style="color:#0003B0; font-size:12px;">Explicacion Detallada P/N</b>',
                allowBlank: true,
                width: 200,
                gwidth: 180,
								galign:'center',
                maxLength:100,
                renderer: function(value, p, record) {
                    if(record.data.revisado == 'no'&& record.data.estado == 'almacen'){
                        return String.format('<div style="text-align:left;" ext:qtip="Optimo"><b><font color="red">{0}</font></b><br></div>', value);
                    }else{
                        return String.format('<div style="text-align:left;" ext:qtip="Optimo"><b><font color="black">{0}</font></b><br></div>', value);

                    }

                }
            },
            type:'TextArea',
            filters:{pfiltro:'det.explicacion_detallada_part',type:'string'},
            id_grupo:0,
            grid:true,
						egrid:true,
						colorEgrid:'red',
            form:true
        },


				/*Separando por grupo para para ganar espacio
				fin grupo 1*/
        {
            config:{
                name: 'cantidad_sol',
                fieldLabel: '<b style="color:#0003B0; font-size:12px;">Cantidad</b>',
                allowBlank: true,
                width: 200,
                gwidth: 120,
								enableKeyEvents: true,
								galign:'right',
								style: {
													background:'#FFCD8C'
												},
                maxLength:4,
                renderer: function(value, p, record) {
                    if(record.data.revisado == 'no'&& record.data.estado == 'almacen'){
                        return String.format('<div ext:qtip="Optimo"><b><font color="red">{0}</font></b><br></div>', value);
                    }else{
                        return String.format('<div ext:qtip="Optimo"><b><font color="black">{0}</font></b><br></div>', value);

                    }

                }
            },
            type:'NumberField',
            filters:{pfiltro:'det.cantidad_sol',type:'numeric'},
            id_grupo:1,
            grid:true,
						egrid:true,
						colorEgrid:'red',
            form:true
        },
				/*Aumentando el campo precio unitario para registrar (Ismael Valdivia 10/02/2020)*/
				{
            config:{
                name: 'precio_unitario',
                fieldLabel: '<b style="color:#0003B0; font-size:12px;">Precio <br>Unitario Referencial</b>',
                allowBlank: false,
                width: 200,
								style: {
													background:'#FFCD8C'
												},
                gwidth: 130,
								galign:'center',
                //maxLength:,
								decimalPrecision : 2,
								enableKeyEvents : true,
								renderer:function (value,p,record){
									if(record.data.tipo_reg != 'summary'){
										return String.format('<p style="text-align:right;">{0}</p>', Ext.util.Format.number(record.data['precio_unitario'],'0,000.00'));
									}
									else{
											return '<b><p style="font-size:20px; color:blue; font-weight:bold; text-align:right;">TOTAL: &nbsp;&nbsp; </p></b>';
									}
								}
            },
            type:'MoneyField',
            filters:{pfiltro:'det.precio_unitario',type:'numeric'},
            id_grupo:1,
            grid:true,
						egrid:true,
						colorEgrid:'red',
            form:true
        },
				{
            config:{
                name: 'precio_total',
                fieldLabel: 'Precio Total',
                allowBlank: true,
                width: 200,
								readOnly:true,
								disabled:true,
								style: {
													background:'#C0FBC8'
												},
                gwidth: 100,
                //maxLength:4,
								decimalPrecision : 2,
								enableKeyEvents: true,
								galign:'right',
								renderer:function (value,p,record){
									if(record.data.tipo_reg != 'summary'){
										return  String.format('<div style="color:blue;"><b>{0}</b></div>', Ext.util.Format.number(value,'0,000.00'));
									}

									else{
										return  String.format('<div style="font-size:20px; text-align:right; color:blue;"><b>{0}<b></div>', Ext.util.Format.number(record.data.venta_total,'0,000.00'));
									}
								}
            },
            type:'MoneyField',
            filters:{pfiltro:'det.precio_total',type:'numeric'},
            id_grupo:1,
            grid:true,
            form:true
        },
				{
            config: {
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
                    baseParams: {par_filtro: ' un.codigo# un.descripcion'}
                }),
                valueField: 'id_unidad_medida',
                displayField: 'codigo',
                gdisplayField: 'codigo',
                tpl:'<tpl for="."><div class="x-combo-list-item"><p>{codigo}</p><p style="color: blue">{descripcion}</p></div></tpl>',
                hiddenName: 'id_unidad_medida',
                forceSelection: true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'remote',
                pageSize: 100,
                queryDelay: 100,
                width: 200,
                gwidth: 200,
                minChars: 2,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['codigo']);
                }
            },
            type: 'ComboBox',
            id_grupo: 0,
            filters: {pfiltro:' u.codigo', type:'string'},
            grid: false,
            form: true

        },
				/******************************************************************************/
				{
						config:{
								name:'id_centro_costo',
								origen:'CENTROCOSTO',
								allowBlank: false ,
								gdisplayField:'desc_centro_costo',
								disabled: false,
								width: 200,
								gwidth: 200,
								style: {
													background:'#FFCD8C'
												},
								fieldLabel: 'Centro de Costos',
								url: '../../sis_parametros/control/CentroCosto/listarCentroCostoFiltradoXDepto',
								baseParams: {filtrar: 'grupo_ep'},
						},
						type: 'ComboRec',
						filters:{pfiltro:'cc.codigo_cc',type:'string'},
						id_grupo: 1,
						form: true,
						grid:true
				},
				{
						config:{
								name:'id_orden_trabajo',
								sysorigen: 'sis_contabilidad',
								origen: 'OT' ,
								width: 200,
								fieldLabel: '<b style="color:#0003B0; font-size:12px;">Orden Trabajo</b>',
								allowBlank: false,
								renderer:function(value, p, record){return String.format('{0}', record.data['desc_orden_trabajo']);},
								baseParams:{par_filtro:'desc_orden#motivo_orden'},
								gdisplayField: 'desc_orden_trabajo',
								gwidth: 200,
								listeners: {
								  beforequery: function(qe){
									delete qe.combo.lastQuery;
								}
							},
						},
						type: 'ComboRec',
						id_grupo: 1,
						egrid:true,
						colorEgrid:'red',
						filters:{pfiltro:'ot.motivo_orden#ot.desc_orden',type:'string'},
						form: true,
						grid:true
				},
				{
						config: {
								name: 'id_concepto_ingas',
								fieldLabel: '<b style="color:#0003B0; font-size:12px;">Concepto</b>',
								allowBlank: false,
								emptyText: 'Concepto...',
								store: new Ext.data.JsonStore({
										url: '../../sis_parametros/control/ConceptoIngas/listarConceptoIngasMasPartida',
										id: 'id_concepto_ingas',
										root: 'datos',
										sortInfo: {
												field: 'desc_ingas',
												direction: 'ASC'
										},
										totalProperty: 'total',
										fields: ['id_concepto_ingas', 'tipo', 'desc_ingas', 'movimiento', 'desc_partida', 'id_grupo_ots', 'filtro_ot', 'requiere_ot'],
										remoteSort: true,
										baseParams: {
												par_filtro: 'desc_ingas#par.codigo',
												movimiento: 'gasto',
												autorizacion: 'adquisiciones',
												gestion_materiales: 'si'
										}
								}),
								valueField: 'id_concepto_ingas',
								displayField: 'desc_ingas',
								gdisplayField : 'desc_concepto_ingas',
								hiddenName: 'id_concepto_ingas',
								triggerAction: 'all',
								lazyRender: true,
								mode: 'remote',
								pageSize: 50,
								queryDelay: 1000,
								width: 200,
								listWidth:'500',
								gwidth: 350,
								minChars: 2,
								renderer : function(value, p, record) {
									return String.format('{0}', record.data['desc_concepto_ingas']);
								},
								forceSelection: true,
								typeAhead: false,
								listeners: {
									  beforequery: function(qe){
										delete qe.combo.lastQuery;
									}
							 },
							 qtip: 'Si el concepto de gasto que necesita no existe por favor  comuniquese con el área de presupuestos para solictar la creación',
							 tpl: '<tpl for="."><div class="x-combo-list-item"><p><b>{desc_ingas}</b></p><strong>{tipo}</strong><p>PARTIDA: {desc_partida}</p></div></tpl>',

						},
						type: 'ComboBox',
						id_grupo: 1,
						filters:{pfiltro:'cig.desc_ingas',type:'string'},
						egrid:true,
						colorEgrid:'red',
						form: true,
						grid:true
				},
        {
            config:{
                name: 'estado_reg',
                fieldLabel: 'Estado Reg.',
                allowBlank: true,
                width: 200,
                gwidth: 100,
                maxLength:10
            },
            type:'TextField',
            filters:{pfiltro:'det.estado_reg',type:'string'},
            id_grupo:0,
            grid:true,
            form:false
        },

        {
            config:{
                name: 'id_usuario_ai',
                fieldLabel: '',
                allowBlank: true,
                width: 200,
                gwidth: 100,
                maxLength:4
            },
            type:'Field',
            filters:{pfiltro:'det.id_usuario_ai',type:'numeric'},
            id_grupo:0,
            grid:false,
            form:false
        },
        {
            config:{
                name: 'usuario_ai',
                fieldLabel: 'Funcionaro AI',
                allowBlank: true,
                width: 200,
                gwidth: 100,
                maxLength:300
            },
            type:'TextField',
            filters:{pfiltro:'det.usuario_ai',type:'string'},
            id_grupo:0,
            grid:false,
            form:false
        },
        {
            config:{
                name: 'fecha_reg',
                fieldLabel: 'Fecha creación',
                allowBlank: true,
                width: 200,
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
            },
            type:'DateField',
            filters:{pfiltro:'det.fecha_reg',type:'date'},
            id_grupo:0,
            grid:true,
            form:false
        },
        {
            config:{
                name: 'usr_reg',
                fieldLabel: 'Creado por',
                allowBlank: true,
                width: 200,
                gwidth: 100,
                maxLength:4
            },
            type:'Field',
            filters:{pfiltro:'usu1.cuenta',type:'string'},
            id_grupo:0,
            grid:true,
            form:false
        },
        {
            config:{
                name: 'fecha_mod',
                fieldLabel: 'Fecha Modif.',
                allowBlank: true,
                width: 200,
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
            },
            type:'DateField',
            filters:{pfiltro:'det.fecha_mod',type:'date'},
            id_grupo:0,
            grid:true,
            form:false
        },
        {
            config:{
                name: 'usr_mod',
                fieldLabel: 'Modificado por',
                allowBlank: true,
                width: 200,
                gwidth: 100,
                maxLength:4
            },
            type:'Field',
            filters:{pfiltro:'usu2.cuenta',type:'string'},
            id_grupo:0,
            grid:true,
            form:false
        }

    ],
	tam_pag:50,
	fheight:480,
	fwidth:750,
	title:'Detalle',
	ActSave:'../../sis_gestion_materiales/control/DetalleSol/insertarDetalleSol',
	ActDel:'../../sis_gestion_materiales/control/DetalleSol/eliminarDetalleSol',
	ActList:'../../sis_gestion_materiales/control/DetalleSol/listarDetalleSol',
	id_store:'id_detalle',
	fields: [
		{name:'id_detalle', type: 'numeric'},
		{name:'id_producto_alkym', type: 'numeric'},

		{name:'id_gestion', type: 'numeric'},
		{name:'id_solicitud', type: 'numeric'},
		{name:'descripcion', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_unidad_medida', type: 'numeric'},
		{name:'nro_parte', type: 'string'},
		{name:'referencia', type: 'string'},
		{name:'nro_parte_alterno', type: 'string'},
		{name:'id_moneda', type: 'numeric'},
		/*Cambiando el campo precio por precio unitario*/
		//{name:'precio', type: 'numeric'},
		{name:'precio_unitario', type: 'numeric'},
		/***********************************************/
		{name:'cantidad_sol', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
        {name:'codigo', type: 'string'},
        {name:'desc_descripcion', type: 'string'},
        {name:'revisado', type: 'string'},
        {name:'tipo', type: 'string'},
        {name:'estado', type: 'string'},
        {name:'explicacion_detallada_part', type: 'string'},
	/*Aumentando los siguientes campos (Ismael Valdivia 31/01/2020)*/
	{name:'id_centro_costo', type: 'numeric'},
	{name:'id_concepto_ingas', type: 'numeric'},
	{name:'id_orden_trabajo', type: 'numeric'},
	{name:'desc_centro_costo', type: 'string'},
	{name:'desc_concepto_ingas', type: 'string'},
	{name:'desc_orden_trabajo', type: 'string'},
	{name:'desc_partida', type: 'string'},
	{name:'desc_cuenta', type: 'string'},
	{name:'desc_auxiliar', type: 'string'},
	{name:'precio_total', type: 'string'},

	{name:'venta_total', type: 'numeric'},
	{name:'tipo_reg', type: 'string'},
	{name:'control_edicion', type: 'string'},
	{name:'condicion_det', type: 'string'},

	],
	sortInfo:{
		field: 'id_detalle',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false,
	btest: false,
  bnew: false,

        onReloadPage: function (m) {
            this.maestro = m;
						this.store.baseParams.tipo_tramite = m.nro_tramite.substr(0, 2);
						var tb =this.tbar;

						//Aqui para oculatar el boton new
						/*if (m.nro_tramite.substr(0, 2) == 'GM' && (tb.items.get('b-new-' + this.idContenedor) != undefined && tb.items.get('b-new-' + this.idContenedor) != '')){
								//Phx.vista.MovimientoEntidad.superclass.preparaMenu.call(this);
								tb.items.get('b-new-' + this.idContenedor).hide();//Ext.getCmp('grupo_alkym').hide();
								tb.items.get('b-del-' + this.idContenedor).hide();
						}*/



						//console.log("llega aqui el dato para esconder",m.nro_tramite.substr(0, 2));
						if (m.nro_tramite.substr(0, 2) == 'GR') {
							this.cm.setHidden(2, false);
						} else {
							this.cm.setHidden(2, true);
						}
						this.store.baseParams = {id_solicitud: this.maestro.id_solicitud,
																	   condcion_editar: 'grilla',
																	   tipo_tramite : m.nro_tramite.substr(0, 2)};
            this.load({params: {start: 0, limit: 50}});
        },
        loadValoresIniciales: function () {
            this.Cmp.id_solicitud.setValue(this.maestro.id_solicitud);
            Phx.vista.DetalleSol.superclass.loadValoresIniciales.call(this);
        },
    preparaMenu:function(n){
        var tb =this.tbar;
				var rec = this.getSelectedData();
        Phx.vista.DetalleSol.superclass.preparaMenu.call(this,n);
				this.Cmp.id_concepto_ingas.store.baseParams.id_gestion = this.maestro.id_gestion;
				this.Cmp.id_concepto_ingas.modificado = true;
				this.Cmp.id_centro_costo.store.baseParams.id_depto = this.maestro.id_depto;
				this.Cmp.id_centro_costo.store.baseParams.id_gestion = this.maestro.id_gestion;
				this.Cmp.id_centro_costo.store.baseParams.codigo_subsistema = 'ADQ';

        return tb;
    },

    liberaMenu: function() {
        var tb = Phx.vista.DetalleSol.superclass.liberaMenu.call(this);
        return tb;
    },

		/*Aumentando esta parte para recuperar el centro de costo Ismael Valdivia (31/01/2020)*/
		onButtonNew: function() {
			Phx.vista.DetalleSol.superclass.onButtonNew.call(this);

			this.Cmp.nro_parte.setDisabled(false);
			this.Cmp.nro_parte_alterno.setDisabled(false);

			/*Aqui aumentamos para completar los datos*/
			this.Cmp.nro_parte.on('select', function (cmp, rec) {

				this.Cmp.id_unidad_medida.reset();
				this.Cmp.tipo.reset();
				this.Cmp.descripcion.reset();
				this.Cmp.id_producto_alkym.reset();

				this.Cmp.id_producto_alkym.setValue(rec.data.idproducto);

				this.Cmp.descripcion.setValue(rec.data.descripcion);
				console.log("aqui llega el rec seleccionado PN",rec);
				this.Cmp.id_unidad_medida.store.baseParams.id_unidad_medida = rec.data.idunidadmedida;

				this.Cmp.id_unidad_medida.store.load({params:{start:0,limit:50},
							 callback : function (r) {
										if (r.length == 1 ) {
													this.Cmp.id_unidad_medida.setValue(r[0].data.id_unidad_medida);
													this.Cmp.id_unidad_medida.fireEvent('select', this.Cmp.id_unidad_medida,r[0],0);
													this.Cmp.id_unidad_medida.store.baseParams.id_unidad_medida = '';
											} else {
												this.Cmp.id_unidad_medida.store.baseParams.id_unidad_medida = '';
											}
								}, scope : this
						});

				this.Cmp.tipo.store.load({params:{start:0,limit:50},
							 callback : function (r) {
										 for (var i = 0; i < r.length; i++) {
											 if (r[i].data.descripcion == rec.data.tipoproducto) {
												 this.Cmp.tipo.setValue(r[i].data.descripcion);
												 this.Cmp.tipo.fireEvent('select', this.Cmp.tipo,r[i]);
											 }
										 }
								}, scope : this
						});


			}, this);
			/******************************************/







			if (this.store.baseParams.tipo_tramite == 'GR') {
				this.mostrarComponente(this.Cmp.serial);
				this.ocultarComponente(this.Cmp.referencia);
				this.ocultarComponente(this.Cmp.condicion_det);
			} else {
				this.mostrarComponente(this.Cmp.referencia);
				this.ocultarComponente(this.Cmp.serial);
				this.ocultarComponente(this.Cmp.condicion_det);
			}
			Ext.Ajax.request({
					url:'../../sis_gestion_materiales/control/DetalleSol/getCentroCosto',
					params:{id_gestion:this.maestro.id_gestion,
							    id_solicitud:this.maestro.id_solicitud},
					success:function(resp){
							var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
							this.store.baseParams.id_centro_costo_rec = reg.ROOT.datos.id_centro_costo;
							/*Aqui recuperamos la OT*/
							this.store.baseParams.id_cp_rec = reg.ROOT.datos.id_cp;
							/************************/
							this.load({params:{start:0, limit:this.tam_pag}});

					},
					failure: this.conexionFailure,
					timeout:this.timeout,
					scope:this
			});
			this.window.items.items[0].body.dom.style.background = '#548DCA';
			this.window.mask.dom.style.background = '#7E7E7E';
			this.window.mask.dom.style.opacity = '0.8';
			this.Cmp.id_centro_costo.store.baseParams.id_depto = this.maestro.id_depto;
			this.Cmp.id_centro_costo.store.baseParams.id_gestion = this.maestro.id_gestion;
			this.Cmp.id_centro_costo.store.baseParams.codigo_subsistema = 'ADQ';
			this.Cmp.id_concepto_ingas.store.baseParams.id_gestion = this.maestro.id_gestion;
			this.Cmp.id_concepto_ingas.modificado = true;
			/*Mandaremos la gestion para recuperar el id auxiliar, id_cuenta, id_partida (Ismael Valdivia 14/02/2020)*/
			this.Cmp.id_gestion.setValue(this.maestro.id_gestion);
			this.Cmp.serial.setValue(this.Cmp.referencia.getValue());

			this.Cmp.serial.on('change',function(field,newValue,oldValue){
				this.Cmp.referencia.setValue(this.Cmp.serial.getValue());
			},this);
			/************************************************************************/

			/*Aumentamos para que el centro de costos este por defecto Flota boa (Ismael Valdivia 17/03/2020)*/
			if (this.Cmp.id_concepto_ingas.getValue() == null || this.Cmp.id_concepto_ingas.getValue() == '') {
				this.Cmp.id_centro_costo.store.load({params:{start:0,limit:50},
					 callback : function (r) {
						 	  this.Cmp.id_centro_costo.setValue(this.store.baseParams.id_centro_costo_rec);
								this.Cmp.id_centro_costo.fireEvent('select',this.Cmp.id_centro_costo, this.Cmp.id_centro_costo.store.getById(this.store.baseParams.id_centro_costo_rec));
						}, scope : this
				});
			}
			/*************************************************************************************************/

			this.Cmp.id_orden_trabajo.store.baseParams.fecha_solicitud = this.maestro.fecha_solicitud.dateFormat('d/m/Y');
			this.Cmp.id_orden_trabajo.modificado = true;

			this.Cmp.id_centro_costo.on('select', function (cmp, rec) {
				console.log("aqui seleccion rec",rec);
				console.log("aqui seleccion cmp",cmp);
				this.Cmp.id_orden_trabajo.store.baseParams.id_centro_costo = rec.data.id_centro_costo;
				this.Cmp.id_orden_trabajo.modificado = true;
				/*Habilitamos el campo centro de concepto cuando seleccionemos el centro de costo*/
					this.Cmp.id_concepto_ingas.el.dom.style.background='#FFCD8C';
                    this.Cmp.id_orden_trabajo.reset();
					/*Aumentando para que se seleccion el CP obtenido en sistema control Mantenimiento*/
					this.Cmp.id_orden_trabajo.store.load({params:{start:0,limit:50},
						 callback : function (r) {
									this.Cmp.id_orden_trabajo.setValue(this.store.baseParams.id_cp_rec);
									this.Cmp.id_orden_trabajo.fireEvent('select',this.Cmp.id_orden_trabajo, this.Cmp.id_orden_trabajo.store.getById(this.store.baseParams.id_cp_rec));
							}, scope : this
					});
					/*¨*********************************************************************************/

			}, this);

            /*Aqui mandaremos los filtros para listar conceptos que perteneces a la OT seleccionada */
			this.Cmp.id_orden_trabajo.on('select', function (cmp, rec) {
					this.Cmp.id_concepto_ingas.store.baseParams.id_grupo_ots = rec.json.id_grupo_ots;
					this.Cmp.id_concepto_ingas.reset();
			}, this);



			if (this.Cmp.id_centro_costo.getValue() != '' || this.Cmp.id_concepto_ingas.getValue() != null) {
				this.Cmp.id_orden_trabajo.store.baseParams.id_centro_costo = this.Cmp.id_centro_costo.getValue();
				this.Cmp.id_concepto_ingas.el.dom.style.background='#FFCD8C';
			}

						// this.Cmp.id_concepto_ingas.on('select', function (cmp, rec) {
			// 	/*Habilitamos el campo centro de concepto cuando seleccionemos el centro de costo*/
			// 	this.Cmp.id_orden_trabajo.el.dom.style.background='#FFCD8C';
			//
			// 	this.Cmp.id_orden_trabajo.store.baseParams = Ext.apply(this.Cmp.id_orden_trabajo.store.baseParams, {
			// 																							 filtro_ot:rec.data.filtro_ot,
			// 													 requiere_ot:rec.data.requiere_ot,
			// 													 id_grupo_ots:rec.data.id_grupo_ots
			// 												});
			// 	this.Cmp.id_orden_trabajo.modificado = true;
			//
			// 	//this.Cmp.id_orden_trabajo.reset();
			//
			//
			// 	this.Cmp.id_orden_trabajo.store.load({params:{start:0,limit:50},
			// 		 callback : function (r) {
			// 					if (this.Cmp.id_centro_costo.getValue() != this.store.baseParams.id_centro_costo_rec && r.length == 1) {
			// 							this.Cmp.id_orden_trabajo.setValue(r[0].data.id_orden_trabajo);
			// 							this.Cmp.id_orden_trabajo.fireEvent('select',this.Cmp.id_orden_trabajo, this.Cmp.id_orden_trabajo.store.getById(r[0].data.id_orden_trabajo));
			//
			// 					} else if (this.Cmp.id_centro_costo.getValue() != this.store.baseParams.id_centro_costo_rec && r.length > 1) {
			// 						this.Cmp.id_orden_trabajo.setValue('');
			// 					}
			// 					// else if (this.Cmp.id_centro_costo.getValue() == this.store.baseParams.id_centro_costo_rec) {
			// 			 	  // this.Cmp.id_orden_trabajo.setValue(this.maestro.id_matricula);
			// 					// this.Cmp.id_orden_trabajo.fireEvent('select',this.Cmp.id_orden_trabajo, this.Cmp.id_orden_trabajo.store.getById(this.maestro.id_matricula));
			// 					// }
			// 			}, scope : this
			// 	});
			//
			// 	/*******************************************************************/
			// }, this);

			if (this.Cmp.id_orden_trabajo.getValue() != '' || this.Cmp.id_orden_trabajo.getValue() != null) {
				this.Cmp.id_orden_trabajo.el.dom.style.background='#FFCD8C';
			}

			this.Cmp.precio_unitario.on('keyup', function (cmp, rec) {
				var cantidad = this.Cmp.cantidad_sol.getValue();
				var precio_uni = this.Cmp.precio_unitario.getValue();
				this.Cmp.precio_total.setValue(cantidad*precio_uni);
			}, this);

			this.Cmp.cantidad_sol.on('keyup', function (cmp, rec) {
				var cantidad = this.Cmp.cantidad_sol.getValue();
				var precio_uni = this.Cmp.precio_unitario.getValue();
				this.Cmp.precio_total.setValue(cantidad*precio_uni);
			}, this);

		},

		onButtonEdit: function() {
			Phx.vista.DetalleSol.superclass.onButtonEdit.call(this);
			this.Cmp.control_edicion.setValue('botonEditar');
			if (this.store.baseParams.tipo_tramite == 'GR') {
				this.mostrarComponente(this.Cmp.serial);
				this.ocultarComponente(this.Cmp.referencia);
				this.mostrarComponente(this.Cmp.condicion_det);
			} else {
				this.mostrarComponente(this.Cmp.referencia);
				this.ocultarComponente(this.Cmp.serial);
				this.ocultarComponente(this.Cmp.condicion_det);
			}
			Ext.Ajax.request({
					url:'../../sis_gestion_materiales/control/DetalleSol/getCentroCosto',
					params:{id_gestion:this.maestro.id_gestion,
							    id_solicitud:this.maestro.id_solicitud},
					success:function(resp){
							var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
							this.store.baseParams.id_centro_costo_rec = reg.ROOT.datos.id_centro_costo;
							/*Aqui recuperamos la OT*/
							this.store.baseParams.id_cp_rec = reg.ROOT.datos.id_cp;
							/************************/
							this.load({params:{start:0, limit:this.tam_pag}});

					},
					failure: this.conexionFailure,
					timeout:this.timeout,
					scope:this
			});
			this.window.items.items[0].body.dom.style.background = '#548DCA';
			this.window.mask.dom.style.background = '#7E7E7E';
			this.window.mask.dom.style.opacity = '0.8';
			this.Cmp.id_centro_costo.store.baseParams.id_depto = this.maestro.id_depto;
			this.Cmp.id_centro_costo.store.baseParams.id_gestion = this.maestro.id_gestion;
			this.Cmp.id_centro_costo.store.baseParams.codigo_subsistema = 'ADQ';
			this.Cmp.id_concepto_ingas.store.baseParams.id_gestion = this.maestro.id_gestion;
			this.Cmp.id_concepto_ingas.modificado = true;
			/*Mandaremos la gestion para recuperar el id auxiliar, id_cuenta, id_partida (Ismael Valdivia 14/02/2020)*/
			this.Cmp.id_gestion.setValue(this.maestro.id_gestion);
			this.Cmp.serial.setValue(this.Cmp.referencia.getValue());

			this.Cmp.serial.on('change',function(field,newValue,oldValue){
				this.Cmp.referencia.setValue(this.Cmp.serial.getValue());
			},this);
			/************************************************************************/

			/*Aumentamos para que el centro de costos este por defecto Flota boa (Ismael Valdivia 17/03/2020)*/
			if (this.Cmp.id_concepto_ingas.getValue() == null || this.Cmp.id_concepto_ingas.getValue() == '') {
				console.log("aquillega la carga",this.Cmp.id_centro_costo);
				this.Cmp.id_centro_costo.store.load({params:{start:0,limit:50},
					 callback : function (r) {
						 	  this.Cmp.id_centro_costo.setValue(this.store.baseParams.id_centro_costo_rec);
								this.Cmp.id_centro_costo.fireEvent('select',this.Cmp.id_centro_costo, this.Cmp.id_centro_costo.store.getById(this.store.baseParams.id_centro_costo_rec));
						}, scope : this
				});
			}
			/*************************************************************************************************/


			this.Cmp.id_orden_trabajo.store.baseParams.fecha_solicitud = this.maestro.fecha_solicitud.dateFormat('d/m/Y');
			this.Cmp.id_orden_trabajo.modificado = true;

			this.Cmp.id_centro_costo.on('select', function (cmp, rec) {
				if (rec != undefined) {
					this.Cmp.id_orden_trabajo.store.baseParams.id_centro_costo = rec.data.id_centro_costo;
				} else {
					this.Cmp.id_orden_trabajo.store.baseParams.id_centro_costo = this.store.baseParams.id_centro_costo_rec;
				}
				this.Cmp.id_orden_trabajo.modificado = true;
				/*Habilitamos el campo centro de concepto cuando seleccionemos el centro de costo*/
					this.Cmp.id_concepto_ingas.el.dom.style.background='#FFCD8C';
					this.Cmp.id_orden_trabajo.reset();

					/*Aumentando para que se seleccion el CP obtenido en sistema control Mantenimiento*/
					this.Cmp.id_orden_trabajo.store.load({params:{start:0,limit:50},
						 callback : function (r) {
									this.Cmp.id_orden_trabajo.setValue(this.store.baseParams.id_cp_rec);
									this.Cmp.id_orden_trabajo.fireEvent('select',this.Cmp.id_orden_trabajo, this.Cmp.id_orden_trabajo.store.getById(this.store.baseParams.id_cp_rec));
							}, scope : this
					});
					/*¨*********************************************************************************/





			}, this);

			/*Aqui mandaremos los filtros para listar conceptos que perteneces a la OT seleccionada */
			this.Cmp.id_orden_trabajo.on('select', function (cmp, rec) {
					this.Cmp.id_concepto_ingas.store.baseParams.id_grupo_ots = rec.json.id_grupo_ots;
					this.Cmp.id_concepto_ingas.reset();
			}, this);



			if (this.Cmp.id_centro_costo.getValue() != '' || this.Cmp.id_concepto_ingas.getValue() != null) {
				this.Cmp.id_orden_trabajo.store.baseParams.id_centro_costo = this.Cmp.id_centro_costo.getValue();
				this.Cmp.id_concepto_ingas.el.dom.style.background='#FFCD8C';
			}



			// this.Cmp.id_concepto_ingas.on('select', function (cmp, rec) {
			// 	/*Habilitamos el campo centro de concepto cuando seleccionemos el centro de costo*/
			// 	this.Cmp.id_orden_trabajo.el.dom.style.background='#FFCD8C';
			//
			// 	this.Cmp.id_orden_trabajo.store.baseParams = Ext.apply(this.Cmp.id_orden_trabajo.store.baseParams, {
			// 																							 filtro_ot:rec.data.filtro_ot,
			// 													 requiere_ot:rec.data.requiere_ot,
			// 													 id_grupo_ots:rec.data.id_grupo_ots
			// 												});
			// 	this.Cmp.id_orden_trabajo.modificado = true;
			//
			//
			// 	this.Cmp.id_orden_trabajo.reset();
			//
			//
			// 	this.Cmp.id_orden_trabajo.store.load({params:{start:0,limit:50},
			// 		 callback : function (r) {
			// 					if (this.Cmp.id_centro_costo.getValue() != this.store.baseParams.id_centro_costo_rec && r.length == 1) {
			// 							this.Cmp.id_orden_trabajo.setValue(r[0].data.id_orden_trabajo);
			// 							this.Cmp.id_orden_trabajo.fireEvent('select',this.Cmp.id_orden_trabajo, this.Cmp.id_orden_trabajo.store.getById(r[0].data.id_orden_trabajo));
			//
			//
			// 					} else if (this.Cmp.id_centro_costo.getValue() != this.store.baseParams.id_centro_costo_rec && r.length > 1) {
			// 						this.Cmp.id_orden_trabajo.setValue('');
			// 					}
			// 					// else if (this.Cmp.id_centro_costo.getValue() == this.store.baseParams.id_centro_costo_rec) {
			// 			 	  // this.Cmp.id_orden_trabajo.setValue(this.maestro.id_matricula);
			// 					// this.Cmp.id_orden_trabajo.fireEvent('select',this.Cmp.id_orden_trabajo, this.Cmp.id_orden_trabajo.store.getById(this.maestro.id_matricula));
			// 					// }
			// 			}, scope : this
			// 	});
			//
			// 	/*******************************************************************/
			// }, this);

			if (this.Cmp.id_orden_trabajo.getValue() != '' || this.Cmp.id_orden_trabajo.getValue() != null) {
				this.Cmp.id_orden_trabajo.el.dom.style.background='#FFCD8C';
			}

			this.Cmp.precio_unitario.on('keyup', function (cmp, rec) {
				var cantidad = this.Cmp.cantidad_sol.getValue();
				var precio_uni = this.Cmp.precio_unitario.getValue();
				this.Cmp.precio_total.setValue(cantidad*precio_uni);
			}, this);

			this.Cmp.cantidad_sol.on('keyup', function (cmp, rec) {
				var cantidad = this.Cmp.cantidad_sol.getValue();
				var precio_uni = this.Cmp.precio_unitario.getValue();
				this.Cmp.precio_total.setValue(cantidad*precio_uni);
			}, this);



		},

		iniciarEventos : function () {
			this.grid.on('afteredit',function(e){
				this.Cmp.control_edicion.setValue(this.store.baseParams.condcion_editar);
			 this.onButtonSave(this);
    	}, this);
		},

		onButtonSave:function(o){
        var filas=this.store.getModifiedRecords();
        if(filas.length>0){
                //prepara una matriz para guardar los datos de la grilla
                var data={};
                for(var i=0;i<filas.length;i++){
                    data[i]=filas[i].data;
                    data[i]._fila=this.store.indexOf(filas[i])+1
                    this.agregarArgsExtraSubmit(filas[i].data);
                    Ext.apply(data[i],this.argumentExtraSubmit);
                }
                Phx.CP.loadingHide();
                Ext.Ajax.request({
                    url:this.ActSave,
                    params:{_tipo:'matriz','row':String(Ext.util.JSON.encode(data))},
                    //isUpload:this.fileUpload,
                    success:this.successSave,
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });
        }
    },

		successSave:function(resp){
				Phx.vista.DetalleSol.superclass.successSave.call(this,resp);
				this.store.commitChanges();
				Phx.CP.getPagina(this.idContenedorPadre).reload();
				Phx.CP.loadingHide();

		},
		/****************************************************/
		/**************************************************************************************/


})
</script>
