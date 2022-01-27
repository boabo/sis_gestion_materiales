
<style type="text/css" rel="stylesheet">

.lista{
  width: '100%';
  font-family: Garamond,"Times New Roman", serif;
}

#table-header {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#table-header td, #table-header th {
  border: 1px solid #ddd;
  padding: 5px;
}

#table-header tr{background-color: #f2f2f2;}

#table-header th {
  padding-top: 5px;
  padding-bottom: 5px;
  text-align: center;
  background-color: #239B56;
  color: white;
  font-size: 14px;
  font-weight: bold;
}

.capti{
  text-align: center;
  font-weight: bold;
  letter-spacing: 10px;
  margin: 15px 0 5px 0;
  box-shadow: 0px 5px 5px -2px black;
}

#Totales td{
  padding-top: 5px;
  padding-bottom: 5px;

  background-color: #326E83;
  color: white;
  font-size: 14px;
  font-weight: bold;
}

</style>
<script>

var me = null
if(screen.width<=1440){
  wdf=1250;
}else if (screen.width<=1920) {
  wdf=1670;
}
Phx.vista.DetalleSolicitud=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    Phx.vista.DetalleSolicitud.superclass.constructor.call(this,config);
    this.init();
    this.load({params:{start:0,limit:2,id_solicitud: this.id_solicitud}});
    this.tbar.el.dom.parentNode.style.height=0;
    this.bbar.container.dom.firstChild.style.height=0;
    this.grid.view.innerHd.style.height=0;
    me = this
	},
	Atributos:[

        {
            config:{
                name: 'jsonData',
                gwidth:wdf,
                renderer: function (value, p, record) {

                var obj = JSON.parse(record.json.jsondata).detalle_solicitud;
                var total_ca = 0;

                var info = `
                <div class="lista">
                <table id="table-header">
                            <caption class="capti">DETALLE DE LA SOLICITUD</caption>
                                  <tr>
                                      <th><b>Nro. Parte</b></th>
                                      <th><b>Nro. Parte Alterna</b></th>
                                      <th><b>Cantidad</b></th>
                                      <th><b>Precio Unitario</b></th>
                                      <th><b>Tiempo de Entrega</b></th>
                                      <th><b>Centro de Costo</b></th>
                                      <th><b>Orden de Trabajo</b></th>
                                      <th><b>Partida</b></th>
                                  </tr>

                                `;

                obj.forEach( e => {


                                  total_ca = total_ca + e.precio_unitario;
                                  info += `
                                              <tr>
                                                  <td align="right">${e.nro_parte_cot}</td>
                                                  <td align="right">${e.explicacion_detallada_part_cot}</td>
                                                  <td align="right">${e.cantidad_det}</td>
                                                  <td align="right">${Ext.util.Format.number(e.precio_unitario,'0.000,00/i')}</td>
                                                  <td align="right">${e.cantidad_dias}</td>
                                                  <td align="center">${e.centro_costo}</td>
                                                  <td align="center">${e.matricula}</td>
                                                  <td align="center">${e.partida}</td>
                                              </tr>
                                          `;
                                });

                                info += `
                                              <tr id="Totales"><td colspan="3" align="center"><b>TOTAL DETALLE</b></td><td align="right"><b>${Ext.util.Format.number(total_ca,'0.000,00/i')}</b></td></tr>
                                            </tbody>
                                          </table>
                                        </div>`;

                return info;
                console.log("aqui llega el objeto",obj);
                //   console.log("data",obj);
                //   var title = '';
                //   var detalle = obj.detalle_venta;
                //   var total_det = 0;
                //   var total_ca = 0;
                //   var total_uni = 0;
                //   var form_pago = obj.formas_pago;
                //   var total_fp = 0;
                //   var bolasoc = obj.bolasoc;
                //   var deposito = obj.deposito;
                //   var pagos_con_recibo = obj.pagos_con_recibo;
                //   var total_depo = 0;
                //   var total_ros = 0;
                //   var moneda_venta = (obj.moneda_venta=='USD')?`<span style="color:blue;font-weight:bold;">${obj.moneda_venta}</span>`:`<span style="color:green;font-weight:bold;">${obj.moneda_venta}</span>`;
                //   (obj.anticipo != null)?title_ant = 'ANTICIPO':title_ant='';
                //   var info =`	<div class="lista">
                //             <table width="100%">
                //             <tr>
                //                 <td>
                //                     <table width="100%" align="center" id="table-header">
                //                         <tr>`;
                //                         if(obj.tipo_factura=='recibo' || obj.tipo_factura == 'recibo_manual'){
                //                           info += `
                //                           <td width="2%" style="font-size:14px;">
                //                               <b>N° ${obj.tit_fac}: </b><span class="f_text">${obj.nro_factura}</span>`;
                //                               if (obj.nit!='' && obj.nit!=null){
                //                                   info +=  `<br><br><b>Nit: </b><span class="f_text">${obj.nit}</span>`;
                //                               }
                //             info +=`<br><br>
                //                           <b>Razon Social: </b><span class="f_text">${obj.nombre_factura}</span>
                //                               <br><br>
                //                               <b>Sucursal: </b><span class="f_text">${obj.sucursal}</span>
                //                           </td>
                //                           <td width="3%" style="font-size:14px;">
                //                               <b>Fecha: </b><span class="f_text">${obj.fecha_factura.split("-").reverse().join("/")}</span>
                //                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<b>Estado: </b> ${(obj.estado=='anulado')?'<span class="f_text" style="background:#E74C3C;color:white;">ANULADO</span>':'<span class="f_text" style="background:#28B463;color:white;">VALIDA</span>'}
                //                               <br><br>
                //                               <b>Usuario Registro: </b><span class="f_text">${obj.desc_persona}</span>
                //                               <br><br>
                //                               <b>P-Venta/Agencia: </b><span class="f_text">${obj.punto_venta}</span></td>
                //
                //                           </td>
                //                               <td width="3%" style="font-size:14px;">
                //                               <b>Total: </b><span class="f_text">${Ext.util.Format.number(obj.total_venta,'0.000,00/i')}</span>&nbsp;&nbsp;${moneda_venta}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Exento: </b><span class="f_text">${Ext.util.Format.number(obj.excento,'0.000,00/i')}</span>
                //                               ${(obj.comision>0)?'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<b>Comision: </b><span class="f_text">'+Ext.util.Format.number(obj.comision,'0.000,00/i')+'</span>':''}
                //                               <br><br>
                //                               <b>Importe Base Para Credito Fiscal: </b> <span class="f_text">${Ext.util.Format.number((obj.total_venta - obj.excento),'0.000,00/i')}</span>
                //                               <br><br>
                //                               <b>Observaciones: </b><span style="font-size:10pt;">${(obj.observaciones == null || obj.observaciones=='')?'':obj.observaciones.toLowerCase()}</span>
                //                           </td>
                //                           `;
                //                         }else{
                //                           info += `
                //                           <td width="2%" style="font-size:14px;">
                //                               <b>N° ${obj.tit_fac}: </b><span class="f_text">${obj.nro_factura}</span>
                //                               <br><br>
                //                               <b>Código de Control: </b><span class="f_text">${(obj.cod_control == null)?'':obj.cod_control}</span>
                //                               <br><br>
                //                               <b>N° Autorizacion: </b><span class="f_text">${(obj.nroaut == null || obj.nroaut==''||obj.nroaut==undefined)?'':obj.nroaut}</span>
                //                               <br><br>
                //                               <b>Sucursal: </b><span class="f_text">${obj.sucursal}</span>
                //                           </td>
                //                           <td width="3%" style="font-size:14px;">
                //                               <b>Fecha: </b><span class="f_text">${obj.fecha_factura.split("-").reverse().join("/")}</span>
                //                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<b>Estado: </b> ${(obj.estado=='anulado')?'<span class="f_text" style="background:#E74C3C;color:white;">ANULADO</span>':'<span class="f_text" style="background:#28B463;color:white;">VALIDA</span>'}
                //                               <br><br>
                //                               <b>Nit: </b><span class="f_text">${obj.nit}</span>
                //                               <br><br>
                //                               <b>Razon Social: </b><span class="f_text">${obj.nombre_factura}</span>
                //                               <br><br>
                //                               <b>P-Venta/Agencia: </b><span class="f_text">${obj.punto_venta}</span></td>
                //
                //                           </td>
                //                               <td width="3%" style="font-size:14px;">
                //                               <b>Total: </b><span class="f_text">${Ext.util.Format.number(obj.total_venta,'0.000,00/i')}</span>&nbsp;&nbsp;&nbsp;${moneda_venta}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Exento: </b><span class="f_text">${Ext.util.Format.number(obj.excento,'0.000,00/i')}</span>
                //                               ${(obj.comision>0)?'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Comision: </b><span class="f_text">'+Ext.util.Format.number(obj.comision,'0.000,00/i')+'</span>':''}
                //                               <br><br>
                //                               <b>Importe Base Para Credito Fiscal: </b> <span class="f_text">${Ext.util.Format.number((obj.total_venta - obj.excento),'0.000,00/i')}</span>
                //                               <br><br>
                //                               <b>Usuario Registro: </b><span class="f_text">${obj.desc_persona}</span>
                //                               <br><br>
                //                               <b>Observaciones: </b><span style="font-size:10pt;">${(obj.observaciones == null || obj.observaciones=='')?'':obj.observaciones.toLowerCase()}</span>
                //                           </td>
                //                           `;
                //                         }
                //
                //                       if (obj.nombre_auxiliar != '' && obj.nombre_auxiliar != null){
                //                           if(obj.us_cajero_fp != ''){
                //                               info += `<tr><td style="font-size:14px;" colspan="2"><br><b>Grupo: </b><span class="f_text">${obj.nombre_auxiliar}</span></td><td><br><b>Usuario Cajero: </b><span class="emisor_cajero">${obj.us_cajero_fp}</span></td></tr>`;
                //                           }else{
                //                               info += `<tr><td style="font-size:14px;" colspan="3"><br><b>Grupo: </b><span class="f_text">${obj.nombre_auxiliar}</span></td></tr>`;
                //                           }
                //                       }else{
                //                         if(obj.us_cajero_fp != ''){
                //                             info += `<tr><td></td><td></td><td><br><b>Usuario Cajero: </b><span class="emisor_cajero">${obj.us_cajero_fp}</span></td></tr>`;
                //                         }
                //                       }
                //
                //           info +=`  </tr>
                //                     </table>
                //                 </td>
                //             </tr>
                //             <tr>
                //                 <td style="background: beige;">
                //                   <hr>
                //                 </td>
                //             </tr>`;
                //          if (detalle!=null){
                //               info += `
                //                     <tr>
                //                         <td class="item2">
                //                         <table id="detalle">
                //                         <caption class="capti">CONCEPTOS VENTA</caption>
                //                           <tr>
                //                             <th>Tipo</th>
                //                             <th>Concepto</th>
                //                             <th>Moneda</th>
                //                             <th>Cantidad</th>
                //                             <th>Precio Unitario</th>
                //                             <th>Total</th>
                //                             <th>Descripcion</th>
                //                           </tr>`;
                //                   detalle.forEach( e => {
                //                     total_det = total_det + (e.cantidad*e.precio);
                //                     total_ca = total_ca + e.cantidad;
                //                     total_uni = total_uni + e.precio;
                //                     info += `
                //                                 <tr>
                //                                     <td>${e.tipo}</td>
                //                                     <td>${e.desc_ingas}</td>
                //                                     <td align="center">${e.moneda_det}</td>
                //                                     <td align="center">${e.cantidad}</td>
                //                                     <td align="center">${Ext.util.Format.number(e.precio,'0.000,00/i')}</td>
                //                                     <td align="center">${Ext.util.Format.number((e.cantidad*e.precio),'0.000,00/i')}</td>
                //                                     <td>${(e.descripcion==null || e.descripcion=='' )?'':e.descripcion}</td>
                //                                 </tr>
                //                             `;
                //                   });
                //
                //                   info += `<tr><td colspan="3" align="center"><b>TOTAL DETALLE</b></td><td align="center"><b>${total_ca}</b></td><td align="center"><b>${Ext.util.Format.number(total_uni,'0.000,00/i')}</b></td><td align="center"><b>${Ext.util.Format.number(total_det,'0.000,00/i')}</b></td><td></td></tr>
                //                         </table>
                //                         </td>
                //                     </tr>
                //                     <tr>
                //                         <td>
                //                           <hr>
                //                         </td>
                //                     </tr>
                //                     `;
                //           }
                //          if (form_pago!=null){
                //             info += `
                //                   <tr>
                //                     <td class="item2">
                //                       <table id="con_pago">
                //                       <caption class="capti">FORMAS DE PAGO ${title_ant}</caption>
                //                         <tr>
                //                           <th>Medio de Pago</th>
                //                           <th>Moneda</th>
                //                           <th>Monto Transaccion</th>
                //                           <th>Monto BOB</th>
                //                           <th>Tipo Tarjeta</th>
                //                           <th>N° Tarjeta</th>
                //                           <th>Codigo Tarjeta</th>
                //                           <th>N° Cuenta</th>
                //                           <th>N° Recibo</th>
                //                         </tr>
                //                       `;
                //                       form_pago.forEach( e => {
                //                         total_fp = total_fp + e.monto_mb_efectivo;
                //                         info += `
                //                           <tr>
                //                               <td>${e.name}</td>
                //                               <td align="center">${e.moneda_fp}</td>
                //                               <td align="center">${Ext.util.Format.number(e.monto_forma_pago,'0.000,00/i')}</td>
                //                               <td align="center">${Ext.util.Format.number(e.monto_mb_efectivo,'0.000,00/i')}</td>
                //                               <td align="center">${(e.tipo_tarjeta==null)?'':e.tipo_tarjeta}</td>
                //                               <td align="center">${(e.numero_tarjeta==null)?'':e.numero_tarjeta}</td>
                //                               <td align="center">${(e.codigo_tarjeta==null)?'':e.codigo_tarjeta}</td>
                //                               <td align="center">${(e.cod_cuenta==null)?'':e.cod_cuenta}</td>
                //                               <td align="center">${(e.nro_recibo==null)?'':`<b class="grupoAnticipo"><i class="fa fa-share" aria-hidden="true" onclick="formAnti(${e.id_venta_recibo})"></i> &nbsp;&nbsp;&nbsp;${e.nro_recibo}</b>`}</td>
                //                           </tr>
                //                           `;
                //                       });
                //                 info +=`<tr><td colspan="3" align="center"><b>TOTAL FORMA PAGO</b></td><td align="center"><b>${Ext.util.Format.number(total_fp,'0.000,00/i')}</b></td><td colspan="5"></td></tr>
                //                       </table>
                //                     </td>
                //                   </tr>
                //                   <tr>
                //                       <td>
                //                         <hr>
                //                       </td>
                //                   </tr>
                //                   `;
                //               }
                //               if (deposito != null){
                //                 info += `
                //                 <tr>
                //                     <td class="item2">
                //                     <table id="deposito">
                //                     <caption class="capti">DEPÓSITO</caption>
                //                     <tr>
                //                       <th>N° Deposito</th>
                //                       <th>Moneda</th>
                //                       <th>Monto Total</th>
                //                       <th>Fecha</th>
                //                     </tr>
                //                     `;
                //                     deposito.forEach( e => {
                //                       total_depo = total_depo + e.monto_total;
                //                       info += `
                //                       <tr>
                //                           <td align="center">${e.nro_deposito}</td>
                //                           <td align="center">${e.moneda_dep}</td>
                //                           <td align="center">${Ext.util.Format.number(e.monto_total,'0.000,00/i')}</td>
                //                           <td align="center">${(e.fecha==null)?'':e.fecha.split("-").reverse().join("/")}</td>
                //                       </tr>
                //                       `;
                //                       });
                //                     info += `<tr><td colspan="2" align="center"><b>TOTAL DEPÓSITO</b></td><td align="center"><b>${Ext.util.Format.number(total_depo,'0.000,00/i')}</b></td><td></td></tr>
                //                     </table>
                //                      </td>
                //                    </tr>
                //                    <tr>
                //                        <td>
                //                          <hr>
                //                        </td>
                //                    </tr>
                //                    `;
                //               }
                //               if (bolasoc != null){
                //                 info += `
                //                 <tr>
                //                     <td class="item2">
                //                     <table id="bol_asoc">
                //                     <caption class="capti">BOLETO ASOCIADO</caption>
                //                     <tr>
                //                       <th>N° Boleto</th>
                //                       <th>Nit</th>
                //                       <th>Pasajero</th>
                //                       <th>Fecha Emision</th>
                //                       <th>Razon</th>
                //                       <th>Ruta</th>
                //                       <th>Estado</th>
                //                     </tr>
                //                     `;
                //                 bolasoc.forEach( e => {
                //                   info += `
                //                   <tr>
                //                       <td align="center">${e.nro_boleto}</td>
                //                       <td align="center">${(e.nit == null)?'':e.nit}</td>
                //                       <td>${(e.pasajero == null)?'':e.pasajero}</td>
                //                       <td align="center">${(e.fecha_emision==null)?'':e.fecha_emision.split("-").reverse().join("/")}</td>
                //                       <td>${(e.razon == null)?'':e.razon}</td>
                //                       <td>${(e.ruta == null)?'':e.ruta}</td>
                //                       <td align="center">${e.estado_reg}</td>
                //                   </tr>
                //                   `;
                //                   });
                //                 info += `</table>
                //                  </td>
                //                </tr>
                //                <tr>
                //                    <td>
                //                      <hr>
                //                    </td>
                //                </tr>
                //                `;
                //              }
                //              if (pagos_con_recibo != null){
                //                info += `
                //                <tr>
                //                    <td class="item2">
                //                    <table id="pago_recibo">
                //                    <caption class="capti">DOCUMENTOS PAGADOS CON RECIBO</caption>
                //                    <tr>
                //                      <th>Razon</th>
                //                      <th>Nit</th>
                //                      <th>N° Documento</th>
                //                      <th>Moneda Forma Pago</th>
                //                      <th>Monto Forma Pago</th>
                //                      <th>Moneda Venta</th>
                //                      <th>Monto Venta</th>
                //                      <th>Fecha</th>
                //                      <th>Tipo Documento</th>
                //                    </tr>
                //                    `;
                //                pagos_con_recibo.forEach( e => {
                //                  var mon_ro=`<span>${e.mon_fp_ro}</span>`;
                //                  var mon_ven=`<span>${e.mone_venta}</span>`;
                //                  if (e.mon_fp_ro!='BOB'){mon_ro=`<span style="color:blue;">${e.mon_fp_ro}</span>`}
                //                  if (e.mon_fp_ro!='BOB'){mon_ro=`<span style="color:blue;">${e.mon_fp_ro}</span>`}
                //                  total_ros = total_ros + e.monto_ro_forma_pago;
                //                  info += `
                //                  <tr>
                //                      <td align="left">${e.nombre_factura}</td>
                //                      <td align="center">${(e.nit == null)?'':e.nit}</td>
                //                      <td align="center">${(e.nro_factura==null)?'':e.nro_factura}</td>
                //                      <td align="center">${mon_ro}</td>
                //                      <td align="center">${Ext.util.Format.number(e.monto_ro_forma_pago,'0.000,00/i')}</td>
                //                      <td align="center">${mon_ven}</td>
                //                      <td align="center">${Ext.util.Format.number(e.total_venta,'0.000,00/i') }</td>
                //                      <td align="center">${(e.fecha==null)?'':e.fecha.split("-").reverse().join("/")}</td>
                //                      <td align="center">${e.tipo_factura}</td>
                //                  </tr>
                //                  `;
                //                  });
                //                info += `<tr><td colspan="4" align="center"><b>TOTAL RECIBOS</b></td><td align="center"><b>${Ext.util.Format.number(total_ros,'0.000,00/i')}</b></td><td align="center"><b>SALDO GRUPO</b></td><td align="center" colspan="3"><b>${Ext.util.Format.number(obj.total_venta-total_ros,'0.000,00/i')}</b></td></tr>`
                //                info += `</table>
                //                 </td>
                //               </tr>`;
                //             }
                //
                // info +=`</table>
                //   </div>
                //   `;
                //   return info;
                }
            },
            type:'TextField',
            id_grupo:1,
            grid:true
        },

	],
	title:'Detalle Factura',
	ActList:'../../sis_gestion_materiales/control/Solicitud/consultaDetalleSolicitud',
	fields: [
		{name:'jsonData', type: 'text'},
	],

	bdel:false,
	bsave:false,
  btest:false,
  bnew:false,
  bedit:false,
  bact:false,
  bexport:false,

});
function formAnti(id){
  me.openAnticipo(id);
}

</script>
