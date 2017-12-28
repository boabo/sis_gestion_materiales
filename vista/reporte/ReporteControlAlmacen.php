<?php
/**
 *@package pXP
 *@file gen-Reporte.php
 *@author  MMV
 *@date  10-02-2017
 *@description
 */

header("content-type: text/javascript; charset=UTF-8");
?>

<script>
    Phx.vista.ReporteControlAlmacen= Ext.extend(Phx.frmInterfaz, {
        Atributos : [

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
                    anchor: '35%',
                    gwidth: 230,
                    store:['Todos','Gerencia de Operaciones','Gerencia de Mantenimiento','Almacenes Consumibles o Rotables','Centro de Entrenamiento Aeronautico Civil']

                },
                type:'ComboBox',
                id_grupo:0,
                grid:true,
                form:true,
                bottom_filter:true

            },
            {
                config: {
                    name: 'estado',
                    fieldLabel: 'Estado',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_gestion_materiales/control/Solicitud/listarEstado',
                        id: 'id_tipo_estado',
                        root: 'datos',
                        sortInfo: {
                            field: 'codigo',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_tipo_estado', 'codigo'],
                        remoteSort: true,
                        baseParams: {par_filtro: 't.codigo'}
                    }),
                    valueField: 'id_tipo_estado',
                    displayField: 'codigo',
                    gdisplayField: 'codigo',
                    hiddenName: 'estado',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 100,
                    queryDelay: 1000,
                    anchor: '35%',
                    gwidth: 230,
                    minChars: 2,
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['codigo']);
                    },
                    enableMultiSelect : true
                },
                type: 'AwesomeCombo',
                filters: {pfiltro: 't.codigo',type: 'string'},
                form: true

            },
           {
                config: {
                    name: 'estado_op',
                    fieldLabel: 'Estado',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_gestion_materiales/control/Solicitud/listarEstadoOp',
                        id: 'id_tipo_estado',
                        root: 'datos',
                        sortInfo: {
                            field: 'codigo',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_tipo_estado', 'codigo'],
                        remoteSort: true,
                        baseParams: {par_filtro: 't.codigo'}
                    }),
                    valueField: 'id_tipo_estado',
                    displayField: 'codigo',
                    gdisplayField: 'codigo',
                    hiddenName: 'estado',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 100,
                    queryDelay: 1000,
                    anchor: '35%',
                    gwidth: 230,
                    minChars: 2,
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['codigo']);
                    },
                    enableMultiSelect : true
                },
                type: 'AwesomeCombo',
                filters: {pfiltro: 't.codigo',type: 'string'},
                form: true

            },
            {
                config: {
                    name: 'estado_ro',
                    fieldLabel: 'Estado',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_gestion_materiales/control/Solicitud/listarEstadoRo',
                        id: 'id_tipo_estado',
                        root: 'datos',
                        sortInfo: {
                            field: 'codigo',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_tipo_estado', 'codigo'],
                        remoteSort: true,
                        baseParams: {par_filtro: 't.codigo'}
                    }),
                    valueField: 'id_tipo_estado',
                    displayField: 'codigo',
                    gdisplayField: 'codigo',
                    hiddenName: 'estado',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 100,
                    queryDelay: 1000,
                    anchor: '35%',
                    gwidth: 230,
                    minChars: 2,
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['codigo']);
                    },
                    enableMultiSelect : true
                },
                type: 'AwesomeCombo',
                filters: {pfiltro: 't.codigo',type: 'string'},
                form: true

            },{
                config: {
                    name: 'estado_sac',
                    fieldLabel: 'Estado',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_gestion_materiales/control/Solicitud/listarEstadoSAC',
                        id: 'id_tipo_estado',
                        root: 'datos',
                        sortInfo: {
                            field: 'codigo',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_tipo_estado', 'codigo'],
                        remoteSort: true,
                        baseParams: {par_filtro: 't.codigo'}
                    }),
                    valueField: 'id_tipo_estado',
                    displayField: 'codigo',
                    gdisplayField: 'codigo',
                    hiddenName: 'estado',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 100,
                    queryDelay: 1000,
                    anchor: '35%',
                    gwidth: 230,
                    minChars: 2,
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['codigo']);
                    },
                    enableMultiSelect : true
                },
                type: 'AwesomeCombo',
                filters: {pfiltro: 't.codigo',type: 'string'},
                form: true

            },
            {
                config:{
                    name: 'fecha_ini',
                    fieldLabel: 'Fecha Inicio',
                    allowBlank: false,
                    anchor: '30%',
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
                    anchor: '30%',
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
        title : 'Generar Reporte',
        ActSave : '../../sis_gestion_materiales/control/Solicitud/ControlPartesAlmacen',
        topBar : true,
        botones : false,
        labelSubmit : 'Imprimir',
        tooltipSubmit : '<b>Generar PDF</b>',
        constructor : function(config) {
            Phx.vista.ReporteControlAlmacen.superclass.constructor.call(this, config);
            this.init();
            this.ocultarComponente(this.Cmp.estado);
            this.ocultarComponente(this.Cmp.estado_op);
            this.ocultarComponente(this.Cmp.estado_ro);
            this.Cmp.origen_pedido.on('select',function(c,r,i) {
                if (this.Cmp.origen_pedido.getValue() == 'Gerencia de Mantenimiento') {
                    this.ocultarComponente(this.Cmp.estado_op);
                    this.ocultarComponente(this.Cmp.estado_ro);
                    this.ocultarComponente(this.Cmp.estado_sac);
                    this.mostrarComponente(this.Cmp.estado);
                }
                if(this.Cmp.origen_pedido.getValue() == 'Gerencia de Operaciones'){
                    this.mostrarComponente(this.Cmp.estado_op);
                    this.ocultarComponente(this.Cmp.estado_ro);
                    this.ocultarComponente(this.Cmp.estado_sac);
                    this.ocultarComponente(this.Cmp.estado);
                }
                if(this.Cmp.origen_pedido.getValue() == 'Almacenes Consumibles o Rotables'){
                    this.ocultarComponente(this.Cmp.estado_op);
                    this.ocultarComponente(this.Cmp.estado_sac);
                    this.mostrarComponente(this.Cmp.estado_ro);
                    this.ocultarComponente(this.Cmp.estado);
                }if (this.Cmp.origen_pedido.getValue() == 'Todos'){
                    this.ocultarComponente(this.Cmp.estado_op);
                    this.ocultarComponente(this.Cmp.estado_ro);
                    this.ocultarComponente(this.Cmp.estado_sac);
                    this.mostrarComponente(this.Cmp.estado);
                }if (this.Cmp.origen_pedido.getValue() == 'Centro de Entrenamiento Aeronautico Civil'){
                    this.ocultarComponente(this.Cmp.estado_op);
                    this.ocultarComponente(this.Cmp.estado_ro);
                    this.ocultarComponente(this.Cmp.estado);
                    this.mostrarComponente(this.Cmp.estado_sac);
                }
            },this);

        },


        iniciarEventos:function(){
            this.cmpFechaIni = this.getComponente('fecha_ini');
            this.cmpFechaFin = this.getComponente('fecha_fin');

        },
        tipo : 'reporte',
        clsSubmit : 'bprint',

        agregarArgsExtraSubmit: function() {

            this.argumentExtraSubmit.origen = this.Cmp.origen_pedido.getRawValue();
            this.argumentExtraSubmit.estados = this.Cmp.estado.getRawValue();
        }

    })
</script>