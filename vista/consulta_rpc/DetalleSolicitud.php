    <script>


        Ext.define('Phx.vista.DetalleSolicitud',{
            extend: 'Ext.util.Observable',

            constructor: function(config) {

                var me = this;
                Ext.apply(this, config);
                var me = this;

                this.callParent(arguments);
                this.panel = Ext.getCmp(this.idContenedor);
                this.reportPanel = new Ext.Panel({
                    id: 'reportPanelBoleto',
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
                            src: '../../../sis_gestion_materiales/vista/consulta_rpc/DetalleRpcHtml.html?id_solicitud='+me.id_solicitud,
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
