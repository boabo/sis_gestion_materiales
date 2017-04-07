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
    Phx.vista.ControlDetalle = {
        require: '../../../sis_gestion_materiales/vista/detalle_sol/DetalleSol.php',
        requireclase: 'Phx.vista.DetalleSol',
        title: 'Solicitud',
        nombreVista: 'Control',
        constructor: function (config) {
            this.Atributos.unshift({
                    config: {
                        name: 'revisado',
                        fieldLabel: 'Parte Adquirida',
                        allowBlank: true,
                        anchor: '50%',
                        gwidth: 80,
                        maxLength: 3,
                        renderer: function (value, p, record, rowIndex, colIndex) {

                            //check or un check row
                            var checked = '',
                                momento = 'no';
                            if (value == 'si') {
                                checked = 'checked';
                            }
                            return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);

                        }
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'sol.revisado', type: 'string'},
                    id_grupo: 0,
                    grid: true,
                    form: false
                });
            Phx.vista.ControlDetalle.superclass.constructor.call(this, config);
            this.maestro = config.maestro;

            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = 'Control';
            //this.load({params:{start:0, limit:this.tam_pag}});
        },
        bnew: true,
        bdel: true,
        bedit: true
    }
</script>
