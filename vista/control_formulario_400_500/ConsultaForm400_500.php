<?php
/**
 *@package pXP
 *@file gen-Informe.php
 *@author  (Ismael Valdivia)
 *@date 30-05-2012 11:31:07
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ConsultaForm400_500=Ext.extend(Phx.gridInterfaz,{

        bnew : false,
        bedit : false,
        bdel : false,
        ActList:'../../sis_gestion_materiales/control/Solicitud/listarForm400',
        gruposBarraTareas:[
            {name:'si-400',title:'<H1 align="center" style="font-size:10px; color:green;"><i class="fa fa-check"></i> CON FORMULARIO <BR/>400</H1>',grupo:0,height:0, width: 300},
            {name:'no-400',title:'<H1 align="center" style="font-size:10px; color:red;"><i class="fa fa-times"></i> SIN FORMULARIO <BR/>400</H1>',grupo:1,height:0, width: 300},
            {name:'si-500',title:'<H1 align="center" style="font-size:10px; color:green;"><i class="fa fa-check"></i> CON FORMULARIO <BR/>500</H1>',grupo:2,height:0, width: 300},
            {name:'no-500',title:'<H1 align="center" style="font-size:10px; color:red;"><i class="fa fa-times"></i> SIN FORMULARIO <BR/>500</H1>',grupo:3,height:0, width: 300},


        ],
        bactGroups:  [0,1,2,3],
        bexcelGroups: [0,1,2,3],
        bganttGroups: [0,1,2,3],

        actualizarSegunTab: function(name, indice){
            this.store.baseParams.tipo_formulario = name;


            if (name == 'si-400' || name == 'no-400') {
              this.cm.setHidden(16,true);
              this.cm.setHidden(15,false);
            } else if (name == 'si-500' || name == 'no-500') {
              this.cm.setHidden(16,false);
              this.cm.setHidden(15,true);
            }


            if (this.fecha_fin.getValue() != '') {
              this.load({params:{start:0, limit:this.tam_pag}});
            }

        },

        constructor:function(config){
            // this.tbarItems = ['-',this.cmbGestion,'-',
            //     this.cmbAux,'-'
            //
            // ];

            this.maestro=config;
            // Ext.Ajax.request({
            //     url: '../../sis_adquisiciones/control/Reporte/getDatosUsuario',
            //     params: {id_usuario: 0},
            //     success: function (resp) {
            //         var reg = Ext.decode(Ext.util.Format.trim(resp.responseText));
            //         this.cmbAux.setValue(reg.ROOT.datos.id_usuario);
            //         this.cmbAux.setRawValue(reg.ROOT.datos.desc_usuario);
            //         this.store.baseParams.id_usuario = reg.ROOT.datos.id_usuario;
            //         this.load({params: {start: 0, limit: this.tam_pag}});
            //     },
            //     failure: this.conexionFailure,
            //     timeout: this.timeout,
            //     scope: this
            // });

            // Ext.Ajax.request({
            //     url:'../../sis_parametros/control/Gestion/obtenerGestionByFecha',
            //     params:{fecha:new Date()},
            //     success:function(resp){
            //         var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
            //         this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
            //         this.cmbGestion.setRawValue(reg.ROOT.datos.anho);
            //         this.store.baseParams.id_gestion=reg.ROOT.datos.id_gestion;
            //         this.load({params:{start:0, limit:this.tam_pag}});
            //     },
            //     failure: this.conexionFailure,
            //     timeout:this.timeout,
            //     scope:this
            // });

            //llama al constructor de la clase padre
            Phx.vista.ConsultaForm400_500.superclass.constructor.call(this,config);
            this.init();
            //this.store.baseParams.tipo_formulario = 'si-400';
            this.cm.setHidden(16,true);
            //this.cmbGestion.on('select',this.capturarEventos, this);
            //this.load({params:{start:0, limit: 50}});



            this.addButton('btnChequeoDocumentosWf',{
                text: 'Documentos',
                grupo: [0,1,2,3,4,5],
                iconCls: 'bchecklist',
                disabled: true,
                handler: this.loadCheckDocumentosRecWf,
                tooltip: '<b>Documentos del Reclamo</b><br/>Subir los documetos requeridos en el Reclamo seleccionado.'
            });

            // this.addButton('diagrama_gantt',{
            //     grupo:[0,1,2,3,4,5],
            //     text:'Gant',
            //     iconCls: 'bgantt',
            //     disabled:true,
            //     handler:this.diagramGantt,
            //     tooltip: '<b>Diagrama Gantt de proceso macro</b>'
            // });

            /*this.addButton('form400',{
                grupo:[0,1,2,3,4,5],
                text :'Form. 400',
                iconCls : 'bballot',
                disabled: false,
                handler : this.reporteForm400,
                tooltip : '<b>Procesos Pendientes</b><br/>Reporte que muestra los procesos pendientes del formulario 400.'
            });*/
            //this.cmbAux.on('select',this.capturarFiltros, this);

            this.tbar.addField(this.cmbAux);
            this.tbar.addField(this.fecha_inicio);
            this.tbar.addField(this.fecha_fin);


            this.fecha_fin.on('select',function(value){
                this.capturarEventos();
            },this);



            this.cmbAux.on('select',function(value){
               if (this.fecha_fin.getValue()!='') {
                 this.capturarEventos();
               }
            },this);

        },

        loadCheckDocumentosRecWf: function() {
            var rec=this.sm.getSelected();
            var that=this;
            Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
                'Chequear documento del WF',
                {
                    width:'90%',
                    height:500,
                    onDestroy: function() {
                        this.fireEvent('closepanel',this);

                  			if (this.window) {
                  					this.window.destroy();
                  			}
                  			if (this.form) {
                  					this.form.destroy();
                  			}
                  			Phx.CP.destroyPage(this.idContenedor);
                        that.reload();


                  	},
                },
                rec.data,
                this.idContenedor,
                'DocumentoWf'
            )
        },

        // diagramGantt: function() {
        //     var data=this.sm.getSelected().data.id_proceso_wf;
        //     //Phx.CP.loadingShow();
        //     Ext.Ajax.request({
        //         url:'../../sis_workflow/control/ProcesoWf/diagramaGanttTramite',
        //         params:{'id_proceso_wf':data},
        //         success:this.successExport,
        //         failure: this.conexionFailure,
        //         timeout:this.timeout,
        //         scope:this
        //     });
        // },

        // cmbTipo: new Ext.form.ComboBox({
        //     name: 'origen',
        //     //fieldLabel: 'Origen',
        //     allowBlank: true,
        //     anchor: '80%',
        //     gwidth: 100,
        //     maxLength: 25,
        //     typeAhead:true,
        //     forceSelection: true,
        //     triggerAction:'all',
        //     mode:'local',
        //     store:[ 'Todos','Proceso']
        // }),

        capturarEventos: function () {
            this.store.baseParams.fecha_inicio=this.fecha_inicio.getValue();
            this.store.baseParams.fecha_fin=this.fecha_fin.getValue();
            this.store.baseParams.id_funcionario=this.cmbAux.getValue();
            this.load({params:{start:0, limit:50}});
        },

        Atributos:[
            {
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_proceso_wf'
                },
                type:'Field',
                form:false
            },
            {
                config:{
                    name: 'nro_tramite',
                    fieldLabel: 'Nro. Tramite',
                    allowBlank: true,
                    anchor: '50%',
                    gwidth: 150,
                    maxLength:20,
                    readOnly:true,
                    // renderer: function(value,p,record) {
                    //     return String.format('<b><font color="green">{0}</font></b>', value);
                    // }
                },
                type:'TextField',
                bottom_filter: true,
                filters:{pfiltro:'dat.nro_tramite',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },

            {
                config: {
                    name: 'fecha_po',
                    fieldLabel: 'Fecha PO/EO/REP',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'nro_po',
                    fieldLabel: 'Nro. PO/EO/REP',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'rotulo_comercial',
                    fieldLabel: 'Proveedor Adjudicado',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 250,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'monto_total_adjudicado',
                    fieldLabel: 'Total Adjudicado M/E',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    galign:'right',
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'fecha_publicacion_cuce',
                    fieldLabel: 'Fecha CUCE',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 80,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'cuce',
                    fieldLabel: 'Nro. CUCE',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'nro_confirmacion',
                    fieldLabel: 'Nro. Confirmación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'fecha_pac',
                    fieldLabel: 'Fecha PAC',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 80,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'nro_pac',
                    fieldLabel: 'Nro. PAC',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'objeto_contratacion',
                    fieldLabel: 'Objeto de Contratación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 250,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'fecha_3008',
                    fieldLabel: 'Fecha 3008',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 80,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'encargado_abastecimiento',
                    fieldLabel: 'Encargado Abastecimiento',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'encargado_adquicisiones',
                    fieldLabel: 'Encargado Adquisiciones',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    // format: 'd/m/Y H:i',
                    // renderer: function (value, p, record) {
                    //     return value ? value.dateFormat('d/m/Y') : ''
                    // }
                },
                type: 'TextField',
                //filters: {pfiltro: 'ts.fecha_aprob', type: 'date'},
                id_grupo: 0,
                grid: true,
                form: true
            },

            {
                config:{
                    name: 'chequeado',
                    fieldLabel: 'Formulario 400',
                    allowBlank: true,
                    anchor: '50%',
                    height: 80,
                    gwidth: 230,
                    maxLength:100,
                    renderer:function (value, p, record){
                        if(record.data.chequeado == 'si')
                            return String.format('{0}', '<div style="text-align:center;color: green; font-size:12px;"><b>TIENE FORMULARIO 400  <i style="font-size:20px;" class="fa fa-download" aria-hidden="true"></i></b></div>');
                        else
                            return String.format('{0}', '<div style="text-align:center;color: RED; font-size:12px;"><b>NO TIENE FORMULARIO 400  <i style="font-size:20px;" class="fa fa-upload" aria-hidden="true"></i></b></div>');
                    }
                },
                type:'TextField',
                filters:{pfiltro:'chequeado',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },

            {
                config:{
                    name: 'chequeado',
                    fieldLabel: 'Formulario 500',
                    allowBlank: true,
                    anchor: '50%',
                    height: 80,
                    gwidth: 230,
                    maxLength:100,
                    renderer:function (value, p, record){
                        if(record.data.chequeado == 'si')
                            return String.format('{0}', '<div style="text-align:center;color: green; font-size:12px;"><b>TIENE FORMULARIO 500  <i style="font-size:20px;" class="fa fa-download" aria-hidden="true"></i></b></div>');
                        else
                            return String.format('{0}', '<div style="text-align:center;color: RED; font-size:12px;"><b>NO TIENE FORMULARIO 500  <i style="font-size:20px;" class="fa fa-upload" aria-hidden="true"></i></b></div>');
                    }
                },
                type:'TextField',
                filters:{pfiltro:'chequeado',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            },


        ],
        tam_pag:50,
        title:'ConsultaForm400_500',
        id_store:'id_proceso_compra',
        fields: [

            {name:'nro_tramite', type: 'string'},
            {name:'fecha_po', type: 'string'},
            {name:'nro_po', type: 'string'},
            {name:'chequeado', type: 'string'},
            {name:'id_solicitud', type: 'numeric'},
            {name:'id_proceso_wf', type: 'numeric'},
            {name:'id_encargado_abastecimiento', type: 'numeric'},
            {name:'encargado_abastecimiento', type: 'string'},
            {name:'id_encargado_adquicisiones', type: 'numeric'},
            {name:'encargado_adquicisiones', type: 'string'},
            {name:'rotulo_comercial', type: 'string'},
            {name:'monto_total_adjudicado', type: 'numeric'},

            {name:'fecha_publicacion_cuce', type: 'string'},
            {name:'cuce', type: 'string'},
            {name:'nro_confirmacion', type: 'string'},
            {name:'fecha_pac', type: 'string'},
            {name:'nro_pac', type: 'string'},
            {name:'objeto_contratacion', type: 'string'},
            {name:'fecha_3008', type: 'string'},

        ],
        sortInfo:{
            field: 'fecha_po',
            direction: 'ASC'
        },
        bsave:false,
        btest: false,
        bgantt:true,
        preparaMenu: function(n)
        {	var rec = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.ConsultaForm400_500.superclass.preparaMenu.call(this,n);
            //this.getBoton('diagrama_gantt').enable();

            this.getBoton('btnChequeoDocumentosWf').setDisabled(false);

        },

        liberaMenu:function(){
            var tb = Phx.vista.ConsultaForm400_500.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('btnChequeoDocumentosWf').setDisabled(true);
              //  this.getBoton('diagrama_gantt').disable();

            }
            return tb
        },

        fecha_inicio : new Ext.form.DateField({
            name: 'fecha_inicio',
            grupo: [0,1,2,3,4,5],
            fieldLabel: 'Fecha Inicio',
            emptyText:'Fecha Inicio',
            anchor: '60%',
            gwidth: 100,
            format: 'd/m/Y'
        }),

        fecha_fin : new Ext.form.DateField({
            name: 'fecha_fin',
            grupo: [0,1,2,3,4,5],
            fieldLabel: 'Fecha Final',
            emptyText:'Fecha Final',
            anchor: '60%',
            gwidth: 100,
            format: 'd/m/Y'
        }),



        cmbAux :new Ext.form.ComboBox({
            name: 'id_funcionario',
            grupo: [0,1,2,3,4,5],
            hiddenName: 'id_funcionario',
            fieldLabel: 'Encargado',
            listWidth:280,
            allowBlank: true,
            store:new Ext.data.JsonStore(
                {
                    url:    '../../sis_gestion_materiales/control/Solicitud/listarFuncionariosEncargados',
                    id: 'id_funcionario',
                    root:'datos',
                    sortInfo:{
                        field:'id_funcionario',
                        direction:'ASC'
                    },
                    totalProperty:'total',
                    fields: ['id_funcionario','desc_funcionario'],
                    // turn on remote sorting
                    remoteSort: true,
                    //baseParams:{par_filtro:'person.nombre_completo1', id_depto:2}
                }),
            valueField: 'id_funcionario',
            displayField: 'desc_funcionario',
            forceSelection:true,
            typeAhead: false,
            triggerAction: 'all',
            lazyRender:true,
            mode:'remote',
            pageSize:50,
            queryDelay:500,
            width:210,
            gwidth:220,
            minChars:2,
            tpl: '<tpl for="."><div class="x-combo-list-item"><p><b>{desc_funcionario}</b></p></div></tpl>'

        }),

    });
</script>
