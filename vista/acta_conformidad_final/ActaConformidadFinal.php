<?php
/**
*@package pXP
*@file gen-ActaConformidadFinal.php
*@author  (ismael.valdivia)
*@date 04-08-2021 13:51:00
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.ActaConformidadFinal=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		this.initButtons=[this.cmbGestion];
		Phx.vista.ActaConformidadFinal.superclass.constructor.call(this,config);
		this.init();
		this.store.baseParams.pes_estado = 'sin_firma';


		var añoActual = new Date().getFullYear();
    this.cmbGestion.store.load({params:{start:0,limit:50},
           callback : function (r) {
             for (var i = 0; i < r.length; i++) {
               if (r[i].data.gestion == añoActual) {
                 this.cmbGestion.setValue(r[i].data.id_gestion);
                 this.cmbGestion.fireEvent('select', this.cmbGestion,this.cmbGestion.store.getById(r[i].data.id_gestion));
               }
             }
            }, scope : this
        });


		this.cmbGestion.on('select', function( combo, record, index){
        this.capturaFiltros();
    },this);



		this.addButton('btnConformidad', {
				text: 'Conformidad',
				grupo: [1, 2],
				iconCls: 'bok',
				disabled: true,
				handler: this.onButtonConformidad,
				tooltip: 'Generar Conformidad Final'
		});

		this.addButton('btnChequeoDocumentosWf',
				{
						text: 'Documentos',
						grupo: [1, 2],
						iconCls: 'bchecklist',
						disabled: true,
						handler: this.loadCheckDocumentosSolWf,
						tooltip: '<b>Documentos de la Solicitud</b><br/>Subir los documetos requeridos en la solicitud seleccionada.'
				}
		);


		//this.load({params:{start:0, limit:this.tam_pag}});

	},

	capturaFiltros:function(combo, record, index){

      //if(this.validarFiltros()){

          this.store.baseParams.id_gestion = this.cmbGestion.getValue();

					this.load();
      //}

  },


	preparaMenu: function (n) {
			var data = this.getSelectedData();
			var tb = this.tbar;
			Phx.vista.ActaConformidadFinal.superclass.preparaMenu.call(this, n);
			//this.getBoton('diagrama_gantt').enable();

			//if (data.tipo == 'devengado' || data.tipo == 'devengado_pagado' || data.tipo == 'devengado_pagado_1c'|| data.tipo == 'devengado_pagado_1c_sp') {
					this.getBoton('btnConformidad').enable();
			//} else {
			//		this.getBoton('btnConformidad').disable();
		//	}
			this.getBoton('btnChequeoDocumentosWf').enable();
			//this.getBoton('btnPagoRel').enable();

	},

	liberaMenu: function () {
			var tb = Phx.vista.ActaConformidadFinal.superclass.liberaMenu.call(this);

			if (tb) {
					//this.getBoton('ant_estado').disable();
				//	this.getBoton('ini_estado').disable();
				//	this.getBoton('sig_estado').disable();
				//	this.getBoton('diagrama_gantt').disable();
					this.getBoton('btnChequeoDocumentosWf').disable();
				//	this.getBoton('btnPagoRel').disable();
					this.getBoton('btnConformidad').disable();
			}
			return tb
	},

  onButtonEdit:function () {
    Phx.vista.ActaConformidadFinal.superclass.onButtonEdit.call(this);

  },

  onButtonNew:function () {
    Phx.vista.ActaConformidadFinal.superclass.onButtonNew.call(this);

  },


	loadCheckDocumentosSolWf: function () {
			var rec = this.sm.getSelected();

			var tmp = {};
			tmp = Ext.apply(tmp, rec.data);


			Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
					'Chequear documento del WF',
					{
							width: '90%',
							height: 500
					},
					Ext.apply(tmp, {
							tipo: 'plan_pago',
							lblDocProcCf: 'Solo doc de Pago',
							lblDocProcSf: 'Todo del Trámite',
							todos_documentos: 'si'

					}),
					this.idContenedor,
					'DocumentoWf'
			);
	},

	onButtonConformidad: function () {
			var data = this.sm.getSelected().data;
			this.creaFormularioConformidad();
			console.log('data', data);
			if (data['fecha_conformidad'] == '' || data['fecha_conformidad'] == undefined || data['fecha_conformidad'] == null) {
					console.log('a');
					this.formConformidad.getForm().findField('fecha_inicio').setValue(data.fecha_inicio);
					this.formConformidad.getForm().findField('fecha_fin').setValue(data.fecha_fin);
					this.formConformidad.getForm().findField('fecha_conformidad_final').setValue(data.fecha_conformidad);
					this.formConformidad.getForm().findField('conformidad_final').setValue(data.conformidad_final);
					this.formConformidad.getForm().findField('observaciones').setValue(data.observaciones);
					this.windowConformidad.show();
			} else {
					console.log('b');
					Ext.Msg.show({
							title: 'Alerta',
							scope: this,
							msg: 'El acta de Conformidad Final ya se encuentra firmada. Esta seguro de volver a firmar?',
							buttons: Ext.Msg.YESNO,
							maxWidth : 400,
							width: 380,
							fn: function (id, value, opt) {
									if (id == 'yes') {
											this.formConformidad.getForm().findField('fecha_inicio').setValue(data.fecha_inicio);
											console.log('conformidad 1', this.formConformidad.getForm().findField('fecha_inicio').getValue());
											this.formConformidad.getForm().findField('fecha_fin').setValue(data.fecha_fin);
											console.log('conformidad 2', this.formConformidad.getForm().findField('fecha_fin').getValue());
											this.formConformidad.getForm().findField('fecha_conformidad_final').setValue(data.fecha_conformidad);
											this.formConformidad.getForm().findField('conformidad_final').setValue(data.conformidad_final);
											this.formConformidad.getForm().findField('observaciones').setValue(data.observaciones);
											this.windowConformidad.show();
									}
							},
							animEl: 'elId',
							icon: Ext.MessageBox.WARNING
					}, this);
			}

	},


	creaFormularioConformidad: function () {

			this.formConformidad = new Ext.form.FormPanel({
					id: this.idContenedor + '_CONFOR',
					items: [
							new Ext.form.DateField({
									fieldLabel: 'Fecha Conformidad Final',
									format: 'd/m/Y',
									name: 'fecha_conformidad_final',
									allowBlank: false,
									width: '95%'
							}),
							new Ext.form.TextArea({
									fieldLabel: 'Conformidad Final',
									name: 'conformidad_final',
									height: 60,
									allowBlank: false,
									width: '95%',
									maxLength: 5000
							}),
							new Ext.form.DateField({
									fieldLabel: 'Fecha Inicio',
									format: 'd/m/Y',
									name: 'fecha_inicio',
									//height: 150,
									allowBlank: true,
									width: '95%'
							}),
							new Ext.form.DateField({
									fieldLabel: 'Fecha Fin',
									format: 'd/m/Y',
									name: 'fecha_fin',
									//height: 150,
									allowBlank: true,
									width: '95%'
							}),
							new Ext.form.TextArea({
									fieldLabel: 'Observaciones',
									name: 'observaciones',
									height: 50,
									allowBlank: true,
									width: '95%',
									maxLength: 5000
							})

					],
					autoScroll: false,
					//height: this.fheight,
					autoDestroy: true,
					autoScroll: true
			});


			// Definicion de la ventana que contiene al formulario
			this.windowConformidad = new Ext.Window({
					// id:this.idContenedor+'_W',
					title: 'Datos Acta Conformidad Final',
					modal: true,
					width: 400,
					height: 400,
					bodyStyle: 'padding:5px;',
					layout: 'fit',
					hidden: true,
					autoScroll: false,
					maximizable: true,
					buttons: [{
							text: 'Guardar',
							arrowAlign: 'bottom',
							handler: this.onSubmitConformidad,
							argument: {
									'news': false
							},
							scope: this

					},
							{
									text: 'Declinar',
									handler: this.onDeclinarConformidad,
									scope: this
							}],
					items: this.formConformidad,
					// autoShow:true,
					autoDestroy: true,
					closeAction: 'hide'
			});
	},

	onSubmitConformidad: function () {

			var d = this.sm.getSelected().data;
			Phx.CP.loadingShow();
			console.log("aqui llega data irva",d);
			Ext.Ajax.request({
					//url:'../../sis_tesoreria/control/PlanPago/generarConformidad',
					url: '../../sis_gestion_materiales/control/Solicitud/actualizarActaConformidad',
					success: this.successConformidad,
					failure: this.failureConformidad,
					params: {
							//'id_conformidad': d.id_conformidad,
							'id_solicitud': d.id_solicitud,
							'fecha_inicio': this.formConformidad.getForm().findField('fecha_inicio').getValue(),
							'fecha_fin': this.formConformidad.getForm().findField('fecha_fin').getValue(),
							'conformidad_final': this.formConformidad.getForm().findField('conformidad_final').getValue(),
							'fecha_conformidad_final': this.formConformidad.getForm().findField('fecha_conformidad_final').getValue().dateFormat('d/m/Y'),
							'observaciones': this.formConformidad.getForm().findField('observaciones').getValue()
					},
					timeout: this.timeout,
					scope: this

			});
			// function acta() {
			//     window.open(../../sis_tesoreria/control/PlanPago/reporteActaConformidadTotal);
			// };

			// function acta() {
			//     Ext.Ajax.request({
			//         url: '../../sis_tesoreria/control/PlanPago/reporteActaConformidadTotal',
			//         params: {'id_obligacion_pago': d},
			//         timeout: this.timeout,
			//         scope: this
			//     });
			// };




	},

	successConformidad: function (resp) {
			this.windowConformidad.hide();
			Phx.vista.ActaConformidadFinal.superclass.successDel.call(this, resp);
			// var d = this.sm.getSelected().data;
			// Phx.CP.loadingShow();
			// Ext.Ajax.request({
			//     url: '../../sis_tesoreria/control/PlanPago/reporteActaConformidadTotal',
			//
			//     timeout: this.timeout,
			//     scope: this
			// });
			//window.open(url, '../../sis_tesoreria/control/PlanPago/reporteActaConformidadTotal', 'scrollbars=NO,statusbar=NO,left=500,top=250');

	},

	failureConformidad: function (resp) {
			Phx.CP.loadingHide();
			Phx.vista.ActaConformidadFinal.superclass.conexionFailure.call(this, resp);
	},

	onDeclinarConformidad: function () {
			this.windowConformidad.hide();
	},

	gruposBarraTareas:[
										  {name:'sin_firma',title:'<H1 style="font-size:12px; color:red;" align="center"><i style="color:red; font-size:15px;" class="fa fa-ban"></i> Pendiente</h1>',grupo:1,height:0},
											{name:'firmados',title:'<H1 style="font-size:12px; color:blue;" align="center"><i style="color:blue; font-size:15px;" class="fa fa-pencil"></i> Revisado</h1>',grupo:2,height:0},
										],

	bactGroups:  [1,2],
	bexcelGroups:  [1,2],

	actualizarSegunTab: function(name, indice){
 					 this.store.baseParams.pes_estado = name;

					 if (this.cmbGestion.getValue()) {
						 this.load({params:{start:0, limit:this.tam_pag}});
					 }
 					 //this.load({params:{start:0, limit:this.tam_pag}});
 	},

	cmbGestion: new Ext.form.ComboBox({
				      fieldLabel: 'Gestion',
				      grupo:[1,2,3],
				      allowBlank: false,
				      blankText:'... ?',
				      emptyText:'Gestion...',
				      name:'id_gestion',
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
				      width:80
				  }),

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
			config:{
				name: 'nro_tramite',
				fieldLabel: 'Nro. Tramite',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
			},
				type:'TextField',
				filters:{pfiltro:'sol.nro_tramite',type:'string'},
				id_grupo:1,
				grid:true,
				form:true,
        bottom_filter: true
		},
		/*Aumentando la columna de NRO PO*/
		//Ismael Valdivia (16/05/2022)
		{
			config:{
				name: 'nro_po',
				fieldLabel: 'Nro. PO',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
			},
				type:'TextField',
				filters:{pfiltro:'sol.nro_po',type:'string'},
				id_grupo:1,
				grid:true,
				form:true,
				bottom_filter: true
		},


		/*********************************/
		{
			config:{
				name: 'estado',
				fieldLabel: 'Estado',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
			},
				type:'TextField',
				filters:{pfiltro:'sol.estado',type:'string'},
				id_grupo:1,
				grid:true,
				form:true,
        bottom_filter: true
		},
		{
			config:{
				name: 'fecha_sol',
				fieldLabel: 'Fecha',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
			},
				type:'TextField',
				filters:{pfiltro:'sol.fecha_solicitud',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
    {
			config:{
				name: 'desc_funcionario1',
				fieldLabel: 'Funcionario',
				allowBlank: true,
				anchor: '80%',
				gwidth: 300,
			},
				type:'TextField',
				filters:{pfiltro:'fun.desc_funcionario1',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
    {
			config:{
				name: 'proveedor',
				fieldLabel: 'Proveedor',
				allowBlank: true,
				anchor: '80%',
				gwidth: 300,
			},
				type:'TextField',
				filters:{pfiltro:'pro.rotulo_comercial',type:'string'},
				id_grupo:1,
				grid:true,
				form:true,
        bottom_filter: true
		},
    {
			config:{
				name: 'total_a_pagar',
				fieldLabel: 'Total a Pagar',
				allowBlank: true,
				anchor: '80%',
				gwidth: 150,
        renderer:function (value,p,record){
            return String.format('<p style="text-align:right;">{0}</p>', Ext.util.Format.number(record.data['total_a_pagar'],'0,000.00'));

        }
			},
				type:'TextField',
				filters:{pfiltro:'pro.rotulo_comercial',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
    {
			config:{
				name: 'moneda',
				fieldLabel: 'Moneda',
				allowBlank: true,
				anchor: '80%',
				gwidth: 200,
			},
				type:'TextField',
				filters:{pfiltro:'mon.moneda',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
    {
			config:{
				name: 'fecha_conformidad',
				fieldLabel: 'Fecha Conformidad Final',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
			},
				type:'TextField',
				filters:{pfiltro:'acta.fecha_conformidad',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},

	],
	tam_pag:50,
	title:'Acta Conformidad Final',
	ActList:'../../sis_gestion_materiales/control/Solicitud/listarActaFinal',
	id_store:'id_solicitud',
	fields: [
		{name:'nro_tramite', type: 'numeric'},
		{name:'estado', type: 'string'},
		{name:'fecha_sol', type: 'string'},
    {name:'proveedor', type: 'string'},
    {name:'total_a_pagar', type: 'numeric'},
    {name:'moneda', type: 'string'},
    {name:'fecha_conformidad', type: 'string'},
    {name:'desc_funcionario1', type: 'string'},
    {name:'id_usuario', type: 'integer'},
		{name:'id_solicitud', type: 'integer'},
		{name:'observaciones', type: 'varchar'},
		{name:'conformidad_final', type: 'varchar'},
		{name:'fecha_inicio', type: 'varchar'},
		{name:'fecha_final', type: 'varchar'},
		{name:'id_proceso_wf', type: 'integer'},
		{name:'nro_po', type: 'varchar'},

	],
	sortInfo:{
		field: 'nro_tramite',
		direction: 'DESC'
	},
	bdel:false,
	bsave:false,
  btest:false,
  bnew:false,
  bedit:false,
  bexcel:true
	}
)
</script>
