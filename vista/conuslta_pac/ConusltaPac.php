<?php
/**
*@package pXP
*@file gen-ConusltaPac.php
*@author  (miguel.mamani)
*@date 03-07-2018 16:19:47
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.ConusltaPac=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.ConusltaPac.superclass.constructor.call(this,config);
		this.init();
        this.store.baseParams.pes_estado = 'pendientes';
        this.finCons = true;
        this.addButton('btnChequeoDocumentosWf',{
            text: 'Documentos',
            grupo: [0,1,2,3,4,5,6,7],
            iconCls: 'bchecklist',
            disabled: true,
            handler: this.loadCheckDocumentosRecWf,
            tooltip: '<b>Documentos</b><br/>Subir los documetos requeridos.'
        });
		this.load({params:{start:0, limit:this.tam_pag}})
	},
    gruposBarraTareas:[
        {name:'pendientes',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Pendientes</h1>',grupo:1,height:0},
        {name:'revisados',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Revisados</h1>',grupo:1,height:0}
    ],
    actualizarSegunTab: function(name, indice){
        if(this.finCons){
            this.store.baseParams.pes_estado = name;
            this.load({params: {start: 0, limit: this.tam_pag}});
        }
    },
    beditGroups: [0,1],
    bdelGroups:  [0,1],
    bactGroups:  [2,1],
    bexcelGroups: [2,1],
    bnewGroups: [0,1],
			
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
                fieldLabel: 'Nro. Tramite',
                allowBlank: true,
                anchor: '80%',
                gwidth: 150,
                maxLength:50
            },
            type:'TextField',
            filters:{pfiltro:'so.nro_tramite',type:'string'},
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
                gwidth: 200,
                maxLength:100
            },
            type:'TextField',
            filters:{pfiltro:'so.origen_pedido',type:'string'},
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
				gwidth: 200,
				maxLength:-5
			},
				type:'TextField',
				filters:{pfiltro:'fu.desc_funcionario1',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
        {
            config:{
                name: 'monto',
                fieldLabel: 'Monto',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:1179650
            },
            type:'NumberField',
            filters:{pfiltro:'pa.monto',type:'numeric'},
            id_grupo:1,
            grid:true,
            form:true
        },
		{
			config:{
				name: 'codigo_internacional',
				fieldLabel: 'Moneda',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'TextField',
				filters:{pfiltro:'mo.codigo_internacional',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
        {
            config:{
                name: 'fecha_requerida',
                fieldLabel: 'Fecha Requerida',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
            type:'DateField',
            filters:{pfiltro:'so.fecha_requerida',type:'date'},
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
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
            type:'DateField',
            filters:{pfiltro:'so.fecha_solicitud',type:'date'},
            id_grupo:1,
            grid:true,
            form:true
        },
		{
			config:{
				name: 'tipo_solicitud',
				fieldLabel: 'Tipo Solicitud',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'so.tipo_solicitud',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
        {
            config:{
                name: 'motivo_solicitud',
                fieldLabel: 'Motivo Solicitud',
                allowBlank: true,
                anchor: '80%',
                gwidth: 200,
                maxLength:10000
            },
            type:'TextField',
            filters:{pfiltro:'so.motivo_solicitud',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'observaciones_sol',
                fieldLabel: 'Observaciones',
                allowBlank: true,
                anchor: '80%',
                gwidth: 200,
                maxLength:10000
            },
            type:'TextField',
            filters:{pfiltro:'so.observaciones_sol',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
		{
			config:{
				name: 'estado',
				fieldLabel: 'estado',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'cpa.estado',type:'string'},
				id_grupo:1,
				grid:false,
				form:true
		}


	],
	tam_pag:50,	
	title:'Consulta Pac',
	ActSave:'../../sis_gestion_materiales/control/ConusltaPac/insertarConusltaPac',
	ActDel:'../../sis_gestion_materiales/control/ConusltaPac/eliminarConusltaPac',
	ActList:'../../sis_gestion_materiales/control/ConusltaPac/listarConusltaPac',
	id_store:'id_proceso_wf',
	fields: [
		{name:'id_proceso_wf', type: 'numeric'},
		{name:'desc_funcionario1', type: 'string'},
		{name:'nro_tramite', type: 'string'},
		{name:'codigo_internacional', type: 'string'},
		{name:'tipo_solicitud', type: 'string'},
		{name:'estado', type: 'string'},
		{name:'monto', type: 'numeric'},
		{name:'origen_pedido', type: 'string'},
		{name:'observaciones_sol', type: 'string'},
		{name:'fecha_requerida', type: 'date',dateFormat:'Y-m-d'},
		{name:'id_solicitud', type: 'numeric'},
		{name:'fecha_solicitud', type: 'date',dateFormat:'Y-m-d'},
		{name:'motivo_solicitud', type: 'string'}
		
	],
	sortInfo:{
		field: 'id_proceso_wf',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false,
    bnew:false,
    bedit:false,
    preparaMenu: function(n) {
        this.getBoton('btnChequeoDocumentosWf').setDisabled(false);
        Phx.vista.ConusltaPac.superclass.preparaMenu.call(this,n);
    },
    liberaMenu:function(){
        var tb = Phx.vista.ConusltaPac.superclass.liberaMenu.call(this);
        if(tb){
            this.getBoton('btnChequeoDocumentosWf').setDisabled(true);
        }
        return tb
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
    }
	}
)
</script>
		
		