<?php
/**
*@package pXP
*@file gen-DetalleSol.php
*@author  (admin)
*@date 23-12-2016 13:13:01
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.DetalleSol=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
        Phx.vista.DetalleSol.superclass.constructor.call(this,config);
        this.init();
        this.grid.addListener('cellclick', this.oncellclick,this);

    },
			
	Atributos:[
        {
            //configuracion del componente
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'id_detalle'
            },
            type:'Field',
            form:true
        },
        {
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
                name: 'nro_parte',
                fieldLabel: 'Nro. Parte',
                allowBlank: true,
                anchor: '80%',
                gwidth: 150,
                maxLength:50
            },
            type:'TextField',
            filters:{pfiltro:'det.nro_parte',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'nro_parte_alterno',
                fieldLabel: 'Nro. Parte Alterno',
                allowBlank: true,
                anchor: '80%',
                gwidth: 150,
                maxLength:50
            },
            type:'TextField',
            filters:{pfiltro:'det.nro_parte_alterno',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'referencia',
                fieldLabel: 'Referencia',
                allowBlank: true,
                anchor: '80%',
                gwidth: 200,
                maxLength:100
            },
            type:'TextField',
            filters:{pfiltro:'det.referencia',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'descripcion',
                fieldLabel: 'Descripcion',
                allowBlank: true,
                anchor: '80%',
                gwidth: 200,
                maxLength:100
            },
            type:'TextField',
            filters:{pfiltro:'det.descripcion',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name:'tipo',
                fieldLabel:'Tipo',
                allowBlank:false,
                emptyText:'Elija una opción...',
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',
                anchor: '50%',
                gwidth: 80,
                store:['Consumible','Rotable']

            },
            type:'ComboBox',
            id_grupo:0,
            grid:true,
            form:true,
            bottom_filter:true

        },
        {
            config:{
                name: 'cantidad_sol',
                fieldLabel: 'Cantidad',
                allowBlank: true,
                anchor: '80%',
                gwidth: 50,
                maxLength:4
            },
            type:'NumberField',
            filters:{pfiltro:'det.cantidad_sol',type:'numeric'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config: {
                name: 'id_unidad_medida',
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
                anchor: '40%',
                gwidth: 60,
                minChars: 2,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['codigo']);
                }
            },
            type: 'ComboBox',
            id_grupo: 0,
            filters: {pfiltro:' u.codigo', type:'string'},
            grid: true,
            form: true

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
            filters:{pfiltro:'det.estado_reg',type:'string'},
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
            filters:{pfiltro:'det.id_usuario_ai',type:'numeric'},
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
            filters:{pfiltro:'det.usuario_ai',type:'string'},
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
            filters:{pfiltro:'det.fecha_reg',type:'date'},
            id_grupo:1,
            grid:true,
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
                name: 'fecha_mod',
                fieldLabel: 'Fecha Modif.',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
            },
            type:'DateField',
            filters:{pfiltro:'det.fecha_mod',type:'date'},
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
        }

    ],
	tam_pag:50,	
	title:'Detalle_sol',
	ActSave:'../../sis_gestion_materiales/control/DetalleSol/insertarDetalleSol',
	ActDel:'../../sis_gestion_materiales/control/DetalleSol/eliminarDetalleSol',
	ActList:'../../sis_gestion_materiales/control/DetalleSol/listarDetalleSol',
	id_store:'id_detalle',
	fields: [
		{name:'id_detalle', type: 'numeric'},
		{name:'id_solicitud', type: 'numeric'},
		{name:'descripcion', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_unidad_medida', type: 'numeric'},
		{name:'nro_parte', type: 'string'},
		{name:'referencia', type: 'string'},
		{name:'nro_parte_alterno', type: 'string'},
		{name:'id_moneda', type: 'numeric'},
		{name:'precio', type: 'numeric'},
		{name:'cantidad_sol', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
        {name:'codigo', type: 'string'},
        {name:'desc_descripcion', type: 'string'},
        {name:'revisado', type: 'string'},
        {name:'tipo', type: 'string'}



	],
	sortInfo:{
		field: 'id_detalle',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false,
    btest: false,

        onReloadPage: function (m) {
            this.maestro = m;

            this.store.baseParams = {id_solicitud: this.maestro.id_solicitud};
            this.load({params: {start: 0, limit: 50}});
            if(this.maestro.estado ==  'borrador'){
                this.getBoton('new').enable();

            }

        },

        loadValoresIniciales: function () {
            this.Cmp.id_solicitud.setValue(this.maestro.id_solicitud);
            Phx.vista.DetalleSol.superclass.loadValoresIniciales.call(this);

        },
    preparaMenu:function(n){
        Phx.vista.DetalleSol.superclass.preparaMenu.call(this,n);
        if(this.maestro.estado ==  'borrador' ||this.maestro.estado==  'Borrador'){
            this.getBoton('edit').enable();
            this.getBoton('new').enable();
            this.getBoton('del').enable();

        }
        /*else{

            this.getBoton('edit').disable();
            this.getBoton('new').disable();
            this.getBoton('del').disable();
        }*/
    },
    /*mostrar:function () {




    },*/
    liberaMenu: function() {
        Phx.vista.DetalleSol.superclass.liberaMenu.call(this);
        if(this.maestro&&(this.maestro.estado !=  'borrador')){

            //this.getBoton('edit').disable();
            //this.getBoton('new').disable();
            //this.getBoton('del').disable();
        }
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
        var d = record.data
        Ext.Ajax.request({
            url:'../../sis_gestion_materiales/control/DetalleSol/cambiarRevision',
            params:{ id_detalle: d.id_detalle,revisado:d.revisado},
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
		
		