<?php
/**
 *@package pXP
 *@file RegistroSolicitud.php
 *@author  MAM
 *@date 27-12-2016 14:45
 *@Interface para el inicio de solicitudes de materiales
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.AbastecimientoSolicitud = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'Consulta Requerimientos',
        direction: 'DESC',
        constructor: function (config) {

            this.Atributos.splice(24,25);
            this.Atributos.push(
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
                    grid:true,
                    form:false
                },
                {
                    config:{
                        name: 'nro_po',
                        fieldLabel: 'Nro. PO',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 100
                    },
                    type:'TextField',
                    filters:{pfiltro:'rec.nro_po',type:'string'},
                    id_grupo:2,
                    grid:true,
                    form:false
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
                    grid: true,
                    form: false
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
                    grid:true,
                    form:false
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
                    grid:true,
                    form:false
                },
                {
                    config:{
                        name: 'fecha_en_almacen',
                        fieldLabel: 'Fecha en Almacen',
                        allowBlank: true,
                        anchor: '100%',
                        gwidth: 100,
                        format: 'd/m/Y',
                        renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                    },
                    type:'DateField',
                    filters:{pfiltro:'sol.fecha_en_almacen',type:'date'},
                    id_grupo:2,
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
            );

            Phx.vista.AbastecimientoSolicitud.superclass.constructor.call(this, config);

            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'ab_origen_ing';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('del').setVisible(false);

        },
        gruposBarraTareas:[
            {name:'ab_origen_ing',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Operaciones</h1>',grupo:0,height:0, width: 100},
            {name:'ab_origen_man',title:'<H1 "center"><i class="fa fa-list-ul"></i> Mantenimiento</h1>',grupo:0,height:0, width: 100},
            {name:'ab_origen_alm',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Abastecimientos</h1>',grupo:0,height:0, width: 150}

        ],
        tam_pag:50,
        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
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
            Phx.vista.AbastecimientoSolicitud.superclass.preparaMenu.call(this,n);

            if(data['estado'] ==  'despachado'){
                this.getBoton('sig_estado').enable();
                this. enableTabDetalle();


            }else if(data['estado'] !=  'finalizado'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.disableTabDetalle();
            }
            else {
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
                this.disableTabDetalle();
            }
            return tb;
        },
        liberaMenu:function(){
            var tb = Phx.vista.AbastecimientoSolicitud.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                this.getBoton('sig_estado').disable();
                this.getBoton('edit').setVisible(true);
                this.getBoton('del').setVisible(false);
                this.getBoton('ini_estado').setVisible(false);
            }
            return tb;
        },
        bnew: false




    }

</script>