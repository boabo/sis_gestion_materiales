<?php
/**
 *@package pXP
 *@file RegistroSolicitud.php
 *@author  MAM
 *@date 27-12-2016 14:45
 *@Interface para el inicio de solicitudes de materiales
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.SolicitudVistoBueno = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'VistoBueno',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleNoEdit.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleNoEdit'
            }
        ],
        constructor: function (config) {
 
            //this.Atributos[this.getIndAtributo('nombre_estado')].grid=false;
            this.Atributos[this.getIndAtributo('tipo_evaluacion')].grid=false;
            this.Atributos[this.getIndAtributo('taller_asignado')].grid=false;
            this.Atributos[this.getIndAtributo('observacion_nota')].grid=false;
            this.Atributos[this.getIndAtributo('lugar_entrega')].grid=false;
            this.Atributos[this.getIndAtributo('condicion')].grid=false;

            Phx.vista.SolicitudVistoBueno.superclass.constructor.call(this, config);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'visto_bueno';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.getBoton('Report').setVisible(false);
            this.getBoton('edit').setVisible(false);
            this.getBoton('ini_estado').setVisible(true);
            this.getBoton('ant_estado').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
            this.getBoton('clonar_solicitud').setVisible(false);
           // this.getBoton('Consulta_desaduanizacion').setVisible(false);
            //.getBoton('Control_aLmacene').setVisible(false);
            //this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);
        },

        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'g_mantemiento',
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

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        enableTabDetalle:function(){
            if(this.TabPanelSouth.get(0)){
                this.TabPanelSouth.get(0).enable();
                this.TabPanelSouth.setActiveTab(0);
            }
        },
        disableTabDetalle:function(){
            if(this.TabPanelSouth.get(0)){
                //this.TabPanelSouth.get(0).disable();
                this.TabPanelSouth.setActiveTab(0);
                //this.TabPanelSouth.bdel.getVisible(false);
            }
        },
        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.SolicitudVistoBueno.superclass.preparaMenu.call(this,n);

            if(data['estado_firma'] ==  'vobo_area'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('ini_estado').enable();
                this. enableTabDetalle();


            }else if(data['estado_firma'] !=  'revision'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('ini_estado').enable();
                this.disableTabDetalle();
            }
            else {
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').enable();
                this.getBoton('ini_estado').enable();
                this.disableTabDetalle();
            }
            return tb;
        },
        liberaMenu:function(){
            var tb = Phx.vista.SolicitudVistoBueno.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                this.getBoton('edit').setVisible(false);
                this.getBoton('Report').setVisible(false);
                this.getBoton('ini_estado').setVisible(true);
               // this.getBoton('del').disable();
            }
            return tb;
        },
        /*Comentando esta parte para que directamente cambiemos el estado de las Solicitudes
        vb_rpcd y vb_dpto_abastecimientos Ismael Valdivia (03/02/2020)*/
        //Comentando esta parte para pasar los estados siguientes de vobo vb_dpto_administrativo y vobo_rpcd
        sigEstado: function(){
            var rec = this.sm.getSelected();
            var tramite = rec.data['nro_tramite'];
            var tipo_tramite = tramite.substring(0,2);

             /*Aumentamos condiciones para que mande cambiar estado de firmas o estado del proceso*/
             if (rec.data.estado == 'vb_rpcd' && rec.data.estado == 'control_mantenimiento') {
                       this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
                        'Estado de Wf',
                        {
                            modal: true,
                            width: 700,
                            height: 450
                        },
                        {
                            data: {
                                id_estado_wf: rec.data.id_estado_wf,
                                id_proceso_wf: rec.data.id_proceso_wf,
                                /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
                                id_solicitud: rec.data.id_solicitud,
                                nro_tramite:rec.data.nro_tramite
                                /********************************************************************************************/
                                }
                        }, this.idContenedor, 'FormEstadoWf',

                        {
                            config: [{
                                event: 'beforesave',
                                delegate: this.onCambiarEstadoProcesoServicio
                                //delegate: this.onCambiarEstadoProceso
                            }],
                            scope: this
                        }
                    );

                  }else {
                    this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
                     'Estado de Wf',
                     {
                         modal: true,
                         width: 700,
                         height: 450
                     },
                     {
                         data: {
                             id_estado_wf: rec.data.id_estado_wf,
                             id_proceso_wf: rec.data.id_proceso_wf,
                             /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
                             id_solicitud: rec.data.id_solicitud,
                             /********************************************************************************************/
                             }
                     }, this.idContenedor, 'FormEstadoWf',

                     {
                         config: [{
                             event: 'beforesave',
                             delegate: this.onCambiarEstadoProceso
                         }],
                         scope: this
                     }
                 );
            }

        },
        // sigEstado:function () {
        //     var rec = this.sm.getSelected();
        //     this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
        //         'Estado de Wf',
        //         {
        //             modal: true,
        //             width: 700,
        //             height: 450
        //         },
        //         {
        //             data: {
        //                 id_estado_wf: rec.data.id_estado_wf,
        //                 id_proceso_wf: rec.data.id_proceso_wf,
        //                 /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
        //                 id_solicitud: rec.data.id_solicitud,
        //                 /********************************************************************************************/
        //                 }
        //         }, this.idContenedor, 'FormEstadoWf',
        //
        //         {
        //             config: [{
        //                 event: 'beforesave',
        //                 delegate: this.onSaveWizard
        //             }],
        //             scope: this
        //         }
        //     );
        // },
        onCambiarEstadoProceso:function(wizard,resp){
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                //url:'../../sis_gestion_materiales/control/Solicitud/siguienteDisparo',
                url:'../../sis_gestion_materiales/control/Solicitud/siguienteEstadoSolicitud',
                params:{

                    id_proceso_wf_act:  resp.id_proceso_wf_act,
                    id_estado_wf_act:   resp.id_estado_wf_act,
                    id_tipo_estado:     resp.id_tipo_estado,
                    id_funcionario_wf:  resp.id_funcionario_wf,
                    id_depto_wf:        resp.id_depto_wf,
                    obs:                resp.obs,
                    json_procesos:      Ext.util.JSON.encode(resp.procesos),
                    /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
                    id_solicitud:       wizard.data.id_solicitud,
                    /********************************************************************************************/
                },
                success:this.successWizard,
                failure: this.conexionFailure,
                argument:{wizard:wizard},
                timeout:this.timeout,
                scope:this
            });

        },

        onCambiarEstadoProcesoServicio:function(wizard,resp){
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                //url:'../../sis_gestion_materiales/control/Solicitud/siguienteDisparo',
                url:'../../sis_gestion_materiales/control/Solicitud/siguienteEstadoSolicitudServicio',
                params:{

                    id_proceso_wf_act:  resp.id_proceso_wf_act,
                    id_estado_wf_act:   resp.id_estado_wf_act,
                    id_tipo_estado:     resp.id_tipo_estado,
                    id_funcionario_wf:  resp.id_funcionario_wf,
                    id_depto_wf:        resp.id_depto_wf,
                    obs:                resp.obs,
                    json_procesos:      Ext.util.JSON.encode(resp.procesos),
                    /*Aumentando este campo para controlar que se tenga adjudicados (Ismael Valdivia 19/02/2020)*/
                    id_solicitud:       wizard.data.id_solicitud,
                    nro_tramite:        wizard.data.nro_tramite
                    /********************************************************************************************/
                },
                success:this.successWizard,
                failure: this.conexionFailure,
                argument:{wizard:wizard},
                timeout:this.timeout,
                scope:this
            });

        },

        successWizard:function(resp){
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
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
                          delegate: this.onAntEstadoProceso
                      }
                      ],
                      scope:this
                  })


        },

        onAntEstadoProceso: function(wizard,resp){
            Phx.CP.loadingShow();
            var rec = this.sm.getSelected();
            Ext.Ajax.request({
                //url:'../../sis_gestion_materiales/control/Solicitud/anteriorDisparo',
                url:'../../sis_gestion_materiales/control/Solicitud/anteriorEstadoSolicitud',
                params:{
                    //id_proceso_wf_firma: rec.data.id_proceso_wf_firma,
                    //id_estado_wf_firma:  rec.data.id_estado_wf_firma,
                     id_proceso_wf: resp.id_proceso_wf,
                     id_estado_wf:  resp.id_estado_wf,
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



        onOpenObs:function() {
            var rec=this.sm.getSelected();
                var data = {
                    id_proceso_wf: rec.data.id_proceso_wf_firma,
                    id_estado_wf: rec.data.id_estado_wf_firma,
                    num_tramite: rec.data.nro_tramite
                };
            Phx.CP.loadWindows('../../../sis_workflow/vista/obs/Obs.php',
                'Observaciones del WF',
                {
                    width:'80%',
                    height:'70%'
                },
                data,
                this.idContenedor,
                'Obs'
            )
        },
        iniEstado:function(res){
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
                        delegate: this.inAntEstado
                    }
                    ],
                    scope:this
                })
        },
        inAntEstado: function(wizard,resp){
            Phx.CP.loadingShow();
            var operacion = 'cambiar';
            operacion =  resp.estado_destino == 'inicio'?'inicio':operacion;
            Ext.Ajax.request({
              //  url:'../../sis_gestion_materiales/control/Solicitud/inicioEstadoSolicitudDisparo',
                url:'../../sis_gestion_materiales/control/Solicitud/inicioEstadoSolicitud',
                params:{
                    id_proceso_wf: resp.id_proceso_wf,
                    id_estado_wf:  resp.id_estado_wf,
                    operacion: operacion,
                    obs: resp.obs
                },
                argument:{wizard:wizard},
                success:this.succeEstadoSinc,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },
        succeEstadoSinc:function(resp){
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
        },


        bdel:false,
        bsave:false,
        bnew:false
    }

</script>
