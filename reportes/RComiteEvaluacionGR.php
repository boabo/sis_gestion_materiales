<?php
class RComiteEvaluacionGR extends  ReportePDF
{
    function Header(){
        $this->Ln(8);
        $this->MultiCell(40, 25, '', 1, 'C', 0, '', '');
        $this->SetFontSize(12);
        $this->SetFont('', 'B');
        $this->MultiCell(105, 25, "\n" . 'COMITÉ DE EVALUACIÓN DE' . "\n" . 'COMPRA Y SELECCIÓN DE'."\n".'PROVEEDOR', 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, 25, "\n" . 'Form MA-01'."\n".'Rev 0 20/06/2016'."\n" .$this->datos[0]['nro_tramite'], 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib/images/Logo-BoA.png', 12, 15, 36);
    }
    function ReporteComiteEvaluacion(){
        $this->AddPage();
        $tipo = $this->datos[0]['evaluacion'];
        $proveedor = $this->datos[0]['proveedor'];
        $cotizacionReci = $this->datos[0]['cotizaciones_recibidas'];
        $itemSeleccionados = $this->datos[0]['literal'];
        $talleAsignado = $this->datos[0]['taller_asignado'];
        $nro_partes = explode(',',$this->datos[0]["parte_solicitada"]);
        $obs = $this->datos[0]['observaciones'];
        $contador = 1;

        $funcionario_abastecimiento = $this->datos[0]["firma_abastecimiento"];
        $fun_abastecimiento = explode('|', $funcionario_abastecimiento);
        /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
        if ($funcionario_abastecimiento == NULL) {
          $fun_abastecimiento_0 = ' ';
          $fun_abastecimiento_1 = '';
          $fun_abastecimiento_3 = '';
          $fun_abastecimiento_4 = '';
        } else {
          $fun_abastecimiento_0 = $fun_abastecimiento[0];
          $fun_abastecimiento_1 = $fun_abastecimiento[1];
          $fun_abastecimiento_3 = $fun_abastecimiento[3];
          $fun_abastecimiento_4 = $fun_abastecimiento[4];
        }
        /*************************************************************************/

        $funcionario_aeronavegabilidad = $this->datos[0]["firma_aeronavegabilidad"];
        $fun_aeronavegabilidad = explode('|', $funcionario_aeronavegabilidad);
        /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
        if ($funcionario_aeronavegabilidad == NULL) {
          $fun_aeronavegabilidad_0 = ' ';
          $fun_aeronavegabilidad_1 = '';
          $fun_aeronavegabilidad_3 = '';
          $fun_aeronavegabilidad_4 = '';
        } else {
          $fun_aeronavegabilidad_0 = $fun_aeronavegabilidad[0];
          $fun_aeronavegabilidad_1 = $fun_aeronavegabilidad[1];
          $fun_aeronavegabilidad_3 = $fun_aeronavegabilidad[3];
          $fun_aeronavegabilidad_4 = $fun_aeronavegabilidad[4];
        }
        /*************************************************************************/

        $funcionario_auxiliar = $this->datos[0]["firma_auxiliar"];
        $fun_auxiliar = explode('|', $funcionario_auxiliar);
        /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
        if ($funcionario_auxiliar == NULL) {
          $fun_auxiliar_0 = ' ';
          $fun_auxiliar_1 = '';
          $fun_auxiliar_3 = '';
          $fun_auxiliar_4 = '';
        } else {
          $fun_auxiliar_0 = $fun_auxiliar[0];
          $fun_auxiliar_1 = $fun_auxiliar[1];
          $fun_auxiliar_3 = $fun_auxiliar[3];
          $fun_auxiliar_4 = $fun_auxiliar[4];        }

        /*************************************************************************/

        /*Fimar del Jefe de departamento*/
        $funcionario_jefe_depto = $this->datos[0]["firma_jefe_departamento"];
        $fun_jefe_depto = explode('|', $funcionario_jefe_depto);
        /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
        if ($fun_jefe_depto == NULL) {
          $fun_jefe_depto_0 = ' ';
          $fun_jefe_depto_1 = '';
          $fun_jefe_depto_3 = '';
          $fun_jefe_depto_4 = '';
        } else {
          $fun_jefe_depto_0 = $fun_jefe_depto[0];
          $fun_jefe_depto_1 = $fun_jefe_depto[1];
          $fun_jefe_depto_3 = $fun_jefe_depto[3];
          $fun_jefe_depto_4 = $fun_jefe_depto[4];        }

        /*************************************************************************/

        $funcionario_rpc = $this->datos[0]["firma_rpce"];
        $fun_rpc = explode('|', $funcionario_rpc);
        /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
        if ($funcionario_auxiliar == NULL) {
          $fun_rpc_0 = ' ';
          $fun_rpc_1 = '';
          $fun_rpc_3 = '';
          $fun_rpc_4 = '';
        } else {
          $fun_rpc_0 = $fun_rpc[0];
          $fun_rpc_1 = $fun_rpc[1];
          $fun_rpc_3 = $fun_rpc[3];
          $fun_rpc_4 = $fun_rpc[4];
        }

        /*************************************************************************/

        $marcar_compra = '';
        $marcar_reparacion = '';
        $marcar_exchange = '';

        if ($proveedor == NULL || $proveedor == '') {
          // code...
          $proveedor = 'Aun no se tiene un taller adjudicado';
        }

        /*Aqui ponemos para marcar con X la condicion que corresponda (Ismael Valdivia 08/05/2020)*/
        if ($tipo == 'Compra') {
          $marcar_compra = 'X';
        } elseif ($tipo == 'Reparacion') {
          $marcar_reparacion = 'X';
        } elseif ($tipo == 'Exchange' || $tipo == 'Flat Exchange') {
          $marcar_exchange = 'X';
        }
        /******************************************************************************************/

        /*Aqui ponemos la condicion para obtener cotizacion Solicitada Si o No (Ismael valdivia)*/
        if ($this->datos[0]['proveedor'] == NULL || $this->datos[0]['proveedor'] == '') {
           $seleccion = 'No';
        } else {
           $seleccion = 'Si';
        }

        if ($talleAsignado == 'si') {
          $asignado = 'SI <input type="checkbox" name="agree" value="1" checked="checked" /> NO <input type="checkbox" name="agree" value="1"/>';
        }
        else {
          $asignado = 'SI <input type="checkbox" name="agree" value="1" /> NO <input type="checkbox" name="agree" value="1" checked="checked"/>';
        }
        //$this->writeHTML($asignado, true, 0, true, 0);
        $tb ='<table cellspacing="0" cellpadding="5" border="1" style="font-size:12px;">
              <tr>
                <th scope="col" width="110" align="center" ><b>Fecha:</b></th>
                <th scope="col" width="253" align="center" >'.$this->datos[0]['fecha_cotizacion'].'</th>
                <th scope="col" width="253"> <b>Cotizaciones recibidas:</b></th>
                <th scope="col" width="79"  align="center">1</th>
              </tr>';
        $tb .= '<tr>
                    <th scope="col" width="110" > <b>Cotización Seleccionada:</b></th>
                    <th scope="col" width="87"   align="center" >' . $seleccion . '</th>
                    <th scope="col" width="87"  align="center"><b>Compra:</b></th>
                    <th scope="col" width="79"   align="center" style="font-size:15px;">'.$marcar_compra.'</th>
                    <th scope="col" width="87" align="center"> <b>Reparación:</b></th>
                    <th scope="col" width="79"   align="center" style="font-size:15px;">'.$marcar_reparacion.'</th>
                    <th scope="col" width="87"  align="center"><b>Exchange:</b></th>
                      <th scope="col" width="79" align="center" style="font-size:15px;">'.$marcar_exchange.'</th>
                  </tr>';

        $tb.=' <tr>
                        <th scope="col"  height="10"> <b>Empresa:</b></th>
                        <th scope="col" width="585"  align="justify"> '.$proveedor.'</th>
                   </tr>
                   <tr>
                        <th scope="col"  height="10" > <b>Items Seleccionados:</b></th>
                        <th scope="col" width="585"  align="justify">  ('.$cotizacionReci.') '.$itemSeleccionados.'</th>
                   </tr> </table>
      <style> .conborde {
                            border: 1px solid #000;
                            border-collapse:collapse;
                        }
                            table.conborde th,
                            table.conborde td { border = 0;
                        }
      </style>
    <table class="conborde">
    <tr>
      <td width="695"><b>Comentario:</b> El Comité de evaluación recomienda la selección de la empresa <b>'.$proveedor.'</b>
      misma que presenta su propuesta económica enmarcada en el precio referencial y cumple con la especificación técnica del servicio solicitado
      debido a la falla técnica no rutinaria, por la suma de <b>$us '.$this->datos[0]['total'].'.</b></td>
    </tr>
    <br>
    <tr>
      <td width="695">Según Part Number.</td>
    </tr>
        <table width="200">
            <br>
        ';
              foreach ($nro_partes as $value){
                  $tb .= '<tr><td align="center" width="50" >'.$contador.'.-</td>';
                  $tb .= '<td align="center" width="150" >'.$value.'</td>
               </tr>';
               $contador++;
              }
                $tb .= '
        </table>
          <tr>
            <td width="695"><b></b></td>
          </tr>
    </table>
    <table border="1">
    <tr>
    <td width="695"> <b>Para componentes reparados</b>
    <br> La(s) unidad(es) reparadas fueron enviadas a los talleres asignados? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$asignado.'
    <br> <b>Observaciones:</b> '.$obs.'</td>
    </tr>
    <br>
    <tr>
    <td width="695"> <b>Observaciones para componentes en exchange:</b> N/A </td>
    </tr>
    ';
        $tb.='</table>';

      if ($fun_jefe_depto_0 != '' && ($fun_jefe_depto_0 != $fun_rpc_0) ) {

        $firmas = '
        <table border="1" cellpadding="10">
            <tr>
                <td style="font-family: Calibri; font-size: 11px; text-align: center;"><b> Jefe de Dpto. de Abastecimiento y Logística:</b> </td>
                <td style="font-family: Calibri; font-size: 11px; text-align: center;"><b> Jefe de Unidad de Abastecimiento:</b> </td>

            </tr>
            <tr>
                <td align="center" style="font-family: Calibri; font-size: 9px;">
                    <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_jefe_depto_0, $fun_jefe_depto_1,$fun_jefe_depto_4,$fun_jefe_depto_3).'" alt="Logo">
                    <br>'.$fun_jefe_depto_0.'
                </td>
                <td align="center" style="font-family: Calibri; font-size: 9px;">
                    <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_abastecimiento_0, $fun_abastecimiento_1,$fun_abastecimiento_4,$fun_abastecimiento_3).'" alt="Logo">
                    <br>'.$fun_abastecimiento_0.'
                </td>

             </tr>
         </table>
         <table nowrap border="1" cellpadding="10">
             <tr>
                 <td style="font-family: Calibri; font-size: 11px; text-align: center;"><b> Representante de Gestión de Aeronavegabilidad continua:</b><br></td>
                 <td style="font-family: Calibri; font-size: 11px; text-align: center;"><b> Elaborado por:</b> </td>
             </tr>
             <tr>
                 <td align="center" style="font-family: Calibri; font-size: 9px;">
                     <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_aeronavegabilidad_0, $fun_aeronavegabilidad_1,$fun_aeronavegabilidad_4,$fun_aeronavegabilidad_3).'" alt="Logo">
                     <br>'.$fun_aeronavegabilidad_0.'
                 </td>
                 <td align="center" style="font-family: Calibri; font-size: 9px;">
                     <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_auxiliar_0, $fun_auxiliar_1,$fun_auxiliar_4,$fun_auxiliar_3).'" alt="Logo">
                     <br>'.$fun_auxiliar_0.'
                 </td>
              </tr>


              <!--Aqui la Firma de Marco Mendoza encargado RPCE-->
              <!--
              <tr>
                  <td colspan="2" style="font-family: Calibri; font-size: 11px; text-align: center;"><b> RPCE:</b> </td>
              </tr>
              <tr>
                  <td colspan="2" align="center" style="font-family: Calibri; font-size: 9px;">
                      <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_rpc_0, $fun_rpc_1,$fun_rpc_4,$fun_rpc_3).'" alt="Logo">
                      <br>'.$fun_rpc_0.'
                  </td>
               </tr>-->
          </table>';


      } else {
        $firmas = '
        <table border="1" cellpadding="10">
            <tr>
                <!--<td style="font-family: Calibri; font-size: 11px; text-align: center;"><b> Jefe de Unidad del Dpto. Abastecimiento y Logística:</b> </td>-->
                <td style="font-family: Calibri; font-size: 11px; text-align: center;"><b> Elaborado por:</b> </td>

            </tr>
            <tr>
                <td align="center" style="font-family: Calibri; font-size: 9px;">
                    <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_abastecimiento_0, $fun_abastecimiento_1,$fun_abastecimiento_4,$fun_abastecimiento_3).'" alt="Logo">
                    <br>'.$fun_abastecimiento_0.'
                </td>

             </tr>
         </table>
         <table border="1" cellpadding="10">
             <tr>
                 <td style="font-family: Calibri; font-size: 11px; text-align: center;"><b> Representante de Gestión de Aeronavegabilidad continua:</b><br></td>
                 <td style="font-family: Calibri; font-size: 11px; text-align: center;"><b> Técnico Adquisiciones:</b> </td>
             </tr>
             <tr>
                 <td align="center" style="font-family: Calibri; font-size: 9px;">
                     <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_aeronavegabilidad_0, $fun_aeronavegabilidad_1,$fun_aeronavegabilidad_4,$fun_aeronavegabilidad_3).'" alt="Logo">
                     <br>'.$fun_aeronavegabilidad_0.'
                 </td>
                 <td align="center" style="font-family: Calibri; font-size: 9px;">
                     <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_auxiliar_0, $fun_auxiliar_1,$fun_auxiliar_4,$fun_auxiliar_3).'" alt="Logo">
                     <br>'.$fun_auxiliar_0.'
                 </td>
              </tr>


              <!--Aqui la Firma de Marco Mendoza encargado RPCE-->
              <!--
              <tr>
                  <td style="font-family: Calibri; font-size: 11px; text-align: center;"><b> RPCE:</b> </td>
              </tr>
              <tr>
                  <td align="center" style="font-family: Calibri; font-size: 9px;">
                      <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_rpc_0, $fun_rpc_1,$fun_rpc_4,$fun_rpc_3).'" alt="Logo">
                      <br>'.$fun_rpc_0.'
                  </td>
               </tr>
               -->
          </table>';
      }







        $this->writeHTML($tb);
        $this->writeHTML($firmas);
        //$this->writeHTML($firmas2);
        $this->writeHTML($pie);

    }
    // function ReporteComiteEvaluacion2(){
    //     $this->AddPage();
    //     $tipo = $this->datos[0]['evaluacion'];
    //     $proveedor = $this->datos[0]['proveedor'];
    //     $cotizacionReci = $this->datos[0]['cotizaciones_recibidas'];
    //     $itemSeleccionados = $this->datos[0]['literal'];
    //     $talleAsignado = $this->datos[0]['taller_asignado'];
    //     $nro_partes = explode(',',$this->datos[0]["parte_solicitada"]);
    //
    //     if ($proveedor == NULL || $proveedor == '') {
    //       // code...
    //       $proveedor = 'Aun no se tiene un taller adjudicado';
    //     }
    //     $var = 'X';
    //
    //     $pie='<table border="1" cellpadding="10">
    //         <tr>';
    //
    //     $funcionario_rpce = $this->datos[0]["firma_rpce"];
    //     $fun_rpce = explode('|', $funcionario_rpce);
    //     /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
    //     if ($funcionario_rpce == NULL) {
    //       $fun_rpce_0 = 'SIN FIRMA';
    //       $fun_rpce_1 = '';
    //       $fun_rpce_3 = '';
    //       $fun_rpce_4 = '';
    //       $pie .= '<td align="center" >ACEPTACION [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; RECHAZA [&nbsp;&nbsp;&nbsp;&nbsp;'.$var.'&nbsp;&nbsp;&nbsp;&nbsp;]</td><br>
    //       ';
    //     } else {
    //       $fun_rpce_0 = $fun_rpce[0];
    //       $fun_rpce_1 = $fun_rpce[1];
    //       $fun_rpce_3 = $fun_rpce[3];
    //       $fun_rpce_4 = $fun_rpce[4];
    //       $pie .= ' <td align="center" >ACEPTACION [&nbsp;&nbsp;&nbsp;'.$var.'&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; RECHAZA [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</td><br>
    //       ';
    //     }
    //     /*************************************************************************/
    //
    //     $pie.=' </tr>
    //         <tr>
    //           <td align="justify"><b>Instruciones RPCE:</b> Adjudiquese el proceso de contratación de '.strtolower($itemSeleccionados).' item(s)  a la empresa '.$proveedor.', de acuerdo con la recomendación del comite de evaluacion de compra y selección de proveedor. Debiendo notificarse via Purchase Order a la empresa adjudicada.</td>
    //         </tr>
    //         <tr>
    //         <td style="font-family: Calibri;font-size: 11px"align="center"><b>RPCE</b></td>
    //         </tr>
    //         <tr>
    //         <td align="center" style="font-family: Calibri; font-size: 9px;">
    //             <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_rpce_0, $fun_rpce_1,$fun_rpce_4,$fun_rpce_3).'" alt="Logo">
    //             <br>'.$fun_rpce_0.'
    //         </td>
    //         </tr>
    //         </table>';
    //     $this->writeHTML($tb);
    //     $this->writeHTML($firmas);
    //     $this->writeHTML($firmas2);
    //     $this->writeHTML($pie);
    //
    // }
    function generarImagen($nom, $car, $ofi, $fecha){
        $cadena_qr = 'Nombre: '.$nom. "\n" . 'Cargo: '.$car."\n".'Fecha: '.$fecha ;
        $barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,M');
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../reportes_generados/" . $nom . ".png");
            imagedestroy($im);

        } else {
            echo 'A ocurrido un Error.';
        }
        $url_archivo = dirname(__FILE__) . "/../../reportes_generados/" . $nom . ".png";

        return $url_archivo;
    }

    function setDatos($datos)
    {
        $this->datos = $datos;
    }
    function generarReporte() {
        $this->SetMargins(10,40,10);
        $this->setFontSubsetting(false);
        //$this->AddPage();
        $this->SetMargins(10,40,10);
        $this->ReporteComiteEvaluacion();
        //$this->ReporteComiteEvaluacion2();
        //$this->revisarfinPagina();
    }
}
?>
