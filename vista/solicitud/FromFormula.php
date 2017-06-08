<?php
/**
 *@package pXP
 *@file    FormFrumla.php
 *@author  MMV
 *@date    21/12/2016
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.FromFormula=Ext.extend(Phx.frmInterfaz,{

        ActSave:'../../sis_gestion_materiales/control/Solicitud/insertarSolicitudCompleta',
        tam_pag: 10,
        layout: 'fit',
        autoScroll: false,
        breset: false,
        constructor:function(config)
        {
            this.addEvents('beforesave');
            this.addEvents('successsave');
            this.buildComponentesDetalle();
            this.buildDetailGrid();
            this.buildGrupos();
            Phx.vista.FromFormula.superclass.constructor.call(this,config);
            this.init();
            this.onNew();
            this.iniciarEventos();
        },
        buildComponentesDetalle: function () {

            this.detCmp =
                {
                    'nro_parte': new Ext.form.TextField({
                        name: 'nro_parte',
                        msgTarget: 'title',
                        fieldLabel: 'Nro. Partes',
                        allowBlank: false,
                        anchor: '90%',
                        maxLength:50

                    }),
                    'nro_parte_alterno': new Ext.form.TextField({
                        name: 'nro_parte_alterno',
                        msgTarget: 'title',
                        fieldLabel: 'Nro. Parte alterno',
                        allowBlank: false,
                        anchor: '90%',
                        maxLength:50
                    }),
                    'referencia': new Ext.form.TextField({
                        name: 'referencia',
                        msgTarget: 'title',
                        fieldLabel: 'Referencia',
                        allowBlank: false,
                        anchor: '90%',
                        maxLength:50
                    }),

                    'descripcion': new Ext.form.TextField({
                        name: 'descripcion',
                        msgTarget: 'title',
                        fieldLabel: 'Descripcion',
                        allowBlank: false,
                        anchor: '80%',
                        maxLength:5000
                    }),

                    'tipo': new Ext.form.ComboBox({

                        name:'tipo',
                        fieldLabel:'Tipo ',
                        allowBlank:false,
                        emptyText:'Elija una opción...',
                        typeAhead: true,
                        triggerAction: 'all',
                        lazyRender: true,
                        lazyRender:true,
                        mode: 'local',
                        anchor: '50%',
                        store:['Consumible','Rotable'],
                        maxLength: 100
                    }),

                    'cantidad_sol': new Ext.form.TextField({
                        name: 'cantidad_sol',
                        msgTarget: 'title',
                        currencyChar:' ',
                        fieldLabel: 'Cantidad',
                        minValue: 0.0001,
                        allowBlank: false,
                        allowDecimals: true,
                        allowNegative:false,
                        decimalPrecision:2
                    }),
                    'id_unidad_medida': new Ext.form.ComboBox({
                        name: 'id_unidad_medida',
                        fieldLabel: 'Unidad de medida',
                        allowBlank: false,
                        emptyText: 'U/M..',
                        store: new Ext.data.JsonStore({
                            url: '../../sis_gestion_materiales/control/DetalleSol/unidadMedia',
                            id: 'id_unidad_medida',
                            root: 'datos',
                            sortInfo: {
                                field: 'codigo',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_unidad_medida','codigo','descripcion','tipo_unidad_medida'],
                            remoteSort: true,
                            baseParams: {par_filtro: ' un.codigo# un.descripcion'}
                        }),
                        valueField: 'id_unidad_medida',
                        displayField: 'codigo',
                        gdisplayField: 'codigo',
                        hiddenName: 'id_unidad_medida',
                        forceSelection: true,
                        typeAhead: false,
                        triggerAction: 'all',
                        listWidth:95,
                        lazyRender: true,
                        resizable:true,
                        mode: 'remote',
                        pageSize: 50,
                        queryDelay: 100,
                        anchor: '40%',
                        gwidth: 10,
                        minChars: 2,
                        tpl:'<tpl for="."><div class="x-combo-list-item"><p>{codigo}</p><p style="color: blue">{descripcion}</p></div></tpl>'

                    })
                }
        },
        arrayStore :{
            'Gerencia de Operaciones':[
                ['Gerencia de Operaciones'],

            ],
            'Gerencia de Mantenimiento':[
                ['Gerencia de Mantenimiento'],
            ]
        },

        iniciarEventos : function () {

            this.Cmp.fecha_solicitud.on('change',function(f){
                Phx.CP.loadingShow();
                this.obtenerGestion(this.Cmp.fecha_solicitud);
                this.Cmp.id_funcionario_sol.reset();
                this.Cmp.id_funcionario_sol.enable();
                this.Cmp.id_funcionario_sol.store.baseParams.fecha = this.Cmp.fecha_solicitud.getValue().dateFormat(this.Cmp.fecha_solicitud.format);

            },this);

            this.mostrarComponente(this.Cmp.id_funcionario_sol);
            this.Cmp.id_funcionario_sol.reset();


            this.Cmp.origen_pedido.on('select',function(cmp,rec){

                //identificamos si es un bien o un servicio
                if(this.isInArray(rec.json, this.arrayStore['Gerencia de Operaciones'])){
                    this.Cmp.origen_pedido.setValue('Gerencia de Operaciones');
                }
                if(this.Cmp.origen_pedido.getValue() == 'Gerencia de Operaciones'){
                     this.ocultarComponente(this.Cmp.mel);
                     this.ocultarComponente(this.Cmp.tipo_reporte);
                     this.ocultarComponente(this.Cmp.tipo_falla);
                     this.ocultarComponente(this.Cmp.nro_justificacion);
                    this.mostrarComponente(this.Cmp.id_matricula);
                    this.Cmp.nro_justificacion.reset();
                 }

                 if(this.isInArray(rec.json, this.arrayStore['Gerencia de Mantenimiento'])){
                    this.Cmp.origen_pedido.setValue('Gerencia de Mantenimiento');
                }
                if(this.Cmp.origen_pedido.getValue() == 'Gerencia de Mantenimiento'){
                    this.mostrarComponente(this.Cmp.mel);
                    this.mostrarComponente(this.Cmp.tipo_reporte);
                    this.mostrarComponente(this.Cmp.tipo_falla);
                    this.ocultarComponente(this.Cmp.nro_justificacion);
                    this.mostrarComponente(this.Cmp.justificacion);
                    this.mostrarComponente(this.Cmp.id_matricula);
                    this.Cmp.nro_justificacion.reset();

                }
                if(this.isInArray(rec.json, this.arrayStore['Almacenes Consumibles o Rotables'])){
                    this.Cmp.origen_pedido.setValue('Almacenes Consumibles o Rotables');
                }
                if(this.Cmp.origen_pedido.getValue() == 'Almacenes Consumibles o Rotables'){
                    this.ocultarComponente(this.Cmp.mel);
                    this.ocultarComponente(this.Cmp.tipo_reporte);
                    this.ocultarComponente(this.Cmp.tipo_falla);
                    this.ocultarComponente(this.Cmp.nro_justificacion);
                    this.ocultarComponente(this.Cmp.justificacion);
                    this.ocultarComponente(this.Cmp.id_matricula);
                    this.Cmp.id_matricula.setValue(null);

                }
                this.Cmp.nro_justificacion.reset();
            },this);

            this.ocultarComponente(this.Cmp.mel);
            this.ocultarComponente(this.Cmp.tipo_reporte);
            this.ocultarComponente(this.Cmp.tipo_falla);
            this.ocultarComponente(this.Cmp.nro_justificacion);

            this.Cmp.justificacion.on('select',function(cmp,rec){
                if(this.Cmp.justificacion.getValue() == 'Task Card'){
                    this.mostrarComponente(this.Cmp.nro_justificacion);
                }
               else if(this.Cmp.justificacion.getValue() == 'Directriz de Aeronavegabilidad'){
                    this.mostrarComponente(this.Cmp.nro_justificacion);
                }
               else if(this.Cmp.justificacion.getValue() == 'Boletín de Servicio'){
                    this.mostrarComponente(this.Cmp.nro_justificacion);
                }
                else{
                    this.ocultarComponente(this.Cmp.nro_justificacion);
                }
            },this);


        },
        evaluaRequistos: function(){
            //valida que todos los requistosprevios esten completos y habilita la adicion en el grid
            var i = 0;
            sw = true;
            while( i < this.Componentes.length) {
                console.log('componetes '+this.Componentes[i].isValid());
                if(!this.Componentes[i].isValid()){
                    sw = false;
                }
                i++;
            }
            return sw
        },
        bloqueaRequisitos: function(sw){

            this.Cmp.id_funcionario_sol.setDisabled(sw);
        },
        evaluaGrilla: function(){
            //al eliminar si no quedan registros en la grilla desbloquea los requisitos en el maestro
            var  count = this.mestore.getCount();
            if(count == 0){
                this.bloqueaRequisitos(false);
            }
        },
        obtenerGestion:function(x){
            var fecha = x.getValue().dateFormat(x.format);
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_parametros/control/Gestion/obtenerGestionByFecha',
                params:{fecha:fecha},
                success:this.successGestion,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },
        successGestion:function(resp){
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            if(!reg.ROOT.error){

                this.Cmp.id_gestion.setValue(reg.ROOT.datos.id_gestion);


            }else{

                alert('ocurrio al obtener la gestion')
            }
        },
        onNew: function(){

            this.mostrarComponente(this.Cmp.id_funcionario_sol);
            this.Cmp.id_funcionario_sol.reset();
            this.Cmp.fecha_solicitud.enable();
            this.Cmp.id_funcionario_sol.disable();
            this.Cmp.fecha_solicitud.setValue(new Date());
            this.Cmp.fecha_solicitud.fireEvent('change');

            Ext.Ajax.request({
                url:'../../sis_gestion_materiales/control/Solicitud/getDatos',
                params:{id_usuario: 0},
                success:function(resp){
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                    console.log('funcionarios',reg);
                    this.Cmp.id_funcionario_sol.setValue(reg.ROOT.datos.id_funcionario);
                    this.Cmp.id_funcionario_sol.setRawValue(reg.ROOT.datos.nombre_completo1);
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });


        },


        onInitAdd: function(){

        },
        onCancelAdd: function(re,save){
            if(this.sw_init_add){
                this.mestore.remove(this.mestore.getAt(0));
            }
            this.sw_init_add = false;
           this.evaluaGrilla();

        },
        onUpdateRegister: function(){
            this.sw_init_add = false;
        },

        onAfterEdit:function(re, o, rec, num){
            //set descriptins values ...  in combos boxs

            var cmb_rec = this.detCmp['id_unidad_medida'].store.getById(rec.get('id_unidad_medida'));
            if(cmb_rec) {

                rec.set('codigo', cmb_rec.get('codigo'));
            }

        },
        buildDetailGrid:function () {
            var Items = Ext.data.Record.create([{
                name: 'cantidad_sol',
                type: 'int'
            }
            ]);
            this.mestore = new Ext.data.JsonStore({
                url: '../../sis_gestion_materiales/control/DetalleSol/listarDetalleSol',
                id: 'id_detalle',
                root: 'datos',
                totalProperty: 'total',
                fields: ['id_detalle','id_solicitud','precio', 'cantidad_sol',
                    'id_unidad_medida','descripcion','nro_parte_alterno','moneda','referencia',
                    'nro_parte','codigo','desc_descripcion','tipo'
                ],remoteSort: true,
                baseParams: {dir:'ASC',sort:'id_detalle',limit:'100',start:'0'}
            });

            this.editorDetaille = new Ext.ux.grid.RowEditor({
                saveText: 'Aceptar',
                name: 'btn_editor'

            });

            // al iniciar la edicion
            this.editorDetaille.on('beforeedit', this.onInitAdd , this);

            //al cancelar la edicion
            this.editorDetaille.on('canceledit', this.onCancelAdd , this);

            //al cancelar la edicion
            this.editorDetaille.on('validateedit', this.onUpdateRegister, this);

            this.editorDetaille.on('afteredit', this.onAfterEdit, this);

            this.megrid = new Ext.grid.GridPanel({
                layout: 'fit',
                store:  this.mestore,
                region: 'center',
                split: true,
                border: false,
                plain: true,
                plugins: [ this.editorDetaille],
                stripeRows: true,
                tbar: [{
                    text: '<i class="fa fa-plus-circle fa-lg"></i> Agregar ',
                    scope: this,
                    width: '100',
                    handler: function(){
                      if(this.evaluaRequistos() === true) {
                            var e = new Items({
                                cantidad_sol: 1
                            });
                            this.editorDetaille.stopEditing();
                            this.mestore.insert(0, e);
                            this.megrid.getView().refresh();
                            this.megrid.getSelectionModel().selectRow(0);
                            this.editorDetaille.startEditing(0);
                            this.sw_init_add = true;
                            this.bloqueaRequisitos(true);
                        }
                      else{
                          //alert('Verifique los requisitos');
                      }
                    }
                },{
                    ref: '../removeBtn',
                    text: '<i class="fa fa-trash fa-lg"></i> Eliminar',
                    scope:this,
                    handler: function(){
                        this.editorDetaille.stopEditing();
                        var s = this.megrid.getSelectionModel().getSelections();
                        for(var i = 0, r; r = s[i]; i++){
                            this.mestore.remove(r);
                        }
                        this.evaluaGrilla();
                    }
                }],

                columns: [
                    new Ext.grid.RowNumberer(),
                    {
                        header: 'Nro. Parte',
                        dataIndex: 'nro_parte',
                        align: 'center',
                        width: 165,
                        editor: this.detCmp.nro_parte
                    },
                    {
                        header: 'Nro. Parte Alterno',
                        dataIndex: 'nro_parte_alterno',
                        align: 'center',
                        width: 165,
                        editor: this.detCmp.nro_parte_alterno
                    },
                    {
                        header: 'Referencia',
                        dataIndex: 'referencia',
                        align: 'center',
                        width: 165,
                        editor: this.detCmp.referencia
                    },
                    {
                        header: 'Descripcion',
                        dataIndex: 'descripcion',
                        align: 'center',
                        width: 180,
                        editor: this.detCmp.descripcion
                    },
                    {
                        header: 'Tipo',
                        dataIndex: 'tipo',
                        align: 'center',
                        width: 95,
                        editor: this.detCmp.tipo
                    },
                    {
                        header: 'Cantidad',
                        dataIndex: 'cantidad_sol',
                        align: 'center',
                        width: 50,
                        editor: this.detCmp.cantidad_sol
                    },
                   {
                        header: 'U/M',
                        dataIndex: 'id_unidad_medida',
                       align: 'center',
                        width: 95,
                       renderer:function(value, p, record){return String.format('{0}', record.data['codigo']);},
                        editor: this.detCmp.id_unidad_medida
                    }
                    /*{
                     header: 'Presio',
                     dataIndex: 'precio',
                     align: 'center',
                     width: 50,
                     editor: this.detCmp.precio
                     },
                     {
                     header: 'Moneda',
                     dataIndex: 'moneda',
                     align: 'center',
                     width: 50,
                     editor: this.detCmp.moneda
                     }*/

                ]
            });

        },
        buildGrupos: function () {

            this.Grupos = [{
                layout: 'border',
                border: false,
                frame:true,
                items:[
                    {
                        xtype: 'fieldset',
                        border: false,
                        split: true,
                        layout: 'column',
                        region: 'north',
                        autoScroll: true,
                        autoHeight: true,
                        collapseFirst : false,
                        collapsible: true,
                        width: '100%',
                        //autoHeight: true,
                        padding: '0 0 0 10',
                        items:[
                            {
                                bodyStyle: 'padding-right:5px;',

                                autoHeight: true,
                                border: false,
                                items:[
                                    {
                                        xtype: 'fieldset',
                                        frame: true,
                                        border: false,
                                        layout: 'form',
                                        title: ' Datos Generales ',
                                        width: '33%',
                                        padding: '0 0 0 10',
                                        bodyStyle: 'padding-left:5px;',
                                        id_grupo: 0,
                                        items: [],
                                    }]
                            },
                            {
                                bodyStyle: 'padding-right:5px;',

                                border: false,
                                autoHeight: true,
                                items: [{
                                    xtype: 'fieldset',
                                    frame: true,
                                    layout: 'form',
                                    title: ' Justificacion de Necesidad ',
                                    width: '33%',
                                    border: false,
                                    padding: '0 0 0 10',
                                    bodyStyle: 'padding-left:5px;',
                                    id_grupo: 1,
                                    items: [],
                                }]
                            },

                        ]
                    },
                    this.megrid
                ]
            }];
        },
        successSave:function(resp)
        {
            Phx.CP.loadingHide();
            Phx.CP.getPagina(this.idContenedorPadre).reload();
            this.panel.close();
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
                    name:'origen_pedido',
                    fieldLabel:'Origen Pedido',
                    allowBlank:false,
                    editable: false,
                    emptyText:'Elija una opción...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    anchor: '105%',
                    store:['Gerencia de Operaciones','Gerencia de Mantenimiento','Almacenes Consumibles o Rotables']

                },
                type:'ComboBox',
                id_grupo:0,
                form:true

            },
            {
                config: {
                    name: 'id_funcionario_sol',
                    fieldLabel: 'Funcionario Solicitante',
                    allowBlank: false,
                    emptyText: 'Elija una opción...',
                    qtip:'Funcionario que registra el Reclamo en el ERP, se rellena por Defecto.',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
                        id: 'id_funcionario',
                        root: 'datos',
                        sortInfo: {
                            field: 'desc_funcionario1',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_funcionario','desc_funcionario1','email_empresa','nombre_cargo','lugar_nombre','oficina_nombre'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'FUNCAR.desc_funcionario1'}//#FUNCAR.nombre_cargo
                    }),
                    valueField: 'id_funcionario',
                    displayField: 'desc_funcionario1',
                    hiddenName: 'id_funcionario_recepcion',
                    tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_funcionario1}</p><p style="color: green">{nombre_cargo}<br>{email_empresa}</p><p style="color:green">{oficina_nombre} - {lugar_nombre}</p></div></tpl>',
                    istWidth:'280',
                    forceSelection:true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode:'remote',
                    pageSize:20,
                    queryDelay:500,
                    minChars:2,
                    anchor: '105%',
                    gwidth: 230

                },
                type: 'ComboBox',
                id_grupo: 4,
                form: true
            },
            {
                config:{
                    name: 'fecha_solicitud',
                    fieldLabel: 'Fecha Solicitud',
                    qtip: 'Según esta fecha se escoje el formulario de solicitud',
                    readOnly : true,
                    allowBlank: false,
                    gwidth: 100,
                    format: 'd/m/Y'
                },
                type: 'DateField',
                id_grupo: 0,
                form: true
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
                    hiddenName: 'id_matricula',
                    tpl:'<tpl for="."><div class="x-combo-list-item"><p>{desc_orden}</p><p style="color: blue">{matricula}</p></div></tpl>',
                    istWidth:'280',
                    forceSelection:true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode:'remote',
                    pageSize:1000,
                    queryDelay:500,
                    minChars:2,
                    anchor: '105%',
                    gwidth: 230
                },
                type: 'ComboBox',
                id_grupo: 0,
                form: true
            },
            {
                config:{
                    name: 'motivo_solicitud',
                    fieldLabel: 'Motivo Solicitud',
                    allowBlank: false,
                    anchor: '105%',
                    maxLength:10000
                },
                type:'TextArea',
                filters:{pfiltro:'sol.motivo_solicitud',type:'string'},
                id_grupo:0,
                form:true
            },
            {
                config:{
                    name: 'observaciones_sol',
                    fieldLabel: 'Observaciones',
                    allowBlank: false,
                    anchor: '105%',
                    maxLength:10000
                },
                type:'TextArea',
                filters:{pfiltro:'sol.observaciones_sol',type:'string'},
                id_grupo:0,
                form:true
            },
            {
                config:{
                    name:'justificacion',
                    fieldLabel:'Justificación ',
                    allowBlank:false,
                    editable: false,
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
                form:true

            },
            {
                config:{
                    name: 'nro_justificacion',
                    fieldLabel: 'Nro. Justificacion',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'sol.estado',type:'string'},
                id_grupo:1,
                form:true
            },

            {
                config:{
                    name:'tipo_solicitud',
                    fieldLabel:'Tipo Solicitud',
                    allowBlank:false,
                    editable: false,
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
                form:true

            },
            {
                config:{
                    name:'tipo_falla',
                    fieldLabel:'Tipo de Falla',
                    allowBlank:true,
                    emptyText:'Elija una opción...',
                    typeAhead: true,
                    editable: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    lazyRender:true,
                    mode: 'local',
                    anchor: '100%',
                    store:['Falla Confirmada','T/S en Progreso ','No Aplica']
                },
                type:'ComboBox',
                id_grupo:1,
                form:true

            },
            {
                config:{
                    name:'tipo_reporte',
                    fieldLabel:'Tipo de Reporte',
                    allowBlank:true,
                    emptyText:'Elija una opción...',
                    editable: false,
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    anchor: '100%',
                    store:['PIREPS','MAREPS','No Aplica']

                },
                type:'ComboBox',
                id_grupo:1,
                form:true

            },
            {
                config:{
                    name:'mel',
                    fieldLabel:'MEL',
                    allowBlank:true,
                    emptyText:'Elija una opción...',
                    editable: false,
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    anchor: '100%',
                    store:['A','B','C','AOG','No Aplica']

                },
                type:'ComboBox',
                id_grupo:1,
                form:true

            },
            {
                config:{
                    name: 'fecha_requerida',
                    fieldLabel: 'Fecha Requerida / Due Date',
                    allowBlank: true,
                    anchor: '95%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{pfiltro:'sol.fecha_requerida',type:'date'},
                id_grupo:1,
                form:true
            },
            {
                config:{
                    name: 'nro_no_rutina',
                    fieldLabel: 'Nro. No Rutina',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 200,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'sol.motivo_solicitud',type:'string'},
                id_grupo:1,
                form:true
            }


        ],
        title: 'Frm Materiales',
        onSubmit: function(o) {
            //  validar formularios
            var arra = [], k, me = this;
            for (k = 0; k < me.megrid.store.getCount(); k++) {
                record = me.megrid.store.getAt(k);
                arra[k] = record.data;
            }
            me.argumentExtraSubmit = { 'json_new_records': JSON.stringify(arra, function replacer(key, value) {
                return value;
            }) };
            if(this.evaluaRequistos()){
                Ext.Ajax.request({
                    url:'../../sis_gestion_materiales/control/Solicitud/compararNroJustificacion',
                    params:{
                        justificacion: this.Cmp.nro_justificacion.getValue(),
                        nro_parte: this.detCmp.nro_parte.getValue(),
                        id_matricula: this.Cmp.id_matricula.getValue()
                    },
                    success:function(resp){
                        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                        var nro_justificacion =reg.ROOT.datos.justificacion;
                        var nro_parte =reg.ROOT.datos.parte;
                        var matricula=reg.ROOT.datos.matricula;
                        var mgs_control =reg.ROOT.datos.mgs_control_duplicidad;
                        var ma =this;
                        if(ma.Cmp.nro_justificacion.getValue() == nro_justificacion && ma.Cmp.nro_justificacion.getValue() != '' || ma.detCmp.nro_parte.getValue() == nro_parte && ma.Cmp.id_matricula.getValue() == matricula){
                            Ext.Msg.confirm('DUPLICIDAD', mgs_control + ' desea continuar con el registro ', function (btn) {
                                if (btn === 'yes') {
                                    Phx.vista.FromFormula.superclass.onSubmit.call(this,o,undefined, true);
                                } else {
                                }
                            },this);
                        }else if(ma.Cmp.nro_justificacion.getValue() != nro_justificacion || ma.detCmp.nro_parte.getValue() != nro_parte && ma.Cmp.id_matricula.getValue()!=matricula){
                            Phx.vista.FromFormula.superclass.onSubmit.call(this,o,undefined, true);
                        }
                    },
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });
                if( k > 0 &&  !this.editorDetaille.isVisible()){
                }
                else{
                    this.mensaje_('ALERTA', "No tiene datos en el detalle", 'ERROR');
                    //alert("No tiene datos en el detalle")
                }
            }
        },
        mensaje_: function (titulo, mensaje, icono) {

            var tipo = 'ext-mb-warning';
            Ext.MessageBox.show({
                title: titulo,
                msg: mensaje,
                buttons: Ext.MessageBox.OK,
                icon: tipo
            })

        }
    })
</script>



