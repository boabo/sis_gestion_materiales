<?php
/**
 *@package pXP
 *@file PedidosRepuestos.php
 *@author  Ismael Valdivia
 *@date 16-03-2020 10:00
 *@Interface para el inicio de solicitudes de materiales
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.PedidosRepuestos = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'PedidoRepuesto',
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
            this.interfaz = 'Repuestos';
            this.Grupos.push( {
                layout: 'column',
                border: false,
                xtype: 'fieldset',
                autoScroll: false,
                defaults: {
                    border: false
                },
                style:{
                      background:'#548DCA',
                      // height:'245px',
                      autoHeight: true,
                      // border:'2px solid red',
                      marginTop:'-26px',
                      // marginLeft: '50%'
                     },
                items: [

                    {
                      xtype: 'fieldset',
                      autoScroll: false,
                      style:{
                            background:'#FFB09C',
                            width:'330px',
                            minHeight:'250px',
                            border:'1px solid black',
                            borderRadius:'2px',
                           },
                        items: [
                            {
                                xtype: 'fieldset',
                                border: false,
                                autoScroll: false,
                                title: ' Datos Adquisiciones ',
                                autoHeight: true,
                                style:{
                                      background:'#FFB09C'
                                     },
                                items: [],
                                id_grupo: 2
                            }

                        ]
                    },
                    {
                      xtype: 'fieldset',
                      style:{
                            background:'#81D3FF',
                            width:'330px',
                            minHeight:'250px',
                            marginLeft:'2px',
                            border:'1px solid black',
                            borderRadius:'2px'
                           },
                        items: [
                            {
                                xtype: 'fieldset',
                                title: ' Datos Comité de Evaluación ',
                                autoHeight: true,
                                border: false,
                                autoScroll: false,
                                style:{
                                      background:'#81D3FF',
                                      //border:'2px solid green',
                                      //width : '100%',
                                     },
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
            Phx.vista.PedidosRepuestos.superclass.constructor.call(this, config);
            this.store.baseParams = {tipo_interfaz: this.nombreVista};
            this.store.baseParams.pes_estado = 'pedido_re_pendiente';
            this.load({params: {start: 0, limit: this.tam_pag}});
            this.getBoton('Report').setVisible(false);
            this.addButton('btnpac',
                {
                    iconCls: 'bemail',
                    text: 'Generar PAC Referencial',
                    grupo:[3],
                    disabled: true,
                    handler: this.correoPac,
                    tooltip: '<b>Envia Correo PAC</b>'
                }
            );

            this.finCons = true;

        },

        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'g_repuestos',
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

        gruposBarraTareas:[
            {name:'pedido_re_pendiente',title:'<H1 align="center" style="color:#E85C00; font-size:12px;"><i style="font-size:15px;" class="fa fa-pencil-square"></i> Pendientes</h1>',grupo:3,height:0},
            {name:'pedido_re_solicitada',title:'<H1 align="center" style="color:#00AD1F; font-size:12px;"><i style="font-size:15px;" class="fa fa-check-circle"></i> Solicitadas</h1>',grupo:3,height:0},
            {name:'pedido_re_sin_resp',title:'<H1 align="center" style="color:red; font-size:12px;"><i style="font-size:15px;" class="fa fa-times-circle"></i> Sin Respuestas</h1>',grupo:3,height:0},
            {name:'pedido_re_comite',title:'<H1 align="center" style="color:#007615; font-size:12px;"><i style="font-size:15px;" class="fa fa-thumbs-up"></i> Vobo Comité</h1>',grupo:5,height:0},
            {name:'pedido_re_compra',title:'<H1 align="center" style="color:#0038D8; font-size:12px;"><i style="font-size:15px;" class="fa fa-shopping-cart"></i> Compra</h1>',grupo:3,height:0},
            {name:'pedido_re_concluido',title:'<H1 align="center" style="color:#00B377; font-size:12px;"><i style="font-size:15px;" class="fa fa fa-th-list"></i> Concluido</h1>',grupo:5,height:0}
        ],

        actualizarSegunTab: function(name, indice){

            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                if(name == 'pedido_re_solicitada'){
                    //this.getBoton('btnproveedor').setVisible(true);
                    this.getBoton('Cotizacion').setVisible(true);
                    this.getBoton('btnpac').setVisible(true);
                }else if (name == 'pedido_re_pendiente' || name == 'pedido_re_compra'){
                    //this.getBoton('btnproveedor').setVisible(true);
                    this.getBoton('Cotizacion').setVisible(true);
                    this.getBoton('btnpac').setVisible(false);
                    this.noti_documentos.setText('');
                } else{
                    //this.getBoton('btnproveedor').setVisible(false);
                    this.getBoton('btnpac').setVisible(false);
                    this.getBoton('Cotizacion').setVisible(false);
                    this.getBoton('btnpac').setVisible(false);
                    this.noti_documentos.setText('');
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
        bganttGroups: [0,1,2,3,5],

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
            Phx.vista.PedidosRepuestos.superclass.preparaMenu.call(this,n);

            //this.getBoton('btnproveedor').enable();
            this.getBoton('Cotizacion').enable();
            this.getBoton('btnpac').enable();


            /*Aqui pondremos para verificar los docuementos*/
            if (this.store.baseParams.pes_estado == 'pedido_re_solicitada') {
              Ext.Ajax.request({
                  url:'../../sis_gestion_materiales/control/Solicitud/getVerificarDocumentos',
                  params:{id_proceso_wf: data.id_proceso_wf,
                          estado_sig: 'comite_unidad_abastecimientos'},
                  success:function(resp){
                      var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                      if (reg.ROOT.datos.v_chekeado == 'no') {
                          this.getBoton('sig_estado').disable();
                          this.noti_documentos.setText('Adjuntar doc: '+reg.ROOT.datos.nombre_documento);
                      } else {
                        this.getBoton('sig_estado').enable();
                        this.noti_documentos.setText('');
                        //this.reload();
                      }
                    /************************************************************************************/

                  },
                  failure: this.conexionFailure,
                  timeout:this.timeout,
                  scope:this
              });
            }
            if (this.store.baseParams.pes_estado == 'pedido_re_compra') {
              if (this.store.baseParams.monto_pac > 20000) {
                Ext.Ajax.request({
                      url:'../../sis_gestion_materiales/control/Solicitud/getVerificarDocumentos',
                      params:{id_proceso_wf: data.id_proceso_wf,
                              estado_sig: 'despachado'},
                      success:function(resp){
                          var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                          if (reg.ROOT.datos.v_chekeado == 'no') {
                              this.getBoton('sig_estado').disable();
                              this.noti_documentos.setText('Adjuntar doc: '+reg.ROOT.datos.nombre_documento);
                          } else {
                            this.getBoton('sig_estado').enable();
                            this.noti_documentos.setText('');
                            //this.reload();
                          }
                        /************************************************************************************/

                      },
                      failure: this.conexionFailure,
                      timeout:this.timeout,
                      scope:this
                  });
              } else {
                this.getBoton('sig_estado').enable();
                this.noti_documentos.setText('');
                //this.reload();
              }
            }
            /***********************************************/





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
            var tb = Phx.vista.PedidosRepuestos.superclass.liberaMenu.call(this);
            if(tb){


                this.getBoton('sig_estado').disable();
                //this.getBoton('btnproveedor').disable();
                this.getBoton('btnpac').disable();
                this.getBoton('Archivado_concluido').enable();
                this.getBoton('Cotizacion').disable();
                this.getBoton('ini_estado').disable();
                this.getBoton('Report').setVisible(false);
            }
            return tb;
        },
        onButtonEdit: function() {
             this.iniciarEvento();
            Phx.vista.PedidosRepuestos.superclass.onButtonEdit.call(this);
            //this.Cmp.mensaje_correo.setValue('Favor cotizar según documento Adjunto.');
            //this.ocultarComponente(this.Cmp.taller_asignado);
            var data = this.getSelectedData();
            this.Cmp.observaciones_sol.reset();
            console.log("aqui llega para poner el nro rep",data);
            this.ocultarComponente(this.Cmp.observacion_nota);
            this.Cmp.tipo_evaluacion.on('select',function(combo, record, index){
                if (record.data.ID == 1 ){
                    //this.ocultarComponente(this.Cmp.taller_asignado);
                    //this.ocultarComponente(this.Cmp.observacion_nota);
                    this.Cmp.mensaje_correo.setValue('Favor cotizar según documento adjunto');
                }if (record.data.ID == 2 ){
                    //this.ocultarComponente(this.Cmp.taller_asignado);
                    //this.ocultarComponente(this.Cmp.observacion_nota);
                    this.Cmp.mensaje_correo.setValue('Favor cotizar la reparación REP-'+data['nro_po']+' de la(s) siguiente(s) unidad(es) según Especificación Técnica adjunta, llenando con los datos según tabla:');
                    this.Cmp.motivo_solicitud.setValue('CONTRATACIÓN DE SERVICIO DE REPARACIÓN DE REPUESTOS ROTABLES FLOTA BOA');

                    this.Cmp.observaciones_sol.reset();
                    this.Cmp.observaciones_sol.allowBlank = true;
                    this.ocultarComponente(this.Cmp.observaciones_sol);


                }if (record.data.ID == 3){
                    //this.mostrarComponente(this.Cmp.taller_asignado);
                    //this.ocultarComponente(this.Cmp.observacion_nota);
                    this.Cmp.observaciones_sol.setValue('Se puede aceptar unidad(es) cotizada(s) en Flat Exchange');
                    this.Cmp.mensaje_correo.setValue('Favor cotizar el Flat Exchange o remplazo REP-'+data['nro_po']+' de la(s) siguiente(s) unidad(es) según Especificación Técnica adjunta, llenando con los datos según tabla:');
                    this.Cmp.motivo_solicitud.setValue('COMPRA DE REPUESTO POR INTERCAMBIO (FLAT EXCHANGE) REQUERIDO PARA FLOTA BOA ');
                    this.mostrarComponente(this.Cmp.observaciones_sol);
                }if (record.data.ID == 4){
                    //this.ocultarComponente(this.Cmp.taller_asignado);
                    //this.ocultarComponente(this.Cmp.observacion_nota);
                    this.Cmp.observaciones_sol.setValue('Se puede aceptar unidad(es) cotizada(s) en Flat Exchange');
                    this.Cmp.mensaje_correo.setValue('Favor cotizar el Flat Exchange o remplazo REP-'+data['nro_po']+' de la(s) siguiente(s) unidad(es) según Especificación Técnica adjunta, llenando con los datos según tabla:');
                    this.Cmp.motivo_solicitud.setValue('COMPRA DE REPUESTO POR INTERCAMBIO (FLAT EXCHANGE) REQUERIDO PARA FLOTA BOA ');
                    this.mostrarComponente(this.Cmp.observaciones_sol);
                }if (record.data.ID == 5){
                    //this.ocultarComponente(this.Cmp.taller_asignado);
                    //this.ocultarComponente(this.Cmp.observacion_nota);
                    this.Cmp.mensaje_correo.setValue('Favor cotizar la calibración REP-'+data['nro_po']+' de la(s) siguiente(s) unidad(es) según Especificación Técnica adjunta, llenando con los datos según tabla:');
                    this.Cmp.motivo_solicitud.setValue('CONTRATACIÓN DE SERVICIO DE CALIBRACIÓN DE EQUIPOS');
                    this.Cmp.observaciones_sol.reset();
                    this.Cmp.observaciones_sol.allowBlank = true;
                    this.ocultarComponente(this.Cmp.observaciones_sol);
                }
                //this.Cmp.taller_asignado.reset();
                this.Cmp.observacion_nota.reset();
            },this);
            this.reload();
        },

        iniciarEvento:function () {

          /*Aqui ocultamos el campo (Ismael valdivia 08/04/2020)*/
          this.ocultarComponente(this.Cmp.nro_no_rutina);
          this.ocultarComponente(this.Cmp.tipo_falla);
          this.ocultarComponente(this.Cmp.tipo_reporte);
          this.ocultarComponente(this.Cmp.mel);
          this.ocultarComponente(this.Cmp.justificacion);
          this.ocultarComponente(this.Cmp.nro_justificacion);
          this.ocultarComponente(this.Cmp.fecha_requerida);
          this.mostrarComponente(this.Cmp.id_condicion_entrega);
          this.mostrarComponente(this.Cmp.id_forma_pago);
          this.mostrarComponente(this.Cmp.motivo_solicitud);
          this.mostrarComponente(this.Cmp.tipo_evaluacion);
          this.ocultarComponente(this.Cmp.lugar_entrega);
          this.ocultarComponente(this.Cmp.obs_pac);
          this.ocultarComponente(this.Cmp.condicion);
          this.ocultarComponente(this.Cmp.remark);
          this.Cmp.remark.allowBlank=true;

          this.mostrarComponente(this.Cmp.metodo_de_adjudicación);
          this.Cmp.metodo_de_adjudicación.allowBlank=false;

          this.mostrarComponente(this.Cmp.tipo_de_adjudicacion);
          this.Cmp.tipo_de_adjudicacion.allowBlank=false;

          //this.ocultarComponente(this.Cmp.tiempo_entrega);


          this.ocultarComponente(this.Cmp.metodo_de_adjudicación);
          this.Cmp.metodo_de_adjudicación.allowBlank=true;

          this.ocultarComponente(this.Cmp.tipo_de_adjudicacion);
          this.Cmp.tipo_de_adjudicacion.allowBlank=true;


          var estado = this.getSelectedData();

          this.mostrarComponente(this.Cmp.tiempo_entrega);
          this.Cmp.tiempo_entrega.allowBlank=false;
          // if(estado['estado'] ==  'cotizacion'){
          //
          // } else {
          //   this.ocultarComponente(this.Cmp.tiempo_entrega);
          //   this.Cmp.tiempo_entrega.allowBlank=true;
          // }
          //
          /******************************************************/



            var data = this.getSelectedData();
            // if (data.estado == 'cotizacion') {
            //     this.Cmp.lista_correos.reset();
            //     this.Cmp.condicion.reset();
            // }
            /*Aumentamos la condicion para mostrar el nuevo Campo MELOBSERVACION Ismael Valdivia 01/10/2020*/
            if(data.mel == 'A' || data.mel == 'Otro' || data.mel == 'otro' || data.mel == 'OTRO'){
              this.mostrarComponente(this.Cmp.mel_observacion);
            } else {
              this.ocultarComponente(this.Cmp.mel_observacion);
            }
            /***********************************************************************************************/
            if (data['origen_pedido'] == 'Reparación de Repuestos' && this.historico == 'si') {

                //this.mostrarComponente(this.Cmp.mel);
                //this.mostrarComponente(this.Cmp.tipo_reporte);
                //this.mostrarComponente(this.Cmp.tipo_falla);

                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);

                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.mostrarComponente(this.Cmp.id_proveedor);
                this.mostrarComponente(this.Cmp.nro_po);
                this.ocultarComponente(this.Cmp.fecha_po);
                this.Cmp.fecha_po.allowBlank = true;
                this.CampoBloqueado(true);
            }else{
                var data = this.getSelectedData();
                //this.mostrarComponente(this.Cmp.mel);
                //this.mostrarComponente(this.Cmp.tipo_reporte);
                //this.mostrarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.ocultarComponente(this.Cmp.id_proveedor);
                this.mostrarComponente(this.Cmp.nro_po);
                this.ocultarComponente(this.Cmp.fecha_po);

                this.Cmp.fecha_po.allowBlank = true;
                this.CampoBloqueado(true);

                if(data['estado'] ==  'compra' || data['estado'] ==  'despachado' || data['estado'] ==  'arribo' || data['estado'] ==  'desaduanizado' )  {
                    this.ocultarComponente(this.Cmp.fecha_cotizacion);
                    this.mostrarComponente(this.Cmp.id_proveedor);
                    //this.mostrarComponente(this.Cmp.nro_po);
                    this.mostrarComponente(this.Cmp.fecha_po);
                    this.mostrarComponente(this.Cmp.fecha_entrega);

                    this.Cmp.observaciones_sol.setDisabled(true);
                    this.Cmp.tiempo_entrega.setDisabled(true);
                    this.Cmp.mensaje_correo.setDisabled(true);
                    this.Cmp.lugar_entrega.setDisabled(true);
                    this.Cmp.condicion.setDisabled(true);
                    this.Cmp.fecha_cotizacion.setDisabled(true);
                    this.Cmp.id_proveedor.setDisabled(true);
                    this.Cmp.obs_pac.setDisabled(true);

                    this.Cmp.fecha_po.on('select', function (c,r,i) {
                      /*Aumentamos la fecha 30 dias mas*/
                      var fecha_po = new Date(this.Cmp.fecha_po.getValue());
                      fecha_po.setDate(fecha_po.getDate() + 15);
                      var fecha_formateada = fecha_po.format('d/m/Y');
                      /*********************************/
                      this.Cmp.fecha_entrega.setValue(fecha_formateada);
                    },this);

                    this.Cmp.id_proveedor.setValue(data['id_proveedor']);
                    this.Cmp.id_proveedor.setRawValue(data['desc_proveedor']);
                    this.Cmp.fecha_cotizacion.setValue(data['fecha_cotizacion'] );
                } else {
                  this.ocultarComponente(this.Cmp.fecha_entrega);
                }

            }

        },
        CampoBloqueado : function (sw) {
            this.Cmp.id_funcionario_sol.setDisabled(sw);
            this.Cmp.origen_pedido.setDisabled(sw);
            this.Cmp.id_matricula.setDisabled(sw);
            this.Cmp.justificacion.setDisabled(sw);
            this.Cmp.nro_justificacion.setDisabled(sw);
            // this.Cmp.tipo_solicitud.setDisabled(sw);
            this.Cmp.nro_no_rutina.setDisabled(sw);
            this.Cmp.mel.setDisabled(sw);
            this.Cmp.tipo_reporte.setDisabled(sw);
            this.Cmp.tipo_falla.setDisabled(sw);
            this.Cmp.tipo_solicitud.setDisabled(sw);
        },




        font :function () {
            this.Atributos[this.getIndAtributo('nombre_estado_firma')].grid=true;
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
            this.Atributos[this.getIndAtributo('lista_correos')].config.fieldLabel='Lista de Talleres';
            this.Atributos[this.getIndAtributo('tiempo_entrega')].config.fieldLabel='Tiempo de entrega Repuestos';
            this.Atributos[this.getIndAtributo('motivo_solicitud')].config.fieldLabel='Justificación';
            this.Atributos[this.getIndAtributo('nro_po')].config.fieldLabel='Nro REP';
            this.Atributos[this.getIndAtributo('fecha_po')].config.fieldLabel='Fecha REP';


        },

        successSave:function(resp){
          Phx.vista.PedidosRepuestos.superclass.successSave.call(this,resp);
          if (this.historico == 'no') {
            this.confirmarEstado();
          }

        },


    }
</script>
