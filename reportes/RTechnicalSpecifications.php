<?php
class RTechnicalSpecifications extends  ReportePDF
{
    function Header(){
        $this->Ln(8);
        $this->MultiCell(40, 25, '', 1, 'C', 0, '', '');
        $this->SetFontSize(12);
        $this->SetFont('', 'B');
        $this->MultiCell(105, 25, "\n" . 'TECHNICAL '."\n".'SPECIFICATIONS'."\n", 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, 25, "\n" . $this->datos[0]['num_tramite']."\n", 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib/images/Logo-BoA.png', 12, 15, 36);
    }
    function ReporteOrdenRaparacion(){
        $this->AddPage();
        $funcionario_aprobado = $this->datos[0]["aprobado_por"];
        $fun_aprobado = explode('|', $funcionario_aprobado);
        //var_dump("aqui llega el dato",$funcionario_aprobado);
        /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
        if ($funcionario_aprobado == NULL || $funcionario_aprobado == '') {
          $fun_aprobado_0 = ' ';
          $fun_aprobado_1 = '';
          $fun_aprobado_3 = '';
          $fun_aprobado_4 = '';
        } else {
          $fun_aprobado_0 = $fun_aprobado[0];
          $fun_aprobado_1 = $fun_aprobado[1];
          $fun_aprobado_3 = $fun_aprobado[3];
          $fun_aprobado_4 = $fun_aprobado[4];
        }
        /*************************************************************************/

        $funcionario_pre = $this->datos[0]["preparado_por"];
        $fun_pre = explode('|', $funcionario_pre);

        /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
        if ($funcionario_pre == NULL || $funcionario_pre == '') {
          $fun_preparado_0 = ' ';
          $fun_preparado_1 = '';
          $fun_preparado_3 = '';
          $fun_preparado_4 = '';
        } else {
          $fun_preparado_0 = $fun_pre[0];
          $fun_preparado_1 = $fun_pre[1];
          $fun_preparado_3 = $fun_pre[3];
          $fun_preparado_4 = $fun_pre[4];
        }
        /*************************************************************************/

        if ($this->datos[0]["num_part"] != '' || $this->datos[0]["num_part"] != null) {
              $repair = 'FOR REPAIR';
        } else {
            $repair = '';
        }

        $nro_partes = explode(',',$this->datos[0]["num_part"]);
        $nro_partes_alternas = explode(',',$this->datos[0]["num_part_alt"]);
        $cantidad = explode(',',$this->datos[0]["cantidad"]);
        $descripcion = explode(',',$this->datos[0]["descripcion"]);
        $serial = explode(',',$this->datos[0]["serial"]);
        $cd = explode(',',$this->datos[0]["cd"]);
        $precio_unitario = explode(',',$this->datos[0]["precio_unitario"]);
        $precio_total = explode(',',$this->datos[0]["precio_total"]);


        $tb_funcio ='<table cellspacing="0" cellpadding="3" style="font-size:11px; border:1px solid #000000;">
                      <tr>
                        <th width="400" align="left" >Boliviana de Aviación-BoA</th>
                        <th width="295" align="left" >PO TYPE: REPAIR</th>
                      </tr>
                      <tr>
                        <th width="400" align="left" >e-mail: reparaciones@boa.bo</th>
                        <th width="295" align="left" >DATE: '.$this->datos[0]['fecha_order'].'</th>
                      </tr>
                      <tr>
                        <th width="400" align="left" >Fax #: 591-4-4140871</th>
                        <th width="295" align="left" >PRIORITY: '.$this->datos[0]['priority'].'</th>
                      </tr>
                      <tr>
                        <th width="450" align="left" >Phone #: 591-4-4150000 INT 4131</th>
                        <th width="145" align="left" ><b style="font-size:15px;">REP: '.$this->datos[0]['rep'].'</b></th>
                      </tr>
                    </table>
                    <table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                          <tr>
                            <th width="695" align="left" >PAYMENTS TERMS: '.$this->datos[0]['payment_terms'].'</th>
                          </tr>
                          <tr>
                            <th width="695" align="left" >INCOTERMS: '.$this->datos[0]['incoterms'].'</th>
                          </tr>
                    </table>
                    <table cellspacing="0" cellpadding="1" border="1" style="font-size:11px;" >
                       <tr>
                           <td width="50" align="center"><b>ITEM</b></td>
                           <td width="120" align="center"><b>PART NUMBER</b></td>
                           <td width="50" align="center"><b>QTY</b></td>
                           <td width="170" align="center"><b>DESCRIPTION</b></td>
                           <td width="100" align="center"><b>SERIAL</b></td>
                           <td width="75" align="center"><b>CD</b></td>
                           <td width="130" align="center"><b>REPAIR INSTRUCTION</b></td>
                       </tr>
                    </table>';

                    $numero = 1;
                    foreach ($nro_partes as $indice=>$partes){
                    $tb_funcio.= '
                        <table cellspacing="0" cellpadding="1" border="1" style="font-size:11px;">
                            <tr>
                                <td width="50" align="center"><li>'.$numero.'</li></td>
                                <td width="120" align="center"><li>'.$partes.'</li></td>
                                <td width="50" align="center"><li>'.$cantidad[$indice].'</li></td>
                                <td width="170" align="center"><li>'.$descripcion[$indice].'</li></td>
                                <td width="100" align="center"><li>'.$serial[$indice].'</li></td>
                                <td width="75" align="center"><li>AR</li></td>
                                <td width="130" align="center"><li>'.$cd[$indice].'</li></td>
                            </tr>
                        </table>
                            ';
                        $numero++;
                     }

                     $tb_funcio.= '
                      <table cellspacing="0" cellpadding="1" border="1" style="font-size:11px;">
                        <tr>
                          <td width="695"> </td>
                        </tr>
                      </table>
                      <table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                        <tr>
                          <td style="width: 695px;" colspan="6"><b>OBSERVACIONES:</b> '.$this->datos[0]['observaciones_sol'].'</td>
                        </tr>
                      </table>
                      <table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                        <tr>
                          <td style="width: 695px;" colspan="6"><b>NOTA: Según procedimientos y Manual de Mantenimiento el Número de Part Number (P/N)
                          es el que identifica al componente, por lo que la descripción puede variar entre proveedores y/o talleres reparadores.</b></td>
                        </tr>
                      </table>
                      <table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                            <tr>
                              <th width="400" align="left" ><b>BILL TO: </b></th>
                              <th width="295" align="left" >SHIP VIA: </th>
                            </tr>
                            <tr>
                              <th width="400" align="left" >Boliviana de Aviación</th>
                              <th width="295" align="left" >INCOTERMS:</th>
                            </tr>
                            <tr>
                              <th width="400" align="left" >Aeropuerto Jorge Wilsterman</th>
                              <th width="295" align="left" >ACCOUNT:</th>
                            </tr>
                            <tr>
                              <th width="400" align="left" >Av. Killman esq. C. Torrico Ex Terminal aerea</th>
                            </tr>
                            <tr>
                              <th width="400" align="left" ><b>Cochabamba-Bolivia</b></th>
                            </tr>
                      </table>
                      <table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                            <tr>
                              <th align="left" >La entrega del componente (s) es en el Forwarder Miami de acuerdo a propuesta de servicio de reparación del taller después de la notificación del Repair Order, sin embargo la recepción del mismo en almacén Cochabamba será hasta en '.$this->datos[0]['tiempo_entrega'].' días.</th>
                            </tr>
                      </table>
                      <table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                            <tr>
                              <th align="left" ><b>SHIP TO: </b></th>
                            </tr>
                            <tr>
                              <th align="left" >Express International Freight, LLC</th>
                            </tr>
                            <tr>
                              <th  align="left" >8345 NW 74th Street</th>
                            </tr>
                            <tr>
                              <th  align="left" >Medley FL 33166</th>
                            </tr>
                            <tr>
                              <th  align="left" ><b>Tel: 305-477-4041</b></th>
                            </tr>
                      </table>';

                      if ($this->datos[0]["tipo_evaluacion"] == 'Calibracion') {
                        $tb_funcio.='<table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                                            <tr>
                                              <th align="left" ><b>OBSERVATIONS: The repair instruction was performed out of the request of the performing
                                              technitian and under the supervisor authorization.</b></th>
                                            </tr>
                                      </table>';
                      } else {
                        $tb_funcio.='<table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                                            <tr>
                                              <th align="left" ><b>OBSERVATIONS: The repair instruction was performed out of the request of the performing
                                              technitian and under the supervisor authorization, attached Form MOM-005 (Tarjeta Parte Reparable)</b></th>
                                            </tr>
                                      </table>';
                      }


                      $tb_funcio.= '<table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                          <tr> <br>
                              <td style="font-family: Calibri; font-size: 9px; text-align: center;"><b> Prepared by:</b> </td>
                              <td style="font-family: Calibri; font-size: 9px; text-align: center;"><b> Aproved by:</b><br></td>
                          </tr>
                          <tr>
                              <td align="center" style="font-family: Calibri; font-size: 9px;">
                                  <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_preparado_0, $fun_preparado_1,$fun_preparado_4,$fun_preparado_3).'" alt="Logo">
                                  <br>'.$fun_preparado_0.'
                              </td>
                              <td align="center" style="font-family: Calibri; font-size: 9px;">
                                  <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_aprobado_0, $fun_aprobado_1,$fun_aprobado_4,$fun_aprobado_3).'" alt="Logo">
                                  <br>'.$fun_aprobado_0.'
                              </td>
                           </tr>
                       </table>
                      ';

        $this->writeHTML($tb_funcio);
        $this->writeHTML($pie);

    }
    function generarImagen($nom, $car, $ofi, $fecha){
        $cadena_qr = 'Nombre: '.$nom. "\n" . 'Cargo: '.$car."\n".'Oficina: '.$ofi."\n".'Fecha: '.$fecha ;
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
        $this->ReporteOrdenRaparacion();
    }
}
?>
