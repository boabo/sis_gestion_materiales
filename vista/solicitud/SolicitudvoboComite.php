<?php
/**
 *@package pXP
 *@file SolicitudvoboComite.php
 *@author  MAM
 *@date 11-08-2017
 *@Interface para el inicio de solicitudes de materiales
 */
header("content-type: text/javascript; charset=UTF-8");
?>

<script>

    Phx.vista.SolicitudvoboComite = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'SolicitudvoboComite',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleComite.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleComite'
            }
        ], 
        constructor: function (config) {
            this.Atributos.unshift({
                config: {
                    name: 'id_proveedor',
                    fieldLabel: 'Proveedor Adjudicado',
                    anchor: '80%',
                    tinit: false,
                    allowBlank: false,
                    origen: 'PROVEEDOR',
                    gdisplayField: 'desc_proveedor',
                    anchor: '100%',
                    gwidth: 280,
                    listWidth: '280',
                    resizable: true,
                    renderer: function(value, p, record) {
                        return String.format('<div ext:qtip="Optimo"><b><font color="green">{0}</font></b><br></div>', value);
                    }
                },
                type: 'ComboRec',
                filters:{pfiltro:'pro.desc_proveedor',type:'string'},
                id_grupo:2,
                grid: true,
                form: false
            });
            this.Atributos[this.getIndAtributo('nombre_estado_firma')].grid=true;
            this.Atributos[this.getIndAtributo('origen_pedido')].grid=true;
            Phx.vista.SolicitudvoboComite.superclass.constructor.call(this, config);
            this.store.baseParams = {tipo_interfaz: this.nombreVista};
            this.store.baseParams.pes_estado = ' ';
            this.load({params: {start: 0, limit: this.tam_pag}});
            this.finCons = true;
            this.getBoton('new').setVisible(false);
            this.getBoton('del').setVisible(false);
            this.getBoton('edit').setVisible(false);
            //this.getBoton('ant_estado').setVisible(false);
            this.getBoton('Report').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
            this.getBoton('clonar_solicitud').setVisible(false);
            this.getBoton('autorizar').setVisible(true);
            this.getBoton('sig_estado').setVisible(false);
            this.getBoton('ini_estado').setVisible(true);
            //this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);
        },
        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'g_comite',
            fieldLabel: 'Gestion',
            allowBlank: true,
            emptyText:'Gestion...',
            blankText: 'Año',
            store:new Ext.data.JsonStore(
                {
                    url: '../../sis_parametros/control/Gestion/listarGestion',
                    id: 'id_gestion',
                    root: 'datos',
                    sortInfo:{
                        field: 'gestion',
                        direction: 'DESC'
                    },
                    totalProperty: 'total',
                    fields: ['id_gestion','gestion'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams:{par_filtro:'gestion'}
                }),
            valueField: 'id_gestion',
            triggerAction: 'all',
            displayField: 'gestion',
            hiddenName: 'id_gestion',
            mode:'remote',
            pageSize:50,
            queryDelay:500,
            listWidth:'280',
            hidden:false,
            width:80
        }),
        enableTabDetalle:function(){
            if(this.TabPanelSouth.get(0)){
                this.TabPanelSouth.get(0).enable();
                this.TabPanelSouth.setActiveTab(0);
            }
        },
        disableTabDetalle:function(){
            if(this.TabPanelSouth.get(0)){
                this.TabPanelSouth.setActiveTab(0);
            }
        },
        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.SolicitudvoboComite.superclass.preparaMenu.call(this,n);
            /*Aumentando la condicion data['estado'] ==  'vb_rpcd' || data['estado'] ==  'vb_dpto_administrativo' (Ismael Valdivia 19/02/2020)*/
            if( data['estado'] ==  'vb_rpcd' || data['estado'] ==  'vb_dpto_administrativo' || data['estado'] ==  'comite_unidad_abastecimientos' || data['estado'] ==  'comite_dpto_abastecimientos' || data['estado'] ==  'comite_aeronavegabilidad'|| data['estado'] ==  'departamento_ceac'){
                //this.getBoton('sig_estado').enable();
                this.getBoton('autorizar').enable();
                this.getBoton('ini_estado').enable();
                this.getBoton('ant_estado').enable();
                this. enableTabDetalle();
            }else if (data['estado_firma']=='comite_aeronavegabilidad') {
                //this.getBoton('sig_estado').enable();
                this.getBoton('autorizar').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('ini_estado').enable();
                this. enableTabDetalle();
            }
            return tb;
        },

        liberaMenu:function(){
            var tb = Phx.vista.SolicitudvoboComite.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
                this.getBoton('ini_estado').disable();


            }
            return tb;
        },

        sigEstado:function () {
            var rec = this.sm.getSelected();
            var tramite = rec.data['nro_tramite'];
            var tipo_tramite = tramite.substring(0,2);
            //this.onSaveWizard(rec);
            if (rec.data.origen_solicitud == 'control_mantenimiento'/*|| tipo_tramite == 'GO' || tipo_tramite == 'GA'*/)  {
              this.onCambiarEstadoProcesoServicio(rec);
            } else {
              this.onSaveWizard(rec);
            }

            /***********************COMENTANDO ESTA PARTE PARA QUE PASE DIRECTAMENTE EL ESTADO**************************/
            // this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
            //     'Estado de Wf',
            //     {
            //         modal: true,
            //         width: 700,
            //         height: 450
            //     },
            //     {
            //         data: {
            //             id_estado_wf: rec.data.id_estado_wf,
            //             id_proceso_wf: rec.data.id_proceso_wf,
            //             /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
            //             id_solicitud: rec.data.id_solicitud,
            //             }
            //     }, this.idContenedor, 'FormEstadoWf',
            //
            //     {
            //         config: [{
            //             event: 'beforesave',
            //             delegate: this.onSaveWizard
            //         }],
            //         scope: this
            //     }
            // );
        },
        onSaveWizard:function(rec){
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/siguienteEstadoSolicitud',
                params:{

                    id_proceso_wf_act:  rec.data.id_proceso_wf,
                    id_estado_wf_act:   rec.data.id_estado_wf,
                    id_tipo_estado:     '',//resp.id_tipo_estado,
                    id_funcionario_wf:  '',//resp.id_funcionario_wf,
                    id_depto_wf:        '',//resp.id_depto_wf,
                    obs:                '',//resp.obs,
                    json_procesos:      '',//Ext.util.JSON.encode(resp.procesos),
                    /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
                    id_solicitud:       rec.data.id_solicitud,
                },
                success:this.successWizard,
                failure: this.conexionFailure,
                argument:{rec:rec},
                timeout:this.timeout,
                scope:this
            });

        },
        successWizard:function(){
            Phx.CP.loadingHide();
            //resp.argument.wizard.panel.destroy();
            //this.onCambiarEstadoProcesoServicio();
            this.reload();
        },

        onCambiarEstadoProcesoServicio:function(rec){
          Phx.CP.loadingShow();
          Ext.Ajax.request({
              url:'../../sis_gestion_materiales/control/Solicitud/siguienteEstadoSolicitudServicio',
              params:{cambio_estado:'cambiar_estado',
                      nro_tramite:rec.data.nro_tramite},
              success: function(resp){
                  var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                  if (reg) {
                    this.onSaveWizard(rec);
                  }
              },
              failure: this.conexionFailure,
              timeout:this.timeout,
              scope:this
          });
            // var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            // Phx.CP.loadingShow();
            // Ext.Ajax.request({
            //     //url:'../../sis_gestion_materiales/control/Solicitud/siguienteDisparo',
            //     url:'../../sis_gestion_materiales/control/Solicitud/siguienteEstadoSolicitudServicio',
            //     params:{
            //
            //         id_proceso_wf_act:  resp.id_proceso_wf_act,
            //         id_estado_wf_act:   resp.id_estado_wf_act,
            //         id_tipo_estado:     resp.id_tipo_estado,
            //         id_funcionario_wf:  resp.id_funcionario_wf,
            //         id_depto_wf:        resp.id_depto_wf,
            //         obs:                resp.obs,
            //         json_procesos:      Ext.util.JSON.encode(resp.procesos),
            //         /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
            //         id_solicitud:       wizard.data.id_solicitud,
            //         nro_tramite:        wizard.data.nro_tramite
            //         /********************************************************************************************/
            //     },
            //     success:this.successWizard,
            //     failure: this.conexionFailure,
            //     argument:{wizard:wizard},
            //     timeout:this.timeout,
            //     scope:this
            // });

        },

        antEstado:function(res){

            var rec=this.sm.getSelected();
            Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
                'Estado de Wf',
                {
                    modal:true,
                    width:450,
                    height:250
                }, { data:rec.data, estado_destino: res.argument.estado}, this.idContenedor,'AntFormEstadoWf',
                {
                    config:[{
                        event:'beforesave',
                        delegate: this.onAntEstado
                    }
                    ],
                    scope:this
                })
        },

        onAntEstado: function(wizard,resp){
            Phx.CP.loadingShow();
            var rec = this.sm.getSelected();
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/anteriorEstadoSolicitud',
                params:{
                    id_proceso_wf: rec.data.id_proceso_wf,
                    id_estado_wf:  rec.data.id_estado_wf,
                    obs: resp.obs,
                    estado_destino: resp.estado_destino
                },
                argument:{wizard:wizard},
                success:this.successEstadoSinc,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },
        successEstadoSinc:function(resp){
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
        },
        /**************************************************************************************************/
    }

</script>
