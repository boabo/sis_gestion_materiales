<?php
/**
 *@package pXP
 *@file gen-PresupPartida.php
 *@author  (admin)
 *@date 29-02-2016 19:40:34
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.solicitudFacMin={
        fwidth: '30%',
        fheight: '30%',
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'SolicitudFec',

        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleSol.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleSol'
            }
        ],
        /*Grupos: [
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
                                title: ' Datos ',
                                autoHeight: false,
                                items: [],
                                id_grupo: 1
                            }


                        ]
                    }
                ]
            }
        ],*/

        constructor: function(config) {
            this.Atributos[this.getIndAtributo('fecha_po')].id_grupo=1;
            this.Atributos[this.getIndAtributo('nro_po')].id_grupo=1;


            Phx.vista.solicitudFacMin.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'ab_origen_ing';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.getBoton('new').setVisible(false);
            this.getBoton('del').setVisible(false);
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Report').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
            this.getBoton('Consulta_desaduanizacion').setVisible(false);
            this.getBoton('sig_estado').setVisible(false);
            this.getBoton('edit').setVisible(true);
            this.getBoton('ant_estado').setVisible(false);
            this.getBoton('btnObs').setVisible(false);
            this.getBoton('Control_aLmacene').setVisible(false);
            this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(true);
        },
        gruposBarraTareas:[
            {name:'ab_origen_ing',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Operaciones</h1>',grupo:7,height:0},
            {name:'ab_origen_man',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Mantenimiento</h1>',grupo:7,height:0},
            {name:'ab_origen_alm',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Abastecimientos</h1>',grupo:7,height:0}

        ],
        tam_pag:50,
        beditGroups: [7,4],
        bdelGroups:  [2],
        bactGroups:  [7],
        btestGroups: [2],
        bexcelGroups: [7],

        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        onButtonEdit: function() {
            Phx.vista.solicitudFacMin.superclass.onButtonEdit.call(this);
                this.ocultarComponente(this.Cmp.mel);
                this.ocultarComponente(this.Cmp.tipo_reporte);
                this.ocultarComponente(this.Cmp.tipo_falla);
                this.ocultarComponente(this.Cmp.justificacion);
                this.ocultarComponente(this.Cmp.id_matricula);
                this.ocultarComponente(this.Cmp.nro_justificacion);
                this.ocultarComponente(this.Cmp.fecha_arribado_bolivia);
                this.ocultarComponente(this.Cmp.fecha_desaduanizacion);
                this.ocultarComponente(this.Cmp.fecha_en_almacen);
                this.ocultarComponente(this.Cmp.fecha_cotizacion);
                this.ocultarComponente(this.Cmp.id_proveedor);
                this.ocultarComponente(this.Cmp.origen_pedido);
                this.ocultarComponente(this.Cmp.id_funcionario_sol);
                this.ocultarComponente(this.Cmp.motivo_solicitud);
                this.ocultarComponente(this.Cmp.observaciones_sol);
                this.ocultarComponente(this.Cmp.nro_no_rutina);
                this.ocultarComponente(this.Cmp.tipo_solicitud);
                this.ocultarComponente(this.Cmp.tipo_evaluacion);
                this.ocultarComponente(this.Cmp.lugar_entrega);
                this.ocultarComponente(this.Cmp.condicion);
                this.ocultarComponente(this.Cmp.mensaje_correo);
                this.mostrarComponente(this.Cmp.fecha_po);
                this.mostrarComponente(this.Cmp.nro_po);

        }
    }
</script>

