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
    Phx.vista.Rubro=Ext.extend(Phx.gridInterfaz,{

        constructor:function(config){
            this.maestro=config.maestro;
            //llama al constructor de la clase padre
            Phx.vista.Rubro.superclass.constructor.call(this,config);
            //this.grid.getTopToolbar().disable();
            //this.grid.getBottomToolbar().disable();
            this.load({params:{start:0, limit:this.tam_pag}});
            this.init();
            //this.iniciarEventos();
        },
        Atributos:[
            {
                //configuracion del componente
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_rubro'
                },
                type:'Field',
                form:true
            },
            {
                config:{
                    name: 'nombre',
                    fieldLabel: 'Nombre Rubro',
                    allowBlank: true,
                    anchor: '100%',
                    gwidth: 100,
                    maxLength:10
                },
                type:'TextField',
                filters:{pfiltro:'rub.nombre',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
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
                filters: {pfiltro: 'rub.descripcion', type: 'string'},
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
                filters:{pfiltro:'rub.estado_reg',type:'string'},
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
                filters:{pfiltro:'rub.fecha_reg',type:'date'},
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
                filters:{pfiltro:'rub.fecha_mod',type:'date'},
                id_grupo:1,
                grid:true,
                form:false
            }
        ],
        title:'Proveedor Rubro',
        ActSave:'../../sis_gestion_materiales/control/Rubro/insertarRubro',
        ActDel:'../../sis_gestion_materiales/control/Rubro/eliminarRubro',
        ActList:'../../sis_gestion_materiales/control/Rubro/listarRubro',
        id_store:'id_rubro',

        fields: [
            {name:'id_rubro', type: 'numeric'},
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
        btest:false
    });
</script>

