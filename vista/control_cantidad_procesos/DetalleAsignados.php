<?php
/**
*@package pXP
*@file gen-Sucursal.php
*@author  (admin)
*@date 20-04-2015 15:07:50
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.DetalleAsignados=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
    	this.initButtons=[this.cmbEntidad];

  		Phx.vista.DetalleAsignados.superclass.constructor.call(this,config);
  		this.init();
  		this.iniciarEventos();



	},





	cmbEntidad:new Ext.form.ComboBox({
            store: new Ext.data.JsonStore({

                url: '../../sis_gestion_materiales/control/CantidadProcesos/listarFuncionariosAsignados',
                id: 'id_entidad',
                root: 'datos',
                sortInfo:{
                    field: 'id_funcionario',
                    direction: 'ASC'
                },
                totalProperty: 'total',
                fields: [
                    {name:'id_funcionario'},
                    {name:'desc_funcionario1', type: 'string'}
                ],
                remoteSort: true,
                baseParams:{start:0,limit:10}
            }),
            displayField: 'desc_funcionario1',
            valueField: 'id_funcionario',
            typeAhead: false,
            mode: 'remote',
            triggerAction: 'all',
            emptyText:'Seleccione Funcionario...',
            selectOnFocus:true,
            width:135,
            resizable : true
        }),

	Atributos:[
		{
          config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_funcionario'
            },
            type:'Field',
            form:true
        },
	      {
            config:{
                name: 'nro_proceso',
                fieldLabel: 'Nro. Trámite',
                allowBlank: true,
                anchor: '80%',
                gwidth: 150,
                maxLength:20
            },
                type:'TextField',
                filters:{pfiltro:'sol.nro_proceso',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
        },

        {
            config:{
                name: 'origen_pedido',
                fieldLabel: 'Origen Pedido',
                allowBlank: true,
                anchor: '80%',
                gwidth: 150,
                maxLength:20
            },
                type:'TextField',
                filters:{pfiltro:'sol.origen_pedido',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
        },


		{
			config:{
				name: 'fecha_asignado',
				fieldLabel: 'Fecha Asignación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y',
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'sol.fecha_asignado',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}

	],
	tam_pag:50,
	title:'Detalle Procesos',
	ActList:'../../sis_gestion_materiales/control/CantidadProcesos/listarProcesos',
	id_store:'id_funcionario',

    tabeast:[
          {
              url:'../../../sis_gestion_materiales/vista/control_cantidad_procesos/CantidadTotalizado.php',
              title:'Cantidad Procesos',
              width:'25%',
              cls:'CantidadTotalizado'
         }],

	fields: [
		{name:'id_funcionario', type: 'numeric'},
    {name:'nro_proceso', type: 'string'},
		{name:'origen_pedido', type: 'string'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'}
	],
	sortInfo:{
		field: 'nro_proceso',
		direction: 'DESC'
	},
	iniciarEventos : function () {


	},
	loadValoresIniciales:function()
    {
        this.Cmp.id_entidad.setValue(this.cmbEntidad.getValue());
        Phx.vista.DetalleAsignados.superclass.loadValoresIniciales.call(this);
    },
	bdel:false,
  bsave:false,
  bnew:false,
  bedit:false,
	btest:false,

  }
)
</script>
