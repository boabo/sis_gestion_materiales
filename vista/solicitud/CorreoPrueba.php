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
    Phx.vista.CorreoJCorreoPruebah=Ext.extend(Phx.frmInterfaz,{
        ActSave:'../../sis_gestion_materiales/control/Solicitud/setCorreosCotizacion',

        constructor:function(config)
        {
            this.maestro = config;
            Phx.vista.CorreoPrueba.superclass.constructor.call(this,config);
            this.init();
            this.nroCotizacion = this.maestro.nro_cotizacion;
            this.Cmp.lista_correos.setValue(this.maestro.lista_correos);
            this.loadValoresIniciales();
            //this.obtenerCorreo();


        }, 

       /* obtenerCorreo:function(){
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                // form:this.form.getForm().getEl(),
                url:'../../sis_organigrama/control/Funcionario/getEmailEmpresa',
                params:{id_funcionario: this.id_funcionario},
                success:this.successSinc,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },
        successSinc:function(resp){
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            if(reg.ROOT.datos.resultado!='falla'){
                if(!reg.ROOT.datos.email_notificaciones_2){
                    alert('Confgure el EMAIL de notificaciones 1, en el archivo de datos generales');
                }
                this.getComponente('email').setValue('abastecimiento@boa.bo');
                this.getComponente('email_cc').setValue(reg.ROOT.datos.email_empresa);
            }else{
                alert(reg.ROOT.datos.mensaje)
            }
        },*/

        loadValoresIniciales:function()
        {

            var CuerpoCorreo = "<div style='font-size: 12px;font-family: Verdana;color: black'>Señores:<br><br>Favor cotizar según documento Adjunto.</div><br><br>" ;
            CuerpoCorreo+='<p><img src="../../../sis_gestion_materiales/media/abastecimientos.png"></p>';


            Phx.vista.CorreoPrueba.superclass.loadValoresIniciales.call(this);



            //this.getComponente('id_cotizacion').setValue(this.id_cotizacion);

            this.getComponente('email').setValue('miguel.ale19934@gmail.com');
            this.getComponente('asunto').setValue('Cotización: '+this.nroCotizacion);
            this.getComponente('body').setValue(CuerpoCorreo);

            this.getComponente('id_solicitud').setValue(this.id_solicitud);
            this.getComponente('estado').setValue(this.estado);

        },

        successSave:function(resp)
        {
            Phx.CP.loadingHide();
            Phx.CP.getPagina(this.idContenedorPadre).reload();
            this.panel.close();
        },


        Atributos:[


            {
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
                    name: 'estado'
                },
                type:'Field',
                form:true
            },



            {
                config:{
                    name: 'email',
                    fieldLabel: 'Destino',
                    allowBlank: true,
                    anchor: '90%',
                    gwidth: 100,
                    maxLength: 100,
                    value:'favio@kplian.com',
                    readOnly :true
                },
                type:'TextField',
                id_grupo:1,
                form:true
            },
            {
                config: {
                    tinit: false,
                    tasignacion: false,
                    name: 'lista_correos',
                    fieldLabel: 'CC',
                    allowBlank: true,
                    emptyText: 'Proveedor...',
                    anchor: '90%',
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
            },
            /*{
                config:{
                    name: 'email_cc',
                    fieldLabel: 'CC',
                    allowBlank: true,
                    anchor: '90%',
                    gwidth: 100,
                    maxLength: 100,
                    readOnly :true
                },
                type:'TextField',
                id_grupo:1,
                form:true
            },*/
            {
                config:{
                    name: 'asunto',
                    fieldLabel: 'Asunto',
                    allowBlank: true,
                    anchor: '90%',
                    gwidth: 100,
                    maxLength: 100
                },
                type:'TextField',
                id_grupo:1,
                form:true
            },
            {
                config:{
                    name: 'body',
                    fieldLabel: 'Mensaje',
                    anchor: '90%'
                },
                type:'HtmlEditor',
                id_grupo:1,
                form:true
            }



        ],
        title:'Solicitud de Compra',
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
                Phx.vista.CorreoPrueba.superclass.onSubmit.call(this,o);
            }
        },
        successSave:function(resp){
            Phx.CP.loadingHide();
            Phx.CP.getPagina(this.idContenedorPadre).reload();
            this.close();

        },
        onButtonEdit: function() {

        }
    })
</script>
