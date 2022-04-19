<script>


    Ext.define('Phx.vista.PanelLogReasignacion',{
        extend: 'Ext.util.Observable',

        constructor: function(config) {

            var me = this;
            Ext.apply(this, config);
            var me = this;
            console.log("aqui llega el id de la solicitud",me.id_solicitud);
            this.callParent(arguments);
            this.panel = Ext.getCmp(this.idContenedor);
            this.reportPanel = new Ext.Panel({
                id: 'panelReasignacion',
                width: '100%',
                height: '100%',
                /*renderTo: Ext.get('principal'),*/
                region:'center',
                margins: '5 0 5 5',
                layout: 'fit',
                autoScroll : true,
                items: [{
                    xtype: 'box',
                    width: '100%',
                    height: '100%',
                    autoEl: {
                        tag: 'iframe',
                        src: '../../../sis_gestion_materiales/vista/reasignacion_encargado/LogReasignacion.html?id_solicitud='+me.id_solicitud,
                    }}]
            });


            this.Border = new Ext.Container({
                layout:'border',
                id:'principal',
                items:[this.reportPanel]
            });

            this.panel.add(this.Border);
            this.panel.doLayout();
            this.addEvents('init');

        }

    });
</script>
