<?php
/**
 *@package pXP
 *@file RubroProveedor.php
 *@author  (Franklin Espinoza)
 *@date 15-08-2012 18:56:19
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.RubroProveedor=Ext.extend(Phx.gridInterfaz,{

        constructor:function(config){
            this.maestro=config.maestro;
            //llama al constructor de la clase padre
            Phx.vista.RubroProveedor.superclass.constructor.call(this,config);
            //this.grid.getTopToolbar().disable();
            //this.grid.getBottomToolbar().disable();
            this.init();
            //this.iniciarEventos();
        },
        Atributos:[
            {
                //configuracion del componente
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_proveedor_rubro'
                },
                type:'Field',
                form:true
            },

            {
                //configuracion del componente
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_proveedor'
                },
                type:'Field',
                form:true
            },
            /*{
                config:{
                    name: 'nombre',
                    fieldLabel: 'Nombre Rubro',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:10
                },
                type:'TextField',
                filters:{pfiltro:'prov.nombre',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },*/
            {
                config: {
                    name: 'nombre_rubro',
                    fieldLabel: 'Tipo de Rubro',
                    allowBlank: false,
                    emptyText: 'Elija una opción...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_gestion_materiales/control/Rubro/listarRubro',
                        id: 'id_rubro',
                        root: 'datos',
                        sortInfo: {
                            field: 'nombre',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_rubro', 'nombre','descripcion'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'rub.nombre'}
                    }),
                    valueField: 'id_rubro',
                    displayField: 'nombre',
                    gdisplayField: 'nombre_rubro',
                    hiddenName: 'id_rubro',
                    forceSelection: true,
                    typeAhead: false,
                    editable: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    anchor: '100%',
                    /*width: 200,*/
                    gwidth: 150,
                    minChars: 2,
                    resizable:true,
                    listWidth:'240',
                    renderer: function (value, p, record) {
                        return String.format('{0}', record.data['nombre_rubro']);
                    }
                },

                type: 'AwesomeCombo',
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'descripcion',
                    fieldLabel: 'Descripción Rubro',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength: 100000
                },
                type: 'TextArea',
                bottom_filter:true,
                filters: {pfiltro: 'prov.descripcion', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config:{
                    name: 'estado_reg',
                    fieldLabel: 'Estado Reg.',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:10
                },
                type:'TextField',
                filters:{pfiltro:'pritse.estado_reg',type:'string'},
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
                type:'NumberField',
                filters:{pfiltro:'usu1.cuenta',type:'string'},
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'fecha_reg',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y h:i:s'):''}
                },
                type:'DateField',
                filters:{pfiltro:'pritse.fecha_reg',type:'date'},
                id_grupo:1,
                grid:true,
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
                type:'NumberField',
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
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y h:i:s'):''}
                },
                type:'DateField',
                filters:{pfiltro:'pritse.fecha_mod',type:'date'},
                id_grupo:1,
                grid:true,
                form:false
            }
        ],
        title:'Proveedor Rubro',
        ActSave:'../../sis_parametros/control/ProveedorItemServicio/insertarProveedorItemServicio',
        /*ActDel:'../../sis_parametros/control/ProveedorItemServicio/eliminarProveedorItemServicio',
        ActList:'../../sis_parametros/control/ProveedorItemServicio/listarProveedorItemServicio',
        id_store:'id_rubro',*/

        loadValoresIniciales:function()
        {
            Phx.vista.RubroProveedor.superclass.loadValoresIniciales.call(this);
            this.getComponente('id_proveedor').setValue(this.maestro.id_proveedor);
        },

        onReloadPage:function(m)
        {
            console.log('master',m);
            this.maestro=m;
            this.store.baseParams={id_proveedor:this.maestro.id_proveedor};
            this.load({params:{start:0, limit:50}});
        },
        fields: [
            {name:'id_proveedor_rubro', type: 'numeric'},
            {name:'estado_reg', type: 'string'},
            {name:'id_usuario_reg', type: 'numeric'},
            {name:'fecha_reg', type: 'date', dateFormat:'Y-m-d H:i:s'},
            {name:'id_usuario_mod', type: 'numeric'},
            {name:'fecha_mod', type: 'date', dateFormat:'Y-m-d H:i:s'},
            {name:'usr_reg', type: 'string'},
            {name:'usr_mod', type: 'string'},
            {name:'nombre', type: 'string'},
            {name:'descripcion', type: 'string'}

        ],
        sortInfo:{
            field: 'id_rubro',
            direction: 'DESC'
        },
        bdel:true,
        bsave:false,
        fwidth: 400,
        fheight: 300,
        //function tha enable/disable combobox of items or services
        /*enableDisable: function(val){
            var cmbIt = this.getComponente('id_item');
            var cmbServ = this.getComponente('id_servicio');
            if(val=='item'){
                cmbIt.enable();
                cmbServ.disable();
                cmbIt.allowBlank = false;
                cmbServ.allowBlank = true;
            } else{
                cmbServ.enable();
                cmbIt.disable();
                cmbIt.allowBlank = true;
                cmbServ.allowBlank = false;
            }

        },
        iniciarEventos: function(){
            //Adding a listener to component item_servicio
            var rbtItSer = this.getComponente('item_servicio');
            rbtItSer.on('change',function(groupRadio,radio){
                this.enableDisable(radio.inputValue);
            },this);
        },
        onButtonEdit:function(){
            var datos=this.sm.getSelected().data;
            var rbtItServ = this.getComponente('item_servicio');
            var cmbIt = this.getComponente('id_item');
            var cmbServ = this.getComponente('id_servicio');

            Phx.vista.ProveedorItemServicio.superclass.onButtonEdit.call(this); //sobrecarga enable select

            //Set the visible value of field item_servicio
            this.enableDisable(datos.item_servicio);

        },
        agregarArgsExtraSubmit: function(){
            //Inicializa el objeto de los argumentos extra
            this.argumentExtraSubmit={};

            //Obtiene los valores dinámicos
            var rbtItSer = this.getComponente('item_servicio');
            var array = [];
            var tmp;
            array = rbtItSer.items;

            //Evaluate the radio checked and asign a valur for the variable
            if(array.items){
                if(array.items[0].checked){
                    tmp='item';
                } else{
                    tmp='servicio';
                }

                //Añade los parámetros extra para mandar por submit
                this.argumentExtraSubmit.item_servicio=tmp;
            }
        },*/


        btest:true
    });
</script>

