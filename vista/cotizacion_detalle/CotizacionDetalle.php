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
<script>
Phx.vista.CotizacionDetalle=Ext.extend(Phx.gridInterfaz, {
        bnew: true,
        fheight: '45%',
        fwidth: '75%',
        constructor: function (config) {

            this.Grupos = [
                {
                    layout: 'column',
                    border: false,
                    autoHeight : true,
                    defaults: {
                        border: false,
                        autoHeight: false,
                        bodyStyle: 'padding-right:4px'
                    },
                    items: [
                        {
                            xtype: 'fieldset',
                            columnWidth: 0.5,
                            defaults: {
                                anchor: '-20' // leave room for error icon
                            },
                            title: 'Datos Part Number',
                            items: [],
                            id_grupo: 0,
                            flex:1,
                            autoHeight : true,
                            margins:'2 2 2 2'
                        },

                        {
                            xtype: 'fieldset',
                            columnWidth: 0.5,
                            title: 'Datos Cotización',
                            items: [],
                            margins:'2 10 2 2',
                            id_grupo:1,
                            autoHeight : true,
                            flex:1
                        }
                    ]
                }];



            this.maestro = config.maestro;
            //llama al constructor de la clase padre
            Phx.vista.CotizacionDetalle.superclass.constructor.call(this, config);
            this.init();
            this.grid.addListener('cellclick', this.oncellclick,this);
            this.grid.on('rowcontextmenu', function(grid, rowIndex, e) {
                e.stopEvent();
                var selModel = this.grid.getSelectionModel();
                if (!selModel.isSelected(rowIndex)) {
                    selModel.selectRow(rowIndex);
                    this.fireEvent('rowclick', this, rowIndex, e);
                }
                this.ctxMenu.showAt(e.getXY())
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
                    anchor: '50%',
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
                config: {
                    name: 'nro_parte_cot',
                    fieldLabel: 'Nro. Parte',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    maxLength: 50

                },
                type: 'TextField',
                filters: {pfiltro: 'cde.nro_parte_cot', type: 'string'},
                id_grupo: 0,
                grid: true,
                egrid: true,
                form: true
            },
            {
                config: {
                    name: 'nro_parte_alterno_cot',
                    fieldLabel: 'Nro. Parte Alterno',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    maxLength: 50
                },
                type: 'TextField',
                filters: {pfiltro: 'cde.nro_parte_alterno_cot', type: 'string'},
                id_grupo: 0,
                grid: true,
                egrid: true,
                form: true
            },
            {
                config: {
                    name: 'referencia_cot',
                    fieldLabel: 'Referencia',
                    allowBlank: true,
                    anchor: '80%',
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
                    name: 'descripcion_cot',
                    fieldLabel: 'Descripcion',
                    allowBlank: true,
                    anchor: '80%',
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
                    fieldLabel: 'Explicacion Detallada P/N',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 200,
                    maxLength: 100
                },
                type: 'TextArea',
                filters: {pfiltro: 'cde.explicacion_detallada_part_cot', type: 'string'},
                id_grupo: 0,
                grid: true,
                egrid: true,
                form: true
            },
            {
                config: {
                    name: 'tipo_cot',
                    fieldLabel: 'Tipo',
                    allowBlank: false,
                    emptyText: 'Elija una opción...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'local',
                    anchor: '50%',
                    gwidth: 80,
                    store: ['Consumible', 'Rotable','Herramienta','Otros Cargos','NA']

                },
                type: 'ComboBox',
                id_grupo: 0,
                grid: true,
                egrid: true,
                form: true

            },
            {
                config: {
                    name: 'codigo',
                    fieldLabel: 'Unidad Medida',
                    allowBlank: true,
                    anchor: '80%',
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
                    width: 120,
                    gwidth: 120,
                    maxLength: 1000,
                    style: 'background-color: #9BF592; background-image: none;'
                },
                type: 'NumberField',
                filters: {pfiltro: 'cde.cantidad_det', type: 'numeric'},
                id_grupo: 0,
                grid: true,
                egrid: true,
                form: true
            },
            {
                config:{
                    name:'cd',
                    fieldLabel:'CD',
                    typeAhead: true,
                    allowBlank:true,
                    triggerAction: 'all',
                    emptyText:'Tipo...',
                    selectOnFocus:true,
                    mode:'local',
                    store:new Ext.data.ArrayStore({
                        fields: ['ID', 'valor'],
                        data :	[
                            ['1','AR'],
                            ['2','OH'],
                            ['3','EXC'],
                            ['4','NEW'],
                            ['5','NE'],
                            ['6','NSV'],
                            ['7','SVC'],
                            ['8','SCR'],
                            ['9','BER'],
                            ['10','RP'],
                            ['11','FN'],
                            ['12','RTC'],
                            ['13','SV'],
                            ['14','INSP'],
                            ['15','AS IS'],
                            ['16','NS'],
                            ['17','I/T']
                        ]
                    }),
                    valueField:'valor',
                    displayField:'valor',
                    anchor: '50%',
                    gwidth:100

                },
                type:'ComboBox',
                id_grupo:1,
                grid:true,
                egrid: true,
                form:true
            },
            {
                config: {
                    name: 'precio_unitario',
                    fieldLabel: 'Precio Unitario',
                    currencyChar: ' ',
                    allowBlank: true,
                    width: 120,
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
                    width: 100,
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
                    anchor: '50%',
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
                    anchor: '80%',
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
                    anchor: '80%',
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
                    anchor: '80%',
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
                    anchor: '80%',
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
                    anchor: '80%',
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
                    anchor: '80%',
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
                    anchor: '80%',
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
            {name: 'desc_codigo_tipo', type: 'string'}

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
                params:{'id_detalle':rec.data.id_cotizacion_det},
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
    }
    })
</script>
		
		