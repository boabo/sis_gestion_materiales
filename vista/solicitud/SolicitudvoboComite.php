<?php
/**
 *@package pXP
 *@file SolicitudvoboComite.php
 *@author  MAM
 *@date 11-08-2017
 *@Interface para el inicio de solicitudes de materiales
 */
header("content-type: text/javascript; charset=UTF-8");
?>

<script>

    Phx.vista.SolicitudvoboComite = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'SolicitudvoboComite',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleComite.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleComite'
            }
        ],
        constructor: function (config) {
            this.Atributos.unshift({
                config: {
                    name: 'id_proveedor',
                    fieldLabel: 'Proveedor Adjudicado',
                    anchor: '80%',
                    tinit: false,
                    allowBlank: false,
                    origen: 'PROVEEDOR',
                    gdisplayField: 'desc_proveedor',
                    anchor: '100%',
                    gwidth: 280,
                    listWidth: '280',
                    resizable: true,
                    renderer: function(value, p, record) {
                        return String.format('<div ext:qtip="Optimo"><b><font color="green">{0}</font></b><br></div>', value);
                    }
                },
                type: 'ComboRec',
                filters:{pfiltro:'pro.desc_proveedor',type:'string'},
                id_grupo:2,
                grid: true,
                form: false
            });
            this.Atributos[this.getIndAtributo('nombre_estado_firma')].grid=true;
            this.Atributos[this.getIndAtributo('origen_pedido')].grid=true;
            Phx.vista.SolicitudvoboComite.superclass.constructor.call(this, config);
            this.store.baseParams = {tipo_interfaz: this.nombreVista};
            this.store.baseParams.pes_estado = ' ';
            this.load({params: {start: 0, limit: this.tam_pag}});
            this.finCons = true;
            this.getBoton('new').setVisible(false);
            this.getBoton('del').setVisible(false);
            this.getBoton('edit').setVisible(false);
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Report').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
            this.getBoton('clonar_solicitud').setVisible(false);
            this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);
        },
        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'g_comite',
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
        enableTabDetalle:function(){
            if(this.TabPanelSouth.get(0)){
                this.TabPanelSouth.get(0).enable();
                this.TabPanelSouth.setActiveTab(0);
            }
        },
        disableTabDetalle:function(){
            if(this.TabPanelSouth.get(0)){
                this.TabPanelSouth.setActiveTab(0);
            }
        },
        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.SolicitudvoboComite.superclass.preparaMenu.call(this,n);
            if( data['estado'] ==  'comite_unidad_abastecimientos' || data['estado'] ==  'comite_dpto_abastecimientos' || data['estado'] ==  'comite_aeronavegabilidad'|| data['estado'] ==  'departamento_ceac'){
                this.getBoton('sig_estado').enable();
                this.getBoton('ant_estado').enable();
                this. enableTabDetalle();
            }
            return tb;
        },

        liberaMenu:function(){
            var tb = Phx.vista.SolicitudvoboComite.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                this.getBoton('ant_estado').disable();


            }
            return tb;
        }
    }

</script>
