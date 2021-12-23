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
    Phx.vista.ReporteRevision= Ext.extend(Phx.frmInterfaz, {
        Atributos : [

            {
                config:{
                    name: 'po_inicio',
                    fieldLabel: 'Nro PO Inicio',
                    allowBlank: false,
                    anchor: '35%',
                    gwidth: 100,
                },
                type:'NumberField',
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'po_final',
                    fieldLabel: 'Nro PO Final',
                    allowBlank: false,
                    anchor: '35%',
                    gwidth: 100,
                },
                type:'NumberField',
                id_grupo:1,
                grid:true,
                form:true
            },
        ],
        title : 'Generar Reporte',
        ActSave : '../../sis_gestion_materiales/control/Solicitud/controlReimpresion',
        topBar : true,
        botones : false,
        labelSubmit : 'Imprimir',
        tooltipSubmit : '<b>Generar Reporte</b>',
        constructor : function(config) {
            Phx.vista.ReporteRevision.superclass.constructor.call(this, config);
            this.init();
        },


        tipo : 'reporte',
        clsSubmit : 'bpdf',

        successSave :function(resp){
          var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
          var datos = objRes.ROOT.datos.mensaje;
          var convertido = JSON.parse(datos);
          /*Aqui tenemos la cantidad de datos*/
          var cantidad = convertido.t_tramites_po.length;
          /***********************************/
          for (var i = 0; i < cantidad; i++) {
            Ext.Ajax.request({
                    url : '../../sis_gestion_materiales/control/Solicitud/reporteComparacionByS',
                    params : {
                      'id_proceso_wf' : convertido.t_tramites_po[i].id_proceso_wf,
                    },
                    success : this.successExport,
                    failure : this.conexionFailure,
                    timeout : this.timeout,
                    scope : this
                  });
          }
          /***************************************************************/
        }

    })
</script>
