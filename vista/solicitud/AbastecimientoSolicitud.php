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
        nombreVista: 'Abastecimientos',
        direction: 'DESC',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/ControlDetalle.php',
                title:'Detalle',
                height:'50%',
                cls:'ControlDetalle'
            }
        ],

        gruposBarraTareas:[
            {name:'ab_origen_ing',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Operaciones</h1>',grupo:0,height:0, width: 100},
            {name:'ab_origen_man',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Mantenimiento</h1>',grupo:0,height:0, width: 100},
            {name:'ab_origen_alm',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Abastecimientos</h1>',grupo:0,height:0, width: 150},
            {name:'almacen',title:'<H1 align="center"><i class="fa fa-list-ul"></i> En Almacén</h1>',grupo:2,height:0, width: 150}

        ],
        actualizarSegunTab: function(name, indice){

            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        tam_pag:50,
        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [0,1,2],
        btestGroups: [0],
        bexcelGroups: [0,1,2],
        constructor: function (config) {

            this.Atributos.splice(24,25);
            this.Atributos.push(
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
            this.Atributos.unshift({
                config: {
                    name: 'revisado_so',
                    fieldLabel: 'Cotizado',
                    allowBlank: true,
                    anchor: '50%',
                    gwidth: 50,
                    maxLength:3,
                    renderer: function (value, p, record, rowIndex, colIndex) {

                        //check or un check row
                        var checked = '',
                            momento = 'no';
                        if (value == 'si') {
                            checked = 'checked';;
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:15px;width:15px;" type="checkbox"  {0}></div>', checked);

                    }
                },
                type: 'TextField',
                filters: {pfiltro: 'sol.revisado_so', type: 'string'},
                id_grupo: 0,
                grid: true,
                form: false

            });
            this.maestro=config.maestro;
            Phx.vista.AbastecimientoSolicitud.superclass.constructor.call(this, config);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'ab_origen_ing';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.getBoton('ini_estado').setVisible(true);
            this.getBoton('del').setVisible(false);
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
                this.getBoton('ini_estado').enable();
                //this.ocultarComponente(this.Atributos[1]);
                this. enableTabDetalle();


            }else if(data['estado'] !=  'finalizado'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this.getBoton('ini_estado').enable();

                this.disableTabDetalle();
            }
            else {
                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();
                this.getBoton('ini_estado').disable();
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
                this.getBoton('ini_estado').disable();
                this.getBoton('ini_estado').setVisible(true);

            }
            return tb;
        },
        bnew: false

    }

</script>