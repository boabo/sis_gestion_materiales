<?php
/**
*@package pXP
*@file gen-Cotizacion.php
*@author  (miguel.mamani)
*@date 04-07-2017 14:03:30
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Cotizacion=Ext.extend(Phx.gridInterfaz,{
    fwidth: '50%',
    fheight: '50%',
	constructor:function(config){
        this.idContenedor = config.idContenedor;
		this.maestro=config;
    	//llama al constructor de la clase padre
        this.id_solicitud=this.maestro.id_solicitud;
        //console.log('hola',this.maestro);
        //console.log('heee',this.idContenedor);
		Phx.vista.Cotizacion.superclass.constructor.call(this,config);
		this.init();
        this.store.baseParams={id_solicitud:this.id_solicitud,tipo_interfaz: 'Solicitud'};
        this.load({params:{start:0, limit:this.tam_pag}});
        this.grid.addListener('cellclick', this.oncellclick,this);
        //this.iniciarEvento();
	},
    Grupos: [
        {
            layout: 'column',
            border: false,
            defaults: {
                border: false
            },

            items: [
                {
                    bodyStyle: 'padding-right:10px;',
                    items: [

                        {
                            xtype: 'fieldset',
                            title: 'Datos Cotización',
                            autoHeight: true,
                            width: 600,
                            items: [/*this.compositeFields()*/],
                            id_grupo: 0
                        }

                    ]
                }

            ]
        }],
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_cotizacion'
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
                name: 'adjudicado',
                fieldLabel: 'Adjudicado',
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
                    }
                    return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:35px;width:35px;" type="checkbox"  {0}></div>',checked);

                }
            },
            type: 'TextField',
            filters: { pfiltro:'cts.adjudicado',type:'string'},
            id_grupo: 0,
            grid: true,
            form: false
        },
        {
            config:{
                name: 'nro_tramite',
                fieldLabel: 'Nro.Tramite',
                allowBlank: true,
                anchor: '80%',
                gwidth: 170,
                maxLength:255
            },
            type:'TextField',
            filters:{pfiltro:'cts.nro_tramite',type:'string'},
            id_grupo:0,
            grid:true,
            form:false
        },
        {
            config: {
                name: 'id_proveedor',
                fieldLabel: 'Proveedor',
                allowBlank: false,
                emptyText: 'Elija una opción...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_gestion_materiales/control/Cotizacion/listarProvedor',
                    id: 'id_prov',
                    root: 'datos',
                    sortInfo: {
                        field: 'desc_proveedor',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_solicitud', 'id_gestion_proveedores','id_prov','desc_proveedor'],
                    remoteSort: true,
                    baseParams: {par_filtro: 'desc_proveedor'}
                }),
                valueField: 'id_prov',
                displayField: 'desc_proveedor',
                gdisplayField: 'desc_proveedor',
                hiddenName: 'id_proveedor',
                forceSelection: true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'remote',
                pageSize: 15,
                queryDelay: 1000,
                anchor: '70%',
                gwidth: 300,
                minChars: 2,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['desc_proveedor']);
                }
            },
            type: 'ComboBox',
            id_grupo: 0,
            filters: {pfiltro: 'p.desc_proveedor',type: 'string'},
            grid: true,
            form: true
        },
        {
            config:{
                name: 'nro_cotizacion',
                fieldLabel: 'Nro.Cotización',
                allowBlank: false,
                anchor: '50%',
                gwidth: 170,
                maxLength:500
            },
            type:'TextField',
            filters:{pfiltro:'cts.nro_cotizacion',type:'string'},
            id_grupo:0,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'fecha_cotizacion',
                fieldLabel: 'Fecha Cotización',
                allowBlank: false,
                anchor: '50%',
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
            type:'DateField',
            filters:{pfiltro:'cts.fecha_cotizacion',type:'date'},
            id_grupo:0,
            grid:true,
            form:true
        },
        {
            config: {
                name: 'monto_total',
                fieldLabel: 'Monto Total',
                currencyChar: ' ',
                allowBlank: true,
                width: 100,
                gwidth: 120,
                disabled: true,
                maxLength: 1245186
            },
            type: 'MoneyField',
            filters: {pfiltro: 'cts.monto_total', type: 'numeric'},
            id_grupo: 1,
            grid: true,
            form: false
        },
        {
            config:{
                name:'id_moneda',
                origen:'MONEDA',
                allowBlank:false,
                fieldLabel:'Moneda',
                gdisplayField:'desc_moneda',//mapea al store del grid
                anchor: '50%',
                gwidth: 100,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['desc_moneda']);
                }
            },
            type:'ComboRec',
            id_grupo:0,
            filters:{
                pfiltro:'incbte.desc_moneda',
                type:'string'
            },
            grid:true,
            egrid:true,
            form:true
        },
        {
            config:{
                name: 'recomendacion',
                fieldLabel: 'Recomendación',
                allowBlank: true,
                anchor: '70%',
                gwidth: 200,
                maxLength:10000
            },
            type:'TextArea',
            filters:{pfiltro:'sol.obs',type:'string'},
            id_grupo:0,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'obs',
                fieldLabel: 'Observaciones',
                allowBlank: true,
                anchor: '70%',
                gwidth: 200,
                maxLength:10000
            },
            type:'TextArea',
            filters:{pfiltro:'cts.obs',type:'string'},
            id_grupo:0,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'pie_pag',
                fieldLabel: 'Pie de Pagina',
                allowBlank: true,
                anchor: '70%',
                gwidth: 200,
                maxLength:10000
            },
            type:'TextArea',
            filters:{pfiltro:'cts.pie_pag',type:'string'},
            id_grupo:0,
            grid:true,
            form:true
        },
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'cts.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: '',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'cts.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
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
				filters:{pfiltro:'cts.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
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
				filters:{pfiltro:'cts.usuario_ai',type:'string'},
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
				filters:{pfiltro:'cts.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Cotización ',
	ActSave:'../../sis_gestion_materiales/control/Cotizacion/insertarCotizacion',
	ActDel:'../../sis_gestion_materiales/control/Cotizacion/eliminarCotizacion',
	ActList:'../../sis_gestion_materiales/control/Cotizacion/listarCotizacion',
	id_store:'id_cotizacion',
	fields: [
		{name:'id_cotizacion', type: 'numeric'},
		{name:'id_solicitud', type: 'numeric'},
		{name:'id_moneda', type: 'numeric'},
		{name:'nro_tramite', type: 'string'},
		{name:'fecha_cotizacion', type: 'date',dateFormat:'Y-m-d'},
		{name:'adjudicado', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_proveedor', type: 'numeric'},
		{name:'monto_total', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
        {name:'desc_moneda', type: 'string'},
        {name:'desc_proveedor', type: 'string'},
        {name:'nro_cotizacion', type: 'string'},
        {name:'recomendacion', type: 'string'},
        {name:'obs', type: 'string'},
        {name:'pie_pag', type: 'string'}
	],
	sortInfo:{
		field: 'id_cotizacion',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false,
    south:
        {
            url:'../../../sis_gestion_materiales/vista/cotizacion_detalle/CotizacionDetalle.php',
            title:'Detalle',
            height:'50%',
            cls:'CotizacionDetalle'
        },
    onButtonNew:function(){
	    console.log('id:',this.getComponente('id_solicitud'));
        Phx.vista.Cotizacion.superclass.onButtonNew.call(this);
        this.getComponente('id_solicitud').setValue(this.id_solicitud);
        this.Cmp.id_proveedor.store.baseParams ={id_solicitud:this.maestro.id_solicitud ,par_filtro: 'desc_proveedor'};
        this.Cmp.pie_pag.setValue('La entrega del producto es en Miami, de acuerdo a oferta del proveedor; sin embargo la recepción del mismo en almacen Cochabamba hasta en 30 dias. ');
        this.Cmp.id_moneda.setValue(2);
        this.Cmp.id_moneda.setRawValue('Dolares Americanos');

    },
   /*onButtonEdit:function () {
       var data = this.getSelectedData();
       Phx.vista.Cotizacion.superclass.onButtonNew.call(this);
       this.Cmp.id_proveedor.store.baseParams ={id_solicitud:this.maestro.id_solicitud};
       this.Cmp.fecha_cotizacion.setValue(data['fecha_cotizacion'] );
       this.Cmp.id_moneda.setValue(data['id_moneda']);
       this.Cmp.id_moneda.setRawValue(data['desc_moneda']);
       this.Cmp.id_proveedor.setValue(data['id_proveedor']);
       this.Cmp.id_proveedor.setRawValue(data['desc_proveedor']);
       this.Cmp.nro_cotizacion.setValue(data['nro_cotizacion']);
       this.Cmp.recomendacion.setValue(data['recomendacion']);
       this.Cmp.obs.setValue(data['obs']);
    },*/

    oncellclick : function(grid, rowIndex, columnIndex, e) {
        var record = this.store.getAt(rowIndex),
            fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name

        if(fieldName == 'adjudicado') {
            this.cambiarRevision(record);
            this.evento();
            //this.onButtonEdit();
        }
    },
    cambiarRevision: function(record){
        Phx.CP.loadingShow();
        var d = record.data;
        Ext.Ajax.request({
            url:'../../sis_gestion_materiales/control/Cotizacion/controlAdjudicado',
            params:{id_cotizacion: d.id_cotizacion, adjudicado: d.adjudicado},
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
    evento:function () {
        Phx.CP.getPagina(this.idContenedorPadre).reload();
    }
	}
)
</script>
		
		