<?php

class RRequerimientoMaterialesCeac extends  ReportePDF
{
    function Header()
    {
        $height = 25;
        $this->ln(8);
        $this->MultiCell(40, $height, '', 1, 'C', 0, '', '');
        //$this->Cell(40, $height, '', 1, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFontSize(15);
        $this->SetFont('', 'B');
        $this->MultiCell(105, $height, "\n" .'CENTRO DE ENTRENAMIENTO AERONAUTICO CIVIL'."\n", 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, $height, "\n" . '' . "\n" . '' . "\n" . '', 1, 'C', 0, '', '');
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
        $this->Cell(0, 7, ' Datos Generales', 1, 0, 'L', 0, '', 0);
        $this->ln();
        $htmlCabe = '<table border="1">
            <tbody>
            <tr>
            <td> <b>Número Tramite:</b> '.$this->datos[0]['nro_tramite'].'</td>
            <td> <b>Fecha Solicitud:</b> '. $this->datos[0]['fecha_solicitud'].'</td>
            </tr>
            <tr>
            <td > <b>Nro. No Rutina:</b> '.$this->datos[0]['nro_no_rutina'].'</td>
            <td > <b>Fecha Requerida:</b> '.$this->datos[0]['fecha_requerida'].'</td>
            </tr>
            <tr>
            <th colspan="2" style="text-align:justify"> <b>Tipo de Solicitud:</b> '.$this->datos[0]['tipo_solicitud'].'</th>
            </tr>
            <tr>
            <th colspan="2" style="text-align:justify"><b>Motivo de la Solicitud:</b> '.$this->datos[0]['motivo_solicitud'].'</th>
            </tr>
            </tbody>
            </table>';
        $this->SetFont('', '', 12);
        $this->writeHTML($htmlCabe);
        $this->SetFont('', 'B', 12);
        $this->Cell(10, 0, 'N°', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Número de Parte', 1, 0, 'C', 0, '', 0);
        $this->Cell(35, 0, 'Referencia', 1, 0, 'C', 0, '', 0);
        $this->Cell(56, 0, 'Descripcion', 1, 0, 'C', 0, '', 0);
        $this->Cell(25, 0, 'Tipo', 1, 0, 'C', 0, '', 0);
        $this->Cell(13, 0, 'QTY', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'U/M', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $numero = 1;
        foreach ($this->datos as $Row) {
            $parte = $Row['nro_parte'];
            $referencia = $Row['referencia'];
            $descripcion = $Row['descripcion'];
            $tipo= $Row['tipo'];
            $cantidad = $Row['cantidad_sol'];
            $unidad = $Row['unidad_medida'];

            $tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <td width="35'" align="center">$numero</td>
        <td width="124" align="center">$parte</td>
        <td width="124" align="center">$referencia</td>
        <td width="199" align="justify"> $descripcion</td>
         <td width="88" align="center">$tipo</td>
        <td width="46" align="center">$cantidad</td>
        <td width="42" align="center">$unidad</td>
    </tr>
  

</table>
EOD;
            $this->SetFont('times', '', 11);
            $this->writeHTML($tbl, false, false, false, false, '');
            $numero++;

        }


        $html = '
 <table cellspacing="0" cellpadding="1" border="1">

  <tr>
    <th colspan="2" style="text-align:justify"><b>Observaciones:</b> '.$this->datos[0]['observaciones_sol'].'</th>
  </tr>
</table>
';

        //var_dump($pagina);exit;
        $this->SetFont('times', '', 12);
        $this->writeHTML($html, true, false, false, false, '');
        $fecha_ab = 'Fecha: '.$this->datos[0]['fecha_fir'];
        $fecha_sol ='Fecha: '.$this->datos[0]['fecha_solicitud'];



        if ($this->datos[0]['estado'] != 'borrador') {

            $qr1 = $this->codigoQr('Funcionario Solicitante: ' . $this->datos[0]['desc_funcionario1'] . "\n" . 'Nro. Pedido: ' . $this->datos[0]['nro_tramite'] . "\n" . 'Tipo Solicitud: ' . $this->datos[0]['tipo_solicitud'] . "\n" ,'primera_firma');
            $fun =  $this->datos[0]['desc_funcionario1'];
            $fe_so =$fecha_sol;
        }


        if($this->datos[0]['estado'] != 'revision' and $this->datos[0]['estado'] != 'borrador') {
            $qr4 = $this->codigoQr('Encargado: '.$this->datos2[0]['visto_ag']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n",'cuarta_firma');
            $fab =  $this->datos2[0]['visto_ag'];
            $fe_ab = $fecha_ab;
        }
        $tbl = <<<EOD
        
        <table cellspacing="0" cellpadding="1" border="1">
        <tr>
        <td align="center" > <b>Solicitado Por</b></td>
        <td align="center" ><b>Revisado Por</b> </td>
        </tr>
        <tr>
           <td align="center" > 
           $fun
            <br><br>
            <img  style="width: 100px;" src="$qr1" alt="Logo">
            <br>
        </td>
        <td align="center" >
        $fab
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