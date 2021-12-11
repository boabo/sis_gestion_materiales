<?php
/**
 *@package pXP
 *@file gen-Presupuesto_material.php
 *@author  (admin)
 *@date 23-12-2016 13:12:58
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
include_once ('../../media/styles.php');
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Presupuesto_material=Ext.extend(Phx.gridInterfaz,{
        nombreVista: 'Presupuesto_Mantenimiento',
        viewConfig: {
          seleccionarFila : 'seleccionado',
          getRowClass: function(record) {
                 return 'selector';
          },
          onRowSelect : function(row) {
              this.addRowClass(row, this.seleccionarFila);
          },
          onRowDeselect : function(row) {
              this.removeRowClass(row, this.seleccionarFila);
          },

        },
        constructor:function(config){
            this.idContenedor = config.idContenedor;
            this.maestro=config.maestro;
            //llama al constructor de la clase padre
            Phx.vista.Presupuesto_material.superclass.constructor.call(this,config);
            this.init();
            this.store.baseParams = {tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'borrador';
            this.store.baseParams.pes_estado = 'no_revisado';
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/getDatosNecesarios',
                params:{id_usuario:0},
                success:function(resp){
                    var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));

                    this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
                    this.cmbGestion.setRawValue(reg.ROOT.datos.gestion);
                    this.store.baseParams.id_gestion = reg.ROOT.datos.id_gestion;
                    this.load({params:{start:0, limit:this.tam_pag}});

                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
            this.cmbGestion.on('select',this.capturarEventos, this);
            this.bbar.add(this.cmbGestion);
            this.controlCorreos=false;
            this.finCons = true;
            this.mensaje='';
            this.grid.addListener('cellclick', this.oncellclick,this);
            this.addButton('btnChequeoDocumentosWf',{
                text: 'Documentos',
                iconCls: 'bchecklist',
                disabled: true,
                handler: this.loadCheckDocumentosRecWf,
                tooltip: '<b>Documentos </b><br/>Subir los documetos requeridos.'
            });
            this.addButton('btnObs',{
                text :'Obs Wf.',
                iconCls : 'bchecklist',
                disabled: true,
                handler : this.onOpenObs,
                tooltip : '<b>Observaciones</b><br/><b>Observaciones del WF</b>'
            });
            this.addButton('diagrama_gantt',{
                text:'Gant',
                iconCls: 'bgantt',
                disabled:true,
                handler:diagramGantt,
                tooltip: '<b>Diagrama Gantt de proceso macro</b>'
            });
            this.bbar.el.dom.style.background='#03A27C';
    				this.tbar.el.dom.style.background='#03A27C';
    				this.grid.body.dom.firstChild.firstChild.firstChild.firstChild.style.background='#E9E9E9';
    				this.grid.body.dom.firstChild.firstChild.lastChild.style.background='#F6F6F6';

            function diagramGantt(){
                var data=this.sm.getSelected().data.id_proceso_wf;
                Phx.CP.loadingShow();
                Ext.Ajax.request({
                    url:'../../sis_workflow/control/ProcesoWf/diagramaGanttTramite',
                    params:{'id_proceso_wf':data},
                    success:this.successExport,
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });
            }

        },

        gruposBarraTareas:[
            {name:'no_revisado',title:'<H1 align="center" style="color:red; font-size:12px;"><i style="font-size:15px;" class="fa fa-eye"></i> No Revisados</h1>',grupo:0,height:0},
            {name:'revisado',title:'<H1 align="center" style="color:green; font-size:12px;"><i style="font-size:15px;" class="fa fa-check-square"></i> Revisados</h1>',grupo:0,height:0},
        ],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },

        preparaMenu: function(n){
            var rec = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.Presupuesto_material.superclass.preparaMenu.call(this,n);
            this.getBoton('btnChequeoDocumentosWf').setDisabled(false);
            this.getBoton('diagrama_gantt').enable();
            this.getBoton('btnObs').enable();
        },

        liberaMenu:function(){
            var tb = Phx.vista.Presupuesto_material.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('btnChequeoDocumentosWf').setDisabled(true);
                this.getBoton('diagrama_gantt').disable();
                this.getBoton('btnObs').disable();

            }
            return tb
        },

        loadCheckDocumentosRecWf:function() {
            var rec=this.sm.getSelected();
            rec.data.nombreVista = this.nombreVista;
            Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
                'Chequear documento del WF',
                {
                    width:'90%',
                    height:500
                },
                rec.data,
                this.idContenedor,
                'DocumentoWf'
            )
        },

        onOpenObs:function() {
            var rec=this.sm.getSelected();
            var data = {
                id_proceso_wf: rec.data.id_proceso_wf,
                id_estado_wf: rec.data.id_estado_wf,
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

        Atributos:[
            {
                //configuracion del componente
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'nro_cotizacion'
                },
                type:'Field',
                form:true
            },
            {
                //configuracion del componente
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_solicitud'
                },
                type:'Field',
                form:true
            },
            {

                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_cotizacion'
                },
                type:'Field',
                form:true
            },
            {
                config: {
                    name: 'revisado_presupuesto',
                    fieldLabel: 'Rev',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 65,
                    renderer: function (value, p, record) {
                      var checked = '',
                          momento = 'no';
                      if(value == 'si'){
                          checked = 'checked';
                      }
                      return  String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:35px;width:35px;" type="checkbox"  {0}></div>',checked);
                    },
                },
                type: 'Checkbox',
                //filters: {pfiltro: 'plapa.revisado_asistente', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config:{
                    name: 'nro_tramite',
                    fieldLabel: 'Solicitud',
                    allowBlank: true,
                    width: 200,
                    gwidth: 350,
                    maxLength:100,
                    renderer: function(value, p, record) {
                      if (record.data['mel'] == 'A') {
                        var color = '#E88C00';
                      } else if (record.data['mel'] == 'B') {
                        var color = '#0053BD';
                      } else if (record.data['mel'] == 'C') {
                         var color = '#9100A2';
                      }else if (record.data['mel'] == 'AOG') {
                         var color = '#FF5151';
                      }else if (record.data['mel'] == 'No Aplica') {
                         var color = '#318495';
                      } else if (record.data['mel'] == 'OTRO') {
                         var color = '#FF6060';
                      } else if (record.data['origen_pedido'] == 'Reparación de Repuestos') {
                         var color = '#A66500';
                      } else {
                        var color = '#5290AD';
                      }

                       if(record.data['tipo'] == 'clon'){
                           return  '<div><p><b>Tramite: </b><font color="'+color+'">'+record.data['nro_tramite']+' </font><font color="'+color+'">'+record.data['tipo']+'</font></p>' +
                               '<p><b>Fecha Sol: </b><font color="'+color+'"><b>'+ record.data['fecha_solicitud'].dateFormat('d/m/Y') +'</b></font></p>' +
                               '<p><b>Funcionario Solicitante: </b><font color="'+color+'">'+ record.data['funcionario_solicitante']+'</font></p>' +
                               '<p><b>Técnico Solicitante: </b><font color="'+color+'">'+ record.data['desc_funcionario1'] +'</font></div>';
                       }else{
                           return  '<div><p><b>Tramite: </b><font color="'+color+'"><b>'+record.data['nro_tramite']+'</b></font></p>' +
                               '<p><b>Fecha Sol.: </b><font color="'+color+'"><b>'+ record.data['fecha_solicitud'].dateFormat('d/m/Y') +'</font></p>' +
                               '<p><b>Funcionario Solicitante: </b><font color="'+color+'">'+ record.data['funcionario_solicitante']+'</font></p>' +
                               '<p><b>Técnico Solicitante: </b><font color="'+color+'">'+ record.data['desc_funcionario1'] +'</font></div>';

                       }
                    }
                },
                type:'TextField',
                filters:{pfiltro:'sol.nro_tramite',type:'string'},
                id_grupo:1,
                grid:true,
                form:false,
                bottom_filter:true
            },
            {
                config:{
                    name:'origen_pedido',
                    fieldLabel:'Info. Sol.',
                    allowBlank:false,
                    emptyText:'Elija una opción...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    width: 200,
                    gwidth: 230,
                    store:['Gerencia de Operaciones','Gerencia de Mantenimiento','Almacenes Consumibles o Rotables','Centro de Entrenamiento Aeronautico Civil'],
                    renderer: function(value, p, record) {
                        var color;
                        if (record.data['tipo_solicitud'] == 'Critico'){
                            color = ' <font  color="red" >';
                        } else if(record.data['tipo_solicitud'] == 'AOG'){
                            color = '<font color="black" >';
                        }else{
                            color = '<font color="green">';
                        }
                        return  '<div><p><b>Importe PAC: </b><font color="#dc143c">'+record.data['monto_pac']+' '+record.data['moneda']+
                            '</font><p style="font-size:14px;"><b style="color:blue;">Tipo Solicitud: </b>'+color+'<b>'+record.data['tipo_solicitud']+'</b></font></div>';
                    }

                },
                type:'ComboBox',
                id_grupo:0,
                grid:true,
                form:true,
                bottom_filter:true

            },
            {
                config:{
                    name: 'nombre_estado',
                    fieldLabel: 'Estados',
                    allowBlank: true,
                    width: 200,
                    gwidth: 250,
                    maxLength:100,
                    renderer: function(value, p, record) {
                        var color ;
                        var colorTipoSolicitud;
                        if (record.data['tipo_solicitud'] == 'Critico'){
                            colorTipoSolicitud = ' <font  color="red" >';
                        } else if(record.data['tipo_solicitud'] == 'AOG'){
                            colorTipoSolicitud = '<font color="black" >';
                        }else{
                            colorTipoSolicitud = '<font color="green">';
                        }

                        if(record.data['contador_estados']  > 1 || record.data['nombre_estado'] == 'Borrador' && record.data['contador_estados'] > 0 ) {
                            color = '<font color="red">';
                        }else{
                            color = '<font color="black">';
                        }
                        var colorf ;
                        if(record.data['estado_firma']  == 'rechazado' ) {
                            colorf = '<font color="red">';
                        }else{
                            colorf = '<font color="green">';
                        }
                        if(record.data['estado_firma'] != '' ) {
                            return '<div><p><b>Estado de Proceso: </b>' + color + '<b>' + record.data['nombre_estado'] + ' (' + record.data['contador_estados'] + ')</font></b></p>' +
                                '<p><b>Estado de Firmas: </b>' + colorf + '<b>' + record.data['nombre_estado_firma'] + '</font></b></p>'+
                                /*'<p style="font-size:14px;"><b style="color:red;">Tipo Solicitud: </b>'+colorTipoSolicitud+'<b>'+record.data['tipo_solicitud']+'</b></p>*/'</div>';
                        }else{
                            return '<div><p><b>Estado: </b>' + color + '<b>' + record.data['nombre_estado'] + ' (' + record.data['contador_estados'] + ')</font></b></p>'+
                            /*'<p style="font-size:14px;"><b style="color:red;">Tipo Solicitud: </b>'+colorTipoSolicitud+'<b>'+record.data['tipo_solicitud']+'</b></p>*/'</div>';
                        }
                    }
                },
                type:'TextField',
                filters:{pfiltro:'ti.nombre_estado',type:'string'},
                id_grupo:1,
                grid:true,
                form:false,
                bottom_filter:true
            },
            {
                config:{
                    name: 'nro_no_rutina',
                    fieldLabel: 'Nro. No Rutina',
                    allowBlank: true,
                    width: 200,
                    gwidth: 200,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'sol.motivo_solicitud',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name:'justificacion',
                    fieldLabel:'Justificación ',
                    allowBlank:false,
                    emptyText:'Elija una opción...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    lazyRender:true,
                    mode: 'local',
                    width: 200,
                    gwidth: 180,
                    store:['Directriz de Aeronavegabilidad','Boletín de Servicio','Task Card','"0" Existencia en Almacén','Otros'],
                    renderer: function(value, p, record) {
                        if (record.data['nro_justificacion'] != "") {
                            return '<tpl for="."><div><p><b>Justificacion: </b>' + record.data['justificacion'] +
                                '<p><b>Nro. Justificación: </b><b>' + record.data['nro_justificacion'] + '</b></div></tpl>';
                        }else{
                            return '<tpl for="."><div><p><b>Justificacion: </b>' + record.data['justificacion']+'</p></div></tpl>';
                        }
                    }
                },
                type:'ComboBox',
                id_grupo:1,
                grid:true,
                form:true

            },
            {
                config:{
                    name: 'nro_justificacion',
                    fieldLabel: 'Nro. Justificación',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'sol.nro_justificacion',type:'string'},
                id_grupo:1,
                grid:false,
                form:true,
                bottom_filter:true
            },
            {
                config:{
                    name: 'nro_po',
                    fieldLabel: 'Nro PO',
                    allowBlank: false,
                    width: 200,
                    gwidth: 100, maxLength:50
                },
                type:'TextField',
                filters:{pfiltro:'sol.nro_po',type:'string'},
                id_grupo:2,
                grid:false,
                form:true,
                bottom_filter:true
            },
            {
                config:{
                    name: 'fecha_po',
                    fieldLabel: 'Datos PO',
                    allowBlank: false,
                    width: 200,
                    gwidth: 150,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){
                        if (record.data['fecha_po'] == null){
                            return '<div><p><b>Nro. PO: </b>' + record.data['nro_po'] + '</p></div>';
                        }else {
                            return '<div><p><b>Nro. PO: </b>' + record.data['nro_po'] + '</p>' +
                                '<p><b>Fecha PO: </b><font color="#dc143c"><b>' + record.data['fecha_po'].dateFormat('d/m/Y') + '</b></font></div>';
                        }
                    }
                },
                type:'DateField',
                filters:{pfiltro:'sol.fecha_po',type:'date'},
                id_grupo:2,
                grid:false,
                form:true
            },
            {
                config: {
                    name: 'id_funcionario_sol',
                    fieldLabel: 'Técnico Solicitante',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
                        id: 'id_funcionario_sol',
                        root: 'datos',
                        sortInfo: {
                            field: 'desc_funcionario1',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_funcionario','desc_funcionario1','email_empresa','nombre_cargo','lugar_nombre','oficina_nombre'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'FUNCAR.desc_funcionario1#FUNCAR.nombre_cargo'}
                    }),
                    valueField: 'id_funcionario',
                    displayField: 'desc_funcionario1',
                    gdisplayField: 'desc_funcionario1',
                    tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_funcionario1}</p><p style="color: blue">{nombre_cargo}<br>{email_empresa}</p><p style="color:blue">{oficina_nombre} - {lugar_nombre}</p></div></tpl>',
                    hiddenName: 'id_funcionario_sol',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    width: 200,
                    gwidth: 300,
                    minChars: 2,

                    renderer : function(value, p, record) {
                        //return String.format('{0}', record.data['desc_funcionario1']);
                        if(record.data.estado == 'almacen'){
                            return String.format('<div ext:qtip="Optimo"><b><font color="blue">{0}</font></b><br></div>', value);
                        }else{
                            return String.format('<div ext:qtip="Optimo"><b><font color="black">{0}</font></b><br></div>', value);
                        }

                    }
                },
                type: 'ComboBox',
                id_grupo: 0,
                filters: {pfiltro:' f.desc_funcionario1', type:'string'},
                grid: false,
                form: true,
                bottom_filter:true
            },
            /*Aumentando el id_moneda para ir modificado*/
            {
                config:{
                    name: 'monto_pac',
                    fieldLabel: 'Importe Total Referencial(PAC)',
                    allowBlank: false,
                    readOnly:true,
                    style:{
                      background:'#FFFB7D',
                      color:'red',
                      fontWeight:'bold'
                    },
                    width: 200,
                    gwidth: 100,
                    maxLength:100
                },
                type:'MoneyField',
                filters:{pfiltro:'pa.monto_pac',type:'string'},
                id_grupo:2,
                grid:false,
                form:true
            },
            {
                config:{
                    name: 'estado_reg',
                    fieldLabel: 'Estado Reg.',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    maxLength:10
                },
                type:'TextField',
                filters:{pfiltro:'sol.estado_reg',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'usr_reg',
                    fieldLabel: 'Creado por',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'usu1.cuenta',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'usuario_ai',
                    fieldLabel: 'Funcionaro AI',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    maxLength:300
                },
                type:'TextField',
                filters:{pfiltro:'sol.usuario_ai',type:'string'},
                id_grupo:1,
                grid:false,
                form:false
            },
            {
                config:{
                    name: 'fecha_reg',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
                },
                type:'DateField',
                filters:{pfiltro:'sol.fecha_reg',type:'date'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'id_usuario_ai',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'sol.id_usuario_ai',type:'numeric'},
                id_grupo:1,
                grid:false,
                form:false
            },
            {
                config:{
                    name: 'usr_mod',
                    fieldLabel: 'Modificado por',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    maxLength:4
                },
                type:'Field',
                filters:{pfiltro:'usu2.cuenta',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'fecha_mod',
                    fieldLabel: 'Fecha Modif.',
                    allowBlank: true,
                    width: 200,
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
                },
                type:'DateField',
                filters:{pfiltro:'sol.fecha_mod',type:'date'},
                id_grupo:1,
                grid:true,
                form:false
            }
        ],
        tam_pag:50,
        title:'Solicitud',
        ActSave:'../../sis_gestion_materiales/control/Solicitud/insertarSolicitud',
        ActDel:'../../sis_gestion_materiales/control/Solicitud/eliminarSolicitud',
        ActList:'../../sis_gestion_materiales/control/Solicitud/listarSolicitud',
        id_store:'id_solicitud',
        fields: [
            {name:'id_solicitud', type: 'numeric'},
            {name:'id_funcionario_sol', type: 'numeric'},
            {name:'id_orden_trabajo', type: 'numeric'},
            {name:'id_proveedor', type: 'numeric'},
            {name:'id_proceso_wf', type: 'numeric'},
            {name:'id_estado_wf', type: 'numeric'},
            {name:'nro_po', type: 'string'},
            {name:'tipo_solicitud', type: 'string'},
            {name:'fecha_entrega_miami', type: 'date',dateFormat:'Y-m-d'},
            {name:'origen_pedido', type: 'string'},
            {name:'fecha_requerida', type: 'date',dateFormat:'Y-m-d'},
            {name:'observacion_nota', type: 'string'},
            {name:'fecha_solicitud', type: 'date',dateFormat:'Y-m-d'},
            {name:'estado_reg', type: 'string'},
            {name:'observaciones_sol', type: 'string'},
            {name:'fecha_tentativa_llegada', type: 'date',dateFormat:'Y-m-d'},
            {name:'fecha_despacho_miami', type: 'date',dateFormat:'Y-m-d'},
            {name:'justificacion', type: 'string'},
            {name:'fecha_arribado_bolivia', type: 'date',dateFormat:'Y-m-d'},
            {name:'fecha_desaduanizacion', type: 'date',dateFormat:'Y-m-d'},
            {name:'fecha_en_almacen', type: 'date',dateFormat:'Y-m-d'},
            {name:'cotizacion', type: 'numeric'},
            {name:'tipo_falla', type: 'string'},
            {name:'nro_tramite', type: 'string'},
            {name:'id_matricula', type: 'numeric'},
            {name:'nro_solicitud', type: 'string'},
            {name:'motivo_solicitud', type: 'string'},
            {name:'fecha_desaduanizacion', type: 'date',dateFormat:'Y-m-d'},
            {name:'estado', type: 'string'},
            {name:'id_usuario_reg', type: 'numeric'},
            {name:'usuario_ai', type: 'string'},
            {name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
            {name:'id_usuario_ai', type: 'numeric'},
            {name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
            {name:'id_usuario_mod', type: 'numeric'},
            {name:'usr_reg', type: 'string'},
            {name:'usr_mod', type: 'string'},
            {name:'desc_funcionario1', type: 'string'},
            {name:'matricula', type: 'string'},

            {name:'tipo_reporte', type: 'string'},
            {name:'mel', type: 'string'},
            {name:'nro_no_rutina', type: 'string'},
            {name:'desc_proveedor', type: 'string'},
            {name:'nro_partes', type: 'string'},
            {name:'nro_justificacion', type: 'string'},

            {name:'fecha_cotizacion', type: 'date',dateFormat:'Y-m-d'},
            'contador_estados',

            {name:'control_fecha', type: 'string'},
            {name:'estado_firma', type: 'string'},
            {name:'id_proceso_wf_firma', type: 'numeric'},
            {name:'id_estado_wf_firma', type: 'numeric'},
            'contador_estados_firma',
            {name:'nombre_estado', type: 'string'},
            {name:'nombre_estado_firma', type: 'string'},
            {name:'fecha_po', type: 'date',dateFormat:'Y-m-d'},
            {name:'tipo_evaluacion', type: 'string'},
            {name:'taller_asignado', type: 'string'},
            {name:'lista_correos', type: 'string'},
            {name:'condicion', type: 'string'},
            {name:'lugar_entrega', type: 'string'},
            {name:'mensaje_correo', type: 'string'},
            {name:'tipo', type: 'string'},
            {name:'id_cotizacion', type: 'numeric'},
            {name:'monto_pac', type: 'numeric'},
            {name:'moneda', type: 'string'},
            {name:'tipo_mov', type: 'string'},
            //{name:'nro_parte_det', type: 'string'}
            //{name:'nro_parte_alterno_det', type: 'string'}
            {name:'monto', type: 'numeric'},

            {name:'obs_pac', type: 'string'},
            /*Aumentando este campo (Ismael Valdivia 31/01/2020)*/
            {name:'id_depto', type: 'numeric'},
            {name:'id_gestion', type: 'numeric'},
            {name:'id_moneda', type: 'numeric'},
            {name:'funcionario_solicitante', type: 'string'},
            {name:'revisado_presupuesto', type: 'string'},



        ],
        sortInfo:{
            field: 'sol.fecha_reg',
            direction: 'DESC'
        },
        bdel:true,
        bsave:false,
        btest: false,
        fheight:'100%',
      	fwidth: 750,


        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/presupuestos_materiales/PresupuestoDet.php',
                title:'Detalle',
                height:'50%',
                cls:'PresupuestoDet'
            }
        ],
        Grupos: [
            {
                layout: 'column',
                border: false,
                xtype: 'fieldset',
                autoScroll: false,
                defaults: {
                    border: false
                },
                style:{
                      background:'#548DCA',
                     },

                items: [
                    {
                      xtype: 'fieldset',
                      style:{
                            background:'#5EDE82',
                            width:'330px',
                            //height:'270px',
                            border:'1px solid black',
                            borderRadius:'2px'
                           },
                        id:'datos_generales',
                        items: [

                            {
                                xtype: 'fieldset',
                                title: '  Datos Generales ',
                                border: false,
                                autoHeight: true,
                                style:{
                                      background:'#5EDE82',
                                     },

                                items: [/*this.compositeFields()*/],
                                id_grupo: 0
                            }

                        ]
                    },
                    {
                      xtype: 'fieldset',
                      style:{
                            background:'#EEDE5A',
                            width:'330px',
                            height:'289px',
                            marginLeft:'2px',
                            border:'1px solid black',
                            borderRadius:'2px'
                           },
                      id:'justificacion_necesidad',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: ' Justificacion de Necesidad ',
                                //autoHeight: true,
                                border: false,
                                style:{
                                      background:'#EEDE5A',
                                      //border:'2px solid green',
                                      height:'289px',
                                     },
                                items: [],
                                id_grupo: 1
                            }


                        ]
                    }
                ]
            }
        ],

        bdel:false,
      	bsave:false,
        btest: false,
        bedit: false,
        bnew: false,

        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'gestion_rev',
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
        capturarEventos: function () {
            if(this.validarFiltros()){
                this.capturaFiltros();
            }
        },

        capturaFiltros:function(combo, record, index){
            this.desbloquearOrdenamientoGrid();
            this.store.baseParams.id_gestion=this.cmbGestion.getValue();
            this.load({params:{start:0, limit:this.tam_pag}});
            //this.load();
        },

        validarFiltros:function(){
            if(this.cmbGestion.isValid()){
                return true;
            }
            else{
                return false;
            }

        },

        oncellclick : function(grid, rowIndex, columnIndex, e) {
            var record = this.store.getAt(rowIndex),
                fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name

            if(fieldName == 'revisado_presupuesto') {
                this.cambiarRevision(record);
                this.evento();
                //this.onButtonEdit();
            }
        },
        cambiarRevision: function(record){
            Phx.CP.loadingShow();
            var d = record.data;
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/controlPresupuesto',
                params:{id_solicitud: d.id_solicitud, revisado_presupuesto: d.revisado_presupuesto},
                success: this.successRevision,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
            this.reload();
        },
        successRevision: function(resp){
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
        },
        evento:function () {
            //Phx.CP.getPagina(this.idContenedorPadre).reload();
        },

        onButtonNew:function(){
          Phx.vista.Presupuesto_material.superclass.onButtonNew.call(this);
          this.window.items.items[0].body.dom.style.background = '#548DCA';
          this.window.mask.dom.style.background = '#7E7E7E';
          this.window.mask.dom.style.opacity = '0.8';
        },

        onButtonEdit:function(){
          Phx.vista.Presupuesto_material.superclass.onButtonEdit.call(this);
          this.window.items.items[0].body.dom.style.background = '#548DCA';
          this.window.mask.dom.style.background = '#7E7E7E';
          this.window.mask.dom.style.opacity = '0.8';
        }


    })

</script>
