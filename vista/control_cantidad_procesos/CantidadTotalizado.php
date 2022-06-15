<?php
/**
*@package pXP
*@file CantidadTotalizado.php
*@author  (admin)
*@date 12-06-2017 21:56:45
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.CantidadTotalizado=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.CantidadTotalizado.superclass.constructor.call(this,config);
		this.init();

		console.log("aqui el id_panel",this);
		// if (Phx.CP.getPagina(this.idContenedorPadre).store.baseParams.fecha_fin.getValue() != '') {
		// 	this.store.baseParams = {
		// 														fecha_inicio:Phx.CP.getPagina(this.idContenedorPadre).store.baseParams.fecha_inicio,
		// 														fecha_fin:Phx.CP.getPagina(this.idContenedorPadre).store.baseParams.fecha_fin,
		// 														id_funcionario:Phx.CP.getPagina(this.idContenedorPadre).store.baseParams.id_funcionario,
		// 														tipo_formulario:Phx.CP.getPagina(this.idContenedorPadre).store.baseParams.tipo_formulario,
		// 													};
		// 	// this.bloquearMenus();
		// 	this.load({params: {start: 0, limit: 50}});
		// }




        //this.load({params:{start:0, limit:this.tam_pag}})
	},


	cargarPagina: function(fecha_inicio,fecha_fin,id_funcionario,tipo_formulario) {

			this.store.baseParams = {
																fecha_inicio:fecha_inicio,
																fecha_fin:fecha_fin,
																id_funcionario:id_funcionario,
																tipo_formulario:tipo_formulario,
															};
			this.load({params: {start: 0, limit: 50}});
		//console.log("aqui para filtarar la carga",datos_llegada);
	},
	loadMask :false,
	Atributos:[

		{
			config:{
				name: 'origen_pedido',
				fieldLabel: 'Origen Pedido',
				allowBlank: true,
				anchor: '200%',
				gwidth: 190,
				maxLength:20,
			},
				type:'TextField',
				filters:{pfiltro:'fichtot.nombre',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'encargado',
				fieldLabel: 'Funcionario',
				allowBlank: true,
				anchor: '200%',
				gwidth: 185,
				maxLength:20,
			},
				type:'TextField',
				filters:{pfiltro:'fichtot.nombre',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'cantidad_total',
				fieldLabel: 'Cantidad',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4,
			},
				type:'NumberField',
				filters:{pfiltro:'fichtot.cantidad',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		}
	],
	tam_pag:50,
	title:'Cantidad Procesos',

	ActList:'../../sis_gestion_materiales/control/Solicitud/listarTotalTramites',
	id_store:'nombre',
	fields: [
		//{name:'id_ficha_total', type: 'numeric'},
		{name:'origen_pedido', type: 'string'},
		{name:'cantidad_total', type: 'numeric'},
		{name:'encargado', type: 'string'}
	],
	sortInfo:{
		field: 'nombre',
		direction: 'ASC'
	},
	bedit:false,
    bnew:false,
    bsave:false,
    bdel:false,
		btest:false,
		bexcel:false,
		onReloadPage: function (m) {
		//	this.reload();
			// this.maestro = m;
			this.store.baseParams = {
																fecha_inicio:Phx.CP.getPagina(this.idContenedorPadre).store.baseParams.fecha_inicio,
																fecha_fin:Phx.CP.getPagina(this.idContenedorPadre).store.baseParams.fecha_fin,
																id_funcionario:Phx.CP.getPagina(this.idContenedorPadre).store.baseParams.id_funcionario,
																tipo_formulario:Phx.CP.getPagina(this.idContenedorPadre).store.baseParams.tipo_formulario,
															};
			// this.bloquearMenus();
		  this.load({params: {start: 0, limit: 50}});
		},


	mostrar:function(param){
		this.store.baseParams = {id_sucursal:param};
		// this.bloquearMenus();
		this.load({params: {start: 0, limit: 50}});
		Phx.vista.CantidadTotalizado.superclass.loadValoresIniciales.call(this);
		console.log("LLEGA",param);

	}

	}
)
</script>
