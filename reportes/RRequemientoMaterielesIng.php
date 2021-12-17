<?php
require_once(dirname(__FILE__) . '/../../lib/tcpdf/tcpdf_barcodes_2d.php');
class RRequemientoMaterielesIng extends  ReportePDF {
    var $url_archivo;
    public $firma;
    function Header()
    {
        $this->Ln(8);
        $this->MultiCell(40, 25, '', 1, 'C', 0, '', '');
        $this->SetFontSize(15);
        $this->SetFont('', 'B');
        $this->MultiCell(105, 25, "\n" . 'REQUERIMIENTO DE MATERIALES' . "\n" . 'INGENIERÍA', 1, 'C', 0, '', '');
        $this->SetFont('times', '', 10);
        $this->MultiCell(0, 25, "\n" . 'R-OA-01' . "\n" . 'Rev. Original' . "\n" . '03/11/16', 1, 'C', 0, '', '');
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
    // Page footer
    function reporteRequerimiento()
    {
        /*$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);*/
        $this->SetFont('times', 'B', 11);
        $this->Cell(60, 7, ' Datos Generales', 1, 0, 'L', 0, '', 0);
        $this->SetFont('times', '', 11);
        $this->Cell(85, 7, ' Nro: ' . $this->datos[0]['justificacion'], 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 7, $this->datos[0]['nro_justificacion'], 1, 0, 'C', 0, '', 0);
        $this->ln();
        $this->SetFont('times', '', 11);
        $this->Cell(40, 0, 'Fecha: '.$this->datos[0]['fecha_solicitud'], 1, 0, 'C', 0, '', 0);
        $this->Cell(75, 0, 'Matrícula: '.$this->datos[0]['matri'], 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'Pedido N°: '.$this->datos[0]['nro_tramite'], 1, 1, 'C', 0, '', 0);
        $this->SetFont('times', 'B', 11);

        $fecha = date_create($this->datos[0]['fecha_soli']);
        $fecha_base = date_create('01-04-2019');

        if ($fecha >= $fecha_base) {
        $this->Cell(0, 7, 'Especificación Técnica Material a Solicitar', 1, 0, 'L', 0, '', 0);
        $tabla='<td width="130"  align="center"><b>Condición</b></td>';
        $condicion = $this->datos[0]['condicion'];
        $condicion_base='<td width="130"  align="center">'.$condicion.'</td>';
        $ancho1='<td width="130"  align="center">';
        $ancho2='<td width="178"  align="center">';
      }else{
        $this->Cell(0, 7, 'Material a Solicitar', 1, 0, 'L', 0, '', 0);
        $ancho1='<td width="220"  align="center">';
        $ancho2='<td width="220"  align="center">';
      }

        $this->ln();
        $this->SetFont('', 'B', 9);
        $this->Cell(5, 0, 'N°', 1, 0, 'C', 0, '', 0);
        $this->Cell(31, 0, 'Número de Parte', 1, 0, 'C', 0, '', 0);
        $this->Cell(31, 0, 'Núm. de Parte Alterno', 1, 0, 'C', 0, '', 0);
        $this->Cell(22, 0, 'Referencia', 1, 0, 'C', 0, '', 0);
        $this->Cell(61, 0, 'Descripción', 1, 0, 'C', 0, '', 0);
        $this->Cell(16, 0, 'Cantidad', 1, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'U/M', 1, 0, 'C', 0, '', 0);
        $this->ln();
        $numero = 1;
        $pagina = 0;
        if ($this->datos[0]['fecha_soli'] >= $this->datos[0]['fecha_salida']) {
        foreach ($this->datos as $Row) {
            $parte = $Row['nro_parte'];
            $parte_alterno = $Row['pn_cotizacion'];
            $referencia = $Row['referencia'];
            $descripcion = $Row['descripcion'];
            $cantidad = $Row['cantidad_sol'];
            $unidad = $Row['unidad_medida'];
            $tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1" >
    <tr>
        <td width="17.5"  align="center">$numero</td>
        <td width="110" align="center">$parte</td>
        <td width="110" align="center">$parte_alterno</td>
        <td width="78" align="center">$referencia</td>
        <td width="215.9" align="justify">$descripcion</td>
        <td width="56.9"  align="center">$cantidad</td>
        <td width="70.5"  align="center">$unidad</td>
    </tr>
</table>
EOD;
            $this->SetFont('times', '', 9);
            $this->writeHTML($tbl, false, false, false, false, '');
            $numero++;
            $pagina++;
        }
}else {
  foreach ($this->datos as $Row) {
      $parte = $Row['nro_parte'];
      $parte_alterno = $Row['nro_parte_alterno'];
      $referencia = $Row['referencia'];
      $descripcion = $Row['descripcion'];
      $cantidad = $Row['cantidad_sol'];
      $unidad = $Row['unidad_medida'];
      $tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1" >
<tr>
  <td width="17.5"  align="center">$numero</td>
  <td width="110" align="center">$parte</td>
  <td width="110" align="center">$parte_alterno</td>
  <td width="78" align="center">$referencia</td>
  <td width="215.9" align="justify">$descripcion</td>
  <td width="56.9"  align="center">$cantidad</td>
  <td width="70.5"  align="center">$unidad</td>
</tr>
</table>
EOD;
      $this->SetFont('times', '', 9);
      $this->writeHTML($tbl, false, false, false, false, '');
      $numero++;
      $pagina++;
  }
}

$tipo_de_adjudicacion = $this->datos[0]['tipo_de_adjudicacion'];
$metodo_de_adjudicación = $this->datos[0]['metodo_de_adjudicación'];

if ($this->datos[0]['fecha_soli'] >= $this->datos[0]['fecha_salida']) {
  if ($this->datos[0]['tipo_de_adjudicacion'] == 'Ninguno') {
    $tb2 = <<<EOD
<table cellspacing="0" cellpadding="1" border="1" >
<tr>
<td width="658"><b>Método de selección de adjudicación: </b>$metodo_de_adjudicación</td>
</tr>
</table>
EOD;

  } else {
    $tb2 = <<<EOD
<table cellspacing="0" cellpadding="1" border="1" >
<tr>
<td width="330"><b>Método de selección de adjudicación: </b>$metodo_de_adjudicación</td>
<td width="330"><b>Tipo de adjudicación: </b>$tipo_de_adjudicacion</td>
</tr>
</table>
EOD;
  }
}




        $this->SetFont('times', '', 9);
        $this->writeHTML($tb2, false, false, false, false, '');

        $this->ln();
        $motivo = $this->datos[0]['motivo_solicitud'];
        $justificacion= $this->datos[0]['justificacion'];
        $tipo_solicitud= $this->datos[0]['tipo_solicitud'];
        $fecha_requerida= $this->datos[0]['fecha_requerida'];
        $observaciones_sol = $this->datos[0]['observaciones_sol'];


        $html = <<<EOD
 <table cellspacing="0" cellpadding="1" border="1">
  <tr>
    <td colspan="2" > <b>Motivo de la Solicitud</b></td>
  </tr>
  <tr>
    <th colspan="2" style="text-align:justify">$motivo</th>
  </tr>
  <tr>
    <td width="220"  align="center" ><b>Justificación de Necesidad</b></td>
    $ancho1 <b>Tipo de Solicitud</b></td>
    $tabla
    $ancho2 <b>Fecha Requerida de Llegada</b></td>
  </tr>
  <tr>
    <td width="220"  align="center" >$justificacion</td>
    $ancho1 $tipo_solicitud</td>
    $condicion_base
    $ancho2 $fecha_requerida</td>
  </tr>
  <tr>
    <th colspan="4" style="text-align:justify"><b>Observaciones:</b> $observaciones_sol</th>
  </tr>
</table>
EOD;
        //var_dump($pagina);exit;
        $this->SetFont('times', '', 11);
        $this->writeHTML($html, true, false, false, false, '');
    /*    $this->firmas();
    }
    function firmas(){*/
        $funcionario_solicitante = $this->datos[0]['desc_funcionario1'];
        $fecha_ab = 'Fecha: '.$this->datos[0]['fecha_fir'];
        $Revisado_vb= $this->datos2[0]['visto_bueno'];
         $VB_DAC= $this->datos2[1]['funcionario_bv'];
        $esta = 'borrador';

        $fecha_sol ='Fecha: '.$this->datos[0]['fecha_solicitud'];
        $fecha_vb = 'Fecha: '. $this->datos2[0]['fecha_ini'];
        $fecha_dac = 'Fecha: '.$this->datos2[1]['fecha_ini'];

        //var_dump($this->datos3[0]); exit;

        if ($this->datos[0]['estado'] != 'borrador') {
            $qr1 = $this->codigoQr('Funcionario Solicitante: '.$this->datos[0]['desc_funcionario1']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud'],'primera_firma');
            $fun =  $this->datos[0]['desc_funcionario1'];
            $fe_so =$fecha_sol;
        }
        if($this->datos[0]['estado_firma'] != 'vobo_area' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr2 = $this->codigoQr('Encargado: '.$this->datos2[0]['visto_bueno']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n",'segunda_firma');
            $frev = $this->datos2[0]['visto_bueno'];
            $fe_vo=$fecha_vb;
        }
        if( $this->datos[0]['estado_firma'] != 'vobo_aeronavegabilidad' and $this->datos[0]['estado_firma'] != 'vobo_area' and $this->datos[0]['estado'] != 'borrador' ) {
            $qr3 = $this->codigoQr('Encargado: '.$this->datos2[0]['aero']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n",'tercera_firma');
            $dac =  $this->datos2[0]['aero'];
            $fe_dac=$fecha_dac;
        }
        if($this->datos[0]['estado'] != 'revision' and $this->datos[0]['estado'] != 'borrador') {
            $qr4 = $this->codigoQr('Encargado: '.$this->datos2[0]['visto_ag']."\n".'Nro. Pedido: '.$this->datos[0]['nro_tramite']."\n".'Tipo Solicitud: '.$this->datos[0]['tipo_solicitud']."\n",'cuarta_firma');
            $fab = $this->datos2[0]['visto_ag'];
            $fe_ab = $fecha_ab;
        }
        $tbl = <<<EOD

        <table cellspacing="0" cellpadding="1" border="1">
        <tr>
        <td align="center" > <b>Solicitado Por:</b> $fun</td>
        <td align="center" > <b>V.B. Encargado Materiales:</b> $frev</td>
        </tr>
        <tr>
           <td align="center" >
            <br><br>
            <img  style="width: 100px;" src="$qr1" alt="Logo">
            <br>
        </td>
        <td align="center" >
        <br><br>
            <img  style="width: 100px;" src="$qr2" alt="Logo">

         </td>
         </tr>
         <tr>
            	<td align="justify"  colspan="2"><b> Evaluado y Analizado por AOC - 121</b>
            	</td>
        </tr>
        </table>

EOD;
        $tbl2 = <<<EOD

        <table cellspacing="0" cellpadding="1" border="1">
         <tr>
        <td align="center" > <b>V.B. DAC:</b> $dac</td>
        <td align="center" > <b>Recibido:</b> $fab</td>
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

       /* if ($pagina >= 20 ){
            $this->setY($this->getY() + 30);
        }*/
        $this->SetFont('times', '', 11);
        $this->writeHTML($tbl, true, false, false, false, '');
        if ( $pagina >= 10 && $pagina <= 11){
            $this->setY($this->getY() + 30);
        }
        $this->writeHTML($tbl2, true, false, false, false, '');

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

    function setDatos($datos,$datos2) {
        $this->datos = $datos;
        $this->datos2 = $datos2;

    }
    function generarReporte() {
        $this->SetMargins(15,40,15);
        //$this->SetAutoPageBreak(true,500);

      //  $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->AddPage();
        $this->SetMargins(15,40,15);
        $this->reporteRequerimiento();

    }

}

?>
