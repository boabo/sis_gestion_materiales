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
    Phx.vista.PedidosCeac = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'PedidoDgac',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleSol.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleSol'
            }
        ],

        constructor: function (config) {

            this.font();
            this.Atributos[this.getIndAtributo('nombre_estado_firma')].grid=true;
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
                    },
                    {
                        bodyStyle: 'padding-left:10px;',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: ' Datos Comité de Evaluación ',
                                autoHeight: true,
                                items: [],
                                id_grupo: 5
                            }


                        ]
                    }
                ]

            });
            this.historico = 'no';
            this.tbarItems = ['-',{
                    text: 'Histórico',
                    enableToggle: true,
                    pressed: false,
                    toggleHandler: function(btn, pressed) {

                        if(pressed){
                            this.historico = 'si';
                            //this.desBotoneshistorico();
                        }
                        else{
                            this.historico = 'no'
                        }

                        this.store.baseParams.historico = this.historico;
                        this.reload();
                    },
                    scope: this
                }
            ];
            Phx.vista.PedidosCeac.superclass.constructor.call(this, config);
            this.store.baseParams = {tipo_interfaz: this.nombreVista};
            this.store.baseParams.pes_estado = 'pedido_dgac_pendiente';
            this.load({params: {start: 0, limit: this.tam_pag}});
            this.getBoton('Report').setVisible(false);
            this.addButton('btnpac',
                {
                    iconCls: 'bemail',
                    text: 'Generar PAC',
                    grupo:[3],
                    disabled: true,
                    handler: this.correoPac,
                    tooltip: '<b>Envia Correo PAC</b>'
                }
            );

            this.finCons = true;

        },
        gruposBarraTareas:[
            {name:'pedido_dgac_pendiente',title:'<H1 align="center"><i class="fa fa-folder-open"></i> Pendientes</h1>',grupo:3,height:0},
            {name:'pedido_dgac_solicitada',title:'<H1 align="center"><i class="fa fa-file"></i> Solicitadas</h1>',grupo:3,height:0},
            {name:'pedido_dgac_sin_resp',title:'<H1 align="center"><i class="fa fa-minus-circle"></i> Sin Respuestas</h1>',grupo:3,height:0},
            {name:'pedido_dgac_comite',title:'<H1 align="center"><i class="fa fa-minus-circle"></i> Vobo Comite</h1>',grupo:5,height:0},
            {name:'pedido_dgac_compra',title:'<H1 align="center"><i class="fa fa-money"></i> Compra</h1>',grupo:3,height:0},
            {name:'pedido_dgac_concluido',title:'<H1 align="center" ><i class="fa fa-folder" ></i> Concluido</h1>',grupo:5,height:0}
        ],

        actualizarSegunTab: function(name, indice){

            if(this.finCons){
                this.store.baseParams.pes_estado = name;

                if(name == 'pedido_dgac_solicitada'){
                    this.getBoton('btnproveedor').setVisible(true);
                    this.getBoton('Cotizacion').setVisible(true);
                    this.getBoton('btnpac').setVisible(true);
                }else if (name == 'pedido_dgac_pendiente' || name == 'pedido_dgac_compra'){
                    this.getBoton('btnproveedor').setVisible(true);
                    this.getBoton('Cotizacion').setVisible(true);
                    this.getBoton('btnpac').setVisible(false);
                } else{
                    this.getBoton('btnproveedor').setVisible(false);
                    this.getBoton('btnpac').setVisible(false);
                    this.getBoton('Cotizacion').setVisible(false);
                    this.getBoton('btnpac').setVisible(false);
                }

                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        tam_pag:50,
        beditGroups: [2,3],
        bdelGroups:  [0],
        bactGroups:  [0,1,2,3,5],
        btestGroups: [0],
        bexcelGroups: [0,1,2,3,5],
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
            Phx.vista.PedidosCeac.superclass.preparaMenu.call(this,n);

            this.getBoton('btnproveedor').enable();
            this.getBoton('Cotizacion').enable();
            this.getBoton('btnpac').enable();


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
            var tb = Phx.vista.PedidosCeac.superclass.liberaMenu.call(this);
            if(tb){


                this.getBoton('sig_estado').disable();
                this.getBoton('btnpac').disable();
                this.getBoton('btnproveedor').disable();
                this.getBoton('Archivado_concluido').enable();
                this.getBoton('Cotizacion').disable();
                this.getBoton('ini_estado').disable();
                this.getBoton('Report').setVisible(false);
            }
            return tb;
        },
        onButtonEdit: function() {
            this.iniciarEvento();
            Phx.vista.PedidosCeac.superclass.onButtonEdit.call(this);
            this.Cmp.mensaje_correo.setValue('Favor cotizar según documento Adjunto.');
            this.ocultarComponente(this.Cmp.taller_asignado);
            this.ocultarComponente(this.Cmp.observacion_nota);
            this.Cmp.tipo_evaluacion.on('select',function(combo, record, index){
                if (record.data.ID == 1 ){
                    this.ocultarComponente(this.Cmp.taller_asignado);
                    this.ocultarComponente(this.Cmp.observacion_nota);
                }if (record.data.ID == 2){
                    this.mostrarComponente(this.Cmp.taller_asignado);
                    this.mostrarComponente(this.Cmp.observacion_nota);
                }if (record.data.ID == 3){
                    this.ocultarComponente(this.Cmp.taller_asignado);
                    this.mostrarComponente(this.Cmp.observacion_nota);
                }
                this.Cmp.taller_asignado.reset();
                this.Cmp.observacion_nota.reset();
            },this);
            this.reload();
        },
        iniciarEvento:function () {
            var data = this.getSelectedData();
            if (data['origen_pedido'] == 'Almacenes Consumibles o Rotables' && this.historico == 'si') {

                this.ocultarComponente(this.Cmp.mel);
                this.ocultarComponente(this.Cmp.tipo_reporte);
                this.ocultarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.justificacion);
                this.ocultarComponente(this.Cmp.id_matricula);
                this.ocultarComponente(this.Cmp.nro_justificacion);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);

                this.mostrarComponente(this.Cmp.fecha_cotizacion);
                this.mostrarComponente(this.Cmp.id_proveedor);
                this.mostrarComponente(this.Cmp.nro_po);
                this.mostrarComponente(this.Cmp.fecha_po);
                this.CampoBloqueado(true);
            }else{
                var data = this.getSelectedData();
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
                this.ocultarComponente(this.Cmp.fecha_po);
                this.CampoBloqueado(true);

                if(data['estado'] ==  'compra' || data['estado'] ==  'despachado' || data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado' )  {
                    this.mostrarComponente(this.Cmp.fecha_cotizacion);
                    this.mostrarComponente(this.Cmp.id_proveedor);
                    this.mostrarComponente(this.Cmp.nro_po);
                    this.mostrarComponente(this.Cmp.fecha_po);
                    this.Cmp.id_proveedor.setValue(data['id_proveedor']);
                    this.Cmp.id_proveedor.setRawValue(data['desc_proveedor']);
                    this.Cmp.fecha_cotizacion.setValue(data['fecha_cotizacion'] );
                }

            }

        },
        CampoBloqueado : function (sw) {
            this.Cmp.id_funcionario_sol.setDisabled(sw);
            this.Cmp.origen_pedido.setDisabled(sw);
            this.Cmp.id_matricula.setDisabled(sw);
            this.Cmp.tipo_solicitud.setDisabled(sw);
            this.Cmp.nro_no_rutina.setDisabled(sw);

        },

        font :function () {
            this.Atributos[this.getIndAtributo('tipo_falla')].grid=false;
            this.Atributos[this.getIndAtributo('tipo_reporte')].grid=false;
            this.Atributos[this.getIndAtributo('mel')].grid=true;
            this.Atributos[this.getIndAtributo('nro_no_rutina')].grid=false;
            this.Atributos[this.getIndAtributo('id_matricula')].grid=false;
            this.Atributos[this.getIndAtributo('nro_justificacion')].grid=false;
            this.Atributos[this.getIndAtributo('justificacion')].grid=false;


          //  this.Atributos[this.getIndAtributo('nombre_estado_firma')].grid=true;
            this.Atributos[this.getIndAtributo('nro_po')].grid=true;
            this.Atributos[this.getIndAtributo('id_proveedor')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_cotizacion')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_po')].grid=true;
            this.Atributos[this.getIndAtributo('mensaje_correo')].grid = true;
            this.Atributos[this.getIndAtributo('tipo_evaluacion')].grid=true;
            this.Atributos[this.getIndAtributo('taller_asignado')].grid=true;
            this.Atributos[this.getIndAtributo('observacion_nota')].grid=true;
            this.Atributos[this.getIndAtributo('lugar_entrega')].grid=true;
            this.Atributos[this.getIndAtributo('condicion')].grid=true;


        }







    }
</script>
