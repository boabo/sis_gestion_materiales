<?php
/**
*@package pXP
*@file gen-LogModificaciones.php
*@author  (ismael.valdivia)
*@date 22-08-2022 12:38:25
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.LogModificaciones=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.LogModificaciones.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}})
	},

	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_log'
			},
			type:'Field',
			form:true
		},
		// {
		// 	config:{
		// 		name: 'estado_reg',
		// 		fieldLabel: 'Estado Reg.',
		// 		allowBlank: true,
		// 		anchor: '80%',
		// 		gwidth: 100,
		// 		maxLength:10
		// 	},
		// 		type:'TextField',
		// 		filters:{pfiltro:'log_tram.estado_reg',type:'string'},
		// 		id_grupo:1,
		// 		grid:true,
		// 		form:false
		// },
		{
			config:{
				name: 'nro_tramite',
				fieldLabel: 'Nro Trámite',
				allowBlank: true,
				anchor: '80%',
				gwidth: 160,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'sol.nro_tramite',type:'string'},
				id_grupo:1,
				grid:true,
				bottom_filter:true,
				form:false
		},
		{
			config:{
				name: 'desc_funcionario_solicitante',
				fieldLabel: 'Solicitado Por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 250,
				bottom_filter:true,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'fun.desc_funcionario1',type:'string'},
				id_grupo:1,
				grid:true,
				bottom_filter:true,
				form:false
		},
		// {
		// 	config: {
		// 		name: 'id_funcionario_solicitante',
		// 		fieldLabel: 'id_funcionario_solicitante',
		// 		allowBlank: true,
		// 		emptyText: 'Elija una opción...',
		// 		store: new Ext.data.JsonStore({
		// 			url: '../../sis_/control/Clase/Metodo',
		// 			id: 'id_',
		// 			root: 'datos',
		// 			sortInfo: {
		// 				field: 'nombre',
		// 				direction: 'ASC'
		// 			},
		// 			totalProperty: 'total',
		// 			fields: ['id_', 'nombre', 'codigo'],
		// 			remoteSort: true,
		// 			baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
		// 		}),
		// 		valueField: 'id_',
		// 		displayField: 'nombre',
		// 		gdisplayField: 'desc_',
		// 		hiddenName: 'id_funcionario_solicitante',
		// 		forceSelection: true,
		// 		typeAhead: false,
		// 		triggerAction: 'all',
		// 		lazyRender: true,
		// 		mode: 'remote',
		// 		pageSize: 15,
		// 		queryDelay: 1000,
		// 		anchor: '100%',
		// 		gwidth: 150,
		// 		minChars: 2,
		// 		renderer : function(value, p, record) {
		// 			return String.format('{0}', record.data['desc_']);
		// 		}
		// 	},
		// 	type: 'ComboBox',
		// 	id_grupo: 0,
		// 	filters: {pfiltro: 'movtip.nombre',type: 'string'},
		// 	grid: true,
		// 	form: true
		// },
		{
			config:{
				name: 'motivo_modificacion',
				fieldLabel: 'Motivo Modificación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 350,
				//maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'log_tram.motivo_modificacion',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nro_po_anterior',
				fieldLabel: 'Nro PO Anterior',
				allowBlank: true,
				anchor: '80%',
				gwidth: 120,
				maxLength:200
			},
				type:'TextField',
				filters:{pfiltro:'log_tram.nro_po_anterior',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nro_po_nuevo',
				fieldLabel: 'Nro PO Modificado',
				allowBlank: true,
				anchor: '80%',
				gwidth: 120,
				maxLength:200
			},
				type:'TextField',
				filters:{pfiltro:'log_tram.nro_po_nuevo',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'fecha_cotizacion_antigua',
				fieldLabel: 'Fecha Cot. Anterior',
				allowBlank: true,
				anchor: '80%',
				gwidth: 120,
							format: 'd/m/Y',
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'log_tram.fecha_cotizacion_antigua',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'fecha_cotizacion_nueva',
				fieldLabel: 'Fecha Cot. Nueva',
				allowBlank: true,
				anchor: '80%',
				gwidth: 120,
							format: 'd/m/Y',
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'log_tram.fecha_cotizacion_nueva',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nro_cotizacion_anterior',
				fieldLabel: 'Nro. Cot. Anterior',
				allowBlank: true,
				anchor: '80%',
				gwidth: 130,
				maxLength:200
			},
				type:'TextField',
				filters:{pfiltro:'log_tram.nro_cotizacion_anterior',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nro_cotizacion_nueva',
				fieldLabel: 'Nro. Cot. Nueva',
				allowBlank: true,
				anchor: '80%',
				gwidth: 120,
				maxLength:200
			},
				type:'TextField',
				filters:{pfiltro:'log_tram.nro_cotizacion_nueva',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		// {
		// 	config: {
		// 		name: 'id_cotizacion',
		// 		fieldLabel: 'id_cotizacion',
		// 		allowBlank: true,
		// 		emptyText: 'Elija una opción...',
		// 		store: new Ext.data.JsonStore({
		// 			url: '../../sis_/control/Clase/Metodo',
		// 			id: 'id_',
		// 			root: 'datos',
		// 			sortInfo: {
		// 				field: 'nombre',
		// 				direction: 'ASC'
		// 			},
		// 			totalProperty: 'total',
		// 			fields: ['id_', 'nombre', 'codigo'],
		// 			remoteSort: true,
		// 			baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
		// 		}),
		// 		valueField: 'id_',
		// 		displayField: 'nombre',
		// 		gdisplayField: 'desc_',
		// 		hiddenName: 'id_cotizacion',
		// 		forceSelection: true,
		// 		typeAhead: false,
		// 		triggerAction: 'all',
		// 		lazyRender: true,
		// 		mode: 'remote',
		// 		pageSize: 15,
		// 		queryDelay: 1000,
		// 		anchor: '100%',
		// 		gwidth: 150,
		// 		minChars: 2,
		// 		renderer : function(value, p, record) {
		// 			return String.format('{0}', record.data['desc_']);
		// 		}
		// 	},
		// 	type: 'ComboBox',
		// 	id_grupo: 0,
		// 	filters: {pfiltro: 'movtip.nombre',type: 'string'},
		// 	grid: true,
		// 	form: true
		// },
		// {
		// 	config: {
		// 		name: 'id_solicitud',
		// 		fieldLabel: 'id_solicitud',
		// 		allowBlank: true,
		// 		emptyText: 'Elija una opción...',
		// 		store: new Ext.data.JsonStore({
		// 			url: '../../sis_/control/Clase/Metodo',
		// 			id: 'id_',
		// 			root: 'datos',
		// 			sortInfo: {
		// 				field: 'nombre',
		// 				direction: 'ASC'
		// 			},
		// 			totalProperty: 'total',
		// 			fields: ['id_', 'nombre', 'codigo'],
		// 			remoteSort: true,
		// 			baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
		// 		}),
		// 		valueField: 'id_',
		// 		displayField: 'nombre',
		// 		gdisplayField: 'desc_',
		// 		hiddenName: 'id_solicitud',
		// 		forceSelection: true,
		// 		typeAhead: false,
		// 		triggerAction: 'all',
		// 		lazyRender: true,
		// 		mode: 'remote',
		// 		pageSize: 15,
		// 		queryDelay: 1000,
		// 		anchor: '100%',
		// 		gwidth: 150,
		// 		minChars: 2,
		// 		renderer : function(value, p, record) {
		// 			return String.format('{0}', record.data['desc_']);
		// 		}
		// 	},
		// 	type: 'ComboBox',
		// 	id_grupo: 0,
		// 	filters: {pfiltro: 'movtip.nombre',type: 'string'},
		// 	grid: true,
		// 	form: true
		// },
		{
			config:{
				name: 'fecha_modificacion',
				fieldLabel: 'Fecha Modificacion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 120,
							format: 'd/m/Y',
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'log_tram.fecha_modificacion',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu1.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		// {
		// 	config:{
		// 		name: 'fecha_reg',
		// 		fieldLabel: 'Fecha creación',
		// 		allowBlank: true,
		// 		anchor: '80%',
		// 		gwidth: 100,
		// 					format: 'd/m/Y',
		// 					renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
		// 	},
		// 		type:'DateField',
		// 		filters:{pfiltro:'log_tram.fecha_reg',type:'date'},
		// 		id_grupo:1,
		// 		grid:true,
		// 		form:false
		// },
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'log_tram.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'log_tram.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
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
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y',
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'log_tram.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,
	title:'Modificaciones',
	ActSave:'../../sis_gestion_materiales/control/LogModificaciones/insertarLogModificaciones',
	ActDel:'../../sis_gestion_materiales/control/LogModificaciones/eliminarLogModificaciones',
	ActList:'../../sis_gestion_materiales/control/LogModificaciones/listarLogModificaciones',
	id_store:'id_log',
	fields: [
		{name:'id_log', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'id_funcionario_solicitante', type: 'numeric'},
		{name:'motivo_modificacion', type: 'string'},
		{name:'nro_po_anterior', type: 'string'},
		{name:'nro_po_nuevo', type: 'string'},
		{name:'fecha_cotizacion_antigua', type: 'date',dateFormat:'Y-m-d'},
		{name:'fecha_cotizacion_nueva', type: 'date',dateFormat:'Y-m-d'},
		{name:'nro_cotizacion_anterior', type: 'string'},
		{name:'nro_cotizacion_nueva', type: 'string'},
		{name:'id_cotizacion', type: 'numeric'},
		{name:'id_solicitud', type: 'numeric'},
		{name:'fecha_modificacion', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'desc_funcionario_solicitante', type: 'string'},
		{name:'nro_tramite', type: 'string'},

	],
	sortInfo:{
		field: 'id_log',
		direction: 'DESC'
	},
	bdel:false,
	bsave:false,
	bnew:false,
	bedit:false,
	btest:false,
	bexcel:false
	}
)
</script>
