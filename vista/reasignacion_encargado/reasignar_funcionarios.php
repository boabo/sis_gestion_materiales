<?php
/**
*@package pXP
*@file gen-ReasignarFuncionarios.php
*@author  (admin)
*@date 14-03-2017 16:18:47
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.ReasignarFuncionarios=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.ReasignarFuncionarios.superclass.constructor.call(this,config);
		this.init();

    Ext.Ajax.request({
        url:'../../sis_gestion_materiales/control/Solicitud/getDatosNecesarios',
        params:{id_usuario:0},
        success:function(resp){
            var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
            this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
            this.cmbGestion.setRawValue(reg.ROOT.datos.gestion);
            this.store.baseParams.id_gestion = reg.ROOT.datos.id_gestion;
            this.load({params:{start:0, limit:this.tam_pag}});
        },
        failure: this.conexionFailure,
        timeout:this.timeout,
        scope:this
    });

    this.addButton('btn_reasignar',{
        grupo: [3,7],
        text: 'Reasignar Proceso',
        iconCls: 'search',
        disabled: false,
        handler: this.FormularioReasignarFuncionario,
        tooltip: '<b>Cotización</b>',
        scope:this
    });

    this.cmbGestion.on('select',this.capturarEventos, this);
    this.tbar.add(this.cmbGestion);

    this.grid.on('cellclick', this.abrirDetalle, this);

    this.bbar.el.dom.style.background='#A3CCD8';
    this.tbar.el.dom.style.background='#A3CCD8';
    this.grid.body.dom.firstChild.firstChild.lastChild.style.background='#FEFFF4';
    this.grid.body.dom.firstChild.firstChild.firstChild.firstChild.style.background='#BBF1E7';

		//this.load({params:{start:0, limit:this.tam_pag}})

    //this.formu_relacion_bear.items.items[0].getValue()

	},

  abrirDetalle: function(cell,rowIndex,columnIndex,e){

    if(columnIndex==1){
      var data = this.sm.getSelected().data;
      Phx.CP.loadWindows('../../../sis_gestion_materiales/vista/reasignacion_encargado/PanelLogReasignacion.php',
      '', {
        width:'100%',
        height:'100%'
        }, {
          id_solicitud: data.id_solicitud,
          link: true
        },
        this.idContenedor,
        'PanelLogReasignacion'
      );
    }
  },

  preparaMenu: function(n)
  {	var rec = this.getSelectedData();
      var tb =this.tbar;
      Phx.vista.ReasignarFuncionarios.superclass.preparaMenu.call(this,n);
      ///this.getBoton('diagrama_gantt').enable();
      this.getBoton('btn_reasignar').enable();
  },

  liberaMenu:function(){
      var tb = Phx.vista.ReasignarFuncionarios.superclass.liberaMenu.call(this);
      if(tb){
          this.getBoton('btn_reasignar').disable();
      }
      return tb
  },

  onButtonAct:function(){
      if(!this.validarFiltros()){
          Ext.Msg.alert('ATENCION!!!','Especifique los filtros antes')
      }
      else{
          this.store.baseParams.id_gestion=this.cmbGestion.getValue();
          Phx.vista.ReasignarFuncionarios.superclass.onButtonAct.call(this);
      }
  },

  FormularioReasignarFuncionario: function (that) {

      var rec = this.getSelectedData();

      var id_proceso_wf = rec.id_proceso_wf;

      var simple = new Ext.FormPanel({
       labelWidth: 75, // label settings here cascade unless overridden
       frame:true,
       bodyStyle:'padding:5px 5px 0; background:#A3CCD8;',
       width: 300,
       height:200,
       defaultType: 'textfield',
       items: [
         new Ext.form.ComboBox({
                                 name: 'id_proceso_macro',
                                 fieldLabel: 'Tipo Proceso',
                                 allowBlank: false,
                                 emptyText: 'Tipo Proceso...',
                                 store: new Ext.data.JsonStore({
                                     url: '../../sis_gestion_materiales/control/ReasignacionEncargados/listarProcesoMacro',
                                     id: 'id_proceso_macro',
                                     root: 'datos',
                                     sortInfo: {
                                         field: 'id_proceso_macro',
                                         direction: 'ASC'
                                     },
                                     totalProperty: 'total',
                                     fields: ['id_proceso_macro', 'nombre'],
                                     remoteSort: true,
                                     baseParams: {par_filtro: 'cdo.nro_parte_cot', id_proceso_wf: id_proceso_wf}
                                 }),
                                 valueField: 'id_proceso_macro',
                                 displayField: 'nombre',
                                 gdisplayField: 'nombre',
                                 hiddenName: 'id_proceso_macro',
                                 forceSelection: true,
                                 typeAhead: false,
                                 triggerAction: 'all',
                                 lazyRender: true,
                                 mode: 'remote',
                                 resizable:true,
                                 pageSize: 15,
                                 queryDelay: 1000,
                                 anchor: '100%',
                                 width : 250,
                                 listWidth:'600',
                                 minChars: 2 ,
                                 disabled:true,

                              }),

                              new Ext.form.ComboBox({
                                                      name: 'id_funcionario',
                                                      fieldLabel: 'Encargado a asignar',
                                                      allowBlank: false,
                                                      emptyText: 'Encargado...',
                                                      store: new Ext.data.JsonStore({
                                                          url: '../../sis_gestion_materiales/control/ReasignacionEncargados/listarEncargados',
                                                          id: 'id_funcionario',
                                                          root: 'datos',
                                                          sortInfo: {
                                                              field: 'id_funcionario',
                                                              direction: 'ASC'
                                                          },
                                                          totalProperty: 'total',
                                                          fields: ['id_funcionario', 'desc_funcionario'],
                                                          remoteSort: true,
                                                          baseParams: {par_filtro: 'cdo.nro_parte_cot'}
                                                      }),
                                                      valueField: 'id_funcionario',
                                                      displayField: 'desc_funcionario',
                                                      gdisplayField: 'desc_funcionario',
                                                      hiddenName: 'id_funcionario',
                                                      forceSelection: true,
                                                      typeAhead: false,
                                                      triggerAction: 'all',
                                                      lazyRender: true,
                                                      mode: 'remote',
                                                      resizable:true,
                                                      pageSize: 15,
                                                      queryDelay: 1000,
                                                      anchor: '100%',
                                                      width : 250,
                                                      listWidth:'600',
                                                      minChars: 2 ,
                                                      disabled:false,

                                                   }),
                             new Ext.form.TextArea({
                                    name: 'descripcion',
                                    fieldLabel: 'Motivo de Reasignacion',
                                    allowBlank:false,
                                    disabled : false,
                                    width : 200,
                                    hidden : false
                            }),

                ]

            });

      this.formu_reasignacion = simple;

      /*AQUI SELECCIONAMOS AUTOMATICAMENTE EL DATO*/

      this.formu_reasignacion.items.items[0].store.load({params:{start:0,limit:50},
         callback : function (r) {
            console.log("aqui llega para seleccion automatica",r);
           if (r.length == 1) {
             this.formu_reasignacion.items.items[0].setValue(r[0].data.id_proceso_macro);
             this.formu_reasignacion.items.items[0].fireEvent('select', this.formu_reasignacion.items.items[0],this.formu_reasignacion.items.items[0].store.getById(r[0].data.id_proceso_macro));
           }
        }, scope : this
      });

      /*aQUI PARA HACER EL FILTRO DE DATOS*/
      this.formu_reasignacion.items.items[0].on('select',function(c,r,i) {
        this.formu_reasignacion.items.items[1].store.baseParams.id_proceso_macro = this.formu_reasignacion.items.items[0].getValue();
      },this);



      /***********************************************************/

      var formu_reasignacion = new Ext.Window({
        title: '<h1 style="height:20px; font-size:15px;">Reasignar Encargado<p></h1>', //the title of the window
        width:320,
        height:280,
        //closeAction:'hide',
        modal:true,
        plain: true,
        items:simple,
        buttons: [{
                    text:'Guardar',
                    scope:this,
                    handler: function(){
                        this.reasignarEncargado(formu_reasignacion);
                    }
                },{
                    text: 'Cancelar',
                    handler: function(){
                        formu_reasignacion.hide();
                    }
                }]

      });
      formu_reasignacion.show();
  },


  reasignarEncargado : function(formu_reasignacion){
    //Phx.CP.loadingShow();
    var rec=this.sm.getSelected();
    /*Recuperamos de la venta detalle si existe algun concepto con excento*/
    Ext.Ajax.request({
        url : '../../sis_gestion_materiales/control/ReasignacionEncargados/actualizarEncargado',
        params : {
          'id_estado_wf' : rec.data.id_estado_wf,
          'id_funcionario': this.formu_reasignacion.items.items[1].getValue(),
          'observacion': this.formu_reasignacion.items.items[2].getValue()
        },
        success : this.successSave,
        failure : this.conexionFailure,
        timeout : this.timeout,
        scope : this
      });

      this.reload();

      formu_reasignacion.hide();
      //Phx.CP.loadingHide();
    /**********************************************************************/
  },

  cmbGestion: new Ext.form.ComboBox({
      name: 'gestion',
      fieldLabel: 'Gestion',
      allowBlank: true,
      emptyText:'Gestion...',
      id: 'gestion_reasig',
      blankText: 'Año',
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
      hidden:false,
      width:80
  }),

  capturarEventos: function () {
      if(this.validarFiltros()){
          this.capturaFiltros();
      }
  },

  capturaFiltros:function(combo, record, index){
      this.desbloquearOrdenamientoGrid();
      this.store.baseParams.id_gestion=this.cmbGestion.getValue();
      this.load({params:{start:0, limit:this.tam_pag}});
      //this.load();
  },

  validarFiltros:function(){
      if(this.cmbGestion.isValid()){
          return true;
      }
      else{
          return false;
      }

  },

	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_proceso_wf'
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
			config:{
				name: 'nro_tramite',
				fieldLabel: 'Nro de Trámite',
				allowBlank: true,
				anchor: '80%',
				gwidth: 180,
				maxLength:100,
				renderer:function (value,p,record){
						return '<a><i class="fa fa-share" aria-hidden="true"></i> '+value+'</a>';					
				}
			},
				type:'TextField',
				filters:{pfiltro:'sol.nro_tramite',type:'string'},
        bottom_filter:true,
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'estado',
				fieldLabel: 'Estado Actual',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'sol.estado_actual',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'fecha_solicitud',
				fieldLabel: 'Fecha Solicitud',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:100
			},
				type:'TextField',
				//filters:{pfiltro:'u/m.tipo_unidad_medida',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'funcionario_asignado',
				fieldLabel: 'Funcionario Asignado',
				allowBlank: true,
				anchor: '80%',
				gwidth: 200,
				maxLength:10
			},
				type:'TextField',
				//filters:{pfiltro:'u/m.funcionario_asignado',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,
	title:'Unidad Medida',
	//ActSave:'../../sis_gestion_materiales/control/UnidadMedida/insertarUnidadMedida',
	//ActDel:'../../sis_gestion_materiales/control/UnidadMedida/eliminarUnidadMedida',
	ActList:'../../sis_gestion_materiales/control/ReasignacionEncargados/listarDatos',
	id_store:'id_unidad_medida',
	fields: [
		{name:'nro_tramite', type: 'string'},
		{name:'estado', type: 'string'},
		{name:'id_proceso_wf', type: 'numeric'},
		{name:'fecha_solicitud', type: 'string'},
    {name:'id_gestion', type: 'numeric'},
		{name:'id_estado_wf', type: 'numeric'},
		{name:'funcionario_asignado', type: 'string'},
		{name:'id_solicitud', type: 'numeric'}

	],
	sortInfo:{
		field: 'id_proceso_wf',
		direction: 'ASC'
	},
	bdel:false,
	bsave:true,
  bexcel:false,
  bnew:false,
  bedit:false,
  btest:false,
  bsave:false,
  bgantt:true,
	}
)
</script>
