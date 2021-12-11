<?php
/**
 *@package pXP
 *@file gen-Reporte.php
 *@author  MMV
 *@date  07/10/2020
 *@description Reporte para controlar las firmas del RPC
 */

header("content-type: text/javascript; charset=UTF-8");
?>

<script>
    Phx.vista.ReporteControlRpc= Ext.extend(Phx.frmInterfaz, {
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
                    store:['Todos','Gerencia de Operaciones','Gerencia de Mantenimiento','Almacenes Consumibles o Rotables','Centro de Entrenamiento Aeronautico Civil','Reparación de Repuestos']

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
                    anchor: '35%',
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
                    anchor: '35%',
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
        ActSave : '../../sis_gestion_materiales/control/Solicitud/ControlRpc',
        topBar : true,
        botones : false,
        labelSubmit : 'Imprimir',
        tooltipSubmit : '<b>Generar Reporte</b>',
        constructor : function(config) {
            Phx.vista.ReporteControlRpc.superclass.constructor.call(this, config);
            this.init();
        },


        iniciarEventos:function(){
            this.cmpFechaIni = this.getComponente('fecha_ini');
            this.cmpFechaFin = this.getComponente('fecha_fin');

        },
        tipo : 'reporte',
        clsSubmit : 'bexcel',

        agregarArgsExtraSubmit: function() {

            this.argumentExtraSubmit.origen = this.Cmp.origen_pedido.getRawValue();

        }

    })
</script>
