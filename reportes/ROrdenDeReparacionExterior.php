<?php
class ROrdenDeReparacionExterior extends  ReportePDF
{
    function Header(){
        $this->Ln(8);
        $this->MultiCell(40, 25, '', 1, 'C', 0, '', '');
        $this->SetFontSize(12);
        $this->SetFont('', 'B');
        $this->MultiCell(105, 25, "\n" . 'REPAIR ORDER: ' .$this->datos[0]['num_tramite']. "\n" . 'ORDEN DE REPARACION EXTERIOR'."\n", 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, 25, "\n" . 'FORM.GMM-M-4.05'."\n", 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib/images/Logo-BoA.png', 12, 15, 36);
    }
    function ReporteOrdenRaparacion(){
        $this->AddPage();

        if ($this->datos[0]['po_type'] == 'Reparacion'){
           $type = 'REPAIR';
        } elseif ($this->datos[0]['po_type'] == 'Compra') {
           $type = 'PURCHASE';
        } else {
           $type = $this->datos[0]['po_type'];
        }

        $nro_partes = explode(',',$this->datos[0]["num_part"]);
        $nro_partes_alternas = explode(',',$this->datos[0]["num_part_alt"]);
        $cantidad = explode(',',$this->datos[0]["cantidad"]);
        $descripcion = explode(',',$this->datos[0]["descripcion"]);
        $serial = explode(',',$this->datos[0]["serial"]);
        $cd = explode(',',$this->datos[0]["cd"]);
        $precio_unitario = explode(',',$this->datos[0]["precio_unitario"]);
        //var_dump("el precio es",$precio_unitario);
        $precio_total = explode(',',$this->datos[0]["precio_total"]);

        $firma_rpce = $this->datos[0]["firma_rpc"];
        $fir_rpce = explode('|', $firma_rpce);

        /*Aqui ponemos las condiciones para que no genere el error por las firmas*/
        if ($firma_rpce == NULL || $firma_rpce == '') {
          $fun_preparado_0 = ' ';
          $fun_preparado_1 = '';
          $fun_preparado_3 = '';
          $fun_preparado_4 = '';
        } else {
          $fun_preparado_0 = $fir_rpce[0];
          $fun_preparado_1 = $fir_rpce[1];
          $fun_preparado_3 = $fir_rpce[3];
          $fun_preparado_4 = $fir_rpce[4];
        }

        $tb_funcio ='<table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                      <tr>
                        <th width="550" align="left" >Boliviana de Aviación-BoA</th>
                        <th width="145" align="left" ><b style = "font-size:15px;">REP: '.$this->datos[0]['rep'].'</b></th>
                      </tr>
                      <tr>
                        <th width="400" align="left" >e-mail: reparaciones@boa.bo</th>
                        <th width="295" align="left" >PO TYPE: REPAIR</th>
                      </tr>
                      <tr>
                        <th width="400" align="left" >Fax #: 591-4-4140871</th>
                        <th width="295" align="left" >ORDER DATE: '.$this->datos[0]['fecha_order'].'</th>
                      </tr>
                      <tr>
                        <th width="400" align="left" >Phone #: 591-4-4150000 INT 4131</th>
                        <th width="295" align="left" >PRIORITY: '.$this->datos[0]['priority'].'</th>
                      </tr>
                      <tr>
                        <th width="300" align="left" ><b>Av. Killman esq. C.Torrico ExTerminal Aérea</b></th>
                      </tr>
                    </table>
                    <table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                          <tr>
                            <th width="400" align="left" ><b>VENDOR: '.$this->datos[0]['nom_provee'].'</b> </th>
                            <th width="295" align="left" >NIT: '.$this->datos[0]['dni'].'</th>
                          </tr>
                          <tr>
                            <th width="400" align="left" >ADDRESS: '.$this->datos[0]['direcc_provee'].'</th>
                            <th width="295" align="left" >ZIP CODE: </th>
                          </tr>
                          <tr>
                            <th width="400" align="left" >STATE: '.$this->datos[0]['estado_provee'].'</th>
                            <th width="295" align="left" >COUNTRY: '.$this->datos[0]['country_provee'].'</th>
                          </tr>
                          <tr>
                            <th width="400" align="left" >CONTACT: '.$this->datos[0]['contacto_proveedor'].'</th>
                            <th width="147" align="left" >PHONE: '.$this->datos[0]['telf_provee'].'</th>
                            <th width="148" align="left" >FAX: '.$this->datos[0]['fax_provee'].'</th>
                          </tr>
                          <tr>
                            <th width="400" align="left" >E-mail: '.$this->datos[0]['email_provee'].'</th>
                            <th width="295" align="left" >AOG CELL: </th>
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
                           <td width="70" align="center"><b>SERIAL</b></td>
                           <td width="75" align="center"><b>CD</b></td>
                           <td width="80" align="center"><b>UNIT PRICE</b></td>
                           <td width="80" align="center"><b>SUBTOT.</b></td>
                       </tr>
                    </table>';

                    $numero = 1;
                    foreach ($nro_partes as $indice=>$partes){
                      //B.E.R.
                      if ($cd[$indice] == 'AS-IS') {
                        if ($precio_unitario[$indice] > 0) {
                          $descipcion = 'S/N: '.$serial[$indice].' RETORNARÁ EN CONDICIÓN '.$cd[$indice].' CON UN COSTO DE EVALUACIÓN IGUAL A '.$precio_unitario[$indice];
                        } else {
                          $descipcion = 'S/N: '.$serial[$indice].' RETORNARÁ EN CONDICIÓN '.$cd[$indice].' SIN NINGÚN COSTO DE EVALUACIÓN';
                        }

                      } elseif ($cd[$indice] == 'B.E.R.') {
                        if ($precio_unitario[$indice] > 0) {
                          $descipcion = 'S/N: '.$serial[$indice].' RETORNARÁ EN CONDICIÓN '.$cd[$indice].' CON UN COSTO DE EVALUACIÓN IGUAL A '.$precio_unitario[$indice];
                        } else {
                          $descipcion = 'S/N: '.$serial[$indice].' RETORNARÁ EN CONDICIÓN '.$cd[$indice].' SIN NINGÚN COSTO DE EVALUACIÓN';
                        }
                      }


                      if ($cd[$indice] == 'AS-IS') {
                        $condicion = '<td width="75" align="center"><li>'.$cd[$indice].'</li></td>
                                      <td width="80" align="right"><li>'.number_format($precio_unitario[$indice], 2, ',', '.').'</li></td>
                                      <td width="80" align="right"><li>'.number_format($precio_total[$indice], 2, ',', '.').'</li></td>
                                      </tr>
                                      <tr>
                                        <td colspan="8" align="center"><li><b>'.$descipcion.'</b></li></td>
                                      </tr>
                                      ';

                      } elseif ($cd[$indice] == 'B.E.R.') {
                        $condicion = '<td width="75" align="center"><li>'.$cd[$indice].'</li></td>
                                      <td width="80" align="right"><li>'.number_format($precio_unitario[$indice], 2, ',', '.').'</li></td>
                                      <td width="80" align="right"><li>'.number_format($precio_total[$indice], 2, ',', '.').'</li></td>
                                      </tr>
                                      <tr>
                                        <td colspan="8" align="center"><li><b>'.$descipcion.'</b></li></td>
                                      </tr>
                                      ';
                      } else {
                        $condicion = '<td width="75" align="center"><li>'.$cd[$indice].'</li></td>
                                      <td width="80" align="right"><li>'.number_format($precio_unitario[$indice], 2, ',', '.').'</li></td>
                                      <td width="80" align="right"><li>'.number_format($precio_total[$indice], 2, ',', '.').'</li></td>
                                    </tr>';
                      }

                    $tb_funcio.= '
                        <table cellspacing="0" cellpadding="1" border="1" style="font-size:11px;">
                            <tr>
                                <td width="50" align="center"><li>'.$numero.'</li></td>
                                <td width="120" align="center"><li>'.$partes.'</li></td>
                                <td width="50" align="center"><li>'.$cantidad[$indice].'</li></td>
                                <td width="170" align="center"><li>'.$descripcion[$indice].'</li></td>
                                <td width="70" align="center"><li>'.$serial[$indice].'</li></td>
                                '.$condicion.'
                        </table>
                            ';
                        $numero++;
                     }
                     //var_dump("la tabla llega",$tb_funcio);
                     $tb_funcio.= '
                      <table cellspacing="0" cellpadding="1" border="1" style="font-size:11px;">
                        <tr>
                          <td style="width: 535px; text-align: right;" colspan="6"><b>TOTAL AMOUNT: </b></td>
                          <td style="width: 80px; text-align: center;"><b>$us</b></td>
                          <td style="width: 80px; text-align: right;">'.number_format($this->datos[0]['suma_total'], 2, ',', '.').'</td>
                        </tr>
                      </table>
                      <table cellspacing="0" cellpadding="1" style="font-size:11px; border:1px solid #000000;">
                        <tr>
                          <td style="width: 695px;" colspan="6"><b>OBSERVATIONS:</b> El servicio deberá ser realizado a la especificación técnica, contización y la presente orden</td>
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
                              <th width="295" align="left" >DELIVERY DATE: '.$this->datos[0]['delivery_date'].'</th>
                            </tr>
                            <tr>
                              <th width="400" align="left" ><b>Cochabamba-Bolivia</b></th>
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
                      </table>
                      <table cellspacing="0" cellpadding="5" style="font-size:11px; border:1px solid #000000;">
                            <tr>
                            <th align="center" style="font-family: Calibri; font-size: 9px;">
                                <img  style="width: 95px; height: 95px;" src="' . $this->generarImagen($fun_preparado_0, $fun_preparado_1,$fun_preparado_4,$this->datos[0]['fecha_order']).'" alt="Logo">
                                <br>'.$fun_preparado_0.'
                            </th>
                            </tr>
                      </table>';

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
