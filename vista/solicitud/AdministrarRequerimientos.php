<?php
/**
 *@package pXP
 *@file RegistroSolicitud.php
 *@author  Ismael Valdivia
 *@date 19-08-2022 08:12
 *@Interface para el inicio de solicitudes de materiales
 */
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
    Phx.vista.AdministrarRequerimientos = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'AdministrarRequerimientos',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleNoEdit.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleNoEdit'
            }
        ],
        constructor: function (config) {

            this.font();
            Phx.vista.AdministrarRequerimientos.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'consulta_op';


            this.addButton('pasar_estado',{
                grupo: [100],//2
                text:'Enviar Cot. Solicitada',
                iconCls: 'badelante',
                id:'pasar_estado',
                disabled: true,
                hidden:false,
                handler:this.enviarSolicitada,
                tooltip: '<b>Pasará al siguiente estado <b style="color:red;">sin enviar Correo</b></b>'
            });
            //this.load({params:{start:0, limit:this.tam_pag}});

            this.getBoton('sig_estado').setVisible(false);
            this.getBoton('ant_estado').setVisible(false);
            this.getBoton('new').setVisible(false);
            this.getBoton('del').setVisible(false);
            this.getBoton('edit').setVisible(false);
            this.getBoton('btnObs').setVisible(false);
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Report').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
            this.getBoton('clonar_solicitud').setVisible(false);
            //this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);
            this.getBoton('btnCheckPresupeusto').setVisible(false);
            this.getBoton('bmodCuce').setVisible(false);
            this.getBoton('bfecha_impresion_Form3008').setVisible(false);
            this.getBoton('bmodPAC').setVisible(false);
            this.getBoton('bhistorialModificaciones').setVisible(true);

            this.getBoton('bspacio1').setVisible(true);
            /*Aumentando botones del Administrador*/
            Ext.getCmp('modificar_cotizacion').setVisible(true);
            Ext.getCmp('modificar_po').setVisible(true);

        },

        	preparaMenu: function(n) {
            var tb = Phx.vista.AdministrarRequerimientos.superclass.preparaMenu.call(this);
            var rec = this.getSelectedData();

            if (rec) {
              Ext.getCmp('modificar_cotizacion').setDisabled(false);
              Ext.getCmp('modificar_po').setDisabled(false);
              Ext.getCmp('pasar_estado').setDisabled(false);
              this.getBoton('bhistorialModificaciones').setDisabled(false);
            } else {
              Ext.getCmp('modificar_cotizacion').setDisabled(true);
              Ext.getCmp('modificar_po').setDisabled(true);
              Ext.getCmp('pasar_estado').setDisabled(true);
              this.getBoton('bhistorialModificaciones').setDisabled(true);
            }

            return tb;

          },

          liberaMenu: function(){
            var tb = Phx.vista.AdministrarRequerimientos.superclass.liberaMenu.call(this);
            console.log("aqui llega data",Ext.getCmp('modificar_cotizacion'));
            // Ext.getCmp('modificar_cotizacion').el.dom.onmouseover = function () {
      			// 	Ext.getCmp('modificar_cotizacion').btnEl.dom.style.backgroundColor = '#0046D2';
      			// 	Ext.getCmp('modificar_cotizacion').btnEl.dom.style.color = '#ffffff';
      			// 	Ext.getCmp('modificar_cotizacion').btnEl.dom.style.fontWeight = 'bold';
            //   Ext.getCmp('modificar_po').btnEl.dom.style.width = '90px';
            //
      			// };
            //
      			// Ext.getCmp('modificar_cotizacion').el.dom.onmouseout = function () {
      			// 	Ext.getCmp('modificar_cotizacion').btnEl.dom.style.backgroundColor = '';
      			// 	Ext.getCmp('modificar_cotizacion').btnEl.dom.style.color = '';
      			// 	Ext.getCmp('modificar_cotizacion').btnEl.dom.style.fontWeight = '';
      			// };
            //
      			// Ext.getCmp('modificar_po').el.dom.onmouseover = function () {
      			// 	Ext.getCmp('modificar_po').btnEl.dom.style.backgroundColor = '#D2AF00';
      			// 	Ext.getCmp('modificar_po').btnEl.dom.style.color = '#ffffff';
            //   Ext.getCmp('modificar_po').btnEl.dom.style.fontWeight = 'bold';
      			// 	Ext.getCmp('modificar_po').btnEl.dom.style.width = '70px';
            //
      			// };
            //
      			// Ext.getCmp('modificar_po').el.dom.onmouseout = function () {
      			// 	Ext.getCmp('modificar_po').btnEl.dom.style.backgroundColor = '';
      			// 	Ext.getCmp('modificar_po').btnEl.dom.style.color = '';
      			// 	Ext.getCmp('modificar_po').btnEl.dom.style.fontWeight = '';
      			// };

          },
        gruposBarraTareas:[
            {name:'consulta_op',title:'<H1 align="center" style="color:#E78800; font-size:11px;"><i style="font-size:12px;" class="fa fa-truck" aria-hidden="true"></i> Operaciones</h1>',grupo:100,height:0, width: 100},
            {name:'consulta_mal',title:'<H1 align="center" style="color:#007AD9; font-size:11px;"><i style="font-size:12px;" class="fa fa-wrench" aria-hidden="true"></i> Mantenimiento</h1>',grupo:100,height:0, width: 100},
            {name:'consulta_ab',title:'<H1 align="center" style="color:#00A530; font-size:11px;"><i style="font-size:12px;" class="fa fa-retweet" aria-hidden="true"></i> Abastecimientos</h1>',grupo:100,height:0, width: 150},
            {name:'consulta_ceac',title:'<H1 align="center" style="color:#FF0000; font-size:11px;"><i style="font-size:12px;" class="fa fa-plane" aria-hidden="true"></i> Ope. CEAC</h1>',grupo:100,height:0, width: 200},
            {name:'consulta_repu',title:'<H1 align="center" style="color:#7100BB; font-size:11px;"><i style="font-size:12px;" class="fa fa-cogs" aria-hidden="true"></i> Reparaciones</h1>',grupo:100,height:0, width: 200}

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
        bactGroups:  [5,100],
        bexcelGroups: [5,100],
        bganttGroups: [0,5,100],

        bgantt:true,
        font:function () {
            this.Atributos[this.getIndAtributo('nro_po')].grid=true;
            this.Atributos[this.getIndAtributo('id_proveedor')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_cotizacion')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_arribado_bolivia')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_desaduanizacion')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_en_almacen')].grid=true;
            this.Atributos[this.getIndAtributo('tipo_evaluacion')].grid=true;
            this.Atributos[this.getIndAtributo('taller_asignado')].grid=true;
            this.Atributos[this.getIndAtributo('observacion_nota')].grid=true;
            this.Atributos[this.getIndAtributo('lugar_entrega')].grid=true;
            this.Atributos[this.getIndAtributo('condicion')].grid=true;

        },


        enviarSolicitada:function () {
            var rec = this.sm.getSelected();
            var tramite = rec.data['nro_tramite'];
            var tipo_tramite = tramite.substring(0,2);


            console.log("aqui llega el dato",rec);

            if (rec.data.estado == 'cotizacion') {
              /*Aqui actualizamos el estado para que se realize el envio de correo*/
              //if (this.store.baseParams.tipo_interfaz != 'AdministrarRequerimientos') {
                Ext.Ajax.request({
                    url:'../../sis_gestion_materiales/control/Solicitud/updateEstadoCorreo',
                    params:{id_solicitud:rec.data.id_solicitud,
                            estado_correo : 'no'
                          },
                    success: function(resp){
                        var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                        console.log("Se enviara Correo ",reg.ROOT.datos.enviar_correo);

                    },
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });
              //}
              /********************************************************************/




              if (((tipo_tramite != 'GR') && (rec.data.nro_po == '' || rec.data.nro_po == null) /*&& rec.data.origen_solicitud == 'control_mantenimiento'*/) && rec.data.estado == 'compra') {
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
                            id_solicitud: rec.data.id_solicitud
                            /********************************************************************************************/
                        }
                    }, this.idContenedor, 'FormEstadoWf',

                    {
                        config: [{
                            event: 'beforesave',
                            delegate: this.onGenerarOrdenCompra
                        }],
                        scope: this
                    }
                );

             } else {
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
                            id_solicitud: rec.data.id_solicitud
                            /********************************************************************************************/
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
              }
              if ( /*rec.data.estado == 'compra' && rec.data.fecha_po == null ||*/ rec.data.estado == 'despachado' && rec.data.fecha_arribado_bolivia == null || rec.data.estado == 'desaduanizado' && rec.data.fecha_en_almacen == null || rec.data.estado == 'arribo'
                  && rec.data.fecha_desaduanizacion == null /*||  rec.data.estado == 'cotizacion' && rec.data.condicion == ''*/) {
                      this.onButtonEdit();
                  }
              } else {
                Ext.Msg.show({
                   title:'<center><h1 style="color:red; font-size:18px">CAMBIAR ESTADO</h1></center>',
                   msg: '<span style="font-size:15px">Solo se puede realizar el cambio de estado a solicitudes en <b style="color:red;">Cotización Pendiente.</b></span>',
                   maxWidth : 500,
                   width: 500,
                   buttons: Ext.Msg.OK,
                   scope:this
                   });
              }
            }




    }

</script>
