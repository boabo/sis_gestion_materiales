<?php
/**
*@package pXP
*@file    FiltroRpc.php
*@author  Ismael Valdivia
*@date    01-12-2020
*@description permite filtrar datos para sacar el reporte de Facturas
*/
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
Phx.vista.FiltroRpc=Ext.extend(Phx.frmInterfaz,{
    constructor:function(config)
    {

        Phx.vista.FiltroRpc.superclass.constructor.call(this,config);


        this.init();
        this.iniciarEventos();

        if(config.detalle){

  			//cargar los valores para el filtro
  			this.loadForm({data: config.detalle});
  			var me = this;

        // setTimeout(function(){
  			// 	me.onSubmit()
  			// }, 1500);

  		}
      /*Fondo de los filtros*/
      this.regiones[0].body.dom.style.background='#dfe8f6';


    },


    Grupos: [
  			{
  					layout: 'column',
            xtype: 'fieldset',
            region: 'north',
            collapseFirst : false,
            width: '100%',
            autoScroll:true,
            padding: '0 0 0 0',
  					items: [
              {
               bodyStyle: 'padding-right:0px;',
               autoHeight: true,
               border: false,
               items:[
                  {
                   xtype: 'fieldset',
                   frame: true,
                   border: false,
                   layout: 'form',
                   width: '90%',
                   style: {
                          height:'150px',
                          width:'590px',
                          backgroundColor:'#dfe8f6'
                       },
                   padding: '0 0 0 0',
                   bodyStyle: 'padding-left:0px;',
                   id_grupo: 0,
                   items: [],
                }]
            },
            {
             bodyStyle: 'padding-right:0px;',
             autoHeight: true,
             border: false,
             items:[
                {
                 xtype: 'fieldset',
                 frame: true,
                 border: false,
                 layout: 'form',
                 style: {
                        height:'150px',
                        width:'300px',
                        backgroundColor:'#dfe8f6'
                     },
                 padding: '0 0 0 0',
                 bodyStyle: 'padding-left:0px;',
                 id_grupo: 1,
                 items: [],
              }]
          },
            {
             bodyStyle: 'padding-right:0px;',
             border: false,
             autoHeight: true,
             items: [{
                   xtype: 'fieldset',
                   frame: true,
                   layout: 'form',
                   style: {
                          height:'150px',
                          width:'350px',
                          backgroundColor:'#dfe8f6',
                         },
                   border: false,
                   padding: '0 0 0 0',
                   bodyStyle: 'padding-left:0px;',
                   id_grupo: 2,
                   items: [],
                }]
            },
  					]
  			}
  	],


    Atributos:[
              {
                  config:{
                      name:'origen_pedido',
                      fieldLabel:'Origen Pedido',
                      allowBlank:false,
                      emptyText:'Elija una opción...',
                      typeAhead: true,
                      triggerAction: 'all',
                      lazyRender:true,
                      mode: 'local',
                      width: 300,
                      gwidth: 230,
                      //store:['Todos','Gerencia de Operaciones','Gerencia de Mantenimiento','Almacenes Consumibles o Rotables','Centro de Entrenamiento Aeronautico Civil','Reparación de Repuestos']
                      store: new Ext.data.ArrayStore({
                          fields: ['ID', 'valor'],
                          data: [['Todos', 'Todos'],
                                 ['Gerencia de Operaciones','GM - Gerencia de Operaciones'],
                                 ['Gerencia de Mantenimiento','GM - Gerencia de Mantenimiento'],
                                 ['Almacenes Consumibles o Rotables', 'GA - Almacenes Consumibles o Rotables'],
                                 ['Centro de Entrenamiento Aeronautico Civil','GC - Centro de Entrenamiento Aeronautico Civil'],
                                 ['Reparación de Repuestos','GR - Reparación de Repuestos'],
                                ]
                      }),
                      valueField: 'ID',
                      displayField: 'valor',

                  },
                  type:'ComboBox',
                  id_grupo:0,
                  grid:true,
                  form:true,
                  bottom_filter:true

              },

              {
                  config:{
                      name: 'fecha_ini',
                      fieldLabel: 'Fecha Inicio',
                      allowBlank: false,
                      width: 300,
                      gwidth: 100,
                      format: 'd/m/Y',
                      renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                  },
                  type:'DateField',
                  filters:{pfiltro:'fecha_ini',type:'date'},
                  id_grupo:1,
                  grid:true,
                  form:true
              },
              {
                  config:{
                      name: 'fecha_fin',
                      fieldLabel: 'Fecha Fin',
                      allowBlank: false,
                      width: 300,
                      gwidth: 100,
                      format: 'd/m/Y',
                      renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                  },
                  type:'DateField',
                  filters:{pfiltro:'fecha_fin',type:'date'},
                  id_grupo:1,
                  grid:true,
                  form:true
              }

	],
	labelSubmit: '<i class="fa fa-check"></i> Aplicar Filtro',
	south: {
		url: '../../../sis_gestion_materiales/vista/consulta_rpc/DetalleRpc.php',
		title: 'Procesos Aprobados',
		height: '70%',
		cls: 'DetalleRpc'
	},
	title: 'Filtro de mayores',
	// Funcion guardar del formulario
	onSubmit: function(o) {
		var me = this;


      if (me.form.getForm().isValid()) {
  			var parametros = me.getValForm();

        var fecha_ini=parametros.fecha_ini;
  			var fecha_fin=parametros.fecha_fin;
  			var origen_pedido=parametros.origen_pedido;

  			this.onEnablePanel(this.idContenedor + '-south',
  				Ext.apply(parametros,{
                      'fecha_ini': fecha_ini,
  										'fecha_fin': fecha_fin,
  										'origen_pedido': origen_pedido,
  									 }));
          }
    },

    iniciarEventos: function(){
      this.Cmp.origen_pedido.setValue('Todos');

      const fecha = new Date();
      var diaActual = new Date().getDate();
      var mesActual = new Date().getMonth() + 1;
      var añoActual = new Date().getFullYear();
      if (diaActual < 10) {
        diaActual = "0"+diaActual;
      }

      if (mesActual < 10) {
        mesActual = "0"+mesActual;
      }

      var fechaFormateada = diaActual + "/" + mesActual + "/" + añoActual;
      this.Cmp.fecha_ini.setValue(fechaFormateada);
      this.Cmp.fecha_fin.setValue(fechaFormateada);


    },


    loadValoresIniciales: function(){
    	Phx.vista.FiltroRpc.superclass.loadValoresIniciales.call(this);

    }

})
</script>
