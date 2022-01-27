<?php
/**
 * @package pXP
 * @file gen-DetalleFacturacion.php
 * @author  (Ismael Valdivia)
 * @date 01-12-2020 08:30:00
 * @description Archivo con la interfaz de usuario que permite generar el reporte de las facturas
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<style>
#ventanaEmergente:hover {
  background-color: #91FF81;
  font-size: 20px;
}
</style>

<script>

    Phx.vista.DetalleRpc = Ext.extend(Phx.gridInterfaz, {
        title: 'Mayor',

        constructor: function (config) {
            var me = this;
            this.maestro = config.maestro;


            this.Atributos = [
              {
                  config: {
                      name: 'fecha_autorizacion_rpc',
                      fieldLabel: 'Fecha aprobación RPC',
                      allowBlank: true,
                      gwidth: 180,
                      maxLength: 1000,
                      renderer: function(value,p,record){
                              return String.format('{0}','<b id="ventanaEmergente"><i class="fa fa-share" aria-hidden="true"></i> </b>&nbsp;&nbsp;&nbsp;'+record.data['fecha_autorizacion_rpc']);
                      },

                  },
                  type: 'TextField',
                  filters: {pfiltro: 'nombre', type: 'string'},
                  //bottom_filter: true,
                  id_grupo: 1,
                  grid: true,
                  form: true
              },


              {
                  config: {
                      name: 'nro_tramite',
                      fieldLabel: 'Nro. de Trámite',
                      allowBlank: true,
                      gwidth: 250,
                      maxLength: 1000,

                  },
                  type: 'TextField',
                  filters: {pfiltro: 's.nro_tramite', type: 'string'},
                  bottom_filter: true,
                  id_grupo: 1,
                  grid: true,
                  form: true
              },


                {
                    config: {
                        name: 'encargado_rpc',
                        fieldLabel: 'Encargado RPC',
                        allowBlank: true,
                        gwidth: 430,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: false,
                    form: true
                },


                // {
                //     config: {
                //         name: 'origen_pedido',
                //         fieldLabel: 'Tipo Pedido',
                //         allowBlank: true,
                //         gwidth: 200,
                //         maxLength: 1000,
                //
                //     },
                //     type: 'TextField',
                //     filters: {pfiltro: 'origen_pedido', type: 'string'},
                //     //bottom_filter: true,
                //     id_grupo: 1,
                //     grid: true,
                //     form: true
                // },


                {
                    config: {
                        name: 'estado',
                        fieldLabel: 'Estado Actual',
                        allowBlank: true,
                        gwidth: 150,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'fecha_solicitud',
                        fieldLabel: 'Fecha de Solicitud',
                        allowBlank: true,
                        gwidth: 180,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'proveedor',
                        fieldLabel: 'Proveedor Adjudicado',
                        allowBlank: true,
                        gwidth: 200,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'precio_unitario_mb',
                        fieldLabel: 'Monto a Pagar Adjudicado',
                        allowBlank: true,
                        gwidth: 200,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'moneda',
                        fieldLabel: 'Moneda',
                        allowBlank: true,
                        gwidth: 200,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'funciaonario',
                        fieldLabel: 'Técnico Solicitante',
                        allowBlank: true,
                        gwidth: 300,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'funcionario_solicitante',
                        fieldLabel: 'Funcionario Solicitante',
                        allowBlank: true,
                        gwidth: 300,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'aux_abas',
                        fieldLabel: 'Auxiliar Abastecimiento',
                        allowBlank: true,
                        gwidth: 300,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'tipo_solicitud',
                        fieldLabel: 'Tipo Solicitud',
                        allowBlank: true,
                        gwidth: 170,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'nro_po',
                        fieldLabel: 'Nro. PO',
                        allowBlank: true,
                        gwidth: 170,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'motivo_solicitud',
                        fieldLabel: 'Motivo de Solicitud',
                        allowBlank: true,
                        gwidth: 430,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'observaciones_sol',
                        fieldLabel: 'Observaciones',
                        allowBlank: true,
                        gwidth: 430,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'remark',
                        fieldLabel: 'Remark (Alkym)',
                        allowBlank: true,
                        gwidth: 430,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'justificacion',
                        fieldLabel: 'Justificacion',
                        allowBlank: true,
                        gwidth: 430,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'tipo_falla',
                        fieldLabel: 'Tipo Falla',
                        allowBlank: true,
                        gwidth: 430,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },

                {
                    config: {
                        name: 'mel',
                        fieldLabel: 'MEL',
                        allowBlank: true,
                        gwidth: 430,
                        maxLength: 1000,

                    },
                    type: 'TextField',
                    filters: {pfiltro: 'nombre', type: 'string'},
                    //bottom_filter: true,
                    id_grupo: 1,
                    grid: true,
                    form: true
                },







            ];


            //llama al constructor de la clase padre
            Phx.vista.DetalleRpc.superclass.constructor.call(this, config);

            /********************Aumentando boton para sacar reporte libro mayor*******************************/
            // this.addButton('btnImprimirReporteExcel', {
            //   text: '<center>Generar Reporte Excel</center>',
            //   iconCls: 'bexcel',
            //   disabled: false,
            //   handler: this.ReporteEXCEL,
            //   tooltip: '<b>Generar Reporte Excel'
            // });

            this.addButton('btnChequeoDocumentosWf',{
                text: 'Documentos',
                grupo: [0,1,2,3,4,5,6,7],
                iconCls: 'bchecklist',
                disabled: true,
                handler: this.loadCheckDocumentosRecWf,
                tooltip: '<b>Documentos </b><br/>Subir los documetos requeridos.'
            });
            /***********************************************************************************************/


            this.grid.getTopToolbar().disable();
            this.grid.getBottomToolbar().disable();
            this.init();

        		this.grid.body.dom.firstChild.firstChild.lastChild.style.background='#F1F7FF';
        		this.grid.body.dom.firstChild.firstChild.firstChild.firstChild.style.background='#dfe8f6';
            this.iniciarEventos();

            this.bbar.el.dom.style.background='#6EC8E3';
          	this.tbar.el.dom.style.background='#6EC8E3';
          	this.grid.body.dom.firstChild.firstChild.lastChild.style.background='#FEFFF4';
          	this.grid.body.dom.firstChild.firstChild.firstChild.firstChild.style.background='#FFF4EB';

            this.grid.on('cellclick', this.abrirDetalle, this);

            this.load({params: {start: 0, limit: this.tam_pag}});

        },


        tam_pag: 50,

        ActList: '../../sis_gestion_materiales/control/Solicitud/ControlGridRpc',
        id_store: 'id_solicitud',
        fields: [
            {name: 'origen_pedido', type: 'varchar'},
            {name: 'nro_tramite', type: 'varchar'},
            {name: 'estado', type: 'varchar'},
            {name: 'funciaonario', type: 'varchar'},
            {name: 'fecha_solicitud', type: 'varchar'},
            {name: 'motivo_solicitud', type: 'varchar'},
            {name: 'observaciones_sol', type: 'varchar'},
            {name: 'remark', type: 'varchar'},
            {name: 'justificacion', type: 'varchar'},
            {name: 'nro_justificacion', type: 'varchar'},
            {name: 'tipo_solicitud', type: 'varchar'},
            {name: 'tipo_falla', type: 'varchar'},
            {name: 'tipo_reporte', type: 'varchar'},
            {name: 'mel', type: 'varchar'},
            {name: 'nro_no_rutina', type: 'varchar'},
            {name: 'nro_cotizacion', type: 'varchar'},
            {name: 'proveedor', type: 'varchar'},
            {name: 'nro_po', type: 'varchar'},
            {name: 'aux_abas', type: 'varchar'},
            {name: 'fecha_autorizacion_rpc', type: 'varchar'},
            {name: 'encargado_rpc', type: 'varchar'},
            {name: 'precio_unitario_mb', type: 'numeric'},
            {name: 'id_proceso_wf', type: 'numeric'},
            {name: 'moneda', type: 'varchar'},
            {name: 'funcionario_solicitante', type: 'varchar'},
            {name: 'id_solicitud', type: 'numeric'},

        ],

        sortInfo: {
            //field: 'nro_tramite',
            direction: 'ASC'
        },
        bdel: true,
        bsave: false,
        bgantt:true,
        loadValoresIniciales: function () {
            Phx.vista.DetalleRpc.superclass.loadValoresIniciales.call(this);
            //this.getComponente('id_int_comprobante').setValue(this.maestro.id_int_comprobante);
        },

        onReloadPage: function (param) {
            //Se obtiene la gestión en función de la fecha del comprobante para filtrar partidas, cuentas, etc.

            var me = this;
            this.initFiltro(param);
        },

        initFiltro: function (param) {
            this.store.baseParams = param;
            this.load({params: {start: 0, limit: this.tam_pag}});
        },

        loadCheckDocumentosRecWf:function() {
            var rec=this.sm.getSelected();
            var that=this;
            rec.data.nombreVista = this.nombreVista;
            Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
                'Chequear documento del WF',
                {
                    width:'90%',
                    height:500,
                    onDestroy: function() {
                        this.fireEvent('closepanel',this);

                  			if (this.window) {
                  					this.window.destroy();
                  			}
                  			if (this.form) {
                  					this.form.destroy();
                  			}
                  			Phx.CP.destroyPage(this.idContenedor);
                        that.reload();


                  	},
                },
                rec.data,
                this.idContenedor,
                'DocumentoWf'
            )
        },


        postReloadPage: function (data) {
            ini = data.desde;
            fin = data.hasta;
        },

        abrirDetalle: function(cell,rowIndex,columnIndex,e){
          if(columnIndex==1){
            var data = this.sm.getSelected().data;
            Phx.CP.loadWindows('../../../sis_gestion_materiales/vista/consulta_rpc/DetalleSolicitud.php',
            '<span style="font-size:14pt;padding-left: 35%;letter-spacing: 12px;">DETALLE SOLICITUD</span>', {
              width:'90%',
              height:'90%'
              }, {
                id_solicitud: data.id_solicitud,
                link: true
              },
              this.idContenedor,
              'DetalleSolicitud'
            );
          }
        },

        bnew: false,
        bedit: false,
        bdel: false,
        bexcel:true,
      	btest:false
    })
</script>
