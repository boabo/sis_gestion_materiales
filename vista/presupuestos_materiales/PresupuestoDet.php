<?php
/**
*@package pXP
*@file gen-PresupuestoDet.php
*@author  (Ismael Valdivia)
*@date 16-04-2020 08:15:00
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.PresupuestoDet=Ext.extend(Phx.gridInterfaz,{
	constructor:function(config){
				this.maestro=config.maestro;
        Phx.vista.PresupuestoDet.superclass.constructor.call(this,config);
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
                name: 'id_solicitud'
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
						config: {
								name: 'id_concepto_ingas',
								fieldLabel: 'Concepto',
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
						//egrid:true,
						form: true,
						grid:true
				},
				{
						config:{
								name:'id_orden_trabajo',
								sysorigen: 'sis_contabilidad',
								origen: 'OT' ,
								width: 200,
								fieldLabel: 'Orden Trabajo',
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
						//egrid:true,
						filters:{pfiltro:'ot.motivo_orden#ot.desc_orden',type:'string'},
						form: true,
						grid:true
				},
        {
            config:{
                name: 'cantidad_sol',
                fieldLabel: 'Cantidad',
                allowBlank: true,
                width: 200,
                gwidth: 150,
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
						//egrid:true,
            form:true
        },
				/*Aumentando el campo precio unitario para registrar (Ismael Valdivia 10/02/2020)*/
				{
            config:{
                name: 'precio_unitario',
                fieldLabel: 'Precio Unitario Referencial',
                allowBlank: false,
                width: 200,
								style: {
													background:'#FFCD8C'
												},
                gwidth: 150,
								galign:'right',
                //maxLength:,
								decimalPrecision : 2,
								enableKeyEvents : true,
								renderer:function (value,p,record){
									if(record.data.tipo_reg != 'summary'){
										return String.format('{0}', Ext.util.Format.number(record.data['precio_unitario'],'0,000.00'));
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
						//egrid:true,
            form:true
        },
				{
            config:{
                name: 'precio_total',
                fieldLabel: 'Precio Total',
                allowBlank: false,
                width: 200,
								readOnly:true,
								style: {
													background:'#C0FBC8'
												},
                gwidth: 150,
                //maxLength:4,
								decimalPrecision : 2,
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
            config:{
                name: 'referencia',
                fieldLabel: 'Referencia',
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
            type:'TextField',
            filters:{pfiltro:'det.referencia',type:'string'},
            id_grupo:0,
            grid:true,
            form:true
        },
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
            grid:true,
            form:true
        },
        {
            config:{
                name: 'explicacion_detallada_part',
                fieldLabel: 'Explicacion Detallada P/N',
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
            filters:{pfiltro:'det.explicacion_detallada_part',type:'string'},
            id_grupo:0,
            grid:true,
						//egrid:true,
            form:true
        },
        {
            config:{
                name:'tipo',
                fieldLabel:'Tipo',
                allowBlank:false,
                emptyText:'Elija una opción...',
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',
                width: 200,
                gwidth: 80,
                store:['Consumible','Rotable','Herramienta','Otros Cargos'],
                renderer: function(value, p, record) {
                    if(record.data.revisado == 'no'&& record.data.estado == 'almacen'){
                        return String.format('<div ext:qtip="Optimo"><b><font color="red">{0}</font></b><br></div>', value);
                    }else{
                        return String.format('<div ext:qtip="Optimo"><b><font color="black">{0}</font></b><br></div>', value);

                    }

                }

            },
            type:'ComboBox',
            id_grupo:0,
            grid:true,
            form:true

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
            grid:true,
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




	],
	sortInfo:{
		field: 'id_detalle',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false,
	btest: false,
  bnew: false,

        onReloadPage: function (m) {
            this.maestro = m;
					  this.store.baseParams = {id_solicitud: this.maestro.id_solicitud};
            this.load({params: {start: 0, limit: 50}});
        },
        loadValoresIniciales: function () {
            this.Cmp.id_solicitud.setValue(this.maestro.id_solicitud);
            Phx.vista.PresupuestoDet.superclass.loadValoresIniciales.call(this);
        },
    preparaMenu:function(n){
        var tb =this.tbar;
				var rec = this.getSelectedData();
        Phx.vista.PresupuestoDet.superclass.preparaMenu.call(this,n);
				this.Cmp.id_concepto_ingas.store.baseParams.id_gestion = this.maestro.id_gestion;
				this.Cmp.id_concepto_ingas.modificado = true;
				this.Cmp.id_centro_costo.store.baseParams.id_depto = this.maestro.id_depto;
				this.Cmp.id_centro_costo.store.baseParams.id_gestion = this.maestro.id_gestion;
				this.Cmp.id_centro_costo.store.baseParams.codigo_subsistema = 'ADQ';

        return tb;
    },

    liberaMenu: function() {
        var tb = Phx.vista.PresupuestoDet.superclass.liberaMenu.call(this);
        return tb;
    },


		iniciarEventos : function () {
			this.grid.on('afteredit',function(e){
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
                    isUpload:this.fileUpload,
                    success:this.successSaveFileUpload,
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });
        }
    },

		successSave:function(resp){
				Phx.vista.PresupuestoDet.superclass.successSave.call(this,resp);
				this.store.commitChanges();
				//Phx.CP.getPagina(this.idContenedorPadre).reload();
				Phx.CP.loadingHide();

		},

		onButtonNew:function(){
			Phx.vista.PresupuestoDet.superclass.onButtonNew.call(this);
			this.window.items.items[0].body.dom.style.background = '#548DCA';
			this.window.mask.dom.style.background = '#7E7E7E';
			this.window.mask.dom.style.opacity = '0.8';
		},

		onButtonEdit:function(){
			Phx.vista.PresupuestoDet.superclass.onButtonEdit.call(this);
			this.window.items.items[0].body.dom.style.background = '#548DCA';
			this.window.mask.dom.style.background = '#7E7E7E';
			this.window.mask.dom.style.opacity = '0.8';
		}
		/****************************************************/
		/**************************************************************************************/


})
</script>
