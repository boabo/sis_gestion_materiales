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
    Phx.vista.SolicitudCotizacion = {
        require: '../../../sis_gestion_materiales/vista/solicitud/Solicitud.php',
        requireclase: 'Phx.vista.Solicitud',
        title: 'Solicitud',
        nombreVista: 'ProcesoCompra',
        tabsouth :[
            {
                url:'../../../sis_gestion_materiales/vista/detalle_sol/DetalleNoEdit.php',
                title:'Detalle',
                height:'50%',
                cls:'DetalleNoEdit' 
            }
        ],

        constructor: function (config) {
          this.id = 'datos_generales';

            this.Atributos[this.getIndAtributo('nombre_estado_firma')].grid=false;
            this.Atributos[this.getIndAtributo('tipo_solicitud')].id_grupo=11;
            Phx.vista.SolicitudCotizacion.superclass.constructor.call(this, config);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'origen_ing';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;
            this.getBoton('ini_estado').setVisible(false);
            this.getBoton('Report').setVisible(false);
           // this.getBoton('ant_estado').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);
            //this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('clonar_solicitud').setVisible(false);
            //this.getBoton('btnproveedor').setVisible(false);
            this.getBoton('Cotizacion').setVisible(false);
            this.iniciarEventos();

        },
        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'g_cotizacion',
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
        gruposBarraTareas:[
            {name:'origen_ing',title:'<H1 align="center" style="color:#E78800; font-size:11px;"><i style="font-size:12px;" class="fa fa-truck" aria-hidden="true"></i> Operaciones</h1>',grupo:2,height:0, width: 100},
            {name:'origen_man',title:'<H1 align="center" style="color:#007AD9; font-size:11px;"><i style="font-size:12px;" class="fa fa-wrench" aria-hidden="true"></i> Mantenimiento</h1>',grupo:2,height:0, width: 100},
            {name:'origen_alm',title:'<H1 align="center" style="color:#00A530; font-size:11px;"><i style="font-size:12px;" class="fa fa-retweet" aria-hidden="true"></i> Abastecimientos</h1>',grupo:2,height:0, width: 150},
            {name:'origen_dgac',title:'<H1 align="center" style="color:#FF0000; font-size:11px;"><i style="font-size:12px;" class="fa fa-plane" aria-hidden="true"></i> Ope. CEAC</h1>',grupo:2,height:0, width: 200},
            {name:'origen_repu',title:'<H1 align="center" style="color:#7100BB; font-size:11px;"><i style="font-size:12px;" class="fa fa-cogs" aria-hidden="true"></i> Reparaciones</h1>',grupo:2,height:0, width: 200}

        ],
        tam_pag:50,
        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0,1,2],
        bdelGroups:  [0],
        bactGroups:  [0,1,2],
        btestGroups: [0],
        bexcelGroups: [0,1,2],

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
            Phx.vista.SolicitudCotizacion.superclass.preparaMenu.call(this,n);

            if(data['estado'] ==  'revision'){
                this.getBoton('sig_estado').enable();
                //this.getBoton('Control_aLmacene').enable();
                this.getBoton('Archivado_concluido').enable();
                this.getBoton('ant_estado').enable();
                this. enableTabDetalle();
            } else {
                this.getBoton('sig_estado').disable();
                //this.getBoton('Control_aLmacene').disable();
                this.getBoton('Archivado_concluido').disable();
                this.getBoton('ant_estado').disable();
                this.disableTabDetalle();
            }
            return tb;
        },
        liberaMenu:function(){
            var tb = Phx.vista.SolicitudCotizacion.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('sig_estado').disable();
                //this.getBoton('Control_aLmacene').disable();
                this.getBoton('Archivado_concluido').disable();
                this.getBoton('ant_estado').disable();


            }
            return tb;
        },

        /*Aumentando la condicion para ocultar campos en revision*/
        iniciarEventos:function(){
          this.Cmp.origen_pedido.allowBlank = true;
          this.Cmp.fecha_po.allowBlank = true;
          this.Cmp.mel.allowBlank = true;
          this.Cmp.id_funcionario_sol.allowBlank = true;
          this.Cmp.id_moneda.allowBlank = true;
          this.Cmp.id_matricula.allowBlank = true;
          this.Cmp.fecha_solicitud.allowBlank = true;
          this.Cmp.motivo_solicitud.allowBlank = true;
          this.Cmp.observaciones_sol.allowBlank = true;
          this.Cmp.nro_justificacion.allowBlank = true;
          this.Cmp.fecha_requerida.allowBlank = true;
          this.Cmp.tipo_falla.allowBlank = true;
          this.Cmp.tipo_reporte.allowBlank = true;
          this.Cmp.nro_po.allowBlank = true;
          this.Cmp.id_proveedor.allowBlank = true;
          this.Cmp.fecha_cotizacion.allowBlank = true;
          this.Cmp.fecha_arribado_bolivia.allowBlank = true;
          this.Cmp.fecha_desaduanizacion.allowBlank = true;
          this.Cmp.fecha_en_almacen.allowBlank = true;
          this.Cmp.taller_asignado.allowBlank = true;
          this.Cmp.observacion_nota.allowBlank = true;
          this.Cmp.lista_correos.allowBlank = true;
          this.Cmp.lugar_entrega.allowBlank = true;
          this.Cmp.condicion.allowBlank = true;
          this.Cmp.monto_pac.allowBlank = true;
          this.Cmp.obs_pac.allowBlank = true;
          this.Cmp.mensaje_correo.allowBlank = true;
          this.Cmp.nro_no_rutina.allowBlank = true;
          this.Cmp.justificacion.allowBlank = true;

          this.ocultarComponente(this.Cmp.nro_no_rutina);
          this.ocultarComponente(this.Cmp.justificacion);
          this.ocultarComponente(this.Cmp.nro_justificacion);
          this.ocultarComponente(this.Cmp.fecha_requerida);
          this.ocultarComponente(this.Cmp.mel);
          this.ocultarComponente(this.Cmp.fecha_solicitud);
          this.ocultarComponente(this.Cmp.tipo_falla);
          this.ocultarComponente(this.Cmp.tipo_reporte);


        },

        onButtonEdit:function(){
          var rec=this.sm.getSelected();
  				var simple = new Ext.FormPanel({
  				 labelWidth: 75, // label settings here cascade unless overridden
  				 frame:true,
  				 bodyStyle:'padding:5px 5px 0; background:linear-gradient(45deg, #a7cfdf 0%,#a7cfdf 100%,#23538a 100%);',
  				 width: 300,
  				 height:200,
  				 defaultType: 'textfield',
  				 items: [ new Ext.form.TextField({
                            name: 'mel',
                            fieldLabel: 'Mel',
                            allowBlank:true,
                            width : 200,
                            disabled : true
                    }),
                    new Ext.form.DateField({
                             name: 'fecha_requerida',
                             fieldLabel: 'Fecha Requerida',
                             allowBlank:true,
                             width : 200,
                             disabled : true
                     }),
                     new Ext.form.ComboBox({
                          name: 'tipo_solicitud',
                          fieldLabel: 'Tipo Solicitud',
                          allowBlank: true,
                          emptyText: 'Tipo..',
                          store : new Ext.data.JsonStore({
             							 url : '../../sis_parametros/control/Catalogo/listarCatalogoCombo',
             							 id : 'id_catalogo',
             							 root : 'datos',
             							 sortInfo : {
             								 field : 'codigo',
             								 direction : 'ASC'
             							 },
             							 totalProperty : 'total',
             							 fields: ['codigo','descripcion'],
             							 remoteSort : true,
             							 baseParams:{
             								cod_subsistema:'MAT',
             								catalogo_tipo:'tsolicitud_criticidad'
             							},
             						 }),
                         valueField : 'descripcion',
                        displayField : 'descripcion',
                        gdisplayField : 'descripcion',
                        hiddenName : 'tipo_solicitud',
                        forceSelection : true,
                        typeAhead : false,
                        tpl: new Ext.XTemplate([
                           '<tpl for=".">',
                           '<div class="x-combo-list-item">',
                           '<p><b><span style="color: black; height:15px;">{descripcion}</span></b></p>',
                           '</div></tpl>'
                       ]),
                          forceSelection: true,
                          typeAhead: false,
                          triggerAction: 'all',
                          listWidth:'450',
                          lazyRender: true,
                          resizable:true,
                          mode: 'remote',
                          pageSize: 100,
                          queryDelay: 100,
                          width: 200,
                          gwidth: 200,
                          minChars: 2,
                      }),
  									]

  		 					});
  					this.formulario_editar = simple;

  				var win = new Ext.Window({
  					title: '<h1 style="height:20px; font-size:15px;">Tipo Solicitud</h1>', //the title of the window
  					width:320,
  					height:200,
  					//closeAction:'hide',
  					modal:true,
  					plain: true,
  					items:simple,
  					buttons: [{
  											text:'<i class="fa fa-floppy-o fa-lg"></i> Guardar',
  											scope:this,
  											handler: function(){
  													this.modificarTipoSolicitud(win);
  											}
  									},{
  											text: '<i class="fa fa-times-circle fa-lg"></i> Cancelar',
  											handler: function(){
  													win.hide();
  											}
  									}]

  				});
  				win.show();
          this.formulario_editar.items.items[0].setValue(rec.data.mel);
          this.formulario_editar.items.items[1].setValue(rec.data.fecha_requerida);
  				this.formulario_editar.items.items[2].setValue(rec.data.tipo_solicitud);

        },

        modificarTipoSolicitud : function(win){
          var rec=this.sm.getSelected();
          Ext.Ajax.request({
              url : '../../sis_gestion_materiales/control/Solicitud/ModificarTipoSolicitud',
              params : {
                'id_solicitud' : rec.data.id_solicitud,
                'tipo_solicitud': this.formulario_editar.items.items[2].getValue()
              },
              success : this.successSave,
              failure : this.conexionFailure,
              timeout : this.timeout,
              scope : this
            });
            this.reload();
            win.hide();

        },


        successSave:function(resp){
          Phx.vista.SolicitudCotizacion.superclass.successSave.call(this,resp);
          this.confirmarEstado();
        },

        /*********************************************************/
        bdel:false,
        bsave:false,
        bnew:false,
        bedit:true,
        fheight:200,
      	fwidth: 400,

   }

</script>
