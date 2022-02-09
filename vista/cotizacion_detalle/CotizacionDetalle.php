<?php
/**
*@package pXP
*@file gen-CotizacionDetalle.php
*@author  (miguel.mamani)
*@date 04-07-2017 14:51:54
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>

<style>

.HazmatAsociado {
    background-color: #5FB1F5;
}

</style>


<script>
Phx.vista.CotizacionDetalle=Ext.extend(Phx.gridInterfaz, {
        bnew: true,
        fheight: 450,
        fwidth: 900,
        // viewConfig: {
        //
        //     getRowClass: function (record) {
        //
        //         var id_hazmat = '';
        //
        //         if (record.data.id_hazmat_asociado != null && record.data.id_hazmat_asociado != '') {
        //           this.id_hazmat_asociado = record.data.id_hazmat_asociado;
        //         }
        //
        //         if (this.id_hazmat_asociado == record.data.id_cotizacion_det) {
        //           return 'HazmatAsociado';
        //         }
        //
        //     },
        //     listener: {
        //         render: this.createTooltip
        //     },
        //
        // },




        constructor: function (config) {
            this.Grupos = [
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
                                   background:'#FFD97A',
                                   width:'400px',
                                   height:'330px',
                                   border:'1px solid black',
                                   borderRadius:'2px'
                                  },
                               items: [

                                   {
                                       xtype: 'fieldset',
                                       title: '  Datos Part Number ',
                                       border: false,
                                       //autoHeight: true,
                                       style:{
                                             background:'#FFD97A',
                                            },

                                       items: [/*this.compositeFields()*/],
                                       id_grupo: 0
                                   }

                               ]
                           },
                           {
                             xtype: 'fieldset',
                             style:{
                                   background:'#77E9CA',
                                   width:'400px',
                                   height:'330px',
                                   marginLeft:'2px',
                                   border:'1px solid black',
                                   borderRadius:'2px'
                                  },
                               items: [
                                   {
                                       xtype: 'fieldset',
                                       title: ' Datos Cotizacion ',
                                       //autoHeight: true,
                                       border: false,
                                       style:{
                                             background:'#77E9CA',
                                             //border:'2px solid green',
                                             width : '100%',
                                            },
                                       items: [],
                                       id_grupo: 1
                                   }


                               ]
                           }
                       ]
                }];


            this.maestro = config.maestro;

            //llama al constructor de la clase padre
            Phx.vista.CotizacionDetalle.superclass.constructor.call(this, config);
            this.tipoTramite = Phx.CP.getPagina(this.idContenedorPadre).maestro.tipoTramite;
            this.init();
            this.grid.addListener('cellclick', this.oncellclick,this);
            this.grid.on('rowcontextmenu', function(grid, rowIndex, e) {
                e.stopEvent();
                var selModel = this.grid.getSelectionModel();
                if (!selModel.isSelected(rowIndex)) {
                    selModel.selectRow(rowIndex);
                    this.fireEvent('rowclick', this, rowIndex, e);
                }

                //Descomentar para no agregar detalles en la cotizacion
                //if (this.tipoTramite != 'GM') {
                this.ctxMenu.showAt(e.getXY());
                  //}
            }, this);




              this.ctxMenu = new Ext.menu.Menu({
                  items: [{
                      handler: this.clonarDetalle,
                      icon: '../../../lib/imagenes/arrow-down.gif',
                      text: 'Clonar Nro. de Parte',
                      scope: this
                  }],
                  scope: this
              });


              this.addButton('relacionarHazmat',{
      						//grupo:[8,3],
      						// argument: {estado: 'inicio'},
      						text:'Relacionar Hazmat',
      						iconCls: 'bupload',
      						disabled:true,
      						hidden:true,
      						handler:this.FormularioRelacionHazmat,
      						tooltip: '<b>Relaciona el Hazmat con el detalle</b>'
      				});

              this.addButton('relacionarBEAR',{
      						//grupo:[8,3],
      						// argument: {estado: 'inicio'},
      						text:'Relacionar B.E.R.',
      						iconCls: 'bupload',
      						disabled:true,
      						hidden:true,
      						handler:this.FormularioRelacionBEAR,
      						tooltip: '<b>Relaciona el Hazmat con el detalle</b>'
      				});





            this.bbar.el.dom.style.background='#80D7FF';
            this.tbar.el.dom.style.background='#80D7FF';
            this.grid.body.dom.firstChild.firstChild.firstChild.firstChild.style.background='#B7E8FF';
            this.grid.body.dom.firstChild.firstChild.lastChild.style.background='#D8F9FF';
        },

        Atributos: [
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_cotizacion_det'
                },
                type: 'Field',
                form: true
            },
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_cotizacion'
                },
                type: 'Field',
                form: true
            },
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_detalle'
                },
                type: 'Field',
                form: true
            },
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_solicitud'
                },
                type: 'Field',
                form: true
            },
            {
                config:{
                    name: 'revisado',
                    fieldLabel: 'Cotizado',
                    allowBlank: true,
                    width: 200,
                    gwidth: 80,
                    maxLength:3,
                    renderer: function (value){
                        //check or un check row
                        var checked = '',
                            momento = 'no';
                        if(value == 'si'){
                            checked = 'checked';;
                        }else if(value==''){
                            return value;
                        }
                        return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:37px;width:37px;" type="checkbox"  {0}></div>',checked);

                    }
                },
                type: 'TextField',
                grid: true,
                form: false
            },
            {
                config:{
                    name: 'referencial',
                    fieldLabel: 'Referencial',
                    allowBlank: true,
                    width: 200,
                    gwidth: 80,
                    maxLength:3,
                    renderer: function (value){
                        //check or un check row
                        var checked = '',
                            momento = 'No';
                        if(value == 'Si'){
                            checked = 'checked';;
                        }else if(value==''){
                            return value;
                        }
                        return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:37px;width:37px;" type="checkbox"  {0}></div>',checked);

                    }
                },
                type: 'TextField',
                grid: true,
                form: false
            },

            {
                config: {
                    name: 'hazmat_relacionado',
                    fieldLabel: 'Nro Parte Relacionado',
                    allowBlank: true,
                    //hidden:true,
                    width: 200,
                    gwidth: 150,
                    maxLength: 50

                },
                type: 'TextField',
                filters: {pfiltro: 'cde.hazmat_relacionado', type: 'string'},
                id_grupo: 0,
                grid: true,
                //egrid: true,
                form: false
            },


            {
                config: {
                    name: 'nro_parte_cot',
                    fieldLabel: 'Nro. Parte',
                    allowBlank: true,
                    width: 200,
                    gwidth: 150,
                    maxLength: 50

                },
                type: 'TextField',
                filters: {pfiltro: 'cde.nro_parte_cot', type: 'string'},
                id_grupo: 0,
                grid: true,
                //egrid: true,
                form: true
            },
            {
                config: {
                    name: 'nro_parte_alterno_cot',
                    fieldLabel: 'Nro. Parte Alterno',
                    allowBlank: true,
                    width: 200,
                    gwidth: 150,
                    maxLength: 50
                },
                type: 'TextField',
                filters: {pfiltro: 'cde.nro_parte_alterno_cot', type: 'string'},
                id_grupo: 0,
                grid: true,
                //egrid: true,
                form: true
            },
            {
                config: {
                    name: 'referencia_cot',
                    fieldLabel: 'Referencia',
                    allowBlank: true,
                    width: 200,
                    gwidth: 200,
                    maxLength: 100
                  },
                type: 'TextField',
                filters: {pfiltro: 'cde.referencia_cot', type: 'string'},
                id_grupo: 0,
                grid: true,
                egrid: true,
                form: true
            },
            {
                config: {
                    name: 'serial',
                    fieldLabel: 'Serial',
                    allowBlank: true,
                    width: 200,
                    gwidth: 200,
                    maxLength: 100,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['referencia_cot']);
                    }
                },
                type: 'TextField',
                filters: {pfiltro: 'cde.referencia_cot', type: 'string'},
                id_grupo: 0,
                grid: true,
                egrid: true,
                form: true
            },
            {
                config: {
                    name: 'descripcion_cot',
                    fieldLabel: 'Descripcion',
                    allowBlank: true,
                    width: 200,
                    gwidth: 200,
                    maxLength: 100
                },
                type: 'TextArea',
                filters: {pfiltro: 'cde.descripcion_cot', type: 'string'},
                id_grupo: 0,
                grid: true,
                egrid: true,
                form: true
            },
            {
                config: {
                    name: 'explicacion_detallada_part_cot',
                    fieldLabel: 'P/N Cotización',
                    allowBlank: true,
                    width: 200,
                    gwidth: 200,
                    maxLength: 100
                },
                type: 'TextField',
                filters: {pfiltro: 'cde.explicacion_detallada_part_cot', type: 'string'},
                id_grupo: 0,
                grid: true,
                egrid: true,
                form: true
            },
            {
  					 config : {
  						 name : 'tipo_cot',
  						 fieldLabel : 'Tipo',
  						 width: 200,
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
  						 gdisplayField : 'tipo_cot',
  						 hiddenName : 'tipo_cot',
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
  						 // renderer: function(value, p, record) {
               //     console.log("aqui llega el dato",record.data);
  							// 	 if(record.data.revisado == 'no'&& record.data.estado == 'almacen'){
  							// 			 return String.format('<div ext:qtip="Optimo"><b><font color="red">{0}</font></b><br></div>', record.data.tipo);
  							// 	 }else{
  							// 			 return String.format('<div ext:qtip="Optimo"><b><font color="black">{0}</font></b><br></div>', record.data.tipo);
  							// 	 }
               //
  						 // }
  					 },
  					 type : 'AwesomeCombo',
  					 id_grupo : 0,
  					 grid: true,
  					 form: true
  				 },
            // {
            //     config: {
            //         name: 'tipo_cot',
            //         fieldLabel: 'Tipo',
            //         allowBlank: false,
            //         emptyText: 'Elija una opción...',
            //         typeAhead: true,
            //         triggerAction: 'all',
            //         lazyRender: true,
            //         mode: 'local',
            //         width: 200,
            //         gwidth: 80,
            //         store: ['Consumible', 'Rotable','Herramienta','Otros Cargos','NA']
            //
            //     },
            //     type: 'ComboBox',
            //     id_grupo: 0,
            //     grid: true,
            //     egrid: true,
            //     form: true
            //
            // },
            {
                config: {
                    name: 'codigo',
                    fieldLabel: 'Unidad Medida',
                    allowBlank: true,
                    width: 200,
                    gwidth: 150,
                    maxLength: 50

                },
                type: 'TextField',
                filters: {pfiltro: 'd.codigo', type: 'string'},
                id_grupo: 0,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'cantidad_det',
                    fieldLabel: 'Cantidad Total',
                    allowBlank: true,
                    width: 200,
                    gwidth: 120,
                    maxLength: 1000,
                    style: 'background-color: #9BF592; background-image: none;'
                },
                type: 'NumberField',
                filters: {pfiltro: 'cde.cantidad_det', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                egrid: true,
                form: true
            },
            {
                config: {
                    name: 'precio_unitario',
                    fieldLabel: 'Precio Unitario',
                    currencyChar: ' ',
                    allowBlank: true,
                    width: 200,
                    gwidth: 120,
                    disabled: false,
                    maxLength: 1245186,
                    style: 'background-color: #9BF592; background-image: none;'
                },
                type: 'MoneyField',
                filters: {pfiltro: 'cde.precio_unitario', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                egrid: true,
                form: true
            },
            {
                config: {
                    name: 'precio_unitario_mb',
                    fieldLabel: 'Precio Total',
                    currencyChar: ' ',
                    allowBlank: true,
                    width: 200,
                    gwidth: 120,
                    disabled: true,
                    maxLength: 1245186
                },
                type: 'MoneyField',
                filters: {pfiltro: 'cde.precio_unitario_mb', type: 'numeric'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
             config : {
               name : 'cd',
               fieldLabel : 'CD',
               width:200,
               allowBlank : false,
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
                  cod_subsistema:'MAT',
                  catalogo_tipo:'tsolicitud'
                },
               }),
               valueField : 'codigo',
               displayField : 'codigo',
               gdisplayField : 'cd',
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
               resizable:true
             },
             type : 'ComboBox',
             id_grupo : 1,
             grid: true,
             form: true
           },
           {
               config: {
                   name: 'id_unidad_medida_cot',
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
               id_grupo: 1,
               filters: {pfiltro:' u.codigo', type:'string'},
               grid: false,
               form: true

           },
            {
                config: {
                    name: 'id_day_week',
                    fieldLabel: 'Tiempo Entrega',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_gestion_materiales/control/CotizacionDetalle/listarDay_week',
                        id: 'id_day_week',
                        root: 'datos',
                        sortInfo: {
                            field: 'id_day',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_day','codigo_tipo'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'da.codigo_tipo'}
                    }),
                    valueField: 'id_day',
                    displayField: 'codigo_tipo',
                    gdisplayField: 'desc_codigo_tipo',
                    hiddenName: 'id_day_week',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 500,
                    queryDelay: 1000,
                    width: 200,
                    gwidth: 100,
                    minChars: 2,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_codigo_tipo']);
                    }
                },
                type: 'ComboBox',
                id_grupo: 1,
                filters: {pfiltro: 'da.codigo_tipo',type: 'string'},
                grid: true,
                egrid: true,
                form: true
            },

            {
                config: {
                    name: 'estado_reg',
                    fieldLabel: 'Estado Reg.',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    maxLength: 10
                },
                type: 'TextField',
                filters: {pfiltro: 'cde.estado_reg', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'id_usuario_ai',
                    fieldLabel: '',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'Field',
                filters: {pfiltro: 'cde.id_usuario_ai', type: 'numeric'},
                id_grupo: 1,
                grid: false,
                form: false
            },
            {
                config: {
                    name: 'usr_reg',
                    fieldLabel: 'Creado por',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'Field',
                filters: {pfiltro: 'usu1.cuenta', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'usuario_ai',
                    fieldLabel: 'Funcionaro AI',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    maxLength: 300
                },
                type: 'TextField',
                filters: {pfiltro: 'cde.usuario_ai', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_reg',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y H:i:s') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'cde.fecha_reg', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_mod',
                    fieldLabel: 'Fecha Modif.',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y H:i:s') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'cde.fecha_mod', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'usr_mod',
                    fieldLabel: 'Modificado por',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'Field',
                filters: {pfiltro: 'usu2.cuenta', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            }
        ],
        tam_pag: 50,
        title: 'Cotización Detalle',
        ActSave: '../../sis_gestion_materiales/control/CotizacionDetalle/insertarCotizacionDetalle',
        ActDel: '../../sis_gestion_materiales/control/CotizacionDetalle/eliminarCotizacionDetalle',
        ActList: '../../sis_gestion_materiales/control/CotizacionDetalle/listarCotizacionDetalle',
        id_store: 'id_cotizacion_det',
        fields: [
            {name: 'id_day_week', type: 'numeric'},
            {name: 'id_cotizacion_det', type: 'numeric'},
            {name: 'id_cotizacion', type: 'numeric'},
            {name: 'id_detalle', type: 'numeric'},
            {name: 'id_solicitud', type: 'numeric'},
            {name: 'nro_parte_cot', type: 'string'},
            {name: 'nro_parte_alterno_cot', type: 'string'},
            {name: 'referencia_cot', type: 'string'},
            {name: 'descripcion_cot', type: 'string'},
            {name: 'explicacion_detallada_part_cot', type: 'string'},
            {name: 'tipo_cot', type: 'string'},
            {name: 'cantidad_det', type: 'numeric'},
            {name: 'precio_unitario', type: 'numeric'},
            {name: 'precio_unitario_mb', type: 'numeric'},
            {name: 'estado_reg', type: 'string'},
            {name: 'id_usuario_ai', type: 'numeric'},
            {name: 'id_usuario_reg', type: 'numeric'},
            {name: 'usuario_ai', type: 'string'},
            {name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'id_usuario_mod', type: 'numeric'},
            {name: 'usr_reg', type: 'string'},
            {name: 'usr_mod', type: 'string'},
            {name: 'cd', type: 'string'},
            {name: 'codigo', type: 'string'},
            {name: 'revisado', type: 'string'},
            {name: 'desc_codigo_tipo', type: 'string'},
            {name: 'referencial', type: 'string'},
            {name: 'id_unidad_medida_cot', type: 'numeric'},
            {name: 'id_hazmat_asociado', type: 'numeric'},
            {name: 'hazmat_relacionado', type: 'numeric'}


        ],
        sortInfo: {
            field: 'id_cotizacion_det',
            direction: 'ASC'
        },
        bdel: true,
        bsave: true,

        onReloadPage: function (m) {
            this.maestro = m;
            this.store.baseParams = {id_cotizacion: this.maestro.id_cotizacion};

            var tipoTramite = Phx.CP.getPagina(this.idContenedorPadre).maestro.tipoTramite;

            if (tipoTramite == 'GR') {
              this.cm.setHidden(6, false);
  						this.cm.setHidden(5, true);
            } else {
              this.cm.setHidden(5, false);
  						this.cm.setHidden(6, true);
            }

            this.cm.setHidden(3, true);
          //  Phx.CP.getPagina(this.idContenedorPadre).maestro
              //console.log("llega carga pagina deta",Phx.CP.getPagina(this.idContenedorPadre).maestro.tipoTramite);
              //this.changeFieldLabel('referencia_cot', '<b style="font-size:12px;"><i class="fa fa-pencil" aria-hidden="true"></i></b> Serial');
            this.load({params: {start: 0, limit: 50}});

        },

        loadValoresIniciales: function () {
            Phx.vista.CotizacionDetalle.superclass.loadValoresIniciales.call(this);
            this.Cmp.id_cotizacion.setValue(this.maestro.id_cotizacion);
        },
        successSave:function(resp){
            Phx.vista.CotizacionDetalle.superclass.successSave.call(this,resp);
            Phx.CP.getPagina(this.idContenedorPadre).reload();
        },
        successEdit:function(resp){
            Phx.vista.CotizacionDetalle.superclass.successEdit.call(this,resp);
            Phx.CP.getPagina(this.idContenedorPadre).reload();
        },
        successDel:function(resp){
            Phx.vista.CotizacionDetalle.superclass.successDel.call(this,resp);
            Phx.CP.getPagina(this.idContenedorPadre).reload();
        },
        clonarDetalle: function () {
            var rec=this.sm.getSelected();
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/CotizacionDetalle/clonarDetalle',
                params:{'id_detalle':rec.data.id_cotizacion_det,
                        'id_solicitud':this.maestro.id_solicitud},
                success:this.succeClonSinc,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
            this.ctxMenu.hide();
        },
        succeClonSinc:function(resp){
            Phx.CP.loadingHide();
            this.reload();
        },
    oncellclick : function(grid, rowIndex, columnIndex, e) {
        var record = this.store.getAt(rowIndex),
            fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name

        if(fieldName == 'revisado') {
            this.cambiarRevision(record);
        }

        if(fieldName == 'referencial') {
            this.cambiarReferencia(record);
            this.evento();
        }
    },
    cambiarRevision: function(record){
        Phx.CP.loadingShow();
        var d = record.data;
        Ext.Ajax.request({
            url:'../../sis_gestion_materiales/control/CotizacionDetalle/cambiarRevision',
            params:{ id_cotizacion_det: d.id_cotizacion_det,
                revisado: d.revisado
            },
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

    cambiarReferencia: function(record){
        Phx.CP.loadingShow();
        var d = record.data;
        console.log("aqui para cambiar el estado",d);
        Ext.Ajax.request({
            url:'../../sis_gestion_materiales/control/CotizacionDetalle/cambiarReferencia',
            params:{ id_cotizacion_det: d.id_cotizacion_det,
                referencial: d.referencial
            },
            success: this.successReferencia,
            failure: this.conexionFailure,
            timeout: this.timeout,
            scope: this
        });
        this.reload();
    },
    successReferencia: function(resp){
        Phx.CP.loadingHide();
        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
    },
    evento:function () {
        Phx.CP.getPagina(this.idContenedorPadre).reload();
    },
    onButtonEdit:function () {
        Phx.vista.CotizacionDetalle.superclass.onButtonEdit.call(this);
        var tipo_tramite = Phx.CP.getPagina(this.idContenedorPadre).maestro.tipoTramite;
        this.window.items.items[0].body.dom.style.background = '#548DCA';
        this.window.mask.dom.style.background = '#000000';
        this.window.mask.dom.style.opacity = '0.9';
        if (tipo_tramite == 'GR') {
          this.Cmp.serial.setValue(this.Cmp.referencia_cot.getValue());
          this.ocultarComponente(this.Cmp.referencia_cot);
          this.ocultarComponente(this.Cmp.id_day_week);
          this.mostrarComponente(this.Cmp.serial);
          this.Cmp.id_day_week.allowBlank = true;
          this.Cmp.serial.on('change',function(field,newValue,oldValue){
    				this.Cmp.referencia_cot.setValue(this.Cmp.serial.getValue());
    			},this);
        } else {
          this.mostrarComponente(this.Cmp.referencia_cot);
          this.ocultarComponente(this.Cmp.serial);
          this.mostrarComponente(this.Cmp.id_day_week);
          this.Cmp.id_day_week.allowBlank = false;
        }

    },

    preparaMenu:function(n){
        var tb =this.tbar;
				var rec = this.getSelectedData();
        Phx.vista.CotizacionDetalle.superclass.preparaMenu.call(this,n);
        console.log("aqui llega datos",rec);
        //relacionarBEAR

				if (rec.nro_parte_cot == 'HAZMAT') {
	        this.getBoton('relacionarHazmat').setVisible(true);
          this.cm.setHidden(3, false);

	      } else {
					this.getBoton('relacionarHazmat').setVisible(false);
          this.cm.setHidden(3, true);
				}

        if (rec.cd == 'B.E.R.') {
          this.getBoton('relacionarBEAR').setVisible(true);
          this.cm.setHidden(3, false);
        } else {
          this.getBoton('relacionarBEAR').setVisible(false);
          this.cm.setHidden(3, true);
        }



        return tb;
    },

    FormularioRelacionHazmat: function (that) {

				var rec = this.getSelectedData();

				var id_solicitud_envio = rec.id_cotizacion;

				var simple = new Ext.FormPanel({
				 labelWidth: 75, // label settings here cascade unless overridden
				 frame:true,
				 bodyStyle:'padding:5px 5px 0; background:linear-gradient(45deg, #a7cfdf 0%,#a7cfdf 100%,#23538a 100%);',
				 width: 300,
				 height:70,
				 defaultType: 'textfield',
				 items: [
					 new Ext.form.ComboBox({
																	 name: 'id_cotizacion_det',
																	 fieldLabel: 'Items Detalle',
																	 allowBlank: false,
																	 emptyText: 'Detalle Items...',
																	 store: new Ext.data.JsonStore({
																			 url: '../../sis_gestion_materiales/control/CotizacionDetalle/listarCotizacionDetalle',
																			 id: 'id_cotizacion_det',
																			 root: 'datos',
																			 sortInfo: {
																					 field: 'id_cotizacion_det',
																					 direction: 'ASC'
																			 },
																			 totalProperty: 'total',
																			 fields: ['id_cotizacion_det', 'nro_parte_cot'],
																			 remoteSort: true,
																			 baseParams: {par_filtro: 'cdo.nro_parte_cot', id_cotizacion: id_solicitud_envio, FiltroDetalle:'si'}
																	 }),
																	 valueField: 'id_cotizacion_det',
																	 displayField: 'nro_parte_cot',
																	 gdisplayField: 'nro_parte_cot',
																	 hiddenName: 'id_cotizacion_det',
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
									]

		 					});

				this.formu_relacion_hazmat = simple;

				var formu_relacion_hazmat = new Ext.Window({
					title: '<h1 style="height:20px; font-size:15px;">Relacionar Hazmat<p></h1>', //the title of the window
					width:320,
					height:150,
					//closeAction:'hide',
					modal:true,
					plain: true,
					items:simple,
					buttons: [{
											text:'Guardar',
											scope:this,
											handler: function(){
													this.relacionarHazmat(formu_relacion_hazmat);
											}
									},{
											text: 'Cancelar',
											handler: function(){
													formu_relacion_hazmat.hide();
											}
									}]

				});
				formu_relacion_hazmat.show();
		},

    relacionarHazmat : function(formu_relacion_hazmat){
			//Phx.CP.loadingShow();
			var rec=this.sm.getSelected();
			/*Recuperamos de la venta detalle si existe algun concepto con excento*/
			Ext.Ajax.request({
					url : '../../sis_gestion_materiales/control/DetalleSol/RelacionarHazmat',
					params : {
						'id_hazmat' : rec.data.id_cotizacion_det,
						'id_cotizacion_det': this.formu_relacion_hazmat.items.items[0].getValue()
					},
					success : this.successSave,
					failure : this.conexionFailure,
					timeout : this.timeout,
					scope : this
				});

				this.reload();

				formu_relacion_hazmat.hide();
				//Phx.CP.loadingHide();
			/**********************************************************************/
		},


    FormularioRelacionBEAR: function (that) {

				var rec = this.getSelectedData();

				var id_solicitud_envio = rec.id_cotizacion;

				var simple = new Ext.FormPanel({
				 labelWidth: 75, // label settings here cascade unless overridden
				 frame:true,
				 bodyStyle:'padding:5px 5px 0; background:linear-gradient(45deg, #a7cfdf 0%,#a7cfdf 100%,#23538a 100%);',
				 width: 300,
				 height:70,
				 defaultType: 'ComboBox',
				 items: [
					 new Ext.form.ComboBox({
																	 name: 'id_cotizacion_det',
																	 fieldLabel: 'Items Detalle',
																	 allowBlank: false,
																	 emptyText: 'Detalle Items...',
																	 store: new Ext.data.JsonStore({
																			 url: '../../sis_gestion_materiales/control/CotizacionDetalle/listarCotizacionDetalle',
																			 id: 'id_cotizacion_det',
																			 root: 'datos',
																			 sortInfo: {
																					 field: 'id_cotizacion_det',
																					 direction: 'ASC'
																			 },
																			 totalProperty: 'total',
																			 fields: ['id_cotizacion_det', 'nro_parte_cot','referencia_cot'],
																			 remoteSort: true,
																			 baseParams: {par_filtro: 'cdo.nro_parte_cot', id_cotizacion: id_solicitud_envio, FiltroDetalleBear:'si'}
																	 }),
																	 valueField: 'id_cotizacion_det',
																	 displayField: 'nro_parte_cot',
																	 gdisplayField: 'nro_parte_cot',
																	 hiddenName: 'id_cotizacion_det',
																	 forceSelection: true,
                                   tpl: new Ext.XTemplate([
                                       '<tpl for=".">',
                                       '<div class="x-combo-list-item">',
                                       '<p><b>Nro. Parte:</b><span style="color: green; font-weight:bold;"> {nro_parte_cot}</span></p></p>',
                                       '<p><b>Serial:</b><span style="color: green; font-weight:bold;"> {referencia_cot}</span></p></p>',
                                       '</div></tpl>'
                                     ]),
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
									]

		 					});

				this.formu_relacion_bear = simple;

				var formu_relacion_bear = new Ext.Window({
					title: '<h1 style="height:20px; font-size:15px;">Relacionar B.E.R..<p></h1>', //the title of the window
					width:320,
					height:150,
					//closeAction:'hide',
					modal:true,
					plain: true,
					items:simple,
					buttons: [{
											text:'Guardar',
											scope:this,
											handler: function(){
													this.relacionarbear(formu_relacion_bear);
											}
									},{
											text: 'Cancelar',
											handler: function(){
													formu_relacion_bear.hide();
											}
									}]

				});
				formu_relacion_bear.show();
		},

    relacionarbear : function(formu_relacion_bear){
			//Phx.CP.loadingShow();
			var rec=this.sm.getSelected();
			/*Recuperamos de la venta detalle si existe algun concepto con excento*/
			Ext.Ajax.request({
					url : '../../sis_gestion_materiales/control/DetalleSol/RelacionarHazmat',
					params : {
						'id_hazmat' : rec.data.id_cotizacion_det,
						'id_cotizacion_det': this.formu_relacion_bear.items.items[0].getValue()
					},
					success : this.successSave,
					failure : this.conexionFailure,
					timeout : this.timeout,
					scope : this
				});

				this.reload();

				formu_relacion_bear.hide();
				//Phx.CP.loadingHide();
			/**********************************************************************/
		},


    onButtonNew:function () {
        Phx.vista.CotizacionDetalle.superclass.onButtonNew.call(this);
        var tipo_tramite = Phx.CP.getPagina(this.idContenedorPadre).maestro.tipoTramite;
        this.window.items.items[0].body.dom.style.background = '#548DCA';
        this.window.mask.dom.style.background = '#000000';
        this.window.mask.dom.style.opacity = '0.9';
        this.Cmp.id_solicitud.setValue(this.maestro.id_solicitud);
        if (tipo_tramite == 'GR') {
          this.Cmp.serial.setValue(this.Cmp.referencia_cot.getValue());
          this.ocultarComponente(this.Cmp.referencia_cot);
          this.ocultarComponente(this.Cmp.id_day_week);
          this.mostrarComponente(this.Cmp.serial);
          this.Cmp.id_day_week.allowBlank = true;
          this.Cmp.serial.on('change',function(field,newValue,oldValue){
            this.Cmp.referencia_cot.setValue(this.Cmp.serial.getValue());
          },this);
        } else {
          this.mostrarComponente(this.Cmp.referencia_cot);
          this.ocultarComponente(this.Cmp.serial);
          this.mostrarComponente(this.Cmp.id_day_week);
          this.Cmp.id_day_week.allowBlank = false;

          /*Aqui aumentando para los gastos extrar en la cotizacion (Ismael Valdivia 09/10/2020)*/
          this.Cmp.tipo_cot.store.load({params:{start:0,limit:50},
  					 callback : function (r) {
                  this.Cmp.tipo_cot.setValue('Fletes - Otros');
                  this.Cmp.tipo_cot.fireEvent('select', this.Cmp.tipo_cot,'Fletes - Otros',0);
  						}, scope : this
  				});
          /*********************************************************/

          this.Cmp.tipo_cot.on('select',function(c,r,i) {
            if (this.Cmp.tipo_cot.getValue() == 'Fletes - Otros') {
                this.ocultarComponente(this.Cmp.cd);
                this.ocultarComponente(this.Cmp.id_unidad_medida_cot);
                this.ocultarComponente(this.Cmp.id_day_week);
                this.ocultarComponente(this.Cmp.explicacion_detallada_part_cot);
                this.Cmp.cd.allowBlank = true;
                this.Cmp.id_unidad_medida_cot.allowBlank = true;
                this.Cmp.id_day_week.allowBlank = true;
                this.Cmp.explicacion_detallada_part_cot.allowBlank = true;
                this.Cmp.precio_unitario.allowBlank = false;

                this.Cmp.tipo_cot.setDisabled(true);

                this.Cmp.nro_parte_cot.setValue('HAZMAT');
                this.Cmp.nro_parte_alterno_cot.setValue('HAZMAT');
                this.Cmp.referencia_cot.setValue('HAZMAT');
                this.Cmp.serial.setValue('HAZMAT');
                this.Cmp.descripcion_cot.setValue('HAZMAT');
                this.Cmp.cantidad_det.setValue('1');


            }
          },this);




        }
    },
    })
</script>
