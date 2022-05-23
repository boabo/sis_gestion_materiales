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
    Phx.vista.ConsultaRequerimientos = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'ConsultaRequerimientos',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleNoEdit.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleNoEdit'
            }
        ],
        constructor: function (config) {

            this.font();
            Phx.vista.ConsultaRequerimientos.superclass.constructor.call(this, config);
            this.maestro = config.maestro;
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'consulta_op';
            //this.load({params:{start:0, limit:this.tam_pag}});

            this.getBoton('sig_estado').setVisible(false);
            this.getBoton('ant_estado').setVisible(false);
            this.getBoton('new').setVisible(false);
            this.getBoton('del').setVisible(false);
            this.getBoton('edit').setVisible(false);
            this.getBoton('btnObs').setVisible(false);
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Report').setVisible(false);
            this.getBoton('Archivado_concluido').setVisible(false);
            this.getBoton('clonar_solicitud').setVisible(false);
            //this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);
            this.getBoton('btnCheckPresupeusto').setVisible(true);
            this.getBoton('bmodCuce').setVisible(true);
            this.getBoton('bmodPAC').setVisible(true);

        },
        gruposBarraTareas:[
            {name:'consulta_op',title:'<H1 align="center" style="color:#E78800; font-size:11px;"><i style="font-size:12px;" class="fa fa-truck" aria-hidden="true"></i> Operaciones</h1>',grupo:5,height:0, width: 100},
            {name:'consulta_mal',title:'<H1 align="center" style="color:#007AD9; font-size:11px;"><i style="font-size:12px;" class="fa fa-wrench" aria-hidden="true"></i> Mantenimiento</h1>',grupo:5,height:0, width: 100},
            {name:'consulta_ab',title:'<H1 align="center" style="color:#00A530; font-size:11px;"><i style="font-size:12px;" class="fa fa-retweet" aria-hidden="true"></i> Abastecimientos</h1>',grupo:5,height:0, width: 150},
            {name:'consulta_ceac',title:'<H1 align="center" style="color:#FF0000; font-size:11px;"><i style="font-size:12px;" class="fa fa-plane" aria-hidden="true"></i> Ope. CEAC</h1>',grupo:5,height:0, width: 200},
            {name:'consulta_repu',title:'<H1 align="center" style="color:#7100BB; font-size:11px;"><i style="font-size:12px;" class="fa fa-cogs" aria-hidden="true"></i> Reparaciones</h1>',grupo:5,height:0, width: 200}

        ],
        tam_pag:50,
        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0],
        bdelGroups:  [0],
        bactGroups:  [5],
        bexcelGroups: [5],
        bganttGroups: [0,5],

        bgantt:true,
        font:function () {
            this.Atributos[this.getIndAtributo('nro_po')].grid=true;
            this.Atributos[this.getIndAtributo('id_proveedor')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_cotizacion')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_arribado_bolivia')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_desaduanizacion')].grid=true;
            this.Atributos[this.getIndAtributo('fecha_en_almacen')].grid=true;
            this.Atributos[this.getIndAtributo('tipo_evaluacion')].grid=true;
            this.Atributos[this.getIndAtributo('taller_asignado')].grid=true;
            this.Atributos[this.getIndAtributo('observacion_nota')].grid=true;
            this.Atributos[this.getIndAtributo('lugar_entrega')].grid=true;
            this.Atributos[this.getIndAtributo('condicion')].grid=true;

        }


    }

</script>
