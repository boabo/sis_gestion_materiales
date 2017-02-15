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
                    anchor: '33%',
                    gwidth: 230,
                    store:['Gerencia de Operaciones','Gerencia de Mantenimiento','Almacenes Consumibles o Rotables']

                },
                type:'ComboBox',
                id_grupo:0,
                grid:true,
                form:true,
                bottom_filter:true

            },
            {
                config:{
                    name:'estado',
                    fieldLabel:'Estado ',
                    allowBlank:true,
                    emptyText:'Elija una opción...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    anchor: '33%',
                    gwidth: 230,
                    enableMultiSelect : true,
                    store:['borrador','vobo_area','vobo_aeronavegabilidad','vobo_almacen','revision','cotizacion','compra','despachado','arribo','desaduanizado','almacen','finalizado','anulado']

                },
                type:'AwesomeCombo',
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
        },


        iniciarEventos:function(){
            this.cmpFechaIni = this.getComponente('fecha_ini');
            this.cmpFechaFin = this.getComponente('fecha_fin');
        },
        tipo : 'reporte',
        clsSubmit : 'bprint',

        agregarArgsExtraSubmit: function() {

            this.argumentExtraSubmit.origen = this.Cmp.origen_pedido.getRawValue();
            this.argumentExtraSubmit.est = this.Cmp.estado.getRawValue();
        }

    })
</script>