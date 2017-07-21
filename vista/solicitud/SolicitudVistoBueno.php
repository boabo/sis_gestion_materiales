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

            this.Atributos[this.getIndAtributo('nombre_estado')].grid=false;
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
            this.getBoton('Consulta_desaduanizacion').setVisible(false);
            this.getBoton('Control_aLmacene').setVisible(false);
            this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);
        },


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

        sigEstado: function(){
            var rec = this.sm.getSelected();
                this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
                    'Estado de Wf',
                    {
                        modal: true,
                        width: 700,
                        height: 450
                    },
                    {
                        data: {
                            id_estado_wf: rec.data.id_estado_wf_firma,
                            id_proceso_wf: rec.data.id_proceso_wf_firma

                        }
                    }, this.idContenedor, 'FormEstadoWf',
                    {
                        config: [{
                            event: 'beforesave',
                            delegate: this.onSaveWizard
                        }],
                        scope: this
                    }
                );


        },
        onSaveWizard:function(wizard,resp){
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/siguienteDisparo',
                params:{

                    id_proceso_wf_act:  resp.id_proceso_wf_act,
                    id_estado_wf_act:   resp.id_estado_wf_act,
                    id_tipo_estado:     resp.id_tipo_estado,
                    id_funcionario_wf:  resp.id_funcionario_wf,
                    id_depto_wf:        resp.id_depto_wf,
                    obs:                resp.obs,
                    json_procesos:      Ext.util.JSON.encode(resp.procesos)
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
                url:'../../sis_gestion_materiales/control/Solicitud/anteriorDisparo',
                params:{
                    id_proceso_wf_firma: rec.data.id_proceso_wf_firma,
                    id_estado_wf_firma:  rec.data.id_estado_wf_firma,
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
                url:'../../sis_gestion_materiales/control/Solicitud/inicioEstadoSolicitudDisparo',
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