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
    Phx.vista.RegistroSolicitud = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        fwidth: '60%',
        fheight: '50%',

        title: 'Solicitud',
        nombreVista: 'RegistroSolicitud',
        constructor: function (config) {
            this.maestro = config.maestro;
            Phx.vista.RegistroSolicitud.superclass.constructor.call(this, config);
            this.store.baseParams = {tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'borrador';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.getBoton('ant_estado').setVisible(false);
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
            this.getBoton('Consulta_desaduanizacion').setVisible(false);
            this.getBoton('Control_aLmacene').setVisible(false);

        },
        gruposBarraTareas:[
            {name:'borrador',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Borrador</h1>',grupo:0,height:0},
            {name:'vobo_area',title:'<H1 "center"><i class="fa fa-eye"></i>Visto Bueno</h1>',grupo:1,height:0},
            {name:'revision',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Proceso</h1>',grupo:1,height:0},
            {name:'finalizado',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Finalizado</h1>',grupo:1,height:0}
        ],

        tam_pag:50,
        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },

        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0,1,2],
        btestGroups: [0],
        bexcelGroups: [0,1,2],

        onButtonNew:function(){
            //abrir formulario de solicitud
            var me = this;
            me.objSolForm = Phx.CP.loadWindows('../../../sis_gestion_materiales/vista/solicitud/FromFormula.php',
                'Formulario Requerimiento de Materiales',
                {
                    modal:true,
                    width:'78%',
                    height:'90%'
                }, {data:{objPadre: me}
                },
                this.idContenedor,
                'FromFormula',
                {
                    config:[{
                        event:'successsave',
                        delegate: this.onSaveForm,

                    }],

                    scope:this
                });

        },

        onSaveForm:function(resp){
            Phx.CP.loadingShow();
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
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
                        id_estado_wf: rec.data.id_estado_wf,
                        id_proceso_wf: rec.data.id_proceso_wf
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
            console.log('Datos: '+JSON.stringify(resp));

            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/siguienteEstadoSolicitud',
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
            var rec = this.sm.getSelected();
            if((rec.data.origen_pedido == 'Gerencia de Operaciones')||(rec.data.origen_pedido == 'Gerencia de Mantenimiento'))  {
                Phx.CP.loadingShow();
                Ext.Ajax.request({
                    url: '../../sis_gestion_materiales/control/Solicitud/iniciarDisparo',
                    params: {
                        id_solicitud: rec.data.id_solicitud
                    },
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                });
            }
            },

        successWizard:function(resp){
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
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
        onButtonEdit: function() {
            var data = this.getSelectedData();
            Phx.vista.Solicitud.superclass.onButtonEdit.call(this);
            if(this.Cmp.origen_pedido.getValue() == 'Gerencia de Operaciones'){
                this.ocultarComponente(this.Cmp.mel);
                this.ocultarComponente(this.Cmp.tipo_reporte);
                this.ocultarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.ocultarComponente(this.Cmp.id_proveedor);
                this.ocultarComponente(this.Cmp.nro_po);

            }  if(this.Cmp.origen_pedido.getValue() == 'Gerencia de Mantenimiento'){
                this.mostrarComponente(this.Cmp.mel);
                this.mostrarComponente(this.Cmp.tipo_reporte);
                this.mostrarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.ocultarComponente(this.Cmp.id_proveedor);
                this.ocultarComponente(this.Cmp.nro_po);

            }  if(this.Cmp.origen_pedido.getValue() == 'Almacenes Consumibles o Rotables'){
                this.ocultarComponente(this.Cmp.mel);
                this.ocultarComponente(this.Cmp.tipo_reporte);
                this.ocultarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.justificacion);
                this.ocultarComponente(this.Cmp.id_matricula);
                this.ocultarComponente(this.Cmp.nro_justificacion);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.ocultarComponente(this.Cmp.id_proveedor);
                this.ocultarComponente(this.Cmp.nro_po);

            }



        },

        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.RegistroSolicitud.superclass.preparaMenu.call(this,n);

            if(data['estado'] ==  'borrador'){
                this.getBoton('sig_estado').enable();
                this. enableTabDetalle();


            }else if(data['estado'] !=  'vobo_area') {
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();


                this.disableTabDetalle();

            } else {
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
                this.disableTabDetalle();

            }

            return tb;
        },
        liberaMenu:function(){
            var tb = Phx.vista.RegistroSolicitud.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').setVisible(false);
                this.getBoton('ini_estado').setVisible(false);
            }
            return tb;
        }
    };
</script>


