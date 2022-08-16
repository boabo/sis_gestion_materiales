<?php
/**
*@package pXP
*@file gen-FirmasDocumentos.php
*@author  (ismael.valdivia)
*@date 16-08-2022 13:50:13
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FirmasDocumentos=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.FirmasDocumentos.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}});
		this.iniciarEventos();
	},

	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_firma_documento'
			},
			type:'Field',
			form:true
		},
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'firm_doc.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_inicio',
				fieldLabel: 'Fecha Inicio',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
							format: 'd/m/Y',
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'firm_doc.fecha_inicio',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'fecha_fin',
				fieldLabel: 'Fecha Fin',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
							format: 'd/m/Y',
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'firm_doc.fecha_fin',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config: {
				name: 'id_funcionario',
				fieldLabel: 'Funcionario',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
										url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
										id: 'id_cargo',
										root: 'datos',
										sortInfo: {
												field: 'descripcion_cargo',
												direction: 'ASC'
										},
										totalProperty: 'total',
										fields: ['id_cargo', 'nombre','desc_funcionario1','id_funcionario','descripcion_cargo','id_cargo'],
										remoteSort: true,
										baseParams: {par_filtro: 'descripcion_cargo#desc_funcionario1#desc_funcionario2'}
								}),
				tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>{descripcion_cargo}</b></p><p>{desc_funcionario1}</p> </div></tpl>',
				valueField: 'id_funcionario',
				displayField: 'desc_funcionario1',
				gdisplayField: 'desc_funcionario1',
				hiddenName: 'id_funcionario',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 250,
				minChars: 2,
				renderer:function(value, p, record){return String.format('{0}', record.data['desc_funcionario1']);}

			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'fun.desc_funcionario1',type: 'string'},
			grid: true,
			bottom_filter:true,
			form: true
		},
		{
		 config : {
			 name : 'tipo_firma',
			 fieldLabel : 'Tipo Firma',
			 //hidden:true,
			 anchor: '100%',
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
					catalogo_tipo:'tfirmas_documentos'
				},
			 }),
			 valueField : 'codigo',
			 displayField : 'codigo',
			 gdisplayField : 'tipo_firma',
			 hiddenName : 'codigo',
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
		 id_grupo : 1,
		 grid: true,
		 form: true
	 },
	 {
		 config:{
			 name: 'tipo_documento',
			 fieldLabel: 'Tipo Documento',
			 allowBlank: true,
			 anchor: '100%',
			 gwidth: 200,
			 disabled:true,
			 //maxLength:4
		 },
			 type:'Field',
			 filters:{pfiltro:'usu1.cuenta',type:'string'},
			 id_grupo:1,
			 grid:true,
			 form:true
	 },
	 {
		 config:{
			 name: 'motivo_asignacion',
			 fieldLabel: 'Motivo Asignacion',
			 allowBlank: true,
			 anchor: '100%',
			 gwidth: 250,
			 //maxLength:4
		 },
			 type:'TextArea',
			 filters:{pfiltro:'firm_doc.motivo_asignacion',type:'string'},
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
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y',
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'firm_doc.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
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
				filters:{pfiltro:'firm_doc.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'firm_doc.usuario_ai',type:'string'},
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
				filters:{pfiltro:'firm_doc.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,
	title:'Firmas Documentos',
	ActSave:'../../sis_gestion_materiales/control/FirmasDocumentos/insertarFirmasDocumentos',
	ActDel:'../../sis_gestion_materiales/control/FirmasDocumentos/eliminarFirmasDocumentos',
	ActList:'../../sis_gestion_materiales/control/FirmasDocumentos/listarFirmasDocumentos',
	id_store:'id_firma_documento',
	fields: [
		{name:'id_firma_documento', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'fecha_inicio', type: 'date',dateFormat:'Y-m-d'},
		{name:'fecha_fin', type: 'date',dateFormat:'Y-m-d'},
		{name:'id_funcionario', type: 'numeric'},
		{name:'tipo_firma', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'motivo_asignacion', type: 'string'},
		{name:'tipo_documento', type: 'string'},
		{name:'desc_funcionario1', type: 'string'},



	],
	sortInfo:{
		field: 'id_firma_documento',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,

			iniciarEventos:function(){
					this.Cmp.tipo_firma.on('select', function (c,r,i) {
						this.Cmp.tipo_documento.setValue(r.data.descripcion);
					},this);

			}


	}
)
</script>
