<?php

class RRequemientoMaterielesMan extends  ReportePDF
{
    function Header()
    {
        $height = 25;
        $this->ln(8);
        $this->MultiCell(40, $height, '', 1, 'C', 0, '', '');
        //$this->Cell(40, $height, '', 1, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(15);
        $this->SetFont('', 'B');
        $this->MultiCell(105, $height, "\n" .'COMITÉ DE EVALUACIÓN TÉCNICA'."\n".'REPUESTOS AERONÁUTICOS', 1, 'C', 0, '', '');
        $this->SetFont('times', 'B', 10);
        $this->MultiCell(0, $height, "\n" . 'From. MOM-019' . "\n" . '' . "\n" . 'Rev.2 19/10/16', 1, 'C', 0, '', '');
        $this->Image(dirname(__FILE__) . '/../../pxp/lib' . $_SESSION['_DIR_LOGO'], 17, 15, 36);

    }
    function Footer() {
        $this->setY(-15);
        $ormargins = $this->getOriginalMargins();
        $this->SetTextColor(0, 0, 0);
        //set style for cell border
        $line_width = 0.85 / $this->getScaleFactor();
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
        $this->Ln(2);
        $cur_y = $this->GetY();
        //$this->Cell($ancho, 0, 'Generado por XPHS', 'T', 0, 'L');
        $this->Cell($ancho, 0, 'Usuario: '.$_SESSION['_LOGIN'], '', 0, 'L');
        $pagenumtxt = 'Página'.' '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
        $this->Cell($ancho, 0, $pagenumtxt, '', 0, 'C');
        $this->Cell($ancho, 0, $_SESSION['_REP_NOMBRE_SISTEMA'], '', 0, 'R');
        $this->Ln();
     //   $fecha_rep = date("d-m-Y H:i:s");
      //  $this->Cell($ancho, 0, "Fecha : ".$fecha_rep, '', 0, 'L');
        $this->Ln($line_width);
        $this->Ln();
        $barcode = $this->getBarcode();
        $style = array(
            'position' => $this->rtl?'R':'L',
            'align' => $this->rtl?'R':'L',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'padding' => 0,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false,
            'text' => false,
            'position' => 'R'
        );
        $this->write1DBarcode($barcode, 'C128B', $ancho*2, $cur_y + $line_width+5, '', (($this->getFooterMargin() / 3) - $line_width), 0.3, $style, '');

    }
    function reporteRequerimiento(){
        $this->SetFont('times', 'B', 12);
        $this->Cell(75, 7, ' Datos Generales', 1, 0, 'L', 0, '', 0);
        $this->Cell(60, 7, ' Número Tramite', 1, 0, 'L', 0, '', 0);
        $this->Cell(0, 7, $this->datos[0]['nro_tramite'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 12);
        $this->SetFont('times', 'B', 12);
        $this->Cell(15, 7, ' Fecha', 1, 0, 'C', 0, '', 0);
        $this->Cell(60, 7,  $this->datos[0]['fecha_solicitud'], 1, 0, 'C', 0, '', 0);
        if($this->datos[0]['flota'] == 'FLOTA') {
            $this->Cell(60, 7,  '  '.$this->datos[0]['matricula'], 1, 0, 'L', 0, '', 0);
        }else{
            $this->Cell(60, 7, ' Matrícula: ' . $this->datos[0]['matri'], 1, 0, 'L', 0, '', 0);
        }
        if($this->datos[0]['flota'] == 'FLOTA') {
            $this->Cell(0, 7, '', 1, 0, 'C', 0, '', 0);
        }else{
            $this->Cell(0, 7, $this->datos[0]['matricula'], 1, 0, 'C', 0, '', 0);
        }

        $fecha = date_create($this->datos[0]['fecha_soli']);
        $fecha_base = date_create('01-04-2019');

        //var_dump($prueba);

        if ($fecha >= $fecha_base) {
          $condicion = $this->datos[0]['condicion'];
          $condicion_tabla='<tr><th colspan="2" ><b>Condición:</b>'.' '.$condicion.'</th></tr>';
          $this->ln();
          $this->Cell(0, 7, ' Especificación Técnica Material a Solicitar', 1, 1, 'L', 0, '', 0);
        } else {
          $this->ln();
        }
        //$this->ln();
        //$this->ln();
        $this->SetFont('times', '', 12);
        $this->SetFont('', 'B', 12);
        $this->Cell(15, 0, 'N°', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Número de Parte', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Referencia', 1, 0, 'C', 0, '', 0);
        $this->Cell(61, 0, 'Descripcion', 1, 0, 'C', 0, '', 0);
        $this->Cell(20, 0, 'Cantidad', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'U/M', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $numero = 1;
        foreach ($this->datos as $Row) {
            $parte = $Row['nro_parte'];
            $referencia = $Row['referencia'];
            $descripcion = $Row['descripcion'];
            $cantidad = $Row['cantidad_sol'];
            $unidad = $Row['unidad_medida'];

            $tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <td width="54" align="center">$numero</td>
        <td width="123" align="center">$parte</td>
        <td width="124" align="center">$referencia</td>
        <td width="218" align="justify">$descripcion</td>
        <td width="70" align="center">$cantidad</td>
        <td width="70" align="center">$unidad</td>
    </tr>


</table>
EOD;
            $this->SetFont('times', '', 11);
            $this->writeHTML($tbl, false, false, false, false, '');
            $numero++;

        }
        $motivo = $this->datos[0]['motivo_solicitud'];
        $tipoReporte = $this->datos[0]['tipo_reporte'];
        $justificacion= $this->datos[0]['justificacion'];
        $tipo_solicitud= $this->datos[0]['tipo_solicitud'];
        $fecha_requerida= $this->datos[0]['fecha_requerida'];
        $observaciones_sol = $this->datos[0]['observaciones_sol'];
        $mel = $this->datos[0]['mel'];

        $html = <<<EOD
 <table cellspacing="0" cellpadding="1" border="1">
  $condicion_tabla
  <tr>
    <th colspan="2" > <b>Motivo de la Solicitud</b></th>
  </tr>
  <tr>
    <th colspan="2" style="text-align:justify"> $motivo</th>
  </tr>
  <tr>
    <td width="191" height="45"  align="center" ><b>Justificación de Necesidad</b></td>
     <td width="150" height="45" align="center"><b>Tipo de Reporte</b></td>
    <td width="150" height="45" align="center"><b>Tipo de Solicitud</b></td>
    <td width="168" height="45" align="center"><b>Fecha Requerida de Llegada</b>
    </td>
  </tr>
  <tr>
    <td width="191" height="60" align="center" >$justificacion</td>
     <td width="150" height="60" align="center">$tipoReporte</td>
    <td width="150" height="60" align="center">$tipo_solicitud</td>
    <td width="168" height="60" align="center">$fecha_requerida<p align="left">  MEL: $mel</p></td>
  </tr>
  <tr>
    <th colspan="3" style="text-align:justify"> <b>Observaciones:</b> $observaciones_sol</th>
  </tr>
</table>
EOD;
        //var_dump($pagina);exit;
        $this->SetFont('times', '', 12);
        $this->writeHTML($html, true, false, false, false, '');
        $funcionario_solicitante = $this->datos[0]['desc_funcionario1'];
        $fecha_ab = 'Fecha: '.$this->datos[0]['fecha_fir'];
       // $Revisado_vb= $this->datos2[0]['funcionario_bv'];
       // $VB_DAC= $this->datos2[1]['funcionario_bv'];
        $VB_DAC= 'Pedro Wilfredo Triveño Herrera';
        $Revisado_vb= 'Roger Wilmer Balderrama Angulo';
        $Abastecimiento = 'Abastecimiento';
        $esta = 'borrador';
        $fecha_sol ='Fecha: '.$this->datos[0]['fecha_solicitud'];
        $fecha_vb = 'Fecha: '.$this->datos2[0]['fecha_ini'];
        $fecha_dac ='Fecha: '. $this->datos2[1]['fecha_ini'];


        if ($this->datos[0]['estado'] != 'borrador') {

            $qr1 = $this->codigoQr('Funcionario Solicitante: ' . $this->datos[0]['desc_funcionario1'] . "\n" . 'Nro. Pedido: ' . $this->datos[0]['nro_tramite'] . "\n" . 'Tipo Solicitud: ' . $this->datos[0]['tipo_solicitud'] . "\n" ,'primera_firma');
            $fun =  $this->datos[0]['desc_funcionario1'];
            $fe_so =$fecha_sol;
        }
        if($this->datos[0]['estado_firma'] != 'vobo_area' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr2 = $this->codigoQr('Encargado: '.$this->datos2[0]['visto_bueno']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n",'segunda_firma');
            $frev =  $this->datos2[0]['visto_bueno'];
            $fe_vo=$fecha_vb;
        }

        if( $this->datos[0]['estado_firma'] != 'vobo_aeronavegabilidad' and $this->datos[0]['estado_firma'] != 'vobo_area' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr3 = $this->codigoQr('Encargado: '.$this->datos2[0]['aero']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n",'tercera_firma');
            $dac =  $this->datos2[0]['aero'];
            $fe_dac=$fecha_dac;
        }

        if($this->datos[0]['estado'] != 'revision' and $this->datos[0]['estado'] != 'borrador') {
            $qr4 = $this->codigoQr('Encargado: '.$this->datos2[0]['visto_ag']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n",'cuarta_firma');
            $fab =  $this->datos2[0]['visto_ag'];
            $fe_ab = $fecha_ab;
        }
        $tbl = <<<EOD

        <table cellspacing="0" cellpadding="1" border="1">
        <tr>
        <td align="center" > <b>Unidad C & S/Control Producción</b></td>
        <td align="center" ><b>Gerencia de Mantenimiento</b> </td>
        </tr>
        <tr>
           <td align="center" >
           $fun
            <br><br>
            <img  style="width: 100px;" src="$qr1" alt="Logo">
            <br>
        </td>
        <td align="center" >
        $frev
        <br><br>
            <img  style="width: 100px;" src="$qr2" alt="Logo">

         </td>
         </tr>
         <tr>
            	<td align="justify"  colspan="2"><b>Evaluado y Analizado por AOC - 121</b>
            	<br></td>
        </tr>
         <tr>
        <td align="center" > <b>V.B. DAC:</b> $dac</td>
        <td align="center" >  <b>Recibido:</b> $fab</td>
        </tr>
        <tr>
        <td align="center" >
            <br><br>
            <img  style="width: 100px;" src="$qr3" alt="Logo">
            <br>
            </td>
        <td align="center" >
        <br><br>
            <img  style="width: 100px;" src="$qr4" alt="Logo">

         </td>
         </tr>

        </table>

EOD;
        $this->SetFont('times', '', 12);
        $this->writeHTML($tbl, true, false, false, false, '');

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

    function setDatos($datos,$datos2,$datos3) {
        $this->datos = $datos;
        $this->datos2 = $datos2;
        $this->datos3 = $datos3;
    }
    function generarReporte() {
        $this->SetMargins(15,40,15);
        $this->setFontSubsetting(false);
        $this->AddPage();
        $this->SetMargins(15,40,15);
        $this->reporteRequerimiento();

    }

}

?>
