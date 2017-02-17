<?php
/**
 *@package pXP
 *@file gen-Solicitud.php
 *@author  (admin)
 *@date 23-12-2016 13:12:58
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Solicitud=Ext.extend(Phx.gridInterfaz,{
        nombreVista: 'Solicitud',
        constructor:function(config){
            this.idContenedor = config.idContenedor;
            this.maestro=config.maestro;
            //llama al constructor de la clase padre
            Phx.vista.Solicitud.superclass.constructor.call(this,config);
            this.init();

            this.grid.addListener('cellclick', this.oncellclick,this);
            this.store.baseParams = {tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'borrador';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;


            this.addButton('ini_estado',{
                grupo:[0],
                argument: {estado: 'inicio'},
                text:'Dev. a borrador',
                iconCls: 'batras',
                disabled:true,
                handler:this.iniEstado,
                tooltip: '<b>Retorna a estado borrador</b>'
            });

            this.addButton('ant_estado',{
                grupo: [0,1],
                argument: {estado: 'anterior'},
                text: 'Anterior',
                iconCls: 'batras',
                disabled: true,
                handler: this.antEstado,
                tooltip: '<b>Volver al Anterior Estado</b>'
            });

            this.addButton('sig_estado',{
                grupo: [0,1],
                text:'Siguiente',
                iconCls: 'badelante',
                disabled:true,
                handler:this.sigEstado,
                tooltip: '<b>Pasar al Siguiente Estado</b>'
            });
            this.addButton('btnChequeoDocumentosWf',{
                text: 'Documentos',
                grupo: [0,1,2,3,4,5],
                iconCls: 'bchecklist',
                disabled: true,
                handler: this.loadCheckDocumentosRecWf,
                tooltip: '<b>Documentos del Reclamo</b><br/>Subir los documetos requeridos en el Reclamo seleccionado.'
            });
            this.addButton('btnObs',{
                grupo:[0,1,3,4,5],
                text :'Obs Wf.',
                iconCls : 'bchecklist',
                disabled: true,
                handler : this.onOpenObs,
                tooltip : '<b>Observaciones</b><br/><b>Observaciones del WF</b>'
            });
            this.addButton('diagrama_gantt',{
                grupo:[0,1,2,3,4,5],
                text:'Gant',
                iconCls: 'bgantt',
                disabled:true,
                handler:diagramGantt,
                tooltip: '<b>Diagrama Gantt de proceso macro</b>'
            });

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


        Atributos:[
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
                    name: 'nro_solicitud'
                },
                type:'Field',
                form:true
            },
            {
                config:{
                    name: 'nro_tramite',
                    fieldLabel: 'Nro. Tramite',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    maxLength:100

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
                    name: 'estado',
                    fieldLabel: 'Estado',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 200,
                    maxLength:100,
                    renderer: function(value, p, record){

                        if(record.data.contador_estados > 1 || value == 'borrador' && record.data.contador_estados > 0 ) {

                            if(value == 'borrador' ){
                                return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value, record.data.contador_estados );
                             }
                             if(value == 'vobo_area'){
                                 return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value);
                             }
                             if(value == 'revision'){
                                 return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value+' abastecimientos', record.data.contador_estados);
                             }
                             if(value == 'cotizacion'){
                                 return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value+' abastecimientos', record.data.contador_estados);
                             }
                             if(value == 'compra'){
                                 return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value+' abastecimientos', record.data.contador_estados);
                             }
                             if(value == 'despachado'){
                                 return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value+' MIAMI', record.data.contador_estados);
                             }
                             if(value == 'arribo'){
                                 return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value+' Bolivia', record.data.contador_estados);
                             }
                             if(value == 'desaduanizado'){
                                 return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value, record.data.contador_estados);
                             }
                             if(value == 'almacen'){
                                 return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value+' BoA', record.data.contador_estados);
                             }
                             if(value == 'finalizado'){
                                 return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value, record.data.contador_estados);
                             }if(value == 'anulado'){
                                return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value, record.data.contador_estados);
                             }if(value == 'vobo_aeronavegabilidad'){
                                return String.format('<div title="Número de revisiones: {1}"><b><font color="red">{0} - ({1})</font></b></div>', value, record.data.contador_estados);
                             }
                        }else{

                            if(value == 'revision'){
                                return String.format('<div title="Número de revisiones: {1}">{0} - ({1})</div>', value+' abastecimientos', record.data.contador_estados);
                            }if(value == 'cotizacion'){
                                return String.format('<div title="Número de revisiones: {1}">{0} - ({1})</div>', value+' abastecimientos', record.data.contador_estados);
                            }
                            if(value == 'compra'){
                                return String.format('<div title="Número de revisiones: {1}">{0} - ({1})</div>', value+' abastecimientos', record.data.contador_estados);
                            }
                            if(value == 'despachado'){
                                return String.format('<div title="Número de revisiones: {1}">{0} - ({1})</div>', value+' MIAMI', record.data.contador_estados);
                            }
                            if(value == 'arribo'){
                                return String.format('<div title="Número de revisiones: {1}">{0} - ({1})</div>', value+' Bolivia', record.data.contador_estados);
                            }
                            if(value == 'desaduanizado'){
                                return String.format('<div title="Número de revisiones: {1}">{0} - ({1})</div>', value, record.data.contador_estados);
                            }
                            if(value == 'almacen'){
                                return String.format('<div title="Número de revisiones: {1}">{0} - ({1})</div>', value+' BoA', record.data.contador_estados);
                            }

                            return String.format('<div title="Número de revisiones: {1}">{0} - ({1})</div>', value, record.data.contador_estados);
                        }

                    }
                },
                type:'TextField',
                filters:{pfiltro:'sol.estado',type:'string'},
                id_grupo:1,
                grid:true,
                form:false,
                bottom_filter:true


            },
            {
                config:{
                    name:'origen_pedido',
                    fieldLabel:'Origen Pedido',
                    allowBlank:false,
                    emptyText:'Elija una opción...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    anchor: '100%',
                    gwidth: 230,
                    store:['Gerencia de Operaciones','Gerencia de Mantenimiento','Almacenes Consumibles o Rotables']

                },
                type:'ComboBox',
                id_grupo:0,
                grid:true,
                form:true,
                bottom_filter:true

            },
            {
                config: {
                    name: 'id_funcionario_sol',
                    fieldLabel: 'Funcionario Solicitante',
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
                    anchor: '100%',
                    gwidth: 230,
                    minChars: 2,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['desc_funcionario1']);
                    }
                },
                type: 'ComboBox',
                id_grupo: 0,
                filters: {pfiltro:' f.desc_funcionario1', type:'string'},
                grid: true,
                form: true,
                bottom_filter:true
            },
            {
                config:{
                    name: 'fecha_solicitud',
                    fieldLabel: 'Fecha Solicitud',
                    allowBlank: true,
                    anchor: '95%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{pfiltro:'sol.fecha_solicitud',type:'date'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config: {
                    name: 'id_matricula',
                    fieldLabel: 'Matricula',
                    allowBlank: true,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_gestion_materiales/control/Solicitud/listarMatricula',
                        id: 'id_matricula',
                        root: 'datos',
                        sortInfo: {
                            field: 'matricula',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_orden_trabajo','desc_orden', 'matricula'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'ord.desc_orden'}
                    }),
                    valueField: 'id_orden_trabajo',
                    displayField: 'desc_orden',
                    gdisplayField: 'desc_orden',
                    tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_orden}</p><p style="color: blue">{matricula}</p></div></tpl>',
                    hiddenName: 'id_matricula',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 100,
                    queryDelay: 1000,
                    anchor: '100%',
                    gwidth: 230,
                    minChars: 2,
                    renderer : function(value, p, record) {
                        return String.format('{0}', record.data['matricula']);
                    }
                },
                type: 'ComboBox',
                id_grupo: 0,
                filters: {pfiltro: 'ot.desc_orden',type: 'string'},
                grid: true,
                form: true,
                bottom_filter:true
            },
            {
                config:{
                    name: 'motivo_solicitud',
                    fieldLabel: 'Motivo Solicitud',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:100
                },
                type:'TextArea',
                filters:{pfiltro:'sol.motivo_solicitud',type:'string'},
                id_grupo:0,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'observaciones_sol',
                    fieldLabel: 'Observaciones',
                    allowBlank: false,
                    anchor: '100%',
                    gwidth: 200,
                    maxLength:1000
                },
                type:'TextArea',
                filters:{pfiltro:'sol.observaciones_sol',type:'string'},
                id_grupo:0,
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
                    anchor: '100%',
                    store:['Directriz de Aeronavegabilidad','Boletín de Servicio','Task Card','"0" Existencia en Almacén','Otros']

                },
                type:'ComboBox',
                id_grupo:1,
                grid:true,
                form:true

            },
            {
                config:{
                    name: 'nro_justificacion',
                    fieldLabel: 'Nro. Justicaion',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'sol.nro_justificacion',type:'string'},
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter:true
            },
            {
                config:{
                    name:'tipo_solicitud',
                    fieldLabel:'Tipo Solicitud',
                    allowBlank:false,
                    emptyText:'Elija una opción...',

                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    anchor: '100%',
                    store:['AOG','Critico','Normal','No Aplica']

                },
                type:'ComboBox',
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter:true

            },
            {
                config:{
                    name:'tipo_falla',
                    fieldLabel:'Tipo de Falla',
                    allowBlank:true,
                    emptyText:'Elija una opción...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    lazyRender:true,
                    mode: 'local',
                    anchor: '101%',
                    store:['Falla Confirmada','T/S en Progreso','No Aplica'],

                },
                type:'ComboBox',
                id_grupo:1,
                grid:true,
                form:true

            },
            {
                config:{
                    name:'tipo_reporte',
                    fieldLabel:'Tipo de Reporte',
                    allowBlank:true,
                    emptyText:'Elija una opción...',

                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    anchor: '100%',
                    store:['PIREPS','MAREPS','No Aplica']

                },
                type:'ComboBox',
                id_grupo:1,
                grid:true,
                form:true

            },
            {
                config:{
                    name:'mel',
                    fieldLabel:'MEL',
                    allowBlank:true,
                    emptyText:'Elija una opción...',

                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    anchor: '100%',
                    store:['A','B','C','No Aplica']

                },
                type:'ComboBox',
                id_grupo:1,
                grid:true,
                form:true

            },
            {
                config:{
                    name: 'fecha_requerida',
                    fieldLabel: 'Fecha Requerida',
                    allowBlank: true,
                    anchor: '95%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){

                        var fecha_actual = new Date();
                        var dias = record.data.control_fecha;


                            if (dias >= 7 && dias <= 10) {
                                return String.format('<div ext:qtip="Optimo"><b><font color="green">{0}</font></b><br></div>', value?value.dateFormat('d/m/Y'):'');
                            }
                            else if(dias >=3  && dias <= 6){
                                return String.format('<div ext:qtip="Critico"><b><font color="orange">{0}</font></b><br></div>', value?value.dateFormat('d/m/Y'):'');
                            }else if(dias = -1){
                                return String.format('<div ext:qtip="Con Respuesta"><b><font color="red">{0}</font></b><br></div>', value?value.dateFormat('d/m/Y'):'');
                            }

                    }
                },
                type:'DateField',
                filters:{pfiltro:'sol.fecha_requerida',type:'date'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'nro_no_rutina',
                    fieldLabel: 'Nro. Doc. Origen de Solicitud',
                    allowBlank: true,
                    anchor: '100%',
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
                 name: 'nro_po',
                 fieldLabel: 'Nro. PO',
                 allowBlank: true,
                 anchor: '100%',
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
                config: {
                    name: 'id_proveedor',
                    fieldLabel: 'Proveedor',
                    anchor: '80%',
                    tinit: false,
                    allowBlank: true,
                    origen: 'PROVEEDOR',
                    gdisplayField: 'desc_proveedor',
                    anchor: '100%',
                    gwidth: 280,
                    listWidth: '280',
                    resizable: true
                },
                type: 'ComboRec',
                filters:{pfiltro:'pro.desc_proveedor',type:'string'},
                id_grupo:2,
                grid: false,
                form: true,
                bottom_filter:true
            },
            {
                config:{
                    name: 'fecha_cotizacion',
                    fieldLabel: 'Fecha Cotizacion',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{pfiltro:'sol.fecha_cotizacion',type:'date'},
                id_grupo:2,
                grid:false,
                form:true
            },
            {
             config:{
             name: 'fecha_arribado_bolivia',
             fieldLabel: 'Fecha Arribo Bolivia',
             allowBlank: true,
             anchor: '100%',
             gwidth: 100,
             format: 'd/m/Y',
             renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
             },
             type:'DateField',
             filters:{pfiltro:'sol.fecha_arribo_bolivia',type:'date'},
             id_grupo:2,
             grid:false,
             form:true
             },
            {
             config:{
             name: 'fecha_desaduanizacion',
             fieldLabel: 'Fecha Desaduanizacion',
             allowBlank: true,
             anchor: '100%',
             gwidth: 100,
             format: 'd/m/Y',
             renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
             },
             type:'DateField',
             filters:{pfiltro:'sol.fecha_desaduanizacion',type:'date'},
             id_grupo:2,
             grid:false,
             form:true
             },
            {
                config:{
                    name: 'fecha_en_almacen',
                    fieldLabel: 'Fecha en Almacen',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 150,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{pfiltro:'sol.fecha_en_almacen',type:'date'},
                id_grupo:2,
                grid:false,
                form:true
            },
            {
                config:{
                    name: 'nro_parte',
                    fieldLabel: 'Nro. de Parte',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 200,
                    maxLength:100

                },
                type:'TextField',
                filters:{pfiltro:'de.nro_parte',type:'string'},
                id_grupo:1,
                grid:true,
                form:false,
                bottom_filter:true
            },
            {
                config:{
                    name: 'estado_reg',
                    fieldLabel: 'Estado Reg.',
                    allowBlank: true,
                    anchor: '80%',
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
                    anchor: '80%',
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
                    anchor: '80%',
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
                    anchor: '80%',
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
                    anchor: '80%',
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
                    anchor: '80%',
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
                    anchor: '80%',
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
            {name:'desc_matricula', type: 'string'},
            {name:'matricula', type: 'string'},

            {name:'tipo_reporte', type: 'string'},
            {name:'mel', type: 'string'},
            {name:'nro_no_rutina', type: 'string'},
            {name:'desc_proveedor', type: 'string'},
            {name:'nro_parte', type: 'string'},
            {name:'nro_justificacion', type: 'string'},

            {name:'fecha_cotizacion', type: 'date',dateFormat:'Y-m-d'},
            'contador_estados',
            {name:'revisado_so', type: 'string'},
            {name:'control_fecha', type: 'string'}
            


        ],
        sortInfo:{
            field: 'sol.fecha_reg',
            direction: 'DESC'
        },
        bdel:true,
        bsave:false,
        btest: false,
        fwidth: '68%',
        fheight : '68%',

        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleSol.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleSol'
            }
        ],
        Grupos: [
            {
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
                                title: '  Datos Generales ',
                                autoHeight: true,
                                items: [/*this.compositeFields()*/],
                                id_grupo: 0
                            }

                        ]
                    },
                    {
                        bodyStyle: 'padding-left:10px;',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: ' Justificacion de Necesidad ',
                                autoHeight: true,
                                items: [],
                                id_grupo: 1
                            }


                        ]
                    },
                    {
                        bodyStyle: 'padding-left:10px;',
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
            }
        ],
        onButtonEdit: function() {

            Phx.vista.Solicitud.superclass.onButtonEdit.call(this);
            var rec = this.sm.getSelected();
            console.log('onButtonEdit: '+rec);
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_workflow/control/TipoColumna/listarColumnasFormulario',
                params:{

                    id_estado_wf: rec.data['id_estado_wf']
                },
                success:this.editCampos,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });

        },

        editCampos: function(resp){
            Phx.CP.loadingHide();
            var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

            console.log('campos Edit: '+JSON.stringify(objRes));
            this.armarFormularioFromArray(objRes.datos);
        },

        preparaMenu: function(n)
        {	var rec = this.getSelectedData();
            var tb =this.tbar;

            this.getBoton('btnChequeoDocumentosWf').setDisabled(false);
            Phx.vista.Solicitud.superclass.preparaMenu.call(this,n);
            this.getBoton('diagrama_gantt').enable();
            this.getBoton('btnObs').enable();
        },

        liberaMenu:function(){
            var tb = Phx.vista.Solicitud.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('ant_estado').disable();
                this.getBoton('sig_estado').disable();
                this.getBoton('btnChequeoDocumentosWf').setDisabled(true);
                this.getBoton('diagrama_gantt').disable();
                this.getBoton('btnObs').disable();
                this.getBoton('ini_estado').setVisible(false);

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
            }
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

        sigEstado: function(){
            var rec = this.sm.getSelected();

            console.log(rec.data);

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
                            delegate: this.onSaveWizard,
                        }],
                        scope: this
                    }
                );
            if(  rec.data.estado=='revision' && rec.data.fecha_cotizacion == null ||rec.data.estado=='cotizacion' && rec.data.nro_po=='' &&  rec.data.id_proveedor == null || rec.data.estado=='despachado' && rec.data.fecha_despacho_miami == null  || rec.data.estado=='despachado' && rec.data.fecha_arribado_bolivia == null || rec.data.estado=='arribo' && rec.data.fecha_desaduanizacion == null || rec.data.estado=='desaduanizado' && rec.data.fecha_en_almacen == null){
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
            resp.argument.wizard.panel.destroy()
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
                        delegate: this.onAntEstado,
                    }
                    ],
                    scope:this
                })
        },
        onAntEstado: function(wizard,resp){
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/anteriorEstadoSolicitud',
                params:{
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
            resp.argument.wizard.panel.destroy()
            this.reload();
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
                        delegate: this.inAntEstado,
                    }
                    ],
                    scope:this
                })
        },
        inAntEstado: function(wizard,resp){
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/inicioEstadoSolicitud',
                params:{
                    id_proceso_wf: resp.id_proceso_wf,
                    id_estado_wf:  resp.id_estado_wf,
                    obs: resp.obs,
                    estado_destino: resp.estado_destino
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
            resp.argument.wizard.panel.destroy()
            this.reload();
        },


        oncellclick : function(grid, rowIndex, columnIndex, e) {

            console.log('llega cellclick')
            var record = this.store.getAt(rowIndex),
                fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name

            if(fieldName == 'revisado_so') {
                this.cambiarRevision(record);
            }
        },
        cambiarRevision: function(record){
            Phx.CP.loadingShow();
            var d = record.data
            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/cambiarRevision',
                params:{ id_solicitud: d.id_solicitud, revisado_so: d.revisado_so},
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
        }

    })
</script>