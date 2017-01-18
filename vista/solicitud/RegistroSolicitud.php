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
    Phx.vista.RegistroSolicitud = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',

        title: 'Solicitud',
        nombreVista: 'RegistroSolicitud',
        constructor: function (config) {
            this.maestro = config.maestro;
            Phx.vista.RegistroSolicitud.superclass.constructor.call(this, config);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'borrador';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
        },
        gruposBarraTareas:[
            {name:'borrador',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Borrador</h1>',grupo:0,height:0, width: 100},
            {name:'vobo_area',title:'<H1 "center"><i class="fa fa-eye"></i>Visto Bueno</h1>',grupo:1,height:0, width: 100},
            {name:'revision',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Proceso</h1>',grupo:2,height:0},
            {name:'finalizado',title:'<H1 align="center"><i class="fa fa-list-ul"></i> Finalizado</h1>',grupo:2,height:0}
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
        bactGroups:  [0,1,2],
        bexcelGroups: [0,1,2],


        onButtonNew:function(){
            //abrir formulario de solicitud
            var me = this;
            me.objSolForm = Phx.CP.loadWindows('../../../sis_gestion_materiales/vista/solicitud/FromFormula.php',
                'Formulario Requerimiento de Materiales',
                {
                    modal:true,
                    width:'70%',
                    height:'90%'
                }, {data:{objPadre: me}
                },
                this.idContenedor,
                'FromFormula',
                {
                    config:[{
                        event:'successsave',
                        delegate: this.onSaveForm,

                    }],

                    scope:this
                });

        },

        onSaveForm:function(resp){
            Phx.CP.loadingShow();
            Phx.CP.loadingHide();
            resp.argument.wizard.panel.destroy();
            this.reload();
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
            Phx.vista.RegistroSolicitud.superclass.preparaMenu.call(this,n);

            if(data['estado'] ==  'borrador'){
                this.getBoton('sig_estado').enable();
                this. enableTabDetalle();


            }else if(data['estado'] !=  'vobo_aeronavegabilidad'){
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
            var tb = Phx.vista.RegistroSolicitud.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                this.getBoton('sig_estado').disable();
            }
            return tb;
        }
    };
</script>


