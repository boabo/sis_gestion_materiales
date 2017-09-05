<?php
/**
*@package pXP
*@file gen-SolicitudMayor500000.php
*@author  (admin)
*@date 05-09-2017 15:19:59
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.SolicitudMayor500000=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.SolicitudMayor500000.superclass.constructor.call(this,config);
        this.grid.getTopToolbar().disable();
        this.grid.getBottomToolbar().disable();
		this.init();
        this.addButton('btnChequeoDocumentosWf',{
            text: 'Documentos',
            grupo: [0,1,2,3,4,5],
            iconCls: 'bchecklist',
            disabled: true,
            handler: this.loadCheckDocumentosRecWf,
            tooltip: '<b>Documentos del Reclamo</b><br/>Subir los documetos requeridos en el Reclamo seleccionado.'
        });
        this.addButton('Cotizacion',{
            grupo: [3],
            text: 'Cotización',
            iconCls: 'bdocuments',
            disabled: false,
            handler: this.onButtonCotizacion,
            tooltip: '<b>Cotización</b>',
            scope:this
        });
        this.addButton('diagrama_gantt',{
            grupo:[0,1,2,3,4,5],
            text:'Gant',
            iconCls: 'bgantt',
            disabled:true,
            handler:diagramGantt,
            tooltip: '<b>Diagrama Gantt de proceso macro</b>'
        });
        function diagramGantt(){
            var data=this.sm.getSelected().data.id_proceso_wf;
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_workflow/control/ProcesoWf/diagramaGanttTramite',
                params:{'id_proceso_wf':data},
                success:this.successExport,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        }
		//this.load({params:{start:0, limit:this.tam_pag}})
	},
			
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
                fieldLabel: 'Nor. Tramites',
                allowBlank: true,
                anchor: '80%',
                gwidth: 150,
                maxLength:50
            },
            type:'TextField',
            filters:{pfiltro:'smi.nro_tramite',type:'string'},
            id_grupo:1,
            grid:true,
            bottom_filter:true,
            form:true
        },
        {
            config:{
                name: 'fecha_solicitud',
                fieldLabel: 'Fecha Solicitud',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
            type:'DateField',
            filters:{pfiltro:'smi.fecha_solicitud',type:'date'},
            id_grupo:1,
            grid:true,
            form:true
        },
		{
			config:{
				name: 'funcionario',
				fieldLabel: 'Funcionario',
				allowBlank: true,
				anchor: '80%',
				gwidth: 170,
				maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'smi.funcionario',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
        {
            config:{
                name: 'nro_po',
                fieldLabel: 'Nro. PO',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:50
            },
            type:'TextField',
            filters:{pfiltro:'smi.nro_po',type:'string'},
            id_grupo:1,
            grid:true,
            bottom_filter:true,
            form:true
        },
		{
			config:{
				name: 'fecha_po',
				fieldLabel: 'Fecha PO',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'smi.fecha_po',type:'date'},
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
                gwidth: 170,
                maxLength:-5
            },
            type:'TextField',
            filters:{pfiltro:'smi.proveedor',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config: {
                name: 'monto_dolares',
                fieldLabel: 'Monto Dolares',
                currencyChar: ' ',
                allowBlank: true,
                width: 100,
                gwidth: 120,
                disabled: true,
                maxLength: 1245186
            },
            type: 'MoneyField',
            filters: {pfiltro: 'smi.monto_dolares', type: 'numeric'},
            id_grupo: 1,
            grid: true,
            form: false
        },
        {
            config: {
                name: 'monto_bolivianos',
                fieldLabel: 'Monto Bolivianos',
                currencyChar: ' ',
                allowBlank: true,
                width: 100,
                gwidth: 120,
                disabled: true,
                maxLength: 1245186
            },
            type: 'MoneyField',
            filters: {pfiltro: 'smi.monto_bolivianos', type: 'numeric'},
            id_grupo: 1,
            grid: true,
            form: false
        }
	],
	tam_pag:50,	
	title:'Solicitud Mayor',
	ActSave:'../../sis_gestion_materiales/control/SolicitudMayor500000/insertarSolicitudMayor500000',
	ActDel:'../../sis_gestion_materiales/control/SolicitudMayor500000/eliminarSolicitudMayor500000',
	ActList:'../../sis_gestion_materiales/control/SolicitudMayor500000/listarSolicitudMayor500000',
	id_store:'id_solicitud',
	fields: [
		{name:'id_solicitud', type: 'numeric'},
        {name:'id_proceso_wf', type: 'numeric'},
		{name:'nro_po', type: 'string'},
		{name:'fecha_solicitud', type: 'date',dateFormat:'Y-m-d'},
		{name:'funcionario', type: 'string'},
		{name:'fecha_po', type: 'date',dateFormat:'Y-m-d'},
		{name:'monto_dolares', type: 'numeric'},
		{name:'nro_tramite', type: 'string'},
		{name:'proveedor', type: 'string'},
		{name:'monto_bolivianos', type: 'numeric'}
		
	],
	sortInfo:{
		field: 'id_solicitud',
		direction: 'ASC'
	},
    loadValoresIniciales:function(){
        Phx.vista.SolicitudMayor500000.superclass.loadValoresIniciales.call(this);
        //this.getComponente('id_int_comprobante').setValue(this.maestro.id_int_comprobante);
    },
    onReloadPage:function(param){
        //Se obtiene la gestión en función de la fecha del comprobante para filtrar partidas, cuentas, etc.
        var me = this;
        this.initFiltro(param);
    },

    initFiltro: function(param){
        this.store.baseParams=param;
        this.load( { params: { start:0, limit: this.tam_pag } });
    },
    loadCheckDocumentosRecWf:function() {
        var rec=this.sm.getSelected();
        rec.data.nombreVista = this.nombreVista;
        Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
            'Chequear documento del WF',
            {
                width:'90%',
                height:500
            },
            rec.data,
            this.idContenedor,
            'DocumentoWf'
        )
    },
    onButtonCotizacion:function() {
        var rec=this.sm.getSelected();
        console.log ('Data',rec.data);
        Phx.CP.loadWindows('../../../sis_gestion_materiales/vista/cotizacion/Cotizacion.php',
            'Cotizacion de solicitud',
            {
                width:'98%',
                height:'98%'
            },
            rec.data,
            this.idContenedor,
            'Cotizacion');
    },
    preparaMenu: function(n)
    {	var rec = this.getSelectedData();
        var tb =this.tbar;

        Phx.vista.SolicitudMayor500000.superclass.preparaMenu.call(this,n);
        this.getBoton('btnChequeoDocumentosWf').setDisabled(false);
        this.getBoton('diagrama_gantt').enable();
        this.getBoton('Cotizacion').enable();

    },

    liberaMenu:function(){
        var tb = Phx.vista.SolicitudMayor500000.superclass.liberaMenu.call(this);
        if(tb){

            this.getBoton('btnChequeoDocumentosWf').setDisabled(true);
            this.getBoton('diagrama_gantt').disable();
            this.getBoton('Cotizacion').disable();

        }
        return tb
    },
	bdel:false,
	bsave:false,
    bnew:false,
    bedit:false
	}
)
</script>
		
		