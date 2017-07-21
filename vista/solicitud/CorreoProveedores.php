<?php
/**
 *@package pXP
 *@file    SolModPresupuesto.php
 *@author  Rensi Arteaga Copari
 *@date    30-01-2014
 *@description permites subir archivos a la tabla de documento_sol
 */
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
    Phx.vista.CorreoProveedores=Ext.extend(Phx.frmInterfaz,{
        ActSave:'../../sis_gestion_materiales/control/Solicitud/setCorreosCotizacion',

        constructor:function(config){

            this.maestro = config;
            Phx.vista.CorreoProveedores.superclass.constructor.call(this,config);
            this.init();
            console.log('maestro correo',this.maestro);
            this.id_solicitud = this.maestro.id_solicitud;

            //var rec = Phx.CP.getPagina(this.idContenedorPadre).getSelectedData();

            this.Cmp.lista_correos.setValue(this.maestro.lista_correos);
        },

        Atributos:[
            {
                config:{
                    labelSeparator: '',
                    name: 'id_solicitud',
                    inputType:'hidden'
                    ///value:  this.maestro.id_solicitud
                },
                type:'Field',
                form:true
            },
            {
                config: {
                    tinit: false,
                   
                    tasignacion: false,
                    
                    name: 'lista_correos',
                    fieldLabel: 'Lista de Posibles Proveedores',
                    allowBlank: true,
                    emptyText: 'Proveedor...',
                    anchor: '100%',
                    store: new Ext.data.JsonStore({

                        url: '../../sis_gestion_materiales/control/Solicitud/listarProveedor',
                        id: 'id_proveedor',
                        root: 'datos',
                        sortInfo:{
                            field: 'rotulo_comercial',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_proveedor','desc_proveedor','codigo','nit','rotulo_comercial','lugar','email'],
                        // turn on remote sorting
                        remoteSort: true,
                        baseParams:{par_filtro:'desc_proveedor#codigo#nit#rotulo_comercial',tipo:'abastecimiento'}

                    }),
                    valueField: 'id_proveedor',
                    displayField: 'desc_proveedor',
                    //tpl:'<tpl for="."><div class="x-combo-list-item"><div class="awesomecombo-item {checked}"><b>{rotulo_comercial}</b></div></div></tpl>',
                    tpl: new Ext.XTemplate([
                        '<tpl for=".">',
                        '<div class="x-combo-list-item">',
                        '<div class="awesomecombo-item {checked}">',
                        '<p><b>Prov: {rotulo_comercial}</b></p>',
                        '</div><p><b>Email:</b> <span style="color: green;">{email}</span></p>',
                        '</div></tpl>'
                    ]),
                    hiddenName: 'id_proveedor',
                    forceSelection:true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode:'remote',
                    pageSize:20,
                    queryDelay:1000,
                    minChars:2,
                    listWidth:'395',
                    resizable:true,
                    enableMultiSelect: true
                },
                type: 'AwesomeCombo',
                id_grupo: 0,
                grid: true,
                form: true
            }
        ],
        title:'EnviarCorreo de Cotización a Proveedores',

        fields: [
            {name:'id_solicitud', type: 'numeric'},
            {name:'lista_correos', type: 'varchar'}
        ],

        onSubmit:function(o){
            this.Cmp.id_solicitud.setValue(this.maestro.id_solicitud);

            if(this.Cmp.lista_correos.getValue()==''){
                Ext.Msg.show({
                    title: 'Información',
                    msg: '<b>Estimado usuario no ha elegido ningun proveedor,  para enviar el documento de detalle de su cotización, seleccion por lo menos un proveedor.</b>',
                    buttons: Ext.Msg.OK,
                    width: 512,
                    icon: Ext.Msg.INFO
                });
            }else{

                //Phx.CP.getPagina(this.idContenedorPadre).reload();

                Phx.vista.CorreoProveedores.superclass.onSubmit.call(this,o);

            }
        },

        successSave:function(resp){
            Phx.CP.loadingHide();
            Phx.CP.getPagina(this.idContenedorPadre).reload();
            this.close();
            //console.log('tigre',Phx.CP.getPagina(this.idContenedorPadre));
            //Phx.CP.getPagina(this.idContenedorPadre).sigEstado();


        },

        onButtonEdit: function() {
            //var rec = this.sm.getSelected();
            /*Ext.Ajax.request({
                url:'../../sis_workflow/control/ProcesoWf/diagramaGanttTramite',
                params:{'id_solicitud':rec.data.id_solicitud},
                success:function (resp) {
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });*/
            //this.Cmp.lista_correos.setValue();
        }



    })
</script>