<?php
/**
 *@package pXP
 *@file    FiltroForm.php
 *@author  Miguel Alejandro Mamani Villegas
 *@date    20-03-2017
 *@description permite filtrar varios campos antes de mostrar el contenido de una grilla
 */
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
    Phx.vista.FiltroForm=Ext.extend(Phx.frmInterfaz,{
        constructor:function(config)
        {
            this.panelResumen = new Ext.Panel({html:''});
            this.Grupos = [{

                xtype: 'fieldset',
                border: false,
                autoScroll: true,
                layout: 'form',
                items: [],
                id_grupo: 0

            },
                this.panelResumen
            ];

            Phx.vista.FiltroForm.superclass.constructor.call(this,config);
            this.init();
        },

        Atributos:[

            {
                config:{
                    name: 'desde',
                    fieldLabel: 'Desde',
                    allowBlank: true,
                    format: 'd/m/Y',
                    width: 150,
                    anchor: '97%'
                },
                type: 'DateField',
                id_grupo: 0,
                form: true
            },
            {
                config:{
                    name: 'hasta',
                    fieldLabel: 'Hasta',
                    allowBlank: true,
                    format: 'd/m/Y',
                    width: 150,
                    anchor: '97%'
                },
                type: 'DateField',
                id_grupo: 0,
                form: true
            },
        ],
        labelSubmit: '<i class="fa fa-check"></i> Aplicar Filtro',
        east: {
            url: '../../../sis_gestion_materiales/vista/solicitud_mayor_500000/SolicitudMayor500000.php',
            title: 'Detalle Solicitud',
            width: '75%',
            cls: 'SolicitudMayor500000'
        },
        title: 'Filtros Para el Reporte de Solicitud Mayot 50000',
        // Funcion guardar del formulario
        onSubmit: function(o) {
            var me = this;
            if (me.form.getForm().isValid()) {
                var parametros = me.getValForm();
                console.log('parametros ....', parametros);
                this.onEnablePanel(this.idContenedor + '-east', parametros)
            }
        }

    })
</script>