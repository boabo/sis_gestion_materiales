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
    Phx.vista.PedidosOperacion = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'PedidoOperacion',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleNoEdit.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleNoEdit'
            }
        ],


        constructor: function (config) {
            this.Atributos[this.getIndAtributo('nombre_estado_firma')].grid=false;
            this.Atributos[this.getIndAtributo('tipo_falla')].grid=false;
            this.Atributos[this.getIndAtributo('tipo_reporte')].grid=false;
            this.Atributos[this.getIndAtributo('mel')].grid=false;
            this.Atributos[this.getIndAtributo('nro_po')].grid=true;
            this.Atributos[this.getIndAtributo('id_proveedor')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_cotizacion')].grid=true;

            this.Grupos.push( {
                layout: 'column',
                border: false,
                defaults: {
                    border: false
                },

                items: [

                     {
                     bodyStyle: 'padding-right:10px;',
                     items: [
                     {
                     xtype: 'fieldset',
                     title: ' Datos Adquisiciones ',
                     autoHeight: true,
                     items: [],
                     id_grupo: 2
                     }


                     ]
                     }
                ]
            });


            Phx.vista.PedidosOperacion.superclass.constructor.call(this, config);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'pedido_op_pendiente';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Report').setVisible(false);
            this.getBoton('ant_estado').setVisible(true);

        },

        gruposBarraTareas:[
            {name:'pedido_op_pendiente',title:'<H1 align="center"><i class="fa fa-folder-open"></i> Pendientes</h1>',grupo:3,height:0},
            {name:'pedido_op_solicitada',title:'<H1 align="center"><i class="fa fa-file"></i> Solicitadas</h1>',grupo:3,height:0},
            {name:'pedido_op_sin_resp',title:'<H1 align="center"><i class="fa fa-minus-circle"></i> Sin Respuestas</h1>',grupo:3,height:0},
            {name:'pedido_op_compra',title:'<H1 align="center"><i class="fa fa-money"></i> Compra</h1>',grupo:3,height:0},
            {name:'pedido_op_concluido',title:'<H1 align="center"><i class="fa fa-folder"></i> Concluido</h1>',grupo:3,height:0}
        ],

        actualizarSegunTab: function(name, indice){

            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        tam_pag:50,
        beditGroups: [2,3],
        bdelGroups:  [0],
        bactGroups:  [0,1,2,3],
        btestGroups: [0],
        bexcelGroups: [0,1,2,3],
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
            if( rec.data.estado=='cotizacion_solicitada' && rec.data.nro_po=='' &&  rec.data.id_proveedor == null || rec.data.estado=='despachado' && rec.data.fecha_despacho_miami == null  || rec.data.estado=='despachado' && rec.data.fecha_arribado_bolivia == null || rec.data.estado=='arribo' && rec.data.fecha_desaduanizacion == null || rec.data.estado=='desaduanizado' && rec.data.fecha_en_almacen == null){
             this.onButtonEdit();
             }
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
        },

        successWizard:function(resp){
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
        },
        onButtonEdit: function() {
        var data = this.getSelectedData();
        Phx.vista.PedidosOperacion.superclass.onButtonEdit.call(this);
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

            if(data['estado'] ==  'cotizacion_solicitada' || data['estado'] ==  'despachado' || data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado' )  {
                this.mostrarComponente(this.Cmp.fecha_cotizacion);
                this.mostrarComponente(this.Cmp.id_proveedor);
                this.mostrarComponente(this.Cmp.nro_po);

            }if(data['estado'] ==  'despachado'||data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado') {

                this.mostrarComponente(this.Cmp.fecha_arribado_bolivia);
            }if(data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado') {
                this.mostrarComponente(this.Cmp.fecha_desaduanizacion);
            } if(data['estado'] ==  'desaduanizado') {
                this.mostrarComponente(this.Cmp.fecha_en_almacen)
            }

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

            if(data['estado'] ==  'cotizacion_solicitada' || data['estado'] ==  'despachado' || data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado' )  {
                this.mostrarComponente(this.Cmp.fecha_cotizacion);
                this.mostrarComponente(this.Cmp.id_proveedor);
                this.mostrarComponente(this.Cmp.nro_po);

            }if(data['estado'] ==  'despachado'||data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado') {

                this.mostrarComponente(this.Cmp.fecha_arribado_bolivia);
            }if(data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado') {
                this.mostrarComponente(this.Cmp.fecha_desaduanizacion);
            } if(data['estado'] ==  'desaduanizado') {
                this.mostrarComponente(this.Cmp.fecha_en_almacen)
            }

        }    if(this.Cmp.origen_pedido.getValue() == 'Almacenes Consumibles o Rotables'){
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

            if(data['estado'] ==  'cotizacion_solicitada' || data['estado'] ==  'despachado' || data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado' )  {
                this.mostrarComponente(this.Cmp.fecha_cotizacion);
                this.mostrarComponente(this.Cmp.id_proveedor);
                this.mostrarComponente(this.Cmp.nro_po);

            }if(data['estado'] ==  'despachado'||data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado') {

                this.mostrarComponente(this.Cmp.fecha_arribado_bolivia);
            }if(data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado') {
                this.mostrarComponente(this.Cmp.fecha_desaduanizacion);
            } if(data['estado'] ==  'desaduanizado') {
                this.mostrarComponente(this.Cmp.fecha_en_almacen)
            }

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
            Phx.vista.PedidosOperacion.superclass.preparaMenu.call(this,n);

            if( data['estado'] ==  'cotizacion'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ini_estado').enable();
                this.getBoton('ant_estado').enable();
                this. enableTabDetalle();


            }else if( data['estado'] !=  'finalizado'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('ini_estado').enable();

                this.disableTabDetalle();
            }
            else {
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
                this.getBoton('ini_estado').disable();
                this.disableTabDetalle();
            }

            return tb;
        },

        liberaMenu:function(){
            var tb = Phx.vista.PedidosOperacion.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                this.getBoton('sig_estado').disable();
                this.getBoton('edit').setVisible(true);
                this.getBoton('Report').setVisible(false);

            }
            return tb;
        },
        bnew: false,
        fwidth: '65%',
        fheight: '75%'

    }

</script>