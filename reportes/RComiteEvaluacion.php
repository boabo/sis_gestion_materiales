<?php
class RComiteEvaluacion extends  ReportePDF
{
    function Header(){
        $this->Ln(8);
        $this->MultiCell(40, 25, '', 1, 'C', 0, '', '');
        $this->SetFontSize(12);
        $this->SetFont('', 'B');
        $this->MultiCell(105, 25, "\n" . 'COMITÉ DE EVALUACIÓN DE' . "\n" . 'COMPRA Y SELECCIÓN DE'."\n".'PROVEEDOR', 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, 25, "\n" . 'Form MA-01'."\n" .' '."\n" .'Rev 0 20/06/2016'."\n" .$this->datos[0]['nro_tramite'], 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib/images/Logo-BoA.png', 12, 15, 36);
    }
    function ReporteComiteEvaluacion(){
        $fechaPo= $this->datos[0]['fecha_po'];
        $fcotizaciones = $this->datos[0]['cotizacion_solicitadas'];
        $cotizacionReci = $this->datos[0]['proveedores_resp'];
        $proveedor = $this->datos[0]['desc_proveedor'];
        $neo_cotizacion =$this->datos[0]['nro_cotizacion'];
        $itemSeleccionados = preg_replace('/[0-9]+/', '', $this->datos[0]['item_selecionados']);
        $itemSeleccionados =str_replace(',/','',$itemSeleccionados);
        $itemSeleccionados =str_replace('/','',$itemSeleccionados);
        $tipo = $this->datos[0]['tipo_evaluacion'];
        $nroParte = explode(',',$this->datos[0]['nro_parte']);
        $a ='{';
        $b ='}';
        $remplace = '';
        $nroParte = str_replace($a,$remplace,$nroParte);
        $nroParte = str_replace($b,$remplace,$nroParte);
        $nomto = $this->datos[0]['monto_total'];
        $observaciones = $this->datos[0]['observacion_nota'];
        $talleAsignado = $this->datos[0]['taller_asignado'];
        $obs = $this->datos[0]['obs'];
        $simbolo = $this->datos[0]['codigo'];
        $mont = number_format($nomto, 2);
        $tb ='<table cellspacing="0" cellpadding="1" border="1">
              <tr>
                <th scope="col" width="60" align="center" ><b>Fecha:</b></th>
                <th scope="col" width="95" align="center" >'.$fechaPo.'</th>
                <th scope="col" width="195" > <b>Cotizaciones solicitadas:</b></th>
                <th scope="col" width="82"  align="center">'.$fcotizaciones.'</th>
                <th scope="col" width="193"> <b>Cotizaciones recibidas:</b></th>
                <th scope="col" width="70"  align="center">'.$cotizacionReci.'</th>
              </tr>';
        $tb .= '<tr>
                    <th scope="col" width="200" > <b>Cotizacion Seleccionada:</b></th>
                    <th scope="col" width="98"   align="center" >' . $neo_cotizacion . '</th>
                    <th scope="col" width="196"  align="center"><b>Tipo de Evaluacion:</b></th>
                    <th scope="col" width="200"   align="center" >' . $tipo . '</th>
                  </tr>';
        /*  if ($tipo == 'Compra') {
             $tb .= '<tr>
                     <th scope="col" width="200" > <b>Cotizacion Seleccionada:</b></th>
                     <th scope="col" width="98"   align="center" >' . $neo_cotizacion . '</th>
                     <th scope="col" width="196"  align="center"><b>Tipo de Evaluacion:</b></th>
                     <th scope="col" width="200"   align="center" >' . $tipo . '</th>
                     <th scope="col" width="100"  align="center" ><b>Reparación:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                     <th scope="col" width="100"  align="center" ><b>Exchange:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                   </tr>';
        }elseif ($tipo == 'Reparacion'){
             $tb .= '<tr>
                     <th scope="col" width="200" > <b>Cotizacion Seleccionada:</b></th>
                     <th scope="col" width="98"   align="center" >' . $neo_cotizacion . '</th>
                     <th scope="col" width="100"  align="center"><b>Compra:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                     <th scope="col" width="100"  align="center" ><b>Reparación:</b></th>
                     <th scope="col" width="32"   align="center" >SI</th>
                     <th scope="col" width="100"  align="center" ><b>Exchange:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                   </tr>';
         }elseif ($tipo == 'Exchage'){
             $tb .= '<tr>
                     <th scope="col" width="200" > <b>Cotizacion Seleccionada:</b></th>
                     <th scope="col" width="98"   align="center" >' . $neo_cotizacion . '</th>
                     <th scope="col" width="100"  align="center"><b>Compra:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                     <th scope="col" width="100"  align="center" ><b>Reparación:</b></th>
                     <th scope="col" width="32"   align="center" ></th>
                     <th scope="col" width="100"  align="center" ><b>Exchange:</b></th>
                     <th scope="col" width="32"   align="center" >SI</th>
                   </tr>';
         }*/
        $tb.=' <tr>
                        <th scope="col"  height="10"> <b>Empresa:</b></th>
                        <th scope="col" width="495"  align="justify"> '.$proveedor.'</th>
                   </tr>
                   <tr>
                        <th scope="col"  height="10" > <b>Items Seleccionados:</b></th>
                        <th scope="col" width="495"  align="justify">  '.$itemSeleccionados.'</th>
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
      <td width="150"><b>Comentario:</b></td>
      <td align="center" width="545" > El Comité de evaluación de acuerdo al cuadro comparativo adjunto y siendo la opción mas conveniente, recomienda la adjudicación al proveedor
           <br> <b>'.$proveedor.'</b> por un importe de <b>'.$simbolo.' '.$mont.'.</b></td>
    </tr>
    <br>
 <table border="1">

    <tr>

<td align="center" ><b>Part Number </b></td>
<td align="center" ><b>Descripción</b></td>
<td align="center" ><b>Cantidad </b></td>
<td align="center" ><b>Condición</b></td>
<td align="center" ><b>Tiempo Entrega</b></td>
</tr>
';
foreach ($this->datos as $value){

    $tb .= '<tr><td align="center" >'.$value['parte'].'</td>';
    $tb .= '<td align="center" >'.$value['descripcion_cot'].'</td>';
    $tb .= '<td align="center" >'.$value['cantidad_det'].'</td>';
    $tb .= '<td align="center" >'.$value['cd'].'</td>';
    $tb .= '<td align="center" >'.$value['codigo_tipo'].'</td>
 
 </tr>';
}
        $tb .= '

</table>
    <tr>
      <td width="150"><b>Observaciones:</b></td>
      <td align="justify" width="545" >'.$obs.'<br></td>
    </tr>
  


    </table>
    
    <table border="1">
    <tr>
    <td width="695"> <b>Para componentes reparados</b>
    <br> Las unidad(es) reparadas fueron enviadas a los talleres asignados? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$talleAsignado.'
    <br> <b>Observaciones:</b> '.$observaciones.'</td>
    </tr>
    <br>
    <tr>
    <td width="695"> <b>Observaciones para componentes en exchange:</b> '.$observaciones.'</td>
    </tr>
    ';
        $tb.='</table>';
        if($this->datos[0]["estado_materiales"] != 'comite_unidad_abastecimientos') {
            $revision = $this->datos[0]['visto_rev'];
            $fun_rev = explode('|', $revision);
            $primeraFirma = $this->codigoQr($revision, 'prime');
        }
        if(  $this->datos[0]["estado_materiales"] != 'comite_aeronavegabilidad' and $this->datos[0]["estado_materiales"] != 'comite_unidad_abastecimientos') {
            $abastecimiento = $this->datos[0]['visto_abas'];
            $fun_abas = explode('|', $abastecimiento);
            $segundaFirma = $this->codigoQr($abastecimiento, 'segundo');
        }
        if( $this->datos[0]["estado_materiales"] != 'comite_dpto_abastecimientos' and $this->datos[0]["estado_materiales"] != 'comite_unidad_abastecimientos' and $this->datos[0]["estado_materiales"] != 'comite_aeronavegabilidad'  ) {
            $aero = $this->datos[0]['aero'];
            $terceraFirma = $this->codigoQr($aero, 'tercera');
            $fun_aero = explode('|', $aero);
        }
        if($this->datos[0]["codigo_pres"] != 'vbrpc'and $this->datos[0]["estado_materiales"] != 'comite_dpto_abastecimientos' and $this->datos[0]["estado_materiales"] != 'comite_unidad_abastecimientos' and $this->datos[0]["estado_materiales"] != 'comite_aeronavegabilidad' and $this->datos[0]["estado_materiales"] != 'compra'  ) {
            $rpce = $this->datos[0]['funcionario_pres'];
            $CuartaFirma = $this->codigoQr($rpce, 'cuarto');
            $fun_rpcs = explode('|', $rpce);
        }
        //if ($this->datos[0]["codigo_pres"] != 'vbrpc' or $this->datos[0]["codigo_pres"] != 'suppresu'or $this->datos[0]["codigo_pres"] != 'vbgaf' ){
        $var = 'X';
        //}
        $firmas ='
       <table border="2">
         <tbody>
        <tr>    
                <td style="font-family: Calibri;font-size: 11px"align="center"><b>Jefe de Unidad del Dpto. Abastecimiento y logística</b><br>'.$fun_rev[0].'</td>
                <td style="font-family: Calibri;font-size: 11px"align="center"><b>Representante del Dpto. Abastecimiento y logística</b><br>'.$fun_abas[0].'</td>
        </tr>
        <tr>
                <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $primeraFirma . '" alt="Logo"><br></td>
                <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $segundaFirma . '" alt="Logo"><br></td>
         </tr>
         </tbody>
        </table>
        ';
        $fun_presu = explode('|', $aero);

        $firmas2 ='
       <table border="2">
         <tbody>
        <tr>    
                <td style="font-family: Calibri;font-size: 11px"align="center"><b>'.$fun_presu[1].'</b><br>'.$fun_aero[0].'</td>
                <td style="font-family: Calibri;font-size: 11px"align="center"><b>RPCE</b><br>'.$fun_rpcs[0].'</td>
        </tr>
        <tr>
                <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $terceraFirma . '" alt="Logo"></td>
                <td align="center"><br><br><img  style="width: 95px; height: 95px;" src="' . $CuartaFirma . '" alt="Logo"><br></td>
         </tr>
         </tbody>
        </table>
        ';
        $pie='<table border="1">
            <tr>';
        if ($this->datos[0]["codigo_pres"] == 'vbrpc' or $this->datos[0]["codigo_pres"] == 'suppresu'or $this->datos[0]["codigo_pres"] == 'vbgaf' or $this->datos[0]["codigo_pres"] == 'vbpresupuestos') {
            $pie .= '  <td align="center" >ACEPTACION [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; RECHAZA [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</td><br>
            ';
        }else{
            $pie .= ' <td align="center" >ACEPTACION [&nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; RECHAZA [&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</td><br>
            ';
        }
        $pie.=' </tr>
            <tr>
              <td align="justify"><b>Instruciones RPCE:</b> Adjudiquese el proceso de contratación de '.strtolower($itemSeleccionados).' items  a la empresa '.$proveedor.', de acuerdo con la recomendación del comite de evaluacion de compra y selección de proveedor. Debiendo notificarse via Purchase Order a la empresa adjudicada.</td>
            </tr>
            </table>';
        $this->writeHTML($tb);
        $this->writeHTML($firmas);
        $this->writeHTML($firmas2);
        $this->writeHTML($pie);
    }
    function  codigoQr ($cadena,$ruta){
        $barcodeobj = new TCPDF2DBarcode($cadena, 'QRCODE,M');
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../reportes_generados/".$ruta.".png");
            imagedestroy($im);
        } else {
            echo 'An error occurred.';
        }
        $url =  dirname(__FILE__) . "/../../reportes_generados/".$ruta.".png";
        return $url;
    }

    function setDatos($datos)
    {
        $this->datos = $datos;
    }
    function generarReporte() {
        $this->SetMargins(10,40,10);
        $this->setFontSubsetting(false);
        $this->AddPage();
        $this->SetMargins(10,40,10);
        $this->ReporteComiteEvaluacion();
        //$this->revisarfinPagina();
    }
}
?>